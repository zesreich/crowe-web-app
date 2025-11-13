<?php 
include_once 'baseSoa.php';
require_once PREPATH . 'composer/vendor/autoload.php';
include_once PREPATH . 'config/sozlesmeConfig.php';
include_once PREPATH . 'config/sablonConfig.php';
include_once PREPATH . 'config/config.php';
include_once PREPATH . 'db/Crud.php';
include_once PREPATH . 'soa/driveSoa.php';
include_once PREPATH . 'soa/mkSoa.php';
use setasign\Fpdi\Fpdi;

Class pdfSoa extends BaseSoa{
    
    public static function beyanIndir ($tklf,$key){
        try {
            $teklif = Crud::getById(new Denetim(),$tklf)->basit();
            $sz     = Crud::getSqlTek(new Sozlesme(), Sozlesme::GET_TEKLIF_ID, array('tklf_id'=>$tklf));
            $sbln   = Crud::getSqlTek(new Sablonlar(), Sablonlar::GEY_BY_KEY, array('anahtar'=>$key));
            if ($sbln->deger->deger == null){
                throw new Exception('Taslak yok.');
            }
            $drive = driveSoa::getir($sbln->deger->deger);
            if(!file_put_contents( PREPATH.config::GECICI_KLASOR.$drive->name,file_get_contents($drive->url))) {
                throw new Exception('Dosya indirilemedi.');
            }
            
            $bilgiArr = array();
            $bilgiArr['sirketUnvan'] = $teklif['musteri_id']['unvan'];
            $bilgiArr['donem']       = BaseSoa::strDateToStr($teklif['donem_bas_trh']).'-'.BaseSoa::strDateToStr($teklif['donem_bts_trh']);
            $bilgiArr['sozlesme']    = $sz->denetim_imza_trh->deger == null ? '' : BaseSoa::strDateToStr($sz->denetim_imza_trh->deger);
            
            $arr = pdfSoa::fpdiCek($key);
            if (sablonConfig::MK_BEYAN_BAGIMSIZ == $key){
                pdfSoa::mkBeyanBagimsizHazirla($arr[1],$arr[2],$bilgiArr);
            }else{
                pdfSoa::mkBeyanSozlesmeHazirla($arr[1],$arr[2],$bilgiArr);
            }
            
            
            $arr[1]->Output($drive->name, 'D');
            unlink($arr[0]);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
    
    public static function mkDenetciSozlesmeIndir ($tklf,$dntciId){
        try {
            $teklif = Crud::getById(new Denetim(),$tklf)->basit();
            $kllnc  = Crud::getById(new Kullanici(),$dntciId)->basit();
    
            $key = sablonConfig::MK_DENETCI;
            $szlsm  = Crud::getSqlTek(new Sablonlar(), Sablonlar::GEY_BY_KEY, array('anahtar'=>$key));
            
            if ($szlsm->deger->deger == null){
                throw new Exception('Taslak yok.');
            }
            if ($teklif['main_drive_id'] == null){
                throw new Exception('drive olusturulmadı.');
            }
            
            $drive = driveSoa::getir($szlsm->deger->deger);
            if(!file_put_contents( PREPATH.config::GECICI_KLASOR.$drive->name,file_get_contents($drive->url))) {
                throw new Exception('Dosya indirilemedi.');
            }
            
            $dnt = Crud::getSqlTek(new MKDenetci(), MKDenetci::GET_TEKLIF_DENETCI_ID, array('tklf_id'=>$tklf,'denetci_id'=>$dntciId))->basit();
            
            $bilgiArr = array();
            $bilgiArr['sirketAdi']  = $teklif['musteri_id']['unvan'];
            $bilgiArr['denetciAd']  = $kllnc['ad'].' '.$kllnc['soyad'];
            $bilgiArr['ekip']       = $dnt['ekip'] .' - ' .$dnt['gorev'];
            
            $arr = pdfSoa::fpdiCek($key);
            pdfSoa::mkDenetciSozlesmeHazirla($arr[1],$arr[2],$bilgiArr);
            $arr[1]->Output($dntciId.'_'.$drive->name, 'D');
            unlink($arr[0]);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
    
    public static function imzasizSozlesmeYukle ($tklf){
        $teklif = Crud::getById(new Denetim(),$tklf)->basit();
        if(!empty(sozlesmeConfig::SOZLESMELER_ID_KEY[$teklif['dton_id']['id']])){
            $key    = sozlesmeConfig::SOZLESMELER_ID_KEY[$teklif['dton_id']['id']];
        }else{
            throw new Exception("Bu denetim nedeni için pdf taslağı yok, '".$teklif['dton_id']['aciklama']."'");
        }
        $szlsm  = Crud::getSqlTek(new Sablonlar(), Sablonlar::GEY_BY_KEY, array('anahtar'=>$key));
        $sz     = Crud::getSqlTek(new Sozlesme(), Sozlesme::GET_TEKLIF_ID, array('tklf_id'=>$tklf));
        if ($szlsm->deger->deger == null){
            throw new Exception('Taslak yok.');
        }
        if ($teklif['main_drive_id'] == null){
            throw new Exception('drive olusturulmadı.');
        }
        $drive = driveSoa::getir($szlsm->deger->deger);
        if(!file_put_contents( PREPATH.config::GECICI_KLASOR.$drive->name,file_get_contents($drive->url))) {
            throw new Exception('Dosya indirilemedi.');
        }
        
        $arr = array();
        $arr['id']          = $teklif['id'];
        $arr['sirketAdi']   = $teklif['musteri_id']['unvan'];
        $arr['raporDil']    = $teklif['rapor_dil_id']['adi'];
        $arr['tarih']       = $sz->denetim_imza_trh->deger == null ? '' : BaseSoa::strDateToStr($sz->denetim_imza_trh->deger);
        $arr['gtarih']      = $sz->genel_kurul_trh->deger == null ? '' : BaseSoa::strDateToStr($sz->genel_kurul_trh->deger);
        $arr['donem']       = BaseSoa::strDateToStr($teklif['donem_bas_trh']).'-'.BaseSoa::strDateToStr($teklif['donem_bts_trh']);
        $arr['finansal']    = $teklif['frc_id']['adi'];
        $arr['ucret']       = BaseSoa::paraFormat($teklif['tutar'],0).' TL';
        $arr['ozel']        = $teklif['ozel_sart'];
        $arr['teslimTrh']   = $sz->teslim_tarihi->deger == null ? '' : BaseSoa::strDateToStr($sz->teslim_tarihi->deger);
        
        $dntList = mkSoa::getDenetciList($tklf);
        $ad = 1;
        $yd = 1;
        
        foreach ($dntList as $dnc){
            if ($dnc['ekip'] == mkConfig::EKIP_ASIL_EKIP[1]){
                $arr['ad'.$ad.'Adi']       = $dnc['denetci_id']['ad'].' '.$dnc['denetci_id']['soyad'];
                $arr['ad'.$ad.'CalsmSr']   = $dnc['saat'].' saat';
                $arr['ad'.$ad.'Grv']       = $dnc['gorev'];
                $arr['ad'.$ad.'SaatUcrt']  = BaseSoa::paraFormat($dnc['saat_ucret'],0).' TL';
                $arr['ad'.$ad.'Ucrt']      = BaseSoa::paraFormat($dnc['ucret'],0).' TL';
                $arr['tplUcret']           = isset($arr['tplUcret']) ? $arr['tplUcret']+ $dnc['ucret'] : $dnc['ucret'];
                $arr['tplSaat']            = isset($arr['tplSaat'])  ? $arr['tplSaat'] + $dnc['saat']  : $dnc['saat'];
                $ad++;
            }else if ($dnc['ekip'] == mkConfig::EKIP_YEDEK_EKIP[1]){
                $arr['yd'.$yd.'Adi']       = $dnc['denetci_id']['ad'].' '.$dnc['denetci_id']['soyad'];
                //$arr['yd'.$yd.'SaatUcrt']  = $dnc['saat'].' saat';
                $arr['yd'.$yd.'SaatUcrt']  = BaseSoa::paraFormat($dnc['saat_ucret'],0).' TL';
                $arr['yd'.$yd.'Grv']       = $dnc['gorev'];
                $yd++;
            }
        }
        $arr['tplUcret'] = BaseSoa::paraFormat($arr['tplUcret'],0).' TL';
        $arr['tplSaat']  = $arr['tplSaat'] .' saat';
        
        pdfSoa::sozlesmeHazirla($key,$arr);
        $id = driveSoa::dosyaYukle($teklif['main_drive_id'], $drive->name, PREPATH.config::GECICI_KLASOR.$drive->name);
        unlink(PREPATH.config::GECICI_KLASOR.$drive->name);
        $tbl = Crud::getSqlTek(new Sozlesme(), Sozlesme::GET_TEKLIF_ID, array('tklf_id'=>$tklf));
        $tbl->imzasiz_drive_id->deger = addslashes($id);
        $snc = Crud::update($tbl);
        if ($snc!=1){
            throw new Exception($snc);
        }
    }
    
    public static function mkBeyanBelgeYukle ($tklf_id,$link,$files,$key){
        if(
            strrpos($files['dosya']['name'],'.') == false ||
            (substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'pdf' &&
                substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'PDF')
            ){
                return 'pdf uzantılı bir dosya yüklemelisiniz.';
        }else{
            $mk = Crud::getSqlTek(new MusteriKabul(), MusteriKabul::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
            $teklif = Crud::getById(new Denetim(),$tklf_id)->basit();
            $drive  = driveSoa::getir($teklif['main_drive_id'],$link);
            $stream = fopen($files['dosya']['tmp_name'], "rb");
            $result = $drive->upload('imzali_'.$key.'.pdf', $stream);
            if (sablonConfig::MK_BEYAN_BAGIMSIZ == $key){
                $mk->belge1->deger = $result->id;
            }else{
                $mk->belge2->deger = $result->id;
            }
            $snc = Crud::update($mk);
            if ($snc != 1){
                return $snc;
            }
        }
        return true;
    }
    
    public static function riskBelegeYukle ($id,$link,$files){
        if(
            strrpos($files['dosya']['name'],'.') == false ||
            (substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'xlsx' &&
                substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'XLSX') &&
            (substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'xls' &&
                substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'XLS')
            ){
            return 'Excel dosya yüklemelisiniz.';
        }else{
            $mk = Crud::getById(new RiskProsedur(),$id);
            $stream = fopen($_FILES['dosya']['tmp_name'], "rb");
            $drive  = driveSoa::getir(Config::DRIVE_SABLON_ID,$link);
            $result = $drive->upload($id.'.'.substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1), $stream);
            $mk->drive_id->deger = $result->id;
            $snc = Crud::update($mk);
            if ($snc != 1){
                return $snc;
            }
        }
        return true;
    }
    
    public static function planlamaBelgeYukle ($tklf_id,$link,$files,$ky){
        $key = null;
        if ($ky == sablonConfig::PLANLAMA_DENETIM_SIRKET_TR){
            $key = 'sirket_tr';
        }else if ($ky == sablonConfig::PLANLAMA_DENETIM_SIRKET_ENG){
            $key = 'sirket_eng';
        }else if ($ky == sablonConfig::PLANLAMA_MUSTERI_TR){
            $key = 'musteri_tr';
        }else if ($ky == sablonConfig::PLANLAMA_MUSTERI_ENG){
            $key = 'musteri_eng';
        }
        if(
            strrpos($files['dosya']['name'],'.') == false ||
            (substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'pdf' &&
                substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'PDF')
            ){
                return 'pdf uzantılı bir dosya yüklemelisiniz.';
        }else{
            $plnm   = Crud::getSqlTek(new Planlama(), Planlama::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
            $client=driveSoa::baglan($link);
            $ii = driveSoa::planlamaDriveDosyasiIdGetir($client, $tklf_id, $key);
            $drive  = driveSoa::clientGetir($client, $ii);
            $stream = fopen($files['dosya']['tmp_name'], "rb");
            $result = $drive->upload($key.'.pdf', $stream);
            $plnm->$key->deger = $result->id;
            $snc = Crud::update($plnm);
            if ($snc != 1){
                return $snc;
            }
        }
        return true;
    }
    
    public static function mkDenetciBelgeYukle ($tklf_id,$denetci_id,$link,$files){
        if(
            strrpos($files['dosya']['name'],'.') == false ||
            (substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'pdf' &&
            substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'PDF')
        ){
            return 'pdf uzantılı bir dosya yüklemelisiniz.';
        }else{
            $teklif = Crud::getSqlTek(new MKDenetci(), MKDenetci::GET_TEKLIF_DENETCI_ID, array('tklf_id'=>$tklf_id,'denetci_id'=>$denetci_id));

            $stream = fopen($files['dosya']['tmp_name'], "rb");
            
            $client=driveSoa::baglan($link);
            $ii = driveSoa::denetciDriveDosyasiIdGetir($client, $tklf_id);
            $drive  = driveSoa::clientGetir($client, $ii);
            $result = $drive->upload($tklf_id.'_'.  $teklif->denetci_id->ref->deger->ad->deger.'_'.$teklif->denetci_id->ref->deger->soyad->deger.'.pdf', $stream);
            
            $teklif->drive_id->deger = $result->id;
            $snc = Crud::update($teklif);
            if ($snc != 1){
                return $snc;
            }
        }
        return true;
    }
    
    public static function imzaliSozlesmeYukle ($tklf_id,$link,$files){
        if(
            strrpos($files['dosya']['name'],'.') == false ||
            (substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'pdf' &&
            substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')+1) != 'PDF')
        ){
            return 'pdf uzantılı bir dosya yüklemelisiniz.';
        }else{
            $stream = fopen($files['dosya']['tmp_name'], "rb");
            
            $teklif = Crud::getById(new Denetim(),$tklf_id)->basit();
            $drive  = driveSoa::getir($teklif['main_drive_id'],$link);
            $result = $drive->upload('imzali_sozlesme'.substr($files['dosya']['name'],strrpos($files['dosya']['name'],'.')), $stream);
            
            $szlsm  = Crud::getSqlTek(new Sozlesme(), Sozlesme::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
            $szlsm->imzali_drive_id->deger = addslashes($result->id);
            $szlsm->durum->deger = sozlesmeConfig::DURUM_TAMAMLADI[0];
            $snc = Crud::update($szlsm);
            if ($snc != 1){
                return $snc;
            }
        }
        return true;
    }
    
    public static function teklifSablon($key,$bilgiArr,$driveId){
        $szlsm  = Crud::getSqlTek(new Sablonlar(), Sablonlar::GEY_BY_KEY, array('anahtar'=>$key));
        if ($szlsm->deger->deger == null){
            throw new Exception('Taslak yok.');
        }
        $drive = driveSoa::getir($szlsm->deger->deger);
        if(!file_put_contents( PREPATH.config::GECICI_KLASOR.$drive->name,file_get_contents($drive->url))) {
            throw new Exception('Dosya indirilemedi.');
        }
        
        
        $arr = pdfSoa::fpdiCek($key);
        if ($key == sablonConfig::TEKLIF_TASLAK_TR){
            pdfSoa::teklifSozlesmesiHazirla_tr($arr[1],$arr[2],$bilgiArr);
        }else if($key == sablonConfig::TEKLIF_TASLAK_ENG){
            pdfSoa::teklifSozlesmesiHazirla_eng($arr[1],$arr[2],$bilgiArr);
        }
        $arr[1]->Output($arr[0], 'F');
        
        $id = driveSoa::dosyaYukleBasla($driveId, 'Teklif.pdf', PREPATH.config::GECICI_KLASOR.$drive->name);
        unlink(PREPATH.config::GECICI_KLASOR.$drive->name);
        return addslashes($id);
        
    }
    
    private static function fpdiCek ($key){
        $pdf = new Fpdi();
        $path = PREPATH.config::GECICI_KLASOR.$key.'.pdf';
        $pageCount = $pdf->setSourceFile($path);
        $pdf->AddFont('arial_tr','','arial_tr.php');
        $pdf->AddFont('arial_tr','B','arial_tr_bold.php');
        return array($path,$pdf,$pageCount);
    }
    
    private static function sozlesmeHazirla ($key,$bilgiArr){
        $arr = pdfSoa::fpdiCek($key);
        if ($key == sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI){
            pdfSoa::sozlesmeBagimsizDenetimHazirla($arr[1],$arr[2],$bilgiArr);
        }else if($key == sablonConfig::BDDK_EK4_SOZLESMESI){
            pdfSoa::sozlesmeBddkEk4Hazirla($arr[1],$arr[2],$bilgiArr);
        }else if($key == sablonConfig::TCMB_SOZLESMESI){
            pdfSoa::sozlesmeTcmbHazirla($arr[1],$arr[2],$bilgiArr);
        }
        $arr[1]->Output($arr[0], 'F');
    }
    
    //TAMAMLANDI
    private static function sozlesmeTcmbHazirla ($pdf,$pageCount,$arr){
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            if ($size['width'] > $size['height']) {
                $pdf->AddPage('L', array($size['width'], $size['height']));
            } else {
                $pdf->AddPage('P', array($size['width'], $size['height']));
            }
            $pdf->useTemplate($templateId);
            
            if($pageNo == 1 ){
                pdfSoa::metinEkle($pdf, 100 , 35    , $arr['id']        , array('arial_tr','B',11));
                pdfSoa::metinEkle($pdf, 64  , 68    , $arr['sirketAdi'] , array('arial_tr','',10));
            }else if($pageNo == 2){
                pdfSoa::metinEkle($pdf, 90  , 237  , $arr['ucret']             , array('arial_tr','B',10));
                
                pdfSoa::metinEkle($pdf, 28  , 261   , isset($arr['ad1Adi']     ) ? $arr['ad1Adi']      : ''       , array('arial_tr','',10));
                pdfSoa::metinEkle($pdf, 74.5, 261   , isset($arr['ad1Grv']     ) ? $arr['ad1Grv']      : ''       );
                pdfSoa::metinEkleR($pdf,123 , 261   , isset($arr['ad1CalsmSr'] ) ? $arr['ad1CalsmSr']  : ''       );
                pdfSoa::metinEkleR($pdf,150 , 261   , isset($arr['ad1SaatUcrt']) ? $arr['ad1SaatUcrt'] : ''       );
                pdfSoa::metinEkleR($pdf,185 , 261   , isset($arr['ad1Ucrt']    ) ? $arr['ad1Ucrt']     : ''       );
                                                      
                pdfSoa::metinEkle($pdf, 28  , 266   , isset($arr['ad2Adi']     ) ? $arr['ad2Adi']      : ''       );
                pdfSoa::metinEkle($pdf, 74.5, 266   , isset($arr['ad2Grv']     ) ? $arr['ad2Grv']      : ''       );
                pdfSoa::metinEkleR($pdf,123 , 266   , isset($arr['ad2CalsmSr'] ) ? $arr['ad2CalsmSr']  : ''       );
                pdfSoa::metinEkleR($pdf,150 , 266   , isset($arr['ad2SaatUcrt']) ? $arr['ad2SaatUcrt'] : ''       );
                pdfSoa::metinEkleR($pdf,185 , 266   , isset($arr['ad2Ucrt']    ) ? $arr['ad2Ucrt']     : ''       );
                                                      
                pdfSoa::metinEkle($pdf, 28  , 271   , isset($arr['ad3Adi']     ) ? $arr['ad3Adi']      : ''       );
                pdfSoa::metinEkle($pdf, 74.5 , 271  , isset($arr['ad3Grv']     ) ? $arr['ad3Grv']      : ''       );
                pdfSoa::metinEkleR($pdf,123 , 271   , isset($arr['ad3CalsmSr'] ) ? $arr['ad3CalsmSr']  : ''       );
                pdfSoa::metinEkleR($pdf,150 , 271   , isset($arr['ad3SaatUcrt']) ? $arr['ad3SaatUcrt'] : ''       );
                pdfSoa::metinEkleR($pdf,185 , 271   , isset($arr['ad3Ucrt']    ) ? $arr['ad3Ucrt']     : ''       );
                                                      
                pdfSoa::metinEkleR($pdf,123 , 276.5 , $arr['tplSaat']);
                pdfSoa::metinEkleR($pdf,185 , 276.5 , $arr['tplUcret']);
                                                      
            }else if($pageNo == 3){                   
                                                      
                //Yedek Denetçiler                    
                pdfSoa::metinEkle($pdf, 28  , 47    , isset($arr['yd1Adi']     ) ? $arr['yd1Adi']      : ''        );
                pdfSoa::metinEkle($pdf, 90  , 47    , isset($arr['yd1Grv']     ) ? $arr['yd1Grv']      : ''        );
                pdfSoa::metinEkleR($pdf, 185 , 47   , isset($arr['yd1SaatUcrt']) ? $arr['yd1SaatUcrt'] : ''        );
                                                      
                pdfSoa::metinEkle($pdf, 28  , 52    , isset($arr['yd2Adi']     ) ? $arr['yd2Adi']      : ''        );
                pdfSoa::metinEkle($pdf, 90  , 52    , isset($arr['yd2Grv']     ) ? $arr['yd2Grv']      : ''        );
                pdfSoa::metinEkleR($pdf, 185 , 52   , isset($arr['yd2SaatUcrt']) ? $arr['yd2SaatUcrt'] : ''        );
                                                      
                pdfSoa::metinEkle($pdf, 28  , 57    , isset($arr['yd3Adi']     ) ? $arr['yd3Adi']      : ''        );
                pdfSoa::metinEkle($pdf, 90  , 57    , isset($arr['yd3Grv']     ) ? $arr['yd3Grv']      : ''        );
                pdfSoa::metinEkleR($pdf, 185 , 57   , isset($arr['yd3SaatUcrt']) ? $arr['yd3SaatUcrt'] : ''        );
                //Yedek Denetçiler
                
                pdfSoa::metinEkle($pdf, 78  , 93   , $arr['teslimTrh']              );
                pdfSoa::metinEkle($pdf, 89  , 254.5, $arr['sirketAdi'] ,array('arial_tr','',9));
                
                $pdf->SetXY(26,146);
                $pdf->MultiCell(163,5,iconv('utf-8','iso-8859-9//TRANSLIT',$arr['ozel']),0,'J',0);
            }
        }
    }
    
    //TAMAMLANDI
    private static function sozlesmeBddkEk4Hazirla ($pdf,$pageCount,$arr){
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            if ($size['width'] > $size['height']) {
                $pdf->AddPage('L', array($size['width'], $size['height']));
            } else {
                $pdf->AddPage('P', array($size['width'], $size['height']));
            }
            $pdf->useTemplate($templateId);
            
            if($pageNo == 1 ){
                pdfSoa::metinEkle($pdf, 100 , 35    , $arr['id']            , array('arial_tr','B',11));
                pdfSoa::metinEkle($pdf, 63.5  , 68    , $arr['sirketAdi']     , array('arial_tr','',10));
            }else if($pageNo == 2){
                pdfSoa::metinEkle($pdf, 90  , 202  , $arr['ucret']          , array('arial_tr','B',10));
                
                //Denetçiler
                pdfSoa::metinEkle( $pdf,28  , 226.3   , isset($arr['ad1Adi']     ) ? $arr['ad1Adi']      : ''   , array('arial_tr','',10));
                pdfSoa::metinEkle( $pdf,74.5, 226.3   , isset($arr['ad1Grv']     ) ? $arr['ad1Grv']      : ''   );
                pdfSoa::metinEkleR($pdf,123 , 226.3   , isset($arr['ad1CalsmSr'] ) ? $arr['ad1CalsmSr']  : ''   );
                pdfSoa::metinEkleR($pdf,150 , 226.3   , isset($arr['ad1SaatUcrt']) ? $arr['ad1SaatUcrt'] : ''   );
                pdfSoa::metinEkleR($pdf,185 , 226.3   , isset($arr['ad1Ucrt']    ) ? $arr['ad1Ucrt']     : ''   );
                                                                              
                pdfSoa::metinEkle( $pdf,28  , 231.3   , isset($arr['ad2Adi']     ) ? $arr['ad2Adi']      : ''   );
                pdfSoa::metinEkle( $pdf,74.5, 231.3   , isset($arr['ad2Grv']     ) ? $arr['ad2Grv']      : ''   );
                pdfSoa::metinEkleR($pdf,123 , 231.3   , isset($arr['ad2CalsmSr'] ) ? $arr['ad2CalsmSr']  : ''   );
                pdfSoa::metinEkleR($pdf,150 , 231.3   , isset($arr['ad2SaatUcrt']) ? $arr['ad2SaatUcrt'] : ''   );
                pdfSoa::metinEkleR($pdf,185 , 231.3   , isset($arr['ad2Ucrt']    ) ? $arr['ad2Ucrt']     : ''   );
                                                                              
                pdfSoa::metinEkle( $pdf,28  , 236.6   , isset($arr['ad3Adi']     ) ? $arr['ad3Adi']      : ''   );
                pdfSoa::metinEkle( $pdf,74.5, 236.6   , isset($arr['ad3Grv']     ) ? $arr['ad3Grv']      : ''   );
                pdfSoa::metinEkleR($pdf,123 , 236.6   , isset($arr['ad3CalsmSr'] ) ? $arr['ad3CalsmSr']  : ''   );
                pdfSoa::metinEkleR($pdf,150 , 236.6   , isset($arr['ad3SaatUcrt']) ? $arr['ad3SaatUcrt'] : ''   );
                pdfSoa::metinEkleR($pdf,185 , 236.6   , isset($arr['ad3Ucrt']    ) ? $arr['ad3Ucrt']     : ''   );
                                                                              
                pdfSoa::metinEkleR($pdf,123 , 241.9   , $arr['tplSaat']);
                pdfSoa::metinEkleR($pdf,185 , 241.9   , $arr['tplUcret']);
                //Denetçiler                                                  
                                                                              
                //Yedek Denetçiler                                            
                pdfSoa::metinEkle($pdf, 28  , 264.5   , isset($arr['yd1Adi']     ) ? $arr['yd1Adi']      : '' );
                pdfSoa::metinEkle($pdf, 90  , 264.5   , isset($arr['yd1Grv']     ) ? $arr['yd1Grv']      : '' );
                pdfSoa::metinEkleR($pdf,185 , 264.5   , isset($arr['yd1SaatUcrt']) ? $arr['yd1SaatUcrt'] : '' );
                                                                              
                pdfSoa::metinEkle($pdf, 28  , 269.5   , isset($arr['yd2Adi']     ) ? $arr['yd2Adi']      : '' );
                pdfSoa::metinEkle($pdf, 90  , 269.5   , isset($arr['yd2Grv']     ) ? $arr['yd2Grv']      : '' );
                pdfSoa::metinEkleR($pdf,185 , 269.5   , isset($arr['yd2SaatUcrt']) ? $arr['yd2SaatUcrt'] : '' );
                                                                              
                pdfSoa::metinEkle($pdf, 28  , 274.5   , isset($arr['yd3Adi']     ) ? $arr['yd3Adi']      : '' );
                pdfSoa::metinEkle($pdf, 90  , 274.5   , isset($arr['yd3Grv']     ) ? $arr['yd3Grv']      : '' );
                pdfSoa::metinEkleR($pdf,185 , 274.5   , isset($arr['yd3SaatUcrt']) ? $arr['yd3SaatUcrt'] : '' );
                //Yedek Denetçiler
                //Denetçiler
            }else if($pageNo == 3){
                pdfSoa::metinEkle($pdf, 76 , 56     , $arr['teslimTrh']  );
                pdfSoa::metinEkle($pdf, 89 , 257    , $arr['sirketAdi'] ,array('arial_tr','',9));

                $pdf->SetXY(26,112);
                $pdf->MultiCell(163,5,iconv('utf-8','iso-8859-9//TRANSLIT',$arr['ozel']),0,'J',0);
                
            }
        }
    }
    
    //TAMAMLANDI
    private static function sozlesmeBagimsizDenetimHazirla ($pdf,$pageCount,$arr){
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            if ($size['width'] > $size['height']) {
                $pdf->AddPage('L', array($size['width'], $size['height']));
            } else {
                $pdf->AddPage('P', array($size['width'], $size['height']));
            }
            $pdf->useTemplate($templateId);
            
            if($pageNo == 1 ){
                pdfSoa::metinEkle($pdf, 100 , 35    , $arr['id']        , array('arial_tr','B',11));
                pdfSoa::metinEkle($pdf, 64  , 67    , $arr['sirketAdi'] , array('arial_tr','',10));
                pdfSoa::metinEkle($pdf, 90  , 114.4 , $arr['gtarih']    );
                pdfSoa::metinEkle($pdf, 90  , 144.5 , $arr['donem']     );
                pdfSoa::metinEkle($pdf, 90  , 152   , $arr['finansal']  );
            }else if($pageNo == 4){
                pdfSoa::metinEkle($pdf, 89  , 243.5 , $arr['sirketAdi'], array('arial_tr','',9));
                
                $pdf->SetXY(26,97);
                $pdf->MultiCell(163,5,iconv('utf-8','iso-8859-9//TRANSLIT',$arr['ozel']),0,'J',0);
            }else if($pageNo == 3){
                pdfSoa::metinEkle($pdf, 90  , 104    , $arr['ucret']          , array('arial_tr','B',10));
                
                //Denetçiler
                pdfSoa::metinEkle($pdf,  28  , 128.5 , isset($arr['ad1Adi']     ) ? $arr['ad1Adi']     : ''   ,array('arial_tr','',10));
                pdfSoa::metinEkle($pdf,  74.5, 128.5 , isset($arr['ad1Grv']     ) ? $arr['ad1Grv']     : ''   );
                pdfSoa::metinEkleR($pdf, 123 , 128.5 , isset($arr['ad1CalsmSr'] ) ? $arr['ad1CalsmSr'] : ''   );
                pdfSoa::metinEkleR($pdf, 150 , 128.5 , isset($arr['ad1SaatUcrt']) ? $arr['ad1SaatUcrt']: ''   );
                pdfSoa::metinEkleR($pdf, 185 , 128.5 , isset($arr['ad1Ucrt']    ) ? $arr['ad1Ucrt']    : ''   );
                
                pdfSoa::metinEkle($pdf,  28  , 133.8 , isset($arr['ad2Adi']     ) ? $arr['ad2Adi']     : ''   );
                pdfSoa::metinEkle($pdf,  74.5, 133.8 , isset($arr['ad2Grv']     ) ? $arr['ad2Grv']     : ''   );
                pdfSoa::metinEkleR($pdf, 123 , 133.8 , isset($arr['ad2CalsmSr'] ) ? $arr['ad2CalsmSr'] : ''   );
                pdfSoa::metinEkleR($pdf, 150 , 133.8 , isset($arr['ad2SaatUcrt']) ? $arr['ad2SaatUcrt']: ''   );
                pdfSoa::metinEkleR($pdf, 185 , 133.8 , isset($arr['ad2Ucrt']    ) ? $arr['ad2Ucrt']    : ''   );
                
                pdfSoa::metinEkle($pdf,  28  , 139.1 , isset($arr['ad3Adi']     ) ? $arr['ad3Adi']     : ''   );
                pdfSoa::metinEkle($pdf,  74.5, 139.1 , isset($arr['ad3Grv']     ) ? $arr['ad3Grv']     : ''   );
                pdfSoa::metinEkleR($pdf, 123 , 139.1 , isset($arr['ad3CalsmSr'] ) ? $arr['ad3CalsmSr'] : ''   );
                pdfSoa::metinEkleR($pdf, 150 , 139.1 , isset($arr['ad3SaatUcrt']) ? $arr['ad3SaatUcrt']: ''   );
                pdfSoa::metinEkleR($pdf, 185 , 139.1 , isset($arr['ad3Ucrt']    ) ? $arr['ad3Ucrt']    : ''   );
                
                pdfSoa::metinEkleR($pdf, 123 , 144.4 , $arr['tplSaat']       );
                pdfSoa::metinEkleR($pdf, 185 , 144.4 , $arr['tplUcret']      );
                //Denetçiler
                
                //Yedek Denetçiler
                pdfSoa::metinEkle($pdf, 28  , 167   , isset($arr['yd1Adi']     ) ? $arr['yd1Adi']     : '' );
                pdfSoa::metinEkle($pdf, 90  , 167   , isset($arr['yd1Grv']     ) ? $arr['yd1Grv']     : '' );
                pdfSoa::metinEkleR($pdf, 185 , 167  , isset($arr['yd1SaatUcrt']) ? $arr['yd1SaatUcrt']: '' );
                                                 
                pdfSoa::metinEkle($pdf, 28  , 172   , isset($arr['yd2Adi']     ) ? $arr['yd2Adi']     : '' );
                pdfSoa::metinEkle($pdf, 90  , 172   , isset($arr['yd2Grv']     ) ? $arr['yd2Grv']     : '' );
                pdfSoa::metinEkleR($pdf, 185 , 172  , isset($arr['yd2SaatUcrt']) ? $arr['yd2SaatUcrt']: '' );
                                                 
                pdfSoa::metinEkle($pdf, 28  , 177   , isset($arr['yd3Adi']     ) ? $arr['yd3Adi']     : '' );
                pdfSoa::metinEkle($pdf, 90  , 177   , isset($arr['yd3Grv']     ) ? $arr['yd3Grv']     : '' );
                pdfSoa::metinEkleR($pdf, 185 , 177  , isset($arr['yd3SaatUcrt']) ? $arr['yd3SaatUcrt']: '' );
                //Yedek Denetçiler
                
                pdfSoa::metinEkle($pdf, 77  , 232 , $arr['teslimTrh']  );
                pdfSoa::metinEkle($pdf, 77  , 257 , $arr['raporDil']  );
            }
        }
    }
    
    private static function teklifSozlesmesiHazirla_eng ($pdf,$pageCount,$bilgiArr){
        
    }
    
    private static function teklifSozlesmesiHazirla_tr ($pdf,$pageCount,$bilgiArr){
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            if ($size['width'] > $size['height']) {
                $pdf->AddPage('L', array($size['width'], $size['height']));
            } else {
                $pdf->AddPage('P', array($size['width'], $size['height']));
            }
            $pdf->useTemplate($templateId);
            if($pageNo == 27 ){
                $pdf->SetFont('arial_tr','',12);
                $pdf->SetY(60);
                $pdf->SetWidths(array(155,155));
                foreach ($bilgiArr['tablo'] as $rslt){
                    $pdf->Row(10,array($rslt[0],$rslt[1]));
                }
            }else if($pageNo == 26){
                $pdf->SetFont('arial_tr','',16);
                $pdf->SetXY(20,55);
                $pdf->SetWidths(array(290));
                $pdf->undrawRow(6,array($bilgiArr['ozel']));
            }else if($pageNo == 1){
                $pdf->SetFont('arial_tr','B',20);
                $pdf->SetXY(20,120);
                $pdf->Write(0, iconv('utf-8','iso-8859-9',$bilgiArr['musteri']));
                $pdf->SetFont('arial_tr','B',20);
//                 $pdf->SetXY(20,130);
//                 $pdf->Write(0, iconv('utf-8','iso-8859-9','Yönetim Kurulu\'na'));
                $pdf->SetFont('arial_tr','',20);
                $pdf->SetXY(20,150);
                $pdf->Write(0, iconv('utf-8','iso-8859-9','Denetim Hizmet Teklifi, '.$bilgiArr['teklifTrh'] ));
            }
        }
    }
    
    
    private static function mkBeyanHazirla ($pdf,$pageCount,$bilgiArr){
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            if ($size['width'] > $size['height']) {
                $pdf->AddPage('L', array($size['width'], $size['height']));
            } else {
                $pdf->AddPage('P', array($size['width'], $size['height']));
            }
            $pdf->useTemplate($templateId);
            if($pageNo == 1 ){
                
                pdfSoa::metinEkle($pdf, 77 , 43  , $bilgiArr['sirketAdi'] , array('arial_tr','',9));
                pdfSoa::metinEkle($pdf, 77 , 254 , $bilgiArr['denetciAd'] , array('arial_tr','',9));
                pdfSoa::metinEkle($pdf, 77 , 258 , $bilgiArr['ekip'] );
            }
        }
    }
    
    private static function mkBeyanSozlesmeHazirla ($pdf,$pageCount,$bilgiArr){
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            if ($size['width'] > $size['height']) {
                $pdf->AddPage('L', array($size['width'], $size['height']));
            } else {
                $pdf->AddPage('P', array($size['width'], $size['height']));
            }
            $pdf->useTemplate($templateId);
            if($pageNo == 1 ){
                pdfSoa::metinEkle($pdf, 76 , 47  , $bilgiArr['sirketUnvan'] , array('arial_tr','B',10));
                pdfSoa::metinEkle($pdf, 76 , 55  , $bilgiArr['donem'] );
                pdfSoa::metinEkle($pdf, 76 , 63.5, $bilgiArr['sozlesme'] );
            }
        }
    }
    
    private static function mkBeyanBagimsizHazirla ($pdf,$pageCount,$bilgiArr){
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            if ($size['width'] > $size['height']) {
                $pdf->AddPage('L', array($size['width'], $size['height']));
            } else {
                $pdf->AddPage('P', array($size['width'], $size['height']));
            }
            $pdf->useTemplate($templateId);
            if($pageNo == 1 ){
                pdfSoa::metinEkle($pdf, 76 , 38  , $bilgiArr['sirketUnvan'] , array('arial_tr','B',10));
                pdfSoa::metinEkle($pdf, 76 , 46  , $bilgiArr['donem'] );
                pdfSoa::metinEkle($pdf, 76 , 54.5, $bilgiArr['sozlesme'] );
            }
        }
    }
    
    private static function mkDenetciSozlesmeHazirla ($pdf,$pageCount,$bilgiArr){
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            if ($size['width'] > $size['height']) {
                $pdf->AddPage('L', array($size['width'], $size['height']));
            } else {
                $pdf->AddPage('P', array($size['width'], $size['height']));
            }
            $pdf->useTemplate($templateId);
            if($pageNo == 1 ){
                       
                pdfSoa::metinEkle($pdf, 77 , 43  , $bilgiArr['sirketAdi'] , array('arial_tr','',9));
                pdfSoa::metinEkle($pdf, 77 , 254 , $bilgiArr['denetciAd'] , array('arial_tr','',9));
                pdfSoa::metinEkle($pdf, 77 , 258 , $bilgiArr['ekip'] );
            }
        }
    }
    
//     private static function metinPozisyon($pdf,$x,$y,$font = null){
//     }
    
    public static function metinEkle ($pdf,$x,$y,$metin,$font = null){
        if ($font != null){
            $pdf->SetFont($font[0],$font[1],$font[2]);
        }
        $pdf->SetXY($x, $y);
        $pdf->Write(0, iconv('utf-8','iso-8859-9',$metin));
    }
    
    public static function metinEkleR ($pdf,$x,$y,$metin,$font = null){
        if ($font != null){
            $pdf->SetFont($font[0],$font[1],$font[2]);
        }
        $pdf->SetXY($x-(strlen($metin)*1.7), $y);
        $pdf->Write(0, iconv('utf-8','iso-8859-9',$metin));
    }
    
}

// pdfSoa::imzasizSozlesmeYukle(202020);

// session_start();
// pdfSoa::imzasizSozlesmeYukle("202070",'bagimsiz_denetim_sozlesme');
//pdfSoa::sozlesmeYukle("1234",'tcmb_sozlesme');
