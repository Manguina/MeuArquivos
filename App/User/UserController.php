<?php

namespace App\User;
use Source\Controller\Controller; 
use Source\Model\User;
use Source\Model\Pessoa;
use Source\Model\Generic;


class UserController extends Controller
{
	
	function __construct()
	{
		if (!User::userLogado()) {
            //$this->message->error("Efetue login para acessar ao sistema.");
            redirect("/entrar");

         }

      
		parent::__construct("erros");
	}

	public function home()
	{  
		$total=(new Pessoa())->find("tipo=:t","t=User")->fetch(true);
		$activos=(new Pessoa())->find("tipo=:t AND status=:s","t=User&s=Activo")->fetch(true);
		$inativo=(new Pessoa())->find("tipo=:t AND status=:s","t=User&s=bloq")->fetch(true);
		
        $this->engine->render("login/home",
        	["listar"=>(new Pessoa())->find("tipo=:t AND status=:s","t=User&s=Activo")->fetch(true),
        	"sit"=>null,
        	"total"=>isset($total) ? count($total) : 0,
        	"activos"=>isset($activos) ? count($activos) : 0,
        	"inativo"=> isset($inativo) ? count($inativo) : 0
        ]);
	}


	public function inativo()
	{
		
        $this->engine->render("login/inativo",["listar"=>(new Pessoa())->find("tipo=:t AND status=:s","t=User&s=bloq")->fetch(true),"sit"=>"Inativo"]);
	}


	public function store($data)
	{

		if (isset($data["id"])) {
			$ts=(new Pessoa())->find("id_pessoa=:d","d={$data["id"]}")->fetch();
			$data=$ts;
		}else{
		$data=(new \stdClass());
		$data->id_pessoa="";
		$data->nome_compl="";
		$data->sexo="";
		$data->bi="";
		$data->telefone="";
		$data->email="";
		$data->data_nascimento="";
		$data->nacionalidade="";
		$data->pais="";
		$data->provincia="";
		$data->cidade="";
		$data->rua="";
		$data->edificio="";
		$data->tipo="User";}
        $this->engine->render("login/cadastro",
        	[
        		"user"=>$data,
        		"funcao"=>(new Generic())->tableConst("pessoa_funcao")->find()->fetch(true),
        		"habil"=>(new Generic())->tableConst("pessoa_habilitcao")->find()->fetch(true)
        	]);
	}


	public function SaveData($data)
	{
		
		if (empty($data["nome"])) {
			$js["warning"]="Campo Nome Vazio";
			echo json_encode($js);
			return ;
		}


		if (empty($data["bi"])) {
			$js["warning"]="Campo B.I Vazio";
			echo json_encode($js);
			return ;
		}

		if (strlen($data["bi"]) < 9) {
			$js["warning"]="B.I  Inválido";
			echo json_encode($js);
			return ;
		}

		if (empty($data["telefone"])) {
			$js["warning"]="Campo Telefone Vazio";
			echo json_encode($js);
			return ;
		}

		if (strlen($data["telefone"]) < 9) {
			$js["warning"]="Número de Telefone Inválido";
			echo json_encode($js);
			return ;
		}


		if (empty($data["provincia"])) {
			$js["warning"]="Campo Província Vazio";
			echo json_encode($js);
			return ;
		}

		if (empty($data["cidade"])) {
			$js["warning"]="Campo Cidade Vazio";
			echo json_encode($js);
			return ;
		}

		if (empty($data["rua"])) {
			$js["warning"]="Campo Rua Vazio";
			echo json_encode($js);
			return ;
		}

		if (empty($data["casa"])) {
			$js["warning"]="Campo Casa Vazio";
			echo json_encode($js);
			return ;
		}


		if (empty($data["nascimento"])) {
			$js["warning"]="Data de Nascimento é Obrigatório";
			echo json_encode($js);
			return ;
		}

		$ps=(new Pessoa());

		if (!empty($data["id_pessoa"])) {
			$ps->id_pessoa=$data["id_pessoa"];
		}

		$ps->nome_compl=$data["nome"];
		$ps->sexo=$data["sexo"];
		$ps->bi=$data["bi"];
		$ps->telefone=$data["telefone"];
		$ps->email=$data["email"];
		$ps->data_nascimento=$data["nascimento"];
		$ps->nacionalidade=$data["nacionalidade"];
		$ps->pais=$data["country"];
		$ps->provincia=$data["provincia"];
		$ps->cidade=$data["cidade"];
		$ps->rua=$data["rua"];
		$ps->edificio=$data["casa"];
		$ps->tipo="User";
		$ps->habilitacao=$data["habilitacao"];
		$ps->funcao=$data["funcao"];
		$ps->id_empresa=$this->session->company->id;
    

		if (!$ps->save()) {
			$js["error"]=$ps->message()->getText();
			echo json_encode($js);
            return;
		}

		if (!empty($data["id_pessoa"])) {
			echo json_encode(["success"=>"Dados Alterados Com Sucesso"]);
			return;
		}


		$id=encryptString($ps->data()->dados->id_pessoa);
		//echo decryptString(encryptString($ps->data()->dados->id_pessoa));
		
        echo json_encode(["redirect"=>url("usuario/acesso/{$id}")]);
		
	}


