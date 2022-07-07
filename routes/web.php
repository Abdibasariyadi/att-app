<?php

use App\Http\Controllers\Permission\RoleController;
use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/login', function () {
//     // $role = Role::find(2);
//     // $role->givePermissionTo('create post', 'delete post');

//     return view('auth.login');
// });

Auth::routes();

Route::middleware('has.role')->group(function () {



    Route::prefix('role-and-permission')->namespace('Permission')->middleware('permission:assign permission')->group(function () {

        Route::get('assignable', [App\Http\Controllers\Permissions\AssignController::class, 'create'])->name('assigns.create');
        Route::post('assignable', [App\Http\Controllers\Permissions\AssignController::class, 'store']);
        Route::get('assignable/{role}/edit', [App\Http\Controllers\Permissions\AssignController::class, 'edit'])->name('assigns.edit');
        Route::put('assignable/{role}/edit', [App\Http\Controllers\Permissions\AssignController::class, 'update']);

        // User
        Route::get('assign-user', [App\Http\Controllers\Permissions\UserController::class, 'create'])->name('assign.user.create');
        Route::post('assign-user', [App\Http\Controllers\Permissions\UserController::class, 'store']);
        Route::get('assign-user/{user}', [App\Http\Controllers\Permissions\UserController::class, 'edit'])->name('assign.user.edit');
        Route::put('assign-user/{user}', [App\Http\Controllers\Permissions\UserController::class, 'update']);

        Route::prefix('roles')->group(function () {
            Route::get('', [App\Http\Controllers\Permissions\RoleController::class, 'index'])->name('roles.index');
            Route::post('/create', [App\Http\Controllers\Permissions\RoleController::class, 'store'])->name('roles.create');
            Route::get('/{role}/edit', [App\Http\Controllers\Permissions\RoleController::class, 'edit'])->name('roles.edit');
            // Route::put('roles/{role}/edit', [App\Http\Controllers\Permissions\RoleController::class, 'update']);
            Route::delete('/{role}/delete', [App\Http\Controllers\Permissions\RoleController::class, 'destroy'])->name('roles.delete');
        });
        Route::prefix('permissions')->group(function () {
            Route::get('', [App\Http\Controllers\Permissions\PermissionController::class, 'index'])->name('permissions.index');
            Route::post('/create', [App\Http\Controllers\Permissions\PermissionController::class, 'store'])->name('permissions.create');
            Route::get('/{permission}/edit', [App\Http\Controllers\Permissions\PermissionController::class, 'edit'])->name('permissions.edit');
            // Route::put('/{permission}/edit', [App\Http\Controllers\Permissions\PermissionController::class, 'update']);
            Route::delete('/{permission}/delete', [App\Http\Controllers\Permissions\PermissionController::class, 'destroy'])->name('permissions.delete');
        });
    });

    Route::prefix('navigation')->middleware('permission:create navigation')->group(function () {
        Route::get('create', [App\Http\Controllers\NavigationController::class, 'create'])->name('navigation.create');
        Route::post('create', [App\Http\Controllers\NavigationController::class, 'store']);
    });

    // Route::prefix('team')->middleware('permission:create team')->group(function () {
    //     Route::get('/', [App\Http\Controllers\TeamController::class, 'index'])->name('team.index');
    //     Route::post('create', [App\Http\Controllers\TeamController::class, 'store'])->name('team.create');
    //     // Route::post('create', [App\Http\Controllers\TeamController::class, 'store']);
    //     Route::get('/{id}/edit', [App\Http\Controllers\TeamController::class, 'edit'])->name('team.edit');
    //     Route::put('/{id}/edit', [App\Http\Controllers\TeamController::class, 'update']);
    //     Route::delete('/{role}/delete', [App\Http\Controllers\TeamController::class, 'destroy'])->name('team.delete');
    // });

    // Route::prefix('tps')->middleware('permission:create team')->group(function () {
    //     Route::get('/', [App\Http\Controllers\TpsController::class, 'index'])->name('tps.index');
    //     Route::post('create', [App\Http\Controllers\TpsController::class, 'store'])->name('tps.create');
    //     // Route::post('create', [App\Http\Controllers\TpsController::class, 'store']);
    //     Route::get('/{id}/edit', [App\Http\Controllers\TpsController::class, 'edit'])->name('tps.edit');
    //     Route::put('/{id}/edit', [App\Http\Controllers\TpsController::class, 'update']);
    //     Route::delete('/{tps}/delete', [App\Http\Controllers\TpsController::class, 'destroy'])->name('tps.delete');
    // });

    // Route::prefix('anggota')->middleware('permission:create anggota')->group(function () {
    //     Route::get('/', [App\Http\Controllers\AnggotaController::class, 'index'])->name('anggota.index');
    //     Route::get('/filter', [App\Http\Controllers\AnggotaController::class, 'filter'])->name('anggota.filter');
    //     Route::get('create', [App\Http\Controllers\AnggotaController::class, 'create'])->name('anggota.create');
    //     Route::post('create', [App\Http\Controllers\AnggotaController::class, 'store'])->name('anggota.store');
    //     Route::post('create', [App\Http\Controllers\AnggotaController::class, 'store'])->name('anggota.store');
    //     Route::get('/edit/{id}', [App\Http\Controllers\AnggotaController::class, 'edit'])->name('anggota.edit');
    //     Route::put('/edit/{id}', [App\Http\Controllers\AnggotaController::class, 'update']);
    //     Route::delete('/{id}/delete', [App\Http\Controllers\AnggotaController::class, 'destroy'])->name('anggota.delete');
    //     Route::post('check-nik', [App\Http\Controllers\AnggotaController::class, 'check_nik'])->name('check.nik');
    //     Route::get('/filter-desa/{min}/{max}', [App\Http\Controllers\DashboardController::class, 'filterDesa']);
    // });

    Route::prefix('indo-region')->middleware('permission:create alamat')->group(function () {
        Route::post('get-kab', [App\Http\Controllers\IndoRegionController::class, 'getKabupaten'])->name('kab.create');
        Route::post('get-kec', [App\Http\Controllers\IndoRegionController::class, 'getKecamatan'])->name('kec.create');
        Route::post('get-desa', [App\Http\Controllers\IndoRegionController::class, 'getDesa'])->name('desa.create');
        Route::post('store-anggota', [App\Http\Controllers\IndoRegionController::class, 'getDesa'])->name('anggota.store');
    });

    // Route::prefix('master-data')->middleware('permission:create master-data')->group(function () {
    //     Route::get('create', [App\Http\Controllers\MasterDataController::class, 'create'])->name('master-data.create');
    //     Route::post('create', [App\Http\Controllers\MasterDataController::class, 'store']);
    // });

    Route::prefix('dashboard')->middleware('permission:show dahsboard')->group(function () {
        Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
    });

    Route::prefix('machineset')->middleware('permission:create machine')->group(function () {
        Route::get('/', [App\Http\Controllers\MachineSet::class, 'index'])->name('machine.index');
        Route::post('create', [App\Http\Controllers\MachineSet::class, 'store'])->name('machine.create');
        // Route::post('create', [App\Http\Controllers\MachineSet::class, 'store']);
        Route::get('/{id}/edit', [App\Http\Controllers\MachineSet::class, 'edit'])->name('machine.edit');
        Route::put('/{id}/edit', [App\Http\Controllers\MachineSet::class, 'update']);
        Route::delete('/{machine}/delete', [App\Http\Controllers\MachineSet::class, 'destroy'])->name('machine.delete');
    });

    Route::prefix('department')->middleware('permission:create department')->group(function () {
        Route::get('/', [App\Http\Controllers\departemenController::class, 'index'])->name('department.index');
        Route::post('create', [App\Http\Controllers\departemenController::class, 'store'])->name('department.create');
        // Route::post('create', [App\Http\Controllers\departemenController::class, 'store']);
        Route::get('/{id}/edit', [App\Http\Controllers\departemenController::class, 'edit'])->name('department.edit');
        Route::put('/{id}/edit', [App\Http\Controllers\departemenController::class, 'update']);
        Route::delete('/{department}/delete', [App\Http\Controllers\departemenController::class, 'destroy'])->name('department.delete');
    });

    Route::prefix('attendance')->middleware('permission:create attendance')->group(function () {
        Route::get('/', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('create', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.create');
        Route::get('get', [App\Http\Controllers\AttendanceController::class, 'getAttendance'])->name('attendance.get');
        Route::get('/{id}/edit', [App\Http\Controllers\AttendanceController::class, 'edit'])->name('attendance.edit');
        Route::put('/{id}/edit', [App\Http\Controllers\AttendanceController::class, 'update']);
        Route::delete('/{attendance}/delete', [App\Http\Controllers\AttendanceController::class, 'destroy'])->name('attendance.delete');
    });

    Route::prefix('position')->middleware('permission:create position')->group(function () {
        Route::get('/', [App\Http\Controllers\PositionController::class, 'index'])->name('position.index');
        Route::post('create', [App\Http\Controllers\PositionController::class, 'store'])->name('position.create');
        Route::get('get', [App\Http\Controllers\PositionController::class, 'getAttendance'])->name('position.get');
        Route::get('/{id}/edit', [App\Http\Controllers\PositionController::class, 'edit'])->name('position.edit');
        Route::put('/{id}/edit', [App\Http\Controllers\PositionController::class, 'update']);
        Route::delete('/{position}/delete', [App\Http\Controllers\PositionController::class, 'destroy'])->name('position.delete');
    });

    Route::prefix('employee')->middleware('permission:create employee')->group(function () {
        Route::get('/', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employee.index');
        Route::get('create', [App\Http\Controllers\EmployeeController::class, 'create'])->name('employee.create');
        Route::post('create', [App\Http\Controllers\EmployeeController::class, 'store'])->name('employee.store');
        Route::get('get', [App\Http\Controllers\EmployeeController::class, 'getAttendance'])->name('employee.get');
        Route::get('/{uid}/edit', [App\Http\Controllers\EmployeeController::class, 'edit'])->name('employee.edit');
        Route::put('/{uid}/edit', [App\Http\Controllers\EmployeeController::class, 'update'])->name('employee.update');
        Route::get('/{id}/shiftwork', [App\Http\Controllers\EmployeeController::class, 'shiftWork_employee'])->name('employee.shiftwork');
        Route::get('/{id}/attendance', [App\Http\Controllers\EmployeeController::class, 'attendance_employee'])->name('employee.attendance');
        Route::get('/{id}/overtime', [App\Http\Controllers\EmployeeController::class, 'overtime_employee'])->name('employee.overtime');
        Route::delete('/{employee}/delete', [App\Http\Controllers\EmployeeController::class, 'destroy'])->name('employee.delete');
        Route::get('/{uid}/filter-att', [App\Http\Controllers\EmployeeController::class, 'filterAtt'])->name('employeeAtt.filter');
        Route::get('/{uid}/filter-shift', [App\Http\Controllers\EmployeeController::class, 'filterShift'])->name('employeeShift.filter');
        Route::get('/{uid}/filter-overtime', [App\Http\Controllers\EmployeeController::class, 'filterOverTime'])->name('employeeOverTime.filter');
    });

    Route::prefix('workcalendar')->middleware('permission:create workcalendar')->group(function () {
        Route::get('/', [App\Http\Controllers\WorkCalendarController::class, 'index'])->name('workcalendar.index');
        Route::post('create', [App\Http\Controllers\WorkCalendarController::class, 'store'])->name('workcalendar.create');
        Route::get('get', [App\Http\Controllers\WorkCalendarController::class, 'getAttendance'])->name('workcalendar.get');
        Route::get('/{id}/edit', [App\Http\Controllers\WorkCalendarController::class, 'edit'])->name('workcalendar.edit');
        Route::put('/{id}/edit', [App\Http\Controllers\WorkCalendarController::class, 'update']);
        Route::delete('/{workcalendar}/delete', [App\Http\Controllers\WorkCalendarController::class, 'destroy'])->name('workcalendar.delete');
    });

    Route::prefix('presence')->middleware('permission:create presence')->group(function () {
        Route::get('/', [App\Http\Controllers\PresenceController::class, 'index'])->name('presence.index');
        Route::post('create', [App\Http\Controllers\PresenceController::class, 'store'])->name('presence.create');
        Route::get('get', [App\Http\Controllers\PresenceController::class, 'getAttendance'])->name('presence.get');
        Route::get('/{id}/edit', [App\Http\Controllers\PresenceController::class, 'edit'])->name('presence.edit');
        Route::put('/{id}/edit', [App\Http\Controllers\PresenceController::class, 'update']);
        Route::delete('/{presence}/delete', [App\Http\Controllers\PresenceController::class, 'destroy'])->name('presence.delete');
        Route::get('/filter', [App\Http\Controllers\PresenceController::class, 'filter'])->name('presence.filter');
    });

    Route::prefix('shiftwork')->middleware('permission:create shiftwork')->group(function () {
        Route::get('/', [App\Http\Controllers\ShiftWorkController::class, 'index'])->name('shiftwork.index');
        Route::post('create', [App\Http\Controllers\ShiftWorkController::class, 'store'])->name('shiftwork.create');
        Route::get('get', [App\Http\Controllers\ShiftWorkController::class, 'getAttendance'])->name('shiftwork.get');
        Route::get('/{id}/edit', [App\Http\Controllers\ShiftWorkController::class, 'edit'])->name('shiftwork.edit');
        Route::put('/{id}/edit', [App\Http\Controllers\ShiftWorkController::class, 'update']);
        Route::delete('/{shiftwork}/delete', [App\Http\Controllers\ShiftWorkController::class, 'destroy'])->name('shiftwork.delete');
    });

    Route::prefix('teamwork')->middleware('permission:create teamwork')->group(function () {
        Route::get('/', [App\Http\Controllers\TeamWorkController::class, 'index'])->name('teamwork.index');
        Route::get('/{id}', [App\Http\Controllers\TeamWorkController::class, 'show'])->name('teamwork.show');
        Route::get('/{id}/shiftwork', [App\Http\Controllers\TeamWorkController::class, 'shiftwork'])->name('teamwork.shiftwork');
        // Route::get('/{id}/shiftwork', [App\Http\Controllers\TeamWorkController::class, 'shiftworkTable'])->name('teamwork.shiftworkTable');
        Route::post('/shiftwork-save', [App\Http\Controllers\TeamWorkController::class, 'shiftworkSave'])->name('teamwork.shiftworkSave');
        Route::post('create', [App\Http\Controllers\TeamWorkController::class, 'store'])->name('teamwork.create');
        Route::post('add-team-work', [App\Http\Controllers\TeamWorkController::class, 'addTeamWork'])->name('teamwork.addteamwork');
        Route::get('get', [App\Http\Controllers\TeamWorkController::class, 'getAttendance'])->name('teamwork.get');
        Route::get('/{id}/edit', [App\Http\Controllers\TeamWorkController::class, 'edit'])->name('teamwork.edit');
        Route::put('/{id}/edit', [App\Http\Controllers\TeamWorkController::class, 'update']);
        Route::delete('/{teamwork}/delete', [App\Http\Controllers\TeamWorkController::class, 'destroy'])->name('teamwork.delete');
        Route::delete('/delete-groupwork/{id}', [App\Http\Controllers\TeamWorkController::class, 'destroyGroup'])->name('groupwork.delete');
        Route::delete('/{shiftwork}/delete-shiftwork', [App\Http\Controllers\TeamWorkController::class, 'shiftworkDelete'])->name('groupwork.shiftworkDelete');
    });

    Route::prefix('overtime')->middleware('permission:create overtime')->group(function () {
        Route::get('/', [App\Http\Controllers\OvertimeController::class, 'index'])->name('overtime.index');
        Route::post('create', [App\Http\Controllers\OvertimeController::class, 'store'])->name('overtime.create');
        Route::get('get', [App\Http\Controllers\OvertimeController::class, 'getAttendance'])->name('overtime.get');
        Route::get('/{id}/edit', [App\Http\Controllers\OvertimeController::class, 'edit'])->name('overtime.edit');
        Route::put('/{id}/edit', [App\Http\Controllers\OvertimeController::class, 'update']);
        Route::delete('/{overtime}/delete', [App\Http\Controllers\OvertimeController::class, 'destroy'])->name('overtime.delete');
        Route::get('/filter', [App\Http\Controllers\OvertimeController::class, 'filter'])->name('overtime.filter');
    });

    Route::prefix('salarycomponent')->middleware('permission:create salarycomponent')->group(function () {
        Route::get('/', [App\Http\Controllers\SalaryComponentController::class, 'index'])->name('salarycomponent.index');
        Route::post('create', [App\Http\Controllers\SalaryComponentController::class, 'store'])->name('salarycomponent.create');
        Route::get('get', [App\Http\Controllers\SalaryComponentController::class, 'getAttendance'])->name('salarycomponent.get');
        Route::get('/{id}/edit', [App\Http\Controllers\SalaryComponentController::class, 'edit'])->name('salarycomponent.edit');
        Route::put('/{id}/edit', [App\Http\Controllers\SalaryComponentController::class, 'update']);
        Route::delete('/{salarycomponent}/delete', [App\Http\Controllers\SalaryComponentController::class, 'destroy'])->name('salarycomponent.delete');
        Route::get('/filter', [App\Http\Controllers\SalaryComponentController::class, 'filter'])->name('salarycomponent.filter');
    });

    Route::prefix('salary')->middleware('permission:create salary')->group(function () {
        Route::get('/', [App\Http\Controllers\SalaryController::class, 'index'])->name('salary.index');
        Route::post('create', [App\Http\Controllers\SalaryController::class, 'store'])->name('salary.create');
        Route::get('get', [App\Http\Controllers\SalaryController::class, 'getAttendance'])->name('salary.get');
        Route::get('/{id}/edit', [App\Http\Controllers\SalaryController::class, 'edit'])->name('salary.edit');
        Route::put('/{id}/edit', [App\Http\Controllers\SalaryController::class, 'update']);
        Route::delete('/{salary}/delete', [App\Http\Controllers\SalaryController::class, 'destroy'])->name('salary.delete');
        Route::get('/{id}/detail', [App\Http\Controllers\SalaryController::class, 'detail'])->name('salary.detail');
        Route::post('/changeperiod', [App\Http\Controllers\SalaryController::class, 'changeSalaryPeriod'])->name('salary.changeperiod');
        Route::post('/add-salary-component', [App\Http\Controllers\SalaryController::class, 'addSalaryComponent'])->name('salary.add-salary-component');
        Route::delete('/{salary}/delete-salary', [App\Http\Controllers\SalaryController::class, 'deleteSalaryDetail'])->name('salaryDetail.delete');
        Route::get('/{id}/pdf', [App\Http\Controllers\SalaryController::class, 'pdf'])->name('salary.pdf');
    });

    Route::get('search', function (Request $request) {
        $query = $request->get('query');
        $filterResult = EmployeeModel::where('name', 'LIKE', '%' . $query . '%')->get();
        return response()->json($filterResult);
    });
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
