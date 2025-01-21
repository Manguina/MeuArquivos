<?php $this->extracts("dashibord/dashbord",["title"=>"Permissao"])?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
          <div class="card-body">
         <a href="<?=url("/user_acesso")?>">
          <button  class="btn mt-4 ms-2 float-end" style="background:#165CAF; color:#fff;font-size: 9pt;" id="addCard">VOLTAR</button></a>
        </div>
        </div>
  </div>


<div class="col-md-12">

  <?php if ($lista): ?>
    
    <?php foreach ($lista as $l): ?>
      
   <?php if ($l->url==""): ?>
    
 <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-shield-lock me-2"></i>
                            Gerenciamento de Permissões do Menu <?=$l->ds_lista?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mt-4">
                            <table class="table table-hover id">
                                <thead >
                                    <tr>
                                        <th style="width:90%;">Descrição</th>
                                        <th></th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (listarSubmenu($l->id_per)): ?>
                                      <?php foreach (listarSubmenu($l->id_per) as $sub): ?>
                                        <tr>
                                          <td><?=$sub->ds_lista?></td>
                                          <td>
                                            <div class="custom-checkbox">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="checkbox3" data-id="<?=$sub->id_per?>" data-tipo="Filho" onclick="save(this)">
            </div>
        </div>
                                          </td>
                                        </tr>
                                      <?php endforeach ?>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">

                    </div>
                </div> 
                 <?php else: ?>
                  <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-shield-lock me-2"></i>
                            Gerenciamento de Permissões de <?=$l->ds_lista?>
                            <button class="btn btn-success" data-id="<?=$l->id_per?>" data-tipo="Pai" onclick="save(this)">Adicionar</button>
                        </h4>
                    </div>
                    
                    <div class="card-footer">

                    </div>
                </div> 

                <?php endif ?>  
                 <?php endforeach ?>
                <?php endif ?>
</div>

</div>
<?=$this->insert("/permissao/views/modal")?>

<?=$this->start("js")?>
<script type="text/javascript">
   
   function save(e)
   {
      const id = e.getAttribute('data-id');
      const nome = e.getAttribute('data-tipo');
      const grupo='<?=$id?>';
      const url='<?=url("/user_acesso/addci")?>'

      const dados={
        id:id,
        tipo:nome,
        grupo:grupo
      }
       $.ajax({
        url:url,
        data:dados,
        type:"POST",
        dataType:'json',
        success: function (response) {     
                 
             if (response.success) {

            Swal.fire({
            title: "Sucesso!",
            text: response.success,
            icon: "success"
            });

            } 

          if (response.error) {

          Swal.fire({
          title: "Erro Fatal",
          text: response.error,
          icon: "error"
          });
          }
        },
    });
   }
</script>
<?=$this->end()?>
