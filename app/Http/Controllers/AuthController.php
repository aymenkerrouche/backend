<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'name' =>'required',
            'email' =>'required|email',
            'password' =>'required',
            'type' =>'required',
        ]);

        // Check if user already exists
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response('The provided email already exists.', 403);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);


        $user = User::create($input);

        $response['token'] = $user->createToken($request->email)->plainTextToken;
        $response['user'] = $user;
        return response(json_encode($response), 201);
    }

    public function login(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request['email'])->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response('The provided credentials are incorrect.', 403);
        }

        $response['token'] =  $user->createToken($request->email)->plainTextToken;
        $response['user'] = $user;
        return response(json_encode($response));
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logout success.'
        ], 200);
    }

    // get user details
    public function user()
    {
        $user = auth()->user();
        return response([
            'user' => $user
        ], 200);
    }

    //update user
    public function update(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if ($request['password'] != null)
        {

            $request['password'] = Hash::make($request['password']);
            auth()->user()->update($request->all());
        }
        elseif ($user) {
            return response('The provided email already exists.', 403);
        }
        else{
            if ($request['name'] == null){
                auth()->user()->update([
                    'email' => $request['email'],
                ]);
           }elseif($request['email'] == null){
                auth()->user()->update([
                    'name' => $request['name'],
                ]);
            }else{
                auth()->user()->update([
                    'name' => $request['name'],
                    'email' => $request['email'],
                ]);
            }
        }

         return response([
             'message' => 'Profile updated.',
             'user' => auth()->user()
         ], 200);
    }


    public function userimage()
    {
        $user = auth()->user();
        $user_image = $user['image'];
        return response([
            'user' => $user_image
        ],200);
    }


    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {

            $originalImage = $request->file('image');
            $image = Storage::disk('local')->put('public/users', $originalImage, 'public');

            DB::table('users')->where('id',auth()->user()->id)
                ->update([
                'image' => $image,
            ]);
            return response([
                'image' => 'First/storage/app/'.$image,
            ], 200);
        }
    }

    public function deleteImage()
    {
        $user = auth()->user();
        $image = $user['image'];
        DB::table('users')->where('id',$user->id)
            ->update([
                'image' => 'user.png',
            ]);
        return Storage::disk('local')->delete($image);;
    }
}
