<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institution;
use Illuminate\Support\Facades\Validator;

class InstitutionController extends Controller
{
    public function show($id){
      $institution = Institution::find($id);
      return view('institution.show', array('institution' => $institution));
    }

    public function create(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
          'institutionName' => 'required|string|max:45|unique:institutions',
          'location' => 'required|string|max:45',
          'schoolTypeID' => 'required|int|max:100',
        ]);

        if ($validator->fails()) {
          return redirect('/dashboard/campus-add')->withErrors($validator)->withInput();
        }

        else if ($validator->passes()) {
          /** 
          $institution = Institution::create([
            'institutionName' => $data['institutionName'],
            'location' => $data['location'],
            'schoolTypeID' => $data['schoolTypeID'],
          ]); */

          $institution = new Institution;
          $institution->institutionName = $data['institutionName'];
          $institution->location = $data['location'];
          $institution->schoolTypeID = $data['schoolTypeID'];
          $institution->save();

          return redirect('/dashboard/campus-add')->with('success', true)->with('message', $data['institutionName'].' added!');
        }
    }

    public function edit(Request $request) {
      $data = $request->all();

      $currentInstitution = $data['current-ci'];
      $institution = $data['ci-name'];
      $location = $data['ci-location'];
      $type = $data['ci-type'];
      
      $institutiondata = Institution::find($currentInstitution);

      $institutiondata->institutionName = $institution;
      $institutiondata->location = $location;
      $institutiondata->schoolTypeID = $type;

      $institutiondata->save();

      return redirect()->route('campus-view');
    }

}