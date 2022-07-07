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
                                        <th>Department ID</th>
                                        <th>Name</th>
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
                <h4>Create New Department</h4>
            </div>
            <form id="departmentForm" name="departmentForm" method="POST">
                <input type="hidden" id="id" name="id">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Department ID</label>
                        <input type="text" class="form-control" id="department_id" name="department_id" onkeyup="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name" onkeyup="this.value = this.value.toUpperCase()">
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
            , ajax: "{{ route('department.index') }}"
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                }
                , {
                    data: 'department_id'
                    , name: 'department_id'
                }
                , {
                    data: 'name'
                    , name: 'name'
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
                    url: "{{ route('department.create') }}"
                    , type: "POST"
                    , data: $('#departmentForm').serialize()
                    , dataType: "json"
                    , success: function(response) {
                        table.draw();

                        $('#saveBtn').html('Create');
                        $('#res_message').html(response.msg);
                        $('#res_message').show();
                        $('#msg_div').removeClass('d-none');

                        document.getElementById("departmentForm").reset();
                        setTimeout(function() {
                            $('#res_message').hide();
                            $('#msg_div').hide();
                        }, 10000);
                    }
                });
            }
        });
        $('#departmentForm').validate({
            rules: {
                department_id: {
                    required: true
                }
                , name: {
                    required: true
                }
            }
            , messages: {
                department_id: {
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



        $('body').on('click', '.editdepartment', function() {
            var departmentId = $(this).data('id');
            console.log(departmentId)
            $.get("{{ route('department.index') }}" + "/" + departmentId + "/edit", function(data) {
                $('#headerForm').html("<h4>Edit department</h4>");
                // $('#roleModal').modal('show');
                // $(".modal-backdrop").hide();
                // console.log(data)
                $("#id").val(data.id);
                $("#department_id").val(data.department_id);
                $('#name').val(data.name);
            })
        })


        $('body').on('click', '.deleteDepartment', function() {
            var departmentId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "department" + '/' + departmentId + '/delete';
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
