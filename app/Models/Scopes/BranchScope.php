<?php

namespace App\Models\Scopes;

use App\Traits\DefaultAccessModelTrait;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class BranchScope implements Scope
{
    use DefaultAccessModelTrait;

    public function apply(Builder $builder, Model $model)
    {
        if (!App::runningInConsole() && Auth::check()) {
            $field = sprintf('%s.%s', $builder->getQuery()->from, 'branch_id');
            $builder->where($field, '=', $this->branch())->orWhere($field, '=', 0);
        }
    }
}
