<?php

namespace App;

use App\AreaTematica;
use App\CoordenadorComissao;
use App\Trabalho;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ProgramaExtensao extends Model
{
    protected $table = 'programa_extensaos';

    protected $fillable = [
        'nome',
        'descricao',
        'coordenador_id',
        'vice_coordenador_id',
        'vigencia_inicio',
        'vigencia_fim',
        'pdf_edital',
        'modelo_documento',
        'criador_id',
    ];

    protected $casts = [
        'vigencia_inicio' => 'date',
        'vigencia_fim'    => 'date',
    ];

    public function coordenador()
    {
        return $this->belongsTo(CoordenadorComissao::class, 'coordenador_id');
    }

    public function viceCoordenador()
    {
        return $this->belongsTo(CoordenadorComissao::class, 'vice_coordenador_id');
    }

    public function criador()
    {
        return $this->belongsTo(User::class, 'criador_id');
    }
    public function trabalhos()
    {
        return $this->hasMany(Trabalho::class, 'programa_de_extensao_id');
    }
}