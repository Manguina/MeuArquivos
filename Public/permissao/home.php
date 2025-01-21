<?php $this->extracts("dashibord/dashbord",["title"=>"Permissao"])?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <button  class="btn mt-4 ms-2 float-end" style="background:#165CAF; color:#fff;font-size: 9pt;" id="addCard">CRIAR NOVO</button>
        </div>
        </div>
  </div>


<div class="col-md-12">

                <!-- Table with stripped rows -->
                <table class="custom-table" id="tblCarrio">
                  <thead>
                    <tr>
                      <th scope="col" style="width:90%;">Grupo</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                    <tbody id="tbl">
                     <?=$this->insert("/permissao/views/tabela",["listar"=>$listar])?>
                   </tbody>
                </table>
                <!-- End Table with stripped rows -->

    
          </div>

</div>
<?=$this->insert("/permissao/views/modal")?>

<?=$this->start("js")?>
<script type="text/javascript">
   $('#addCard').on('click', function() {
      document.getElementById("tilteM").innerHTML="Cadastrar Grupo de Acesso";
       document.getElementById("addBody").innerHTML=`<?=$this->insert("/permissao/views/cada")?>`;
        $('#largeModal').modal('show');
    });


   function jotaA(){
    const form= document.getElementById("ajaxForm");
            const url=form.getAttribute('action');
            const formData = new FormData(form);
       
      fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        
        if (data.error) {
        Swal.fire({
          icon: "warning",
          title: "Atenção",
          text: data.error,
          footer: ''
        });

        }


        if (data.html) {
        $("#largeModal").modal('hide');
            Swal.fire({
          icon: "success",
          title: "Sucesso",
          text: "Cadastrado Realizado Com Sucesso",
          footer: ''
        });
             document.getElementById("tbl").innerHTML=data.html;
        }

        if (data.sucesso) {
            Swal.fire({
          icon: "success",
          title: "Sucesso",
          text: "Cadastrado Realizado Com Sucesso",
          footer: ''
        });
         window.location.href=data.sucesso;
        }

        

         if (data.htmlplus) {

         $("#largeModal").modal('hide');

         document.getElementById("tbl").innerHTML=data.htmlplus;


     }


        if (data.success) {

            Swal.fire({
          icon: "success",
          title: "Sucesso",
          text: data.success,
          footer: ''
        });

        const select = document.getElementById('meuSelect');
        const dados=data.data;
         // Limpar qualquer opção existente, exceto a primeira
                select.innerHTML = '<option value="">Selecione...</option>';

         dados.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.nome;
                    select.appendChild(option);
                });


        }
      
    })
    .catch(error => {
       Swal.fire({
          icon: "error",
          title: "erro",
          text: error,
          footer: ''
        });
    });
   }
</script>
<?=$this->end()?>
