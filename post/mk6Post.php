<?php 
include_once 'basePost.php';
include_once PREPATH.'soa/mkSoa.php';

try {
    if ($_GET['tur']== 'prosedur'){
        if($_GET['islem']== 'riskleriGetir'){
            $sql = "SELECT m.grup as mgrup,m.kod as mkod,r.kod as rkod,r.adi, p.sonuc FROM ";
            $sql.= Config::DB_DATABASE.".mk_risk m, ".Config::DB_DATABASE.".risk_list r, ".Config::DB_DATABASE.".prosedur p ";
            $sql.= "WHERE m.risk_id = r.id AND p.tklf_id = m.tklf_id AND p.grup = m.grup AND p.tip = '".mkConfig::PROSEDUR_TIP_MK."' and p.kod = m.kod AND m.tklf_id=".$_POST['tklf_id'];
            $result = Crud::selectSql($sql);
            cevapDondur($result);
        }else if($_GET['islem']== 'turliste'){
           $list = Crud::getSqlCok(new Prosedur(), Prosedur::GET_BY_TEKLIF, array('tklf_id'=>$_POST['tklf_id']));
           cevapDondur(json_encode($list,JSON_UNESCAPED_UNICODE));
        }
    }else if ($_GET['tur']== 'degerlendirme'){
        if($_GET['islem']== 'kaydet'){
            $tbl = Crud::getById(new MusteriKabul(),$_POST['id']);
            $tbl->degerlendirme->deger = $_POST['deger'];
            if ($tbl->durum->deger == mkConfig::DURUM_BASLANMADI[0]) {
                $tbl->durum->deger = mkConfig::DURUM_DEVAM_EDIYOR[0];
            }
            $result = Crud::update($tbl);
            if ($result==1){
                mkSoa::mkDurumGuncelleme($tbl->tklf_id->deger);
                cevapDondur("Değerlendirme kaydedildi.");
            }else{
                hataDondur($result);
            }
        }else if($_GET['islem']== 'onayRed'){
            $tbl = Crud::getById(new MusteriKabul(),$_POST['id']);
            if ($tbl->durum->deger == mkConfig::DURUM_TAMAMLANDI[0]){
                $tbl->kabul->deger = $_POST['onayRed'];
                $tbl->durum->deger = mkConfig::DURUM_ONAYLANDI[0];
                $result = Crud::update($tbl);
                if ($result==1){
                    cevapDondur("Değerlendirme kaydedildi.");
                }else{
                    hataDondur($result);
                }
            }else{
                hataDondur('Müşteri kabul henüz tamamlanmadı. Eksik kısımlar var.');
            }
        }
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}