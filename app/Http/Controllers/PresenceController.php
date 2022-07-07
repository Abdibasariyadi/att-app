<?php

namespace App\Http\Controllers;

use App\Models\PresenceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        $title = "Attendance History";
        $presence = DB::table('view_attendancehistory')->get();
        // dd($presence);
        if ($request->ajax()) {
            $allData = DataTables::of($presence)
                ->addIndexColumn()
                ->make(true);
            return $allData;
        }

        return view('presence.index', compact('presence', 'title'));
    }

    public function store(Request $request)
    {

        PresenceModel::updateOrCreate(
            ['id' => $request->id],
            [
                'presence_id' => $request->presence_id,
                'name' => $request->name,
            ]
        );

        return response()->json(['success' => 'Machine Added Successfully!']);
    }

    public function edit($id)
    {
        $presence = PresenceModel::find($id);
        return response()->json($presence);
    }

    public function destroy($id)
    {
        PresenceModel::find($id)->delete();
        return response()->json(['succes' => 'Department Delete Successfully!']);
    }

    public function filter(Request $request)
    {
        $min = date('Y-m-d 00:00:00', strtotime($request->get('min')));
        $max = date('Y-m-d 23:59:59', strtotime($request->get('max')));

        if (!empty($min) && !empty($max)) {
            $presence = PresenceModel::filterPresence($min, $max);
            return Datatables::of($presence)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
