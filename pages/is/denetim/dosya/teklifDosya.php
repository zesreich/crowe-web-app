<?php
$pId = 199;
include_once '../../../../First.php';
if (isset($_GET['id'])){
    $id = $_GET['id'];
}else{
    hata('Parametreler eksik : id',PREPATH);
}
include_once PREPATH . 'header.php';
$tbl = Crud::getById(new Denetim() , $id ) -> basit();
?>

<div class="row" id="drive-box">
    <div class="col-lg-12 col-xl-12 pb-3">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">Teklif Dosyalar</h6>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
            		<div class="container col-12">
                		<div class="row">
                    		<div class="col-10 mb-2" >
                    			<input id="search" type="text" class="form-control form-control-user "  placeholder="Arama">
                    		</div>
                          	<div id="button-upload" class="col-2 mb-2">
                            	<a href="#"  class="btn btn-success col-lg-12" >
                            		<i class="fa fa-share"></i>
                            		<span id='upload-percentage' class="text">Resim yükle</span>
                                </a>
                            </div>
                    		<div  class="col-12">
                        		<table id="tablebot" class="table table-bordered table-striped" >
                        			<thead>
                        				<tr>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Dosya adı</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Son değişiklik Yapan</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">İndir</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Aç</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Sil</th>
                        				</tr>
                        			</thead>
                        			<tbody id="tableLst">
                        			</tbody>
                        		</table>
                    		</div>
                		</div>
            		</div>
            	</div>
            </div>
        </div>
    </div>
</div>
<input type="file" id="fUpload" class="hide" hidden/>

<div class="modal hide" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-body h-100 d-flex align-items-center">
        <i class='fas fa-sync fa-spin fa-3x mx-auto' style="color: #F8E6E0;"></i>
        
    </div>
</div>


<script >
    tableSirala("#tablebot");
    tableArama("#tablebot","#search");

	//Tablo sırala fonksiyonu
    function tableSirala(tbl){ 
	    $(tbl+' th').each(function (col) {
            $(this).click(function () {
        		$(tbl+" th i").remove();
                if ($(this).is('.asc')) {
                    $(this).removeClass('asc');
                    $(this).addClass('desc');
                    $(this).append('<i class="fa fa-sort-desc" aria-hidden="true"/>');
                    sortOrder = -1;
                } else {
                    $(this).addClass('asc');
                    $(this).removeClass('desc');
                    $(this).append('<i class="fa fa-sort-asc" aria-hidden="true"/>');
                    sortOrder = 1;
                }
                $(this).siblings().removeClass('asc');
                $(this).siblings().removeClass('desc');
                var arrData = $(tbl).find('tbody >tr:has(td)').get();
                arrData.sort(function (a, b) {
                    var val1 = $(a).children('td').eq(col).text().toUpperCase();
                    var val2 = $(b).children('td').eq(col).text().toUpperCase();
                    if ($.isNumeric(val1) && $.isNumeric(val2))
                        return sortOrder == 1 ? val1 - val2 : val2 - val1;
                    else
                        return (val1 < val2) ? -sortOrder : (val1 > val2) ? sortOrder : 0;
                });
                $.each(arrData, function (index, row) {
                    $(tbl+' tbody').append(row);
                });
            });
        });
    }

    //List arama fonksiyonu
    function tableArama(tbl, edt){
		$(edt).on("keyup",function(){
			var value = $(this).val().toLowerCase();
			$(tbl+" tbody tr").filter(function(){
				if ($(this).text().toLowerCase().indexOf(value)>-1) {
				    $(this).toggle(true);    
				}else{
				    $(this).toggle(false);
				}
				
			});
		});
    }

///////////////////////////////////////////////////////////////////////////////////////////////


var SCOPES = ['https://www.googleapis.com/auth/drive','profile'];
var CLIENT_ID = '339439061130-6nn00dqc5b3i32p5kcoh1bbii9vvh62n.apps.googleusercontent.com';
var API_KEY = 'AIzaSyBW-9xrGOEd2c7IBACixWYGjx-3zacFlCM';
var FOLDER_NAME = "";
var FOLDER_ID = "1VTeiDTPvFYY4ptvT1HYY7ODOhnm-DYkO";
// var FOLDER_ID = "1iRUQUds1lczh9XuBBgFt9TfzlBouObLh";
//var FOLDER_ID = "root";
var FOLDER_PERMISSION = true;
// var FOLDER_LEVEL = 0;
var NO_OF_FILES = 1000;
var DRIVE_FILES = [];
var FILE_COUNTER = 0;
var FOLDER_ARRAY = [];


function handleClientLoad() {
    gapi.load('client:auth2', initClient);
}

function initClient() {
    showLoading();
    gapi.client.init({
	    clientId: CLIENT_ID,scope: SCOPES.join(' ')
    }).then(function () {
	    var a = gapi.auth2.getAuthInstance().isSignedIn.get();
	    console.log(a);
		if (<?= ($tbl['drive_id'] == null ? 1 : 2 ) ?> == 1) {
	    	console.log("yeni");
		    dosyaAc(FOLDER_ID,<?= $tbl['id'] ?>);
		}else{
	    	console.log(<?= "'".$tbl['drive_id']."'" ?>);
		    FOLDER_ID = <?= "'".$tbl['drive_id']."'" ?>;
    	    getDriveFiles();
		}
    });
}

// function initClient() {
// 	gapi.client.init({
// 		clientId: CLIENT_ID,
// 		scope: SCOPES.join(' ')
// 	}).then(function () {
// 	  gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);
// 	  updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
// 	});
// }


function showLoading() {
    $('#pleaseWaitDialog').modal();
}

function hideLoading() {
    $('#pleaseWaitDialog').modal('hide');
}

