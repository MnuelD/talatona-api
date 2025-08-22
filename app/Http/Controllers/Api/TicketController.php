<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tickets = Ticket::with('ocorrencia')->with('direccao')->with('responsavel')->get();
        return response()->json($tickets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'ocorrencia_id' => 'required|exists:ocorrencias,id',
            'direccao_id' => 'required|exists:direccaos,id',
            'responsavel_id' => 'required|exists:users,id',
            'status' => 'required|string',
            'observacoes' => 'nullable|string',
        ]);


    //  Criar página no banco
    $tickets = Ticket::create($validatedData);

    // Resposta diferenciada para Web e API
    if ($request->wantsJson()) {
        // 🔹 API: Retorno JSON
        return response()->json([
            'message' => 'Ticket  criada com sucesso!',
            'data'    => $tickets
        ], 201);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $ticket = Ticket::with('ocorrencia')->with('direccao')->with('responsavel')->findOrFail($id);
        return response()->json($ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $ticket = Ticket::findOrFail($id);

        $validatedData = $request->validate([
           'ocorrencia_id' => 'required|exists:ocorrencias,id',
            'direccao_id' => 'required|exists:direccaos,id',
            'responsavel_id' => 'required|exists:users,id',
            'status' => 'required|string',
            'observacoes' => 'nullable|string',
        ]);



        // Atualizar dados
        $ticket->update($validatedData);

        if ($request->wantsJson()) {
            // 🔹 API: Retorno JSON
            return response()->json([
                'message' => 'ticket atualizada com sucesso!',
                'data'    => $ticket
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return response()->json(['message' => 'ticket apagada com sucesso']);
    }
}
