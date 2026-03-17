<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function profile($id)
    {
        $user = User::find($id);

        if(!$user){
            return response()->json([
                "status" => false,
                "message" => "User tidak ditemukan"
            ]);
        }

        return response()->json([
            "status" => true,
            "data" => $user
        ]);
    }

    // UPLOAD FOTO AVATAR
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = User::find($request->user_id);

        if(!$user){
            return response()->json([
                "status" => false,
                "message" => "User tidak ditemukan"
            ]);
        }

        if($request->hasFile('avatar')){

            // HAPUS FOTO LAMA
            if($user->avatar && file_exists(public_path('avatar/'.$user->avatar))){
                unlink(public_path('avatar/'.$user->avatar));
            }

            $file = $request->file('avatar');
            $filename = time().".".$file->getClientOriginalExtension();

            $file->move(public_path('avatar'), $filename);

            $user->avatar = $filename;
            $user->save();
        }

        return response()->json([
            "status" => true,
            "message" => "Avatar berhasil diupdate",
            "avatar" => $filename
        ]);
    }

    // UPDATE NAMA USER
    public function updateName(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'name' => 'required|string|max:255'
        ]);

        $user = User::find($request->user_id);

        if(!$user){
            return response()->json([
                "status" => false,
                "message" => "User tidak ditemukan"
            ]);
        }

        $user->name = $request->name;
        $user->save();

        return response()->json([
            "status" => true,
            "message" => "Nama berhasil diupdate",
            "name" => $user->name
        ]);
    }

    // UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'old_password' => 'required',
            'new_password' => 'required|min:6'
        ]);

        $user = User::find($request->user_id);

        if(!$user){
            return response()->json([
                "status" => false,
                "message" => "User tidak ditemukan"
            ]);
        }

        // CEK PASSWORD LAMA
        if(!Hash::check($request->old_password, $user->password)){
            return response()->json([
                "status" => false,
                "message" => "Password lama salah"
            ]);
        }

        // UPDATE PASSWORD
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            "status" => true,
            "message" => "Password berhasil diubah"
        ]);
    }

}