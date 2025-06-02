<?php

namespace App\Http\Controllers;

use App\Models\Supermarket;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Menampilkan ulasan untuk supermarket tertentu
    public function index($supermarketId)
    {
        $supermarket = Supermarket::findOrFail($supermarketId);
        $reviews = $supermarket->reviews()->latest()->get();

        return view('reviews.index', compact('supermarket', 'reviews'));
    }

    // Membuat ulasan baru
    public function store(Request $request, $supermarketId)
    {
        $this->validate($request, [
            'content' => 'required|string|max:500'
        ]);

        $supermarket = Supermarket::findOrFail($supermarketId);

        $review = Review::create([
            'user_id' => Auth::id(),
            'supermarket_id' => $supermarket->id,
            'content' => $request->content,
            'upvotes' => 0,
            'downvotes' => 0
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil dibuat');
    }

    // Voting ulasan
    public function vote(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $voteType = $request->input('vote_type');

        if ($voteType === 'upvote') {
            $review->upvotes++;
        } elseif ($voteType === 'downvote') {
            $review->downvotes++;
        }

        $review->save();

        return redirect()->back()->with('success', 'Vote berhasil');
    }

    // Menghapus ulasan (hanya oleh pemilik ulasan)
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Pastikan hanya pemilik ulasan yang bisa menghapus
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Ulasan berhasil dihapus');
    }
}
