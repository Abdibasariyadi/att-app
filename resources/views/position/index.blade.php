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
                                        <th>Position ID</th>
                                        <th>Name</th>
                                        <th>Positional Allowance</th>
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
                <h4>Create New Position</h4>
            </div>
            <form id="positionForm" name="positionForm" method="POST">
                <input type="hidden" id="id" name="id">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Position ID</label>
                        <input type="text" class="form-control" id="position_id" name="position_id" onkeyup="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name" onkeyup="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                        <label>Positional Allowance</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <input type="number" id="salary_position" name="salary_position" class="form-control">
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
            , ajax: "{{ route('position.index') }}"
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                }
                , {
                    data: 'position_id'
                    , name: 'position_id'
                }
                , {
                    data: 'name'
                    , name: 'name'
                }
                , {
                    data: 'salary_position'
                    , name: 'salary_position'
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
                    url: "{{ route('position.create') }}"
                    , type: "POST"
                    , data: $('#positionForm').serialize()
                    , dataType: "json"
                    , success: function(response) {
                        table.draw();

                        $('#saveBtn').html('Create');
                        $('#res_message').html(response.msg);
                        $('#res_message').show();
                        $('#msg_div').removeClass('d-none');

                        document.getElementById("positionForm").reset();
                        setTimeout(function() {
                            $('#res_message').hide();
                            $('#msg_div').hide();
                        }, 10000);
                    }
                });
            }
        });
        $('#positionForm').validate({
            rules: {
                position_id: {
                    required: true
                }
                , name: {
                    required: true
                }
                , salary_position: {
                    required: true
                }
            }
            , messages: {
                position_id: {
                    required: "Please input Position ID"
                }
                , name: {
                    required: "Please input Position Name"
                }
                , salary_position: {
                    required: "Please input Salary"
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



        $('body').on('click', '.editPosition', function() {
            var positionId = $(this).data('id');
            console.log(positionId)
            $.get("{{ route('position.index') }}" + "/" + positionId + "/edit", function(data) {
                $('#headerForm').html("<h4>Edit position</h4>");
                // $('#roleModal').modal('show');
                // $(".modal-backdrop").hide();
                $("#id").val(data.id);
                $("#position_id").val(data.position_id);
                $('#name').val(data.name);
                $('#salary_position').val(data.salary_position);
            })
        })


        $('body').on('click', '.deletePosition', function() {
            var positionId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "position" + '/' + positionId + '/delete';
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
