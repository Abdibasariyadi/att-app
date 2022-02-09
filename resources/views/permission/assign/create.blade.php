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
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<div class="row">
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div id="headerForm" class="card-header">
                <h4>Create Role</h4>
            </div>
            <form id="assignForm" action="{{ route('assigns.create') }}" name="assignForm" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Role Name</label>
                        <select id="assigns" name="role" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option disabled selected>--Pilih--</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <div class="text-danger mt-2 d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Permission Name</label>
                        <select name="permissions[]" id="assigns" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" multiple>
                            @foreach($permissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>
                        @error('permissions')
                        <div class="text-danger mt-2 d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button id="saveBtnAssigns" type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
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
                                        <th>The Permissions</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $index => $role)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->guard_name }}</td>
                                        <td>{{ implode(', ', $role->getPermissionNames()->toArray()) }}</td>
                                        <td><a href="{{ route('assigns.edit', $role) }}">Sync</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // var table = $('.data-table').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: "{{ route('assigns.create') }}",
        //     columns: [{
        //             data: 'DT_RowIndex',
        //             name: 'DT_RowIndex'
        //         },
        //         {
        //             data: 'name',
        //             name: 'name'
        //         },
        //         {
        //             data: 'guard_name',
        //             name: 'guard_name'
        //         },
        //         {
        //             data: 'permissions.name',
        //             name: 'permissions.name'
        //         },
        //         {
        //             data: 'action',
        //             name: 'action',
        //             orderable: false,
        //             searchable: false
        //         },
        //     ]
        // })


        // $('#saveBtnAssigns').click(function(e) {
        //     e.preventDefault();
        //     $(this).html('Save');

        //     $.ajax({
        //         data: $('#assignForm').serialize(),
        //         url: "{{ route('assigns.create') }}",
        //         type: "POST",
        //         dataType: 'json',
        //         success: function(data) {

        //             $("#assignForm").trigger("reset");
        //             // $('#roleModal').modal('hide');
        //             table.draw();
        //         },
        //         error: function(data) {
        //             console.log('Error:', data);
        //             $('#saveBtnAssigns').html('Save')
        //         }
        //     })
        // })



        $('body').on('click', '.editPermission', function() {
            var permissionId = $(this).data('id');
            $.get("{{ route('permissions.index') }}" + "/" + permissionId + "/edit", function(data) {
                $('#headerForm').html("<h4>Edit Permission</h4>");
                // $('#roleModal').modal('show');
                // $(".modal-backdrop").hide();
                $("#role_id").val(data.id);
                $('#name').val(data.name);
                $('#guard_name').val(data.guard_name);
            })
        })


        $('body').on('click', '.deletePermission', function() {
            var permissionId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "permissions" + '/' + permissionId + '/delete';
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