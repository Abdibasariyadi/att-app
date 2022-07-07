@extends('layouts.app')

@section('content')


<div class="section-header">
    <h1>{{ $title }}</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            {{-- <div class="card-header">
                <h4>Machine Data</h4>
            </div> --}}
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-bordered">
                            <tr role="row">
                                <td width="35%">Team Work Name :</td>
                                <td>{{ $teamWork->TeamWorkName }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <form id="teamworkForm" name="teamworkForm" method="POST">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ $teamWork->id }}">
                            <table class="table table-bordered">
                                <tr>
                                    <td>
                                        <input class="form-control" type="text" id="name" name="name" placeholder="Search name...">
                                    </td>
                                    <td><button type="submit" id="addTeamBtn" class="btn btn-info">Add Team</button></td>
                                </tr>
                            </table>
                        </form>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered data-table table-sm table-hover" id="tableGroup">
                                            <thead>
                                                <tr role="row">
                                                    <th>#</th>
                                                    <th>UID</th>
                                                    <th width="60%">Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php

                                                $no=1;
                                                @endphp
                                                @foreach($groupWork as $row)
                                                <tr>
                                                    <td>{{ $no++}}</td>
                                                    <td>{{ $row->uid}}</td>
                                                    <td>{{ $row->name}}</td>
                                                    <td>
                                                        {{-- <button type="submit" id="deleteName" class="btn btn-danger btn-sm"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                                            Delete</button> --}}
                                                        {{ Form::open(['url'=>'teamwork/delete-groupwork/'.$row->id,'method'=>'delete'])}}
                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                                            Hapus</button>
                                                        {{ Form::close()}}
                                                    </td>
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

        $(function() {
            $('#tableGroup').DataTable()
            $('#tableGroup2').DataTable({
                'paging': true
                , 'lengthChange': false
                , 'searching': false
                , 'ordering': true
                , 'info': true
                , 'autoWidth': false
            })
        })

        $.validator.setDefaults({
            submitHandler: function() {
                $('#addTeamBtn').html('Saving..');
                $.ajax({
                    url: "{{ route('teamwork.addteamwork') }}"
                    , type: "POST"
                    , data: $('#teamworkForm').serialize()
                    , dataType: "json"
                    , success: function(response) {
                        location.reload()
                        $('#addTeamBtn').html('Create');
                        $('#res_message').html(response.msg);
                        $('#res_message').show();
                        $('#msg_div').removeClass('d-none');

                        document.getElementById("teamworkForm").reset();
                        setTimeout(function() {
                            $('#res_message').hide();
                            $('#msg_div').hide();
                        }, 10000);
                    }
                });
            }
        });
        $('#teamworkForm').validate({
            rules: {
                TeamWorkName: {
                    required: true
                }
            }
            , messages: {
                TeamWorkName: {
                    required: "Please input teamwork ID"
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


        $('body').on('click', '#deleteName', function() {
            var groupworkId = $('#groupWorkId').val();

            console.log(groupworkId);
            confirm("Are you sure want to delete!");
            var url = "delete-groupwork" + '/' + groupworkId;
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

        var route = "{{ url('search') }}";

        $('#name').typeahead({
            source: function(query, process) {
                return $.get(route, {
                    query: query
                }, function(data) {
                    return process(data);
                });
            }
        });
    })

</script>
@endsection
