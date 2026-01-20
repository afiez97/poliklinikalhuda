<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Services\AuditService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/departments')]
#[Middleware(['web', 'auth'])]
class DepartmentController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    #[Get('/', name: 'admin.departments.index')]
    public function index(Request $request)
    {
        $departments = Department::withCount('staff')
            ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->when($request->status !== null, fn ($q) => $q->where('is_active', $request->status === 'active'))
            ->orderBy('name')
            ->paginate(25);

        return view('admin.departments.index', compact('departments'));
    }

    #[Get('/create', name: 'admin.departments.create')]
    public function create()
    {
        return view('admin.departments.create');
    }

    #[Post('/', name: 'admin.departments.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:departments,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        try {
            $department = Department::create($validated);

            $this->auditService->log(
                'create',
                "Department created: {$department->name}",
                $department
            );

            return $this->successRedirect(
                'admin.departments.index',
                __('Jabatan berjaya ditambah.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to create department', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{department}/edit', name: 'admin.departments.edit')]
    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    #[Patch('/{department}', name: 'admin.departments.update')]
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'code' => "required|string|max:10|unique:departments,code,{$department->id}",
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        try {
            $oldData = $department->toArray();
            $department->update($validated);

            $this->auditService->log(
                'update',
                "Department updated: {$department->name}",
                $department,
                $oldData,
                $department->toArray()
            );

            return $this->successRedirect(
                'admin.departments.index',
                __('Jabatan berjaya dikemaskini.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to update department', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Delete('/{department}', name: 'admin.departments.destroy')]
    public function destroy(Department $department)
    {
        if ($department->staff()->count() > 0) {
            return $this->errorRedirect('Tidak boleh memadam jabatan yang mempunyai kakitangan.');
        }

        try {
            $name = $department->name;
            $department->delete();

            $this->auditService->log(
                'delete',
                "Department deleted: {$name}",
                null
            );

            return $this->successRedirect(
                'admin.departments.index',
                __('Jabatan berjaya dipadam.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to delete department', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }
}
