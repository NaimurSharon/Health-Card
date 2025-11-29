<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VideoConsultation;
use Carbon\Carbon;

class CleanStaleCallParticipants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:clean-stale-participants {--ttl=30 : TTL in seconds to consider a participant stale}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove stale participants from VideoConsultation.call_metadata based on TTL';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ttl = (int) $this->option('ttl');
        $this->info("Cleaning stale participants older than {$ttl} seconds...");

        $now = Carbon::now();
        $consultations = VideoConsultation::whereNotNull('call_metadata')->get();
        $removedTotal = 0;

        foreach ($consultations as $consultation) {
            $meta = $consultation->call_metadata ?? [];
            $participants = $meta['participants'] ?? [];
            $kept = [];
            $removed = 0;

            foreach ($participants as $p) {
                // participants may have 'last_seen' or 'joined_at'
                $timestamp = null;
                if (!empty($p['last_seen'])) {
                    $timestamp = Carbon::parse($p['last_seen']);
                } elseif (!empty($p['joined_at'])) {
                    $timestamp = Carbon::parse($p['joined_at']);
                }

                if ($timestamp === null) {
                    // if no timestamp, be conservative and keep
                    $kept[] = $p;
                    continue;
                }

                $age = $now->diffInSeconds($timestamp);
                if ($age <= $ttl) {
                    $kept[] = $p;
                } else {
                    $removed++;
                }
            }

            if ($removed > 0) {
                $meta['participants'] = array_values($kept);
                $consultation->call_metadata = $meta;
                $consultation->save();
                $this->info("Consultation {$consultation->id}: removed {$removed} stale participants");
                $removedTotal += $removed;
            }
        }

        $this->info("Done. Total removed: {$removedTotal}");
        return 0;
    }
}
