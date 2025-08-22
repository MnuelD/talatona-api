<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->addRole('admin');

        return response()->json(['message' => 'Utilizador registado com sucesso!']);
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Credenciais inválidas!'], 401);
    }

    $user = Auth::user();

    // Criar token de verificação
    $verificationToken = Str::uuid();
    $user->two_factor_token = $verificationToken;
    $user->two_factor_expires_at = now()->addMinutes(15);
    $user->save();

    // Enviar email com link
    $verificationLink = url('/api/verify-2fa/' . $verificationToken);
    Mail::to($user->email)->send(new \App\Mail\TwoFactorLogin($verificationLink));

    return response()->json(['message' => 'Verificação de dois fatores enviada para seu e-mail.']);
}

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }

    public function verify2FA($token)
{
    $user = \App\Models\User::where('two_factor_token', $token)
        ->where('two_factor_expires_at', '>', now())
        ->first();

    if (!$user) {
        //return response()->json(['message' => 'Token inválido ou expirado.'], 401);
        return redirect()->away('http://localhost:4200/token-expirado');

    }

    // Invalidar o token para que não possa ser reutilizado
    $user->two_factor_token = null;
    $user->two_factor_expires_at = null;
    $user->save();

    // Gerar token de API
    $token = $user->createToken('API Token')->plainTextToken;

    // Redirecionar para o frontend com o token (por query param, ex: ?token=...)
   // return redirect("http://localhost:4200/dashboard?token=$token");


    return redirect()->away("http://localhost:4200/auth-redirect?token=$token");

}


}

