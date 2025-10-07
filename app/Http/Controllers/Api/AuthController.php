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
        'telefone' => 'nullable|string|max:20',
        'role'     => 'required|string|exists:roles,name',
    ]);



    try {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'telefone' => $request->telefone,
            'password' => bcrypt($request->password),
        ]);

        // procurar a role pelo nome (seguro)
        $role = Role::where('name', $request->role)->first();

        if (!$role) {
            throw new \Exception('Role não encontrada');
        }

        // garantir que o user tenha *apenas* a role escolhida
        // usa sync para remover outras roles acidentais
        if (method_exists($user, 'roles')) {
            $user->roles()->sync([$role->id]);
        } else {
            // fallback para Laratrust / metodos alternativos
            if (method_exists($user, 'attachRole')) {
                $user->attachRole($role);
            } elseif (method_exists($user, 'addRole')) {
                $user->addRole($role);
            } else {
                // último recurso
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }



        return response()->json([
            'success' => true,
            'message' => 'Utilizador registado com sucesso!',
            'user' => $user->only(['id','name','email','telefone']),
            'role' => $role->name
        ], 201);

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => 'Erro ao registar utilizador: ' . $e->getMessage()
        ], 500);
    }
}


  public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'method'   => 'required|in:email,sms',
             'from'     => 'nullable|string'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Credenciais invÃ¡lidas']);
        }

        $user = Auth::user();
       

        if ($request->method === 'email') {
    $user->email_token = Str::random(64);

    // âœ… define validade de 15 minutos
    $user->two_factor_expires_at = now()->addMinutes(15);
    $user->two_factor_verified_at = now(); // ðŸ”‘ marca que o 2FA foi cumprido
    $user->save();

    $dados = [
        'email' => $user->email,
        'link'  => route('verify.email', $user->email_token),
    ];
    $this->enviarEmail($dados);

    Auth::logout();
    return back()->with('message', 'Link de verificaÃ§Ã£o enviado para seu email.');
}

if ($request->method === 'sms') {
    $code = rand(100000, 999999);
    $user->sms_code = $code;

    // âœ… define validade de 15 minutos
    $user->two_factor_expires_at = now()->addMinutes(15);
   
    $user->save();

    $dados = [
        'telefone' => $user->telefone,
        'code' => $code,
    ];
    $this->enviarSms($dados);

    Auth::logout();
   if ($request->from === 'frontend') {
    return response()->json([
        'message' => 'Codigo SMS enviado',
        'user_id' => $user->id,
    ], 200, [], JSON_UNESCAPED_UNICODE);
}

    return view('verify', ['user_id' => $user->id]);
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
        'doc'     => 'nullable' // só para diferenciar a doc
    ]);

    $user = User::findOrFail($request->user_id);

    if (
        $user->sms_code == $request->code &&
        $user->two_factor_expires_at > now()
    ) {
        $user->sms_verified_at = now();
        $user->sms_code = null;
        $user->two_factor_verified_at = now();
        $user->two_factor_expires_at = null;
        $user->save();

        // pega o role real da relação (Laratrust, Spatie, etc.)
        $role = $user->roles()->pluck('name')->first();

        // 🔹 Se for para documentação Blade
        if ($request->has('doc')) {
            Auth::login($user);
            return redirect()->route('docs');
            // ou, se preferir render direto:
            // return view('verify', ['user_id' => $user->id]);
        }

        // 🔹 Caso normal (API / Frontend Angular)
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login bem-sucedido',
            'token'   => $token,
            'user'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'telefone' => $user->telefone,
                'role'     => $role,
            ]
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    // 🔹 Resposta de erro sempre em JSON para o frontend
    return response()->json([
        'success' => false,
        'error'   => 'Código inválido ou expirado'
    ], 422, [], JSON_UNESCAPED_UNICODE);
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

