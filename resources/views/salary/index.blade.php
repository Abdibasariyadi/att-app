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
                                        <th>UID</th>
                                        <th>Employee Name</th>
                                        <th>Salary Period</th>
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
            <div class="card-body">
                {{ Form::open(['url'=>'salary/changeperiod'])}}
                <div class="form-group">
                    <label>Filter Report</label>
                    {{ Form::select('period',$salaryPeriod,null,['class'=>'form-control'])}}
                    <button style="margin-top:5px;" type="submit" class="btn btn-info">Filter</button>
                </div>
                {{ Form::close()}}

                {{ Form::open(['url'=>'salary/create'])}}
                <div class="form-group">
                    <label>Salary Period</label>
                    {{ Form::text('period',null,['class'=>'form-control','placeholder'=>'Ex : 202207'])}}
                    <button style="margin-top:5px;" type="submit" class="btn btn-primary">Create</button>
                </div>
                {{ Form::close()}}
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
            , ajax: "{{ route('salary.index') }}"
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                }
                , {
                    data: 'uid'
                    , name: 'uid'
                }
                , {
                    data: 'name'
                    , name: 'name'
                }
                , {
                    data: 'period'
                    , name: 'period'
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
                    url: "{{ route('salarycomponent.create') }}"
                    , type: "POST"
                    , data: $('#componentForm').serialize()
                    , dataType: "json"
                    , success: function(response) {
                        table.draw();

                        $('#saveBtn').html('Create');
                        $('#res_message').html(response.msg);
                        $('#res_message').show();
                        $('#msg_div').removeClass('d-none');

                        document.getElementById("componentForm").reset();
                        setTimeout(function() {
                            $('#res_message').hide();
                            $('#msg_div').hide();
                        }, 10000);
                    }
                });
            }
        });
        $('#componentForm').validate({
            rules: {
                component_code: {
                    required: true
                }
                , component_name: {
                    required: true
                }
                , type: {
                    required: true
                }
                , amount: {
                    required: true
                }
            }
            , messages: {
                component_id: {
                    required: "Please input Position ID"
                }
                , component_name: {
                    required: "Please input Position Name"
                }
                , type: {
                    required: "Please input Type"
                }
                , amount: {
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



        $('body').on('click', '.editComponent', function() {
            var componentId = $(this).data('id');
            console.log(componentId)
            $.get("{{ route('salarycomponent.index') }}" + "/" + componentId + "/edit", function(data) {
                console.log('Component: ', data)
                $('#headerForm').html("<h4>Edit component</h4>");
                // $('#roleModal').modal('show');
                // $(".modal-backdrop").hide();
                $("#id").val(data.id);
                $("#component_code").val(data.component_code);
                $('#component_name').val(data.component_name);
                $('#type').val(data.type);
                $('#amount').val(data.amount);
            })
        })


        $('body').on('click', '.deleteComponent', function() {
            var componentId = $(this).data('id');
            confirm("Are you sure want to delete!");
            var url = "salarycomponent" + '/' + componentId + '/delete';
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
