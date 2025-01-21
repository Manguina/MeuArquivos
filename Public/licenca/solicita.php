<?php $this->extracts("licenca/theme",["title"=>"Solicitar Licença","grupo"=>"Solicitar Licença","tipo"=>""])?>
<div class="card" style="margin: 2rem;">
        <div class="card-header" style="background: linear-gradient(to right, #2BAEE8, #052159FA);">
    <h1 class="text-center mb-4" style="color: #fff;">MINHAS LICENÇAS</h1>
    </div>
    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th scope="col">Tipo de Licença</th>
            <th scope="col">Tempo Restante</th>
            <th scope="col">Status</th>
        </thead>
        <tbody>
          <?php if ($modulo): ?>
            <?php foreach ($modulo as $m): ?>
              <tr>
            <td><?=$m->ds_modulo?></td>
            <td>0</td>
            <td><i class="bi bi-x-circle-fill text-danger"></i></td>
          </tr>
            <?php endforeach ?>
          <?php endif ?>
         
        </tbody>
      </table>
    </div>
    <div class="card-body">
    <div class="d-flex justify-content-between my-4">
     <a href="<?=url("/licenca/solicitar")?>"> <button class="btn btn-primary">Solicitar Licença (Angola)</button></a>
      <button class="btn btn-primary">Solicitar Licença (Internacional)</button>
      <button class="btn btn-primary">Pacote Deskiline</button>
    </div>
    <div class="form-group">
      <label for="activationCode">Insira a baixo o Código de Ativação da Licença Retirada do Portal</label>
      <input type="text" class="form-control" id="activationCode" placeholder="Código de Ativação">
    </div>
    <div class="d-flex justify-content-end my-4">
      <button class="btn btn-success me-2" id="activar">Ativar</button>
      <button class="btn btn-danger">Fechar</button>
    </div>
    <div class="d-flex justify-content-between my-4">
      <button class="btn btn-primary">Licenças Automáticas</button>
      <button class="btn btn-primary">Licenças Automáticas (Internacional)</button>
    </div>
    </div>
    </div>

    <?php $this->start("css")?>
<style>
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 25px 0;
            font-family: Arial, sans-serif;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .custom-table thead tr {
            background: linear-gradient(to right, #2BAEE8, #052159FA);
            color: white;
            text-align: left;
        }

        .custom-table th {
            padding: 15px;
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 500;
        }

        .custom-table th:last-child {
            border-right: none;
        }

        .custom-table td {
            padding: 15px;
            border-right: 1px solid #e0e0e0;
            color: #666666;
        }

        .custom-table td:last-child {
            border-right: none;
        }

        .custom-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .custom-table tbody tr:nth-of-type(even) {
            background-color: #fafafa;
        }

        .custom-table tbody tr:last-of-type {
            border-bottom: 2px solid #2BAEE8;
        }

        .custom-table tbody tr:hover {
            background-color: #f8f8f8;
            transition: all 0.3s ease;
        }

        @media (max-width: 500px) {
            .custom-table {
                font-size: 14px;
            }
            
            .custom-table th,
            .custom-table td {
                padding: 10px;
            }
        }
        .ft-11{
          font-size: 12px;
        }
    </style>
<?php $this->end()?>

<?php $this->start("js") ?>
<script type="text/javascript">
    $(document).ready(function () {
    $('#activar').on('click', function (e) {
        e.preventDefault(); // Previne o envio padrão do formulário
         const url="<?=url("/licenca/activar")?>"
        // Captura os dados do formulário
        let formData = {
            hash: $('#activationCode').val()
        };

        // Envia os dados via AJAX
        $.ajax({
            url: url, // Substitua pelo seu arquivo PHP
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Exibe mensagem de sucesso com SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                    });
                } else if (response.error) {
                    // Exibe mensagem de erro com SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: response.error,
                    });
                }
            },
            error: function () {
                // Trata erros de requisição
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Não foi possível processar a solicitação.',
                });
            }
        });
    });
});

</script>
<?php $this->end()?>