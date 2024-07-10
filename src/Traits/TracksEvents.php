<?php

namespace Safemood\Workflow\Traits;

use Illuminate\Support\Facades\Event;

trait TracksEvents
{
    protected $trackedEvents = [];

    /**
     * Register event listeners to track all events.
     *
     * @return void
     */
    protected function trackAllEvents()
    {
        Event::listen('*', function ($eventName, $payload) {
            $this->recordTrackedEvent($eventName, $payload);
        });
    }

    /**
     * Register event listeners to track events within a specific namespace.
     *
     * @param  string  $namespace  Namespace of the events to track
     * @return void
     */
    protected function trackEventsIn(string $namespace)
    {
        Event::listen('*', function ($eventName, $payload) use ($namespace) {
            if (strpos($eventName, $namespace) === 0) {
                $this->recordTrackedEvent($eventName, $payload);
            }
        });
    }

    /**
     * Register event listeners to track specified events.
     *
     * @param array $events Event names or classes to track
     * @return void
     */
    protected function trackEvents(array $events)
    {
        foreach ($events as $event) {
            Event::listen($event, function ($payload) use ($event) {
                $this->recordTrackedEvent($event, $payload);
            });
        }
    }

    /**
     * Record a tracked event.
     *
     * @param  mixed  $payload
     * @return void
     */
    protected function recordTrackedEvent(string $eventName, $payload)
    {
        $this->trackedEvents[] = [
            'event' => $eventName,
            'payload' => $payload,
            'timestamp' => now()->toDateTimeLocalString(),
        ];
    }

    /**
     * Get all tracked events.
     *
     * @return array
     */
    public function trackedEvents()
    {
        return $this->trackedEvents;
    }

    /**
     * Clear all tracked events.
     *
     * @return void
     */
    public function clearTrackedEvents()
    {
        $this->trackedEvents = [];
    }
}
