@extends('layouts.app')

@section('content')


<div class="section-header">
    <h1>{{ $title }}</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Filter Date</h4>
            </div>
            <div class="row col-md-12" style="margin-left:2px;">
                <div class="form-group col-md-3">
                    <label for="kategori">Start</label>
                    <input type="text" name="min" id="min" class="form-control dataPickerPresence" />
                </div>
                <div class="form-group col-md-3">
                    <label for="kategori">End</label>
                    <input type="text" name="max" id="max" class="form-control dataPickerPresence" />
                </div>
                <div class="form-group col-md-3" style="top: 30px;">
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
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Date</th>
                                        <th>Shift Type</th>
                                        <th>Clock In</th>
                                        <th>Clock Out</th>
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
            , ajax: "{{ route('presence.index') }}"
            , dom: 'Bfrtip'
            , buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                    , orderable: false
                    , searchable: false
                }, {
                    data: 'uid'
                    , name: 'uid'
                }, {
                    data: 'name'
                    , name: 'name'
                }, {
                    data: 'loginTime'
                    , name: 'loginTime'
                }, {
                    data: 'logoutTime'
                    , name: 'logoutTime'
                }, {
                    data: 'date'
                    , name: 'date'
                }, {
                    data: 'shiftwork'
                    , name: 'shiftwork'
                }, {
                    data: 'checkIn'
                    , name: 'checkIn'
                }, {
                    data: 'checkOut'
                    , name: 'checkOut'
                }
            , ]
        })

        $.validator.setDefaults({
            submitHandler: function() {
                $('#saveBtn').html('Saving..');
                $.ajax({
                    url: "{{ route('presence.create') }}"
                    , type: "POST"
                    , data: $('#presenceForm').serialize()
                    , dataType: "json"
                    , success: function(response) {
                        table.draw();

                        $('#saveBtn').html('Create');
                        $('#res_message').html(response.msg);
                        $('#res_message').show();
                        $('#msg_div').removeClass('d-none');

                        document.getElementById("presenceForm").reset();
                        setTimeout(function() {
                            $('#res_message').hide();
                            $('#msg_div').hide();
                        }, 10000);
                    }
                });
            }
        });
        $('#presenceForm').validate({
            rules: {
                presence_id: {
                    required: true
                }
                , name: {
                    required: true
                }
            }
            , messages: {
                presence_id: {
                    required: "Please input Department ID"
                }
                , name: {
                    required: "Please input Department Name"
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



        $('body').on('click', '.editpresence', function() {
            var presenceId = $(this).data('id');
            console.log(presenceId)
            $.get("{{ route('presence.index') }}" + "/" + presenceId + "/edit", function(data) {
                $('#headerForm').html("<h4>Edit presence</h4>");
                // $('#roleModal').modal('show');
                // $(".modal-backdrop").hide();
                // console.log(data)
                $("#id").val(data.id);
                $("#presence_id").val(data.presence_id);
                $('#name').val(data.name);
            })
        })


        $('body').on('click', '.deleteDepartment', function() {
            var presenceId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "presence" + '/' + presenceId + '/delete';
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
                    url: "{{ url('presence/filter') }}"
                    , data: function(d) {
                        d.min = $('#min').val();
                        d.max = $('#max').val();

                    }
                }
                , columns: [{
                        data: 'DT_RowIndex'
                        , name: 'DT_RowIndex'
                        , orderable: false
                        , searchable: false
                    }, {
                        data: 'uid'
                        , name: 'uid'
                    }, {
                        data: 'name'
                        , name: 'name'
                    }, {
                        data: 'loginTime'
                        , name: 'loginTime'
                    }, {
                        data: 'logoutTime'
                        , name: 'logoutTime'
                    }, {
                        data: 'date'
                        , name: 'date'
                    }, {
                        data: 'shiftwork'
                        , name: 'shiftwork'
                    }, {
                        data: 'checkIn'
                        , name: 'checkIn'
                    }, {
                        data: 'checkOut'
                        , name: 'checkOut'
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
