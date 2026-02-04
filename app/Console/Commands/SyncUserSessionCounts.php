<?php

namespace App\Console\Commands;

use App\Models\UserActiveSession;
use App\Services\SessionManager;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncUserSessionCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:sync-counts
                            {--cleanup : Also cleanup inactive sessions}
                            {--inactive-minutes=120 : Minutes of inactivity before a session is considered expired}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize user logged_count with actual active sessions and optionally cleanup inactive sessions';

    protected $sessionManager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->sessionManager = new SessionManager();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting session count synchronization...');

        $cleanupInactive = $this->option('cleanup');
        $inactiveMinutes = (int) $this->option('inactive-minutes');

        // Step 1: Cleanup inactive sessions if requested
        if ($cleanupInactive) {
            $this->info("Cleaning up sessions inactive for more than {$inactiveMinutes} minutes...");
            $deletedCount = $this->sessionManager->cleanupInactiveSessions($inactiveMinutes);
            $this->info("✓ Cleaned up {$deletedCount} inactive sessions");
        }

        // Step 2: Get actual session counts from database
        $sessionCounts = UserActiveSession::select('user_id', DB::raw('COUNT(*) as session_count'))
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        // Step 3: Get all users who have logged_count > 0 or have active sessions
        $userIds = User::where('logged_count', '>', 0)
            ->orWhereIn('id', $sessionCounts->keys())
            ->pluck('id');

        $this->info("Processing {$userIds->count()} users...");

        $correctedCount = 0;
        $bar = $this->output->createProgressBar($userIds->count());
        $bar->start();

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if (!$user) {
                $bar->advance();
                continue;
            }

            // Get actual session count (0 if user has no sessions)
            $actualCount = $sessionCounts->get($userId)?->session_count ?? 0;

            // Update if counts don't match
            if ($user->logged_count != $actualCount) {
                $user->update(['logged_count' => $actualCount]);
                $correctedCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✓ Corrected {$correctedCount} user session counts");
        $this->info('Session synchronization completed successfully!');

        return 0;
    }
}
