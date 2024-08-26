<?php

namespace App\Console\Commands;

use App\Models\Ledger;
use App\Models\Search;
use Illuminate\Console\Command;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        Ledger::truncate();
        //$searches = Search::whereIn('id', [336])->where('search_type', 'plate')->orderBy('id', 'ASC')->get();
        $searches = Search::whereNull('ledgered_at')->orderBy('id', 'ASC')->get();
        //$searches = Search::orderBy('id','ASC')->get();
        foreach ($searches as $search) {
            $search->log_ledgers();
        }


    }
}
