 <?php if ($listar): ?>
                    
                  <?php foreach ($listar as $key): ?>
                      <tr>
                      <td style="font-size:11pt; color: #333;"><?=$key->ds_grupo?></td>
              <td>
                        
                <a href="<?=url("/user_acesso/update/".base64_encode($key->id_grupo))?>"> <button type="button" class="btn" title="Eliminar" data-id="<?=$key->id_grupo?>" data-url="" id="del">
                  <i class="ri-pencil-fill" style="color: #ffc107;"></i>
                </button> </a>

                <button type="button" class="btn" title="Eliminar" data-id="<?=$key->id_grupo?>" data-url="<?=url("/fornecedor/eliminar")?>" id="del">
                  <i class="ri-delete-bin-6-fill" style="color: red;"></i>
                </button>
                      </td>
                    </tr>
                  <?php endforeach ?>  
                  
                   <?php endif ?>