<?php

namespace App\Events;

use App\Models\OptionPackage;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OptionPackageWasUpdated extends Event
{
    use SerializesModels;

    public $building;

    /**
     * Create a new event instance.
     *
     * @param OptionPackage $optionPackage
     */
    public function __construct(OptionPackage $optionPackage)
    {
        $this->optionPackage = $optionPackage;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['optionPackage.' . $this->optionPackage->id];
    }
}
