<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait IsActive
{
    /**
     * Scope a query to only include active items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', 1)->where('is_delete', 0);
    }

    /**
     * Scope a query to only include inactive items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive(Builder $query)
    {
        return $query->where('is_active', 0);
    }

    /**
     * Mark the model as active.
     *
     * @return bool
     */
    public function activate()
    {
        $this->is_active = true;
        return $this->save();
    }

    /**
     * Mark the model as inactive.
     *
     * @return bool
     */
    public function deactivate()
    {
        $this->is_active = false;
        return $this->save();
    }
}
