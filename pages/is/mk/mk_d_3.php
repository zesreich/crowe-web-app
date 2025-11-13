<?php 
$MK3Prs = $prosedurs[mkConfig::MK3];
?>
<div class="card shadow mb-1">
    <?php 
    foreach ($MK3Prs as $p){
        $prosedurKod = mkConfig::MK3;
        include 'mk_d_prosedur.php';
    }
    ?>
</div>