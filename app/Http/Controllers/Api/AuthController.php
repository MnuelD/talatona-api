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
use GuzzleHttp\Client;
use  App\Mail\AuthenticationMail;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        $user->addRole('admin');

        return response()->json(['message' => 'Utilizador registado com sucesso!']);
    }
  
public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'method'   => 'required|in:email,sms',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Credenciais inválidas']);
        }

        $user = Auth::user();

        if ($request->method === 'email') {
            $user->email_token = Str::random(64);
            $user->two_factor_expires_at = now()->addMinutes(15);
            $user->save();

            $dados = [
                'email' => $user->email,
                'link'  => route('verify.email', $user->email_token),
            ];
            $this->enviarEmail($dados);

            Auth::logout(); // só loga após clicar no link
            return back()->with('message', 'Link de verificação enviado para seu email.');
        }

        if ($request->method === 'sms') {
            $code = rand(100000, 999999);
            $user->sms_code = $code;
            $user->two_factor_expires_at = now()->addMinutes(15);
            $user->save();

            $dados = [
                'telefone' => $user->phone,
                'code' => $code,
            ];
            $this->enviarSms($dados);

            Auth::logout();
            return view('verify-sms', ['user_id' => $user->id]);
        }
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_token', $token)
            ->where('two_factor_expires_at', '>', now())
            ->firstOrFail();

        $user->email_verified_at = now();
        $user->email_token = null;
        $user->two_factor_expires_at = null;
        $user->save();

        Auth::login($user);
        return redirect()->route('docs');
    }

    public function verifySms(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'code'    => 'required',
        ]);

        $user = User::findOrFail($request->user_id);

        if (
            $user->sms_code == $request->code &&
            $user->two_factor_expires_at > now()
        ) {
            $user->sms_verified_at = now();
            $user->sms_code = null;
            $user->two_factor_expires_at = null;
            $user->save();

            Auth::login($user);
            return redirect()->route('docs');
        }

        return back()->withErrors(['code' => 'Código inválido ou expirado.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    private function enviarEmail(array $dados)
    {
        try {
            Mail::to($dados['email'])->send(new AuthenticationMail($dados));
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email: ' . $e->getMessage());
        }
    }

private function enviarSms(array $dados)
    {
        if (empty($dados['telefone'])) return;

        try {
            $client = new Client();
            $client->post('https://www.telcosms.co.ao/api/v2/send_message', [
                'json' => [
                    'message' => [
                        'api_key_app' => 'prdedc696db1298b9f54f1d124197',
                        'phone_number' => $dados['telefone'],
                        'message_body' => 'Seu código de acesso é: ' . $dados['code'],
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar SMS: ' . $e->getMessage());
        }
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

