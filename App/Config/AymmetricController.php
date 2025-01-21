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

class AymmetricController extends Controller
{
	private $saft;
    private $errorSaft;
	function __construct()
	{

         parent::__construct("erros");
           //$this->session->set("SaftXml",null);
	}

	public function home()
	{
		$this->engine->render("licenca/solicita",["modulo"=>(new Generic())->tableConst("ep_modulo")->find()->fetch(true)]);
	}

    public function emitit()
    {  
        $this->engine->render("licenca/emitir",[
            "comp"=>(new Generic())->tableConst("empresa")->find()->fetch(),
            "parceiros"=>json_decode($this->fazerRequisicao(API))
        ]);
    }

    public function activar($data)
    {
        
        if (empty($data["hash"])) {
            echo json_encode(["error"=>"O Campo codigo Vazio"]);
            return;
        }
        $url=API."activar";
        $retorn=json_decode($this->fazerRequisicao($url,"POST",$data));


        if (isset($retorn->error)) {
            echo json_encode(["error"=>$retorn->error]);
            return;
        }
        $this->activeL($retorn);
    }

 
    public function save($data)
    {
        

        $empresa=(new Generic())->tableConst("empresa")->find("id=:id","id={$data["id"]}")->fetch();
        $company=[];

    if (!isset($data["tipoLicenca"])) {
        echo json_encode(["error"=>"Selecione um tipo de Licença"]);
        return;
    }


    if (!isset($data["parceiro"])) {
        echo json_encode(["error"=>"Selecione um Parceiro"]);
        return;
    }

    if (empty($data["codigo"])) {
       echo json_encode(["error"=>"Campo Codigo do Parceiro Vazio"]);
        return;
    }

       $os=PHP_OS;
        if (stristr($os, "win")) {
    // Para Windows
    $cpu = shell_exec("wmic cpu get name");
    $disk = shell_exec('wmic logicaldisk get Caption,VolumeSerialNumber');
    $motherboard = shell_exec("wmic baseboard get product,manufacturer");
} elseif (stristr($os, "linux")) {
    // Para Linux
    $cpu = shell_exec("lscpu | grep 'Model name:'");
    $disk = shell_exec("df -h");
    $motherboard = shell_exec("cat /sys/class/dmi/id/board_vendor; cat /sys/class/dmi/id/board_name");
} else {
    $cpu = "Informação do processador indisponível para este sistema operacional.";
    $disk = "Informação do disco indisponível para este sistema operacional.";
    $motherboard = "Informação da placa-mãe indisponível para este sistema operacional.";
}
$cpu = trim($cpu);
$disk = trim($disk);
$motherboard = trim($motherboard);

   if (!$empresa) {
         echo json_encode(["error"=>"Erro: ao Obter dados do sistema, Contacte o Suporte"]);
        return;
            
    }


            $company=[
              "nome"=>$empresa->nome,
              "nif"=>$empresa->nif,
              "regime"=>$empresa->regime,
              "email"=>$data["email"],
              "telefone"=>$empresa->tel_um,
              "endereco"=>$empresa->cidade." ".$empresa->rua." ".$empresa->edificio,
              "parceiro"=>$data["parceiro"],
              "tipo_licenca"=>$data["tipoLicenca"],
              "obs"=>$data["observacao"],
              "data"=>date("Y-m-d"),
              "hora"=>date("H:i:s"),
              "modulo"=>"POS",
              "PC_nome"=>gethostname(),
              "sistema_operativo"=>PHP_OS,
              "processador"=>$cpu,
              "hd"=>$disk,
              "placa"=>$motherboard,
              "provicia"=>$data["cidade"],
              "cidade"=>$data["cidade"]
            ];
            $auth=[
                "parceiro"=>$data['parceiro'],
                "chave"=>AsymmetricCrypto::encrypt($data["codigo"])
            ];
            
    
        $data=["acesso"=>$auth,"data"=>$company];
        //$encryptedMessage = AsymmetricCrypto::encrypt($message);
        $url=API."add";
        $retorno=json_decode($this->fazerRequisicao($url,"POST",$data));
        
        if (isset($retorno->error)) {
            echo json_encode(["error"=>$retorno->error]);
            return;
        }
        
        echo json_encode(["redirect"=>$retorno->redirect,]);
    }

 
/**
 * Faz uma requisição HTTP para um site externo.
 *
 * @param string $url URL do site externo.
 * @param string $method Método HTTP (GET ou POST).
 * @param array|null $data Dados para enviar no caso de requisições POST.
 * @param array $headers Cabeçalhos adicionais para a requisição.
 * @return string|false Retorna a resposta do site ou false em caso de erro.
 */
private function fazerRequisicao($url, $method = 'GET', $data = null, $headers = []) {
    // Inicializa o cURL
    $ch = curl_init();

    // Configura a URL
    curl_setopt($ch, CURLOPT_URL, $url);

    // Configura o método (GET ou POST)
    if (strtoupper($method) === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Converte os dados para formato de query
        }
    }

    // Configura os cabeçalhos adicionais
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    // Configurações adicionais do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retorna o resultado em vez de exibir na tela
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Segue redirecionamentos (se houver)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desabilita a verificação SSL (não recomendado para produção)

    // Executa a requisição
    $response = curl_exec($ch);

    // Verifica se houve erro
    if (curl_errno($ch)) {
        echo 'Erro no cURL: ' . curl_error($ch);
        curl_close($ch);
        return false;
    }

    // Fecha a conexão cURL
    curl_close($ch);

    // Retorna a resposta do site
    return $response;
}


private function activeL($data)
{

           $os=PHP_OS;
        if (stristr($os, "win")) {
    // Para Windows
    $cpu = shell_exec("wmic cpu get name");
    $disk = shell_exec('wmic logicaldisk get Caption,VolumeSerialNumber');
    $motherboard = shell_exec("wmic baseboard get product,manufacturer");
} elseif (stristr($os, "linux")) {
    // Para Linux
    $cpu = shell_exec("lscpu | grep 'Model name:'");
    $disk = shell_exec("df -h");
    $motherboard = shell_exec("cat /sys/class/dmi/id/board_vendor; cat /sys/class/dmi/id/board_name");
} else {
    $cpu = "Informação do processador indisponível para este sistema operacional.";
    $disk = "Informação do disco indisponível para este sistema operacional.";
    $motherboard = "Informação da placa-mãe indisponível para este sistema operacional.";
}
$cpu = trim($cpu);
$disk = trim($disk);
$motherboard = trim($motherboard);

$e=explode("\n", trim($disk));


$hd=explode("C:", str_replace(" ", "",$e[1]));

$p=explode("\n", trim($motherboard));
$placa=str_replace(" ", "",$p[1]);

 if ($data->pc==gethostname() && $data->hd==$hd[1] && $data->placa==$placa) {
      $this->addLice($data);
     
     return;
 }

 

 echo json_encode(["error"=>"Licença Invalida Code=11"]);

}

private function addLice($data){
     $pdo=(new Generic())->tableConst("ep_config");
     $find=$pdo->find()->fetch();

     if (!$find) {
          echo json_encode(["error"=>"Erro ao Activar Licença code=1"]);
          return;
     }

     $pdo->licenca=$data->code;
     $pdo->tempo=AsymmetricCrypto::encrypt($data->tempo);
     $pdo->status="Activo";
     $pdo->data_activacao=$data->data;
     if (!$pdo->updateGeneric("id_confi=:d","d={$find->id_confi}")) {
         echo json_encode(["error"=>"Erro ao Activar Licença code=2"]);
         return;
     }
     
   echo json_encode(["success"=>"Activado com Sucesso"]);
}

	

}