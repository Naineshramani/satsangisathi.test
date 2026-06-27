<?php

namespace App\Http\Controllers;

use App\Models\AdditionalAttribute;
use Illuminate\Http\Request;
use Redirect;
use Validator;

class AdditionalAttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_additional_profile_attributes'])->only('index');

        $this->rules = [
            'title' => ['required','max:255'],
        ];

        $this->messages = [
            'title.required'             => translate('Name is required'),
            'title.max'                  => translate('Max 255 characters'),
        ];
    }

    public function index()
    {
        $additionalAttributes = AdditionalAttribute::get();
        return view('admin.member_profile_attributes.additioal_attributes.index', compact('additionalAttributes'));
    }

    public function create(){}

    public function store(Request $request)
    {
        $rules      = $this->rules;
        $messages   = $this->messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $attribute       = new AdditionalAttribute();
        $attribute->title = $request->title;
        if($attribute->save()){
            flash(translate('New Attribute has been added successfully'))->success();
            return back();
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    public function show($id){}

    public function edit($id){}

    public function update(Request $request)
    { 
        $attribute       = AdditionalAttribute::findOrFail($request->id);
        $attribute->title = $request->title;
        $attribute->status = $request->status;
        if($attribute->save()){
            return 1;
        }
        return 0;
    }

    public function destroy($id){}
}
