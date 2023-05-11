<?php
use Illuminate\Http\Request;
use App\Curso;
use App\Usuario;
use App\Disciplina;
use App\Questao;
use App\Aluno;
use App\Turma;
use App\Simulado;
use App\Instituicao;
use App\UnidadeAcademica;
use App\Http\Middleware\AdministradorMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
   |--------------------------------------------------------------------------
   | Web Routes
   |--------------------------------------------------------------------------
   |
   | Here is where you can register web routes for your application. These
   | routes are loaded by the RouteServiceProvider within a group which
   | contains the "web" middleware group. Now create something great!
   |
 */

Route::get('/sobre', 'UsuarioController@sobre')->name('sobre');
Route::get('/contato', 'UsuarioController@contato')->name('contato');
Route::get('/redefinicao', 'UsuarioController@redefinicao')->name('redefinicao.senhas');
Route::post('/cookie', 'UsuarioController@cookie')->name('cookie.resetPassword');


Route::middleware('auth')->group(function () {

    Route::get('/', 'UsuarioController@home')->name('home');
    Route::get('/home', 'UsuarioController@home');

    Route::get('/listar/simulado', 'SimuladoController@listar')->name('list_simulado');

    Route::middleware('coordenador.auth')->group(function () {

        Route::get('/listar/simulados-expirados', 'SimuladoController@listarExpirados')->name('list_simulado_expirados');
        Route::get('/listar/simulado/{id}/questoes', 'QuestaoSimuladoController@listarQuestoeSimulado')->name('questoes_simulado');

        Route::get('/listar/disciplina', 'DisciplinaController@listar')->name('list_disciplina');
        Route::get('/cadastrar/disciplina', 'DisciplinaController@cadastrar')->name('new_disciplina');
        Route::post('/adicionar/disciplina', 'DisciplinaController@adicionar')->name('add_disciplina');
        Route::get('/editar/disciplina/{id}', 'DisciplinaController@editar')->name('edit_disciplina');
        Route::post('/atualizar/disciplina', 'DisciplinaController@atualizar')->name('update_disciplina');
        Route::get('/remover/disciplina/{id}', 'DisciplinaController@remover')->name('delete_disciplina');

        Route::get('/listar/aluno', 'AlunoController@listar')->name('list_aluno');
        Route::get('/cadastrar/aluno', 'AlunoController@cadastrar')->name('new_aluno');
        Route::post('/adicionar/aluno', 'AlunoController@adicionar')->name('add_aluno');
        Route::get('/editar/aluno/{id}', 'AlunoController@editar')->name('edit_aluno');
        Route::get('/remover/aluno/{id}', 'AlunoController@remover')->name('delete_aluno');
        Route::post('/importa/aluno/', 'AlunoController@importaArquivo')->name('import_aluno');

        Route::get('/listar/professor', 'UsuarioController@listar')->name('list_professor');
        Route::get('/cadastrar/professor', 'UsuarioController@cadastrar')->name('new_professor');
        Route::post('/adicionar/professor', 'UsuarioController@adicionar')->name('add_professor');
        Route::get('/editar/professor/{id}', 'UsuarioController@editar')->name('edit_professor');
        Route::post('/atualizar/professor', 'UsuarioController@atualizar')->name('update_professor');
        Route::get('/remover/professor/{id}', 'UsuarioController@remover')->name('delete_professor');

        Route::get('/cadastrar/simulado', 'SimuladoController@cadastrar')->name('new_simulado');
        Route::post('/adicionar/simulado', 'SimuladoController@adicionar')->name('add_simulado');
        Route::get('/editar/simulado/{id}', 'SimuladoController@editar')->name('edit_simulado');
        Route::post('/atualizar/simulado', 'SimuladoController@atualizar')->name('update_simulado');
        Route::get('/remover/simulado/{id}', 'SimuladoController@remover')->name('delete_simulado');

        Route::get('/montarSimulado/{id}', 'QuestaoSimuladoController@montar')->name('set_simulado');
        Route::get('/cadastrarQuestaoSimulado/', 'QuestaoSimuladoController@cadastrarQuestao')->name('add_qst_simulado');
        Route::get('/cadastrarQuestaoSimuladoManual/', 'QuestaoSimuladoController@cadastrarQuestaoManualmente')->name('add_qst_simulado_obj');

        Route::get('/cadastrarQuestaoDiscursivaSimulado/', 'QuestaoDiscursivaSimuladoController@cadastrarQuestao')->name('add_qst_disc_simulado_auto');
        Route::get('/cadastrarQuestaoDiscursivaSimuladoManual/', 'QuestaoDiscursivaSimuladoController@cadastrarQuestaoManualmente')->name('add_qst_disc_simulado_manual');

        // Chamadas Assicronas - Jquery
        Route::get('/addQuestaoSimulado/Async', 'QuestaoSimuladoController@addQuestaoAsync')->name('add_questao_objetiva_async');
        Route::get('/removeQuestaoSimulado/Async', 'QuestaoSimuladoController@removeQuestaoAsync')->name('remove_questao_objetiva_async');

        Route::get('/addQuestaoDiscursivaSimulado/Async', 'QuestaoDiscursivaSimuladoController@addQuestaoAsync')->name('add_questao_discursiva_async');
        Route::get('/removeQuestaoDiscursivaSimulado/Async', 'QuestaoDiscursivaSimuladoController@removeQuestaoAsync')->name('remove_questao_discursiva_async');
        //

        Route::get('/removerQuestaoSimulado/{sim_qst_id}', 'QuestaoSimuladoController@removerQuestao')->name('remove_qst_simulado');

        Route::get('/relatorio/QstDis', 'RelatorioController@questoesPorDisciplina')->name('qst_por_disciplina');
        Route::get('/relatorio/DesempenhoAlunos', 'RelatorioController@desempenhoAlunos')->name('desempenho_alunos');
        Route::get('/relatorio/relatorioSimulados', 'RelatorioController@relatorioSimulados')->name('relatorio_simulados');
        Route::get('/relatorio/relatorioDisciplina', 'RelatorioController@relatorioDisciplina')->name('relatorio_disciplinas');

        Route::get('/downloadModelCSV', 'AlunoController@downloadModeloCSV')->name('csv_model_download');
    });

    Route::middleware('coordenadorGeral.auth')->group(function () {

        // Route::get('/listar/coordenacaoGeral', 'UsuarioController@listar')->name('list_professor');
        Route::get('/cadastrar/coordenacaoGeral', 'UsuarioController@cadastrar')->name('new_coordenacaoGeral');
        Route::post('/adicionar/coordenacaoGeral', 'UsuarioController@adicionar')->name('add_coordenacaoGeral');
        Route::get('/editar/coordenacaoGeral/{id}', 'UsuarioController@editar')->name('edit_coordenacaoGeral');
        Route::post('/atualizar/coordenacaoGeral', 'UsuarioController@atualizar')->name('update_coordenacaoGeral');
        Route::get('/remover/coordenacaoGeral/{id}', 'UsuarioController@remover')->name('delete_coordenacaoGeral');
        Route::get('/relatorio/cursosCG', 'RelatorioController@relatorioGeralCursos')->name('geral_cursosCG');

    });

    Route::group(['middleware' => ['professor.auth' or 'coordenador.auth' or 'adm.auth' or 'coordenadorGeral.auth']], function () {
        Route::post('/atualizar/professor', 'UsuarioController@atualizar')->name('update_professor');
        Route::get('/editar/usuario/{id}', 'UsuarioController@editar')->name('edit_usuario');
        Route::post('/alterarSenha/', 'UsuarioController@editarSenha')->name('alterar_senha');

    });

    Route::group(['middleware' => ['professor.auth' or 'coordenador.auth']], function () {

        Route::get('/listar/questao', 'QuestaoController@listar')->name('list_qst');
        Route::get('/listar/questoes/disciplina/{id}', 'QuestaoController@listarQstDisciplina')->name('list_qst_disciplina');
        Route::get('/cadastrar/questao', 'QuestaoController@cadastrar')->name('new_qst');
        Route::post('/adicionar/questao', 'QuestaoController@adicionar')->name('add_qst');
        Route::get('/editar/questao/{id}', 'QuestaoController@editar')->name('edit_qst');
        Route::post('/atualizar/questao', 'QuestaoController@atualizar')->name('update_qst');
        Route::get('/remover/questao/{id}', 'QuestaoController@remover')->name('delete_qst');
        Route::get('/importarQuestao/', 'QuestaoController@importarQuestao')->name('import_qst');
        Route::post('/importarQuestao/listando', 'QuestaoController@importarQuestao')->name('listar_import_qst');
        Route::post('/importarQuestao/importando', 'QuestaoController@importandoQuestoes')->name('import_qst_post');

        Route::get('/editar/questaoDiscursiva/{id}', 'QuestaoDiscursivaController@editar')->name('edit_qst_disc');
        Route::post('/adicionar/questaoDiscursiva', 'QuestaoDiscursivaController@adicionar')->name('add_qst_disc');
        Route::get('/remover/questaoDiscursiva/{id}', 'QuestaoDiscursivaController@remover')->name('delete_qst_disc');
        Route::post('/atualizar/questaoDiscursiva', 'QuestaoDiscursivaController@atualizar')->name('update_qst_disc');
        Route::get('/listar/questoesDiscursivas/disciplina/{id}', 'QuestaoDiscursivaController@listarQstDisciplina')->name('list_qst_disc_disciplina');

        Route::get('/importarQuestaoDiscursiva/', 'QuestaoDiscursivaController@importarQuestao')->name('import_qst_disc');
        Route::post('/importarQuestaoDiscursiva/listando', 'QuestaoDiscursivaController@importarQuestao')->name('listar_import_qst_disc');
        Route::post('/importarQuestaoDiscursiva/importando', 'QuestaoDiscursivaController@importandoQuestoes')->name('import_qst_disc_post');


        Route::get('listar/questoesDiscursivas/simulados', 'QuestaoDiscursivaController@listarSimuladosRespostasDiscursivas')->name('listar_simulados_questoes_discursivas');
        Route::get('listar/questoesDiscursivas/simulados/{simulado_id}', 'QuestaoDiscursivaController@litarRespostasSimulados')->name('ver_respostas_discursivas_simulado');
        Route::get('listar/questoesDiscursivas/simulados/{simulado_id}/resposta/{resposta_id}', 'QuestaoDiscursivaController@avaliarRespostaSimulados')->name('avaliar_resposta_discursivas');
        Route::post('salvar_avaliacao_resposta_discursiva', 'QuestaoDiscursivaController@avaliarRespostaDiscursiva')->name('salvar_avaliacao_resposta_discursiva');


    });

    // Route::middleware('adm.auth')->group(function(){

    // 	Route::get('/listar/curso','CursoController@listar')->name('list_curso');
    // 	Route::get('/cadastrar/curso', 'CursoController@cadastrar')->name('new_curso');
    // 	Route::post('/adicionar/curso','CursoController@adicionar')->name('add_curso');
    // 	Route::get('/editar/curso/{id}', 'CursoController@editar')->name('edit_curso');
    // 	Route::post('/atualizar/curso','CursoController@atualizar')->name('update_curso');
    // 	Route::get('/remover/curso/{id}', 'CursoController@remover')->name('delete_curso');

    // 	Route::get('/listar/usuario', 'UsuarioController@listar')->name('list_usuario');
    // 	Route::get('/cadastrar/usuario', 'UsuarioController@cadastrar')->name('new_usuario');
    // 	Route::post('/adicionar/usuario', 'UsuarioController@adicionar')->name('add_usuario');
    // 	Route::post('/atualizar/usuario', 'UsuarioController@atualizar')->name('update_usuario');
    // 	Route::get('/remover/usuario/{id}', 'UsuarioController@remover')->name('delete_usuario');

    // 	Route::get('/listar/ciclo', 'CicloController@listar')->name('list_ciclo');
    // 	Route::get('/cadastrar/ciclo', 'CicloController@cadastrar')->name('new_ciclo');
    // 	Route::post('/adicionar/ciclo', 'CicloController@adicionar')->name('add_ciclo');
    // 	Route::get('/editar/ciclo/{id}', 'CicloController@editar')->name('edit_ciclo');
    // 	Route::post('/atualizar/ciclo', 'CicloController@atualizar')->name('update_ciclo');
    // 	Route::get('/remover/ciclo/{id}', 'CicloController@remover')->name('delete_ciclo');

    // 	Route::get('/relatorio/cursos', 'RelatorioController@relatorioGeralCursos')->name('geral_cursos');

    // });

});

