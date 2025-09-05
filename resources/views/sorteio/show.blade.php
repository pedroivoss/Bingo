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

   {{-- A seção do histórico de números sorteados na sua view --}}
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <h4 id="contador-sorteios"></h4>
            <div class="d-flex justify-content-center flex-wrap" id="numeros-agrupados-lista">
                {{-- A lista será gerada aqui pelo JavaScript --}}
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
                    grupoDiv.classList.add('bingo-group-column', 'p-3', 'mx-2'); // Nova classe para o grupo
                    grupoDiv.innerHTML = `
                        <h6 class="bingo-letter-header mb-2">${letra}</h6> <div class="list-group bingo-numbers-list"> ${agrupados[letra].sort((a, b) => a - b).map(numero => `
                                <div class="list-group-item d-flex justify-content-between align-items-center bingo-number-item p-2 my-1">
                                    <span class="bingo-number-text">${numero}</span>
                                    <button class="btn btn-sm p-0 ms-2 remove-individual bingo-remove-btn" data-numero="${numero}">
                                        <i class="fas fa-times-circle"></i> </button>
                                </div>
                            `).join('')}
                        </div>
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
    /* Estilos Gerais para o Layout do Histórico */
    #numeros-agrupados-lista {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 1rem;
    }

    /* Estilo para cada coluna de grupo de letras (B, I, N, G, O) */
    .bingo-group-column {
        background-color: #f8f9fa;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 8px;
        min-width: 70px;
        flex-grow: 1;
        max-width: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Estilo para a letra (B, I, N, G, O) */
    .bingo-letter-header {
        font-size: 1.5rem;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 5px;
        text-align: center;
        width: 100%;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 3px;
    }

    /* Estilo para a lista de números dentro de cada grupo */
    .bingo-numbers-list {
        width: 100%;
    }

    /* Estilo para cada item de número sorteado */
    .bingo-number-item {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin-bottom: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease-in-out;
        font-size: 1.2rem; /* TAMANHO DO NÚMERO BEM REDUZIDO */
        font-weight: 600;
        color: #343a40;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 4px 6px;
    }

    .bingo-number-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Estilo do botão de remover individual */
    .bingo-remove-btn {
        color: #dc3545;
        background: none;
        border: none;
        font-size: 1.2rem; /* TAMANHO DO ÍCONE BEM REDUZIDO */
        padding: 0;
        line-height: 1;
        opacity: 0.7;
        transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
    }

    .bingo-remove-btn:hover {
        opacity: 1;
        transform: scale(1.1);
        color: #c82333;
    }

    /* Ocultar texto do botão, se houver */
    .bingo-remove-btn span {
        display: none;
    }

    /* Estilos para a validação da cartela */
    #modal-bingo-card .not-marked {
        background-color: #dc3545;
        color: white;
        font-weight: bold;
    }
    #modal-bingo-card .marked {
        background-color: #28a745;
        color: white;
        font-weight: bold;
    }

    /* Media Queries para Telas Menores */
    @media (max-width: 768px) {
        .bingo-group-column {
            min-width: 60px;
            max-width: 100%;
            margin: 5px 0;
            padding: 5px;
        }
        .bingo-letter-header {
            font-size: 1.2rem;
        }
        .bingo-number-item {
            font-size: 1rem;
            padding: 3px 5px;
        }
        .bingo-remove-btn {
            font-size: 1rem;
        }
        #bola-gigante {
            font-size: 3rem !important;
        }
    }
</style>
@endpush