	public function saveAcess($data){

		
		$code=decryptString($data["code"]);
        $ts=(new User())->find("id_pessoa=:d","d={$code}")->fetch();

        if ($ts) {
        	$user=$ts;
        }else{
		$user= new \stdClass();
		$user->id_acesso="";
		$user->username="";
		$user->pos="";
	}
		
		$data=(new Pessoa())->find("id_pessoa=:id","id=$code")->fetch();
		 $this->engine->render("login/acesso",["dados"=>$data,"user"=>$user]);

	}

	public function access($data){

		//var_dump($data);

		if (empty($data["nome_acesso"])) {
			$js["warning"]="Campo Nome de Acesso é Obrigatório";
			echo json_encode($js);
			return;
		}

		if (empty($data["passw"])) {
			$js["warning"]="Campo Senha é Obrigatório";
			echo json_encode($js);
			return;
		}

		if ($data["passw"]!=$data["passw2"]) {
			$js["warning"]="A senha  e confirmar Senha são diferentes";
			echo json_encode($js);
			return;
		}

		$user=(new User);
		if (!empty($data["id_acesso"])) {
			$user->id_acesso=$data["id_acesso"];
		}
		$user->id_pessoa=$data["id_pessoa"];
		$user->username=$data["nome_acesso"];
		$user->password=$data["passw"];
		$user->grupo=$data["grupos"];
		$user->estado="Activo";
		$user->acesso=0;
		$user->pos=(isset($data["passw"]) ? 1 : 0);
		$user->id_empresa=$this->session->company->id;

	if (!$user->save()) {
			$js["error"]=$user->message()->getText();
			echo json_encode($js);
            return;
		}

		if (!empty($data["id_acesso"])) {
			echo json_encode(["success"=>"Dados Alterados Com Sucesso"]);
			return;
		}
		
		echo json_encode(["success"=>"Usuário Cadastrado Com Sucesso"]);
		

	}

	
private function savedEmpresa(){
	// Caminho do arquivo no mesmo diretório do script PHP

	$arquivo = dirname(__DIR__) . '/base.txt';
	$frase = "Usuario de acesso cadastrado com sucesso\n";
			

	
	// Verificar se o arquivo existe, caso contrário, criar o arquivo vazio
	if (file_exists($arquivo)) {
		
	
	
	$conteudo = file_get_contents($arquivo);

		// Verificar se a frase já existe no conteúdo

		if (strpos($conteudo, $frase) !== false) {
					
		} else {
		
file_put_contents($arquivo, $frase, FILE_APPEND);

			
		}
	
	// Ler o conteúdo do arquivo
	}


}



