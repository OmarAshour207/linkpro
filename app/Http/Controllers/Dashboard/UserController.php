<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whenSearch(\request()->search)
            ->whenRole(\request()->role)
            ->where('role', '!=', 'admin')
            ->where('role', '!=', 'company')
            ->orderBy('id')
            ->paginate(20);
        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        return view('dashboard.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'sometimes|nullable|string|max:255',
            'email'     => 'sometimes|nullable|email|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'phonenumber'   => 'required|numeric|unique:users',
            'image'     => 'sometimes|nullable',
            'role'      => 'required|string'
        ]);

        $data['password'] = Hash::make($request->get('password'));

        if ($request->image) {
            $image = Media::where('id', $request->image)->first();
            File::move(public_path('uploads/temp/users/' . $image->name), public_path('uploads/users/' . $image->name));
//            File::move(storage_path('app/public/temp/users/' . $image->name), storage_path('app/public/users/' . $image->name));
//            File::move(storage_path('app/public/temp/users/thumb_' . $image->name), storage_path('app/public/users/thumb_' . $image->name));
            File::move(public_path('uploads/temp/users/thumb_' . $image->name), public_path('uploads/users/thumb_' . $image->name));
            $data['image'] = $image->name;
        }

        User::create($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('users.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'sometimes|nullable|string|min:6|confirmed',
            'phonenumber'   => 'required|numeric|unique:users,phonenumber,' . $user->id,
            'role'      => 'required|string',
            'image'     => 'sometimes|nullable'
        ]);

        if ($request->get('password'))
            $data['password'] = Hash::make($request->get('password'));
        else
            unset($data['password']);

        if ($request->image) {
            $image = Media::where('id', $request->image)->first();
            File::move(public_path('uploads/temp/users/' . $image->name), public_path('uploads/users/' . $image->name));
            File::move(public_path('uploads/temp/users/thumb_' . $image->name), public_path('uploads/users/thumb_' . $image->name));

//            File::move(storage_path('app/public/temp/users/' . $image->name), storage_path('app/public/users/' . $image->name));
//            File::move(storage_path('app/public/temp/users/thumb_' . $image->name), storage_path('app/public/users/thumb_' . $image->name));
            $data['image'] = $image->name;
        } else {
            unset($data['image']);
        }

        $user->update($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        if($user->image) {
            Storage::disk('local')->delete('public/users/'. $user->image);
            Storage::disk('local')->delete('public/users/thumb_'. $user->image);
        }
        $user->delete();
        session()->flash('success', __('Deleted successfully'));
        return redirect()->route('users.index');
    }
}
