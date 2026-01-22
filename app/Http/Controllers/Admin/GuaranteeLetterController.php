<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuaranteeLetter;
use App\Models\Panel;
use App\Models\Patient;
use App\Services\GLService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/panel/gl')]
#[Middleware(['web', 'auth'])]
class GuaranteeLetterController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected GLService $glService
    ) {}

    #[Get('/', name: 'admin.panel.gl.index')]
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'panel_id' => $request->input('panel_id'),
            'status' => $request->input('status'),
            'verification_status' => $request->input('verification_status'),
            'expiring_soon' => $request->boolean('expiring_soon'),
        ];

        $guaranteeLetters = $this->glService->getGuaranteeLetters($filters);
        $panels = Panel::active()->orderBy('panel_name')->get();
        $statistics = $this->glService->getGLStatistics();

        return view('admin.panel.gl.index', compact('guaranteeLetters', 'panels', 'filters', 'statistics'));
    }

    #[Get('/create', name: 'admin.panel.gl.create')]
    public function create(Request $request)
    {
        $panels = Panel::active()->orderBy('panel_name')->get();
        $patient = $request->has('patient_id') ? Patient::find($request->patient_id) : null;

        return view('admin.panel.gl.create', compact('panels', 'patient'));
    }

    #[Post('/', name: 'admin.panel.gl.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gl_number' => ['nullable', 'string', 'max:100', 'unique:guarantee_letters,gl_number'],
            'panel_id' => ['required', 'exists:panels,id'],
            'patient_id' => ['required', 'exists:patients,id'],
            'panel_employee_id' => ['nullable', 'exists:panel_employees,id'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'coverage_limit' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
            'expiry_date' => ['required', 'date', 'after:effective_date'],
            'diagnoses_covered' => ['nullable', 'string'],
            'special_remarks' => ['nullable', 'string'],
            'verification_status' => ['nullable', 'in:pending,verified'],
        ]);

        try {
            $gl = $this->glService->createGuaranteeLetter($validated, auth()->id());

            return $this->successRedirect(
                'admin.panel.gl.show',
                __('Guarantee Letter berjaya dicipta.'),
                ['gl' => $gl->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{gl}', name: 'admin.panel.gl.show')]
    public function show(GuaranteeLetter $gl)
    {
        $gl->load(['panel', 'patient', 'employee', 'dependent', 'utilizations', 'claims', 'verifiedByUser']);

        return view('admin.panel.gl.show', compact('gl'));
    }

    #[Get('/{gl}/edit', name: 'admin.panel.gl.edit')]
    public function edit(GuaranteeLetter $gl)
    {
        $panels = Panel::active()->orderBy('panel_name')->get();

        return view('admin.panel.gl.edit', compact('gl', 'panels'));
    }

    #[Patch('/{gl}', name: 'admin.panel.gl.update')]
    public function update(Request $request, GuaranteeLetter $gl)
    {
        $validated = $request->validate([
            'panel_id' => ['required', 'exists:panels,id'],
            'panel_employee_id' => ['nullable', 'exists:panel_employees,id'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'coverage_limit' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
            'expiry_date' => ['required', 'date', 'after:effective_date'],
            'diagnoses_covered' => ['nullable', 'string'],
            'special_remarks' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,utilized,expired,cancelled'],
        ]);

        try {
            $this->glService->updateGuaranteeLetter($gl, $validated);

            return $this->successRedirect(
                'admin.panel.gl.show',
                __('Guarantee Letter berjaya dikemaskini.'),
                ['gl' => $gl->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Delete('/{gl}', name: 'admin.panel.gl.destroy')]
    public function destroy(GuaranteeLetter $gl)
    {
        try {
            $gl->delete();

            return $this->successRedirect(
                'admin.panel.gl.index',
                __('Guarantee Letter berjaya dipadam.')
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/{gl}/verify', name: 'admin.panel.gl.verify')]
    public function verify(Request $request, GuaranteeLetter $gl)
    {
        $validated = $request->validate([
            'verification_method' => ['required', 'in:system,phone,email,portal'],
            'verification_person' => ['nullable', 'string', 'max:255'],
            'verification_notes' => ['nullable', 'string'],
        ]);

        try {
            $this->glService->verifyGL(
                $gl,
                auth()->id(),
                $validated['verification_method'],
                $validated['verification_notes'] ?? null
            );

            if ($validated['verification_person']) {
                $gl->update(['verification_person' => $validated['verification_person']]);
            }

            return $this->successRedirect(
                'admin.panel.gl.show',
                __('Guarantee Letter berjaya disahkan.'),
                ['gl' => $gl->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/expiring', name: 'admin.panel.gl.expiring')]
    public function expiring()
    {
        $guaranteeLetters = $this->glService->getExpiringSoon(14);

        return view('admin.panel.gl.expiring', compact('guaranteeLetters'));
    }

    #[Get('/check-eligibility', name: 'admin.panel.gl.checkEligibility')]
    public function checkEligibilityForm()
    {
        $panels = Panel::active()->orderBy('panel_name')->get();

        return view('admin.panel.gl.check-eligibility', compact('panels'));
    }

    #[Post('/check-eligibility', name: 'admin.panel.gl.doCheckEligibility')]
    public function checkEligibility(Request $request)
    {
        $validated = $request->validate([
            'panel_id' => ['required', 'exists:panels,id'],
            'patient_id' => ['required', 'exists:patients,id'],
            'employee_id' => ['nullable', 'string'],
            'ic_number' => ['nullable', 'string'],
        ]);

        $result = $this->glService->checkEligibility(
            $validated['panel_id'],
            $validated['patient_id'],
            $validated['employee_id'] ?? null,
            $validated['ic_number'] ?? null,
            auth()->id()
        );

        $panels = Panel::active()->orderBy('panel_name')->get();

        return view('admin.panel.gl.check-eligibility', compact('panels', 'result'));
    }
}
