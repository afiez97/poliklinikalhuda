<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Services\AuditService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/settings')]
#[Middleware(['web', 'auth'])]
class SettingController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Display system settings.
     */
    #[Get('/', name: 'admin.settings.index')]
    public function index()
    {
        $this->authorize('viewAny', SystemSetting::class);

        $settings = SystemSetting::all()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Display general settings.
     */
    #[Get('/general', name: 'admin.settings.general')]
    public function general()
    {
        $this->authorize('update', SystemSetting::class);

        $settings = SystemSetting::where('group', 'general')->get()->keyBy('key');

        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Display clinic settings.
     */
    #[Get('/clinic', name: 'admin.settings.clinic')]
    public function clinic()
    {
        $this->authorize('update', SystemSetting::class);

        $settings = SystemSetting::where('group', 'clinic')->get()->keyBy('key');

        return view('admin.settings.clinic', compact('settings'));
    }

    /**
     * Display security settings.
     */
    #[Get('/security', name: 'admin.settings.security')]
    public function security()
    {
        $this->authorize('update', SystemSetting::class);

        $settings = SystemSetting::where('group', 'security')->get()->keyBy('key');

        return view('admin.settings.security', compact('settings'));
    }

    /**
     * Display notification settings.
     */
    #[Get('/notifications', name: 'admin.settings.notifications')]
    public function notifications()
    {
        $this->authorize('update', SystemSetting::class);

        $settings = SystemSetting::where('group', 'notifications')->get()->keyBy('key');

        return view('admin.settings.notifications', compact('settings'));
    }

    /**
     * Update settings.
     */
    #[Patch('/', name: 'admin.settings.update')]
    public function update(Request $request)
    {
        $this->authorize('update', SystemSetting::class);

        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string'],
            'settings.*.value' => ['nullable'],
        ]);

        try {
            $updated = [];

            foreach ($validated['settings'] as $setting) {
                $record = SystemSetting::where('key', $setting['key'])->first();

                if ($record) {
                    $oldValue = $record->value;
                    $record->update(['value' => $setting['value']]);
                    $updated[$setting['key']] = [
                        'old' => $oldValue,
                        'new' => $setting['value'],
                    ];
                }
            }

            // Clear settings cache
            Cache::forget('system_settings');

            $this->auditService->log('update', 'System settings updated', metadata: $updated);

            return $this->successRedirect(
                'admin.settings.index',
                __('Tetapan berjaya dikemaskini.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to update settings', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Initialize default settings.
     */
    #[Get('/initialize', name: 'admin.settings.initialize')]
    public function initialize()
    {
        $this->authorize('create', SystemSetting::class);

        $defaults = config('security.default_settings', []);
        $created = 0;

        foreach ($defaults as $group => $settings) {
            foreach ($settings as $key => $config) {
                SystemSetting::firstOrCreate(
                    ['key' => $key],
                    [
                        'value' => $config['default'] ?? null,
                        'type' => $config['type'] ?? 'string',
                        'group' => $group,
                        'description' => $config['description'] ?? null,
                        'is_public' => $config['is_public'] ?? false,
                    ]
                );
                $created++;
            }
        }

        $this->auditService->log('initialize', "Initialized {$created} default settings");

        return $this->successRedirect(
            'admin.settings.index',
            __(':count tetapan lalai telah dimulakan.', ['count' => $created])
        );
    }
}
