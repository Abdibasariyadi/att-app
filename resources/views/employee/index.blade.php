@extends('layouts.app')

@section('content')

<style>
    .modal-backdrop {
        /* bug fix - no overlay */
        display: none;
    }

</style>
<div class="section-header">
    <h1>{{ $title }}</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a class="btn btn-outline-primary" href="{{ route('employee.create') }}">Create Employee</a>
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
                                        <th>Email</th>
                                        <th>Date Of Birth</th>
                                        <th>Gender</th>
                                        <th>Photo</th>
                                        <th>Department</th>
                                        <th>Position</th>
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
            , order: []
            , ajax: "{{ route('employee.index') }}"
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
                    data: 'email'
                    , name: 'email'
                }, {
                    data: 'dateOfBirth'
                    , name: 'dateOfBirth'
                }, {
                    data: 'gender'
                    , name: 'gender'
                }
                , {
                    data: 'photo'
                    , name: 'photo'
                }
                , {
                    data: 'department_id'
                    , name: 'department_id'
                }
                , {
                    data: 'position_id'
                    , name: 'position_id'
                }
                , {

                    data: 'action'
                    , name: 'action'
                    , orderable: false
                    , searchable: false
                }
            , ]
        })

        $('body').on('click', '.deleteEmployee', function() {
            var employeeId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "employee" + '/' + employeeId + '/delete';
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
