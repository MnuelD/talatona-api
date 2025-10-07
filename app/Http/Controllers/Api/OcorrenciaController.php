<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ocorrencia;
use App\Models\TipoOcorrencia;
use App\Models\Ticket;
use App\Models\Direccao;
use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OcorrenciaMail;
use GuzzleHttp\Client;



class OcorrenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $ocorrencias = Ocorrencia::with('tipoOcorrencia')->with('bairro')->get();
        return response()->json($ocorrencias);
    }
    
     public function getByCodigo(string $codigo)
{
    $ocorrencia = Ocorrencia::with(['tipoOcorrencia', 'bairro'])
        ->where('codigo_ocorrencia', $codigo)
        ->first();

    if (!$ocorrencia) {
        return response()->json(['message' => 'Ocorrência não encontrada'], 404);
    }

    return response()->json($ocorrencia);
}
    

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // Validação
    $validatedData = $request->validate([
        'codigo_ocorrencia' => 'nullable|string|max:255',
        'user_id' => 'nullable|exists:users,id',
        'anonimo' => 'required|string|in:true,false',
        'nome' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'telefone' => 'nullable|string|max:20',
        'bairro_id' => 'required|exists:bairros,id',
        'tipoOcorrencia_id' => 'required|exists:tipo_ocorrencias,id',
        'localizacao_especifica' => 'nullable|string|max:255',
        'descricao' => 'required|string',
    ]);

    // Gerar código de ocorrência amigável se não informado
    if (empty($validatedData['codigo_ocorrencia'])) {
        $validatedData['codigo_ocorrencia'] = 'OC-' . now()->format('Ymd') . '-' . rand(1000, 9999);
    }

    // Criar ocorrência
    $ocorrencia = Ocorrencia::create($validatedData);

    // Lógica de envio
    $anonimo = $validatedData['anonimo'] === 'true';
    $email = $validatedData['email'] ?? null;
    $telefone = $validatedData['telefone'] ?? null;

    if (!$anonimo) {
        if ($email) {
            $this->enviarEmail([
                'email' => $email,
                'nome' => $validatedData['nome'],
                'codigo_ocorrencia' => $ocorrencia->codigo_ocorrencia,
                'descricao' => $validatedData['descricao'],
            ]);
        }

        if ($telefone) {
            $this->enviarSms([
                'telefone' => $telefone,
                'codigo_ocorrencia' => $ocorrencia->codigo_ocorrencia,
            ]);
        }
    }

    $tipo=TipoOcorrencia::find($validatedData['tipoOcorrencia_id']);
    $direccao=Direccao::where('nome',$tipo->nome)->first();
    $funcionario=Funcionario::where('direccao_id',$direccao->id)->first();
    if(!$direccao){
        $direccao=Direccao::create(['nome'=>$tipo->nome]);
    }

    // Criar ticket automaticamente
        $tickets= [
            'ocorrencia_id'=>$ocorrencia->id,
            'direccao_id'=>$direccao->id, // Pendente atribuição
            'responsavel_id'=>$funcionario->user_id, // Pendente atribuição
            'status'=>'aberto',
            'observacoes'=>'Ticket criado automaticamente com a ocorrência'
        ];
        Ticket::create($tickets);

        if ($telefone) {
            $this->enviarSms([
                'telefone' => $direccao->telefone,
                'codigo_ocorrencia' => 'nova ocorrencia registrada, codigo: '.$ocorrencia->codigo_ocorrencia,
            ]);
        }


    return response()->json([
        'message' => 'Ocorrência criada com sucesso!',
        'data'    => $ocorrencia
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $ocorrencia = Ocorrencia::with('tipoOcorrencia')->with('bairro')->findOrFail($id);
        return response()->json($ocorrencia);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $ocorrencia = Ocorrencia::findOrFail($id);
        $validatedData = $request->validate([
            'codigo_ocorrencia' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'anonimo' => 'required|boolean',
            'nome' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'bairro_id' => 'required|exists:bairros,id',
            'localizacao_especifica' => 'nullable|string|max:255',
            'descricao' => 'required|string',
        ]);
        // Gerar código de ocorrência amigável se não informado
        if (empty($validatedData['codigo_ocorrencia'])) {
            // Exemplo: OC-20250813-4821
            $validatedData['codigo_ocorrencia'] = 'OC-' . now()->format('Ymd') . '-' . rand(1000, 9999);
        }
        // Atualizar ocorrência
        $ocorrencia->update($validatedData);
        // Resposta diferenciada para Web e API
        if ($request->wantsJson()) {
            // 🔹 API: Retorno JSON
            return response()->json([
                'message' => 'Ocorrência atualizada com sucesso!',
                'data'    => $ocorrencia
            ], 200);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $ocorrencia = Ocorrencia::findOrFail($id);
        $ocorrencia->delete();
        return response()->json(['message' => 'ocorrencia apagada com sucesso']);
    }

/**
 * Envia email usando Mailable
 */
private function enviarEmail(array $dados)
{
        try {
            Mail::to($dados['email'])->send(new OcorrenciaMail($dados));
           // Mail::to($dados['email'])->queue(new OcorrenciaMail($dados));
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email: '.$e->getMessage());
            // opcional: lançar ou retornar erro para o caller
        }
    }

/**
 * Envia SMS usando Telco API
 */
private function enviarSms(array $dados)
{
    if (empty($dados['telefone'])) {
        return;
    }

    try {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://www.telcosms.co.ao/api/v2/send_message', [
            'json' => [
                'message' => [
                    'api_key_app' => 'prdedc696db1298b9f54f1d124197',
                    'phone_number' => $dados['telefone'],
                    'message_body' => 'Ocorrência Registada. Código: ' . $dados['codigo_ocorrencia'] . '. Acompanhe o progresso no portal.'

                ]
            ]
        ]);

        \Log::info('SMS enviado: ' . $response->getBody());
    } catch (\Exception $e) {
        \Log::error('Erro ao enviar SMS: '.$e->getMessage());
    }
}

}

