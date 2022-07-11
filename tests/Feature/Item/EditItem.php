<?php

use App\Models\Item;
use App\Models\User;

it('redirects unauthenticated users to login', function ()
{
    $this->putJson('api/items/1')->assertStatus(401);
});

it('should allow users to edit their items', function ()
{
    $user = User::factory()->create();
    $item = Item::create(
        ['name' => 'lorem', 'user_id' => $user->id],
    );

    $this->actingAs($user)->putJson("api/items/{$item->id}", ['name' => 'edited'])->assertStatus(204);
    $this->assertDatabaseHas('items', [
        'name' => 'edited',
    ]);
});

it('should not allow users to edit other people items', function ()
{
    $user1 = User::factory()->create(['email' => 'foo@bar.com']);
    $user2 = User::factory()->create(['email' => 'bar@foo.com']);
// Insert multiple items for user1 & user2
    $user2ItemId = 2;
    Item::insert([
            ['name' => 'lorem', 'user_id' => $user1->id, 'id' => 1],
            ['name' => 'ipsum', 'user_id' => $user2->id, 'id' => $user2ItemId],
        ]
    );

    $this->actingAs($user1)->putJson("api/items/$user2ItemId", ['name' => 'edited'])->assertNotFound();
});
