<?php
namespace App\Services;

use App\Evento;
use App\ProgramaExtensao;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventoService
{
    public function list(Request $request)
    {
        $status = $request->get('status', 'aberto');
        $buscar = $request->get('buscar');
        $hoje = Carbon::today('America/Recife')->toDateString();

        $query = Evento::query();
        $queryProgramas = ProgramaExtensao::query();

        if ($buscar) {
            $query->where('nome', 'ilike', "%{$buscar}%");
            $queryProgramas->where('nome', 'ilike', "%{$buscar}%");
        }

        return [
            'eventos' => $query->orderBy('nome')->get(),
            'programas' => $queryProgramas->orderBy('nome')->get(),
            'hoje' => $hoje,
            'palavra' => $buscar ?? '',
            'flag' => $buscar ? 'true' : 'false',
            'status' => $status,
        ];
    }


}