<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class WarungScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        if (is_null($user->warung_id)) {
            return;
        }

        $builder->where($model->getTable() . '.warung_id', $user->warung_id);
    }
}
