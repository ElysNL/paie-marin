<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('auth:prune-expired-tokens')]
#[Description('Nettoie les sessions orphelines, personal_access_tokens expirés et password_reset_tokens expirés.')]
class PruneExpiredTokens extends Command
{
    public function handle(): int
    {
        $total = 0;

        $orphanSessions = DB::table('sessions')
            ->whereNull('user_id')
            ->orWhereNotIn('user_id', fn ($q) => $q->select('id')->from('users'))
            ->delete();
        $total += $orphanSessions;
        $this->info("Sessions orphelines supprimées : {$orphanSessions}");

        // last_activity est un Unix timestamp (int), pas un datetime
        $expiredSessions = DB::table('sessions')
            ->where('last_activity', '<', (int) now()->subMinutes(
                (int) config('session.lifetime', 120)
            )->getTimestamp())
            ->delete();
        $total += $expiredSessions;
        $this->info("Sessions expirées supprimées : {$expiredSessions}");

        if (DB::getSchemaBuilder()->hasTable('personal_access_tokens')) {
            $expiredTokens = DB::table('personal_access_tokens')
                ->whereNotNull('expires_at')
                ->where('expires_at', '<', now())
                ->delete();
            $total += $expiredTokens;
            $this->info("Personal access tokens expirés supprimés : {$expiredTokens}");
        }

        if (DB::getSchemaBuilder()->hasTable('password_reset_tokens')) {
            $expiredResets = DB::table('password_reset_tokens')
                ->where('created_at', '<', now()->subMinutes(60))
                ->delete();
            $total += $expiredResets;
            $this->info("Password reset tokens expirés supprimés : {$expiredResets}");
        }

        $this->info("Total nettoyé : {$total} enregistrements");
        return self::SUCCESS;
    }
}
