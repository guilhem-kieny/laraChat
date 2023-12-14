<?php

namespace App\Observers;

use App\Models\Conversation;

class ConversationObserver
{
    /**
     * Handle the Conversation "created" event.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return void
     */
    public function created(Conversation $conversation)
    {
        //
    }

    /**
     * Handle the Conversation "updated" event.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return void
     */
    public function updated(Conversation $conversation)
    {
        //
    }

    /**
     * Handle the Conversation "deleted" event.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return void
     */
    public function deleted(Conversation $conversation)
    {
        //Supprime la liaison avec user et les messages associés à la conversation supprimé
        $conversation->users()->detach();
        $conversation->messages()->delete();
    }

    /**
     * Handle the Conversation "restored" event.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return void
     */
    public function restored(Conversation $conversation)
    {
        //
    }

    /**
     * Handle the Conversation "force deleted" event.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return void
     */
    public function forceDeleted(Conversation $conversation)
    {
        //
    }
}
