<?php

namespace App\Http\Controllers;

use App\Models\photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]
     */
    public function store(Request $request)
    {
        $offer_id = $request['offer_id'];
        $input=$request->allFiles();
        foreach ($input as $imagefile) {
            $image = $imagefile->store('public/offers/'.$offer_id);
            Photo::create([
               'image' => $image,
              'offer_id' => $offer_id,
            ]);
        }

        return $input;
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(photo $photo)
    {
        //
    }
}
