<?php 

$mk1Prs = $prosedurs[mkConfig::MK1]; //= Crud::getSqlCok(new Prosedur(), Prosedur::GET_TEKLIF_BY_GRUP, array('tklf_id'=>$_GET['id'],'grup'=>'MK1'));

?>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

input:checked + .slider {
  background-color: #ff0000;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

</style>

<div class="card shadow mb-1">
    <?php 
    foreach ($mk1Prs as $p){
        $prosedurKod = mkConfig::MK1;
        include 'mk_d_prosedur.php';
    }
    ?>
</div>