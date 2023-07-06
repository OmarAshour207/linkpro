<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('dashboard.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string',
            'old_password'  => 'sometimes|nullable',
            'new_password'  => 'sometimes|nullable|min:6|required_with:confirm_new_password|same:confirm_new_password',
            'lat'           => 'sometimes|nullable|string',
            'lng'           => 'sometimes|nullable|string',
            'address'       => 'sometimes|nullable|string',
            'phonenumber'   => 'sometimes|nullable|numeric|unique:users:phonenumber'
        ]);

        $user = auth()->user();

        if ($request->old_password)
            if(Hash::check($request->old_password, $user->password))
                $data['password'] = bcrypt($request->new_password);

        if ($request->image && $request->image != 'avatar.png') {
            $image = Media::where('id', $request->image)->first();
            File::move(storage_path('app/public/temp/users/' . $image->name), storage_path('app/public/users/' . $image->name));
            File::move(storage_path('app/public/temp/users/thumb_' . $image->name), storage_path('app/public/users/thumb_' . $image->name));
            $data['image'] = $image->name;
            $image->delete();
        } else {
            unset($data['image']);
        }

        foreach ($data as $key => $value) {
            if(!$request->get($key))
                unset($data[$key]);
        }

        $user->update($data);

        session()->flash('success', __('Saved successfully'));
        return redirect()->back();
    }
}
