{{-- resources/views/painel/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Painel Administrativo</h1>
        <a href="{{ route('painel.create-festa') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Festa
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse ($festas as $festa)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $festa->nome }}</h5>
                        <p class="card-text">Data: {{ $festa->data->format('d/m/Y') }}</p>
                        <p class="card-text">Cartelas Geradas: {{ $festa->cartelas->count() }}</p>
                        <h6>Prêmios:</h6>
                        <ul>
                            @forelse ($festa->premios->sortBy('ordem') as $premio)
                                <li>{{ $premio->ordem }}º - {{ $premio->titulo }}</li>
                            @empty
                                <li>Nenhum prêmio cadastrado.</li>
                            @endforelse
                        </ul>
                        <hr>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#gerarCartelasModal{{ $festa->id }}">
                                <i class="fas fa-magic"></i> Gerar Cartelas
                            </button>

                            @if($festa->folhas->count() > 0)
                                <a href="{{ route('festas.showPdfs', $festa) }}" class="btn btn-secondary btn-sm"><i class="fas fa-download"></i> Ver PDFs</a>
                            @endif

                            {{--<a href="{{ route('gerar.Pdfs') }}" class="btn btn-secondary btn-sm"><i class="fas fa-download"></i> ver template PDFs</a>--}}
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#gerarPDFsModal{{ $festa->id }}">
                                <i class="fas fa-file-pdf"></i> Gerar PDFs
                            </button>
                            <a href="{{ route('sorteio.show', $festa) }}" class="btn btn-success btn-sm"><i class="fas fa-dice"></i> Ir para Sorteio</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="gerarPDFsModal{{ $festa->id }}" tabindex="-1" aria-labelledby="gerarPDFsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="gerarPDFsModalLabel">Gerar PDFs para {{ $festa->nome }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span>Total de Cartelas: {{ $festa->cartelas->count() }}</span>
                            <br>
                            <div class="mb-3">
                                <label for="cartelas_por_arquivo" class="form-label">Cartelas por Arquivo PDF</label>
                                <input type="number" class="form-control" id="cartelas_por_arquivo" name="cartelas_por_arquivo" min="1" max="1000" placeholder="informe o numero" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button id="gerarPDF" data-festa-qtd-cartela="{{ $festa->cartelas->count() }}" value="{{ $festa->id }}" class="btn btn-primary">Gerar PDFs</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="gerarCartelasModal{{ $festa->id }}" tabindex="-1" aria-labelledby="gerarCartelasModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {{--<form action="{{ route('painel.generate-cards', $festa) }}" method="POST">
                            @csrf--}}
                            <div class="modal-header">
                                <h5 class="modal-title" id="gerarCartelasModalLabel">Gerar Cartelas para {{ $festa->nome }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="quantidade" class="form-label">Quantidade de Cartelas</label>
                                    <input type="number" class="form-control" id="quantidade" name="quantidade" min="1" max="20000" required>
                                </div>
                                {{--<div class="mb-3">
                                    <label for="quantidade_por_folha" class="form-label">Cartelas por Página (PDF)</label>
                                    <select class="form-select" id="quantidade_por_folha" name="quantidade_por_folha" required>
                                        <option value="1">1 cartela por folha (2 distintas por página A4)</option>
                                        <option value="2">Multiplas cartelas por folha (iguais)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="texto_lateral" class="form-label">Texto Lateral (apenas para 1 cartela por folha)</label>
                                    <textarea class="form-control" id="texto_lateral" name="texto_lateral" rows="3"></textarea>
                                </div>--}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                <button id="gerarCartelasFesta" data-cartelas-ja-geradas="{{ $festa->cartelas->count() }}" value="{{$festa->id}}" class="btn btn-primary">Gerar</button>
                            </div>


                        {{--</form>--}}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Nenhuma festa cadastrada ainda.</div>
            </div>
        @endforelse
    </div>

    @section('jsPersonalizado')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const buttonGerarCartelasFesta = document.querySelector("#gerarCartelasFesta");

                buttonGerarCartelasFesta.addEventListener('click', function () {
                    bloquear()

                    const festaId = this.value;
                    const modal = this.closest('.modal');
                    const quantidade = modal.querySelector('#quantidade').value;
                    const cartelasJaGeradas = parseInt(this.getAttribute('data-cartelas-ja-geradas')) || 0;

                    //colocar trava depois para que conforme o nivel do usuario, ele tem x cartelas maximas

                    if(!quantidade || quantidade < 1) {
                        desbloquear()
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            text: 'Por favor, informe uma quantidade válida de cartelas.',
                        });
                        return;
                    }

                    if(!festaId) {
                        desbloquear()
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'ID da festa não encontrado.',
                        });
                        return;
                    }

                    if((cartelasJaGeradas + parseInt(quantidade)) > maxCartelas) {
                        desbloquear()
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            text: `O número máximo permitido de cartelas é ${maxCartelas}. Você já gerou ${cartelasJaGeradas} cartelas.`,
                        });
                        return;
                    }

                    let formData = new FormData()
                    formData.append('festa_id', festaId);
                    formData.append('quantidade', quantidade);
                    formData.append("_token","{{ csrf_token() }}")

                    fetch(`{{ route('painel.festa.generate-cards') }}`, {
                        method: 'POST',
                        body: formData,
                    })
                    .then(function(response) {
                        response.json().then(function(data) {
                            if(true == data.success){
                                desbloquear()
                                Swal.fire({
                                    title: 'Sucesso!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then(function () {
                                    bloquear()
                                    location.reload();
                                })
                            }else{
                                desbloquear()
                                Swal.fire(
                                    'Warning!',
                                    data.message,
                                    'warning'
                                )
                            }
                        })
                    })
                    .catch(function(err) {
                        desbloquear()
                        Swal.fire('Erro!',err,'error')
                    });
                });

                const buttonGerarPDF = document.querySelector("#gerarPDF");

                buttonGerarPDF.addEventListener('click', function () {

                    bloquear()

                    const festaId = this.value;
                    const modal = this.closest('.modal');
                    const cartelas_por_arquivo = modal.querySelector('#cartelas_por_arquivo').value;

                    const total_cartelas = this.getAttribute('data-festa-qtd-cartela');

                    if(!cartelas_por_arquivo || cartelas_por_arquivo < 1) {
                        desbloquear()
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            text: 'Por favor, informe um número válido de cartelas por arquivo.',
                        });
                        return;
                    }

                    if(cartelas_por_arquivo > 1000) {
                        desbloquear()
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            text: 'O número máximo permitido de cartelas por arquivo é 1000.',
                        });
                        return;
                    }

                    if(!festaId) {
                        desbloquear()
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'ID da festa não encontrado.',
                        });
                        return;
                    }

                    if(parseInt(cartelas_por_arquivo) > parseInt(total_cartelas)) {
                        desbloquear()
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            text: 'O número de cartelas por arquivo não pode ser maior que o total de cartelas.',
                        });
                        return;
                    }

                    let formData = new FormData()
                    formData.append('festa_id', festaId);
                    formData.append('cartelas_por_arquivo', cartelas_por_arquivo);
                    formData.append("_token","{{ csrf_token() }}")

                    fetch(`{{ route('painel.festa.generate-pdf') }}`, {
                        method: 'POST',
                        body: formData,
                    })
                    .then(function(response) {
                        response.json().then(function(data) {
                            if(true == data.success){
                                desbloquear()
                                Swal.fire({
                                    title: 'Sucesso!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then(function () {
                                    bloquear()
                                    location.reload();
                                })
                            }else{
                                desbloquear()
                                Swal.fire(
                                    'Warning!',
                                    data.message,
                                    'warning'
                                )
                            }
                        })
                    })
                    .catch(function(err) {
                        desbloquear()
                        Swal.fire('Erro!',err,'error')
                    });
                });
            });
        </script>
    @endsection
@endsection
