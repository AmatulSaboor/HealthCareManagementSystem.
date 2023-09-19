@extends('layouts.app')
@push('css')
<link href="{{ asset('css/table.css')}}" rel="stylesheet">
<link href="{{ asset('css/form.css')}}" rel="stylesheet">
@endpush
@section('content')
<div class="container">
    @if(session()->get('error_message'))
    <div class="alert alert-danger">{{session()->get('error_message')}}</div>
    @endif
    <h4 class="d-flex align-items-end mb-0">Appointments List (by appointment date order)</h4>
    <div>
        <table>
            <tr>
                <th>For</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Patient</th>
                <th></th>
            </tr>
            @foreach($appointments as $appointment)
            <tr>
                <td>{{$appointment->doctorUser->doctorDetail->specialization->name}}</td>
                <td>{{$appointment->doctorUser->name}}</td>
                <td>{{$appointment->appointment_date}}</td>
                <td>{{date('h:i A', strtotime($appointment->appointment_time))}}</td>
                <td><a href="{{url('show_patient').'/'.$appointment->patientUser->id}}"
                            class="reschedule"><button class="reschedule">{{$appointment->patientUser->name}} </button></a></td>
                <th>
                    <form id="delete_form_{{$appointment->id}}" action="{{url('delete_appointment').'/'.$appointment->id}}"
                        method="POST">
                        @csrf
                        @method('delete')
                        <button type="button" class="cancel-app"
                            onclick="confirmationPopUp({{ $appointment->id }}, 'delete_form_', 'appointment')">Cancel</button>
                    </form>
                </th>
            </tr>
            @endforeach
        </table>
    </div> 
</div>
{{$appointments->links()}}   
@push('js')
<script>
$(document).ready(function() {
    sucessPopUp("{{ session('sent_email_msg') }}");
});
</script>
@endpush
@endsection