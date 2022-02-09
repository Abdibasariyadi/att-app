<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $title = "Dashboard";

        // Chart Team
        $teams = DB::table('anggota as a')
            ->select('b.nama', DB::raw('month(a.created_at) as bln'), DB::raw('year(a.created_at) as thn'), DB::raw('count(a.id) as total'))
            ->leftJoin('team as b', 'a.team_id', '=', 'b.id')
            ->groupByRaw('month(a.created_at), year(a.created_at), b.nama')
            ->get();
        $team = $teams->toArray();

        foreach ($team as $row) {
            if ($row->nama == "A") {
                $a[$row->bln] = $row->total;
            } else if ($row->nama == "B") {
                $b[$row->bln] = $row->total;
            }
        }

        $a_str = '';
        for ($i = 1; $i <= 12; $i++) {
            $a_str .= (isset($a[$i]) ? $a[$i] : 0) . ",";
        }
        $data['a_str'] = rtrim($a_str, ',');

        $b_str = '';
        for ($i = 1; $i <= 12; $i++) {
            $b_str .= (isset($b[$i]) ? $b[$i] : 0) . ",";
        }
        $data['b_str'] = rtrim($b_str, ',');

        // End Chart Team


        // Chart Kelurahan
        $pieDesa = DB::table('anggota')
            ->select('name', DB::raw('COUNT(desa_id) as count'))
            ->join('villages', 'villages.id', '=', 'anggota.desa_id')
            ->groupBy('desa_id', 'name')
            ->get();
        // End Chart Kelurahan

        return view('dashboard', compact(
            'title',
            'a_str',
            'b_str',
            'pieDesa'
        ));
    }

    public function filterDesa($min, $max)
    {
        // Chart Kelurahan
        return $pieDesa = Anggota::pie_chart_js($min, $max);
        // echo json_encode($pieDesa);
        // dd($pieDesa);
        // End Chart Kelurahan
    }
}
