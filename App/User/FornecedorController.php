<?php
namespace App\User;

use Source\Controller\Controller; 
use Source\Model\User;
use Source\Model\Pessoa;

class FornecedorController extends Controller
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
		

        $total=(new Pessoa())->find("tipo=:t","t=Fornecedor")->fetch(true);
		$activos=(new Pessoa())->find("tipo=:t AND status=:s","t=Fornecedor&s=Activo")->fetch(true);
		$inativo=(new Pessoa())->find("tipo=:t AND status=:s","t=Fornecedor&s=bloq")->fetch(true);

        $this->engine->render("fornecedor/home",
           [
        	"listar"=>(new Pessoa())->find("tipo=:t AND status=:s","t=Fornecedor&s=Activo")->fetch(true),"sit"=>null,
        	"total"=>isset($total) ? count($total) : 0,
        	"activos"=>isset($activos) ? count($activos) : 0,
        	"inativo"=> isset($inativo) ? count($inativo) : 0
          ]);
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
        $this->engine->render("fornecedor/cadastro",["user"=>$data]);
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
		

		if (!empty($data["telefone"]) && strlen($data["telefone"]) < 9) {
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


		

		$ps=(new Pessoa());

		if (!empty($data["id_pessoa"])) {
			$ps->id_pessoa=$data["id_pessoa"];
		}

		$ps->nome_compl=$data["nome"];
		$ps->sexo=$data["sexo"];
		$ps->bi=$data["bi"];
		$ps->telefone=(!empty($data["telefone"]) ? $data["telefone"] : "Não Especificado");
		$ps->email=$data["email"];
		$ps->data_nascimento=$data["nascimento"];
		$ps->nacionalidade=$data["nacionalidade"];
		$ps->pais=$data["country"];
		$ps->provincia=$data["provincia"];
		$ps->cidade=$data["cidade"];
		$ps->rua=$data["rua"];
		$ps->edificio=$data["casa"];
		$ps->tipo="Fornecedor";


		if (!$ps->save()) {
			$js["error"]=$ps->message()->getText();
			echo json_encode($js);
            return;
		}

			echo json_encode(["success"=>"Dados Alterados Com Sucesso"]);
			

		
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

		if (isset($data["rest"])) {
			
			$data=(new Pessoa())->find("tipo=:t AND status=:s","t=Cliente&s=bloq")->fetch(true);
		// $this->engine->render("login/acesso",["dados"=>$data,"user"=>$user]);
		echo json_encode(["success"=> $this->view->v("cliente/tblpsooa",["listar"=>$data])]);

			return;
		}
       $data=(new Pessoa())->find("tipo=:t AND status=:s","t=Cliente&s=Activo")->fetch(true);
		// $this->engine->render("login/acesso",["dados"=>$data,"user"=>$user]);
		echo json_encode(["success"=> $this->view->v("cliente/tblpsooa",["listar"=>$data])]);


	}

	public function filtrar($data)
	{
		  $data=(new Pessoa())->find("tipo=:t AND status=:s AND nome_compl LIKE :id","t=Cliente&s=Activo&id=%{$data['nome']}%")->fetch(true);
		  $html="";
		  if ($data) {
		  	$html=$this->view->v("cliente/tblpsooa",["listar"=>$data]);
		  }
		
		echo json_encode(["success"=> $html]);

	}


}