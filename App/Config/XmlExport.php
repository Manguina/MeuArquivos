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

class XmlExport extends Controller
{
	private $saft;
    private $errorSaft;
	function __construct()
	{
		if (!User::userLogado()) 
		{
            redirect("/entrar");
         }

         parent::__construct("erros");
           //$this->session->set("SaftXml",null);
	}

	public function home()
	{
		$this->engine->render("exporta/home");
	}



	public function addLoad()
	{

		
		if (!$this->uploadAndValidateXML($_FILES['xml_file'])) {
			echo json_encode(["error"=>$this->errorSaft]);
			return;
		}
         
         $cliente=$this->saft->MasterFiles->Customer;
         $produto=$this->saft->MasterFiles->Product;
         $invoice=$this->saft->SourceDocuments->SalesInvoices;
         $workDocument=$this->saft->SourceDocuments->WorkingDocuments;

         $clie=$this->view->v("exporta/cliente",["dados"=>$cliente]);
         $prod=$this->view->v("exporta/produto",["dados"=>$produto,"doc"=>$invoice]);
         $invo=$this->view->v("exporta/fatura",["dados"=>$invoice->Invoice,"cliente"=>$cliente]);

         echo json_encode(["cliente"=>$clie,"produto"=>$prod,"fatura"=>$invo]);
        // $this->saveXmlToCookie($this->saft);
		 
	}

    public function saveSaft($data)
    {
        if (isset($data['cliente'])) {
            $this->saveCostumer($data['cliente']);
        }

        if (isset($data['produto'])) {
             $this->saveProduct($data['produto']);
        }
     
    }

private function uploadAndValidateXML($file) {
    // Configurações para o upload
    $maxFileSize = 2 * 1024 * 1024; // Tamanho máximo do arquivo (2 MB)
    $allowedMimeTypes = ['application/xml', 'text/xml']; // Tipos MIME permitidos

    // Verifica se o arquivo foi enviado sem erros
    if ($file['error'] !== UPLOAD_ERR_OK) {
        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $this->errorSaft="O arquivo excede o limite de tamanho permitido.";
        		 return false;
            case UPLOAD_ERR_PARTIAL:
                $this->errorSaft="Upload do arquivo interropido.";
        		 return false;
            case UPLOAD_ERR_NO_FILE:
                 $this->errorSaft="Nenhum Arquivo Foi Enviado.";
        		 return false;
            default:
                $this->errorSaft="Problema desconhecido ao enviar o arquivo.";
        		return false;
        }
    }

    // Verifica se o tamanho do arquivo é menor que o limite permitido
    if ($file['size'] > $maxFileSize) {
        $this->errorSaft="O arquivo excede o tamanho máximo permitido de 2 MB.";
        return false;
    }

    // Verifica a extensão do arquivo
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($fileExtension !== 'xml') {
        $this->errorSaft="A extensão do arquivo deve ser .xml.";
        return false;
    }

    // Verifica o tipo MIME para garantir que seja XML
    $fileMimeType = mime_content_type($file['tmp_name']);
    if (!in_array($fileMimeType, $allowedMimeTypes)) {
        $this->errorSaft="O arquivo não possui um tipo MIME válido para XML";
        return false;
    }

    // Tenta carregar o conteúdo do arquivo como XML
    try {
        // Carrega o conteúdo do arquivo
        $xmlContent = file_get_contents($file['tmp_name']);
        
        // Tenta interpretar o conteúdo como XML
        libxml_use_internal_errors(true); // Suprime erros padrão do XML
        $xml = new \SimpleXMLElement($xmlContent);
        libxml_clear_errors(); // Limpa os erros depois de validar

        $this->saft=$xml;
       // $this->saveXmlToCookie($xml);
        // Se o parsing for bem-sucedido, o arquivo é XML válido
        return true;
    } catch (Exception $e) {
        // Caso ocorra uma exceção, o arquivo não é um XML válido
        $this->errorSaft="O arquivo não é um XML válido.";

        return false;
    }

}

// Função para ler XML do cookie
private function getXmlFromCookie() {
    if (!isset($_COOKIE['xml_data'])) {
        die("Nenhum XML encontrado no cookie.");
    }
   // var_dump(base64_decode($_COOKIE['xml_data']));
    return base64_decode($_COOKIE['xml_data']); // Decodificar de Base64
}

private function saveCostumer($data)
{
    if ($data) 
    {
        $psd = new Pessoa(); // Instância da classe Pessoa
       
        foreach ($data as $d) {
            // Verifica se já existe um registro com o mesmo nome
            $exists = $psd->find("nome_compl=:d", "d={$d['nome']}")->fetch();

            // Se já existir, pula para o próximo item
            if ($exists) {
                continue;
            }

            // Caso contrário, realiza o cadastro
            $this->custummerSing($d);
        }
    }
}


private function custummerSing($d)
{  
        $ps=(new Pessoa());
        $ps->nome_compl=$d["nome"];
        $ps->sexo="indefinido";
        $ps->bi=$d["nif"];
        $ps->telefone= "Não Especificado";
        $ps->email="";
        $ps->data_nascimento=date("Y-m-d");
        $ps->nacionalidade=date("Y-m-d");
        $ps->pais=$d["pais"];
        $ps->provincia=$d["cidade"];
        $ps->cidade=$d["cidade"];
        $ps->rua=$d["endereco"];
        $ps->edificio="";
        $ps->tipo="Cliente";
        $ps->entidade="Singular";

        if (!$ps->save()) {
            $js["error"]=$ps->message()->getText();
            echo json_encode($js);
            return false;
        }

        return true;
}

