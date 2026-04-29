<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Support\Collection;

class RankingTrabalhoExport implements FromCollection, WithHeadings, WithColumnWidths
{
    protected $ranking;

    public function __construct(array $ranking)
    {
        $this->ranking = $ranking;
    }

    /*
     * cria de fato a sheet
     * */
    public function collection()
    {
        return new Collection(array_map(function ($item, $index) {
            return [
                'posicao'     => $index + 1,
                'titulo'      => $item['titulo'],
                'media_geral' => number_format($item['media_geral'], 2, '.', ''),
            ];
        }, $this->ranking, array_keys($this->ranking)));
    }

    /*
     * define cabeçalhos para a sheet
     * */
    public function headings(): array
    {
        return ['Posição', 'Título do Trabalho', 'Média Geral'];
    }

    /*
     * deixa todas as celulas com a mesma largura
     * */
    public function columnWidths(): array
    {
        $maxTitulo = collect($this->ranking)->max(function ($item) {
            return strlen($item['titulo']);
        });

        return [
            'A' => 10,                            // Posição
            'B' => max(20, $maxTitulo * 1.1),     // Título — based on longest value
            'C' => 15,                            // Média Geral
        ];
    }

    /*
     * para todas as celulas estarem alinhadas à esquerda
     * */
    public function styles(Worksheet $sheet)
    {
        $sheet->getDefaultStyle()->getAlignment()->setHorizontal('left');
    }
}