<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LookbookModel;
use Illuminate\Http\Request;

class LookbookController extends Controller
{
    public function getAllData()
    {
        $data = LookbookModel::all();
        return response([
            'code' => 200,
            'message' => 'success get all data ',
            'data' => $data
        ]);
    }

}
