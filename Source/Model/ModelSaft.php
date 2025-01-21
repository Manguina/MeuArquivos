<?php
namespace Source\Model;

/**
 * 
 */
use Source\Core\SaftConnect;
class ModelSaft extends SaftConnect
{
	
   

    public function __construct() {
       
       parent::__construct("venda");
    }


    /**
     * Funcao que conta o total de documentos no SalesInvoices
     * @param string $data
     * @param string $dataEnd
     * @return int $conta
     * **/

    public function numberOfEntries($data,$findEnd)
    {
        $sa=$this->totalDoc("doc_tipo!='RECIBO' AND doc_tipo!='PRO-FORMA' AND doc_tipo!='ORCAMENTO' AND data_venda BETWEEN '{$data}' AND '{$findEnd}'");

        return $sa;
    }

    public function numberOfEntriesW($data,$findEnd)
    {
        $sa=$this->totalDoc("doc_tipo='ORCAMENTO' OR doc_tipo='PRO-FORMA' AND data_venda BETWEEN '{$data}' AND '{$findEnd}'");

        return $sa;
    }

    /**
     * Funcao que conta o total valores a credito documentos no SalesInvoices
     * @param string $data
     * @param string $dataEnd
     * @return int $conta
     * **/

    public function totalCredit($data,$findEnd)
    {
        $sa=$this->totalCredito("doc_tipo!='NOTA-CREDITO' AND doc_tipo!='RECIBO' AND doc_tipo!='PRO-FORMA' AND doc_tipo!='ORCAMENTO' AND  data_venda BETWEEN '{$data}' AND '{$findEnd}'","SUM(pre_sem_iva) AS total");

        return $sa;
    }


    public function totalCreditW($data,$findEnd)
    {
        $sa=$this->totalCredito("doc_tipo='PRO-FORMA' OR doc_tipo='ORCAMENTO'  AND  data_venda BETWEEN '{$data}' AND '{$findEnd}'","SUM(pre_sem_iva) AS total");

        return $sa;
    }



    /**
     * Funcao que conta o total valores a credito documentos no SalesInvoices
     * @param string $data
     * @param string $dataEnd
     * @return int $conta
     * **/

    public function totalDebit($data,$findEnd)
    {
        $sa=$this->totalCredito("doc_tipo='NOTA-CREDITO'  AND data_venda BETWEEN '{$data}' AND '{$findEnd}'","SUM(pre_sem_iva) AS total");

        return $sa;
    }

    /**
     * Funcao que traz todos os documentos do sistema
     * @param string $consulta
     * @return array $conta
     * **/

    public function SourceDocuments($consulta)
    {
        $conta=$this->tudoData($consulta,$fild="*",$tabela="");
        
        return $conta;
    }

   

    /**
     * Funcao que traz hash dos documentos do sistema
     * @param string $consulta
     * @return string $hash
     * **/
   
    public function hashData(string $doc)
    {
         $hash=$this->table("document_key")->select("key_generation")->where("number_doc='$doc'")->fetch();
         $da="";
         if ($hash) {
            $da=$hash->key_generation;
         }

         return $da;
    }


    /**
     * Funcao que traz A REFERENCIA DA FACTURA NA NOTA DE CREDITO 
     * @param string $consulta
     * @return string $hash
     * **/


   
    public function reference(string $doc)
    {
         $hash=$this->table("nota_credito")->select("referencia")->where("codigo_nota='$doc'")->fetch();
         $da="";
         if ($hash) {
            $da=$hash->referencia;
         }

         return $da;
    }

     public function motivoIsencao(string $doc)
    {
         $hash=$this->table("iva")->select("razao")->where("codigo='$doc'")->fetch();
         $da="";
         if ($hash) {
            $da=$hash->razao;
         }

         return $da;
    }


    public function line(string $doc)
    {
         $hash=$this->select("*")->where("numero_doc='$doc'")->order("id_venda ASC")->fetch(true);
         $da=[];
         if ($hash) {
            $da=$hash;
         }

         return $da;
    }

    public function saldo(string $doc,$filtro)
    {
         $hash=$this->select("{$filtro} AS total")->where("numero_doc='$doc'")->order("id_venda ASC")->fetch(true);
         $da=0;
         if ($hash) {
            foreach ($hash as $k) {
               $da+=$k->total;
            }
         }

         return $da;
    }


     public function priceDroduct(string $id)
    {
         $hash=$this->table("produto")->select("*")->where("id_produto='$id'")->fetch();
         $da=0;
         if ($hash) {
            $da=$hash->preco_sem_iva;
         }

         return $da;
    }


    /**
     * Funcão que conta os numeros dos documentos
     * @param string $param
     * @param string | null $table
     * @param string or null $fild
     * @return int $data
     * **/

    private function totalDoc($param,$tabela="",$fild="*")
    {
         $data=0;
         if (!empty($tabela)) {
             $this->table($tabela)->select($fild)->where($param)->group("numero_doc")->fetch(true);
             if ($this->data()) {
                $data=count($data);
             }
             return  $data;
         }
        $this->select($fild)->where($param)->group("numero_doc")->fetch(true);

       
             if($this->data()) {

                $data=count($this->data());
             }
             return  $data;

    }

    /**
     * Funcão que conta total de valores (cedito|debito)
     * @param string $param
     * @param string | null $table
     * @param string or null $fild
     * @return float $data
     * **/

    private function totalCredito($param,$fild="*",$tabela="")
    {
         $data=0;
         if (!empty($tabela)) {
             $this->table($tabela)->select($fild)->where($param)->group("numero_doc")->fetch(true);
             if ($this->data()) {
               foreach ($this->data() as $value) {
                   $data+=$value->total;
               }
             }
             return  $data;
         }
        $this->select($fild)->where($param)->group("numero_doc")->fetch(true);

       
             if($this->data()) {

               foreach ($this->data() as $value) {
                   $data+=$value->total;
               }
             }
             return  $data;

    }


    /**
     * Funcão que traz todos os dados do sistema
     * @param string $param
     * @param string | null $table
     * @param string or null $fild
     * @return array $data
     * **/

    private function tudoData($param,$fild="*",$tabela="")
    {
         $data=[];
         if (!empty($tabela)) {
             $this->table($tabela)->select($fild)->where($param)->group("numero_doc")->fetch(true);
             if ($this->data()) {
              $data=$this->data();
             }
            
         }
        $this->select($fild)->where($param)->group("numero_doc")->fetch(true);

             if($this->data()) {

                $data=$this->data();
             }
             return  $data;

    }
   
   
}

