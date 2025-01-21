<?php
namespace Source\Model;

use Source\Core\Db;
use Source\Core\Session;

class DefiGeral extends Db
{
	
	function __construct()
	{
		parent::__construct("ep_conf_geral",[]);
	}

  
  public function add()
  {
    $casa=$this->find()->count();

    if ($casa==0) {
         $this->tipo_impressao="A4";
    $this->impressora=null;
    $this->impressao_vias=1;
    $this->tipo_fatura="rel";
    
     if (!$this->save()) {
         return false;
     }

     return true;
    }
    return true;
  }
	
	public function save()
    {
         if (!empty($this->id_geral)) {
            
            $id=$this->id_geral;
            $this->update($this->safe(), "id_geral = :id", "id={$id}");
            if ($this->fail()) {
                $this->message->error("Erro ao Editar a categoria");
                return false;

            }

            return true;
         }


         if (empty($this->id_geral)) {
                          

            $id = $this->insert($this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar");
                return false;

            }  

         }

          $this->dados = $this->findById("id_geral",$id)->data();
        return true;
    }
}