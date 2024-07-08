<?php

namespace Safemood\Workflow\Traits;

use Illuminate\Support\Facades\Event;

trait RegistersObservers
{
    protected $observers = [];

    /**
     * Register observers for the given models.
     *
     * @param  array  $observers
     *                            Example: ['App\Models\User' => 'App\Observers\UserObserver', 'App\Models\Post' => 'App\Observers\PostObserver']
     * @return void
     */
    public function registerObservers(array $observers)
    {
        foreach ($observers as $model => $observer) {
            $this->registerObserver($model, $observer);
        }
    }

    /**
     * Register an observer for a single model.
     *
     * @return void
     */
    public function registerObserver(string $model, string $observer)
    {
        if (! isset($this->observers[$model])) {
            $this->observers[$model] = $observer;
        }
    }

    /**
     * Get all registered observers.
     *
     * @return array
     */
    public function getObservers()
    {
        return $this->observers;
    }

    /**
     * Register the observers with the framework.
     *
     * @return void
     */
    public function bootObservers()
    {

        foreach ($this->observers as $model => $observer) {
            Event::listen('eloquent.booted: '.$model, function () use ($model, $observer) {
                $model::observe($observer);
            });
        }

    }

    /**
     * Boot observers if any have been registered.
     *
     * @return void
     */
    protected function bootObserversIfNeeded()
    {
        if (! empty($this->observers)) {
            $this->bootObservers();
        }
    }
}
