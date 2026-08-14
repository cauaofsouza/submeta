<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class UpdateProgramaExtensaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $programa = $this->route('programa');

        return [
            'nome'                => ['required', 'string', 'max:255'],
            'descricao'           => ['required', 'string'],

            'coordenador_id'      => ['required', 'exists:coordenador_comissaos,id'],
            'vice_coordenador_id' => ['nullable', 'exists:coordenador_comissaos,id'],

            'vigencia_inicio'     => ['required', 'date'],
            'vigencia_fim' => [
                'required',
                'date',
                'after:vigencia_inicio',
                function ($attribute, $value, $fail) {
                    $inicio = Carbon::parse($this->vigencia_inicio);
                    $limite = $inicio->copy()->addYears(5);

                    if (Carbon::parse($value)->greaterThan($limite)) {
                        $fail('A vigência não pode ultrapassar 5 anos a partir da data de início.');
                    }
                },
            ],

            'pdf_edital'          => [
                (!$programa->pdf_edital && $this->pdfEditalPreenchido !== 'sim') ? 'required' : 'nullable',
                'file', 'mimes:pdf', 'max:2048',
            ],
            'modelo_documento.*'  => ['nullable', 'file', 'max:2048'],
            'modelo_relatorio.*'  => ['nullable', 'file', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'              => 'O título do programa é obrigatório.',
            'descricao.required'         => 'A descrição é obrigatória.',
            'coordenador_id.required'    => 'Selecione um coordenador.',
            'coordenador_id.exists'      => 'Coordenador inválido.',
            'vice_coordenador_id.exists' => 'Vice-coordenador inválido.',
            'vigencia_inicio.required'   => 'Informe o início da vigência.',
            'vigencia_fim.required'      => 'Informe o fim da vigência.',
            'vigencia_fim.after'         => 'O fim da vigência deve ser posterior ao início.',
            'pdf_edital.mimes'           => 'O edital deve estar no formato PDF.',
            'pdf_edital.max'             => 'O edital deve ter no máximo 2MB.',
            'modelo_documento.*.max'     => 'Cada modelo de documento deve ter no máximo 2MB.',
            'modelo_relatorio.*.max'     => 'Cada modelo de relatório parcial anual deve ter no máximo 2MB.',
        ];
    }
}