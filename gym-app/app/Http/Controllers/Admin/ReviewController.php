<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews.index', [
            'reviews' => Review::with(['user', 'trainer.user'])->latest()->get(),
            'users' => User::orderBy('name')->get(),
            'trainers' => Trainer::with('user')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.reviews.create', [
            'users' => User::orderBy('name')->get(),
            'trainers' => Trainer::with('user')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'trainer_id' => ['required', 'exists:trainers,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create($validated);

        return back()->with('success', 'Da tao danh gia.');
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review->update($validated);

        return back()->with('success', 'Da cap nhat danh gia.');
    }

    public function edit(Review $review)
    {
        $review->load(['user', 'trainer.user']);

        return view('admin.reviews.edit', [
            'review' => $review,
            'users' => User::orderBy('name')->get(),
            'trainers' => Trainer::with('user')->get(),
        ]);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Da xoa danh gia.');
    }
}