	public function del($data)
	{

		$ps=(new Pessoa());

		if (isset($data["rest"])) {
			$ps->id_pessoa=$data["rest"];
		$ps->status="Activo";
		}else{
			$ps->id_pessoa=$data["aluno"];
			$ps->status="bloq";

		}
		

		if (!$ps->save()) {
			$js["error"]=$ps->message()->getText();
			echo json_encode($js);
            return;
		}

		$total=(new Pessoa())->find("tipo=:t","t=User")->fetch(true);
		$activos=(new Pessoa())->find("tipo=:t AND status=:s","t=User&s=Activo")->fetch(true);
		$inativo=(new Pessoa())->find("tipo=:t AND status=:s","t=User&s=bloq")->fetch(true);

		if (isset($data["rest"])) {
			
			$data=(new Pessoa())->find("tipo=:t AND status=:s","t=User&s=bloq")->fetch(true);
		// $this->engine->render("login/acesso",["dados"=>$data,"user"=>$user]);
		echo json_encode([
			"success"=> $this->view->v("login/pedaco/tblpsooa",["listar"=>$data]),
			"total"=>isset($total) ? count($total) : 0,
        	"activos"=>isset($activos) ? count($activos) : 0,
        	"inativo"=> isset($inativo) ? count($inativo) : 0
		]);

			return;
		}
       $data=(new Pessoa())->find("tipo=:t AND status=:s","t=User&s=Activo")->fetch(true);
		// $this->engine->render("login/acesso",["dados"=>$data,"user"=>$user]);
		echo json_encode(
			["success"=> $this->view->v("login/pedaco/tblpsooa",["listar"=>$data]),
			"total"=>isset($total) ? count($total) : 0,
        	"activos"=>isset($activos) ? count($activos) : 0,
        	"inativo"=> isset($inativo) ? count($inativo) : 0
		]);


	}

	public function filtrar($data)
	{
		  $data=(new Pessoa())->find("tipo=:t AND status=:s AND nome_compl LIKE :id","t=User&s=Activo&id=%{$data['nome']}%")->fetch(true);
		  $html="";
		  if ($data) {
		  	$html=$this->view->v("login/pedaco/tblpsooa",["listar"=>$data]);
		  }
		
		echo json_encode(["success"=> $html]);

	}


	public function tipo($data)
	{

		if ($data["tipo"]=="funcao") {
			$save=(new Generic())->tableConst("pessoa_funcao");
			$save->ds_funcao=$data["ds"];
			if (!$save->saveGeneric("id_funcao")) {
				echo json_encode(["error"=>$save->message()->getText()]);
			return;
		}

		$dados=(new Generic())->tableConst("pessoa_funcao")->find()->fetch(true);
           $baza=[];

           foreach ($dados as $d) {
         	 $baza[]=["id"=>$d->id_funcao,"nome"=>$d->ds_funcao];
             }

            $retor=["success"=>'Função Cadastrado Com Sucesso',"data"=>$baza,"tipo"=>"funcaoRender"];
            echo json_encode($retor);
		}

		 if($data["tipo"]=="habili") 
		{
			$save=(new Generic())->tableConst("pessoa_habilitcao");
			$save->ds_habi=$data["ds"];
			if (!$save->saveGeneric("id_habi")) {
				echo json_encode(["error"=>$save->message()->getText()]);
			return;
		}

		$dados=(new Generic())->tableConst("pessoa_habilitcao")->find()->fetch(true);
           $baza=[];

           foreach ($dados as $d) {
         	 $baza[]=["id"=>$d->id_funcao,"nome"=>$d->ds_funcao];
             }

            $retor=["success"=>'Habilitação  Cadastrado Com Sucesso',"data"=>$baza,"tipo"=>"funcaoHabili"];

            echo json_encode($retor);
		}
	}

	


}