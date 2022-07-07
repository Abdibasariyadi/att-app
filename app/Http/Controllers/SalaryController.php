<?php

namespace App\Http\Controllers;

use App\Models\SalaryComponentModel;
use App\Models\SalaryModel;
use Illuminate\Http\Request;
use Illuminate\Support\FacadesDB;
use Yajra\DataTables\DataTables;
use Fpdf;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    public function index(Request $request)
    {

        $salaryPeriod = Session('salary_period');
        if ($salaryPeriod == '') {
            $periode = date('Ym');
        } else {
            $periode = $salaryPeriod;
        }

        $data['title'] = "Salary Report";
        $getSalary = SalaryModel::join('employee', 'employee.uid', '=', 'salary.uid')
            ->select('salary.id', 'salary.uid', 'employee.name', 'salary.period')
            ->where('salary.period', $periode)
            ->get();
        // dd($getSalary);
        $data['salaryPeriod'] = SalaryModel::pluck('period', 'period');
        // dd($machine);
        if ($request->ajax()) {
            $allData = DataTables::of($getSalary)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd($row->id);
                    $btn = '<a href="salary/' . $row->id . '/detail" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Detail" class="edit btn btn-primary btn-sm editComponent">Detail</a> | ';
                    $btn .= '<a href="salary/' . $row->id . '/pdf" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-warning btn-sm">Print</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $allData;
        }

        return view('salary.index', $data);
    }

    public function store(Request $request)
    {

        $period = $request->period;

        $data = [];

        $employee = DB::table('employee')->select('uid')->get();
        foreach ($employee as $em) {
            $data[] = [
                'uid' => $em->uid,
                'period' => $period
            ];
        }

        DB::table('salary')->insert($data);
        return redirect('salary')->with('message', 'period salary ' . $period . ' Sudah Dibuat');
    }

    public function detail($id)
    {
        $data['title'] = "Salary Report Detail";
        $data['componentSalary'] = SalaryComponentModel::pluck('component_name', 'component_code');
        $salary = SalaryModel::find($id);

        $period = substr($salary->period, 0, 4) . '-' . substr($salary->period, 4, 2);

        $data['salary'] = $salary;
        $data['employee'] = DB::table('employee')
            ->leftJoin('position', 'employee.position_id', '=', 'position.position_id')
            ->select('employee.uid', 'employee.name as name', 'position.name as position', 'salary', 'salary_position', 'photo')
            ->where('employee.uid', $salary->uid)
            ->first();
        // dd($data['employee']);
        $data['detailSalary'] = DB::table('salary_detail')
            ->join('salary_component', 'salary_component.component_code', '=', 'salary_detail.component_code')
            ->select('salary_detail.*', 'salary_component.component_name', 'salary_component.component_code', 'salary_component.type', 'salary_component.amount')
            ->where('salary_detail.salary_id', $id)
            ->get();
// dd($data['detailSalary']);
        $overtimeCount = DB::select("SELECT SUM(UNIX_TIMESTAMP(logoutTime) - UNIX_TIMESTAMP(checkOut)) as seconds 
                        FROM view_attendancehistory
                        WHERE left(cast(date as date),7)='" . $period . "' and uid = '" . $salary->uid . "'");

        $data['overtimeCount']= $overtimeCount[0]->seconds / 60;

        $data['period'] = $period;
        return view('salary.detail', $data);
    }

    public function destroy($id)
    {
        SalaryModel::find($id)->delete();
        return response()->json(['success' => 'Department Delete Successfully!']);
    }

    function changeSalaryPeriod(Request $request)
    {
        Session(['salary_period' => $request->period]);
        // return response()->json(['success' => 'Change period Successfully!']);
        return redirect('salary')->with('message', 'Periode Gaji Diset Menjadi : ' . $request->period);
    }

    public function addSalaryComponent(Request $request)
    {
        $data = ['component_code' => $request->component_code, 'salary_id' => $request->salary_id];
        DB::table('salary_detail')->insert($data);

        return redirect('salary/' . $request->salary_id . '/detail')->with('message', 'Komponen Gaji Berhasil Ditambahkan');
    }

    function deleteSalaryDetail($id)
    {
        $salary = DB::table('salary_detail')->where('id', $id)->first();
        DB::table('salary_detail')->where('id', $id)->delete();
        return redirect('salary/' . $salary->salary_id . '/detail')->with('message', 'Komponen salary Berhasil Dihapus');
    }

    function pdf($id)
    {
        // ========= Query Database Untuk Mendapatkan Data Gaji =======================

        $salary                   = SalaryModel::find($id);
        $employee               = DB::table('employee')
            ->join('position', 'employee.position_id', '=', 'position.position_id')
            ->join('departemen', 'employee.department_id', '=', 'departemen.department_id')
            ->select('departemen.name as department', 'employee.uid', 'employee.name as name', 'position.name as position', 'salary', 'salary_position', 'photo')
            ->where('employee.uid', $salary->uid)
            ->first();
        $setting             = DB::table('setting')->where('id', 1)->first();;

        $yearPeriod = substr($salary->period, 0, 4);
        $monthPeriod = substr($salary->period, 4, 2);

        $periode_sekarang = '01/' . substr($salary->period, 4, 2) . '/' . substr($salary->period, 0, 4);

        $periode_bln_depan = '01/' . date('m-Y', strtotime('+1 month', strtotime('2019-04')));

        Fpdf::AddPage('L', 'A5');
        Fpdf::SetFont('Arial', 'B', 14);
        Fpdf::Cell(190, 7, 'EMPLOYEE SALARY SLIP REPORT', 1, 1, 'C');
        Fpdf::Cell(190, 16, '', 1, 1, 'C');
        Fpdf::SetFont('Arial', 'B', 8);

        Fpdf::text(12, 22, 'Company Name');
        Fpdf::text(38, 22, ' : ' . $setting->company_name);
        Fpdf::text(12, 26, 'Period');
        Fpdf::text(38, 26, ' : ' . $periode_sekarang . ' - ' . $periode_bln_depan);
        Fpdf::text(12, 30, 'Department');
        Fpdf::text(38, 30, ' : ' . $employee->department);


        Fpdf::text(110, 22, 'UID');
        Fpdf::text(136, 22, ' : ' . $employee->uid);
        Fpdf::text(110, 26, 'Employee Name');
        Fpdf::text(136, 26, ' : ' . $employee->name);
        Fpdf::text(110, 30, 'Position');
        Fpdf::text(136, 30, ' : ' . $employee->position);

        Fpdf::Cell(190, 90, '', 1, 1, 'C');
        // ---------------------------------------
        Fpdf::text(12, 40, 'Received ( + )');
        Fpdf::line(12, 75, 210 - 20, 75);
        Fpdf::line(12, 42, 110 - 20, 42);
        Fpdf::line(110, 42, 210 - 20, 42);


        // KALKULASI KOMPONEN GAJI --------------------------

        $total_penerimaan = 0;
        $total_potongan = 0;

        $jml_kehadiran = hitungJmlKehadiran($employee->uid, $yearPeriod . '-' . $monthPeriod);


        // gaji pokok harian
        $gph = $employee->salary / 24;
        // gaji berdasarkan kehadiran
        $gbh = $gph * $jml_kehadiran;
        $total_penerimaan = $total_penerimaan + $gbh;



        $penerimaan = [
            [
                'component_code' => 'BS',
                'component_name' => 'Basic Salary',
                'amount' => $employee->salary
            ],
            [
                'component_code' => 'DBS',
                'component_name' => 'Daily Basic Salary',
                'amount' => $gph
            ],
            [
                'component_code' => 'GBH',
                'component_name' => 'Salary Based on Attendance (' . $jml_kehadiran . ')',
                'amount' => $gbh
            ]
        ];

        $potongan = [];

        // =============== KOMPONEN GAJI DETAIL ======================
        $salary_detail =  DB::table('salary_detail')
            ->join('salary_component', 'salary_component.component_code', '=', 'salary_detail.component_code')
            ->where('salary_detail.salary_id', $id)
            ->get()->toArray();

        foreach ($salary_detail as $gd) {
            $komponen_baru = ['component_code' => $gd->component_code, 'component_name' => $gd->component_name, 'amount' => $gd->amount];

            if ($gd->type == 'allowance') {
                array_push($penerimaan, $komponen_baru);
            } else {
                array_push($potongan, $komponen_baru);
            }
        }

        // ============== HITUNG LEMBUR ==============================================
        $overtimeCount = DB::select("select sum(duration) as duration 
                            from overtime where left(cast(checkIn as date),7)='" . $yearPeriod . '-' . $monthPeriod . "' 
                            and uid='" . $salary->uid . "'");
        $upahLembur   = $overtimeCount[0]->duration * 20000;

        $lembur = ['component_code' => 'LBR', 'component_name' => 'Overtime Wage', 'amount' => $upahLembur];
        array_push($penerimaan, $lembur);


        $start = 48;
        foreach ($penerimaan as $p) {
            Fpdf::text(12, $start, $p['component_code']);
            Fpdf::text(24, $start, $p['component_name']);
            Fpdf::text(74, $start, ': ' . rupiah($p['amount']));

            if ($p['component_code'] != 'GPH' and $p['component_code'] != 'GP' and $p['component_code'] != 'GBH') {
                $total_penerimaan += $p['amount'];
            }

            $start = $start + 5;
        }

        //////////////////////////////////////////////////////////////////////

        Fpdf::text(110, 40, 'Deduction ( - )');
        $start = 48;
        foreach ($potongan as $pt) {
            //dd($pt);
            Fpdf::text(110, $start, $pt['component_code']);
            Fpdf::text(124, $start, $pt['component_name']);
            Fpdf::text(174, $start, ': ' . rupiah($pt['amount']));
            $start = $start + 5;
            $total_potongan = $total_potongan + $pt['amount'];
        }

        Fpdf::text(12, 82, 'Total Receipt');
        Fpdf::text(74, 82, ': ' . rupiah($total_penerimaan));

        Fpdf::text(12, 86, 'Salary Received');
        Fpdf::text(74, 86, ': ' . (rupiah($total_penerimaan - $total_potongan)));

        Fpdf::text(12, 90, '---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');


        Fpdf::text(12, 96, 'Company Name');
        Fpdf::text(42, 96, ' : ' . $setting->company_name);
        Fpdf::text(12, 100, 'Period');
        Fpdf::text(42, 100, ' : ' . $periode_sekarang . ' - ' . $periode_bln_depan);
        Fpdf::text(12, 104, 'Department');
        Fpdf::text(42, 104, ' : ' . $employee->department);


        Fpdf::text(12, 108, 'UID');
        Fpdf::text(42, 108, ' : ' . $employee->uid);
        Fpdf::text(12, 112, 'Employee Name');
        Fpdf::text(42, 112, ' : ' . $employee->name);
        Fpdf::text(12, 116, 'Position');
        Fpdf::text(42, 116, ' : ' . $employee->position);



        Fpdf::text(120, 96, 'Submitted by');
        Fpdf::text(125, 116, 'Admin');
        Fpdf::text(110, 120, 'Print Date : ' . date('d/m/Y : H:i:s'));
        Fpdf::text(166, 96, 'Received by');
        Fpdf::text(163, 116, $employee->name);

        Fpdf::Output();
        exit;
    }
}
