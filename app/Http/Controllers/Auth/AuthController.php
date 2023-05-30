<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'check your validation',
                'errros' => $validation->errors()
            ]);
        }

        try {
            $data = new User;
            $data->uuid = Uuid::uuid4()->toString();
            $data->name = $request->input('name');
            $data->email = $request->input('email');
            $data->password = Hash::make($request->input('password'));
            $data->save();

            $this->sendVerifyMail($data);

            $token = $data->createToken('auth_token')->plainTextToken;

        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => 'failed',
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success register',
            'data' => $data,
            'access_token' => $token
        ]);

    }


    public function verifyMail($email)
    {
        $data = User::where('email' , $email)->firstOrFail();
        $data->email_verified_at = now();
        $data->save();


        return redirect('becdex.com')->with([
            'success' => 'Email verified successfully',
            'data' => $data,
            'code' => 200
        ]);
    }



    private function sendVerifyMail(User $user)
    
    {
        $verificationUrl = url('api/v1/cms/verify-mail/' . $user->email);
        Mail::to($user->email)->send(new VerificationMail($verificationUrl));
        return response()->json([
            'code' => 200 ,
            'message' => 'success send mail verify'
        
        ]);
    }

}
