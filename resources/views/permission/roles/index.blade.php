@extends('layouts.app')

@section('content')

<div class="section-header">
    <h1>{{ $title }}</h1>
</div>

<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h4>Basic DataTables</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered data-table table-sm table-hover">
                                <thead>

                                    <tr role="row">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Guard Name</th>
                                        <th>Created At</th>
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
                <h4>Create Role</h4>
            </div>
            <form id="roleForm" name="roleForm" method="POST">
                <input type="hidden" id="role_id" name="role_id">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label>Guard Name</label>
                        <input type="text" class="form-control" id="guard_name" name="guard_name">
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
            processing: true,
            serverSide: true,
            ajax: "{{ route('roles.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'guard_name',
                    name: 'guard_name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        })

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Save');

            $.ajax({
                data: $('#roleForm').serialize(),
                url: "{{ route('roles.create') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $("#roleForm").trigger("reset");
                    // $('#roleModal').modal('hide');
                    table.draw();
                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save')
                }
            })
        })



        $('body').on('click', '.editRole', function() {
            var roleId = $(this).data('id');
            $.get("{{ route('roles.index') }}" + "/" + roleId + "/edit", function(data) {
                $('#headerForm').html("<h4>Edit Role</h4>");
                // $('#roleModal').modal('show');
                // $(".modal-backdrop").hide();
                $("#role_id").val(data.id);
                $('#name').val(data.name);
                $('#guard_name').val(data.guard_name);
            })
        })


        $('body').on('click', '.deleteRole', function() {
            var roleId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "roles" + '/' + roleId + '/delete';
            $.ajax({
                type: "DELETE",
                url,
                success: function(data) {
                    table.draw();
                },
                error: function(data) {
                    console.log('Error: ', data);
                }
            })
        })
    })
</script>
@endsection