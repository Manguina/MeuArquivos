<form method="POST" action="<?=url("/user_acesso/cadastrar")?>" id="ajaxForm">
	<div class="row">
		<div class="col-md-12">
			<label for="inputName5" class="form-label">Descrição</label>
			<input type="text" class="form-control" name="ds_nome" value="" id="nome">
        </div>

        <div class="col-md-12 mt-4">
			<button class="btn w-100" type="button" onclick="jotaA(this)" style="background:#165CAF; color:#fff">Registrar</button>
        </div>

	</div>
</form>