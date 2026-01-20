<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/audit')]
#[Middleware(['web', 'auth'])]
class AuditLogController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Display a listing of audit logs.
     */
    #[Get('/', name: 'admin.audit.index')]
    public function index(Request $request)
    {
        $this->authorize('viewAny', \App\Models\AuditLog::class);

        $filters = [
            'user_id' => $request->input('user_id'),
            'action' => $request->input('action'),
            'auditable_type' => $request->input('auditable_type'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'ip_address' => $request->input('ip_address'),
        ];

        $logs = $this->auditService->getFiltered(
            filters: array_filter($filters),
            perPage: $request->input('per_page', 25)
        );

        $actions = config('security.audit.logged_actions', []);
        $users = \App\Models\User::select('id', 'name')->orderBy('name')->get();

        return view('admin.audit.index', compact('logs', 'filters', 'actions', 'users'));
    }

    /**
     * Display the specified audit log.
     */
    #[Get('/{auditLog}', name: 'admin.audit.show')]
    public function show(\App\Models\AuditLog $auditLog)
    {
        $this->authorize('view', $auditLog);

        return view('admin.audit.show', compact('auditLog'));
    }

    /**
     * Export audit logs.
     */
    #[Get('/export', name: 'admin.audit.export')]
    public function export(Request $request)
    {
        $this->authorize('export', \App\Models\AuditLog::class);

        $filters = [
            'user_id' => $request->input('user_id'),
            'action' => $request->input('action'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];

        $logs = $this->auditService->getFiltered(
            filters: array_filter($filters),
            perPage: 10000
        );

        $filename = 'audit_logs_'.now()->format('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID',
                'Tarikh/Masa',
                'Pengguna',
                'Tindakan',
                'Jenis',
                'ID Rekod',
                'Alamat IP',
                'Peranti',
                'Penerangan',
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user?->name ?? 'Sistem',
                    $log->action,
                    $log->auditable_type ? class_basename($log->auditable_type) : '-',
                    $log->auditable_id ?? '-',
                    $log->ip_address ?? '-',
                    $log->user_agent ?? '-',
                    $log->description,
                ]);
            }

            fclose($file);
        };

        $this->auditService->log('export', 'Audit logs exported');

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display audit statistics.
     */
    #[Get('/statistics', name: 'admin.audit.statistics')]
    public function statistics()
    {
        $this->authorize('viewStatistics', \App\Models\AuditLog::class);

        $statistics = $this->auditService->getStatistics();

        return view('admin.audit.statistics', compact('statistics'));
    }
}
