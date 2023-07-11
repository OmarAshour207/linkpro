<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Office;
use App\Models\Content;
use App\Models\Path;
use App\Models\User;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::whenSearch(\request()->search)
            ->whenOffice(\request()->office)
            ->with('company', 'floor', 'path', 'office')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('dashboard.officecontents.index', compact('contents'));
    }

    public function create()
    {
        $companies = User::companies()->get();
        return view('dashboard.officecontents.create', compact('companies'));
    }

    public function showOffices(Request $request)
    {
        if ($request->ajax()) {
            $offices = Office::where('path_id', $request->get('path_id'))->get();
            return view('dashboard.officecontents.ajax.offices', compact('offices'));
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content'   => 'required|array',
            'user_id'   => 'required|numeric',
            'floor_id'  => 'required|array',
            'path_id'   => 'required|array',
            'office_id' => 'required|array',
            'content_id'    => 'sometimes|nullable|array'
        ]);
        $contents = $data['content'];
        foreach ($contents as $index => $content) {
            if(empty($content) || $data['floor_id'][$index] == 0 || $data['path_id'][$index] == 0)
                continue;
            Content::updateOrCreate([
                'id'    => $data['content_id'][$index]
            ],[
                'user_id'   => $data['user_id'],
                'floor_id'  => $data['floor_id'][$index],
                'path_id'   => $data['path_id'][$index],
                'office_id' => $data['office_id'][$index],
                'content'   => $data['content'][$index],
            ]);
        }

        session()->flash('success', __('Saved successfully'));
        return redirect("/dashboard/companies/{$data['user_id']}/edit?tab=office_contents");
    }

    public function show($id)
    {
        //
    }

    public function edit(Content $content)
    {
        $companies = User::companies()->get();
        $floors = Floor::where('id', $content->floor_id)->get();
        $paths = Path::where('id', $content->path_id)->get();
        $offices = Office::where('id', $content->office_id)->get();

        return view('dashboard.officecontents.edit', compact('content', 'companies', 'floors', 'paths', 'offices'));
    }

    public function update(Request $request, Content $content)
    {
        $data = $request->validate([
            'content'   => 'required|string|max:255',
            'note'      => 'required|string|max:255',
            'user_id'   => 'required|numeric',
            'floor_id'  => 'required|numeric',
            'path_id'   => 'required|numeric',
            'office_id'  => 'required|numeric',
        ]);

        $content->update($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('contents.index');
    }

    public function destroy(Content $content)
    {
        $content->delete();
        session()->flash('success', __('Deleted successfully'));
        return response()->json(['success' => true], 200);
//        return redirect()->route('contents.index');
    }
}
