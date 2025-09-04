<?php

namespace App\Http\Controllers;

use App\Models\Festa;
use App\Models\Premio;
use App\Jobs\GerarCartelasJob;
use Illuminate\Http\Request;

class PainelController extends Controller
{
    public function index()
    {
        $festas = Festa::with('premios')->get();
        return view('painel.index', compact('festas'));
    }

    public function createFesta()
    {
        return view('painel.create-festa');
    }

    /**public function storeFesta(Request $request)
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
            $path = $request->file('marca_dagua_path')->store('public/uploads');
            $data['marca_dagua_path'] = str_replace('public/', '', $path);
        }

        // 4. Processa e salva a imagem Coringa
        if ($request->hasFile('coringa_path')) {
            $path = $request->file('coringa_path')->store('public/uploads');
            $data['coringa_path'] = str_replace('public/', '', $path);
        }

        // 5. Processa e salva a imagem do Cabeçalho
        if ($request->hasFile('cabecalho_path')) {
            $path = $request->file('cabecalho_path')->store('public/uploads');
            $data['cabecalho_path'] = str_replace('public/', '', $path);
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
    }*/

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
    }

    public function generateCards(Request $request)
    {
        // Adicione esta validação para a requisição AJAX
       /*$festa_id = $request->festa_id;
       $quantidade = $request->quantidade;
       $quantidade_por_folha = $request->quantidade_por_folha;
       $cartelas_por_arquivo = $request->cartelas_por_arquivo;
       $texto_lateral = $request->texto_lateral;*/
       $request->validate([
            'festa_id' => 'required|exists:festas,id',
            'quantidade' => 'required|integer|min:1',
            'quantidade_por_folha' => 'required|integer|in:1,2,3,4,5,6',
            'cartelas_por_arquivo' => 'required|integer|min:1',
            'texto_lateral' => 'nullable|string'
        ]);

       $festa = Festa::find($request->festa_id);

        if (!$festa) {
            return response()->json(['success' => false, 'message' => 'Festa não encontrada.'], 404);
        }

        GerarCartelasJob::dispatch(
            $festa,
            $request->quantidade, // <<< CORRIGIDO
            $request->quantidade_por_folha, // <<< CORRIGIDO
            $request->cartelas_por_arquivo, // <<< CORRIGIDO
            $request->texto_lateral // <<< CORRIGIDO
        );


        // Retorna uma resposta JSON de sucesso
        return response()->json([
            'success' => true,
            'message' => 'Geração de cartelas em andamento! Você será notificado quando o processo for concluído.'
        ]);
    }
}
