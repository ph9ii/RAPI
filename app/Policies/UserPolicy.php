<?php

namespace App\Policies;

use App\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $authUser
     * @return mixed
     */
    public function view(User $user, User $authUser)
    {
        return $user->id === $authUser->id;
    }

    

    /**
     * Determine whether the user can update the authUser.
     *
     * @param  \App\User  $user
     * @param  \App\User  $authUser
     * @return mixed
     */
    public function update(User $user, User $authUser)
    {
        return $user->id === $authUser->id;
    }

    /**
     * Determine whether the user can delete the authUser.
     *
     * @param  \App\User  $user
     * @param  \App\User  $authUser
     * @return mixed
     */
    public function delete(User $user, User $authUser)
    {
        return $user->id === $authUser->id && $authUser->token()->client->personal_access_client;
    }
}
