<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VideoConsultation;
use Carbon\Carbon;

class SeedStaleCallParticipants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:seed-stale-participants {--count=1 : Number of consultations to seed} {--ttl=60 : How many seconds in the past the joined_at should be}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed consultations with stale participants for testing cleanup';

    public function handle()
    {
        $count = (int) $this->option('count');
        $ttl = (int) $this->option('ttl');

        $this->info("Seeding {$count} consultations with stale participants (joined_at set to {$ttl} seconds ago)...");

        $consultations = VideoConsultation::limit($count)->get();
        if ($consultations->isEmpty()) {
            $this->warn('No consultations found to seed.');
            return 0;
        }

        foreach ($consultations as $consultation) {
            $meta = $consultation->call_metadata ?? [];
            if (!isset($meta['participants']) || !is_array($meta['participants'])) {
                $meta['participants'] = [];
            }

            $sessionId = 'stale-' . uniqid();
            $stale = [
                'sessionId' => $sessionId,
                'user' => [
                    'id' => 'seed-test',
                    'name' => 'Stale Participant',
                    'role' => 'tester'
                ],
                'joined_at' => Carbon::now()->subSeconds($ttl)->toISOString(),
                'last_seen' => Carbon::now()->subSeconds($ttl)->toISOString(),
            ];

            $meta['participants'][] = $stale;
            $consultation->call_metadata = $meta;
            $consultation->save();

            $this->info("Seeded consultation id={$consultation->id} with stale participant {$sessionId}");
        }

        $this->info('Seeding complete.');
        return 0;
    }
}
