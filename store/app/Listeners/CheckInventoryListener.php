<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckInventoryListener
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
    public function handle(ProductCreated $event): void
    {
        $product = $event->product;

        // Check inventory logic here
        $isInventory = $product->quantity > 0; // Your inventory checking logic here (e.g., querying a database)

        // Update the is_inventory attribute if the product is in inventory
        if ($isInventory) {
            $product->update(['is_inventory' => 1]);
        }
        
    }
}