private function saveProduct($dados)
{
    if ($dados) {
       foreach ($dados as $k) {
           $this->productSingle($k);
       }
    }
}

private function productSingle($data)
{
      $product= (new Product());

      $pro=(new Product())->find("descricao=:d","d={$data["descricao"]}")->fetch();
 if (!$pro) {
           
       $product->descricao=$data["descricao"];
       $product->codigo_barra="";
       $product->codigo_ucf="";
       $product->marca="sem marca";
       $product->modelo="sem modelo";
       $product->perce_iva=(!empty($data["taxa"]) ? $data["iva"] : 0);
       if ($data["taxa"]==0) {
           $product->codigo_iva=$this->motivo($data["motivo"]);
           $product->motiv_izecao=$data["motivo"];
       }
       $product->custo= 0;
       $product->preco_sem_iva=$data["preco"];
       $product->valor_iva=$product->ivaPrice($product->perce_iva, $data["preco"]);
       $product->min=(!empty($data["stoq_min"]) ? $data["stoq_min"] : 0);
       $product->mix=(!empty($data["stoq_max"]) ? $data["stoq_max"] : 0);
       $product->lucros=($product->preco_sem_iva-$product->custo);
       $product->preco_venda=$product->priceVenda($data["preco"],$product->ivaPrice($product->perce_iva,$data["preco"]));;
       $product->categoria="Geral";
       $product->tipo=$data["tipo"];
       $product->metro=(isset($data["metro"]) ? 's' : 'n'); 
       $product->balanca=(isset($data["balanca"]) ? 's' : 'n');
       $product->confencionavel=(isset($data["confencionavel"]) ? 's' : 'n');
       $product->expira=(isset($data["expira"]) ? 's' : 'n');
       $product->p_venda=(isset($data["p_venda"]) ? 's' : 'n');
       $product->n_venda=(isset($data["n_venda"]) ? 's' : 'n');

       if (!$product->save()) {
        //echo json_encode(["error"=>$product->message()->getText()]);
         return false;
       }
    }
    return true;
}

private function motivo(string $motivo)
 { 

        $motivo=(new Motivo())->find("motivo=:c","c={$motivo}")->fetch();
        return $motivo->codigo;
    }

private function saveXmlToCookie($xmlString) 
{
    $_SESSION['SaftXml']=$xmlString;

    var_dump($_SESSION['SaftXml']);
    return $this->session->set("SaftXml",$xmlString);
}

private function saveInvoice($ivoice)
{

}

    public function line($pagamento,$data)
    {

    $c=factura();
      
   

    $pagament=(new Pagament());
    $hash=(new CryptHash());
    $hash->codHash=$data["InvoiceNo"];
     $totalGeral=0;
     $conta="";
      if ($pagamento) {
            $product= (new Product())->find("descricao=:d","d={$pagamento['ProductDescription']}")->fetch();
            $qtd=explode(".", $pagamento['Quantity']);
            $pagament->id_produto=$product->id_produto;
            $pagament->id_operador=$ca->id_user;
            $pagament->id_cliente=$data['CustomerID'];
            $pagament->descricao=$pagamento['ProductDescription'];
            $pagament->qtd=$qtd[0];
            $pagament->taxa_iva=$product->perce_iva;
            $pagament->cod_iva=$product->codigo_iva;
            $pagament->pre_sem_iva=$ca->total - $product->ivaPrice($product->perce_iva,$ca->total);
            $pagament->val_iva=$product->ivaPrice($product->perce_iva,$ca->total);
            $pagament->preco_unit=$product->preco_venda;
            $pagament->desconto=0;
            $pagament->valor_pago=$data["GrossTotal"];
            $pagament->troco=0;
            $pagament->total=$data["GrossTotal"];
            $pagament->pagamento="Numerario";
            $pagament->doc_tipo=$this->tipoDoc($data["InvoiceType"]);
            $pagament->numero_doc=$data["InvoiceNo"];
            $pagament->estado="Finalizado";
            $pagament->hora="Finalizado";
            $pagament->referencia=isset($pagamento['Reference']) ? $pagamento['Reference'] : '';
            $pagament->data_venda="Finalizado";
            $totalGeral+=0;
            if (!$pagament->save()) {
                echo json_encode(["error"=>"Erro ao Finalizar a Venda"]);
                return;
            }


         $hash->saves();
         //echo json_encode(["redirect"=>url("/pos/print/".base64_encode($pagamento["nun_doc"]))]);

         return true;
      }

      return false;
    }

    private function tipoDoc($tipo)
    {
        $ret="";
        if ($tipo=="FT") {
             $ret=="FACTURA";
        }

        if ($tipo=="FR") {
            $ret=="FACTURA-RECIBO";
        }

        if ($tipo=="PP") {
            $ret=="PRO-FORMA";
        }

        if ($tipo=="OR") {
            $ret=="ORCAMENTO";
        }

        if ($tipo=="NC") {
            $ret=="NOTA-CREDITO";
        }

        if ($tipo=="RC") {
            $ret=="RECIBO";
        }
    }

}