<?php

namespace App\Jobs;

use App\Models\VisitStatusLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogVisitStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $visit;
    /**
     * Create a new job instance.
     */
    public function __construct( $visit)
    {
        $this->visit = $visit;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        VisitStatusLog::query()->create([
            'visit_id' => $this->visit->id,
            'user_id' => auth()->id(),
            'status' => $this->visit->status,
        ]);
    }
}
