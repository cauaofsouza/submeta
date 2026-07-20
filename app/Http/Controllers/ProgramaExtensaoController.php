<?php


namespace App\Http\Controllers;
use App\CoordenadorComissao;
use App\Evento;
use App\Http\Requests\StoreProgramaExtensaoRequest;
use App\Http\Requests\UpdateProgramaExtensaoRequest;
use App\ProgramaExtensao;
use App\Trabalho;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
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
        //pegar projetos e dados do programa

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
}