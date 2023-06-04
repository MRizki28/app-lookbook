<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'errors' => $validation->errors()
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
        $verificationUrl = url('v1/cms/verify-mail/' . $user->email);
        Mail::to($user->email)->send(new VerificationMail($verificationUrl));
        return response()->json([
            'code' => 200 ,
            'message' => 'success send mail verify'
        
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email' , 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'code' => 400,
                'message' => 'Invalid email or password'
            ]);
        }

        $user = User::where('email' , $request->email)->first();
        if (!$user || !$user->email_verified_at) {
            return response()->json([
                'code' => 422,
                'message' => 'email not verified',
            ]);
        }

        $token = $user->createToken('auth_token');

        if (!$this->isTokenValid($token)) {
            Auth::logout();
            return response()->json([
                'code' => 400,
                'message' => 'Token expired'
            ]);
        }


        return response()->json([
            'code' => 200,
            'message' => 'success login',
            'data' => $user,
            'access_token' => $token->plainTextToken
        ]);
    }

    private function isTokenValid($token)
    {
        $expirationMinutes = config('sanctum.expiration');

        if ($expirationMinutes === null) {
            return true; // Token tidak kedaluwarsa jika tidak ada batasan waktu
        }
        // Periksa apakah waktu pembuatan token ditambah dengan waktu kedaluwarsa masih lebih besar dari waktu saat ini
        return $token->accessToken->created_at->addMinutes($expirationMinutes)->isFuture();
    }


    public function logout(Request $request)
    {
        $request->user('web')->tokens()->delete();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'code' => 200,
            'message' => 'sucess logout and delete token access'
        ]);
    }

}
