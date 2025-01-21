<?php 

namespace Source\Controller;

use Source\Core\View;
use Source\Core\Engine;
use Source\Support\Message;
use Source\Support\LicenciValid;
use Source\Core\Session;
use Source\Model\User;
use Source\Model\DefiGeral;

class Controller 
{   

    protected $view;
    protected $message;
    protected $session;
    protected $engine;
    protected $photoMessage;
    protected $photo;
    protected $licencas;

    function __construct($theme)
	{
		$this->message=(new Message());
        $this->view=(new View($theme));
        $this->session=(new Session());
        $this->engine=(new Engine("casa"));
        $this->licencas=(new LicenciValid())->verifyLicenca();
        $casa=(new DefiGeral())->add();
        date_default_timezone_set('Africa/Luanda');
       // $this->permisao();

	}


    protected function views($pag, array $data=[])
    {
    	$this->view->section($pag,$data);
    	$this->view->render();
    }


    private function permisao()
    {

        if (isset($this->session->user)) {
            $id=$_SESSION['user']->id_pessoa;
            $user=(new User())->find("id_pessoa=:id","id={$id}")->fetch();

            if($user->grupo!="Administrador")
            {
                echo json_encode(["redirect"=>url("/posto")]);

                return;
            }
        }
       
    }

     protected function uploadFiles($files,$fotoDefault): bool
    {
        

        if ($files['name']=="") {
            
            $this->photo=file_get_contents($fotoDefault);
            return true;
        }

        if ($files['error']!=0) {
            
            $this->photoMessage="ERROR AO CARREGAR A IMAGEM, TAMANHO DEMAZIADO GRANDE";
            echo json_encode($sms);
            return false;
        }

         if ($files['type']!="image/gif" && $files['type']!="image/jpeg" && $files['type']!="image/png" && $files['type']!="image/jpg") {
                
                 $this->photoMessage="O SISTEMA SÓ PERMITE O CARREGAMENTOS DOS ARQUIVOS DO TIPO(PNG,JPEG,JPG)";
                 echo json_encode($sms);
                return false;
        }

        $this->photo=file_get_contents($files['tmp_name']);
        return true;
    }
}
