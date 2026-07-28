@extends('layouts.app')

@section('content')

    <div class="container" style="margin-top: 100px;">

        <div class="row justify-content-center">
            <div class="container" style="margin-bottom: 1rem;">
                @if(!Auth::check())
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong> A submissão de um projeto é possível apenas quando cadastrado no sistema. </strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                    @if(session('mensagem'))
                        <div class="row">
                            <div class="col-md-12" style="margin-top: -70px;">
                                <div class="alert alert-success">
                                    <p>{{session('mensagem')}}</p>
                                </div>
                            </div>
                        </div>
                    @endif
            </div>


            <div class="col-md-7" style="margin-bottom:10px">
                <div class="form-row">
                    <div class="col-md-12" style="margin-bottom:20px">
                        <div class="card shadow bg-white" style="border-radius:12px; border-width:0px;">
                            <img src="{{asset('img/img_fundo_2.png')}}" class="card-img-top" alt="..." style="border-radius:12px">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12" style="margin-bottom: 0.5rem">
                                        <h5 class="card-title mb-0" style="font-size:30px; font-family:Arial, Helvetica, sans-serif; color:#0842A0; font-weight:bold">{{ $programa->nome }}</h5>
                                    </div>

                                    <div class="col-md-12" style="margin-top: 5px">
                                        <div><h5 class="card-title mb-0" style="font-size:20px; font-family:Arial, Helvetica, sans-serif; color:#1492E6;">Descrição</h5></div>
                                        <pre wrap>
                      <div style="margin-top: 10px"><h5 style="font-size: 16px; font-weight:normal; text-align:justify; font-family:Arial, Helvetica, sans-serif">{{ $programa->descricao }}</h5></div>
                    </pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== TRABALHOS LIGADOS (Coordenador / Vice) ===================== --}}
                    @if($isCoordenadorOuVice)
                        <div class="col-md-12" style="margin-bottom:20px">
                            <div class="card shadow bg-white" style="border-radius:12px; border-width:0px;">
                                <div class="card-header" style="border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #fff">
                                    <div class="d-flex justify-content-between align-items-center" style="margin-top: 9px; margin-bottom:6px">
                                        <h5 class="card-title mb-0" style="font-size:25px; font-family:Arial, Helvetica, sans-serif; color:#1492E6">Trabalhos Vinculados</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a class="btn btn-primary" href="{{ route('admin.analisar-trabalhos-programa-extensao', ['id' => $programa->id]) }}" style="width:100%; height:50px; padding-top:7px; font-size:20px">
                                        Visualizar Trabalhos ({{ $trabalhos->count() }})
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-row">

                    {{-- ===================== AÇÕES (apenas proponente) ===================== --}}
                    @if($proponente)
                        <div class="col-md-12" style="margin-bottom:30px">
                            <div class="card card_conteudo shadow bg-white" style="border-radius:12px; border-width:0px;">
                                <div class="card-header" style="border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #fff">
                                    <div class="d-flex justify-content-between align-items-center" style="margin-top: 9px; margin-bottom:6px">
                                        <h5 class="card-title mb-0" style="font-size:25px; font-family:Arial, Helvetica, sans-serif; color:#1492E6">Ações</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        @if($programa->vigencia_inicio <= $hoje && $hoje <= $programa->vigencia_fim)
                                            <div class="col-md-12" style="margin-bottom:18px">
                                                <a class="btn btn-success" data-toggle="modal" data-target="#modalVincularProposta" style="width:100%; height:50px; padding-top:7px; font-size:20px">
                                                    <img src="{{asset('img/icons/icon_enviar_proposta.png')}}" class="card-img-top" alt="..." style="width:30px; margin-right:5px"> Vincular Proposta
                                                </a>
                                            </div>
                                        @endif

                                        <div class="col-md-12">
                                            <a class="btn btn-primary" href="{{ route('proponente.projetosPrograma', ['id' => $programa->id]) }}" style="width:100%; height:50px; padding-top:5px; font-size:20px">
                                                <img src="{{asset('img/icons/icon_minhas_propostas.png')}}" class="card-img-top" alt="..." style="width:20px; margin-right:10px; margin-top:-5px"> Minhas propostas
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- ===================== DATAS ===================== --}}
                    <div class="col-md-12" style="margin-bottom:30px">
                        <div class="card card_conteudo shadow bg-white" style="border-radius:12px; border-width:0px;">
                            <div class="card-header" style="border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #fff">
                                <div class="d-flex justify-content-between align-items-center" style="margin-top: 9px; margin-bottom:6px">
                                    <h5 class="card-title mb-0" style="font-size:22px; font-family:Arial, Helvetica, sans-serif; color:#1492E6">Vigência do Programa de Extensão</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-left align-items-center">
                                            <div style="margin-right:10px; margin-top:-20px">
                                                <img class="" src="{{asset('img/icons/icon_submissao.png')}}" alt="" width="40px">
                                            </div>
                                            <div class="form-group">
                                                <div><h5 style="font-size:17px; font-weight: normal; color:#909090">{{ date('d/m/Y', strtotime($programa->vigencia_inicio)) }} - {{ date('d/m/Y', strtotime($programa->vigencia_fim)) }}</h5></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== DOCUMENTOS ===================== --}}
                    <div class="col-md-12" style="margin-bottom:30px">
                        <div class="card card_conteudo shadow bg-white" style="border-radius:12px; border-width:0px;">
                            <div class="card-header" style="border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #fff">
                                <div class="d-flex justify-content-between align-items-center" style="margin-top: 9px; margin-bottom:6px">
                                    <h5 class="card-title mb-0" style="font-size:22px; font-family:Arial, Helvetica, sans-serif; color:#1492E6">Documentos</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-row">

                                    {{-- Edital --}}
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-left align-items-center" style="margin-bottom: -15px">
                                            <div style="margin-right:10px; margin-top:-15px">
                                                <img class="" src="{{asset('img/icons/icon_edital.png')}}" alt="" width="40px">
                                            </div>
                                            <div class="form-group" style="width: 100%">
                                                <div class="d-flex justify-content-between" style="width: 100%">
                                                    <div><h5 style="font-size:17px; margin-top:18px">Edital</h5></div>
                                                    <div style="float: right">
                                                        @if($programa->pdf_edital)
                                                            <a class="btn btn-light" href="{{ Storage::url($programa->pdf_edital) }}" target="_new">
                                                                <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px"><br>
                                                                Baixar
                                                            </a>
                                                        @else
                                                            <span style="color:#909090">Não disponível</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12"><hr></div>

                                    {{-- Outros documentos --}}
                                    <div class="col-md-12">
                                        @if($programa->modelo_documento != null)
                                            <div class="d-flex justify-content-left align-items-center" style="margin-bottom: -15px">
                                                <div style="margin-right:10px; margin-top:-15px">
                                                    <img class="" src="{{asset('img/icons/icon_modelo.png')}}" alt="" width="40px">
                                                </div>
                                                <div class="form-group" style="width: 100%">
                                                    <div class="d-flex justify-content-between" style="width: 100%">
                                                        <div><h5 style="font-size:17px; margin-top:9px">Outros<br>documentos</h5></div>
                                                        <div>
                                                            <a class="btn btn-light" href="{{ Storage::url($programa->modelo_documento) }}" target="_new">
                                                                <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px"><br>
                                                                Baixar
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <h6 style="color: #909090">O criador do programa não disponibilizou modelos</h6>
                                        @endif
                                    </div>
                                    <div class="col-md-12"><hr></div>

                                    {{-- Relatório Anual (visualização apenas, ainda sem funcionalidade) --}}
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-left align-items-center" style="margin-bottom: -15px">
                                            <div style="margin-right:10px; margin-top:-15px">
                                                <img class="" src="{{asset('img/icons/icon_modelo.png')}}" alt="" width="40px">
                                            </div>
                                            <div class="form-group" style="width: 100%">
                                                <div class="d-flex justify-content-between" style="width: 100%">
                                                    <div><h5 style="font-size:17px; margin-top:18px">Relatório<br>Anual</h5></div>
                                                    <div style="float: right">
                                                        <span style="color:#909090">Não disponível</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

{{--    MODAL VINCULAR PROPOSTA--}}
    <div class="modal fade" id="modalVincularProposta" tabindex="-1" role="dialog" aria-labelledby="modalVincularPropostaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVincularPropostaLabel">Vincular Proposta ao Programa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('programa.solicitarVinculo', $programa->id) }}">
                    @csrf
                    <div class="modal-body">
                        @if($trabalhosDisponiveis->isEmpty())
                            <p>Você não possui propostas disponíveis para vincular.</p>
                        @else
                            <p>Selecione a proposta que deseja vincular a este programa:</p>
                            @foreach($trabalhosDisponiveis as $trabalho)
                                <div class="form-check" style="margin-bottom:8px">
                                    <input class="form-check-input" type="radio" name="trabalho_id"
                                           id="trabalho{{ $trabalho->id }}" value="{{ $trabalho->id }}" required>
                                    <label class="form-check-label" for="trabalho{{ $trabalho->id }}">
                                        {{ $trabalho->titulo }}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        @if($trabalhosDisponiveis->isNotEmpty())
                            <button type="submit" class="btn btn-success">Vincular</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
    </script>
@endsection