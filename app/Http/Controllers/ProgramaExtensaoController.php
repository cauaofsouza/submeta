<?php


namespace App\Http\Controllers;
use App\CoordenadorComissao;
use App\Evento;
use App\Http\Requests\StoreProgramaExtensaoRequest;
use App\Http\Requests\UpdateProgramaExtensaoRequest;
use App\Notifications\SolicitacaoVinculacaoProgramaNotification;
use App\ProgramaExtensao;
use App\Proponente;
use App\Trabalho;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProgramaExtensaoController extends Controller
{

    public function create(){
        return view('evento.criarProgramaExtensao', [
            'coordenadors' => CoordenadorComissao::first()->with('user')->get(),
        ]);
    }

    public function store(StoreProgramaExtensaoRequest $request)
    {

        $programa = ProgramaExtensao::create([
            'nome'                        => $request->nome,
            'descricao'                   => $request->descricao,
            'coordenador_id'              => $request->coordenador_id,
            'vice_coordenador_id'         => $request->vice_coordenador_id,
            'vigencia_inicio'             => $request->vigencia_inicio,
            'vigencia_fim'                => $request->vigencia_fim,
            'criador_id'                  => auth()->id(),
        ]);


        $this->armazenarAnexos($request, $programa);
        return redirect()
            ->route('coordenador.editais')
            ->with(['mensagem' => 'Programa de Extensão criado com sucesso!']);
    }

    private function armazenarAnexos(FormRequest $request, ProgramaExtensao $programa)
    {
        if ($request->hasFile('pdf_edital')) {
            $path = 'pdfEdital/' . $programa->id . '/';
            Storage::putFileAs($path, $request->file('pdf_edital'), 'edital.pdf');
            $programa->pdf_edital = $path . 'edital.pdf';
        }

        if ($request->hasFile('modelo_documento')) {
            $dir      = "storage/app/modeloDocumento/{$programa->id}";
            $filename = "{$dir}/modelo.zip";

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $zip = new ZipArchive;
            $zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            foreach ($request->file('modelo_documento') as $file) {
                $zip->addFile($file->getRealPath(), $file->getClientOriginalName());
            }
            $zip->close();

            $programa->modelo_documento = $filename;
        }

        $programa->save();
    }

    public function show($id)
    {
        $programa = ProgramaExtensao::with(['coordenador.user', 'viceCoordenador.user'])
            ->findOrFail($id);

        $proponente = Auth::check()
            ? Proponente::where('user_id', Auth::id())->first()
            : null;

        $isCoordenadorOuVice = false;
        $trabalhos = collect();
        $trabalhosDisponiveis = collect();


        if ($proponente) {
            $trabalhosDisponiveis = Trabalho::where('proponente_id', $proponente->id)
                ->where('aprovado', '=', '1')
                ->whereHas('evento', function ($query) {
                    $query->whereHas('natureza', function ($query) {
                        $query->whereRaw("nome ~* 'extens(a|ã)o'");
                    });
                })
                ->where(function ($query) {
                    $query->whereNull('programa_de_extensao_id')
                        ->orWhere('programa_extensao_status', 'reprovado');
                })
                ->orderByDesc('updated_at')
                ->get();
        } elseif(Auth::check()){
            $userId = Auth::id();
            if (($programa->coordenador && $programa->coordenador->user_id == $userId) ||
                ($programa->viceCoordenador && $programa->viceCoordenador->user_id == $userId)) {
                $isCoordenadorOuVice = true;
                $trabalhos = $programa->trabalhos()->orderBy('titulo')->get();
            }
        }

        $hoje = Carbon::today('America/Recife')->toDateString();

        return view('evento.visualizarProgramaExtensao', [
            'programa'            => $programa,
            'proponente'          => $proponente,
            'isCoordenadorOuVice' => $isCoordenadorOuVice,
            'trabalhos'           => $trabalhos,
            'trabalhosDisponiveis' => $trabalhosDisponiveis,
            'hoje'                => $hoje,
        ]);
    }

    public function edit($id)
    {
        $programa = ProgramaExtensao::find($id);
        $coordenadors = CoordenadorComissao::with('user')->get();
        $trabalhos = Trabalho::where('programa_de_extensao_id', '=', $id)->get();
        return view('evento.editarProgramaExtensao',[
            'programa' => $programa,
            'trabalhos' => $trabalhos,
            'coordenadors' => $coordenadors]);
    }

    public function aceitarTrabalho($id)
    {
        $trabalho = Trabalho::findOrFail($id);
        $trabalho->programa_extensao_status = 'aceito';
        $trabalho->save();

        return redirect()
            ->back()
            ->with(['sucesso' => 'Proposta aceita com sucesso!']);
    }

    public function rejeitarTrabalho($id)
    {
        $trabalho = Trabalho::findOrFail($id);
        $trabalho->programa_extensao_status = 'rejeitado';
        $trabalho->save();

        return redirect()
            ->back()
            ->with(['sucesso' => 'Proposta rejeitada com sucesso!']);
    }


    public function Update(UpdateProgramaExtensaoRequest $request, ProgramaExtensao $programaExtensao)
    {
        $programaExtensao->update([
            'nome'                => $request->nome,
            'descricao'           => $request->descricao,
            'coordenador_id'      => $request->coordenador_id,
            'vice_coordenador_id' => $request->vice_coordenador_id,
            'vigencia_inicio'     => $request->vigencia_inicio,
            'vigencia_fim'        => $request->vigencia_fim,
        ]);

        $this->armazenarAnexos($request, $programaExtensao);

        return redirect()
            ->route('coordenador.editais')
            ->with(['mensagem' => 'Programa de Extensão atualizado com sucesso!']);
    }


    public function buscarProgramasExtensao(Request $request)
    {
        $programas = Evento::where('tipo', 'PROGRAMA_EXTENSAO')//CRIAR UM PARA TESTE
        ->where('titulo', 'ILIKE', '%' . $request->busca . '%')
            ->limit(3)
            ->get(['id', 'titulo']);

        return response()->json($programas);
    }

    public function listar()
    {

    }

    public function destroy($id)
    {
        $programa = ProgramaExtensao::findOrFail($id);

        if ($programa->pdf_edital && Storage::exists($programa->pdf_edital)) {
            Storage::delete($programa->pdf_edital);
        }

        if ($programa->modelo_documento && file_exists($programa->modelo_documento)) {
            unlink($programa->modelo_documento);
        }

        $programa->delete();

        return redirect()
            ->route('coordenador.editais')
            ->with(['mensagem' => 'Programa de Extensão deletado com sucesso!']);
    }

    public function solicitarVinculoPrograma(Request $request, $id)
    {
        $request->validate([
            'trabalho_id' => ['required', 'exists:trabalhos,id'],
        ]);

        $programa = ProgramaExtensao::findOrFail($id);
        $proponente = Proponente::where('user_id', Auth::id())->firstOrFail();

        $trabalho = Trabalho::where('id', $request->trabalho_id)
            ->where('proponente_id', $proponente->id)
            ->whereNull('programa_de_extensao_id')
            ->firstOrFail();

        $trabalho->programa_extensao_status = 'pendente';
        $trabalho->programa_de_extensao_id = $programa->id;
        $trabalho->save();

        if ($programa->coordenador && $programa->coordenador->user) {
            $programa->coordenador->user->notify(
                (new SolicitacaoVinculacaoProgramaNotification($trabalho, $programa))->delay(now()->addSeconds(2))
            );
        }

        if ($programa->viceCoordenador && $programa->viceCoordenador->user) {
            $programa->viceCoordenador->user->notify(
                (new SolicitacaoVinculacaoProgramaNotification($trabalho, $programa))->delay(now()->addSeconds(4))
            );
        }

        return redirect()->route('programa.visualizar', $programa->id)
            ->with(['mensagem' => 'Solicitação de vincular proposta ao programa enviada com sucesso!']);
    }
}