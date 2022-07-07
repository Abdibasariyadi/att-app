@extends('layouts.app')

@section('content')


<div class="section-header">
    <h1>{{ $title }}</h1>
</div>

<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered data-table table-sm table-hover">
                                <thead>

                                    <tr role="row">
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Description</th>
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
                <h4>Create New Work Calendar</h4>
            </div>
            <form id="workcalendarForm" name="workcalendarForm" method="POST">
                <input type="hidden" id="id" name="id">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="text" class="form-control datepickerEmp" id="date" name="date">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" id="description" name="description" onkeyup="this.value = this.value.toUpperCase()">
                    </div>
                    <button id="saveBtn" class="btn btn-primary">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {

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
            , ajax: "{{ route('workcalendar.index') }}"
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                }
                , {
                    data: 'date'
                    , name: 'date'
                }
                , {
                    data: 'description'
                    , name: 'description'
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
                    url: "{{ route('workcalendar.create') }}"
                    , type: "POST"
                    , data: $('#workcalendarForm').serialize()
                    , dataType: "json"
                    , success: function(response) {
                        table.draw();

                        $('#saveBtn').html('Create');
                        $('#res_message').html(response.msg);
                        $('#res_message').show();
                        $('#msg_div').removeClass('d-none');

                        document.getElementById("workcalendarForm").reset();
                        setTimeout(function() {
                            $('#res_message').hide();
                            $('#msg_div').hide();
                        }, 10000);
                    }
                });
            }
        });
        $('#workcalendarForm').validate({
            rules: {
                date: {
                    required: true
                }
                , description: {
                    required: true
                }
            }
            , messages: {
                date: {
                    required: "Please input date Work Calendar"
                }
                , description: {
                    required: "Please input description"
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



        $('body').on('click', '.editworkcalendar', function() {
            var workcalendarId = $(this).data('id');
            console.log(workcalendarId)
            $.get("{{ route('workcalendar.index') }}" + "/" + workcalendarId + "/edit", function(data) {
                $('#headerForm').html("<h4>Edit workcalendar</h4>");
                // $('#roleModal').modal('show');
                // $(".modal-backdrop").hide();
                // console.log(data)
                $("#id").val(data.id);
                $("#date").val(data.date);
                $('#description').val(data.description);
            })
        })


        $('body').on('click', '.deleteworkcalendar', function() {
            var workcalendarId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "workcalendar" + '/' + workcalendarId + '/delete';
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
