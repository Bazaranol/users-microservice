<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function exists(Request $request) {
        $clientData = DB::table('clients')
            ->where('login', $request->login)
            ->where('password', $request->password)
            ->first();

        $employeeData = DB::table('employees')
            ->where('login', $request->login)
            ->where('password', $request->password)
            ->first();

        $exists = $clientData != null || $employeeData != null;

        $roles = [];

        if ($clientData != null) {
            $roles[] = (object)[
                'id' => $clientData->id,
                'role' => 'client',
            ];
        }

        if ($employeeData != null) {
            $roles[] = (object)[
                'id' => $employeeData->id,
                'role' => 'employee',
            ];
        }

        return response()->json([
            'exists' => $exists,
            'roles' => $roles,
        ]);
    }
}
