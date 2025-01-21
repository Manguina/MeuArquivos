<?php $this->extracts("dashibord/dashbord",["title"=>"Configuração-Definições Gerais"])?>
<div class="row">
	<div class="col-md-12"></div>
	<div class="col-md-6 col-sm-12">
		<div class="row">
			<div class="col-md-12">
				<div class="section">
			      <h2>Impostos</h2>
			      <label for="tempo-inventarios">Rentação na Fonte:</label>
			      <input type="number" id="retencao" name="retencao" value="<?=$rentecao->percentagem?>"><br>
			      <label for="prazo-facturas">Valor Minimo a Reter</label>
			      <input type="text" id="prazo-facturas" value="<?=str_price($rentecao->valor)?>"><br>
			     <button class="btn btn-primary">Alterar</button>
    			</div>
			</div>

			<div class="col-md-12">
				<div class="section">
					<h2>Modelo de Facturas</h2>
					<div class="form-group row">
						<div class="col-md-8">
							 <label for="impressora-a4">Modelo</label>
						      <select id="impressora-a4" name="template">
						       <?php if ($template): ?>
						       	 <?php foreach ($template as $key => $t): ?>
						       	 	<option><?=$t->ds_template?></option>
						       	 <?php endforeach ?>
						       <?php endif ?>
						      </select>
						</div>
						<div class="col-md-4">
							<button class="btn btn-primary mt-4">Prê Visualizar</button>
						</div>
					</div><br>
					<button class="btn btn-success">Salvar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="row">
			<div class="col-md-12">
				 <div class="section">
			      <h2>Dispositivos e Impressoras</h2>
			      <label for="impressora-a4">Impressora padrão (A4):</label>
			      <select id="impressora-a4">
			        <option value="pdf">Microsoft Print to PDF</option>
			      </select><br>
			      <label for="impressora-ticket">Impressora padrão (Ticket):</label>
			      <select id="impressora-ticket">
			        <option value="pdf">Microsoft Print to PDF</option>
			      </select><br>
			      <label for="padrao-impressao">Padrão de impressão:</label>
			      <select id="padrao-impressao">
			        <option value="A4">A4</option>
			        <option value="ticket">Ticket</option>
			      </select><br>
			      <label for="numero-vias">Número de vias na impressão de facturas e/ou recibos:</label>
			      <input type="number" id="numero-vias" value="1"><br>
			      <input type="checkbox" id="sistema-impressao">
			      <label for="sistema-impressao">Sistema de impressão sem visualização</label><br>
			      <button class="btn btn-success">Salvar</button>
               </div>
			</div>
		</div>
	</div>
</div>
<?=$this->start("css")?>
<style type="text/css">
    body {
  font-family: Arial, sans-serif;
  background-color: #f0f8ff;
  margin: 0;
  padding: 20px;
}

.container {
  max-width: 1200px;
  margin: auto;
  background: #fff;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.section {
  margin-bottom: 20px;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background-color: #f9f9f9;
}

.section h2 {
  margin-top: 0;
  color: #333;
  font-size: 16px;
  border-bottom: 1px solid #ddd;
  padding-bottom: 5px;
}

label {
  font-size: 14px;
  color: #333;
}

input[type="text"], input[type="number"], select {
  width: 300px;
  padding: 5px;
  margin-top: 5px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

input[type="checkbox"] {
  margin-right: 5px;
}

  </style>
<?=$this->end()?>