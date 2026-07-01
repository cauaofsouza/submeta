@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row titulo">
            <h1>Novo Programa de Extensão</h1>
        </div>

        <form action="{{ route('evento.criarProgramaExtensao') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipo" value="PROGRAMA_DE_EXTENSAO">

            {{-- ===================== INFORMAÇÕES DO PROGRAMA ===================== --}}
            <div class="row subtitulo">
                <div class="col-sm-12">
                    <p>Informações do Programa de Extensão</p>
                </div>
            </div>

            {{-- Título do Programa --}}
            <div class="row justify-content-start">
                <div class="col-sm-12">
                    <label for="nome" class="col-form-label">
                        {{ __('Título do Programa:') }}<span style="color:red; font-weight:bold;">*</span>
                    </label>
                    <input id="nome" type="text"
                           class="form-control @error('nome') is-invalid @enderror"
                           name="nome" value="{{ old('nome') }}"
                           required autocomplete="off">
                    @error('nome')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            {{-- Coordenador + Vice-Coordenador --}}
            <div class="row justify-content-start mt-2">

                {{-- Coordenador --}}
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-md-11">
                            <label for="coordenador_id" class="col-form-label">
                                {{ __('Coordenador do Programa:') }}<span style="color:red; font-weight:bold;">*</span>
                            </label>
                        </div>
                        <div class="col-md-1 text-sm-right">
                            <a type="button" data-toggle="modal" data-target="#modalCoord">
                                <img src="{{ asset('img/icons/add.ico') }}" style="width:30px" alt="Adicionar">
                            </a>
                        </div>
                    </div>
                    <input id="coordenador_id" name="coordenador_id" class="form-control" value="{{ old('coordenador_id') }}" hidden>
                    <input id="coordenador_name" name="coordenador_name"
                           class="form-control @error('coordenador_id') is-invalid @enderror"
                           value="{{ old('coordenador_name') }}"
                           placeholder="Nenhum Coordenador atribuído"
                           required readonly style="margin-top:5px">
                    @error('coordenador_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                {{-- Vice-Coordenador --}}
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-md-11">
                            <label for="vice_coordenador_id" class="col-form-label">
                                {{ __('Vice-Coordenador do Programa:') }}
                            </label>
                        </div>
                        <div class="col-md-1 text-sm-right">
                            <a type="button" data-toggle="modal" data-target="#modalViceCoord">
                                <img src="{{ asset('img/icons/add.ico') }}" style="width:30px" alt="Adicionar">
                            </a>
                        </div>
                    </div>
                    <input id="vice_coordenador_id" name="vice_coordenador_id" class="form-control" value="{{ old('vice_coordenador_id') }}" hidden>
                    <input id="vice_coordenador_name" name="vice_coordenador_name"
                           class="form-control"
                           value="{{ old('vice_coordenador_name') }}"
                           placeholder="Nenhum Vice-Coordenador atribuído"
                           readonly style="margin-top:5px">
                </div>
            </div>

            {{-- Vigência do Programa --}}
            <div class="row justify-content-start mt-2">
                <div class="col-sm-6">
                    <label for="vigencia_inicio" class="col-form-label">
                        {{ __('Vigência do Programa — Início:') }}<span style="color:red; font-weight:bold;">*</span>
                    </label>
                    <input id="vigencia_inicio" type="date"
                           class="form-control @error('vigencia_inicio') is-invalid @enderror"
                           name="vigencia_inicio" value="{{ old('vigencia_inicio') }}" required>
                    @error('vigencia_inicio')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-sm-6">
                    <label for="vigencia_fim" class="col-form-label">
                        {{ __('Vigência do Programa — Fim:') }}<span style="color:red; font-weight:bold;">*</span>
                    </label>
                    <input id="vigencia_fim" type="date"
                           class="form-control @error('vigencia_fim') is-invalid @enderror"
                           name="vigencia_fim" value="{{ old('vigencia_fim') }}" required>
                    @error('vigencia_fim')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            {{-- Área Temática Principal + Secundária --}}
            <div class="row justify-content-start mt-2">

                {{-- Principal (selecionar apenas uma) --}}
                <div class="col-sm-6">
                    <label class="col-form-label">
                        {{ __('Área Temática (Principal):') }}<span style="color:red; font-weight:bold;">*</span>
                        <small class="text-muted d-block">Selecione apenas uma.</small>
                    </label>
                    @foreach($areas_tematicas as $area_tematica)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="area_tematica_principal[]"
                                   id="area_principal_{{ $area_tematica->id }}"
                                   value="{{ $area_tematica->id }}"
                                   {{ in_array($area_tematica->id, old('area_tematica_principal', [])) ? 'checked' : '' }}
                                   onchange="limitCheckbox('area_tematica_principal', 'area_tematica_secundaria', this)">
                            <label class="form-check-label" for="area_principal_{{ $area_tematica->id }}">
                                {{ $area_tematica->nome }}
                            </label>
                        </div>
                    @endforeach
                    @error('area_tematica_principal')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                {{-- Secundária (se houver, selecionar apenas uma) --}}
                <div class="col-sm-6">
                    <label class="col-form-label">
                        {{ __('Área Temática (Secundária):') }}
                        <small class="text-muted d-block">Se houver, selecione apenas uma.</small>
                    </label>
                    @foreach($areas_tematicas as $area_tematica)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="area_tematica_secundaria[]"
                                   id="area_secundaria_{{ $area_tematica->id }}"
                                   value="{{ $area_tematica->id }}"
                                   {{ in_array($area_tematica->id, old('area_tematica_secundaria', [])) ? 'checked' : '' }}
                                   onchange="limitCheckbox('area_tematica_secundaria', 'area_tematica_principal', this)">
                            <label class="form-check-label" for="area_secundaria_{{ $area_tematica->id }}">
                                {{ $area_tematica->nome }}
                            </label>
                        </div>
                    @endforeach
                    @error('area_tematica_secundaria')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            {{-- Descrição --}}
            <div class="row justify-content-center mt-2">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="descricao">
                            {{ __('Descrição:') }}<span style="color:red; font-weight:bold;">*</span>
                        </label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror"
                                  id="descricao" name="descricao" rows="6" required>{{ old('descricao') }}</textarea>
                        @error('descricao')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ===================== DOCUMENTOS ===================== --}}
            <hr>
            <div class="row subtitulo">
                <div class="col-sm-12">
                    <p>Documentos</p>
                </div>
            </div>

            <div class="row justify-content-center" style="margin-top:10px">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="pdfEdital">
                            Anexar edital:<span style="color:red; font-weight:bold;">*</span>
                        </label>
                        @if(old('pdfEditalPreenchido') != null)
                            <a id="pdfEditalTemp" href="{{ route('baixar.evento.temp', ['nomeAnexo' => 'pdfEdital']) }}">Arquivo atual</a>
                        @endif
                        <input type="hidden" id="pdfEditalPreenchido" name="pdfEditalPreenchido" value="{{ old('pdfEditalPreenchido') }}">
                        <input type="file" accept=".pdf"
                               class="form-control-file pdf @error('pdfEdital') is-invalid @enderror"
                               name="pdfEdital" id="pdfEdital" onchange="exibirAnexoTemp(this)">
                        <small>O arquivo deve estar em formato PDF e ter até 2MB.</small>
                        @error('pdfEdital')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="modeloDocumento">
                            Anexar modelos de documentos:
                        </label>
                        @if(old('modeloDocumentoPreenchido') != null)
                            <a id="modeloDocumentoTemp" href="{{ route('baixar.modelo.evento.temp', ['nomeAnexo' => 'modeloDocumento']) }}">Arquivo atual</a>
                        @endif
                        <input type="hidden" id="modeloDocumentoPreenchido" name="modeloDocumentoPreenchido" value="{{ old('modeloDocumentoPreenchido') }}">
                        <input type="file" accept=".doc,.docx,.pdf,.zip"
                               class="form-control-file @error('modeloDocumento[]') is-invalid @enderror"
                               name="modeloDocumento[]" id="modeloDocumento" multiple onchange="exibirAnexoTemp(this)">
                        <small>Os arquivos devem ter até 2MB cada.</small>
                        @error('modeloDocumento[]')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ===================== AÇÕES ===================== --}}
            <div class="row justify-content-center" style="margin: 20px 0">
                <div class="col-md-6" style="padding-left:0">
                    <a class="btn btn-secondary botao-form" href="{{ route('admin.editais') }}" style="width:100%">Cancelar</a>
                </div>
                <div class="col-md-6" style="padding-right:0">
                    <button type="submit" class="btn btn-primary botao-form" style="width:100%">
                        {{ __('Criar Programa de Extensão') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===================== MODAL COORDENADOR ===================== --}}
    <div class="modal fade" id="modalCoord" tabindex="-1" role="dialog" aria-labelledby="modalCoordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCoordLabel" style="color:#1492E6">Coordenadores</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#1492E6">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Celular</th>
                            <th>Instituição</th>
                            <th>Seleção</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($coordenadors as $coordenador)
                            <tr>
                                <td>{{ $coordenador->user->name }}</td>
                                <td>{{ $coordenador->user->email }}</td>
                                <td>{{ $coordenador->user->celular ?? 'Não Definido' }}</td>
                                <td>{{ $coordenador->user->instituicao ?? 'Não Definida' }}</td>
                                <td style="text-align-last:center">
                                    <input type="button" class="btn btn-primary btn-sm" value="Definir"
                                           onclick="defCoord({{ $coordenador->id }}, '{{ $coordenador->user->name }}')"
                                           style="width:100px">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== MODAL VICE-COORDENADOR ===================== --}}
    <div class="modal fade" id="modalViceCoord" tabindex="-1" role="dialog" aria-labelledby="modalViceCoordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalViceCoordLabel" style="color:#1492E6">Vice-Coordenadores</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#1492E6">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Celular</th>
                            <th>Instituição</th>
                            <th>Seleção</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($coordenadors as $coordenador)
                            <tr>
                                <td>{{ $coordenador->user->name }}</td>
                                <td>{{ $coordenador->user->email }}</td>
                                <td>{{ $coordenador->user->celular ?? 'Não Definido' }}</td>
                                <td>{{ $coordenador->user->instituicao ?? 'Não Definida' }}</td>
                                <td style="text-align-last:center">
                                    <input type="button" class="btn btn-primary btn-sm" value="Definir"
                                           onclick="defViceCoord({{ $coordenador->id }}, '{{ $coordenador->user->name }}')"
                                           style="width:100px">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        function defCoord(id, name) {
            document.getElementById('coordenador_id').value = id;
            document.getElementById('coordenador_name').value = name;
            $('#modalCoord').modal('hide');
        }

        function defViceCoord(id, name) {
            document.getElementById('vice_coordenador_id').value = id;
            document.getElementById('vice_coordenador_name').value = name;
            $('#modalViceCoord').modal('hide');
        }

        function exibirAnexoTemp(file) {
            const map = {
                'pdfEdital':       'pdfEditalPreenchido',
                'modeloDocumento': 'modeloDocumentoPreenchido',
            };
            if (map[file.id]) {
                document.getElementById(map[file.id]).value = 'sim';
            }
        }
        function limitCheckbox(groupName, otherGroupName, clicked) {
            // enforce max 1 in own group
            document.querySelectorAll(`input[name="${groupName}[]"]`).forEach(function (checkbox) {
                if (checkbox !== clicked) checkbox.checked = false;
            });

            // if same value is checked in the other group, uncheck it there
            if (clicked.checked) {
                const mirror = document.querySelector(`input[name="${otherGroupName}[]"][value="${clicked.value}"]`);
                if (mirror) mirror.checked = false;
            }
        }

        $("input[type='file']").on('change', function () {
            if (this.files[0].size > 2000000) {
                alert('O tamanho do arquivo deve ser menor que 2MB!');
                $(this).val('');
            }
        });

        $('input.pdf').on('change', function () {
            const file = this.files[0];
            if (file.type.split('/')[1] !== 'pdf') {
                alert('O arquivo não é do tipo PDF!');
                $(this).val('');
            }
        });
    </script>
@endsection