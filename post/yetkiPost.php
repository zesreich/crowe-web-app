<?php 
include_once 'basePost.php';
include_once '../db/Crud.php';
try {
    if ($_GET['tur']== 'kullaniciTurGruplari'){
        $result = Crud::getSqlCok(new GrupPrm(), GrupPrm::KULLANICI_GRUPLARI, array('kullaniciTurId'=>$_GET['kullaniciTurId']));
        if ($result!=null){
            $result = json_encode(Base::basitList($result),JSON_UNESCAPED_UNICODE) ;
            cevapDondur($result);
        }else{
            hataDondur($result);
        }
    }else if ($_GET['tur']== 'yetkiProgramListesi'){
        $result = Crud::getSqlCok(new YetkiProgram(), YetkiProgram::FIND_PROGRAM_ID, array('programId'=>$_GET['programId']));
        if ($result!=null){
            $result = json_encode(Base::basitList($result),JSON_UNESCAPED_UNICODE) ;
            cevapDondur($result);
        }else{
            hataDondur($result);
        }
    }else if ($_GET['tur']== 'yetkiGrupProgramListesi'){
        $result = Crud::getSqlCok(new YetkiGrup(), YetkiGrup::GRUP_PROGRAM_YETKILER, array('grpId'=>$_GET['grpId'],'prgId'=>$_GET['prgId']));
        if ($result!=null){
            $result = json_encode(Base::basitList($result),JSON_UNESCAPED_UNICODE) ;
            cevapDondur($result);
        }else{
            hataDondur($result);
        }
    }else if ($_GET['tur']== 'yetkiCheckle'){
        if (!yetkiSoa::yetkiVarmi(yetkiConfig::GRUP_YETKILER_YETKI_VERME)){
            hataDondur('Gruplara yetki tanımlamak için yetkiniz bulunmamaktadır.',PREPATH);
        }
        if ($_GET['klc'] == KullaniciTurPrm::IT && $_SESSION['login']['tur'] != KullaniciTurPrm::IT){
            hataDondur('IT kullanıcı türü içi yetki değişikliği yapılamaz.',PREPATH);
        }
        if ($_GET['chk'] == 'true'){
            $tbl = new YetkiGrup();
            $tbl->grup_id->deger = $_GET['grp'];
            $tbl->yetki_id->deger = $_GET['ytk'];
            $result =Crud::save($tbl);
        }else if ($_GET['chk'] == 'false'){
            $tbl = Crud::getSqlTek(new YetkiGrup(), YetkiGrup::YETKI_GRUP_TEK, array('ytk'=>$_GET['ytk'],'grp'=>$_GET['grp']));
            $result = Crud::delete(new YetkiGrup(), $tbl->id->deger);
        }
        if ($result==1){
            cevapDondur("Tamam");
        }else{
            hataDondur($result);
        }
    }
} catch (Exception $e) {
    hataDondur($e);
}

