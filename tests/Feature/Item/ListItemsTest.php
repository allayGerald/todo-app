<?php

use App\Models\Item;
use App\Models\User;

it('redirects unauthenticated users to login', function ()
{
    $this->getJson('api/items')->assertStatus(401);
});

it('should show only items belonging to the current user', function ()
{
    $user1 = User::factory()->create(['email' => 'foo@bar.com']);
    $user2 = User::factory()->create(['email' => 'bar@foo.com']);
// Insert multiple items for user1 & user2
    $user1Item = 'Book Hotel';
    $user2Item = 'Take out the trash';
    Item::insert([
            ['name' => $user1Item, 'user_id' => $user1->id],
            ['name' => $user2Item, 'user_id' => $user2->id],
        ]
    );

    $response = $this->actingAs($user1)->getJson('api/items'); // login as user1

    $response
        ->assertJsonFragment(['name' => $user1Item])
        ->assertJsonMissing(['name' => $user2Item]);
});
