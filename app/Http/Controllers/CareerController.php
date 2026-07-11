<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use Validator;
use Redirect;

class CareerController extends Controller
{
    public function __construct()
    {
        $this->baseRules = [
            'employment_type' => ['required', 'in:job,business,self_employed,not_working'],
            'career_start'    => ['nullable', 'numeric'],
            'career_end'      => ['nullable', 'numeric'],
        ];
    }

    protected function getRules(string $type): array
    {
        $rules = $this->baseRules;
        if ($type === 'job') {
            $rules['designation'] = ['required', 'max:255'];
            $rules['company']     = ['required', 'max:255'];
        } elseif ($type === 'business') {
            $rules['nature_of_business'] = ['required', 'max:255'];
            $rules['company']            = ['required', 'max:255'];
        } elseif ($type === 'self_employed') {
            $rules['designation'] = ['required', 'max:255'];
        }
        // not_working: no additional fields required
        return $rules;
    }

    public function index(){}

    public function create(Request $request)
    {
        $member_id = $request->id;
        return view('frontend.member.profile.career.create', compact('member_id'));
    }

    public function store(Request $request)
    {
        $type      = $request->employment_type ?? 'job';
        $validator = Validator::make($request->all(), $this->getRules($type));

        if ($validator->fails()) {
            flash(translate('Something went wrong'))->error();
            return Redirect::back();
        }

        $career                    = new Career;
        $career->user_id           = $request->user_id;
        $career->employment_type   = $type;
        $career->designation       = $request->designation ?: null;
        $career->nature_of_business = $request->nature_of_business ?: null;
        $career->company           = $request->company ?: null;
        $career->currency          = $request->currency ?? 'INR';
        $career->start             = $request->career_start;
        $career->end               = $request->career_end;

        if ($career->save()) {
            flash(translate('Career Info has been added successfully'))->success();
            return back();
        }
        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function show($id){}

    public function edit(Request $request)
    {
        $career = Career::findOrFail($request->id);
        return view('frontend.member.profile.career.edit', compact('career'));
    }

    public function update(Request $request, $id)
    {
        $type      = $request->employment_type ?? 'job';
        $validator = Validator::make($request->all(), $this->getRules($type));

        if ($validator->fails()) {
            flash(translate('Something went wrong'))->error();
            return Redirect::back();
        }

        $career                     = Career::findOrFail($id);
        $career->employment_type    = $type;
        $career->designation        = $request->designation ?: null;
        $career->nature_of_business = $request->nature_of_business ?: null;
        $career->company            = $request->company ?: null;
        $career->currency           = $request->currency ?? 'INR';
        $career->start              = $request->career_start;
        $career->end                = $request->career_end;

        if ($career->save()) {
            flash(translate('Career Info has been updated successfully'))->success();
            return back();
        }
        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function update_career_present_status(Request $request)
    {
        $career = Career::findOrFail($request->id);
        $career->present = $request->status;
        if ($career->save()) {
            $msg = $career->present == 1 ? translate('Enabled') : translate('Disabled');
            flash(translate($msg))->success();
            return 1;
        }
        return 0;
    }

    public function destroy($id)
    {
        if (Career::destroy($id)) {
            flash(translate('Career info has been deleted successfully'))->success();
            return back();
        }
        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }
}
