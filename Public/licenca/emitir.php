<?php var_dump($parceiros); $this->extracts("licenca/theme",["title"=>"Solicitar Licença","grupo"=>"Solicitar Licença","tipo"=>""])?>
<div class="card" style="margin: 2rem;">
       <div class="container mt-5">
        <h1 class="mb-4">Formulário</h1>
        <?php if ($comp): ?>
          
       
        <form id="addLicenca" action="<?=url("/licenca/salvar")?>" method="POST">
            <div class="mb-3">
                <label for="empresa" class="form-label">Empresa:</label>
                <input type="text" class="form-control" id="empresa" name="empresa" placeholder="Digite o nome da empresa" required  value="<?=$comp->nome?>" disabled>
                <input type="hidden" name="id"  value="<?=$comp->id?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">NIF:</label>
<input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email" disabled value="<?=$comp->nif?>">
            </div>


            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email" required value="<?=$comp->email?>">
            </div>

            <div class="mb-3">
                <label for="pais" class="form-label">País:</label>
                <select class="form-select" name="pais">
                  <option selected>Angola</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade:</label>
                <select class="form-select" name="cidade">
            <option value="bengo">Bengo</option>
            <option value="bie">Bié</option>
            <option value="benguela">Benguela</option>
            <option value="cabinda">Cabinda</option>
            <option value="cunene">Cunene</option>
            <option value="hua">Huambo</option>
            <option value="huila">Huíla</option>
            <option value="kuando-kubango">Kuando-Kubango</option>
            <option value="kwanza-norte">Kwanza-Norte</option>
            <option value="kwanza-sul">Kwanza-Sul</option>
            <option value="luanda">Luanda</option>
            <option value="luena">Luena</option>
            <option value="lunda-norte">Lunda-Norte</option>
            <option value="lunda-sul">Lunda-Sul</option>
            <option value="malanje">Malanje</option>
            <option value="moxico">Moxico</option>
            <option value="namibe">Namibe</option>
            <option value="uige">Uíge</option>
            <option value="zaire">Zaire</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tipoLicenca" class="form-label">Tipo de Licença:</label>
                <select class="form-select" id="tipoLicenca" name="tipoLicenca" required>
                    <option disabled selected>Selecione</option>
                    <option value="profissional">Desktop</option>
                    <option value="enterprise">Web</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="parceiro" class="form-label">Parceiro:</label>
                <select name="parceiro" class="form-control">
                    <option disabled selected>Selecione Parceiro</option>
                    <?php if ($parceiros): ?>
                         <?php foreach ($parceiros as $p): ?>
                             <option ><?=$p[0]->username?></option>
                         <?php endforeach ?>
                    <?php endif ?>
                </select> 
            </div>

            <div class="mb-3">
                <label for="codigo" class="form-label">Código do Parceiro:</label>
                <input type="password" class="form-control" id="codigo" name="codigo" placeholder="Digite o código" required>
            </div>

            <div class="mb-3">
                <label for="observacao" class="form-label">Observação:</label>
                <textarea class="form-control" id="observacao" name="observacao" rows="4" placeholder="Digite uma observação"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
         <?php endif ?>
    </div>
</div>

<?php $this->start("js")?>
<script type="text/javascript">
  $("#addLicenca").submit(function(e) {
                e.preventDefault(); // Evita o envio padrão do formulário

                // Captura os dados do formulário
                const form=$(this);
                const url= form.attr('action');

                // Envia os dados via AJAX
                $.ajax({
                   url:url,
                  data:form.serialize(),
                  type:"POST",
                  dataType:'json',
                    success: (response) => {
                        if (response.error) {
                            // Exibe mensagem de erro com SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: response.error,
                    });
                        }

                    if (response.redirect) {
                        window.open(response.redirect, '_blank')
                    }
                    },
                    error: (xhr, status, error) => {
                        // Exibe mensagem de erro com SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Ocorreu um erro ao enviar o formulário',
                    });
    
                    }
                });
            });
</script>
<?php $this->end()?>