<?php

namespace App\Observers;

use App\Models\User;
use App\Traits\DefaultAccessModelTrait;

class UserObserver
{
    use DefaultAccessModelTrait;

    public function creating(User $user)
    {
        $user->branch_id = $this->setBranch($user->branch_id);
    }

    public function updating(User $user)
    {
        $user->branch_id = $this->setBranch($user->branch_id);
    }
}
