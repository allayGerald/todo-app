<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProfileRequest;

class UserProfileController extends Controller
{
    public function show(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user()->only('id', 'email', 'name');
        return response()->json($user);
    }

    public function update(EditProfileRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        $user->email = $request->email;
        $user->name = $request->name;
        $user->save();

        return response()->json($user->only('id', 'email', 'name'));
    }
}
