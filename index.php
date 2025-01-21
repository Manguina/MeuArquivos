<?php

ob_start();

require __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Router\Router;

 
$router = new Router(url());

$router->namespace("App");
$router->get("/", "Dashibord:home");
//$router->get("/home", "Dashibord:empresa");
$router->get("/entrar", "Login:home");
$router->get("/recupera", "Login:recupera");
$router->post("/entrar", "Login:login");
$router->post("/trocar", "Login:trocar");
$router->get("/sair", "Login:sair");
$router->get("/alterar_minha_senha", "Login:alterar");
$router->get("/atualiza", "StartSetup:atualiza");


$router->group("empresa")->namespace("App\\Company");
$router->get("/", "CompanyController:home");
$router->post("/save", "CompanyController:SaveData");
$router->post("/save_acess", "CompanyController:saved_acesss");


$router->group("usuario")->namespace("App\\User");
$router->get("/", "UserController:home");
$router->get("/cadastro", "UserController:store");
//$router->get("/alterar_minha_senha", "UserController:alterar");
$router->get("/inativos", "UserController:inativo");
$router->get("/editar/{id}", "UserController:store");
$router->get("/acesso/{code}", "UserController:saveAcess");
$router->post("/save", "UserController:SaveData");
$router->post("/acesso", "UserController:access");



$router->post("/eliminar", "UserController:del");
$router->post("/filtrar", "UserController:filtrar");
$router->post("/itens", "UserController:tipo");

$router->group("cliente")->namespace("App\\User");
$router->get("/", "ClintController:home");
$router->get("/cadastro", "ClintController:store");
$router->get("/inativos", "ClintController:inativo");
$router->post("/save", "ClintController:SaveData");
$router->get("/editar/{id}", "ClintController:store");
$router->post("/eliminar", "ClintController:del");
$router->post("/filtrar", "ClintController:filtrar");

$router->group("fornecedor")->namespace("App\\User");
$router->get("/", "FornecedorController:home");
$router->get("/cadastro", "FornecedorController:store");
$router->post("/save", "FornecedorController:SaveData");
$router->get("/editar/{id}", "FornecedorController:store");
$router->post("/eliminar", "FornecedorController:del");
$router->post("/filtrar", "FornecedorController:filtrar");

$router->group("produto")->namespace("App\\Product");
$router->get("/", "ProductController:home");
$router->get("/inativo", "ProductController:inativo");
$router->get("/cadastrar", "ProductController:store");
$router->post("/save", "ProductController:save");
$router->post("/filtrar", "ProductController:home");
$router->post("/eliminar", "ProductController:del");
$router->get("/editar/{code}", "ProductController:store");
$router->get("/relatorios", "ReportProduct:home");
$router->get("/listagem_precos", "ReportProduct:listagem");
$router->get("/mercadoria", "ReportProduct:movimento");
$router->get("/inventario", "ReportProduct:inventario");
$router->get("/servico", "ReportProduct:produto");

$router->group("inventario")->namespace("App\\Product");
$router->get("/", "InventarioController:home");
$router->get("/print/{id}", "InventarioController:print");
$router->post("/add", "InventarioController:addInvoice");
$router->post("/remove", "InventarioController:deleteIvoice");
$router->post("/finaliza", "InventarioController:save");
$router->post("/buscar_product", "InventarioController:filtrarInvent");


$router->group("entrada")->namespace("App\\Product");
$router->get("/", "EstoqueController:home");
$router->post("/filtrar", "EstoqueController:home");
$router->post("/produto", "EstoqueController:store");
$router->post("/save", "EstoqueController:save");


$router->group("categoria")->namespace("App\\Product");
$router->get("/", "CategoryController:home");
$router->post("/cadastrar", "CategoryController:store");

$router->group("marca")->namespace("App\\Product");
$router->get("/", "MarcaController:home");
$router->post("/cadastrar", "MarcaController:store");
$router->post("/eliminar", "MarcaController:delete");
$router->post("/buscar", "MarcaController:filtrar");
$router->post("/modelo", "MarcaController:marca");

$router->group("modelo")->namespace("App\\Product");
$router->get("/", "ModeloController:home");
$router->post("/cadastrar", "ModeloController:store");
$router->post("/eliminar", "ModeloController:delete");
$router->post("/buscar", "ModeloController:filtrar");

$router->group("banco")->namespace("App\\Finance");
$router->get("/", "BancoController:home");
$router->post("/cadastrar", "BancoController:store");
$router->post("/eliminar", "BancoController:delete");
$router->post("/buscar", "BancoController:filtrar");

$router->group("conta")->namespace("App\\Finance");
$router->get("/", "ContaController:home");
$router->post("/cadastrar", "ContaController:store");
$router->post("/eliminar", "ContaController:delete");
$router->post("/buscar", "ContaController:filtrar");

$router->group("forma_pagamento")->namespace("App\\Finance");
$router->get("/", "FormaPamentoController:home");
$router->post("/cadastrar", "FormaPamentoController:store");
$router->post("/eliminar", "FormaPamentoController:delete");
$router->post("/buscar", "FormaPamentoController:filtrar");



$router->group("imposto")->namespace("App\\Product");
$router->get("/", "ImpostoController:home");
$router->post("/cadastrar", "ImpostoController:store");

$router->group("caixa")->namespace("App\\Caixa");
$router->get("/", "CaixaController:home");
$router->get("/operacao", "CaixaController:store");
$router->get("/a4/{id}", "ReportCaixa:aberturaA4");
$router->get("/ticket/{id}", "ReportCaixa:aberturaTicket");
$router->get("/pdf_operacao", "ReportCaixa:operacoGeral");
$router->get("/historico/{id}", "CaixaController:historico");
$router->get("/turno", "CaixaController:turnoCaixa");
$router->post("/abrir", "CaixaController:abertura");
$router->post("/POS", "CaixaController:aberturaPos");
$router->post("/fecho", "CaixaController:fecho");
$router->post("/filtra", "CaixaController:turnoCaixa");
$router->post("/operacao_filtrar", "CaixaController:filterOperation");

