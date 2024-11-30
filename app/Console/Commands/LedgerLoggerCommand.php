<?php
namespace App\Console\Commands;
use App\Models\Ledger;
use App\Models\Search;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LedgerLoggerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ledger-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process unledgered searches and create ledger entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $searches = Search::whereNull('ledgered_at')->orderBy('id', 'ASC')->get();
            
            $count = $searches->count();
            Log::info("LedgerLogger: Processing {$count} unledgered searches");
            $this->info("Processing {$count} unledgered searches");

            foreach ($searches as $search) {
                try {
                    $search->log_ledgers();
                    Log::info("LedgerLogger: Created ledger entries for search ID: {$search->id}");
                    $this->info("Created ledger entries for search ID: {$search->id}");
                } catch (\Exception $e) {
                    Log::error("LedgerLogger: Error processing search ID {$search->id}: {$e->getMessage()}");
                    $this->error("Error processing search ID {$search->id}: {$e->getMessage()}");
                }
            }

            Log::info('LedgerLogger: Processing complete');
            $this->info('Processing complete');
            
        } catch (\Exception $e) {
            Log::error("LedgerLogger: Main process error: {$e->getMessage()}");
            $this->error("Main process error: {$e->getMessage()}");
        }
    }
}
