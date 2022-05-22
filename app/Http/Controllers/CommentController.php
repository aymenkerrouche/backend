<?php

namespace App\Http\Controllers;

use App\Models\comment;
use App\Models\Offer;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $offer = Offer::find($id);

        if(!$offer)
        {
            return response([
                'message' => 'Offer not found.'
            ], 403);
        }

        return response([
            'comments' => $offer->comments()->with('user:id,name,image')->get()
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $offer = Offer::find($id);

        if(!$offer)
        {
            return response([
                'message' => 'Offer not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);

        Comment::create([
            'comment' => $attrs['comment'],
            'offer_id' => $id,
            'user_id' => auth()->user()->id
        ]);

        return response([
            'message' => 'Comment created.'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ], 403);
        }

        if($comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 401);
        }

        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);

        $comment->update([
            'comment' => $attrs['comment']
        ]);

        return response([
            'message' => 'Comment updated.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ], 403);
        }

        if($comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 401);
        }

        $comment->delete();

        return response([
            'message' => 'Comment deleted.'
        ], 200);
    }
}
