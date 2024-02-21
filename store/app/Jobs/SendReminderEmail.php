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

    public function handle(): void
    {
        $batchId = uniqid(); // Correct the function name
        
        Bus::batch([
            new SendMail('najus777@gmail.com', $batchId),
            new ItemsCreated('najus777@gmail.com', $batchId),
        ])->dispatch();
    }
}
