<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Media;
use App\Models\Office;
use App\Models\Content;
use App\Models\Path;
use App\Models\Supply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = User::companies()->orderBy('id')->paginate(20);
        return view('dashboard.companies.index', compact('companies'));
    }

    public function create()
    {
        $supervisors = User::supervisors()->orderBy('id')->get();
        return view('dashboard.companies.create', compact('supervisors'));
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'lat'       => 'sometimes|nullable|string',
            'lng'       => 'sometimes|nullable|string',
            'address'   => 'sometimes|nullable|string',
            'phonenumber'   => 'required|numeric|unique:users',
            'image'     => 'sometimes|nullable',
            'supervisor_id' => 'sometimes|nullable|numeric'
        ]);

        $data['password'] = Hash::make($request->get('password'));
        $data['role'] = 'company';

        if ($request->image) {
            $image = Media::where('id', $request->image)->first();
            File::move(public_path('uploads/temp/users/' . $image->name), public_path('uploads/users/' . $image->name));
            File::move(public_path('uploads/temp/users/thumb_' . $image->name), public_path('uploads/users/thumb_' . $image->name));
//            File::move(storage_path('app/public/temp/users/' . $image->name), storage_path('app/public/users/' . $image->name));
//            File::move(storage_path('app/public/temp/users/thumb_' . $image->name), storage_path('app/public/users/thumb_' . $image->name));
            $data['image'] = $image->name;
        }

        $company = User::create($data);
        session()->flash('success', __('Saved successfully'));
        return redirect("/dashboard/companies/{$company->id}/edit?tab=company");
//        return redirect()->route('companies.edit', $company->id, ['tab' => 'company']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $company = User::whereId($id)->whereRole('company')->first();
        if(!$company)
            abort(404);
        $supervisors = User::supervisors()->orderBy('id')->get();
        $floors = Floor::where('user_id', $company->id)->get();
        $paths = Path::where('user_id', $company->id)->get();
        $offices = Office::with('path')->where('user_id', $company->id)->get();
        $officeContents = Content::where('user_id', $company->id)->get();
        $supplies = Supply::where('user_id', $company->id)->get();

        return view('dashboard.companies.edit', [
            'company' => $company,
            'supervisors'   => $supervisors,
            'floors'    => $floors,
            'paths'     => $paths,
            'offices'   => $offices,
            'contents'  => $officeContents,
            'supplies'  => $supplies
        ]);
    }

    public function update(Request $request, User $company)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $company->id,
            'password'  => 'sometimes|nullable|string|min:6|confirmed',
            'lat'       => 'sometimes|nullable|string',
            'lng'       => 'sometimes|nullable|string',
            'address'   => 'sometimes|nullable|string',
            'phonenumber'   => 'required|numeric|unique:users,phonenumber,' . $company->id,
            'image'     => 'sometimes|nullable',
            'supervisor_id' => 'sometimes|nullable|numeric'
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

        $company->update($data);
        session()->flash('success', __('Saved successfully'));

        return redirect("/dashboard/companies/{$company->id}/edit?tab=company");
//        return redirect()->route('companies.edit', ['company_id' => $company->id, 'tab' => 'company']);
    }

    public function destroy(User $company)
    {
        Storage::disk('local')->delete('public/users/'. $company->image);
        Storage::disk('local')->delete('public/users/thumb_'. $company->image);
        $company->delete();
        session()->flash('success', __('Deleted successfully'));
        return redirect()->route('companies.index');

    }
}