function getDriveFiles(){
    gapi.client.load('drive', 'v2', getFiles);
}

//*** DOSYALARI GETİR ***///
function getFiles(){
	var query = "trashed=false and '" + FOLDER_ID + "' in parents";
	var request = gapi.client.drive.files.list({'maxResults': NO_OF_FILES,'q': query });
    request.execute(function (resp) {
       if (!resp.error) {
            var table = $('#tableLst');
			table.empty();
            DRIVE_FILES = resp.items;
            DRIVE_FILES.forEach(function(item,idx){
            	if (item.fileExtension != null ) {
            		table.append('<tr class="listeEleman">'+
            		'<td class="text-center align-middle" >'+item.title+'</td>'+
            		'<td class="text-center align-middle" >'+item.lastModifyingUserName+'</td>'+
            		'<td class="text-center align-middle" ><a href="'+item.webContentLink+'" class="btn btn-success col-lg-12" ><i class="fas fa-cloud-download-alt"></i></a></td>'+
            		'<td class="text-center align-middle" ><a href="'+item.alternateLink+'" class="btn btn-warning col-lg-12" target="_blank" ><i class="fas fa-external-link-alt"></i></i></a></td>'+
            		'<td class="text-center align-middle" ><a href="#" onclick="dosyaSil(\''+item.id+'\')" class="btn btn-danger col-lg-12" ><i class="fas fa-times"></i></input></td>'+
        			'</tr>');
            	}
            });
       }else{
	   hataAc("Error: " + resp.error.message);
       }
       hideLoading();
    });
}
//*** DOSYALARI GETİR ***///

//*** DOSYALARI GETİR ***///
function dosyaSil(id){
    var c = confirm("Silmek istediğinize emin misiniz?");
    if (c) {
		var request = gapi.client.drive.files.delete({
			'fileId': id //"15lRc5eH9whoLMs9MxJD_mtgQA7aNFLQb"
		});
		request.execute(function(resp) { 
		   if (resp.error) {
		       hataAc("Error: " + resp.error.message);
		   }else{
			   getDriveFiles();
		   }
		});
    }
}
//*** DOSYA SİLME ***///

//*** DOSYA YÜKLEME ***///
$("#button-upload").click(function () {
    $("#fUpload").click();
});
$("#fUpload").bind("change", function () {
    var uploadObj = $("[id$=fUpload]");
    var file = uploadObj.prop("files")[0];
	var metadata = {
		'title': file.name,
		'description': "bytutorial.com File Upload",
		'mimeType': file.type || 'application/octet-stream',
		"parents": [{
			"kind": "drive#file",
			"id": FOLDER_ID
		}]
	};
	if(file.size <= 0){
		var emptyContent = " ";
		file = new Blob([emptyContent], {type: file.type || 'application/octet-stream'});
	}
	try{
        showLoading();
		var uploader =new MediaUploader({
			file: file,
			token: gapi.auth2.getAuthInstance().currentUser.get().getAuthResponse().access_token,
			metadata: metadata,
			onError: function(response){
				var errorResponse = JSON.parse(response);
				hataAc("Error: " + errorResponse.error.message);
				$("#fUpload").val("");
				$("#upload-percentage").hide(1000);
				getDriveFiles();
			},
			onComplete: function(response){
				var errorResponse = JSON.parse(response);
				if(errorResponse.message != null){
				    hataAc("Error: " + errorResponse.error.message);
					$("#fUpload").val("");
				}else{
					getDriveFiles();
				}
				$("#upload-percentage").html("Resim Yükle");
			},
			onProgress: function(event) {
			    $("#upload-percentage").html((Math.round(((event.loaded/event.total)*100), 0)).toString() + "%");
			},
			params: {
				convert:false,
				ocr: false
			}
		});
		uploader.upload();
	}catch(exc){
	    hataAc("Error: " + exc);
		$("#fUpload").val("");
		getDriveFiles();
	}
});
//*** DOSYA YÜKLEME ***///


//*** DOSYA OLUŞTURMA ***///
function dosyaAc(id,ad){
    var access_token = gapi.auth2.getAuthInstance().currentUser.get().getAuthResponse().access_token;
    var request = gapi.client.request({
	'path' : '/drive/v2/files/',
	'method' : 'POST',
	'headers' : {
	    'Content-Type' : 'application/json',
	    'Authorization' : 'Bearer ' + access_token,
	},

	'body' : {
	    "title" : ad,
	    "mimeType" : "application/vnd.google-apps.folder",
	    "parents" : [ {
		"kind" : "drive#file",
		"id" : id
	    } ]
	}
    });
    request.execute(function(resp) {
    	if (!resp.error) {
    	 	$.post("<?=PREPATH.'post/genelPost.php?tur=update&tablo=Denetim' ?>",
		        {
		    		id 			: <?=$tbl['id'] ?>,
		    		drive_id 	: resp.id,
			    },
			    function(data,status){
		    		if(status == "success"){
		    		    var obj = JSON.parse(data);
		    		    if (obj.hata == true) {
		    				hataAc(obj.hataMesaj);
    						hideLoading();
		    		    }else{
                    	 	FOLDER_ID = resp.id;
                    	 	getDriveFiles();
		    		    }
		    		}else if(status == "error"){
		    		    hataAc("Bir sorun oluştu.");
		    	    }
			    }
		    );
    	} else {
    		hataAc("Error: " + resp.error.message);
    	}
    });
}
//*** DOSYA OLUŞTURMA ***///

</script>
<script async defer src="https://apis.google.com/js/api.js" 
      onload="this.onload=function(){};handleClientLoad()" 
      onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>
<script src="upload.js"></script>
<?php include (PREPATH.'footer.php'); ?>