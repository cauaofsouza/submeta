<?php


namespace App\Http\Controllers;
use App\CoordenadorComissao;
use App\Evento;
use App\Http\Requests\StoreProgramaExtensaoRequest;
use App\Http\Requests\UpdateProgramaExtensaoRequest;
use App\Notifications\SolicitacaoVinculacaoProgramaNotification;
use App\Notifications\VinculacaoProgramaNotification;
use App\ProgramaExtensao;
use App\Proponente;
use App\Trabalho;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
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
            Storage::disk('public')->putFileAs($path, $request->file('pdf_edital'), 'edital.pdf');
            $programa->pdf_edital = $path . 'edital.pdf';
        }

        if ($request->hasFile('modelo_documento')) {
            $relativeDir = "modeloDocumento/{$programa->id}";
            $absoluteDir = Storage::disk('public')->path($relativeDir);

            if (!file_exists($absoluteDir)) {
                mkdir($absoluteDir, 0777, true);
            }

            $zipPath = "{$absoluteDir}/modelo.zip";

            $zip = new ZipArchive;
            $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            foreach ($request->file('modelo_documento') as $file) {
                $zip->addFile($file->getRealPath(), $file->getClientOriginalName());
            }
            $zip->close();

            $programa->modelo_documento = "{$relativeDir}/modelo.zip";
        }

        if ($request->hasFile('modelo_relatorio')) {
            $extension = $request->file('modelo_relatorio')->getClientOriginalExtension();
            $path = 'modeloRelatorio/' . $programa->id . '/';
            Storage::disk('public')->putFileAs($path, $request->file('modelo_relatorio'), 'relatorio.' . $extension);
            $programa->modelo_relatorio = $path . 'relatorio.' . $extension;
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
//        dd($programa);
        return view('evento.editarProgramaExtensao',[
            'programa' => $programa,
            'coordenadors' => $coordenadors]);
    }

    public function aceitarTrabalho($request, $trabalhoId)
    {
        $trabalho = Trabalho::findOrFail($trabalhoId);
        $trabalho->programa_extensao_status = 'aceito';
        $trabalho->save();

        $programa = ProgramaExtensao::findOrFail($trabalho->programa_de_extensao_id);

        Notification::send(
            $trabalho->proponente->user,
            new VinculacaoProgramaNotification($trabalho, $programa, 'aceito')
        );

        return redirect()
            ->back()
            ->with(['sucesso' => 'Proposta aceita com sucesso!']);
    }

    public function rejeitarTrabalho(Request $request, $trabalhoId)
    {
        $request->validate([
            'motivo_rejeicao' => ['required', 'string', 'max:1000'],
        ]);

        $trabalho = Trabalho::findOrFail($trabalhoId);
        $trabalho->programa_extensao_status = 'rejeitado';
        $trabalho->motivo_rejeicao = $request->motivo_rejeicao;
        $trabalho->save();

        $programa = ProgramaExtensao::findOrFail($trabalho->programa_de_extensao_id);

        Notification::send(
            $trabalho->proponente->user,
            new VinculacaoProgramaNotification($trabalho, $programa, 'rejeitado')
        );

        return redirect()
            ->back()
            ->with(['sucesso' => 'Proposta rejeitada com sucesso']);
    }


    public function Update(UpdateProgramaExtensaoRequest $request, ProgramaExtensao $programa)
    {
        $programa->update([
            'nome'                => $request->nome,
            'descricao'           => $request->descricao,
            'coordenador_id'      => $request->coordenador_id,
            'vice_coordenador_id' => $request->vice_coordenador_id,
            'vigencia_inicio'     => $request->vigencia_inicio,
            'vigencia_fim'        => $request->vigencia_fim,
        ]);

        $this->armazenarAnexos($request, $programa);

        return redirect()
            ->route('coordenador.editais')
            ->with(['mensagem' => 'Programa de Extensão atualizado com sucesso!']);
    }


    public function buscarProgramasExtensao(Request $request)
    {
        $programas = ProgramaExtensao::where('nome', 'ILIKE', '%' . $request->busca . '%')
            ->limit(3)
            ->get(['id', 'nome']);

        return response()->json($programas);
    }

    public function listar()//esta incluso nos metodos de EventoController que lidam com evento
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
        $successMessage = 'Solicitação de vincular proposta ao programa enviada com sucesso!';
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
            sleep(2);
            $programa->viceCoordenador->user->notify(
                (new SolicitacaoVinculacaoProgramaNotification($trabalho, $programa))->delay(now()->addSeconds(4))
            );
        }

        if($request->button){//caso a rota seja acessada por meio do ajax
            return response()->json([
                'mensagem' => $successMessage
            ], 200);
        } else {
            return redirect()->route('programa.visualizar', $programa->id)
                ->with(['mensagem' => $successMessage]);
        }

    }
}