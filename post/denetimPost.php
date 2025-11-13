<?php 
include_once 'basePost.php';
include_once PREPATH.'db/Crud.php';
include_once PREPATH.'soa/genelSoa.php';
include_once PREPATH.'soa/mkSoa.php';
include_once PREPATH.'soa/sozlesmeSoa.php';
include_once PREPATH.'soa/driveSoa.php';
include_once PREPATH.'soa/pdfSoa.php';
include_once PREPATH.'config/sablonConfig.php';
include_once PREPATH .'config/config.php';
try {
    if (isset($_GET['fnk'])){
        if($_GET['fnk'] == 'tklfList'){
            $drm = $_POST['durum'];
            $sql = "SELECT d.id, mus.id as mid, mus.unvan as munvan, ort.unvan as iunvan,d.donem_bts_trh, dt.aciklama as dton, dt.id as dton_id, frc.adi as frc_id, d.tutar, pr.adi as para_birimi_id, drm.adi as durum_id, drm.id as drmId, d.dil_id as dil_id, d.drive_id as drive_id, d.tr_szlsm_drive_id as trDrv, d.eng_szlsm_drive_id as engDrv FROM ";
            $sql = $sql. " ".Config::DB_DATABASE.".denetim d, ".Config::DB_DATABASE.".musteri mus, ".Config::DB_DATABASE.".is_ortagi ort,".Config::DB_DATABASE.".denetim_durum drm, ".Config::DB_DATABASE.".tklf_para_birimi_prm pr, ".Config::DB_DATABASE.".tklf_denetim_nedeni_prm dt, ".Config::DB_DATABASE.".tklf_finans_rapor_prm frc ";
            $sql = $sql. " WHERE 1=1 AND d.musteri_id = mus.id AND mus.isortagi_id = ort.id AND frc.id = d.frc_id AND drm.id = d.durum_id AND pr.id = d.para_birimi_id AND dt.id = d.dton_id AND drm.grup_id in ";
            $sql = $sql. " (".$drm.") ";
            
            if ($_POST['ara_id'      ] != null ){$sql = $sql. "AND d.id like  '%".$_POST['ara_id']."%' ";}
            if ($_POST['ara_unvan'   ] != null ){$sql = $sql. "AND mus.unvan like '%".$_POST['ara_unvan'   ]."%' ";}
            if ($_POST['ara_isortagi'] != null ){$sql = $sql. "AND ort.unvan like '%".$_POST['ara_isortagi']."%' ";}
            //if ($_POST['ara_dnm_alt' ] != null ){$sql = $sql. "AND d.donem_bas_trh > '".$_POST['ara_dnm_alt']."' ";}
            if ($_POST['ara_dnm_ust' ] != null ){$sql = $sql. "AND d.donem_bts_trh = '".$_POST['ara_dnm_ust']."' ";}
            if ($_POST['ara_dton'    ] != null ){$sql = $sql. "AND d.dton_id = ".$_POST['ara_dton']." ";}
            if ($_POST['ara_frc'     ] != null ){$sql = $sql. "AND d.frc_id = ".$_POST['ara_frc'     ]." ";}
            if ($_POST['ara_ttr_alt' ] != null ){$sql = $sql. "AND d.tutar > ".$_POST['ara_ttr_alt']." ";}
            if ($_POST['ara_ttr_ust' ] != null ){$sql = $sql. "AND d.tutar < ".$_POST['ara_ttr_ust']." ";}
            if ($_POST['ara_para'    ] != null ){$sql = $sql. "AND d.para_birimi_id = ".$_POST['ara_para']." ";}
            if ($_POST['ara_durum'   ] != null ){$sql = $sql. "AND d.durum_id = ".$_POST['ara_durum'   ]." ";}
            //if ($_POST['ara_dil'     ] != null ){$sql = $sql. "AND d.dil_id = ".$_POST['ara_dil']." ";}

            $result = Crud::selectSql($sql);
            cevapDondur($result);
        }else if($_GET['fnk'] == 'tklfDznl'){
            $db = new Db();
            $ses = $db->getCon();
            try {
                if ($_POST['id'] != null || $_POST['id'] != ''){
                    $tbl = Crud::getById(new Denetim(),$_POST['id']);
                    if ($_POST['islem'] == 'duzenleme'){
                        if ($tbl->durum_id->deger != DenetimDurum::DURUM_TASLAK && $tbl->durum_id->deger != DenetimDurum::DURUM_DUZENLE && $tbl->durum_id->deger != DenetimDurum::DURUM_DUZENLENDI) {
                            hataDondur("Düzenleme yapmak için uygun durumda değildir.");
                        }
                    }else if ($_POST['islem'] == 'onay'){
                        if ($tbl->durum_id->deger != DenetimDurum::DURUM_TASLAK &&  $tbl->durum_id->deger != DenetimDurum::DURUM_DUZENLENDI &&  $tbl->durum_id->deger != DenetimDurum::DURUM_DUZENLE) {
                            hataDondur("Onaya göndermek için durum uygun değildir.");
                        }
                    }
                }else{
                    $tbl = new Denetim();
                }
                
                if (!yetkiSoa::yetkiVarmi(yetkiConfig::TEKLIF_DUZENLEME)){
                    hataDondur('Düzenleme yetkiniz bulunmamaktadır.',PREPATH);
                }
                
                if ($tbl->id->deger == null){
                    $eskiVarmi = Crud::getSqlTek(new Denetim(), Denetim::GET_MUSTERI_DONEM, array('musteri_id'=>$_POST['musteri_id'],'dton_id'=>$_POST['dton_id'],'donem_bas_trh'=>$_POST['donem_bas_trh'],'donem_bts_trh'=>$_POST['donem_bts_trh']));
                    if ($eskiVarmi != null){
                        hataDondur("Bu müşteri için bu 'dönem' ve bu 'Denetime Tabi Olma Nedeni' ile kayıt bulunuyor. No :".$eskiVarmi->id->deger  ,PREPATH);
                    }
                }
                
                $tbl->id            ->deger = $_POST['id'] ;
                $tbl->musteri_id    ->deger = $_POST['musteri_id'] ;
                $tbl->teklif_tarihi ->deger = $_POST['teklif_tarihi'] ;
                $tbl->dton_id       ->deger = $_POST['dton_id'] ;
                $tbl->donem_bas_trh ->deger = $_POST['donem_bas_trh'] ;
                $tbl->donem_bts_trh ->deger = $_POST['donem_bts_trh'] ;
                $tbl->frc_id        ->deger = $_POST['frc_id'] ;
                $tbl->raporsekli_id ->deger = $_POST['raporsekli_id'] ;
                $tbl->duzenkurum_id ->deger = $_POST['duzenkurum_id'] ;
                $tbl->tutar         ->deger = $_POST['tutar'] ;
                $tbl->para_birimi_id->deger = $_POST['para_birimi_id'] ;
                $tbl->dil_id        ->deger = $_POST['dil_id'] ;
                $tbl->rapor_dil_id  ->deger = $_POST['rapor_dil_id'] ;
                $tbl->email         ->deger = $_POST['email'] ;
                $tbl->bilgi         ->deger = $_POST['bilgi'] ;
                $tbl->ozel_sart     ->deger = $_POST['ozel_sart'] ;
                
                
                if ($_POST['islem'] == 'duzenleme'){
                    if ($_POST['id'] == null || $_POST['id'] == ''){
                        $tbl->durum_id->deger = DenetimDurum::DURUM_TASLAK;
                        $tbl->id->deger = Denetim::idGetir(BaseSoa::yilGetir($tbl->teklif_tarihi ->deger));
                        $result = Crud::save($tbl,$ses);
                        $tbl->id->deger == null;
                    }else{
                        if ($tbl->durum_id->deger == DenetimDurum::DURUM_TASLAK){
                            $tbl->durum_id->deger = DenetimDurum::DURUM_TASLAK;
                        }else if ($tbl->durum_id->deger == DenetimDurum::DURUM_DUZENLE || $tbl->durum_id->deger == DenetimDurum::DURUM_DUZENLENDI){
                            $tbl->durum_id->deger = DenetimDurum::DURUM_DUZENLENDI;
                        }
                        $result = Crud::update($tbl,$ses);
                    }
                    
                    if ($tbl->id->deger == null){
                        $sonId = $ses->insert_id;
                    }else{
                        $sonId = $tbl->id->deger;
                    }
                    $tbl->id->deger = $sonId;
                    if ($tbl->main_drive_id->deger == null){
                        $mainDriveId =  driveSoa::klasorOlusturClsiz(Config::DRIVE_ROOT_ID, $sonId);
                        $tbl->main_drive_id->deger = addslashes($mainDriveId);
                    }
                    if ($tbl->drive_id->deger == null){
                        $driveId =  driveSoa::klasorOlusturClsiz($mainDriveId, 'Teklif');
                        $tbl->drive_id->deger = addslashes($driveId);
                    }
                    
                    $mstrAdi = (Crud::getById(new Musteri(),$tbl->musteri_id->deger))->unvan->deger;
                    $dtoAdi  = (Crud::getById(new TklfDenetimNedeni(),$tbl->dton_id->deger))->aciklama->deger;
                    $frcAdi  = (Crud::getById(new TklfFinansRapor(),$tbl->frc_id->deger))->aciklama->deger;
                    $praAdi  = (Crud::getById(new TklfParaBirimi(),$tbl->para_birimi_id->deger))->adi->deger;
                    $dilAdi  = (Crud::getById(new TklfDil(),$tbl->dil_id->deger))->adi->deger;
                    
                    $tablo = array(
                        array('Denetime Tabi Olma Nedeni'   ,$dtoAdi),
                        array('Dönem '                      ,BaseSoa::strDateToStr($tbl->donem_bas_trh ->deger).'-'.BaseSoa::strDateToStr($tbl->donem_bts_trh ->deger)),
                        array('FRÇ'                         ,$frcAdi),
                        array('Tutar (KDV Hariç)'           ,number_format($tbl->tutar->deger, 2, ',', '.')),
                        array('Para Birimi'                 ,$praAdi),
                        array('Teklif Dil'                  ,$dilAdi),
                    );
                    
                    $pdf = array();
                    $pdf['pdfAd']       = $sonId.'_'.$tbl->musteri_id->deger.'_'.TklfDil::TR;
                    $pdf['musteri']     = $mstrAdi;
                    $pdf['teklifTrh']   = BaseSoa::strDateToStr($tbl->teklif_tarihi->deger);
                    $pdf['donemSonTrh'] = BaseSoa::strDateToStr($tbl->donem_bts_trh->deger);
                    $pdf['tutar']       = number_format($tbl->tutar->deger, 2, ',', '.');
                    $pdf['yil']         = $tbl->para_birimi_id->deger;
                    $pdf['dil']         = TklfDil::TR;
                    $pdf['ozel']        = $tbl->ozel_sart;
                    $pdf['tablo']       = $tablo;
                    
                    if ($tbl->dil_id->deger == TklfDil::TR || $tbl->dil_id->deger == TklfDil::TR_ING){
                        $tbl->tr_szlsm_drive_id->deger = pdfSoa::teklifSablon(sablonConfig::TEKLIF_TASLAK_TR ,$pdf,$tbl->main_drive_id->deger);
                    }
                    if ($tbl->dil_id->deger == TklfDil::ING || $tbl->dil_id->deger == TklfDil::TR_ING){
                        $tbl->eng_szlsm_drive_id->deger = pdfSoa::teklifSablon(sablonConfig::TEKLIF_TASLAK_ENG,$pdf,$tbl->main_drive_id->deger);
                    }
                    $result = Crud::update($tbl);
                    if ($result!=1){
                        hataDondur("Drive da dosya oluşturulamadı:".$result );
                    }
                }else if ($_POST['islem'] == 'onay'){
                    $tbl->durum_id->deger = DenetimDurum::DURUM_ONAY_YONETICI;
                    if ($_POST['id'] == null || $_POST['id'] == ''){
                        $result = Crud::save($tbl,$ses);
                    }else{
                        $result = Crud::update($tbl,$ses);
                    }
                }
                $ses->commit();
                if ($result==1){
                    cevapDondur($tbl->id->deger);
                }else{
                    hataDondur($result);
                }
            } catch (Exception $e) {
                if (isset($ses)){
                    $ses->rollback();
                }
                hataDondur($e->getMessage());
            } finally {
                if (isset($ses)){
                    mysqli_close($ses);
                }
            }
        }else if($_GET['fnk'] == 'tklfRed'){
            if ($_POST['id'] == null || $_POST['id'] == ''){
                hataDondur("Id eksik.");
            }
            if ($_POST['neden'] == null || $_POST['neden'] == '') {
                hataDondur("Red nedeni boş olamaz.");
            }
            
            if (!yetkiSoa::yetkiVarmi(yetkiConfig::TEKLIF_YONETICI_ONAYLAMA)){
                hataDondur('Yönetici Onay yetkiniz bulunmamaktadır.',PREPATH);
            }
            
            $tbl = Crud::getById(new Denetim(),$_POST['id']);
            if ($tbl->durum_id->deger != DenetimDurum::DURUM_ONAY_YONETICI && $tbl->durum_id->deger != DenetimDurum::DURUM_ONAY_MUSTERI && $tbl->durum_id->deger != DenetimDurum::DURUM_ONAYLI) {
                hataDondur("Düzenleme yapmak için uygun durumda değildir.");
            }
            $tbl->durum_id->deger = DenetimDurum::DURUM_DUZENLE;
            $tbl->teklif_red_ack->deger = $_POST['neden'];
            $result = Crud::update($tbl);
            if ($result==1){
                mesajSet('onay', 'İşlemi tamamlandı.');
                cevapDondur("Tamam");
            }else{
                hataDondur($result);
            }
        }else if($_GET['fnk'] == 'tklfAckKydt'){
            if ($_POST['id'] == null || $_POST['id'] == ''){
                hataDondur("Id eksik.");
            }
            $tbl = Crud::getById(new Denetim(),$_POST['id']);
            $tbl->bilgi->deger = $_POST['bilgi'];
            
            $result = Crud::update($tbl);
            if ($result==1){
                mesajSet('onay', 'İşlemi tamamlandı.');
                cevapDondur("Tamam");
            }else{
                hataDondur($result);
            }
        }else if($_GET['fnk'] == 'tklfOnay'){
            if ($_POST['id'] == null || $_POST['id'] == ''){
                hataDondur("Id eksik.");
            }
            $tbl = Crud::getById(new Denetim(),$_POST['id']);
            if ($tbl->durum_id->deger != DenetimDurum::DURUM_ONAY_YONETICI) {
                hataDondur("Onaylama yapmak için uygun durumda değildir.");
            }
            if (!yetkiSoa::yetkiVarmi(yetkiConfig::TEKLIF_YONETICI_ONAYLAMA)){
                hataDondur('Yönetici Onay yetkiniz bulunmamaktadır.',PREPATH);
            }
            
            $tbl->durum_id->deger = DenetimDurum::DURUM_ONAY_MUSTERI;
            $tbl->yonay_id->deger = $_SESSION['login']['id'];
            $tbl->yonay_trh->deger= date("Y-m-d h:i:s");
            
            if ($tbl->email->deger != null && $tbl->email->deger != ''){
                
                $sfr = md5(time());
                $onay = new DenetimOnay();
                $onay->referans_id->deger = $_POST['id'];
                $onay->sifre    ->deger =  $sfr;
                $onay->link     ->deger = 'post/denetimPost.php?fnk=musteriOnay&sifre='.$sfr;
                $onay->son_trh  ->deger = date("Y-m-d", strtotime("+15 day"));
                $onay->tamammi  ->deger = 'H';
                $result = Crud::save($onay);
                
                $dosyalar = array();
                if ($tbl->tr_szlsm_drive_id->deger != null){
                    $drivetr = driveSoa::getir($tbl->tr_szlsm_drive_id->deger);
                    if(!file_put_contents( PREPATH.config::GECICI_KLASOR.$drivetr->name,file_get_contents($drivetr->url))) {
                        return 'Dosya indirilemedi.';
                    }
                    array_push($dosyalar, $drivetr->name);
                }
                if ($tbl->eng_szlsm_drive_id->deger != null){
                    $driveeng = driveSoa::getir($tbl->eng_szlsm_drive_id->deger);
                    if(!file_put_contents( PREPATH.config::GECICI_KLASOR.$driveeng->name,file_get_contents($driveeng->url))) {
                        return 'Dosya indirilemedi.';
                    }
                    array_push($dosyalar, $driveeng->name);
                }
                
                $sablon = Crud::getSqlTek(new mailSablon(), mailSablon::GET_KEY, array('skey'=>config::MAIL_TEKLIF_SOZLESMESI))->basit();

                $keyler = array (
                    '#musteriAd#'   => $tbl->musteri_id->ref->deger->unvan->deger,
                    //'#link#'        => '<a class="button" href="https://'.$_SERVER['SERVER_NAME'].'/'.$onay->link->deger.'" style="color:#000000;">Onayla</a>'
                    '#link#'        => 'https://'.$_SERVER['SERVER_NAME'].'/'.$onay->link->deger
                );
                $sablon = mailSoa::sablonKetSetle($sablon, $keyler);
                mailSoa::mailAt($tbl->email->deger, null, $sablon,$dosyalar,true);
                if (isset($drivetr)){
                    unlink(PREPATH.config::GECICI_KLASOR.$drivetr->name);
                }
                if (isset($driveeng)){
                    unlink(PREPATH.config::GECICI_KLASOR.$driveeng->name);
                }
            }
            
            $result = Crud::update($tbl);
            if ($result==1){
                cevapDondur("Tamam");
            }
            hataDondur($result);
        }else if($_GET['fnk'] == 'tklfMOnay'){
            $db = new Db();
            $ses = $db->getCon();
            try {
                if ($_POST['id'] == null || $_POST['id'] == ''){
                    hataDondur("Id eksik.");
                }
                $tbl = Crud::getById(new Denetim(),$_POST['id']);
                if ($tbl->durum_id->deger != DenetimDurum::DURUM_ONAY_MUSTERI) {
                    hataDondur("Onaylama yapmak için uygun durumda değildir.");
                }
                if (!yetkiSoa::yetkiVarmi(yetkiConfig::TEKLIF_YONETICI_ONAYLAMA)){
                    hataDondur('Yönetici Onay yetkiniz bulunmamaktadır.',PREPATH);
                }
                
                $tbl->durum_id->deger = DenetimDurum::DURUM_ONAYLI;
                $tbl->monay_id->deger = $_SESSION['login']['id'];
                $tbl->monay_trh->deger= date("Y-m-d h:i:s");
                $result = Crud::update($tbl,$ses);
                
                
                $onyLst = Crud::getSqlCok(new DenetimOnay(), DenetimOnay::DENETIM_REF_ID, array('refId'=>$_POST['id']));
                if ($onyLst != null){
                    foreach ($onyLst as $ony){
                        $ony->tamammi->deger = 'E';
                        $result = Crud::update($ony,$ses);
                        if (!$result){
                            throw new Exception($result);
                        }
                    }
                }
                
                $sz = sozlesmeSoa::createSozlesme($_POST['id'],$ses);
                if ($sz['hata'] == true){
                    throw new Exception($mk['ht_ack']);
                }

                $mk = mkSoa::createMusteriKabul($_POST['id'],$ses);
                if ($mk['hata'] == true){
                    throw new Exception($mk['ht_ack']);
                }
                
                $pl = new Planlama();
                $pl->tklf_id->deger = $_POST['id'];
                $pl->uygula->deger  = 'H';
                $snc = Crud::save($pl,$ses);
                if ($snc!=1){
                    throw new Exception($snc);
                }
                
                $ses->commit();
                mesajSet('onay', 'İşlemi tamamlandı.');
                cevapDondur("Tamam");
            } catch (Exception $e) {
                if (isset($ses)){
                    $ses->rollback();
                }
                hataDondur($e);
            } finally {
                if (isset($ses)){
                    mysqli_close($ses);
                }
            }
        }else if($_GET['fnk'] == 'musteriOnay'){
            $db = new Db();
            $ses = $db->getCon();
            try {
                $ony = Crud::getSqlTek(new DenetimOnay(), DenetimOnay::DENETIM_SIFRE, array('sifre'=>$_GET['sifre']));
                if ( $ony == null ) {
                    onayHataSayfası("false", "Böyle bir bekleyen onay yoktur.");
                }
                if ( $ony->tamammi->deger == 'E' ) {
                    onayHataSayfası("false", "Bu onay zaten tamamlandı.");
                }
                if (new DateTime() > new DateTime($ony->son_trh->deger)){
                    onayHataSayfası("false", "Onaylama tarihi geçti.");
                }
                $ony->tamammi->deger = 'E';
                $result = Crud::update($ony,$ses);
                
                $tbl = Crud::getById(new Denetim(),$ony->referans_id->deger);
                if ($tbl->durum_id->deger != DenetimDurum::DURUM_ONAY_MUSTERI) {
                    throw new Exception("Onaylama yapmak için uygun durumda değildir.");
                    //onayHataSayfası("false", "Onaylama yapmak için uygun durumda değildir.");
                }
                $tbl->durum_id->deger = DenetimDurum::DURUM_ONAYLI;
                $tbl->monay_id->deger = 1;
                $tbl->monay_trh->deger= date("Y-m-d h:i:s");
                $result = Crud::update($tbl,$ses);
                if ($result!=1){
                    throw new Exception($result);
//                     onayHataSayfası("false", $result);
                }
                
                $mk = mkSoa::createMusteriKabul($tbl->id->deger,$ses);
                if ($mk['hata'] == true){
                    throw new Exception($mk['ht_ack']);
//                     onayHataSayfası("false", $mk['ht_ack']);
                }
                $sz = sozlesmeSoa::createSozlesme($tbl->id->deger,$ses);
                if ($sz['hata'] == true){
                    throw new Exception($sz['ht_ack']);
//                     onayHataSayfası("false", $sz['ht_ack']);
                }
                $ses->commit();
                onayHataSayfası(true, "");
            } catch (Exception $e) {
                if (isset($ses)){
                    $ses->rollback();
                }
                onayHataSayfası("false", $e);
            } finally {
                if (isset($ses)){
                    mysqli_close($ses);
                }
            }
        }else if($_GET['fnk'] == 'denetciIsListesi'){
            $sql =
                'SELECT * FROM ('.
                'SELECT                                                     '.
                '    d.id as teklif_id,                                     '.
                '    s.id as s_id,                                          '.
                '    mk.id as mk_id,                                        '.
                '    mus.unvan as munvan,                                   '.
                '    d.donem_bas_trh as donem_bas,'.
                '    d.donem_bts_trh as donem_bts,'.
                '    i.unvan as iunvan,                                     '.
                '    dd.id as teklif_durum_id,                              '.
                '    dd.adi as teklif_durum,                                '.
                '    dt.aciklama as dton,                                   '.
                '    dt.id as dton_id,                                      '.
                '    CASE                                                   '.
                '        WHEN mk.durum =40 THEN "Yeni"                      '.
                '        WHEN mk.durum =41 THEN "Devam Ediyor"              '.
                '        WHEN mk.durum =42 THEN "Tamamlandı"                '.
                '        WHEN mk.durum =43 THEN "Kontrol Ediliyor"          '.
                '        WHEN mk.durum =44 THEN "Onaylandı"                 '.
                '        ELSE "Oluşturulmadı"                               '.
                '    END as mk_durum,                                       '.
                '    CASE                                                   '.
                '        WHEN s.durum =51 THEN "Sözleşme Oluşturulmadı"     '.
                '        WHEN s.durum =52 THEN "Müşteriye İmzaya Gönder"    '.
                '        WHEN s.durum =53 THEN "Müşteri İmza Aşamasında"    '.
                '        WHEN s.durum =54 THEN "İmzalı Sözleşme"            '.
                '        ELSE "Oluşturulmadı"                               '.
                '    END as s_durum                                         '.
                'FROM                                                       '.
                '    '.Config::DB_DATABASE.'.denetim d                                    '.
                '    left JOIN '.Config::DB_DATABASE.'.musteri_kabul mk on d.id = mk.no   '.
                '    left JOIN '.Config::DB_DATABASE.'.sozlesme s on d.id= s.tklf_id,     '.
                '    '.Config::DB_DATABASE.'.denetim_durum dd,                            '.
                '    '.Config::DB_DATABASE.'.musteri mus,                                 '.
                '    '.Config::DB_DATABASE.'.is_ortagi i,                                 '.
                '    '.Config::DB_DATABASE.'.tklf_denetim_nedeni_prm dt                   '.
                'WHERE 1=1                                                  '.
                '    AND d.durum_id = dd.id                                 '.
                '    AND d.musteri_id = mus.id                              '.
                '    AND mus.isortagi_id = i.id                             '.
                '    AND dt.id = d.dton_id                                  '.
                '    AND EXISTS (                                               '.                     
                '        SELECT 1 FROM '.Config::DB_DATABASE.'.mk_denetci mkd WHERE 1=1   '.
                '            and mkd.ekip = \'Yardımcı Ekip\'                   '.
                '            and mkd.tklf_id = d.id                             '.
                '            and mkd.denetci_id = '.$_GET['id'].'               '.
                '        )) sq WHERE 1=1   '
                ;
            
            if ($_POST['ara_id']        != null ){$sql = $sql. "AND sq.teklif_id = ".$_POST['ara_id']." ";}
            if ($_POST['ara_munvan']    != null ){$sql = $sql. "AND sq.munvan like '%".$_POST['ara_munvan']."%' ";}
            if ($_POST['ara_iunvan']    != null ){$sql = $sql. "AND sq.iunvan  like '%".$_POST['ara_iunvan']."%' ";}
            if ($_POST['ara_dnm_alt' ]  != null ){$sql = $sql. "AND sq.donem_bas > '".$_POST['ara_dnm_alt']."' ";}
            if ($_POST['ara_dnm_ust' ]  != null ){$sql = $sql. "AND sq.donem_bts < '".$_POST['ara_dnm_alt']."' ";}
            if ($_POST['ara_tdurum']    != null ){$sql = $sql. "AND sq.teklif_durum like '%".$_POST['ara_tdurum']."%' ";}
            if ($_POST['ara_mdurum']    != null ){$sql = $sql. "AND sq.mk_durum like '%".$_POST['ara_mdurum']."%' ";}
            if ($_POST['ara_sdurum']    != null ){$sql = $sql. "AND sq.s_durum  like '%".$_POST['ara_sdurum']."%' ";}
            
            $result = Crud::selectSql($sql);
            cevapDondur($result);
        }else if($_GET['fnk'] == 'isOrtagiIsListesi'){
            $sql =
                'SELECT * FROM ('.
                'SELECT                                                     '.
                '    d.id as teklif_id,                                     '.
                '    s.id as s_id,                                          '.
                '    mk.id as mk_id,                                        '.
                '    mus.unvan as munvan,                                   '.
                '    d.donem_bas_trh as donem_bas,                          '.
                '    d.donem_bts_trh as donem_bts,                          '.
                '    dt.aciklama as dton,                                   '.
                '    dt.id as dton_id,                                      '.
                '    i.unvan as iunvan,                                     '.
                '    dd.id as teklif_durum_id,                              '.
                '    dd.adi as teklif_durum,                                '.
                '    CASE                                                   '.
                '        WHEN mk.durum =40 THEN "Yeni"                      '.
                '        WHEN mk.durum =41 THEN "Devam Ediyor"              '.
                '        WHEN mk.durum =42 THEN "Tamamlandı"                '.
                '        WHEN mk.durum =43 THEN "Kontrol Ediliyor"          '.
                '        WHEN mk.durum =44 THEN "Onaylandı"                 '.
                '        ELSE "Oluşturulmadı"                               '.
                '    END as mk_durum,                                       '.
                '    CASE                                                   '.
                '        WHEN s.durum =51 THEN "Sözleşme Oluşturulmadı"     '.
                '        WHEN s.durum =52 THEN "Müşteriye İmzaya Gönder"    '.
                '        WHEN s.durum =53 THEN "Müşteri İmza Aşamasında"    '.
                '        WHEN s.durum =54 THEN "İmzalı Sözleşme"            '.
                '        ELSE "Oluşturulmadı"                               '.
                '    END as s_durum                                         '.
                'FROM                                                       '.
                '    '.Config::DB_DATABASE.'.denetim d                                    '.
                '    left JOIN '.Config::DB_DATABASE.'.musteri_kabul mk on d.id = mk.no   '.
                '    left JOIN '.Config::DB_DATABASE.'.sozlesme s on d.id= s.tklf_id,     '.
                '    '.Config::DB_DATABASE.'.denetim_durum dd,                            '.
                '    '.Config::DB_DATABASE.'.musteri mus,                                 '.
                '    '.Config::DB_DATABASE.'.is_ortagi i,                                  '.
                '    '.Config::DB_DATABASE.'.tklf_denetim_nedeni_prm dt                   '.  
                'WHERE 1=1                                                  '.
                '    AND d.durum_id = dd.id                                 '.
                '    AND d.musteri_id = mus.id                              '.
                '    AND mus.isortagi_id = i.id                             '.
                '    AND dt.id = d.dton_id                                  '.
                '    AND i.id = '.$_GET['id'] .') sq WHERE 1=1 '
                ;
            
            if ($_POST['ara_id']        != null ){$sql = $sql. "AND sq.teklif_id = ".$_POST['ara_id']." ";}
            if ($_POST['ara_munvan']    != null ){$sql = $sql. "AND sq.munvan like '%".$_POST['ara_munvan']."%' ";}
            if ($_POST['ara_dnm_alt' ]  != null ){$sql = $sql. "AND sq.donem_bas > '".$_POST['ara_dnm_alt']."' ";}
            if ($_POST['ara_dnm_ust' ]  != null ){$sql = $sql. "AND sq.donem_bts < '".$_POST['ara_dnm_alt']."' ";}
            if ($_POST['ara_tdurum']    != null ){$sql = $sql. "AND sq.teklif_durum like '%".$_POST['ara_tdurum']."%' ";}
            if ($_POST['ara_mdurum']    != null ){$sql = $sql. "AND sq.mk_durum like '%".$_POST['ara_mdurum']."%' ";}
            if ($_POST['ara_sdurum']    != null ){$sql = $sql. "AND sq.s_durum  like '%".$_POST['ara_sdurum']."%' ";}
            
            $result = Crud::selectSql($sql);
            cevapDondur($result);
        }else if($_GET['fnk'] == 'musteriIsListesi'){
            $sql =
                'SELECT * FROM ('.
                'SELECT                                                     '.
                '    d.id as teklif_id,                                     '.
                '    s.id as s_id,                                          '.
                '    mk.id as mk_id,                                        '.
                '    mus.unvan as munvan,                                   '.
                '    d.donem_bas_trh as donem_bas,                          '.
                '    d.donem_bts_trh as donem_bts,                          '.
                '    dt.aciklama as dton,                                   '.
                '    dt.id as dton_id,                                      '.
                '    i.unvan as iunvan,                                     '.
                '    dd.id as teklif_durum_id,                              '.
                '    dd.adi as teklif_durum,                                '.
                '    CASE                                                   '.
                '        WHEN mk.durum =40 THEN "Yeni"                      '.
                '        WHEN mk.durum =41 THEN "Devam Ediyor"              '.
                '        WHEN mk.durum =42 THEN "Tamamlandı"                '.
                '        WHEN mk.durum =43 THEN "Kontrol Ediliyor"          '.
                '        WHEN mk.durum =44 THEN "Onaylandı"                 '.
                '        ELSE "Oluşturulmadı"                               '.
                '    END as mk_durum,                                       '.
                '    CASE                                                   '.
                '        WHEN s.durum =51 THEN "Sözleşme Oluşturulmadı"     '.
                '        WHEN s.durum =52 THEN "Müşteriye İmzaya Gönder"    '.
                '        WHEN s.durum =53 THEN "Müşteri İmza Aşamasında"    '.
                '        WHEN s.durum =54 THEN "İmzalı Sözleşme"            '.
                '        ELSE "Oluşturulmadı"                               '.
                '    END as s_durum                                         '.
                'FROM                                                       '.
                '    '.Config::DB_DATABASE.'.denetim d                                    '.
                '    left JOIN '.Config::DB_DATABASE.'.musteri_kabul mk on d.id = mk.no   '.
                '    left JOIN '.Config::DB_DATABASE.'.sozlesme s on d.id= s.tklf_id,     '.
                '    '.Config::DB_DATABASE.'.denetim_durum dd,                            '.
                '    '.Config::DB_DATABASE.'.musteri mus,                                 '.
                '    '.Config::DB_DATABASE.'.is_ortagi i,                                 '.
                '    '.Config::DB_DATABASE.'.tklf_denetim_nedeni_prm dt                   '.
                'WHERE 1=1                                                  '.
                '    AND d.durum_id = dd.id                                 '.
                '    AND d.musteri_id = mus.id                              '.
                '    AND mus.isortagi_id = i.id                             '.
                '    AND dt.id = d.dton_id                                  '.
                '    AND mus.id = '.$_GET['id'] .') sq WHERE 1=1 '
                ;
            
            if ($_POST['ara_id']        != null ){$sql = $sql. "AND sq.teklif_id = ".$_POST['ara_id']." ";}
            if ($_POST['ara_munvan']    != null ){$sql = $sql. "AND sq.munvan like '%".$_POST['ara_munvan']."%' ";}
            if ($_POST['ara_dnm_alt' ]  != null ){$sql = $sql. "AND sq.donem_bas > '".$_POST['ara_dnm_alt']."' ";}
            if ($_POST['ara_dnm_ust' ]  != null ){$sql = $sql. "AND sq.donem_bts < '".$_POST['ara_dnm_alt']."' ";}
            if ($_POST['ara_tdurum']    != null ){$sql = $sql. "AND sq.teklif_durum like '%".$_POST['ara_tdurum']."%' ";}
            if ($_POST['ara_mdurum']    != null ){$sql = $sql. "AND sq.mk_durum like '%".$_POST['ara_mdurum']."%' ";}
            if ($_POST['ara_sdurum']    != null ){$sql = $sql. "AND sq.s_durum  like '%".$_POST['ara_sdurum']."%' ";}
            
            $result = Crud::selectSql($sql);
            cevapDondur($result);
        }else if($_GET['fnk'] == 'paylasimButon'){
            $result = array('odeme'=>true,'fatura'=>true);
            $Lst = Crud::getSqlCok(new IsOrtakPayDeger(), IsOrtakPayDeger::GET_TEKLIF_ID, array('tklf_id'=>$_POST['id']));
            if ($Lst != null){
                foreach ($Lst as $dgr){
                    if ($dgr->odeme->deger == 'H'){
                        $result['odeme'] = false;
                    }
                    if ($dgr->fatura->deger == 'H'){
                        $result['fatura'] = false;
                    }
                }
            }else{
                $result['odeme'] = false;
                $result['fatura'] = false;
            }
            cevapDondur($result);
        }else if($_GET['fnk'] == 'uygunIdler'){
            $yil = BaseSoa::yilGetir($_GET['trh']);
            $list = Crud::selectSql("SELECT id FROM ".Config::DB_DATABASE.".denetim WHERE id like '".$yil."%' order by id desc");
            $eksik = array(); 
            if ($list[0]==null){
                array_push($eksik, $yil.'1');
                cevapDondur($eksik);
            }
            $byk = substr($list[0]['id'],4,strlen($list[0]['id']));
            array_push($eksik, $yil.($byk+1));
            for ($i = $byk; $i > 0; $i--) {
                $var = false;
                foreach ($list as $l){
                    if (intval($i) == intval(substr($l['id'],4,strlen($l['id'])))){
                        $var = true;
                        break;
                    }
                }
                if (!$var){
                    array_push($eksik, $yil.$i);
                }
           }
           cevapDondur($eksik);
        }
    }else{
        hataDondur("Parametreler düzgün değil.");
    }
} catch (Exception $e) {
    hataDondur($e);
}