Route::group(['middleware' => ['aluno.auth' or 'coordenador.auth']], function () {

    Route::post('/atualizar/aluno', 'AlunoController@atualizar')->name('update_aluno');

});

Route::middleware('aluno.auth')->group(function () {

    Route::get('/alunohome', 'AlunoController@home')->name('home_aluno');

    Route::get('/listaSimuladoAluno/simulado', 'SimuladoController@listaSimuladoAluno')->name('list_simulado_aluno');
    Route::get('/listaSimuladoAluno/simuladoFeitos', 'SimuladoController@listaSimuladoAlunoFeitos')->name('list_simulado_feitos');

    Route::get('/questao/simulado/{id}', 'SimuladoController@questao')->name('qst_simulado');
    Route::post('/questao/simulado/voltar', 'SimuladoController@voltar_questao')->name('voltar_qst_simulado');
    Route::post('/questao/simulado/voltar/salvar', 'SimuladoController@salvar_voltar_questao')->name('salvar_voltar_qst_simulado');


    Route::post('/responder_discursiva/simulado/', 'SimuladoController@responder_discursiva')->name('answ_qst_discursiva_simulado');
    Route::post('/questao_disc/simulado/voltar', 'SimuladoController@voltar_questao_discursiva')->name('voltar_qst_discursiva_simulado');
    Route::post('/questao_disc/simulado/voltar/salvar', 'SimuladoController@salvar_voltar_questao_discursiva')->name('salvar_voltar_qst_discursiva_simulado');

    Route::post('/responder/simulado/', 'SimuladoController@responder')->name('answ_qst_simulado');
    Route::get('/resultado/simulado/{id}', 'SimuladoController@resultado')->name('result_simulado');
    Route::get('/startSimulado/{id}', 'SimuladoController@startSimulado')->name('startSimulado');

    Route::get('/editar/respostasSimulado/{id}', 'SimuladoController@editarRespostasSimulado')->name('list_edit_answ');
    Route::get('/editar/resposta/{id}', 'SimuladoController@editarResposta')->name('edit_answ');
    Route::post('/editar/atualizarRespostaSimulado/', 'SimuladoController@updateRespostaSimulado')->name('update_answ');

    Route::get('/editarPerfil', 'AlunoController@editarPerfil')->name('edit_perfil_aluno');
    Route::post('/alterarSenhaAluno', 'AlunoController@editarSenha')->name('alterar_senha_aluno');

	Route::get('/mudarSenhaAluno', 'AlunoController@mudarSenhaView')->name('mudar_senha_view');
	Route::post('/mudarSenha', 'AlunoController@mudarSenha')->name('mudar_senha');

	Route::get('/alunoCadastrado', 'AlunoController@alunoCadastrado')->name('aluno_cadastrado');
});

