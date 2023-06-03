<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LookbookModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

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

    public function getDataByUser()
    {
        $user = Auth::user();
        $data = LookbookModel::with('users')->where('id_user', $user->id)->get();
        return response()->json([
            'message' => 'success',
            'data' => $data
        ]);
    }


    public function createData(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'date' => 'required',
            'description' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'check your validation',
                'errors' => $validation->errors()
            ]);
        }

        try {
            $user = Auth::user();
            $data = new LookbookModel;
            $data->uuid = Uuid::uuid4()->toString();
            $data->id_user = $user->id;
            $data->date = $request->input('date');
            $data->description = $request->input('description');
            $data->save();
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => 'failed',
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ]);
    }
}
