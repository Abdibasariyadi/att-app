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
                                <tbody>
                                    @foreach($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                                        <td><a href="{{ route('assign.user.edit', $user) }}">Sync</a></td>
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

    <div class="col-12 col-md-4 col-lg-4">
        <div class="card">
            <div id="headerForm" class="card-header">
                <h4>Create Role</h4>
            </div>
            <form id="assignForm" action="{{ route('assign.user.create') }}" name="assignForm" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>User</label>
                        <input type="text" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label>Pick Roles</label>
                        <select id="roles" name="roles[]" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" multiple>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <div class="text-danger mt-2 d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button id="saveBtnAssigns" type="submit" class="btn btn-primary">Create</button>
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