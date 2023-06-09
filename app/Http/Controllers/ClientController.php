<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    private $stringGenerator;

    function __construct() {
        $this->stringGenerator = new Helpers\StringGenerator();
    }

    public function getClients(){
        $clientsData = Client::all()->toArray();
        return json_encode($clientsData, JSON_UNESCAPED_UNICODE);
    }
    public function paginateClients(){
        $clients = DB::table('clients')->paginate(1);
        return $clients;
    }
    public function getClient(Request $request){
        $clientData = Client::where('id', $request->id)->first();
        return response()->json([
            'id' => $clientData->id,
            'firstName' => $clientData->firstName,
            'lastName' => $clientData->lastName,
            'isBlocked' => $clientData->isBlocked,
        ]);
    }

    public function createClient(Request $request){
        $validated = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
        ]);

        $login = $this->stringGenerator->generateRandomString(12);
        $password = $this->stringGenerator->generateRandomString(12);

        $id = Client::insertGetId([
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

    public function blockClient(Request $request)
    {
        $data = DB::table('clients')->where('id', $request->id)->first();
        $set = 0;
        switch ($data->isBlocked){
            case 0: $set = 1; break;
            case 1: $set = 0; break;
        }
        DB::table('clients')->where('id', $request->id)
            ->update([
                'isBlocked' => $set
            ]);
        return response()->json([
            'status' => 'success'
        ]);
    }
    public function deleteClient(Request $request)
    {
        DB::table('clients')->where('id', $request->id)
            ->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
