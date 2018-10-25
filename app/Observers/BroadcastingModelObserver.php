<?php

namespace App\Observers;

use App\Events\BroadcastingModelEvent;
use Illuminate\Database\Eloquent\Model;

class BroadcastingModelObserver
{
    public function created(Model $model)
    {
        event(new BroadcastingModelEvent($model, 'created'));
    }

    public function updated(Model $model)
    {
        event(new BroadcastingModelEvent($model, 'updated'));
    }

    public function saved(Model $model)
    {
        event(new BroadcastingModelEvent($model, 'saved'));
    }

    public function deleted(Model $model)
    {
        event(new BroadcastingModelEvent($model, 'deleted'));
    }
}
