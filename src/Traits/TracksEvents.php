<?php

namespace Safemood\Workflow\Traits;

use Illuminate\Support\Facades\Event;

trait TracksEvents
{
    protected $trackedEvents = [];

    /**
     * Register event listeners to track specified events or namespaces.
     *
     * @param  array|string  $events  Event names or namespace patterns to track
     * @return void
     */
    protected function trackEvents($events)
    {
        if (is_string($events)) {
            $events = [$events];
        }

        foreach ($events as $event) {
            if (strpos($event, '\\') !== false) {
                // Track events in a specific namespace
                $this->trackEventsInNamespace($event);
            } else {
                // Track a specific event name
                Event::listen($event, function ($eventName, $payload) {
                    $this->recordTrackedEvent($eventName, $payload);
                });
            }
        }
    }

    /**
     * Track all events within a specific namespace.
     *
     * @param  string  $namespace  Namespace of the events to track
     * @return void
     */
    protected function trackEventsInNamespace(string $namespace)
    {
        Event::listen('*', function ($eventName, $payload) use ($namespace) {

            if (strpos($eventName, $namespace) === 0) {
                $this->recordTrackedEvent($eventName, $payload);
            }
        });

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
    public function getTrackedEvents()
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
