<?php

namespace App;
use Source\Controller\Controller; 
use Source\Model\User; 
use Source\Model\Generic; 

class Login extends Controller
{
	
	function __construct()
	{
		parent::__construct("erros");
	}

	public function home()
	{
        $this->engine->render("login/login",["user"=>(new Generic())->tableConst("p_acesso")->find()->fetch(true)]);
	}

	public function recupera()
	{
        $this->engine->render("login/recupara");
	}

	public function alterar()
	{
        $this->engine->render("login/alterar");
	}


	public function login($data)
	{
		//var_dump($data);

		if (empty($data["username"]) || empty($data["password"])) {
			echo json_encode(["success"=> $this->view->v("login/pedaco/message",["tipo"=>"warning","msg"=>"Username ou Senha Inválida"])]);
			return;
		}

		$user= new User();
        
        if (!$user->logar($data["username"],$data["password"])) {
        	echo json_encode(["success"=> $this->view->v("login/pedaco/message",["tipo"=>"danger","msg"=>$user->message()->getText()])]);
			return;
        }

        if ($user->status=="bloq") {
        	session_destroy();
        	echo json_encode(["success"=> $this->view->v("login/pedaco/message",["tipo"=>"danger","msg"=>"Este Usuário Foi Bloqueado, Contacte O administrador"])]);
			return;
        }

        $autentica=(new User())->find("id_pessoa=:d","d={$user->id_pessoa}")->fetch();

        if (!$autentica->acesso) {
        	  echo json_encode(["redirect"=>url("/alterar_minha_senha")]);
        	  return;
        }

		if($autentica->grupo!="Administrador")
		{
            echo json_encode(["redirect"=>url("/posto")]);
			return;
		}

        echo json_encode(["redirect"=>url()]);
	}

	public function trocar($data)
	{
       
       $id=$_SESSION['user']->id_pessoa;
       $user=(new User())->find("id_pessoa=:id","id={$id}")->fetch();
     
      if (!password_verify($data['senha_atual'],$user->password)) {
      		echo json_encode(["success"=> $this->view->v("login/pedaco/message",["tipo"=>"warning","msg"=>"Senha Atual Esta Errada"])]);
			return;
		
      }




      if (strlen($data['password']) < 4) {
      	echo json_encode(["success"=> $this->view->v("login/pedaco/message",["tipo"=>"warning","msg"=>"A Senha Tem que Ter No Minimo 4 Caracteres."])]);
			return;
		
      }


      if ($data['password']!=$data['password2']) {
      	echo json_encode(["success"=> $this->view->v("login/pedaco/message",["tipo"=>"warning","msg"=>"Erro, Senhas Diferentes"])]);
			return;
      }

      if (password_verify($data['password'],$user->password)) {
      		echo json_encode(["success"=> $this->view->v("login/pedaco/message",["tipo"=>"warning","msg"=>"Senha Atual e  Nova tem que ser diferentes"])]);
			return;
		
      }

      $us=(new User());

		$us->id_acesso=$user->id_acesso;
		/*$user->id_pessoa=$id;
		$user->username=$user->username;
		$us->password=$data["password"];
		$user->grupo=$user->grupo;
		$user->estado="Activo";
		$user->acesso=1;
		$user->pos=$user->pos;*/

		$us->id_pessoa=$id;
		$us->username=$user->username;
		$us->password=$data["password"];
		$us->grupo=$user->grupo;
		$us->estado="Activo";
		$us->acesso=1;
		$us->pos=$user->pos;
		if (!$us->save()) {
			echo json_encode(["success"=> $this->view->v("login/pedaco/message",["tipo"=>"warning","msg"=>$us->message()->getText()])]);
            return;
		}

		if($user->grupo!="Administrador")
		{
            echo json_encode(["redirect"=>url("/posto")]);

			return;
		}

		echo json_encode(["redirect"=>url()]);
	}



	public function sair()
	{
		session_destroy();
		redirect("/");
	}
}