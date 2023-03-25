<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    private $stringGenerator;

    function __construct() {
        $this->stringGenerator = new Helpers\StringGenerator();
    }

    public function getEmployees(){
        $employeesData = Employee::all()->toArray();
        return json_encode($employeesData, JSON_UNESCAPED_UNICODE);
    }

    public function paginateEmployees(){
        $employees = DB::table('employees')->paginate(2);
        return $employees;
    }

    public function getEmployee(Request $request){
        $employeeData = Employee::where('id', $request->id)->first();
        return response()->json($employeeData);
    }

    public function createEmployee(Request $request){
        $validated = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
        ]);

        $login = $this->stringGenerator->generateRandomString(12);
        $password = $this->stringGenerator->generateRandomString(12);

        $id = Employee::insertGetId([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'login' => $login,
            'password' => $password,
            'isBlocked' => 0,
        ]);

        return response()->json([
            'id' => $id,
        ]);
    }

    public function blockEmployee(Request $request)
    {
        $data = DB::table('employees')->where('id', $request->id)->first();
        $set = 0;
        switch ($data->isBlocked){
            case 0: $set = 1; break;
            case 1: $set = 0; break;
        }
        DB::table('employees')->where('id', $request->id)
            ->update([
                'isBlocked' => $set
            ]);
        return response()->json([
            'status' => 'success'
        ]);
    }
    public function deleteEmployee(Request $request)
    {
        DB::table('employees')->where('id', $request->id)
            ->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
