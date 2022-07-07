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
    <div class="col-8">
        <div class="card">
            {{-- <div class="card-header">
                <h4>Machine Data</h4>
            </div> --}}
            <div class="card-body">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered data-table table-sm table-hover">
                                <thead>

                                    <tr role="row">
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Shift Work</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
        <div class="card">
            <div id="headerForm" class="card-header">
                <h4>Create New Team Work</h4>
            </div>
            <form id="shiftworkForm" name="shiftworkForm" method="POST">
                <input type="hidden" id="teamWork" name="teamWork" value="{{ $teamWork->id }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Group Name</label>
                        <input value="{{ $teamWork->TeamWorkName }}" readonly type="text" class="form-control" id="TeamWorkName" name="TeamWorkName">
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="text" class="form-control datepickerEmp" id="startDate" name="startDate">
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="text" class="form-control datepickerEmp" id="endDate" name="endDate">
                    </div>
                    <div class="form-group">
                        <label>Shift</label>
                        <select id="shiftWork" name="shiftWork" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option disabled selected>--Select--</option>
                            @foreach($shiftWork as $sw)
                            <option value="{{ $sw->id }}">{{ $sw->shiftwork }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button id="saveBtn" class="btn btn-primary">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {

        var teamWorkId = "{{ $teamWork->id }}"
        var APP_URL = "{{ route('teamwork.index') }}" + "/" + teamWorkId + "/" + "shiftwork"
        $(".datepickerEmp").datepicker({
            changeMonth: true
            , changeYear: true
            , inline: true
            , dateFormat: 'yy-mm-dd'
        , })

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true
            , serverSide: true
            , ajax: APP_URL
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                }
                , {
                    data: 'date'
                    , name: 'date'
                }
                , {
                    data: 'shiftwork'
                    , name: 'shiftwork'
                }
                , {
                    data: 'checkIn'
                    , name: 'checkIn'
                }
                , {
                    data: 'checkOut'
                    , name: 'checkOut'
                }
                , {
                    data: 'action'
                    , name: 'action'
                    , orderable: false
                    , searchable: false
                }
            , ]
        })

        $.validator.setDefaults({
            submitHandler: function() {
                $('#saveBtn').html('Saving..');
                $.ajax({
                    url: "{{ route('teamwork.shiftworkSave') }}"
                    , type: "POST"
                    , data: $('#shiftworkForm').serialize()
                    , dataType: "json"
                    , success: function(response) {
                        table.draw();

                        $('#saveBtn').html('Create');
                        $('#res_message').html(response.msg);
                        $('#res_message').show();
                        $('#msg_div').removeClass('d-none');

                        document.getElementById("shiftworkForm").reset();
                        setTimeout(function() {
                            $('#res_message').hide();
                            $('#msg_div').hide();
                        }, 10000);
                    }
                });
            }
        });
        $('#shiftworkForm').validate({
            rules: {
                TeamWorkName: {
                    required: true
                }
            }
            , messages: {
                TeamWorkName: {
                    required: "Please input teamwork ID"
                }
            }
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


        $('body').on('click', '.deleteshiftworkTable', function() {
            var DeleteteamworkId = $(this).data('id');
            console.log(DeleteteamworkId);
            confirm("Are you sure want to delete!");
            var url = "{{ route('teamwork.index') }}" + "/" + DeleteteamworkId + "/" + "delete-shiftwork"

            $.ajax({
                type: "DELETE"
                , url
                , success: function(data) {
                    table.draw();
                }
                , error: function(data) {
                    console.log('Error: ', data);
                }
            })
        })
    })

</script>
@endsection
