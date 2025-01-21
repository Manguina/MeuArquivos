<?php
namespace Source\Model;

use Source\Core\Db;
use Source\Core\Session;

class Motivo extends Db
{
	
	function __construct()
	{
		parent::__construct("iva",["cat_nome"]);
	}


	
	
}