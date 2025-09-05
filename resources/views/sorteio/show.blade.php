{{-- resources/views/sorteio/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <h3>Festa: {{ $festa->nome }}</h3>
            <h4>Números Sorteados</h4>
            <ul class="list-group" id="numeros-sorteados-lista">
                @foreach ($sorteios as $sorteio)
                    <li class="list-group-item">{{ $sorteio->letra }}{{ $sorteio->numero }}</li>
                @endforeach
            </ul>
            <button class="btn btn-warning btn-sm mt-3" id="remove-last-sorted" style="display: {{ $sorteios->isEmpty() ? 'none' : 'block' }}">
                <i class="fas fa-undo"></i> Remover Último
            </button>
            <button class="btn btn-danger btn-sm mt-3" id="limpar-sorteio" style="display: {{ $sorteios->isEmpty() ? 'none' : 'block' }}">
                <i class="fas fa-trash"></i> Limpar Sorteio
            </button>
        </div>

        <div class="col-md-6 text-center">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h1 style="font-size: 8rem;" id="bola-gigante">
                        @if ($sorteios->isEmpty())
                            ?
                        @else
                            {{ $sorteios->first()->letra }}{{ $sorteios->first()->numero }}
                        @endif
                    </h1>
                </div>
            </div>

            <div class="input-group mb-3">
                <input type="number" class="form-control form-control-lg" placeholder="Digite o número" id="numero-registrar" min="1" max="75">
                <button class="btn btn-primary btn-lg" type="button" id="btn-registrar-numero"><i class="fas fa-check"></i> Registrar</button>
            </div>

            <hr>

            <div class="input-group mb-3">
                <input type="text" class="form-control form-control-lg" placeholder="Código da Cartela" id="codigo-cartela">
                <button class="btn btn-success btn-lg" type="button" id="btn-validar-cartela"><i class="fas fa-search"></i> Validar Cartela</button>
            </div>
        </div>

        <div class="col-md-3">
            <h4>Próximos Prêmios:</h4>
            <ul class="list-group">
                @forelse ($festa->premios->sortBy('ordem') as $premio)
                    <li class="list-group-item">
                        {{ $premio->ordem }}º - {{ $premio->titulo }}
                    </li>
                @empty
                    <li class="list-group-item">Nenhum prêmio cadastrado.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="modal fade" id="validarCartelaModal" tabindex="-1" aria-labelledby="validarCartelaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="validarCartelaModalLabel">Conferência da Cartela: <span id="modal-cartela-codigo"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Cartela Sorteada</h5>
                            <table class="bingo-card w-100" id="modal-bingo-card">
                                <thead><tr><td>B</td><td>I</td><td>N</td><td>G</td><td>O</td></tr></thead>
                                <tbody>
                                    {{-- Conteúdo preenchido via JS --}}
                                </tbody>
                            </table>
                            <h5 class="mt-3" id="modal-status-ganhador"></h5>
                        </div>
                        <div class="col-md-6">
                            <h5>Prêmios Disponíveis</h5>
                            <form id="form-confirmar-vencedor">
                                @csrf
                                <input type="hidden" name="cartela_id" id="modal-cartela-id">
                                <div class="mb-3">
                                    <label for="premio_id" class="form-label">Selecione o Prêmio</label>
                                    <select class="form-select" name="premio_id" id="premio_id" required>
                                        <option value="">Selecione um prêmio...</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success" id="btn-confirmar-vencedor" disabled>Confirmar Vencedor</button>
                            </form>
                            <div class="alert alert-warning mt-3" id="integridade-warning" style="display: none;">
                                <i class="fas fa-exclamation-triangle"></i> **Alerta de Integridade:** O hash da cartela não confere!
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const festaId = {{ $festa->id }};
    const listaSorteados = document.getElementById('numeros-sorteados-lista');
    const bolaGigante = document.getElementById('bola-gigante');
    const inputNumeroRegistrar = document.getElementById('numero-registrar');
    const inputCodigoCartela = document.getElementById('codigo-cartela');
    const btnRegistrarNumero = document.getElementById('btn-registrar-numero');
    const btnRemoverUltimo = document.getElementById('remove-last-sorted');
    const btnLimparSorteio = document.getElementById('limpar-sorteio'); // Novo botão
    const btnValidarCartela = document.getElementById('btn-validar-cartela');
    const modalValidar = new bootstrap.Modal(document.getElementById('validarCartelaModal'));
    const formConfirmarVencedor = document.getElementById('form-confirmar-vencedor');

    // URL base para as requisições API

    // Mapeia os números para as letras de bingo
    const getBingoLetter = (number) => {
        if (number >= 1 && number <= 15) return 'B';
        if (number >= 16 && number <= 30) return 'I';
        if (number >= 31 && number <= 45) return 'N';
        if (number >= 46 && number <= 60) return 'G';
        if (number >= 61 && number <= 75) return 'O';
        return '';
    };

    // Atualiza a lista e a bola gigante
    const updateUi = async () => {
        try {
            const response = await fetch(`${base_URL}/sorteio/${festaId}`, { headers: { 'Accept': 'application/json' } });
            const data = await response.json();

            // Limpa a lista
            listaSorteados.innerHTML = '';

            // Mapeia e organiza os sorteios por letra e número
            const sorteiosFormatados = data.sorteios.map(sorteio => ({
                ...sorteio,
                letra: getBingoLetter(sorteio.numero) // Garante que a letra está correta
            }));

            // Ordena os sorteios: primeiro por letra (B, I, N, G, O) e depois por número
            const sorteiosOrdenados = sorteiosFormatados.sort((a, b) => {
                const letrasOrdem = ['B', 'I', 'N', 'G', 'O'];
                if (letrasOrdem.indexOf(a.letra) !== letrasOrdem.indexOf(b.letra)) {
                    return letrasOrdem.indexOf(a.letra) - letrasOrdem.indexOf(b.letra);
                }
                return a.numero - b.numero;
            });

            // Popula a lista com os números ordenados
            sorteiosOrdenados.forEach(sorteio => {
                const li = document.createElement('li');
                li.classList.add('list-group-item');
                li.textContent = `${sorteio.letra}${sorteio.numero}`;
                listaSorteados.appendChild(li);
            });

            // Atualiza a bola gigante com o último número sorteado (o mais recente)
            const ultimoSorteio = data.sorteios[0];
            bolaGigante.textContent = ultimoSorteio ? `${getBingoLetter(ultimoSorteio.numero)}${ultimoSorteio.numero}` : '?';

            // Alterna a exibição dos botões de remover e limpar
            btnRemoverUltimo.style.display = data.sorteios.length > 0 ? 'block' : 'none';
            btnLimparSorteio.style.display = data.sorteios.length > 0 ? 'block' : 'none';

        } catch (error) {
            console.error('Erro ao atualizar a interface:', error);
        }
    };

    // Registra um novo número
    btnRegistrarNumero.addEventListener('click', async () => {
        const numero = inputNumeroRegistrar.value;
        if (!numero) return alert('Por favor, digite um número.');

        try {
            const response = await fetch(`${base_URL}/sorteio/${festaId}/registrar-numero`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ numero: parseInt(numero) })
            });
            const data = await response.json();

            if (response.ok) {
                inputNumeroRegistrar.value = '';
                updateUi();
            } else {
                alert(data.message || 'Erro ao registrar número.');
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro de comunicação.');
        }
    });

    // Remove o último número registrado
    btnRemoverUltimo.addEventListener('click', async () => {
        if (confirm('Tem certeza que deseja remover o último número sorteado?')) {
            try {
                const response = await fetch(`${base_URL}/sorteio/${festaId}/remover-ultimo`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await response.json();
                if (data.success) {
                    updateUi();
                } else {
                    alert(data.message || 'Erro ao remover número.');
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro de comunicação.');
            }
        }
    });

    // Limpa todos os números sorteados
    btnLimparSorteio.addEventListener('click', async () => {
        if (confirm('Tem certeza que deseja limpar todo o sorteio e começar uma nova rodada de prêmios?')) {
            try {
                const response = await fetch(`${base_URL}/sorteio/${festaId}/limpar`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await response.json();
                if (data.success) {
                    alert('Sorteio limpo com sucesso!');
                    updateUi();
                } else {
                    alert(data.message || 'Erro ao limpar sorteio.');
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro de comunicação.');
            }
        }
    });

    // Valida a cartela
    btnValidarCartela.addEventListener('click', async () => {
        const codigo = inputCodigoCartela.value;
        if (!codigo) return alert('Por favor, digite o código da cartela.');

        try {
            const response = await fetch(`${base_URL}/sorteio/${festaId}/validar-cartela`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ codigo })
            });
            const result = await response.json();

            if (response.ok) {
                document.getElementById('modal-cartela-codigo').textContent = result.cartela.codigo;
                document.getElementById('modal-cartela-id').value = result.cartela.id;

                const bingoTable = document.getElementById('modal-bingo-card').querySelector('tbody');
                bingoTable.innerHTML = '';

                // Renderiza a matriz da cartela e marca os números sorteados
                result.cartela.numeros.forEach(row => {
                    const tr = document.createElement('tr');
                    row.forEach(number => {
                        const td = document.createElement('td');
                        if (number === null) {
                             td.innerHTML = `<img src="{{ asset('storage/' . $festa->coringa_path) }}" class="coringa-image" style="width: 40px;">`;
                        } else {
                            td.textContent = number;
                            if (result.numeros_sorteados.includes(number)) {
                                td.classList.add('marked');
                            }
                        }
                        tr.appendChild(td);
                    });
                    bingoTable.appendChild(tr);
                });

                document.getElementById('modal-status-ganhador').textContent = result.is_winner ? `Status: GANHOU! Cartela Cheia!` : `Status: Faltam ${24 - result.acertos} números para cartela cheia.`;
                document.getElementById('btn-confirmar-vencedor').disabled = !result.is_winner;
                document.getElementById('integridade-warning').style.display = result.is_integrity_ok ? 'none' : 'block';

                // Preenche a lista de prêmios disponíveis no modal
                const premioSelect = document.getElementById('premio_id');
                premioSelect.innerHTML = '<option value="">Selecione um prêmio...</option>';
                if (result.premios_disponiveis) {
                    result.premios_disponiveis.forEach(premio => {
                        const option = document.createElement('option');
                        option.value = premio.id;
                        option.textContent = `${premio.ordem}º - ${premio.titulo}`;
                        premioSelect.appendChild(option);
                    });
                }

                modalValidar.show();
            } else {
                alert(result.message || 'Erro ao validar cartela.');
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro de comunicação.');
        }
    });

    // Confirmação do vencedor
    formConfirmarVencedor.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formConfirmarVencedor);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(`/sorteio/${festaId}/confirmar-vencedor`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(data)
            });
            const result = await response.json();

            if (response.ok) {
                alert('Vencedor confirmado com sucesso!');
                modalValidar.hide();
                inputCodigoCartela.value = '';
            } else {
                alert(result.message || 'Erro ao confirmar vencedor.');
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro de comunicação ao confirmar vencedor.');
        }
    });
</script>
@endpush
