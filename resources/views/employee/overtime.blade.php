@extends('layouts.app')

@section('content')

<style>
    .modal-backdrop {
        /* bug fix - no overlay */
        display: none;
    }

</style>
<div class="section-header">
    <h1>{{ $title }}</h1>
</div>

<div class="row">
    <div class="col-12">
        @include('employee.tab')
        <hr>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <img src="{{ asset('uploads/'.$employee->photo)}}" width="300">
                        <br>
                        <br>
                        <h3>{{ $employee->name }}</h3>
                    </div>
                    <div class="col-sm-8">
                        <div class="row col-md-12" style="margin-left:-30px;">
                            <div class="form-group col-md-4">
                                <label for="kategori">Start</label>
                                <input type="text" name="min" id="min" class="form-control dataPickerPresence" />
                            </div>
                            <div class="form-group col-md-4">
                                <label for="kategori">End</label>
                                <input type="text" name="max" id="max" class="form-control dataPickerPresence" />
                            </div>
                            <div class="form-group col-md-4" style="top: 30px;">
                                <button type="button" name="filter" id="filter" class="btn btn-outline-primary">Filter</button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-outline-warning">Refresh</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered data-table table-sm table-hover">
                                <thead>
                                    <tr role="row">
                                        <th>#</th>
                                        <th>UID</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Shift Type</th>
                                        <th>Clock In</th>
                                        <th>Clock Out</th>
                                        <th>Overtime</th>
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
</div>

<script type="text/javascript">
    $(function() {

        $(".dataPickerPresence").datepicker({
            dateFormat: 'yy-mm-dd'
            , changeMonth: true
            , changeYear: true
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true
            , serverSide: true
            , order: []
            , ajax: "{{ route('employee.overtime', $employee->uid) }}"
            , dom: 'Bfrtip'
            , buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
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
                    data: 'date'
                    , name: 'date'
                }
                , {
                    data: 'loginTime'
                    , name: 'loginTime'
                }
                , {
                    data: 'logoutTime'
                    , name: 'logoutTime'
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
                    data: 'overTime'
                    , name: 'overTime'
                }
            , ]
        })

        $("#filter").click(function() {
            var min = $("#min").val()
            var max = $("#max").val()
            $('.data-table').DataTable({
                "processing": true
                , "serverSide": true
                , "dom": 'Bfrtip'
                , "buttons": ['copy', 'csv', 'excel', 'pdf', 'print']
                , 'destroy': true
                , ajax: {
                    url: "{{ route('employeeOverTime.filter', $employee->uid) }}"
                    , data: function(d) {
                        d.min = $('#min').val();
                        d.max = $('#max').val();

                    }
                }
                , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
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
                    data: 'date'
                    , name: 'date'
                }
                , {
                    data: 'loginTime'
                    , name: 'loginTime'
                }
                , {
                    data: 'logoutTime'
                    , name: 'logoutTime'
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
                    data: 'overTime'
                    , name: 'overTime'
                }
            , ]
            });
        });

        $('#refresh').click(function() {
            $('#min').val('');
            $('#max').val('');
            $('.data-table').DataTable().destroy();
            table.draw();
        });

    })

</script>
@endsection
