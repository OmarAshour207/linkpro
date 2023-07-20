<?php

namespace App\Http\Controllers\Dashboard\Order;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'content'       => 'required|string',
            'ticket_id'     => 'required|numeric'
        ]);

        $data['user_id'] = auth()->user()->id;

        Comment::create($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->back();
    }
}
