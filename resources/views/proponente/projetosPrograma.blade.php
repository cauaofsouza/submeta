@extends('layouts.app')

@section('content')

    <div class="container">
        @if($errors->any())
            <div class="col-sm-12">
                <br>
                <div class="alert alert-danger">
                    <p>{{$errors->first()}}</p>
                </div>
            </div>
        @endif
        @if(isset($mensagem))
            <div class="col-sm-12">
                <br>
                <div class="alert alert-success">
                    <p>{{ $mensagem }}</p>
                </div>
            </div>
        @endif
        @if(session('mensagem'))
            <div class="col-sm-12">
                <br>
                <div class="alert alert-success">
                    <p>{{session('mensagem')}}</p>
                </div>
            </div>
        @endif
        <div class="row justify-content-center" style="margin-top: 3rem;">
            <div class="col-md-11">
                <div class="card card_conteudo shadow bg-white" style="border-radius:12px; border-width:0px;">
                    <div class="card-header" style="border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #fff">
                        <div class="d-flex justify-content-between align-items-center" style="margin-top: 9px; margin-bottom:-1.5rem">
                            <div class="bottomVoltar">
                                <a href="{{  route('programa.visualizar',['id'=> $programa->id])  }}"  class="btn btn-secondary" style="position:relative; float: right;"><img src="{{asset('img/icons/logo_esquerda.png')}}" alt="" width="15px"></a>
                            </div>
                            <div class="form-group">
                                <h5 class="card-title mb-0" style="font-size:25px; font-family:Arial, Helvetica, sans-serif; color:#1492E6">Propostas vinculadas - {{ $programa->nome }}</h5>

                                @if($hoje > $programa->vigencia_fim)
                                    <h6 class="titulo-table" style="color: red;">Vigência encerrada dia <span style="color: red">{{ date('d/m/Y', strtotime($programa->vigencia_fim)) }}</span></h6>
                                @else
                                    <h6 class="titulo-table" style="color: red;">Vigência até o dia <span style="color: red">{{ date('d/m/Y', strtotime($programa->vigencia_fim)) }}</span></h6>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="card-body" >
                        @if(count($projetos)>0)
                            <table class="table table-bordered table-hover" style="display: block; overflow-x: visible; white-space: nowrap; border-radius:10px; margin-bottom:0px">
                                <thead>
                                <tr>
                                    <th scope="col" style="width:100%">Nome do projeto</th>
                                    <th scope="col">Data de Vinculação</th>
                                    <th scope="col" style="text-align:center">Status</th>
                                    <th scope="col" style="text-align:center">Opção</th>
                                </tr>
                                </thead>
                                <tbody id="projetos">
                                @foreach ($projetos as $projeto)
                                    <tr>
                                        <td style="max-width:100px; overflow-x:hidden; text-overflow:ellipsis">
                                            {{ $projeto->titulo }}
                                        </td>
                                        <td style="text-align: center">{{ date('d-m-Y', strtotime($projeto->updated_at)) }}</td>
                                        @if($projeto->programa_extensao_status == 'aceito')
                                            <td style="color: rgb(6, 85, 6); text-align: center;text-transform: capitalize;">{{$projeto->programa_extensao_status}}</td>
                                        @elseif($projeto->programa_extensao_status == 'rejeitado')
                                            <td style="color: rgb(200, 0, 0); text-align: center;text-transform: capitalize;">{{$projeto->programa_extensao_status}}</td>
                                        @else
                                            <td style="color: rgb(0, 0, 0); text-align: center;text-transform: capitalize;">Pendente</td>
                                        @endif

                                        <td>
                                            <div class="dropright dropdown-options" style="width: 100%; text-align:center; float:none">
                                                <a id="options" class="dropdown-toggle btn btn-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <img src="{{asset('img/icons/ellipsis-v-solid.svg')}}" style="width:8px">
                                                </a>
                                                <div class="dropdown-menu">

                                                    <a href="{{route('docComplementar.listar', ['projeto_id' => $projeto->id])}}" class="dropdown-item" style="text-align: center">
                                                        Documentos Complementares
                                                    </a>
                                                    <hr class="dropdown-hr">

                                                    <a href="{{ route('trabalho.show', ['id' => $projeto->id]) }}" class="dropdown-item" style="text-align: center">
                                                        Visualizar
                                                    </a>

                                                    <hr class="dropdown-hr">
                                                    <a href="{{route('planos.listar', ['id' => $projeto->id])}}" class="dropdown-item" style="text-align: center">
                                                        Relatórios
                                                    </a>

                                                    <hr class="dropdown-hr">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col text-center" style="margin-left: 20px">
                                                                <button type="button" class="dropdown-item dropdown-item-delete" data-toggle="modal" data-target="#modal{{$projeto->id}}" style="text-align: center">
                                                                    <img src="{{asset('img/icons/logo_lixeira.png')}}" alt=""> Deletar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="form-row justify-content-center" style="margin-top: 5rem; margin-bottom:9rem;">
                                <div class="col-md-12" style="text-align: center; margin-bottom:20px">
                                    <img src="{{asset('img/icons/logo_projeto.png')}}" style="width:200px">
                                </div>
                                <div class="col-md-12" style="text-align: center; color:#909090">
                                    <h5>Nenhum projeto vinculado a este programa!</h5>
                                </div>
                                <div class="col-md-12" style="text-align: center;">
                                    <a href="{{ route('programa.visualizar', ['id' => $programa->id]) }}">Clique aqui para voltar ao programa e vincular uma proposta.</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-12" style="padding-left: 20px">
                        {{ $projetos->links() }}
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

@section('javascript')
    <script>
        function buscarEdital(input) {
            var editais = document.getElementById('projetos').children;
            if(input.value.length > 2) {
                for(var i = 0; i < editais.length; i++) {
                    var nomeEvento = editais[i].children[0].textContent;
                    if(nomeEvento.substr(0).indexOf(input.value) >= 0) {
                        editais[i].style.display = "";
                    } else {
                        editais[i].style.display = "none";
                    }
                }
            } else {
                for(var i = 0; i < editais.length; i++) {
                    editais[i].style.display = "";
                }
            }
        }
    </script>
@endsection