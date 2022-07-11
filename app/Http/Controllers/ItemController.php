<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display todo items for current user.
     *
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $userId = auth()->user()->id;

        $items = Item::where('user_id', '=', $userId)->latest()->get();

        return response()->json($items);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
       $item = Item::create(['name' => $request->name, 'user_id' => Auth::id(), 'completed_at' => null]);

       return response()->json($item, 201);
    }

    public function update(Request $request, int $itemId): \Illuminate\Http\JsonResponse
    {
        $item = Item::where(['user_id' => Auth::id(), 'id' => $itemId])->firstOrFail();

        $data = [];
        $data['completed_at'] = $request->is_complete ? now() : null;
        $data['name'] = $request->name ?: $item->name;

        $item->update($data);
        return response()->json(null, 204);
    }

    public function destroy(int $itemId): \Illuminate\Http\JsonResponse
    {
        $item = Item::where(['user_id' => Auth::id(), 'id' => $itemId])->firstOrFail();

        $item->delete();
        return response()->json(null, 204);
    }
}
