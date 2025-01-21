<?php

namespace App\Config;
use Source\Controller\Controller; 
use Source\Model\User;
use Source\Model\Category;
use Source\Model\Caixa;
use Source\Model\Product;
use Source\Model\Pessoa;
use Source\Model\Generic;
use Source\Model\Rentacao;
use Source\Model\DefiGeral;
use Source\Api\Core\AsymmetricCrypto;

class Definicoes extends Controller
{
	private $saft;
    private $pdo;
    private $retencao;
    private $template;
	function __construct()
	{

         parent::__construct("erros");
           if (!User::userLogado()) 
        {
            redirect("/entrar");
         }

         $this->pdo=(new Generic());
         $this->retencao=(new Generic())->tableConst("fina_retencao");
         $this->template=(new Generic())->tableConst("ep_template");
	}

	public function home() 
	{ 
        
		$this->engine->render("definicao/home",[
            "template"=>$this->template->find()->fetch(true),
            "rentecao"=>$this->retencao->find()->fetch(),
            "impresora"=>$this->listarImpressora()
        ]);
	}


    public function preview($data) 
    { 
        if ($data["tela"]=="rel") 
        {
           $this->engine->render("definicao/view/modelo01");
           return;
        }
        
    }

//METODOS POST

    public function retencao($data)
    {
       $rete=(new Rentacao());
        if (!is_numeric($data["percentagem"])) {

            echo json_encode(["error"=>"Valor da Percentagem Errado"]);
            return;
        }

        if (!is_numeric($data["valor"])) {

            echo json_encode(["error"=>"Valor minimo da renteção Errado"]);
            return;
        }
       $da=$rete->find()->fetch();
       
       if (!$da) {
           $rete->percentagem=$data["percentagem"];
           $rete->valor=$data["valor"];
           if (!$rete->save()) {
               echo json_encode(["error"=>$rete->message()->getText()]);
              return;
           }
        }else{ 

         $rete->id_rentecao=$da->id_rentecao;
         $rete->percentagem=$data["percentagem"];
         $rete->valor=$data["valor"];
         if (!$rete->save()) {
               echo json_encode(["error"=>$rete->message()->getText()]);
              return;
           }
        }
           echo json_encode(["success"=>"Operação Realizada com Sucesso"]);
    }

    public function tela($data)
    {
          
          $dados=(new DefiGeral())->find()->fetch();
          $save=(new DefiGeral());

          $save->id_geral=$dados->id_geral;
          $save->tipo_fatura=$data["valor"];

          if (!$save->save()) {
              echo json_encode(["error"=>$save->message()->getText()]);
              return;
          }

          echo json_encode(["success"=>"Modelo Adicionado com Sucesso"]);
    }

//METODOS PRIVADOS
  
	private function listarImpressora()
    {
         $os=PHP_OS;
         $output = [];
         $printers=[];


         if (stristr($os, "win")) {
            exec('wmic printer get name', $output);
            $printers = array_filter($output, fn($line) => trim($line) !== '' && $line !== 'Name');
             
         }elseif (stristr($os, "linux")) {

            exec('lpstat -p', $output);
            $printers=$output;
         }

         return $this->htmLister($printers);
    }

    private function htmLister($data)
    {
        $os=PHP_OS;
        $html="";
        if ($data) {
           if (stristr($os, "win")) {
                 foreach ($data as $printer) {
                  $html.="<option >" . htmlspecialchars($printer) . "</option>";
                 }
           }
           elseif(stristr($os, "linux")){
          foreach ($data as $line) {
            if (preg_match('/printer\s+([^\s]+)/', $line, $matches)) {
                $html.="<option>" . htmlspecialchars($matches[1]) . "</option>";
            }
        }
        }
    }

    return $html;
 }   

}