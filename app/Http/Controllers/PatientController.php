<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPatientRequest;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PatientDetail;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function index()
    {
        try {
            $response['upcoming_appointments_count'] = Appointment::where([['appointment_date', '>', now()],['patient_id', '=', Auth::id()]])->count();
            $response['prev_appointments_count'] = Appointment::where([['appointment_date', '<', now()],['patient_id', '=', Auth::id()]])->count();
            $response['todays_appointments_count'] = Appointment::where([['appointment_date', '=' , now()],['patient_id', '=', Auth::id()]])->count();
            return view('patient/patient')->with('response', $response);
        } catch (Exception $e) {
            return view('errors.patient_error')->with(['error_message' => 'something went wrong, refresh the page and try again']);
        }
    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {
        try {
            $patient = User::find($id);
            return view('patient/show_patient_profile')->with(['patient' => $patient]);
        } catch (Exception $e) {
            return redirect('patient')->with(['error_message' => 'something went wrong, refresh the page and try again']);
        }
    }

    public function edit($id)
    {
        try {
            $patient = User::find($id);
            return view('patient/edit_patient_profile')->with(['patient' => $patient]);
        } catch (Exception $e) {
            return redirect('patient' . '/' . $id)->with(['error_message' => 'something went wrong, refresh the page and try again']);
        }
    }

    public function update(EditPatientRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            // update patient in user table
            $patient = User::find($id);
            $patient->update(['name' => $request['first_name'] . ' ' . $request['last_name']]);
            $request['user_id'] = $id;

            // update patient details
            $input = $request->all();
            if ($request->hasFile('image_link')) {
                $file = $request->file('image_link');
                $input['image_link'] = Storage::putFile('public/ProfileImages', $file, 'public');
            }
            PatientDetail::where(['user_id' => $id])->first()->update($input);
            DB::commit();
            return redirect('patient/' . $id);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect('patient/' . $id . '/edit')->with(['error_message' => 'something went wrong, refresh the page and try again']);
        }
    }

    public function destroy($id)
    {
        //
    }
}