Route::middleware('admGeral.auth')->group(function () {

    Route::get('/listar/instituicao', 'InstituicaoController@listar')->name('list_instituicao');
    Route::get('/cadastrar/instituicao', 'InstituicaoController@cadastrar')->name('new_instituicao');
    Route::post('/adicionar/instituicao', 'InstituicaoController@adicionar')->name('add_instituicao');
    Route::get('/editar/instituicao/{id}', 'InstituicaoController@editar')->name('edit_instituicao');
    Route::post('/atualizar/instituicao', 'InstituicaoController@atualizar')->name('update_instituicao');
    Route::get('/remover/instituicao/{id}', 'InstituicaoController@remover')->name('delete_instituicao');

});

Route::middleware('instituicao.auth')->group(function () {

    Route::get('/instituicaohome', 'InstituicaoController@home')->name('home_instituicao');
    Route::post('/atualizar/instituicao', 'InstituicaoController@atualizar')->name('update_instituicao');
    Route::get('/editarPerfilInstituicao', 'InstituicaoController@editarPerfil')->name('edit_perfil_instituicao');
    Route::post('/alterarSenhaInstituicao', 'InstituicaoController@editarSenha')->name('alterar_senha_instituicao');

    Route::get('/listar/curso', 'CursoController@listar')->name('list_curso');
    Route::get('/cadastrar/curso', 'CursoController@cadastrar')->name('new_curso');
    Route::post('/adicionar/curso', 'CursoController@adicionar')->name('add_curso');
    Route::get('/editar/curso/{id}', 'CursoController@editar')->name('edit_curso');
    Route::post('/atualizar/curso', 'CursoController@atualizar')->name('update_curso');
    Route::get('/remover/curso/{id}', 'CursoController@remover')->name('delete_curso');

    Route::get('/listar/usuario', 'UsuarioController@listar')->name('list_usuario');
    Route::get('/cadastrar/usuario', 'UsuarioController@cadastrar')->name('new_usuario');
    Route::post('/adicionar/usuario', 'UsuarioController@adicionar')->name('add_usuario');
    Route::get('/editar/usuario/{id}', 'UsuarioController@editar')->name('edit_usuario');
    Route::post('/atualizar/usuario', 'UsuarioController@atualizar')->name('update_usuario');
    Route::get('/remover/usuario/{id}', 'UsuarioController@remover')->name('delete_usuario');

    Route::get('/listar/unidade', 'UnidadeAcademicaController@listar')->name('list_unidade');
    Route::get('/cadastrar/unidade', 'UnidadeAcademicaController@cadastrar')->name('new_unidade');
    Route::post('/adicionar/unidade', 'UnidadeAcademicaController@adicionar')->name('add_unidade');
    Route::get('/editar/unidade/{id}', 'UnidadeAcademicaController@editar')->name('edit_unidade');
    Route::post('/atualizar/unidade', 'UnidadeAcademicaController@atualizar')->name('update_unidade');
    Route::get('/remover/unidade/{id}', 'UnidadeAcademicaController@remover')->name('delete_unidade');

    Route::get('/listar/ciclo', 'CicloController@listar')->name('list_ciclo');
    Route::get('/cadastrar/ciclo', 'CicloController@cadastrar')->name('new_ciclo');
    Route::post('/adicionar/ciclo', 'CicloController@adicionar')->name('add_ciclo');
    Route::get('/editar/ciclo/{id}', 'CicloController@editar')->name('edit_ciclo');
    Route::post('/atualizar/ciclo', 'CicloController@atualizar')->name('update_ciclo');
    Route::get('/remover/ciclo/{id}', 'CicloController@remover')->name('delete_ciclo');

    Route::get('/relatorio/cursos', 'RelatorioController@relatorioGeralCursos')->name('geral_cursos');
});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/listar/turma','TurmaController@listar')->name('list_turma');
// Route::get('/cadastrar/turma','TurmaController@cadastrar')->name('new_turma');
// Route::post('/adicionar/turma','TurmaController@adicionar')->name('add_turma');
// Route::get('/editar/turma/{id}','TurmaController@editar')->name('edit_turma');
// Route::post('/atualizar/turma','TurmaController@atualizar')->name('update_turma');
// Route::get('/remover/turma/{id}','TurmaController@remover')->name('delete_turma');

