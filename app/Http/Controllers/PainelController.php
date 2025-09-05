<?php

namespace App\Http\Controllers;

use App\Models\Festa;
use App\Models\Premio;
use App\Jobs\GerarCartelasJob;
use App\Traits\mainTrait;
use Illuminate\Http\Request;

class PainelController extends Controller
{
    use mainTrait;

    public function index()
    {
        $festas = Festa::with('premios')->get();
        return view('painel.index', compact('festas'));
    }//fim funcao

    public function createFesta()
    {
        return view('painel.create-festa');
    }//fim funcao

    public function storeFesta(Request $request)
    {
        // 1. Validação dos dados do formulário
        $request->validate([
            'nome' => 'required',
            'data' => 'required|date',
            'marca_dagua_path' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'coringa_path' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'cabecalho_path' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'rodape_html' => 'nullable|string',
            'premios.*.titulo' => 'required|string',
            'premios.*.ordem' => 'required|integer',
            'premios.*.descricao' => 'nullable|string',
        ]);

        // 2. Prepara os dados do request
        $data = $request->except(['_token', 'premios', 'marca_dagua_path', 'coringa_path', 'cabecalho_path']);

        // 3. Processa e salva a imagem de Marca d'água
        if ($request->hasFile('marca_dagua_path')) {
            // Correção: Salvando diretamente no disco 'public'
            $path = $request->file('marca_dagua_path')->store('uploads', 'public');
            $data['marca_dagua_path'] = $path;
        }

        // 4. Processa e salva a imagem Coringa
        if ($request->hasFile('coringa_path')) {
            // Correção: Salvando diretamente no disco 'public'
            $path = $request->file('coringa_path')->store('uploads', 'public');
            $data['coringa_path'] = $path;
        }

        // 5. Processa e salva a imagem do Cabeçalho
        if ($request->hasFile('cabecalho_path')) {
            // Correção: Salvando diretamente no disco 'public'
            $path = $request->file('cabecalho_path')->store('uploads', 'public');
            $data['cabecalho_path'] = $path;
        }

        // 6. Cria a nova Festa no banco de dados
        $festa = Festa::create($data);

        // 7. Salva os prêmios relacionados
        if ($request->has('premios')) {
            foreach ($request->premios as $premio) {
                $festa->premios()->create($premio);
            }
        }

        return redirect()->route('painel.index')->with('success', 'Festa criada com sucesso!');
    }//fim funcao

    public function generateCards(Request $request)
    {

       $request->validate([
            'festa_id' => 'required|exists:festas,id',
            'quantidade' => 'required|integer|min:1'
        ]);

       $festa = Festa::find($request->festa_id);

        if (!$festa) {
            return $this->error('Festa não encontrada.', 404);
        }

        GerarCartelasJob::dispatch(
            $festa,
            $request->quantidade
        );


        // Retorna uma resposta JSON de sucesso
        return $this->success('Geração de cartelas em andamento! Você será notificado quando o processo for concluído.', 200);
    }//fim funcao

}//fim classe
