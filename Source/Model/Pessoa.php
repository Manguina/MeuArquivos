<?php
namespace Source\Model;

use Source\Core\Db;

use Source\Core\Session;

class Pessoa extends Db
{
	
	function __construct()
	{
		parent::__construct("pessoa",["nome_compl,sexo,bi","data_nascimento","regime","pais","provincia","cidade","rua","edificio"]);
	}



	/**
	 *função que insere e atualiza dados no banco
	 * @param  $this->data object
	 * @return $this->dados object
	 * 
	 * **/


	 public function save()
	{
		 if (!empty($this->id_pessoa)) {
		 	
		 	$id=$this->id_pessoa;
            $this->update($this->safe(), "id_pessoa = :id", "id={$id}");
            if ($this->fail()) {
                $this->message->error("Erro ao Editar os dados do Usuario");
                return false;

            }
		 }


		 if (empty($this->id_pessoa)) {
		 	
		 	 $nif=$this->find("bi=:p","p={$this->bi}")->fetch();

		 	 if ($nif) {
		 	 	 $this->message->error("Esse Usruario Já se Encontra Cadastrado");
                return false;
		 	 }



		 	$id = $this->insert($this->safe());

            if ($this->fail()) {
                //var_dump($this->fail());
                $this->message->error("Erro ao cadastrar, verifique os dados, do Usuario");

                return false;

            }  

		 }
         
		$this->dados = $this->findById("id_pessoa",$id)->data();
        return true;
	}
}