<?php 
include_once 'baseSoa.php';
include_once PREPATH.'config/sozlesmeConfig.php';
include_once 'driveSoa.php';
include_once 'mailSoa.php';

Class sozlesmeSoa extends BaseSoa{
    
    public static function createSozlesme($tklf_id,$session = null){
        $result['hata'] = true;
        $result['ht_ack'] = 'Boş';
        $varmi = Crud::getSqlTek(new Sozlesme(), Sozlesme::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
        if ($varmi != null){
            $result['hata'] = false;
            $result['ht_ack'] = '';
            return $result;
        }
        
        if ($session == null){
            $db = new Db();
            $ses = $db->getCon();
        }else{
            $ses = $session;
        }
        try {
            $tklf_ham = Crud::getById(new Denetim() , $tklf_id );
            if ($tklf_ham == null){
                $result['ht_ack'] = 'Teklif id ile ilgili bir kayıt bulunamadı.';
                return $result;
            }
            $tklf = $tklf_ham -> basit();
            if (DenetimDurum::DURUM_ONAYLI != $tklf['durum_id']['id']){
                $result['ht_ack'] = 'Bu durumda Sözleşme oluşturulamaz.';
                return $result;
            }
            
            $mk = new Sozlesme();
            $mk->tklf_id->deger = $tklf['id'];
            $mk->no->deger      = $tklf['id'];
            $mk->durum->deger   = sozlesmeConfig::DURUM_OLUSMADI[0];
            $snc = Crud::save($mk,$ses);
            if ($snc!=1){
                throw new Exception($snc);
            }
            
            if ($session == null){
                $ses->commit();
            }
            $result['hata'] = false;
            $result['ht_ack'] = '';
            return $result;
        } catch (Exception $e) {
            if ($session == null &&isset($ses)){
                $ses->rollback();
            }
            $result['ht_ack'] = $e;
            return $result;
        }finally {
            if ($session == null && isset($ses)){
                mysqli_close($ses);
            }
        }
    }
    
    public static function sozlesmeEmailGonder($tklf_id){
        try {
            $tklf   = Crud::getById(new Denetim(),$tklf_id) -> basit();
            if ($tklf['email'] != null){
                $szlsm  = Crud::getSqlTek(new Sozlesme(), Sozlesme::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
                if ($szlsm->imzasiz_drive_id->deger == null) {
                    return 'Sözleşme Düzenlenmedi.';
                }
                $drive = driveSoa::getir($szlsm->imzasiz_drive_id->deger);
                if(!file_put_contents( PREPATH.config::GECICI_KLASOR.$drive->name,file_get_contents($drive->url))) {
                    return 'Dosya indirilemedi.';
                }
                
                $sablon = Crud::getSqlTek(new mailSablon(), mailSablon::GET_KEY, array('skey'=>config::MAIL_SABLON_SOZLESME))->basit();
                $keyler = array (
                    '#musteriAd#'   => $tklf['musteri_id']['unvan'],
                    //'#deneme#'      => $tklf['yonay_trh']
                );
                $sablon = mailSoa::sablonKetSetle($sablon, $keyler);
                mailSoa::mailAt($tklf['email'], $tklf['musteri_id']['unvan'], $sablon,array($drive->name),true);
                unlink(PREPATH.config::GECICI_KLASOR.$drive->name);
                
                $szlsm->durum->deger = sozlesmeConfig::DURUM_IMZAYI_BEKLE[0];
                $result = Crud::update($szlsm);
                if ($result!=1){
                    return $result;
                }
                return true;
            }else{
                return 'Email eklenmemiş.';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
}