$router->group("pos")->namespace("App\\Pos");
$router->get("/", "FacturaReciboController:home");
$router->get("/fatura", "FacturaReciboController:homeFact");
$router->get("/proforma", "FacturaReciboController:homePro");
$router->get("/orcamento", "FacturaReciboController:homeOr");
$router->post("/venda", "FacturaReciboController:vender");
$router->post("/divida", "FacturaReciboController:divida");
$router->get("/nota", "FacturaReciboController:nota");
$router->post("/orcamentos", "FacturaReciboController:orcamento");
$router->post("/proformas", "FacturaReciboController:proforma");
$router->post("/nota_credito", "FacturaReciboController:credito");
$router->post("/add", "FacturaReciboController:addCart");
$router->post("/remove", "FacturaReciboController:remove");
$router->post("/nota", "FacturaReciboController:nota");

//$router->post("/abrir", "CaixaController:abertura");

$router->get("/print/{code}", "Reports:FaturaRecibo");
$router->get("/segunda/{code}", "Reports:Factura");
$router->get("/ticket/{code}", "Reports:ticket");
$router->get("/recibo/{code}/{fatura}", "Reports:recibo");



$router->group("saft")->namespace("App\\Caixa");
$router->get("/", "SaftAoController:home");
$router->get("/ao", "SaftAoController:abertura");
$router->post("/gerar", "SaftAoController:gerar");

$router->group("financas")->namespace("App\\Finance");
$router->get("/recibo", "ReciboController:home");
$router->post("/finaliza", "ReciboController:recibo");
$router->post("/add_cliente", "ReciboController:conta");
$router->post("/find_cliente", "ReciboController:findInvoice");

$router->group("backup")->namespace("App\\Company");
$router->get("/", "BackupController:home");
$router->get("/restaurar", "BackupController:backup");
$router->post("/importar", "BackupController:restaurar");

$router->group("posto")->namespace("App\\Pos");
$router->get("/", "PostoVenda:home");
$router->get("/restaurar", "PostoVenda:backup");
$router->post("/fil_prod", "PostoVenda:findProduct"); 
$router->post("/add", "PostoVenda:addCart"); 
$router->post("/remove", "PostoVenda:remove");
$router->get("/fatura", "PostoVenda:homeFact");
$router->get("/proforma", "PostoVenda:homePro");
$router->get("/orcamento", "PostoVenda:homeOr");
$router->post("/add_qtd", "FacturaReciboController:addQtd");


$router->group("exportar")->namespace("App\\Config");
$router->get("/", "XmlExport:home");
$router->post("/upload", "XmlExport:addLoad");
$router->post("/salvar", "XmlExport:saveSaft");




// transferencia de produtos
$router->group("transferencia")->namespace("App\\Transferir");
$router->get("/", "TransferenciaP:home");
$router->post("/cadastrar-armazem", "TransferenciaP:savedArmazem");
$router->post("/transferir-produto-armazem", "TransferenciaP:trasnferirArmazem");

$router->post("/buscar-produto-armazem", "TransferenciaP:buscarProdutoId");
$router->post("/buscar-produto-tb-prod", "TransferenciaP:buscarProdutoTBProduto");

// cadastro de armazens

$router->group("armazem")->namespace("App\\Armazem");
$router->get("/", "Armazem:home");
$router->get("/saida-produtos-expirado", "Armazem:saidas_expirados");
$router->get("/historico-produto", "Armazem:historico_produto");

$router->post("/saida-produtos", "Armazem:saidaProduto");
$router->post("/saida-produtos-expirados", "Armazem:saidaProdutoexpi");

// fluxo do caixa
$router->group("financa")->namespace("App\\Finance");
$router->get("/", "Financas:home");
$router->get("/conta-corrente", "Financas:conta_corrente");
$router->post("/filtro-corrente", "Financas:conta_corrente_datas");
$router->post("/filtro-corrente-cliente", "Financas:conta_corrent_cliente");
$router->post("/corrente-cliente", "Financas:buscar_cliente");
$router->post("/filtro-fluxo-caixa", "Financas:conta_fluxo_datas");
//========================================


$router->group("licenca")->namespace("App\\Config");
$router->get("/", "AymmetricController:home");
$router->get("/solicitar", "AymmetricController:emitit");
$router->get("/atualiza", "StartSetup:atualiza");
$router->post("/salvar", "AymmetricController:save");
$router->post("/activar", "AymmetricController:activar");

$router->group("user_acesso")->namespace("App\\Config");
$router->get("/", "PermissaoController:home");
$router->get("/add/{id}", "PermissaoController:add");
$router->post("/cadastrar", "PermissaoController:save");
$router->post("/addci", "PermissaoController:saveUser");
$router->get("/update/{id}", "PermissaoController:add");

$router->group("definicoes")->namespace("App\\Config");
$router->get("/", "Definicoes:home");
$router->get("/preview/{tela}", "Definicoes:preview");
$router->post("/add_rentecao", "Definicoes:retencao");
$router->post("/add_tamplate", "Definicoes:tela");


/**rotas de erros**/
$router->group("error")->namespace("App");
$router->get("/{errcode}", "Error:notFound");

/**
 * This method executes the routes
 */
$router->dispatch();

/*
 * Redirect all errors
 */
if ($router->error()) {
   redirect("/error/{$router->error()}");
}

ob_end_flush();