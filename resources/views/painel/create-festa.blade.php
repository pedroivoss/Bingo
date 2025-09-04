{{-- resources/views/painel/create-festa.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>Criar Nova Festa</h1>

    <form action="{{ route('painel.store-festa') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Festa</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="mb-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" class="form-control" id="data" name="data" required>
        </div>
       <div class="mb-3">
            <label for="cabecalho_path" class="form-label">Imagem do Cabeçalho (PNG, JPG, SVG)</label>
            <input type="file" class="form-control" id="cabecalho_path" name="cabecalho_path">
        </div>
        <div class="mb-3">
            <label for="marca_dagua_path" class="form-label">Marca d'água (PNG, JPG)</label>
            <input type="file" class="form-control" id="marca_dagua_path" name="marca_dagua_path">
        </div>
        <div class="mb-3">
            <label for="coringa_path" class="form-label">Imagem Coringa (PNG, JPG)</label>
            <input type="file" class="form-control" id="coringa_path" name="coringa_path">
        </div>
        <div class="mb-3">
            <label for="rodape_html" class="form-label">Rodapé HTML (opcional)</label>
            <textarea class="form-control" id="rodape_html" name="rodape_html" rows="3"></textarea>
        </div>

        <hr>
        <h4>Configurações de Prêmios</h4>
        <div id="premios-container">
            <div class="row mb-2 premio-item">
                <div class="col-md-5">
                    <label class="form-label">Título do Prêmio</label>
                    <input type="text" class="form-control" name="premios[0][titulo]" placeholder="Ex: 1º Prêmio - Moto">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Ordem</label>
                    <input type="number" class="form-control" name="premios[0][ordem]" value="1">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Descrição (Extra)</label>
                    <input type="text" class="form-control" name="premios[0][descricao]" placeholder="Detalhes do prêmio">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-premio"><i class="fas fa-minus-circle"></i></button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary mt-3" id="add-premio"><i class="fas fa-plus-circle"></i> Adicionar Prêmio</button>

        <button type="submit" class="btn btn-primary mt-4">Salvar Festa</button>
        <a href="{{ route('painel.index') }}" class="btn btn-secondary mt-4">Cancelar</a>
    </form>
@endsection

@push('scripts')
<script>
    let premioIndex = 1;
    document.getElementById('add-premio').addEventListener('click', function() {
        const container = document.getElementById('premios-container');
        const newPremio = document.createElement('div');
        newPremio.classList.add('row', 'mb-2', 'premio-item');
        newPremio.innerHTML = `
            <div class="col-md-5">
                <label class="form-label">Título do Prêmio</label>
                <input type="text" class="form-control" name="premios[${premioIndex}][titulo]" placeholder="Ex: 2º Prêmio - R$ 5.000,00">
            </div>
            <div class="col-md-2">
                <label class="form-label">Ordem</label>
                <input type="number" class="form-control" name="premios[${premioIndex}][ordem]" value="${premioIndex + 1}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Descrição</label>
                <input type="text" class="form-control" name="premios[${premioIndex}][descricao]" placeholder="Detalhes do prêmio">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-premio"><i class="fas fa-minus-circle"></i></button>
            </div>
        `;
        container.appendChild(newPremio);
        premioIndex++;

        newPremio.querySelector('.remove-premio').addEventListener('click', function() {
            newPremio.remove();
        });
    });

    document.querySelectorAll('.remove-premio').forEach(button => {
        button.addEventListener('click', function() {
            button.closest('.premio-item').remove();
        });
    });
</script>
@endpush
