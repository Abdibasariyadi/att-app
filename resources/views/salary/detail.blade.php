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
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <img src="{{ asset('uploads/'.$employee->photo)}}" width="300">
                        <br>
                        <br>
                        <h3>{{ $employee->name }}</h3>
                    </div>
                    <div class="col-sm-8">
                        <form id="componentForm" name="componentForm" action="{{ route('salary.add-salary-component') }}" method="POST">
                            @csrf
                            <input type="hidden" id="salary_id" name="salary_id" value="{{ $salary->id }}">

                            <div class="input-group col-6" style="margin-left:-15px; margin-bottom:25px;">
                                <div class="custom-file">
                                    <select id="component_code" name="component_code" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                        <option selected value="">--Select--</option>
                                        @foreach($componentSalary as $component_code => $component_name)
                                        <option value="{{ $component_code }}">{{ $component_name }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <label class="custom-file-label" for="inputGroupFile04">Choose file</label> --}}
                                </div>
                                <div class="input-group-append">
                                    <button id="salaryBtn" class="btn btn-primary">Create</button>
                                </div>
                            </div>

                        </form>
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered data-table table-sm table-hover">
                                        <thead>

                                            <tr role="row">
                                                <th width="30%">Salary Component</th>
                                                <th width="30%">Amount ($)</th>
                                                <th width="20%">type</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>Basic Salary</td>
                                            <td>{{ $employee->salary }}</td>
                                            <td>Basic</td>
                                        </tr>
                                        <tr>
                                            <td>Positional Allowance</td>
                                            <td>{{ $employee->salary_position }}</td>
                                            <td>Allowance</td>
                                        </tr>
                                    </table>

                                    <table class="table table-bordered data-table table-sm table-hover">
                                        <thead>

                                            <tr role="row">
                                                <th width="30%"></th>
                                                <th width="30%"></th>
                                                <th width="20%"></th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                                $income = 0;
                                            ?>
                                        @if(isset($detailSalary))
                                        @foreach($detailSalary as $ds)
                                        <tr>
                                            <td>{{ $ds->component_name }}</td>
                                            <td>{{ $ds->amount}}</td>
                                            <td>{{ $ds->type}}</td>
                                            <td>
                                                {{ Form::open(['url'=>'salary/'.$ds->id.'/delete-salary','method'=>'delete'])}}
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                {{ Form::close()}}
                                            </td>
                                        </tr>
                                        <?php
                                                if($ds->type=='allowance')
                                                {
                                                    $income = $income + $ds->amount;
                                                }                        
                                                else
                                                {
                                                    $income = $income - $ds->amount;
                                                } 
                                            ?>
                                        @endforeach
                                        @endif

                                        <?php

                                            $total = $employee->salary + $employee->salary_position + $income;

                                            $overTimeTotal = number_format($overtimeCount) * 15;
                                            // echo "Total: ". $overTimeTotal;
                                            
                                        ?>
                                        <tr>
                                            <td>Overtime Pay</td>
                                            <td>{{ number_format($overtimeCount)}} Minutes X 15MMK</td>
                                            <td>Addition</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px"><b>Total Income</b></td>
                                            <td style="font-size:16px"><b>{{ $total + $overTimeTotal}} MMK</b></td>
                                        </tr>
                                    </table>

                                </div>
                            </div>



                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                        <h4>Attendance and Overtime History</h4>

                        <table class="table-bordered" style="width:100%;">
                            <tr>
                                <th height="50" style="text-align: center; vertical-align: middle;">Date</th>
                                <?php
                                                $month = substr($period,5,2);
                                                $year = substr($period,0,4);

                                                for($d=1; $d<=31; $d++)
                                                {
                                                    $time=mktime(12, 0, 0, $month, $d, $year);          
                                                    if (date('m', $time)==$month)       
                                                        //$list[]=date('Y-m-d-D', $time);
                                                        echo "<td height='50' style='text-align: center; vertical-align: middle;'>".date('d', $time)."</td>";
                                                }
                                            ?>
                            </tr>
                            <tr>
                                <td height="50" style="text-align: center; vertical-align: middle;">Attendance</td>
                                <?php
                                                $month = substr($period,5,2);
                                                $year = substr($period,0,4);

                                                for($d=1; $d<=31; $d++)
                                                {
                                                    $time=mktime(12, 0, 0, $month, $d, $year);          
                                                    if (date('m', $time)==$month)       
                                                        //$list[]=date('Y-m-d-D', $time);
                                                        echo "<td height='50' style='text-align: center; vertical-align: middle;'>".chekKehadiran($employee->uid,date('Y-m-d', $time))."</td>";
                                                }
                                            ?>
                            </tr>
                            <tr>
                                <td height="50" style="text-align: center; vertical-align: middle;">Overtime</td>
                                <?php
                                
                                    $month = substr($period,5,2);
                                    $year = substr($period,0,4);
                            
                                    for($d=1; $d<=31; $d++)
                                    {
                                            $time=mktime(12, 0, 0, $month, $d, $year);     
                                            // echo date('Y-m-d', $time); die;     
                                            if (date('m', $time)==$month)       
                                                //$list[]=date('Y-m-d-D', $time);
                                                echo "<td height='50' style='text-align: center; vertical-align: middle;'>".chekLembur($employee->uid, date('Y-m-d', $time))."</td>";
                                    }
                                ?>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
