<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Office;
use App\Models\OfficeContent;
use App\Models\Path;
use App\Models\User;
use Illuminate\Http\Request;

class OfficeContentController extends Controller
{
    public function index()
    {
        $contents = OfficeContent::whenSearch(\request()->search)
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
        $validated = $request->validate([
            'content'   => 'required',
            'user_id'   => 'required|numeric',
            'floor_id'  => 'required|numeric',
            'path_id'   => 'required|numeric',
            'office_id' => 'required|numeric',
            'note'      => 'sometimes|nullable'
        ]);
        $contents = $validated['content'];

        for ($i = 0;$i < count($contents);$i++) {
            if(!empty($validated['content'][$i])) {
                OfficeContent::create([
                    'user_id'   => $validated['user_id'],
                    'floor_id'  => $validated['floor_id'],
                    'path_id'   => $validated['path_id'],
                    'office_id' => $validated['office_id'],
                    'content'   => $validated['content'][$i],
                    'note'      => $validated['note'][$i]
                ]);
            }
        }
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('contents.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(OfficeContent $content)
    {
        $companies = User::companies()->get();
        $floors = Floor::where('id', $content->floor_id)->get();
        $paths = Path::where('id', $content->path_id)->get();
        $offices = Office::where('id', $content->office_id)->get();

        return view('dashboard.officecontents.edit', compact('content', 'companies', 'floors', 'paths', 'offices'));
    }

    public function update(Request $request, OfficeContent $content)
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

    public function destroy(OfficeContent $content)
    {
        $content->delete();
        session()->flash('success', __('Deleted successfully'));
        return redirect()->route('contents.index');
    }
}
