<?php
namespace Source\Model;

use Source\Core\Db;
use Source\Core\Session;

class User extends Db
{
	
	function __construct()
	{
		parent::__construct("p_acesso",["id_pessoa,username,password"]);
	}


	/**
	 * Função que mostra se o usuario esta ou não logado e cria uma sessão para trazer os dados do usuario
	 * @return $session->user->data() object
	 * **/

	 public static function userLogado()
    {
        $session = new Session();
        if (!$session->has("user")) {
            return null;
        }
        
    return $session->user->data();
        
       
    }


    /**
     * funcao para logar
     * @param string $username
     * @param string $password
     * @return bool true ou false
     * **/

       public function logar(string $username, string $password) 
    {

            if (empty($username) || empty($password)) {
                
                 $this->message->error("Campo Usuario ou Senha Esta Vazio");
                

                return false;
            }

			$user=$this->find("username=:p","p={$username}")->fetch();


            if (!$user || !password_verify($password,$user->password)) {

                $this->message->error("Usuario ou Senha Invalído")->render();
                return false;
            }
        
        $this->dado=(new Pessoa())->find("id_pessoa=:p","p={$user->id_pessoa}")->fetch();
        $empresa=(new Company())->find("id=:id","id={$user->id_empresa}")->fetch();

        if (!$empresa) {
             $this->message->error("Empresa Invalida")->render();
            return  false;
        }
        $session=(new Session());
        $session->set("user",$this->data());
        $session->set("company",$empresa->data());
         return true;
    }





	/**
	 *função que insere e atualiza dados no banco
	 * @param  $this->data object
	 * @return $this->dados object
	 * @return bool true ou false
	 * **/


	 public function save()
	{
		 
        if (!is_passwd($this->password)) {
            $min = CONF_PASSWD_MIN_LEN;
            $max = CONF_PASSWD_MAX_LEN;
            $this->message->warning("A senha deve ter entre {$min} e {$max} caracteres");
            return false;

        } else {
            $this->password = passwd($this->password);
        }


         if (!empty($this->id_acesso)) {
        	$id=$this->id_pessoa;
            $this->update($this->safe(), "id_pessoa = :id", "id={$id}");
            if ($this->fail()) {
               $this->message->error("Erro ao atualizar, verifique os dados, do Usuario");
                return false;
            }
        }


          if (empty($this->id_acesso)) {
       		 $achou=$this->find("username=:p","p={$this->username}")->fetch();

	        if ($achou) {

	       $this->message->warning("Já existe Um Usuário Com este Nome ");

	            return false;

	        }

        	
        	$id = $this->insert($this->safe());
        	
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados, do Usuario");
                return false;
            }

        }
         $rest=(new Pessoa())->find("id_pessoa=:p","p={$id}")->fetch();
         $this->dados = $rest;
         return true;


	}
}