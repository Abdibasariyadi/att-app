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
                                        <th>Name</th>
                                        <th>IP</th>
                                        <th>Port</th>
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
                <h4>Create New Machine</h4>
            </div>
            <form id="machineForm" name="machineForm" method="POST">
                <input type="hidden" id="tps_id" name="tps_id">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name" onkeyup="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                        <label>IP</label>
                        <input type="text" class="form-control" id="ipAddress" name="ipAddress" onkeyup="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                        <label>Port</label>
                        <input type="text" class="form-control" id="port" name="port" onkeyup="this.value = this.value.toUpperCase()">
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
            , ajax: "{{ route('machine.index') }}"
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                }
                , {
                    data: 'name'
                    , name: 'name'
                }
                , {
                    data: 'ipAddress'
                    , name: 'ipAddress'
                }
                , {
                    data: 'port'
                    , name: 'port'
                }
                , {
                    data: 'action'
                    , name: 'action'
                    , orderable: false
                    , searchable: false
                }
            , ]
        })

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving...');

            $.ajax({
                data: $('#machineForm').serialize()
                , url: "{{ route('machine.create') }}"
                , type: "POST"
                , dataType: 'json'
                , success: function(data) {
                    $("#machineForm").trigger("reset");
                    // $('#roleModal').modal('hide');
                    table.draw();
                    $('#saveBtn').html('Saving...')

                }
                , error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Saving...')
                }
            })
        })



        $('body').on('click', '.editmachine', function() {
            var machineId = $(this).data('id');
            $.get("{{ route('machine.index') }}" + "/" + machineId + "/edit", function(data) {
                $('#headerForm').html("<h4>Edit machine</h4>");
                // $('#roleModal').modal('show');
                // $(".modal-backdrop").hide();
                $("#machine_id").val(data.id);
                $('#name').val(data.name);
            })
        })


        $('body').on('click', '.deletemachine', function() {
            var machineId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "machine" + '/' + tpsId + '/delete';
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
