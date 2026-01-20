<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/sessions')]
#[Middleware(['web', 'auth'])]
class SessionController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Display a listing of active sessions.
     */
    #[Get('/', name: 'admin.sessions.index')]
    public function index(Request $request)
    {
        $this->authorize('viewAny', 'session');

        $sessions = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'sessions.id',
                'sessions.user_id',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->whereNotNull('sessions.user_id')
            ->orderBy('sessions.last_activity', 'desc')
            ->paginate(25);

        // Parse user agents for display
        $sessions->getCollection()->transform(function ($session) {
            $session->last_activity_at = \Carbon\Carbon::createFromTimestamp($session->last_activity);
            $session->browser = $this->parseBrowser($session->user_agent);
            $session->is_current = $session->id === session()->getId();

            return $session;
        });

        $statistics = [
            'total_sessions' => DB::table('sessions')->whereNotNull('user_id')->count(),
            'unique_users' => DB::table('sessions')->whereNotNull('user_id')->distinct('user_id')->count('user_id'),
            'active_now' => DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
                ->count(),
        ];

        return view('admin.sessions.index', compact('sessions', 'statistics'));
    }

    /**
     * Display sessions for a specific user.
     */
    #[Get('/user/{userId}', name: 'admin.sessions.user')]
    public function userSessions(int $userId)
    {
        $this->authorize('view', 'session');

        $user = \App\Models\User::findOrFail($userId);

        $sessions = DB::table('sessions')
            ->where('user_id', $userId)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->last_activity_at = \Carbon\Carbon::createFromTimestamp($session->last_activity);
                $session->browser = $this->parseBrowser($session->user_agent);
                $session->is_current = $session->id === session()->getId();

                return $session;
            });

        return view('admin.sessions.user', compact('user', 'sessions'));
    }

    /**
     * Terminate a specific session.
     */
    #[Delete('/{sessionId}', name: 'admin.sessions.destroy')]
    public function destroy(string $sessionId)
    {
        $this->authorize('delete', 'session');

        // Prevent terminating own session
        if ($sessionId === session()->getId()) {
            return $this->errorRedirect('Tidak boleh menamatkan sesi semasa anda.');
        }

        try {
            $session = DB::table('sessions')->where('id', $sessionId)->first();

            if (! $session) {
                return $this->errorRedirect('Sesi tidak dijumpai.');
            }

            DB::table('sessions')->where('id', $sessionId)->delete();

            $this->auditService->log(
                'session_terminated',
                'Session terminated for user ID: '.$session->user_id,
                metadata: [
                    'session_id' => $sessionId,
                    'user_id' => $session->user_id,
                    'ip_address' => $session->ip_address,
                ]
            );

            return $this->successRedirect(
                'admin.sessions.index',
                __('Sesi berjaya ditamatkan.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to terminate session', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Terminate all sessions for a user.
     */
    #[Post('/user/{userId}/terminate-all', name: 'admin.sessions.terminateAll')]
    public function terminateAll(int $userId)
    {
        $this->authorize('delete', 'session');

        // Prevent terminating own sessions
        if ($userId === auth()->id()) {
            return $this->errorRedirect('Tidak boleh menamatkan semua sesi anda sendiri.');
        }

        try {
            $count = DB::table('sessions')
                ->where('user_id', $userId)
                ->delete();

            $user = \App\Models\User::find($userId);

            $this->auditService->log(
                'all_sessions_terminated',
                "All {$count} sessions terminated for user: ".($user?->name ?? $userId),
                metadata: [
                    'user_id' => $userId,
                    'session_count' => $count,
                ]
            );

            return $this->successRedirect(
                'admin.sessions.index',
                __(':count sesi berjaya ditamatkan.', ['count' => $count])
            );
        } catch (\Exception $e) {
            Log::error('Failed to terminate all sessions', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Terminate all sessions except current.
     */
    #[Post('/terminate-others', name: 'admin.sessions.terminateOthers')]
    public function terminateOthers()
    {
        try {
            $count = DB::table('sessions')
                ->where('user_id', auth()->id())
                ->where('id', '!=', session()->getId())
                ->delete();

            $this->auditService->log(
                'other_sessions_terminated',
                "Terminated {$count} other sessions",
                metadata: ['session_count' => $count]
            );

            return $this->successRedirect(
                'admin.sessions.index',
                __(':count sesi lain berjaya ditamatkan.', ['count' => $count])
            );
        } catch (\Exception $e) {
            Log::error('Failed to terminate other sessions', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Parse browser from user agent.
     */
    protected function parseBrowser(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'Unknown';
        }

        if (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        }
        if (str_contains($userAgent, 'Edg')) {
            return 'Edge';
        }
        if (str_contains($userAgent, 'Chrome')) {
            return 'Chrome';
        }
        if (str_contains($userAgent, 'Safari')) {
            return 'Safari';
        }
        if (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) {
            return 'Opera';
        }

        return 'Other';
    }
}
