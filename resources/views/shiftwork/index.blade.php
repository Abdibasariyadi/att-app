@extends('layouts.app')

@section('content')


<div class="section-header">
    <h1>{{ $title }}</h1>
</div>

<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h4>Machine Data</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered data-table table-sm table-hover">
                                <thead>

                                    <tr role="row">
                                        <th>#</th>
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
                <h4>Create New Shif tWork</h4>
            </div>
            <form id="shiftworkForm" name="shiftworkForm" method="POST">
                <input type="hidden" id="id" name="id">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Shift Work</label>
                        <input type="text" class="form-control" id="shiftwork" name="shiftwork" onkeyup="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                        <label>Check In</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <input type="time" name="checkIn" class="form-control">
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
                            <input type="time" class="form-control">
                        </div>
                    </div>
                    <button id="saveBtn" class="btn btn-primary">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            processing: true
            , serverSide: true
            , ajax: "{{ route('shiftwork.index') }}"
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
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
                    url: "{{ route('shiftwork.create') }}"
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
                shiftwork: {
                    required: true
                }
            }
            , messages: {
                shiftwork: {
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



        $('body').on('click', '.editShiftWork', function() {
            var shiftworkId = $(this).data('id');
            console.log(shiftworkId)
            $.get("{{ route('shiftwork.index') }}" + "/" + shiftworkId + "/edit", function(data) {
                $('#headerForm').html("<h4>Edit shiftwork</h4>");
                // $('#roleModal').modal('show');
                // $(".modal-backdrop").hide();
                $("#id").val(data.id);
                $("#shiftwork_id").val(data.shiftwork_id);
                $('#name').val(data.name);
            })
        })


        $('body').on('click', '.deleteShiftWork', function() {
            var shiftworkId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "shiftwork" + '/' + shiftworkId + '/delete';
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
