<?php

namespace SimuladoENADE\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimuladoENADE\Validator\QuestaoValidator;
use SimuladoENADE\Validator\ValidationException;

class QuestaoController extends Controller {
	public function adicionar(Request $request){
		try{
			QuestaoValidator::Validate($request->all()); 
			
			$alternativas = $request->input('alternativa');

			$questao = new \SimuladoENADE\Questao();
			$questao->enunciado = $request->input('enunciado');
			$questao->alternativa_correta = $request->input('alternativa_correta');
			$questao->dificuldade = $request->input('dificuldade');
			$questao->disciplina_id = $request->input('disciplina_id');

			$questao->alternativa_a = $alternativas[0];
			$questao->alternativa_b = $alternativas[1];
			$questao->alternativa_c = $alternativas[2];
			$questao->alternativa_d = $alternativas[3];
			$questao->alternativa_e = $alternativas[4];

			$questao->save();

			return redirect("listar/questao");

		} catch(ValidationException $ex){
			return redirect("cadastrar/questao")->withErrors($ex->getValidator())->withInput();
		}
	}

	public function cadastrar(){
		$cursos = \SimuladoENADE\Curso::all();
		#dd($cursos);
		$disciplinas = \SimuladoENADE\Disciplina::where('curso_id', '=', \Auth::user()->curso_id)->get(); 
		return view('/QuestaoView/cadastrarQuestao', ['disciplinas' => $disciplinas, 'cursos' => $cursos]); 
	}
	
	public function listar(){
		$curso_id = \Auth::user()->curso_id;

		$questaos =\SimuladoENADE\Questao::select('*', \DB::raw('questaos.id as qstid'))
			->join('disciplinas', 'questaos.disciplina_id', '=', 'disciplinas.id')
			->where('curso_id', '=', \Auth::user()->curso_id)
			->orderBy('nome')
			->orderBy('dificuldade')
			->get();

		$disciplinas = \SimuladoENADE\Disciplina::where('curso_id', '=', \Auth::user()->curso_id)->get();

		return view('/QuestaoView/listaQuestao', ['questaos' => $questaos, 'disciplinas' => $disciplinas]);
	}

	public function listarQstDisciplina(Request $request){
		$curso_id = \Auth::user()->curso_id;

		$questaos =\SimuladoENADE\Questao::select('*', \DB::raw('questaos.id as qstid'))
			->join('disciplinas', 'questaos.disciplina_id', '=', 'disciplinas.id')
			->where('disciplina_id', '=', $request->id)
			->orderBy('nome')
			->orderBy('dificuldade')
			->get();

		$disciplinas = \SimuladoENADE\Disciplina::where('curso_id', '=', \Auth::user()->curso_id)->get();

		return view('/QuestaoView/listaQuestao', ['questaos' => $questaos, 'disciplinas' => $disciplinas]);
	}

	public function editar(Request $request){
		$questao = \SimuladoENADE\Questao::find($request->id);
		$disciplinas = \SimuladoENADE\Disciplina::where('curso_id', '=', \Auth::user()->curso_id)->get();

		\Session::put('url.intended', \URL::previous());  // using the Facade
		session()->put('url.intended', \URL::previous()); // using the L5 helper

		return view('/QuestaoView/editarQuestaos', ['questao' => $questao], ['disciplinas'=>$disciplinas]);
	}

	public function atualizar(Request $request){
		try{
			QuestaoValidator::Validate($request->all()); 
			
			$alternativas = $request->input('alternativa');

			$questao = \SimuladoENADE\Questao::find($request->id);
			$questao->enunciado = $request->input('enunciado');
			$questao->alternativa_correta = $request->input('alternativa_correta');
			$questao->dificuldade = $request->input('dificuldade');
			$questao->disciplina_id = $request->input('disciplina_id');

			$questao->alternativa_a = $alternativas[0];
			$questao->alternativa_b = $alternativas[1];
			$questao->alternativa_c = $alternativas[2];
			$questao->alternativa_d = $alternativas[3];
			$questao->alternativa_e = $alternativas[4];

			$questao->update();

			return \Redirect::intended('/');

		}catch(ValidationException $ex){
			return redirect("editar/questao")->withErrors($ex->getValidator())->withInput();
		}
	}

	public function remover(Request $request){
		$questao = \SimuladoENADE\Questao::find($request->id);
		$questao->delete();
		return redirect('\listar\questao');
	}


	public function importarQuestao(Request $request){
		#$disciplinas = \SimuladoENADE\Disciplina::all();
		#
		$cursos = \SimuladoENADE\Curso::all();
		$disciplinas = \SimuladoENADE\Disciplina::all();

		return view('/QuestaoView/importarQuestao', ['disciplinas' => $disciplinas], ['cursos' => $cursos]);
	}
}

		#$questaos =\SimuladoENADE\Questao::select('*', \DB::raw('questaos.id as qstid'))
		#	->join('disciplinas', 'questaos.disciplina_id', '=', 'disciplinas.id')