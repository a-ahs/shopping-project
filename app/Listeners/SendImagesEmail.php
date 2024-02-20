<?php

namespace App\Listeners;

use App\Events\SendImages;
use App\Mail\SendOrderedImages;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendImagesEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendImages $event): void
    {
        Mail::to($event->user->email)->send(new SendOrderedImages($event->images, $event->user));
    }
}
