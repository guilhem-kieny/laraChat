<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        /*$users = User::factory()->count(10)->create();*/
        /*$conversations = Conversation::factory(5)->create();
        $users = User::all();
        foreach ($conversations as $conversation) {
            $usersInConversation = $users->random(2);

            // Attachement des deux utilisateurs à la conversation
            $conversation->users()->attach($usersInConversation[0]);
            $conversation->users()->attach($usersInConversation[1]);
        }*/
       Message::factory(20)->create();
        /*$user = User::where('id', '=', 14)->first();
        $conversation = Conversation::where('id', '=', 43)->first();
        $user->conversations()->attach($conversation->id);*/
    }
}
