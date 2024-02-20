<?php

namespace App\Jobs;
use App\Mail\SendMail;
use App\Mail\ItemsCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use Illuminate\Support\Facades\Bus;

class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        
        // Create a batch of jobs
        $batch = Bus::batch([
            new SendMail(),
            new ItemsCreated(),     
        ])->dispatch();

        // Log batch information
        $batchId = $batch->id;
        $totalJobs = $batch->jobs->count();
        \Log::info("Batch ID: {$batchId}. Total jobs: {$totalJobs}");
    }
}
