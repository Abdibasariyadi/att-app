@extends('layouts.app')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

</script>
@endpush
@section('content')

<div class="section-header">
    <h1>{{ $title }}</h1>
</div>

<div class="row">
    <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
            <div id="headerForm" class="card-header">
                <h4>Update Employee Data</h4>
            </div>
            <form id="employeeForm" name="employeeForm" method="POST" action="{{ route('employee.store') }}" enctype="multipart/form-data">
                <input type="hidden" id="id" name="id">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-2">
                            <label>UID</label>
                            <input type="text" class="form-control" id="uid" name="uid" onkeyup="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-group col-4">
                            <label>Name</label>
                            <input type="text" class="form-control" id="name" name="name" onkeyup="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-group col-2">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group col-2">
                            <label>Date of Birth</label>
                            <input type="text" class="form-control datepickerEmp" id="dateOfBirth" name="dateOfBirth" onkeyup="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-group col-2">
                            <label>Gender</label>
                            <select id="gender" name="gender" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value="" disabled selected>--Select--</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-3">
                            <label>Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                        </div>
                        <div class="form-group col-3">
                            <label>Department</label>
                            <select id="department_id" name="department_id" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option disabled selected>--Select--</option>
                                @foreach($department as $dp)
                                <option value="{{ $dp->department_id }}">{{ $dp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-3">
                            <label>Position</label>
                            <select id="position_id" name="position_id" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option disabled selected>--Select--</option>
                                @foreach($position as $ps)
                                <option value="{{ $ps->position_id }}">{{ $ps->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-3">
                            <label>Salary</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i>MMK</i>
                                    </div>
                                </div>
                                <input type="number" id="salary" name="salary" class="form-control">
                            </div>
                        </div>
                    </div>
                    <button id="saveBtn" class="btn btn-primary">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $(".datepickerEmp").datepicker({
            changeMonth: true
            , changeYear: true
            , inline: true
            , dateFormat: 'yy-mm-dd'
            , yearRange: "-70:+0" // last hundred years
        , })

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#employeeForm').submit(function(evt) {
            evt.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: 'POST'
                , url: $(this).attr('action')
                , data: formData
                , cache: false
                , contentType: false
                , processData: false
                , success: function(data) {
                    console.log(data)
                    location.href = "{{ route('employee.index') }}";
                }
                , error: function(data) {
                    console.log(data.responseJSON.errors)

                }
            });
        });

        $('#employeeForm').validate({
            rules: {
                uid: {
                    required: true
                , }
                , name: {
                    required: true
                , }
                , email: {
                    required: true
                , }
                , dateOfBirth: {
                    required: true
                , }
                , gender: {
                    required: true
                , }
                , department_id: {
                    required: true
                , }
            , }
            , messages: {
                uid: {
                    required: "Please input UID"
                , }
                , name: {
                    required: "Please input name"
                , }
                , email: {
                    required: "Please input email"
                , }
                , dateOfBirth: {
                    required: "Please input date of birth"
                , }
                , gender: {
                    required: "Please input gender"
                , }
                , department_id: {
                    required: "Please input department"
                , }
            , }
            , errorElement: 'span'
            , errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            }
            , highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            }
            , unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });

</script>
@endsection
