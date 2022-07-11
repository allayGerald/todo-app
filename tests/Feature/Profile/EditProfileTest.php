<?php

use App\Models\User;

it('should restrict unauthenticated users', function ()
{
    $this->putJson('api/my-profile')->assertStatus(401);
});

it('should require email and name', function ()
{
    login()
        ->putJson('api/my-profile', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email' => 'required', 'name' => 'required']);
});

it('should restrict duplicate emails', function ()
{
    $user1 = User::factory()->create(['email' => 'foo@bar.com']);
    $user2 = User::factory()->create(['email' => 'bar@foo.com']);

    $this->actingAs($user1)->putJson('api/my-profile', [
        'email' => $user2->email,
        'name'  => $user1->name
    ])->assertStatus(422)->assertJsonValidationErrorFor('email');

    $profile = User::find($user1->id);
    expect($profile)->email->toEqual($user1->email);
});

it('it should allow user to edit their own profile', function ()
{
    $oldEmail = 'foo@bar.com';
    $oldName = 'Test User';
    $newEmail = 'test@example.com';
    $newName = 'New Name';

    $user = User::factory()->create(['email' => $oldEmail, 'name' => $oldName]);

    $this->actingAs($user)->putJson('api/my-profile', [
        'name'  => $newName,
        'email' => $newEmail
    ]);

    $updateProfile = User::find($user->id);
    expect($updateProfile)
        ->name->toEqual($newName)
        ->email->toEqual($newEmail);
});
