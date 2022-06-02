<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $offer_id = $request['offer_id'];
        $input=$request->allFiles();
        $image= '';
        foreach ($input as $imagefile) {
            $image = $imagefile->store('public/offers/'.$offer_id);
            Photo::create([
               'image' => $image,
              'offer_id' => $offer_id,
            ]);
        }
        DB::table('offers')->where('id',$offer_id)
            ->update([
                'image' => $image,
            ]);
        return response([
            'message' => 'Photos Added.'
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response(
            Photo::orderBy('created_at', 'desc')->where('offer_id', $id)
                ->select('image')->get()
        , 200);
    }


     public function destroy($id)
    {
        $images = Photo::where('offer_id', $id);
        $images->delete();
        Storage::disk('local')->deleteDirectory('/public/offers/'.$id);

        return response([
            'message' => 'photos Deleted'
        ], 200);;
    }


}
