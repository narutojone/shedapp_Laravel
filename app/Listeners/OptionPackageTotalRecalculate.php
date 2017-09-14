<?php

namespace App\Listeners;

use App\Events\OptionPackageWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OptionPackageTotalRecalculate
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param OptionPackageWasUpdated $event
     */
    public function handle(OptionPackageWasUpdated $event)
    {
        $optionPackage = $event->optionPackage;

        $total_price = 0;
        // Update totals
        $optionPackage->options->each(function($item) use (&$total_price) {
            $total_price += $item->pivot->unit_price;
        });
        $optionPackage->total_price = $total_price;
        $optionPackage->save();
    }
}
