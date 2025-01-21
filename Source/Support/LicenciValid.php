<?php

namespace Source\Support;

use Source\Core\Session;
use Source\Model\Generic;
use Source\Api\Core\AsymmetricCrypto;
use Source\Setup\Model\DataBase;
/**
 * FSPHP | Class Message
 *
 * @author Robson V. Leite <cursos@upinside.com.br>
 * @package Source\Core
 */

class LicenciValid
{

    public $pdo;
    private $criptografia;
    private $database;

    function __construct()
    {
       $this->pdo=(new Generic())->tableConst("ep_config");
       //$this->criptografia=(new AsymmetricCrypto());
       $this->database=(new DataBase());
    }


    public function verifyLicenca()
    {

        if ($this->database->validarDase()) {
           // code...
       
         $company=$this->dataGeneric("empresa")->find()->fetch();

         if (!$company) 
         {
             //redirect("/");
             return;
         }

         if ($this->validaLicence($company)) 
         {
               
               return ;
         }


         $this->controlaLicenca($company);

          }
    }

   /**
    * verifica se a empresa tem licenca ou esta valida, se nao tem ele insere um licenca temporaria
    * @param int $data
    * @return bool
    * **/

    private function validaLicence($data)
    {
         $das=$this->pdo->find("cod_empresa=:id","id={$data->id}")->fetch();
         if (!$das) {
            $da=$this->pdo;
            $da->modulo="Todos";
            $da->licenca=$this->createHash($data);
            $da->tempo=AsymmetricCrypto::encrypt("15");
            $da->cod_empresa=$data->id;
            $da->tipo_licenca="Desktop";
            $da->situacao="Teste";
            $da->status="Activo";
            $da->dispositivos_permitidos=1;
            $da->parceiro="APTO";
            $da->dispositivo=gethostname();
            $da->SO=PHP_OS;
            $da->processador="Generico";
            $da->data_activacao=date("Y-m-d H:i:s");
            $da->data_criate=date("Y-m-d H:i:s");
            if (!$da->saveGeneric("id_confi")) {
               return false;
            }

            return true;
         }
    }


   
 /**
  * FUNCAO QUE CONTROLA A LICENCA E VALIDA
  * @param object $data
  * @return bool true|false
  * **/
   private function controlaLicenca($data)
   {
      $das=$this->pdo->find("cod_empresa=:id","id={$data->id}")->fetch();
      $pdo=$this->pdo;

     
      if ($das) {
         $codigo=explode(";", AsymmetricCrypto::decrypt($das->licenca));
         $dias=AsymmetricCrypto::decrypt($das->tempo);
          $ultimo="0";
         if ($dias<=0 && $das->status=="Activo") {
            $pdo->id_confi=$das->id_confi;
            $pdo->tempo=AsymmetricCrypto::encrypt($ultimo);
            $pdo->status="Expirado";
            $pdo->data_update=date("Y-m-d H:i:s");
            if (!$pdo->updateGeneric("id_confi=:id","id={$das->id_confi}")) {
               return false;
            }

            return false;
         }


         $data1=date($das->data_update);
         $data1 = date("Y-m-d");
         $data2 = explode(":", $codigo[3]);
         $dias = round((strtotime($data2[1]) - strtotime($data1)) / (60 * 60 * 24));
         if ($das->data_update!=date("Y-m-d")) 
         {  
            $temp=explode(":", $codigo[1]);
            $tempo=$temp[1]-1;
            $pdo->id_confi=$das->id_confi;
            $pdo->tempo=AsymmetricCrypto::encrypt($dias);
            $pdo->data_renovacao= $data2[1];
            $pdo->data_update=date("Y-m-d H:i:s");
            if (!$pdo->updateGeneric("id_confi=:id","id={$das->id_confi}")) {
               return false;
            }

            
           return true;
         }
         return false;
      }
   }
    

    /**
     * Funcao que faz o Crud no Banco para qualquer dabela
     * @param string $table;
     * @return Class 
     * **/
    private function dataGeneric(string $table)
    {
        return (new Generic())->tableConst($table);
    }



    private function createHash($data)
    {
       $mensagem=$data->nif.";tempo:15;data_inicial:".date("Y-m-d").";data_expira:".date('Y-m-d', strtotime('+14 days'));
       return AsymmetricCrypto::encrypt($mensagem);
       
    }
       
}