<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
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
            'status' => 'success',
            'data' => [
                'id' => $clientData->id,
                'firstName' => $clientData->firstName,
                'lastName' => $clientData->lastName,
                'isBlocked' => $clientData->isBlocked,
            ]
        ]);
    }

    public function createClient(Request $request){
        $validated = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
        ]);

        $id = Client::insertGetId([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'isBlocked' => 0,
        ]);

        return response()->json([
            'status' => 'success',
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
