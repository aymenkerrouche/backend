<?php

namespace App\Http\Controllers;

use App\Models\like;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    // like or unlike
    public function likeOrUnlike($id)
    {
        $offer = Offer::find($id);

        if(!$offer) {
            return response([ 'message' => 'Offer not found.'], 403);
        }

        $like = $offer->likes()->where('user_id', auth()->user()->id)->first();

        // if not liked then like
        if(!$like){
            Like::create([ 'offer_id' => $id, 'user_id' => auth()->user()->id]);
            return response(['message' => 'Liked'], 200);}

        // else dislike it
        $like->delete();
        return response([ 'message' => 'Disliked'], 200);
    }

    public function index()
    {
        $offers = Offer::with('user:id,name')->with('likes', function($like){
            return $like->where('user_id', auth()->user()->id)
                ->select('id', 'user_id', 'offer_id')->get();
            })->get();

        $likes = $offers->where('likes' ,'<>', '[]')->values();

        return  response($likes, 200);


    }

    public function Unlike($id)
    {
        $offer = Offer::find($id);

        if(!$offer)
        {
            return response([
                'offers' => 'Offer not found.'
            ], 403);
        }else{
            $like = $offer->likes()->where('user_id', auth()->user()->id)->first();

            $like->delete();
            return response([
                'offers' => 'Disliked'
            ], 200);
        }
    }
}
