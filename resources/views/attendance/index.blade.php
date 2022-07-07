@extends('layouts.app')

@section('content')


<div class="section-header">
    <h1>{{ $title }}</h1>
</div>

<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h4>Data Attendance Logs</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered data-table table-sm table-hover">
                                <thead>

                                    <tr role="row">
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Date</th>
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
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <button type="button" id="getAttendance" class="btn btn-info btn-flat"><i class="fas fa-fw fa-search"></i> Get Attendance</button>
                <div class="text-primary" id="getAttendanceLoading" role="status" style="margin-left: 10px; margin-bottom: -8px;">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function getAttendance() {



        /*
                $.ajax({
                    url: "{{ route('attendance.get') }}"
                    , dataType: "json"
                    , method: "GET"
                    , success: function(data) {
                        console.log('log: ', data)
                    }
                });
            */
    }

    $(function() {

        $("#getAttendance").click(function() {
            var btn = $("#getAttendanceLoading");
            $(btn).addClass("spinner-border");
            $.ajax({
                url: "{{ route('attendance.get') }}"
                , success: function(result) {
                    $(btn).removeClass("spinner-border");
                    table.draw();

                    console.log(result)
                }
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true
            , serverSide: true
            , ajax: "{{ route('attendance.index') }}"
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'uid'
                    , name: 'uid'
                }
                , {
                    data: 'name'
                    , name: 'name'
                }
                , {
                    data: 'dateFormat'
                    , name: 'dateFormat'
                }
                , {
                    data: 'checkin'
                    , name: 'checkin'
                }
                , {
                    data: 'checkout'
                    , name: 'checkout'
                }
                , {
                    data: 'action'
                    , name: 'action'
                    , orderable: false
                    , searchable: false
                }
            , ]
        })


        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving...');

            $.ajax({
                data: $('#machineForm').serialize()
                , url: "{{ route('machine.create') }}"
                , type: "POST"
                , dataType: 'json'
                , success: function(data) {
                    $("#machineForm").trigger("reset");
                    // $('#roleModal').modal('hide');
                    table.draw();
                    $('#saveBtn').html('Saving...')

                }
                , error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Saving...')
                }
            })
        })



        $('body').on('click', '.editmachine', function() {
            var machineId = $(this).data('id');
            $.get("{{ route('machine.index') }}" + "/" + machineId + "/edit", function(data) {
                $('#headerForm').html("<h4>Edit machine</h4>");
                // $('#roleModal').modal('show');
                // $(".modal-backdrop").hide();
                $("#machine_id").val(data.id);
                $('#name').val(data.name);
            })
        })


        $('body').on('click', '.deletemachine', function() {
            var machineId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "machine" + '/' + tpsId + '/delete';
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
