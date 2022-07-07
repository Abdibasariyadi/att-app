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
                                        <th>Component Code</th>
                                        <th>Component Name</th>
                                        <th>Type</th>
                                        <th>Amount ($)</th>
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
                <h4>Create New Component Salary</h4>
            </div>
            <form id="componentForm" name="componentForm" method="POST">
                <input type="hidden" id="id" name="id">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Component Salary Code</label>
                        <input type="text" class="form-control" id="component_code" name="component_code" onkeyup="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                        <label>Component Salary Name</label>
                        <input type="text" class="form-control" id="component_name" name="component_name" onkeyup="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <div class="input-group">
                            <select id="type" name="type" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value="" disabled selected>--Select--</option>
                                <option value="allowance">Allowance</option>
                                <option value="deduction">Deduction</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <input type="number" id="amount" name="amount" class="form-control">
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
            , ajax: "{{ route('salarycomponent.index') }}"
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                }
                , {
                    data: 'component_code'
                    , name: 'component_code'
                }
                , {
                    data: 'component_name'
                    , name: 'component_name'
                }
                , {
                    data: 'type'
                    , name: 'type'
                }
                , {
                    data: 'amount'
                    , name: 'amount'
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
