@extends('layouts.app')
@push('css')
<link href="{{ asset('css/table.css')}}" rel="stylesheet">
<link href="{{ asset('css/form.css')}}" rel="stylesheet">
@endpush
@section('content')
@if(session()->get('error_message'))
<div class="alert alert-danger">{{session()->get('error_message')}}</div>
@endif
    <div class="container" >
    <form action="{{url('doctor/create')}}" class="d-flex justify-content-between">
        <h3 class="d-flex align-items-end mb-0 font-weight-bold">Doctors List (in alphabetical order)</h3>  
        <button class="add-doctor-btn">+ Add New Doctor</button>
    </form>
<div>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Eductaion</th>
            <th>Specialization</th>
            <th>Designation</th>
            <th>Experience</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Working Days</th>
            <th>Timings</th>
            <th>Conusltaion Fee</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @foreach($doctors as $doctor)
        <tr>
            <td>{{$doctor->name}}</td>
            <td>{{$doctor->email}}</td>
            @if($doctor && $doctor->doctorDetail)
            <td>{{$doctor->doctorDetail->education->name}}</td>
            <td>{{$doctor->doctorDetail->specialization->name}}</td>
            <td>{{$doctor->doctorDetail->designation->name}}</td>
            <td>{{$doctor->doctorDetail->experience}} year(s)</td>
            <td>@calculateAge($doctor->doctorDetail->dob) years</td>
            <td>{{$doctor->doctorDetail->gender}}</td>
            <td>
                @foreach($doctor->doctorWorkingDays as $doctor_day)
                {{$doctor_day->day_name}}
                @endforeach
            </td>
            <td>{{ date('h:i A', strtotime($doctor->doctorDetail->start_time)). ' to ' .date('h:i A', strtotime($doctor->doctorDetail->end_time))}}</td>
            <td>Rs. {{$doctor->doctorDetail->conusltaion_fee}}</td>
            @endif
            <td><button class="activate"><a href="" class="activate">Activate</a></button></td>
            <td><button class="edit"><a href="{{url('doctor').'/'.$doctor->id.'/edit'}}" class="edit">Edit</a></button></td>
            <th>
                <form action="{{url('doctor').'/'.$doctor->id}}" method ="POST">
                @csrf
                @method('delete')
                    <button type="submit" value="delete" class="delete">Delete</button>
                </form>
            </th>
        </tr>
        @endforeach
    </table>
</div>
</div>
{{ $doctors->links() }}


{{-- @if(session('success_message'))
    @sweetAlert(session('success_message'))
@endif --}}
@push('js')
<script>
    $(document).ready(function () {
    let sessionMessage = "{{ session('success_message') }}";
    console.log(sessionMessage);
    // showSwalPopUp(sessionMessage);
    });
</script>
@endpush
@endsection