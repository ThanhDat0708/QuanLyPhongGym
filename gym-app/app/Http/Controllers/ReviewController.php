<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trainer_id' => ['required', 'exists:trainers,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'user_id' => $request->user()->id,
            'trainer_id' => $validated['trainer_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('home')->with('success', 'Cam on ban da gui danh gia.');
    }
}
