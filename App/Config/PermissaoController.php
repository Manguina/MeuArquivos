<?php

namespace App\Config;
use Source\Controller\Controller; 
use Source\Model\User;
use Source\Model\Category;
use Source\Model\Caixa;
use Source\Model\Product;
use Source\Model\Pessoa;
use Source\Model\Generic;
use Source\Model\Motivo;
use Source\Api\Core\AsymmetricCrypto;
use Source\Model\UserAcess;

class PermissaoController extends Controller
{
	private $pdo;
    private $errorSaft;
	function __construct()
	{
 
         parent::__construct("erros");
         $this->pdo=(new Generic())->tableConst("per_grupos");
	}

	public function home()
	{
		$this->engine->render("permissao/home",
            [
                "listar"=>$this->pdo->find()->fetch(true)
            ]);
	}

    public function add($data)
    {
       $id=base64_decode($data['id']);
        $this->engine->render("permissao/add",
            [
                "id"=>$id,
                "lista"=>(new Generic())->tableConst("per_lista")->find("tipo='Pai'")->fetch(true)
            ]);
    }



    public function save($data)
    {

        if (empty($data["ds_nome"])) {
            echo json_encode(["error"=>"Campo Vazio"]);
            return;
        }
        $mad=$this->pdo->find("ds_grupo=:d","d={$data["ds_nome"]}")->fetch();

        if ($mad) {
            echo json_encode(["error"=>"Ja existe um Grupo com este  nome"]);
            return;
        }

        $grupo=$this->pdo;
        $grupo->ds_grupo=$data["ds_nome"];
        $grupo->id_empresa=2;
        $grupo->estado="Activo";

        if (!$grupo->saveGeneric("id_grupo")) 
          {
            echo json_encode(["error"=>$grupo->message()->getText()]);
               return;
            }
          $data=base64_encode($grupo->id_grupo);
          echo json_encode(["sucesso"=>url("/user_acesso/add/{$data}")]);
    }

     public function saveUser($data)
    {
         $gru=(new Generic())->tableConst("per_lista");
          $save=(new UserAcess());
        if ($data["tipo"]=="Filho") {
            $d=$gru->find("id=:id","id={$data["id_per"]}")->fetch();
           $user=(new UserAcess())->find("id_sub=:id AND id_grupo=:g","id={$d->id_pai}&g={$data["grupo"]}")->fetch();

           if (!$user) {
               $save->id_grupo=$data["grupo"];
               $save->id_sub=$d->id_pai;
               $save->tipo=$data["tipo"];

               if (!$save->save()) {
                   echo json_encode(["error"=>$save->message()->getText()]);
                   return;
               }

           }
        }

               $save->id_grupo=$data["grupo"];
               $save->id_sub=$data["id"];
               $save->tipo=$data["tipo"];

               if (!$save->save()) {
                   echo json_encode(["error"=>$save->message()->getText()]);
                   return;
               }

               echo json_encode(["success"=>"Permissão Adicionada"]);
               return;

    }



    private function renderHtml($data)
    {
        $html="";

        if ($data) {        
                $html=$this->view->v("/permissao/views/tabela",["listar"=>$data]);
        }

        return $html;
    }


}