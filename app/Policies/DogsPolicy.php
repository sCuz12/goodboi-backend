<?php

namespace App\Policies;

use App\Models\Dogs;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DogsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the the dog listing information.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dogs  $dogs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function edit(User $user, Dogs $dogs)
    {
        return $user->shelter->id === $dogs->shelter_id;
    }

    /**
     * Determine whether the user is shelter type and can create dog listing
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isShelter();
    }

    /**
     * Determine whether the user can update the Dog listing.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dogs  $dogs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Dogs $dogs)
    {
        return $user->shelter->id == $dogs->shelter_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dogs  $dogs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Dogs $dogs)
    {
        return $user->shelter->id === $dogs->shelter_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dogs  $dogs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Dogs $dogs)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dogs  $dogs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Dogs $dogs)
    {
        //
    }

    /**
     * Determine whether the user can delete the dog listing
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dogs  $dogs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteLostDog(User $user, Dogs $dogs)
    {
        return $user->id === $dogs->user_id;
    }
    /**
     * Determine whether the user can see the edit info
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dogs  $dogs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function showEditLostDog(User $user, Dogs $dogs)
    {
        return $user->id === $dogs->user_id;
    }
}
