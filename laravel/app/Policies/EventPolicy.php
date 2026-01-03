<?php
// app/Policies/EventPolicy.php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function update(User $user, Event $event): bool
    {
        // Eigenaar kan bewerken
        if ($user->id === $event->user_id) {
            return true;
        }

        // Co-hosts kunnen ook bewerken (als je collaborators hebt)
        return $event->collaborators()
            ->where('user_id', $user->id)
            ->whereIn('role', ['owner', 'co_host'])
            ->exists();
    }

    public function delete(User $user, Event $event): bool
    {
        // Zelfde logica als update
        return $this->update($user, $event);
    }
}
