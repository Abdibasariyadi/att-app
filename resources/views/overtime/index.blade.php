@extends('layouts.app')

@section('content')


<div class="section-header">
    <h1>{{ $title }}</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Machine Data</h4>
            </div>
            <div class="row col-md-12" style="margin-left:2px;">
                <div class="form-group col-md-3">
                    <label for="kategori">Start</label>
                    <input type="text" name="min" id="min" class="form-control dataPickerOvertime" />
                </div>
                <div class="form-group col-md-3">
                    <label for="kategori">End</label>
                    <input type="text" name="max" id="max" class="form-control dataPickerOvertime" />
                </div>
                <div class="form-group col-md-2" style="top: 30px;">
                    <button type="button" name="filter" id="filter" class="btn btn-outline-primary">Filter</button>
                    <button type="button" name="refresh" id="refresh" class="btn btn-outline-warning">Refresh</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-sm-12">
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

    {{-- <div class="col-12 col-md-4 col-lg-4">
        <div class="card">
            <div id="headerForm" class="card-header">
                <h4>Create New Overtime</h4>
            </div>
            <form id="overtimeForm" name="overtimeForm" method="POST">
                <input type="hidden" id="id" name="id">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name" onkeyup="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                        <label>Check In</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <input type="text" id="checkIn" name="checkIn" class="form-control datetimepicker">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Check Out</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <input type="text" id="checkOut" name="checkOut" class="form-control datetimepicker">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-hourglass-start"></i>
                                </div>
                            </div>
                            <input type="number" id="duration" name="duration" class="form-control">
                        </div>
                    </div>
                    <button id="saveBtn" class="btn btn-primary">Create</button>
                </div>
            </form>

        </div>
    </div> --}}
</div>

<script type="text/javascript">
    $(function() {

        $(".dataPickerOvertime").datepicker({
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
            , ajax: "{{ route('overtime.index') }}"
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

        $.validator.setDefaults({
            submitHandler: function() {
                $('#saveBtn').html('Saving..');
                $.ajax({
                    url: "{{ route('overtime.create') }}"
                    , type: "POST"
                    , data: $('#overtimeForm').serialize()
                    , dataType: "json"
                    , success: function(response) {
                        table.draw();

                        $('#saveBtn').html('Create');
                        $('#res_message').html(response.msg);
                        $('#res_message').show();
                        $('#msg_div').removeClass('d-none');

                        document.getElementById("overtimeForm").reset();
                        setTimeout(function() {
                            $('#res_message').hide();
                            $('#msg_div').hide();
                        }, 10000);
                    }
                });
            }
        });
        $('#overtimeForm').validate({
            rules: {
                overtime: {
                    required: true
                }
            }
            , messages: {
                overtime: {
                    required: "Please input Shift Work"
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

        $('body').on('click', '.editOvertime', function() {
            var overtimeId = $(this).data('id');
            console.log(overtimeId)
            $.get("{{ route('overtime.index') }}" + "/" + overtimeId + "/edit", function(data) {
                $('#headerForm').html("<h4>Edit overtime</h4>");
                // $('#roleModal').modal('show');
                // $(".modal-backdrop").hide();
                $("#id").val(data.id);
                $('#name').val(data.uid);
                $('#checkIn').val(data.checkIn);
                $('#checkOut').val(data.checkOut);
                $('#duration').val(data.duration);
            })
        })


        $('body').on('click', '.deleteOvertime', function() {
            var overtimeId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "overtime" + '/' + overtimeId + '/delete';
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
                    url: "{{ url('overtime/filter') }}"
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

        var route = "{{ url('search') }}";

        $('#nameEmployee').typeahead({
            source: function(query, process) {
                return $.get(route, {
                    query: query
                }, function(data) {
                    return process(data);
                });
            }
        });

    })

</script>
@endsection
