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
                                        <th>Parent</th>
                                        <th>Name</th>
                                        <th>Url</th>
                                        <th>Permission Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- @foreach($navigation as $index => $nav)                                    
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td><strong>{{ $nav->parent->name }}</strong></td>
                                        <td>{{ $nav->name }}</td>
                                        <td>{{ $nav->url ?? "It's a parent" }}</td>
                                        <td>{{ $nav->permission_name }}</td>
                                    </tr>
                                    @endforeach -->
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
            <form id="navForm" name="navForm" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Parent</label>
                        <select id="parent_id" name="parent_id" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option disabled selected>--Pilih--</option>
                            @foreach($navigations as $navigation)
                            <option value="{{ $navigation->id }}">{{ $navigation->name }}</option>
                            @endforeach
                        </select>
                        @error('navigation')
                        <div class="text-danger mt-2 d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Permissions</label>
                        <select id="permission_name" name="permission_name" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option disabled selected>--Pilih--</option>
                            @foreach($permissions as $permission)
                            <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>
                        @error('permission')
                        <div class="text-danger mt-2 d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label>Url</label>
                        <input type="text" class="form-control" id="url" name="url">
                    </div>

                    <button id="saveBtnNav" type="submit" class="btn btn-primary">Create</button>
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
            ajax: "{{ route('navigation.create') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'parent_id',
                    name: 'parent_id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'url',
                    name: 'url'
                },
                {
                    data: 'permission_name',
                    name: 'permission_name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        })
    })
</script>
@endsection