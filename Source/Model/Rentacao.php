<?php
namespace Source\Model;

use Source\Core\Db;
use Source\Core\Session;

class Rentacao extends Db
{
	
	function __construct()
	{
		parent::__construct("fina_retencao",[""]);
        //$this->addAutomatico();
	}


    public function calculaRetecao($cliente,$valor)
    {
        
       $retido=new \stdClass();
       $retido->valRet=0;
       $retido->valSobrou=0;
       $cliente=(new Generic())->tableConst("pessoa")->find("id_pessoa=:id","id={$cliente}")->fetch();
       $rete=$this->find()->fetch();
       
       if ($cliente->entidade==="Coletivo") {
          if ($rete->valor<=$valor) {
             $percentagemRetencao = $rete->percentagem / 100; // Percentagem de 6.50%
             $retido->valRet=$valor *  $percentagemRetencao ;
             $retido->valSobrou=$valor - $retido->valRet;
          }
       }

       return $retido;
    }


    private function addAutomatico()
    {
        $rete=$this->find()->fetch();

        if (!$rete) {
            $this->percentagem="6.50";
            $this->valor="20000";
            $this->save();
        }

    }


	
	public function save()
    {
         if (!empty($this->id_rentecao )) {
            
            $id=$this->id_rentecao;
            $this->update($this->safe(), "id_rentecao  = :id", "id={$id}");
            if ($this->fail()) {
                $this->message->error("Erro ao Alterar a Retencão");
                return false;

            }

            return true;
         }


         if (empty($this->id_rentecao)) {
            
             

            $id = $this->insert($this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar ");

                return false;

            }  

         }

          $this->dados = $this->findById("id_rentecao",$id)->data();
        return true;
    }
}


