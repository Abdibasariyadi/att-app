<?php

use Illuminate\Support\Facades\DB;

function hitungJmlKehadiran($uid, $period)
{
    $sql = "select count(*) as total from logsattendance where uid='$uid' and left(cast(time as date),7)='$period'";
    $jmlKehadiran = DB::select($sql);
    return $jmlKehadiran[0]->total;
}

function chekKehadiran($uid, $date)
{
    $logsattendance = DB::table('logsattendance')
        ->where('uid', $uid)
        ->whereRaw("cast(time as date)='" . $date . "'")
        ->first();
    if (isset($logsattendance)) {
        return "A";
    } else {
        return '-';
    }
}


// function chekLembur($uid, $date)
// {
//     $overtime = DB::table('overtime')
//         ->where('uid', $uid)
//         ->whereRaw("cast(checkIn as date)='" . $date . "'")
//         ->first();

//     if (isset($overtime)) {
//         return $overtime->duration . ' H';
//     } else {
//         return '-';
//     }
// }

function chekLembur($uid, $date)
{
    // $overtime = DB::table('view_attendancehistory')
    //     ->select('timediff(logoutTime, checkOut)')
    //     ->where('uid', $uid)
    //     ->whereRaw("cast(date as date)='" . $date . "'")
    //     ->first();
    $overtime = DB::select("select timediff(logoutTime, checkOut) as difference from view_attendancehistory where (cast(date as date))='" .$date. "'and uid='" . $uid . "'");
// dd($overtime[0]->difference);
    
    if (isset($overtime[0])) {
        return $overtime[0]->difference;
        // return $overtime[0]->difference  . ' Minutes';
    } else {
        return '-';
    }
}

function rupiah($amount)
{

    $moneyFormat = "MMK " . number_format($amount, 0, ',', '.');
    return $moneyFormat;
}