// Route::get('/listar/resposta','RespostaController@listar')->name('list_resposta');
// Route::get('/cadastrar/resposta','RespostaController@cadastrar')->name('new_resposta');
// Route::post('/adicionar/resposta','RespostaController@adicionar')->name('add_resposta');
// Route::get('/editar/resposta/{id}','RespostaController@editar')->name('edit_resposta');
// Route::post('/atualizar/resposta','RespostaController@atualizar')->name('update_resposta');
// Route::get('/remover/resposta/{id}','RespostaController@remover')->name('delete_resposta');
    // Auth::routes();

    // Route::get('/home', 'HomeController@index')->name('home');

    // Route::get('/listar/turma','TurmaController@listar')->name('list_turma');
    // Route::get('/cadastrar/turma','TurmaController@cadastrar')->name('new_turma');
    // Route::post('/adicionar/turma','TurmaController@adicionar')->name('add_turma');
    // Route::get('/editar/turma/{id}','TurmaController@editar')->name('edit_turma');
    // Route::post('/atualizar/turma','TurmaController@atualizar')->name('update_turma');
    // Route::get('/remover/turma/{id}','TurmaController@remover')->name('delete_turma');

    // Route::get('/listar/resposta','RespostaController@listar')->name('list_resposta');
    // Route::get('/cadastrar/resposta','RespostaController@cadastrar')->name('new_resposta');
    // Route::post('/adicionar/resposta','RespostaController@adicionar')->name('add_resposta');
    // Route::get('/editar/resposta/{id}','RespostaController@editar')->name('edit_resposta');
    // Route::post('/atualizar/resposta','RespostaController@atualizar')->name('update_resposta');
    // Route::get('/remover/resposta/{id}','RespostaController@remover')->name('delete_resposta');

    // // Route::get('/listar/simuladoaluno','SimuladoAlunoController@listar')->name('list_simulado_aluno');
    // Route::get('/cadastrar/simuladoaluno','SimuladoAlunoController@cadastrar')->name('new_simulado_aluno');
    // Route::post('/adicionar/simuladoaluno','SimuladoAlunoController@adicionar')->name('add_simulado_aluno');
    // Route::get('/editar/simuladoaluno/{id}','SimuladoAlunoController@editar')->name('edit_simulado_aluno');
    // Route::post('/atualizar/simuladoaluno','SimuladoAlunoController@atualizar')->name('update_simulado_aluno');
    // Route::get('/remover/simuladoaluno/{id}','SimuladoAlunoController@remover')->name('delete_simulado_aluno');
