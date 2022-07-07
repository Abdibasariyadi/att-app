<?php

namespace App\Http\Controllers;

use App\Models\SalaryComponentModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SalaryComponentController extends Controller
{
    public function index(Request $request)
    {
        $title = "Component Salary Data";
        $componentsalary = SalaryComponentModel::get();
        // dd($machine);
        if ($request->ajax()) {
            $allData = DataTables::of($componentsalary)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd($row->id);
                    $btn = '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editComponent">Edit</a> | ';
                    $btn .= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteComponent">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $allData;
        }

        return view('componentsalary.index', compact('componentsalary', 'title'));
    }

    public function store(Request $request)
    {

        SalaryComponentModel::updateOrCreate(
            ['id' => $request->id],
            [
                'component_code' => $request->component_code,
                'component_name' => $request->component_name,
                'type' => $request->type,
                'amount' => $request->amount
            ]
        );

        return response()->json(['success' => 'Component Salary Added Successfully!']);
    }

    public function edit($id)
    {
        $department = SalaryComponentModel::find($id);
        return response()->json($department);
    }

    public function destroy($id)
    {
        SalaryComponentModel::find($id)->delete();
        return response()->json(['succes' => 'Department Delete Successfully!']);
    }
}
