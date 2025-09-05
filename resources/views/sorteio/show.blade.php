{{-- resources/views/sorteio/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12 text-center">
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
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="input-group mb-3">
                <input type="number" class="form-control form-control-lg" placeholder="Digite o número" id="numero-registrar" min="1" max="75">
                <button class="btn btn-primary btn-lg" type="button" id="btn-registrar-numero"><i class="fas fa-check"></i> Registrar</button>
            </div>
        </div>

        <div class="col-md-6">
            <div class="input-group mb-3">
                <input type="text" class="form-control form-control-lg" placeholder="Código da Cartela" id="codigo-cartela">
                <button class="btn btn-success btn-lg" type="button" id="btn-validar-cartela"><i class="fas fa-search"></i> Validar Cartela</button>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3">
                <button class="btn btn-warning btn-sm me-2" id="remove-last-sorted" style="display: {{ $sorteios->isEmpty() ? 'none' : 'block' }}">
                    <i class="fas fa-undo"></i> Remover Último
                </button>
                <button class="btn btn-danger btn-sm" id="limpar-sorteio" style="display: {{ $sorteios->isEmpty() ? 'none' : 'block' }}">
                    <i class="fas fa-trash"></i> Limpar Sorteio
                </button>
            </div>
    </div>

    <hr>

    <div class="row mt-4">
        <div class="col-md-12 text-center">


            <h4 id="contador-sorteios"></h4>
            <div class="d-flex justify-content-center flex-wrap" id="numeros-agrupados-lista">
                {{-- Lista gerada via JS --}}
            </div>
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
    const listaSorteados = document.getElementById('numeros-agrupados-lista');
    const bolaGigante = document.getElementById('bola-gigante');
    const inputNumeroRegistrar = document.getElementById('numero-registrar');
    const inputCodigoCartela = document.getElementById('codigo-cartela');
    const btnRegistrarNumero = document.getElementById('btn-registrar-numero');
    const btnRemoverUltimo = document.getElementById('remove-last-sorted');
    const btnLimparSorteio = document.getElementById('limpar-sorteio');
    const btnValidarCartela = document.getElementById('btn-validar-cartela');
    const modalValidar = new bootstrap.Modal(document.getElementById('validarCartelaModal'));
    const formConfirmarVencedor = document.getElementById('form-confirmar-vencedor');
    const contadorSorteios = document.getElementById('contador-sorteios');

    // Mapeia os números para as letras de bingo
    const getBingoLetter = (number) => {
        if (number >= 1 && number <= 15) return 'B';
        if (number >= 16 && number <= 30) return 'I';
        if (number >= 31 && number <= 45) return 'N';
        if (number >= 46 && number <= 60) return 'G';
        if (number >= 61 && number <= 75) return 'O';
        return '';
    };

    // Função para remover um número específico da lista
    const removeNumberFromList = async (numero) => {
        const result = await Swal.fire({
            title: `Remover o número ${numero}?`,
            text: "Deseja realmente remover este número do sorteio?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, remover!',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`${base_URL}/sorteio/${festaId}/remover-numero`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ numero: numero })
                });
                const data = await response.json();
                if (response.ok) {
                    updateUi();
                    Swal.fire({
                        title: 'Removido!',
                        text: data.message || `Número ${numero} removido com sucesso.`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Erro', data.message || 'Erro ao remover número.', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                Swal.fire('Erro de Comunicação', 'Não foi possível se conectar ao servidor.', 'error');
            }
        }
    };

    // Atualiza a lista e a bola gigante
    const updateUi = async () => {
        try {
            const response = await fetch(`${base_URL}/sorteio/${festaId}`, { headers: { 'Accept': 'application/json' } });
            if (!response.ok) {
                throw new Error('Falha ao buscar dados do sorteio.');
            }
            const data = await response.json();

            // Limpa a lista
            listaSorteados.innerHTML = '';

            // Agrupa e ordena os sorteios por letra
            const agrupados = { 'B': [], 'I': [], 'N': [], 'G': [], 'O': [] };
            (data.sorteios || []).forEach(s => {
                const letra = getBingoLetter(s.numero);
                if (letra) {
                    agrupados[letra].push(s.numero);
                }
            });

            // Cria os elementos para a lista agrupada com botões de remoção
            for (const letra in agrupados) {
                if (agrupados[letra].length > 0) {
                    const grupoDiv = document.createElement('div');
                    grupoDiv.classList.add('p-2', 'me-3');
                    grupoDiv.innerHTML = `
                        <h6>${letra}</h6>
                        <ul class="list-group">
                            ${agrupados[letra].sort((a, b) => a - b).map(numero => `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>${numero}</span>
                                    <button class="btn btn-danger btn-sm p-1 ms-2 remove-individual" data-numero="${numero}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </li>
                            `).join('')}
                        </ul>
                    `;
                    listaSorteados.appendChild(grupoDiv);
                }
            }

            // Adiciona um listener para os botões de remoção individuais
            document.querySelectorAll('.remove-individual').forEach(button => {
                button.addEventListener('click', (e) => {
                    const numero = e.currentTarget.dataset.numero;
                    removeNumberFromList(numero);
                });
            });

            // Atualiza a bola gigante com o último número sorteado
            const ultimoSorteio = data.sorteios[0];
            bolaGigante.textContent = ultimoSorteio ? `${getBingoLetter(ultimoSorteio.numero)}${ultimoSorteio.numero}` : '?';

            // Atualiza o contador
            const totalSorteado = data.sorteios.length;
            const totalFaltando = 75 - totalSorteado;
            contadorSorteios.textContent = `${totalSorteado} de 75 pedras sorteadas. Faltam ${totalFaltando}.`;

            // Alterna a exibição dos botões
            btnRemoverUltimo.style.display = totalSorteado > 0 ? 'block' : 'none';
            btnLimparSorteio.style.display = totalSorteado > 0 ? 'block' : 'none';

        } catch (error) {
            console.error('Erro ao atualizar a interface:', error);
            Swal.fire({
                title: 'Erro!',
                text: error,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    };

    updateUi(); // Chamada inicial para carregar a UI

    // Registra um novo número
    btnRegistrarNumero.addEventListener('click', async () => {
        const numero = inputNumeroRegistrar.value;
        if (!numero) {
            return Swal.fire({
                title: 'Atenção!',
                text: 'Por favor, digite um número.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }

        if (numero < 1 || numero > 75) {
            return Swal.fire({
                title: 'Atenção!',
                text: 'O número deve estar entre 1 e 75.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }

        try {
            const response = await fetch(`${base_URL}/sorteio/${festaId}/registrar-numero`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ numero: parseInt(numero) })
            });
            const data = await response.json();

            if(data.success) {
                inputNumeroRegistrar.value = '';
                updateUi(); // **CORRIGIDO: Chamada para updateUi()**
            } else {
                Swal.fire({
                    title: 'Erro!',
                    text: data.message || 'Erro ao registrar número.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                title: 'Erro de Comunicação!',
                text: 'Não foi possível se conectar ao servidor.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });

    // Remove o último número registrado
    btnRemoverUltimo.addEventListener('click', async () => {
        const result = await Swal.fire({
            title: 'Tem certeza?',
            text: 'Deseja remover o último número sorteado?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`${base_URL}/sorteio/${festaId}/remover-ultimo`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await response.json();

                if (data.success) {
                    updateUi(); // **CORRIGIDO: Chamada para updateUi()**
                } else {
                    Swal.fire('Erro', data.message || 'Erro ao remover número.', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                Swal.fire('Erro de Comunicação', 'Não foi possível se conectar ao servidor.', 'error');
            }
        }
    });

    // Limpa todos os números sorteados
    btnLimparSorteio.addEventListener('click', async () => {
        const result = await Swal.fire({
            title: 'Tem certeza?',
            text: 'Deseja limpar todo o sorteio e começar uma nova rodada?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, limpar!',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`${base_URL}/sorteio/${festaId}/limpar`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await response.json();
                if (data.success) {
                      Swal.fire({
                        title: 'Success!',
                        text: 'Sorteio limpo com sucesso!',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then(function () {
                        updateUi(); // **CORRIGIDO: Chamada para updateUi()**
                    });
                } else {
                    Swal.fire('Erro', data.message || 'Erro ao limpar sorteio.', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                Swal.fire('Erro de Comunicação', 'Não foi possível se conectar ao servidor.', 'error');
            }
        }
    });

    // Valida a cartela
    btnValidarCartela.addEventListener('click', async () => {
        const codigo = inputCodigoCartela.value;
        if (!codigo) {
            return Swal.fire({
                title: 'Atenção!',
                text: 'Por favor, digite o código da cartela.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }

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

                const numerosSorteados = result.numeros_sorteados;

                // Renderiza a matriz da cartela e marca os números que NÃO foram sorteados
                result.cartela.numeros.forEach(row => {
                    const tr = document.createElement('tr');
                    row.forEach(number => {
                        const td = document.createElement('td');
                        if (number === null) {
                             td.innerHTML = `<img src="{{ asset('storage/' . $festa->coringa_path) }}" class="coringa-image" style="width: 40px;">`;
                        } else {
                            td.textContent = number;
                            // AQUI está a mudança: marca os números que NÃO foram sorteados
                            if (!numerosSorteados.includes(number)) {
                                td.classList.add('not-marked'); // Nova classe CSS
                            } else {
                                td.classList.add('marked'); // Mantém a classe original para os sorteados
                            }
                        }
                        tr.appendChild(td);
                    });
                    bingoTable.appendChild(tr);
                });

                document.getElementById('modal-status-ganhador').textContent = result.is_winner ? `Status: GANHOU! Cartela Cheia!` : `Status: Faltam ${24 - result.acertos} números para cartela cheia.`;
                document.getElementById('btn-confirmar-vencedor').disabled = !result.is_winner;
                document.getElementById('integridade-warning').style.display = result.is_integrity_ok ? 'none' : 'block';

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
                Swal.fire({
                    title: 'Erro!',
                    text: result.message || 'Erro ao validar cartela.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                title: 'Erro de Comunicação!',
                text: 'Não foi possível se conectar ao servidor.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });

    // Confirmação do vencedor
    formConfirmarVencedor.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formConfirmarVencedor);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(`${base_URL}/sorteio/${festaId}/confirmar-vencedor`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(data)
            });
            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    title: 'Sucesso!',
                    text: 'Vencedor confirmado com sucesso!',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                modalValidar.hide();
                inputCodigoCartela.value = '';
            } else {
                Swal.fire({
                    title: 'Erro!',
                    text: result.message || 'Erro ao confirmar vencedor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                title: 'Erro de Comunicação!',
                text: 'Não foi possível se conectar ao servidor.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
</script>
<style>
    /* Estilo para a célula da cartela com número não sorteado */
    #modal-bingo-card .not-marked {
        background-color: #dc3545; /* Vermelho */
        color: white;
        font-weight: bold;
    }
    /* Mantém o estilo para a célula da cartela com número sorteado */
    #modal-bingo-card .marked {
        background-color: #28a745; /* Verde */
        color: white;
        font-weight: bold;
    }
</style>
@endpush
