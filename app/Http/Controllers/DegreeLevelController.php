<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DegreeLevel;
use Redirect;
use Validator;

class DegreeLevelController extends Controller
{
    public function __construct()
    {
        $this->rules = [
            'name' => ['required', 'max:255'],
        ];
        $this->messages = [
            'name.required' => translate('Name is required'),
            'name.max'      => translate('Max 255 characters'),
        ];
    }

    public function index(Request $request)
    {
        $sort_search   = null;
        $degree_levels = DegreeLevel::orderBy('sort_order')->orderBy('name');

        if ($request->has('search')) {
            $sort_search   = $request->search;
            $degree_levels = $degree_levels->where('name', 'like', '%' . $sort_search . '%');
        }
        $degree_levels = $degree_levels->paginate(15);
        return view('admin.member_profile_attributes.degree_levels.index', compact('degree_levels', 'sort_search'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }
        $degree             = new DegreeLevel;
        $degree->name       = $request->name;
        $degree->sort_order = $request->sort_order ?? 0;
        if ($degree->save()) {
            flash(translate('Degree has been added successfully'))->success();
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
        }
        return redirect()->route('degree-levels.index');
    }

    public function edit($id)
    {
        $degree_level = DegreeLevel::findOrFail(decrypt($id));
        return view('admin.member_profile_attributes.degree_levels.edit', compact('degree_level'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }
        $degree             = DegreeLevel::findOrFail($id);
        $degree->name       = $request->name;
        $degree->sort_order = $request->sort_order ?? $degree->sort_order;
        if ($degree->save()) {
            flash(translate('Degree has been updated successfully'))->success();
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
        }
        return redirect()->route('degree-levels.index');
    }

    public function destroy($id)
    {
        if (DegreeLevel::destroy($id)) {
            flash(translate('Degree has been deleted successfully'))->success();
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
        }
        return redirect()->route('degree-levels.index');
    }
}
