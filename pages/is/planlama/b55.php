<div class="card shadow mb-1">
    <div class="card-body">
    	<nav >
          	<div class="nav nav-tabs "	id="nav-tabiki" role="tablist">
            	<a class="nav-item nav-link mk mr-1 text-center active" data-toggle="tab" id="b5501_btn"	role="tab" href="#nav-b5501" >B55.01</a>
            	<a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b5502_btn"	role="tab" href="#nav-b5502" onclick="sayfaYukle('<?=mkConfig::B55_02?>')">B55.02</a>
			</div>
       	</nav>
        <div class="border">
            <div class="tab-content m-3" id="nav-tabContent">
            	<div class="tab-pane fade show active"	id="nav-b5501" 	role="tabpanel" ><?php include 'b55-01.php';?></div>
            	<div class="tab-pane fade show"			id="nav-b5502" 	role="tabpanel" ><?php include 'b55-02.php';?></div>
          	</div>
    	</div>
    </div>
</div>