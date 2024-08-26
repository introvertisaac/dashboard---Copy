<?php

namespace App\Jobs;

use App\Models\Search;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LedgerLoggerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Search $search;

    /**
     * Create a new job instance.
     */
    public function __construct(Search $search)
    {
        //
        $this->search = $search;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
       $this->search->log_ledgers();



    }
}
