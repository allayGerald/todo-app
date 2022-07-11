<?php

use App\Models\Item;
use App\Models\User;

it('redirects unauthenticated users to login', function ()
{
    $this->deleteJson('api/items/1')->assertStatus(401);
});

it('should allow users to delete their items', function ()
{
    $user = User::factory()->create();
    $item = Item::create(
        ['name' => 'lorem', 'user_id' => $user->id],
    );

    $this->actingAs($user)->deleteJson("api/items/{$item->id}")->assertStatus(204);
    $itemsCount = Item::count();
    expect($itemsCount)->toEqual(0);
});

it('should not allow users to delete other people items', function ()
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

    $this->actingAs($user1)->deleteJson("api/items/$user2ItemId")->assertNotFound();
});
