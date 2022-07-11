<?php

use App\Models\Item;
use App\Models\User;

it('redirects unauthenticated users to login', function ()
{
    $this->postJson('api/items')->assertStatus(401);
});

it('should allow authenticated users to create todo item', function ()
{
    $user = User::factory()->create(['id' => 1]);
    $this->actingAs($user)->postJson('api/items', ['name' => 'Be happy']);

    $items = Item::get();
    expect($items)->toHaveCount(1)
                  ->and($items->first())
        ->id->toEqual($user->id);
});
