<?php 
include_once '../../db/Crud.php';
echo $_GET['id'];
$list = Crud::selectSqlWithPrm(MKRefs::GET_BY_ID, array('id'=>$_GET['id']))[0];
print_r($list);
?>
<div class="col-12">
	<table  class="table table-bordered" >
		<thead>
			<tr>
				<th style="width: 35%" />
				<th style="width: 65%" />
			</tr>
		</thead>
		<tbody>
        	<tr class="listeEleman" >
            	<td class="text-center align-middle">Açıklama</td>
            	<td class="text-center align-middle"><?=$list['aciklama'] ?></td>
	        </tr>
        	<tr class="listeEleman" >
            	<td class="text-center align-middle">Grup</td>
            	<td class="text-center align-middle"><?=$list['pGrup'] ?></td>
	        </tr>
        	<tr class="listeEleman" >
            	<td class="text-center align-middle">Kod</td>
            	<td class="text-center align-middle"><?=$list['pKod'] ?></td>
	        </tr>
        	<tr class="listeEleman" >
            	<td class="text-center align-middle">Risk Grup</td>
            	<td class="text-center align-middle"><?=$list['grup'] ?></td>
	        </tr>
        	<tr class="listeEleman" >
            	<td class="text-center align-middle">Risk Kod</td>
            	<td class="text-center align-middle"><?=$list['kod'] ?></td>
	        </tr>
	        <tr class="listeEleman" ><td class="text-center align-middle">Var olma					</td><td class="text-center align-middle"><?= ($list['bilanco_var_olma']           == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>     
            <tr class="listeEleman" ><td class="text-center align-middle">Haklar ve zorunluluklar	</td><td class="text-center align-middle"><?= ($list['bilanco_haklar_ve_zorunlu']  == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
            <tr class="listeEleman" ><td class="text-center align-middle">Tamlık                    </td><td class="text-center align-middle"><?= ($list['bilanco_tamlik']             == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
            <tr class="listeEleman" ><td class="text-center align-middle">Değerleme ve tahsis       </td><td class="text-center align-middle"><?= ($list['bilanco_deger_ve_tahsis']    == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
            <tr class="listeEleman" ><td class="text-center align-middle">Meydana gelme             </td><td class="text-center align-middle"><?= ($list['gelir_meydana_gelme']        == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
            <tr class="listeEleman" ><td class="text-center align-middle">Tamlık                    </td><td class="text-center align-middle"><?= ($list['gelir_tamlik']               == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
            <tr class="listeEleman" ><td class="text-center align-middle">Doğruluk                  </td><td class="text-center align-middle"><?= ($list['gelir_dogruluk']             == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
            <tr class="listeEleman" ><td class="text-center align-middle">Cutoff                    </td><td class="text-center align-middle"><?= ($list['gelir_cutoff']               == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
            <tr class="listeEleman" ><td class="text-center align-middle">Sınıflandırma             </td><td class="text-center align-middle"><?= ($list['gelir_siniflandirma']        == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
            <tr class="listeEleman" ><td class="text-center align-middle">Meydana gelme             </td><td class="text-center align-middle"><?= ($list['sunum_meydana_gelme']        == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
            <tr class="listeEleman" ><td class="text-center align-middle">Tamlık                    </td><td class="text-center align-middle"><?= ($list['sunum_tamlik']               == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
            <tr class="listeEleman" ><td class="text-center align-middle">Sınıflandırma ve anlaş.   </td><td class="text-center align-middle"><?= ($list['sunum_sinif_ve_anlas']       == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
            <tr class="listeEleman" ><td class="text-center align-middle">Doğruluk ve değerleme     </td><td class="text-center align-middle"><?= ($list['sunum_dogruluk_ve_deger']    == 'E' ? '<i class="fas fa-check"></i>' : '' )?></td></tr>
		</tbody>
	</table>
</div>