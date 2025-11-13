<div class="card shadow mb-1">
    <div class="card-body">
    	<nav >
          	<div class="nav nav-tabs "	id="nav-tabiki" role="tablist">
            <?php 
                foreach (mkConfig::B40_LIST  as $pln){
                    echo '<a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b00_btn"	role="tab" href="#nav-a'.str_replace(".","",$pln[0]).'" onclick="prosedurYukle(\''.$pln[0].'\')">'.$pln[0].'</a>';
                }
            ?>
			</div>
       	</nav>
        <div class="border">
            <div class="tab-content m-3" id="nav-tabContent">
            <?php 
                foreach (mkConfig::B40_LIST  as $pln){
                    $prosedurKod = $pln[0];
                    $prosedurs   = $pros[$prosedurKod];
                    echo '<div class="tab-pane fade " id="nav-a'.str_replace(".","",$pln[0]).'" role="tabpanel" >';
                    echo '<div class="text-center"><h5>'.$pln[1].'</h5></div>';
                    foreach ($prosedurs as $p){
                        include 'plan_prosedur.php';
                    }
                    echo '</div>';
                }
            ?>
          	</div>
    	</div>
    </div>
</div>