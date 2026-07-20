@extends('layouts.app')

@section('content')

<div class="container" style="margin-top: 100px;">

  <div class="container" >

    <div class="row p-b-5" >

      <div class="col-sm-4" style="float: start;">
        <h3 class="title-table">Meus Editais</h3>
      </div>
      <div class="col-sm-8 align-text-top aling-end ">
          <a href="{{route('evento.criar')}}" class="btn btn-info" style="float: right;">Criar Edital</a>
        <a href="{{route('programa.criar')}}" class="btn btn-info" style="float: right; margin-right:20px">Criar Programa de Extensão</a>

      </div>

    </div>

  </div>

  <hr>
  @if(session('mensagem'))
    <div class="row">
      <div class="col-md-12" style="margin-top: 30px;">
        <div class="alert alert-success">
            <p>{{session('mensagem')}}</p>
        </div>
      </div>
    </div>
  @endif
  <table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">Nome do Edital/Programa</th>
        <th scope="col">Data de Criação</th>
        <th scope="col">Opção</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($listagem as $item)
      <tr>
        <td>
          <a href="{{ $item['tipo'] === 'evento'
                ? route('evento.visualizar', ['id' => $item['id']])
                : route('programa.visualizar', ['id' => $item['id']]) }}"
             class="visualizarEvento">
            {{ $item['nome'] }}
          </a>
        </td>
        <td>{{ date('d/m/Y \à\s H:i\h', strtotime($item['created_at'])) }}</td>
        <td>
          <div class="btn-group dropright dropdown-options">
            <a id="options" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img src="{{ asset('img/icons/ellipsis-v-solid.svg') }}" style="width:8px">
            </a>

            {{-- DROPDOWN EVENTO --}}
            @if($item['tipo'] === 'evento')
              <div class="dropdown-menu">
                <a href="{{ route('evento.editar', ['id' => $item['id']]) }}" class="dropdown-item text-center">
                  Editar Edital
                </a>
                <hr class="dropdown-hr">
                <a href="{{ route('admin.analisar', ['evento_id' => $item['id']]) }}" class="dropdown-item text-center">
                  Visualizar Projetos
                </a>
                <hr class="dropdown-hr">
                <a href="{{ route('admin.atribuir', ['evento_id' => $item['id']]) }}" class="dropdown-item text-center">
                  Atribuir Avaliadores
                </a>
                <hr class="dropdown-hr">
                <a href="{{ route('admin.pareceres', ['evento_id' => $item['id']]) }}" class="dropdown-item text-center">
                  Visualizar Pareceres
                </a>
                @if($item['tipoAvaliacao'] != 'link')
                  <hr class="dropdown-hr">
                  <a href="{{ route('admin.showResultados', ['evento_id' => $item['id']]) }}" class="dropdown-item text-center">
                    Resultados
                  </a>
                @endif
                <hr class="dropdown-hr">
                <button type="button" class="dropdown-item dropdown-item-delete text-center"
                        data-toggle="modal" data-target="#exampleModal{{ $item['id'] }}">
                  <img src="{{ asset('img/icons/logo_lixeira.png') }}" alt=""> Deletar
                </button>
              </div>

              {{-- DROPDOWN PROGRAMA DE EXTENSÃO --}}
            @else
              <div class="dropdown-menu">
                <a href="{{ route('programa.editar', ['id' => $item['id']]) }}" class="dropdown-item text-center">
                  Editar Programa
                </a>
                <hr class="dropdown-hr">
                <a href="{{ route('admin.analisar-trabalhos-programa-extensao', ['id' => $item['id']]) }}" class="dropdown-item text-center">
                  Visualizar Projetos
                </a>
                <hr class="dropdown-hr">
                <button type="button" class="dropdown-item dropdown-item-delete text-center"
                        data-toggle="modal" data-target="#exampleModal{{ $item['id'] }}">
                  <img src="{{ asset('img/icons/logo_lixeira.png') }}" alt=""> Deletar
                </button>
              </div>
            @endif

          </div>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>

@endsection

@section('javascript')
<script>

</script>
@endsection
