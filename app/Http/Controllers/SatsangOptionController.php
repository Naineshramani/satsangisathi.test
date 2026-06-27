<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SatsangOption;
use Redirect;
use Validator;

class SatsangOptionController extends Controller
{
    protected $categories = [
        'sect'             => 'Follower of (Sect)',
        'nitya_pooja'      => 'Nitya Pooja Daily',
        'kanthi_tilak'     => 'Kanthi / Tilak Chandlo',
        'onion_garlic'     => 'Eat Onion / Garlic',
        'aarti'            => 'Perform Aarti / Ghar Sabha',
        'fasts'            => 'Observe Fasts',
        'temple_frequency' => 'Frequency of Temple Visits',
    ];

    public function index(Request $request)
    {
        $sort_search = null;
        $category    = $request->category ?? 'sect';
        $options     = SatsangOption::where('category', $category);

        if ($request->has('search')) {
            $sort_search = $request->search;
            $options     = $options->where('name', 'like', '%' . $sort_search . '%');
        }

        $options    = $options->paginate(10);
        $categories = $this->categories;

        return view('admin.member_profile_attributes.satsang_options.index',
            compact('options', 'sort_search', 'category', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'max:255'],
            'category' => ['required'],
        ]);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $option           = new SatsangOption;
        $option->category = $request->category;
        $option->name     = $request->name;

        if ($option->save()) {
            flash(translate('Option added successfully'))->success();
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
        }

        return redirect()->route('satsang-options.index', ['category' => $request->category]);
    }

    public function edit($id)
    {
        $option     = SatsangOption::findOrFail(decrypt($id));
        $categories = $this->categories;
        return view('admin.member_profile_attributes.satsang_options.edit',
            compact('option', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
        ]);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $option       = SatsangOption::findOrFail($id);
        $option->name = $request->name;

        if ($option->save()) {
            flash(translate('Option updated successfully'))->success();
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
        }

        return redirect()->route('satsang-options.index', ['category' => $option->category]);
    }

    public function destroy($id)
    {
        $option = SatsangOption::findOrFail($id);
        $category = $option->category;
        if ($option->delete()) {
            flash(translate('Option deleted successfully'))->success();
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
        }

        return redirect()->route('satsang-options.index', ['category' => $category]);
    }
}
