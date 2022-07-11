<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

it('should restrict unauthenticated users', function ()
{
    $this->getJson('api/my-profile')->assertStatus(401);
});

it('shows only profile of the current logged in user', function ()
{
    $user1 = User::factory()->create(['email' => 'foo@bar.com']);
    $user2 = User::factory()->create(['email' => 'bar@foo.com']);

    $this
        ->actingAs($user1)
        ->getJson('api/my-profile')
        ->assertOk()
        ->assertJson(['name' => $user1->name, 'email' => $user1->email]);
});

it('does not show user password', function ()
{
    $user = User::factory()->create();

    $this->
    actingAs($user)
         ->getJson('api/my-profile')
         ->assertJson(fn(AssertableJson $json) => $json->where('email', $user->email)
                                                       ->where('name', $user->name)
                                                       ->missing('password')
                                                       ->etc()
         );
});
