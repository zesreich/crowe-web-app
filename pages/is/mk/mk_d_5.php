<?php 
$MK5Prs = $prosedurs[mkConfig::MK5];
?>
<div class="card shadow mb-1">
    <?php 
    foreach ($MK5Prs as $p){
        $prosedurKod = mkConfig::MK5;
        include 'mk_d_prosedur.php';
    }
    ?>
</div>