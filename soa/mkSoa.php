<?php 
include_once 'baseSoa.php';
include_once PREPATH.'config/mkConfig.php';
include_once PREPATH.'config/sablonConfig.php';
include_once 'driveSoa.php';
include_once 'genelSoa.php';
include_once PREPATH.'db/Crud.php';

Class mkSoa extends BaseSoa{
    
    public static function mkDenetcisiMisin($tklf_id,$id){
        $list =  mkSoa::getDenetciList($tklf_id);
        if ($list != null){
            foreach ($list as $l){
                if ($l['ekip'] == mkConfig::EKIP_YARDIMCI_EKIP[1] && $l['denetci_id']['id'] == $id){
                    return true;
                }
            }
        }
        return false;
    }
    
    public static function aktifProsedurler (){
        $return  = Base::basitList(Crud::all(new MKListesi()));
        return $return;
    }
    
    public static function aktifPlanProsedurler (){
        $return  = Base::basitList(Crud::all(new PlanListesi()));
        return $return;
    }
    
    public static function riskGetir ($tklfId, $ciddi = false){
        $sql = "SELECT m.grup as mgrup,m.kod as mkod,r.kod as rkod,r.adi, p.sonuc FROM ";
        $sql.= Config::DB_DATABASE.".mk_risk m, ".Config::DB_DATABASE.".risk_list r, ".Config::DB_DATABASE.".prosedur p ";
        $sql.= "WHERE m.risk_id = r.id AND p.tklf_id = m.tklf_id AND p.grup = m.grup and p.kod = m.kod AND m.tklf_id=".$tklfId;
        $result = Crud::selectSql($sql);
        if ($ciddi){
            $dnn = array();
            foreach ($result as $val){
                if ($val['sonuc'] == mkConfig::PROSEDUR_SONUC_CIDDI){
                    array_push($dnn,$val);
                }
            }
            $result = $dnn;
        }
        return $result;
    }
    
    public static function eskifirmaGunluk($mstrId,$tklf_id){
        $result = array();
        $eskiIsler = mkSoa::eskifirmaList($mstrId, $tklf_id);
        foreach ($eskiIsler as $v){
            if ($v['selected']){
                $yillar = BaseSoa::donemGetir(7,$v['donem_bas_trh']);
            }
        }
        if (isset($yillar)){
            foreach ($yillar as $y){
                if (isset($eskiIsler[$y])){
                    $result[$y] = $eskiIsler[$y];
                }else{
                    $result[$y] = null;
                }
            }
        }
        return $result;
    }
    
    public static function eskifirmaList($mstrId,$tklf_id){
        $result = array();
        $list = Crud::getSqlCokTblsiz(null, MusteriKabul::GECMIS_BY_MUSTERIID, array('mstr_id'=>$mstrId));
        if ($list != null ){
            foreach ($list as $l){
                if ($l['tklf_id'] == $tklf_id) {
                    $l['selected'] = 1;
                } else {
                    $l['selected'] = 0;
                }
                $result[$l['trh']] = $l;
            }
        }
        return $result;
    }
    
    public static function eskiDenetciListMstr($tklf_id,$dntmLst){
        $buDenetci = null;
        $dntci = array();
        foreach ($dntmLst as $v){
            if ($v != null){
                $dntc = mkSoa::getDenetciList($v['tklf_id']);
                if ($buDenetci == null){
                    $buDenetci = $dntc;
                }
                if ($dntc != null && $buDenetci != null){
                    foreach ($dntc as $val ){
                        if ($val['ekip'] == mkConfig::EKIP_ASIL_EKIP[1]){
                            $var = false;
                            foreach ($buDenetci as $vth ){
                                if ($vth['denetci_id']['id'] == $val['denetci_id']['id']){
                                    if (!isset($dntci[$val['denetci_id']['id']])){
                                        foreach (BaseSoa::donemGetir(5,$v['donem_bas_trh']) as $trh){
                                            $dntci[$val['denetci_id']['id']]['isler'][$trh] = null;
                                        }
                                    }
                                    $var = true;
                                    break;
                                }
                            }
                            if ($var){
                                $y = BaseSoa::trhParcalaGun2($v['donem_bas_trh'])['y'];
                                $dntci[$val['denetci_id']['id']]['ad_soyad'] = $val['denetci_id']['ad'].' '.$val['denetci_id']['soyad'];
                                $dntci[$val['denetci_id']['id']]['isler'][$y]['tklf_id'] = $v['tklf_id']; //    [$v['tklf_id']]['ekip'] = $val['ekip'];
                                $dntci[$val['denetci_id']['id']]['isler'][$y]['gorev'] = $val['gorev'];
                                $dntci[$val['denetci_id']['id']]['isler'][$y]['bas_trh'] = $v['donem_bas_trh'];
                                $dntci[$val['denetci_id']['id']]['isler'][$y]['bts_trh'] = $v['donem_bts_trh'];
                            }
                        }
                    }
                }
            }
        }
        foreach ($dntci as $k => $v){
            $adet = 0;
            $seri = true;
            foreach ($v['isler'] as $d){
                if ($d != null && $seri){
                    $adet++;
                }else{
                    $seri = false;
                }
            }
            if($adet >= 5){
                $dntci[$k]['uygun'] = 'false';
            }else{
                $dntci[$k]['uygun'] = 'true';
            }
        }
        return $dntci;
    }

    
    public static function eskiDenetciList($mstrId,$tklf_id){
        $dntmLst = mkSoa::eskifirmaList($mstrId, $tklf_id);
        return mkSoa::eskiDenetciListMstr($tklf_id,$dntmLst);
    }
    
    
    public static function mkDurumGuncelleme($tklf_id){
        $mk = Crud::getSqlTek(new MusteriKabul(), MusteriKabul::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
        if ($mk->durum->deger != mkConfig::DURUM_ONAYLANDI[0]){
            $result = mkSoa::mkKapakUyarilarTklfId($tklf_id);
            $full = 't';
            foreach ($result as $deger){
                if ($deger['durum'] != 2){
                    $full = 'f';
                    break;
                }
                if($deger['sayilar']['not'] != 0){
                    $full = 'f';
                    break;
                }
            }
            
            //echo $tklf_id.' - '.$mk->durum->deger.' - '.mkConfig::DURUM_TAMAMLANDI[0].' - '.$full.' - ';
            $snc = 1;
            if (($mk->durum->deger == mkConfig::DURUM_BASLANMADI[0] || $mk->durum->deger == mkConfig::DURUM_TAMAMLANDI[0]) && $full == 'f') {
                $mk->durum->deger = mkConfig::DURUM_DEVAM_EDIYOR[0];
                $snc = Crud::update($mk);
            }else if ($mk->durum->deger == mkConfig::DURUM_DEVAM_EDIYOR[0] && $full == 't') {
                $mk->durum->deger = mkConfig::DURUM_TAMAMLANDI[0];
                $snc = Crud::update($mk);
            }
            if ($snc!=1){
                throw new Exception($snc);
            }
        }
        
    }
    
    public static function mkKapakUyarilarTklfId($tklf_id){
        $prosedurs  = mkSoa::prosedurlerHepsi($tklf_id);
        $notCheckler= mkSoa::notlarKontrol($tklf_id);
        $mk0        = Crud::getSqlTek(new MK0(), MK0::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
        $mk         = Crud::getSqlTek(new MusteriKabul(), MusteriKabul::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
        $result     = mkSoa::mkKapakUyarilar($tklf_id,$prosedurs,$notCheckler,$mk0,$mk);
        return $result;
    }
    
    
    public static function mkKapakUyarilar($tklf_id,$prosedurs,$notCheckler,$mk0,$mk){
        $mkUyari = array();
        
        $mkUyari[mkConfig::MK0]['eksik'] = mkSoa::mk0KapakUyarilar($tklf_id,$mk0);
        $mkUyari[mkConfig::MK1]['eksik']['yeni'] = true;
        $mkUyari[mkConfig::MK2]['eksik']['yeni'] = true;
        $mkUyari[mkConfig::MK3]['eksik']['yeni'] = true;
        $mkUyari[mkConfig::MK4]['eksik'] =  mkSoa::mk4KapakUyarilar($tklf_id,$mk);
        $mkUyari[mkConfig::MK5]['eksik']['yeni'] = true;
        $mkUyari[mkConfig::MK6]['eksik'] =  mkSoa::mk6KapakUyarilar($mk);
        
        foreach (mkConfig::MK_LIST as $sira){
            $mkUyari[$sira[0]]['baslik'] = $sira[0].' - '.$sira[1];
            
            $notSys     = 0;
            $eksikSys   = count($mkUyari[$sira[0]]['eksik'])-1;
            $prdrSys    = 0;
            $prdrOKSys  = 0;
            if (isset($prosedurs[$sira[0]])) {
                foreach ($prosedurs[$sira[0]] as $k => $d) {
                    if ($d->sonuc->deger == null){
                        $mkUyari[$sira[0]]['prosedur'][$k] = true;
                        $prdrSys++;
                    }else{
                        $prdrOKSys++;
                    }
                }
                
            }
            if (isset($notCheckler[$sira[0]])) {
                foreach ($notCheckler[$sira[0]] as $k => $d) {
                    $mkUyari[$sira[0]]['notVarmi'][$k] = $d;
                    if ($d == 1){
                        $notSys++;
                    }
                }
            }
            
            if ($prdrOKSys == 0 && $notSys == 0 && $mkUyari[$sira[0]]['eksik']['yeni'] ){
                $mkUyari[$sira[0]]['durum'] = 0;
            }else if ($prdrSys == 0 && $notSys == 0 && $eksikSys == 0){
                $mkUyari[$sira[0]]['durum'] = 2;
            }else{
                $mkUyari[$sira[0]]['durum'] = 1;
            }
            unset($mkUyari[$sira[0]]['eksik']['yeni']);
            $mkUyari[$sira[0]]['sayilar']['eksik'] = $eksikSys;
            $mkUyari[$sira[0]]['sayilar']['not']   = $notSys;
            $mkUyari[$sira[0]]['sayilar']['prdr']  = $prdrSys;
            $mkUyari[$sira[0]]['sayilar']['prdrOK']= $prdrOKSys;
        }
        return $mkUyari;
    }
    
    private static function mk6KapakUyarilar($mk6){
        $mk6Uyari = array();
        $i=0;
//         if ($mk6->kabul->deger == null){
//             $mk6Uyari[$i++]  = "Henüz 'KABUL' yada 'RED' edilmedi.";
//         }
        if ($mk6->degerlendirme->deger == null || $mk6->degerlendirme->deger == ''){
            $mk6Uyari[$i++]  = "Henüz bir değerlendirme girilmedi.";
        }
        ($i == 1) ? $mk6Uyari['yeni'] = true : $mk6Uyari['yeni'] = false;
        return $mk6Uyari;
    }
    
    private static function mk4KapakUyarilar($tklf_id,$mk){
        $mk4Uyari = array();
        $i=0;
        $list = mkSoa::getDenetciList($tklf_id);
        if (isset($list)) {
            foreach ($list as $v){
                if ($v['ekip'] == mkConfig::EKIP_ASIL_EKIP[1] && $v['drive_id'] == null){
                    $mk4Uyari[$i++]  = $v['denetci_id']['ad'].' '.$v['denetci_id']['soyad'].' belge yüklememiş.';
                }
            }
        }
        
        if ($mk->belge1->deger == null){
            $mk4Uyari[$i++]  = sablonConfig::MKBEYAN[sablonConfig::MK_BEYAN_BAGIMSIZ].' belge eksik.';
        }
        
        if ($mk->belge1->deger == null){
            $mk4Uyari[$i++]  = sablonConfig::MKBEYAN[sablonConfig::MK_BEYAN_SOZLESME].' belge eksik.';
        }
        
        $tklf   = Crud::getById(new Denetim(),$tklf_id) -> basit();
        $eskiIsler      = mkSoa::eskifirmaGunluk($tklf['musteri_id']['id'], $tklf_id);
        $eskiDenetci    = mkSoa::eskiDenetciListMstr($tklf_id, $eskiIsler);
        if ($eskiDenetci != null){
            foreach ($eskiDenetci as $k1 => $v1) {
                if ($v1['uygun'] == 'false'){
                    $mk4Uyari[$i++]  = $v1['ad_soyad'].' bu denetçi bu işte çalışamaz.';
                }
            }
        }
        $yasak = true;
        foreach ($eskiIsler as $k => $v) {
            if ($v == null){
                $yasak = false;
            }
        }
        if ($yasak){
            $mk4Uyari[$i++]  = 'Bu müşteri ile bu dönem çalışamazsınız.';
        }
        
        ($list == null || $i == count($list)) ? $mk4Uyari['yeni'] = true : $mk4Uyari['yeni'] = false;
        return $mk4Uyari;
    }
    
    private static function mk0KapakUyarilar($tklf_id,$mk0){
        $tklf   = Crud::getById(new Denetim(),$tklf_id) -> basit();
        $szlsm  = Crud::getSqlTek(new Sozlesme(), Sozlesme::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id))->basit();
        $list = mkSoa::getDenetciList($tklf_id);
        $tutar = 0;
        
        if ($list != null){
            foreach ($list as $t){
                if ($t['ekip'] == mkConfig::EKIP_ASIL_EKIP[1]){
                    $tutar = $tutar + $t['ucret'];
                }
            }
        }
        
        $dntUygn = mkSoa::getDenetciUygunmu($tklf_id,$list);
        
        $mk0Uyari = array();
        $i=0;
        if ($mk0->denetim_maddesi->deger == null){
            $mk0Uyari[$i++]  = 'Şirketin, Kararın Hangi Maddesi Uyarınca Denetim Kapsamında Olduğunu Seçiniz';
        }
        if ($mk0->kayik->deger == null){
            $mk0Uyari[$i++] = 'Müşteri KAYİK mi?';
        }
        if ($mk0->halka_acik->deger == null){
            $mk0Uyari[$i++] = 'Müşteri Halka Açık Bir Şirket mi?';
        }
        if ($dntUygn['asil_s'] < 1){
            $mk0Uyari[$i++] =  'Bir asıl sorumlu denetçi olmak zorundadır.';
        }
        if ($dntUygn['yedek_s'] < 1){
            $mk0Uyari[$i++] =  'Bir yedek sorumlu denetçi olmak zorundadır.';
        }
        if ($dntUygn['asil'] < 3){
            $mk0Uyari[$i++] =  'En az 3 asıl denetçi olmak zorundadır.';
        }
        if ($dntUygn['yedek'] < 3){
            $mk0Uyari[$i++] =  'En az 3 yedek denetçi olmak zorundadır.';
        }
        if ($tutar != $tklf['tutar']){
            $mk0Uyari[$i++] =  "'Denetçi Denetim Üçretleri' ve 'Toplam Denetim Ücreti' eşit değil.";
        }
        if ($szlsm['denetim_imza_trh'] == ''){
            $mk0Uyari[$i++] =  "'Sözleşme Tarihi' dolu olmak zorunda.";
        }
        if ($tklf['musteri_id']['sicil_no'] == ''){
            $mk0Uyari[$i++] =  "Müşterinin 'Ticaret Sicil No' su dolu olmak zorunda.";
        }
        
        ($i < 0) ? $mk0Uyari['yeni'] = true : $mk0Uyari['yeni'] = false;
        return $mk0Uyari;
    }
    
    public static function createMusteriKabul($tklf_id,$session = null){
        $result['hata'] = true;
        $result['ht_ack'] = 'Boş';
        $varmi = Crud::getSqlTek(new MusteriKabul(), MusteriKabul::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
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
                $result['ht_ack'] = 'Bu durumda Müşteri Kabul oluşturulamaz.';
                return $result;
            }
            
            $mk = new MusteriKabul();
            $mk->tklf_id->deger = $tklf['id'];
            $mk->durum->deger   = mkConfig::DURUM_BASLANMADI[0];
            $mk->no->deger      = $tklf['id'];
            $snc = Crud::save($mk,$ses);
            if ($snc!=1){
                throw new Exception($snc);
            }
            mkSoa::musteriTekOlutur($ses,new MK0(),$tklf['id']);
            mkSoa::musteriTekOlutur($ses,new MK2(),$tklf['id']);
            
            $prsdrler = mkSoa::aktifProsedurler();
            foreach ($prsdrler as $prsdr){
                $nw = new Prosedur();
                $nw->tklf_id->deger    = $tklf['id'];
                $nw->ref_id->deger     = $prsdr['id'];
                $nw->grup->deger       = $prsdr['grup'];
                $nw->kod->deger        = $prsdr['kod'];
                $nw->tip->deger        = mkConfig::PROSEDUR_TIP_MK;
                $snc = Crud::save($nw,$ses);
                if ($snc!=1){
                    throw new Exception($snc);
                }
            }
            
            $pprsdrler = mkSoa::aktifPlanProsedurler();
            foreach ($pprsdrler as $prsdr){
                $nw = new Prosedur();
                $nw->tklf_id->deger    = $tklf['id'];
                $nw->ref_id->deger     = $prsdr['id'];
                $nw->grup->deger       = $prsdr['grup'];
                $nw->kod->deger        = $prsdr['kod'];
                $nw->tip->deger        = mkConfig::PROSEDUR_TIP_PLAN;
                $snc = Crud::save($nw,$ses);
                if ($snc!=1){
                    throw new Exception($snc);
                }
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
    
    private static function musteriTekOlutur($ses,$mk,$id){
        $mk->tklf_id->deger = $id;
//         $mk->durum->deger   = mkConfig::DURUM_BASLANMADI[0];
        $result = Crud::save($mk,$ses);
        if ($result!=1){
            throw new Exception($result);
        }
    }
    
    public static function prosedurDriveIdSetle($tklf_id,$grp,$kod,$drive_id){
        $psdr = Crud::getSqlTek(new Prosedur(), Prosedur::GET_TEKLIF_BY_GRUP_KOD, array('tklf_id'=>$tklf_id,'grup'=>$grp,'kod'=>$kod));
        $psdr->drive_id->deger = addslashes($drive_id);
        $result = Crud::update($psdr);
        if ($result!=1){
            throw new Exception($result);
        }
    }
    
    public static function getDenetciKapak($tklf_id){
        $lst = Base::basitList(Crud::getSqlCok(new MKDenetci(), MKDenetci::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id)));
        $rst = array();
        if ($lst != null){
            foreach ($lst as $l){
                if ($l['ekip'] == mkConfig::EKIP_ASIL_EKIP[1]){
                    $rst[$l['pozisyon']] =$l['denetci_id']['ad'].' '.$l['denetci_id']['soyad'];
                }
                
//                 if ($l['ekip'] == mkConfig::EKIP_ASIL_EKIP[1] && $l['gorev'] == mkConfig::GOREV_SORUMLU[1]){
//                     $rst[0] = $l['denetci_id']['ad'].' '.$l['denetci_id']['soyad'];
//                 }else if($l['ekip'] == mkConfig::EKIP_ASIL_EKIP[1] && $l['gorev'] == mkConfig::GOREV_DENETCI[1]){
//                     if ($rst[1] == ''){
//                         $rst[1] = a$l['denetci_id']['ad'].' '.$l['denetci_id']['soyad'];
//                     }else{
//                         $rst[2] = $l['denetci_id']['ad'].' '.$l['denetci_id']['soyad'];
//                     }
//                 }
            }
        }
        return $rst;
    }
    
    public static function getDenetciIsimler($tklf_id){
        $sql = "select Concat(k.ad ,' ', k.soyad) as adSoyad,d.ekip from ".Config::DB_DATABASE.".kullanici k, ".Config::DB_DATABASE.".mk_denetci d, ".Config::DB_DATABASE.".denetim t where t.id = d.tklf_id and d.denetci_id = k.id and t.id = ".$tklf_id." order by d.id";
        return Crud::selectSql($sql);
    }
    
    public static function getDenetciList($tklf_id){
        return  Base::basitList(Crud::getSqlCok(new MKDenetci(), MKDenetci::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id)));
    }
    
    public static function getAsilDenetciList($tklf_id){
        return  Base::basitList(Crud::getSqlCok(new MKDenetci(), MKDenetci::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id)));
    }
    
    public static function getNotList($tklf_id,$grup,$kod){
        return Crud::selectSqlWithPrm(MKNot::NOTLAR_KISI_ISIMLER, array('tklf_id'=>$tklf_id,'grup'=>$grup,'kod'=>$kod));
//         return Base::basitList(Crud::getSqlCok(new MKNot(), MKNot::GET_TEKLIF_GRUP_KOD, array('tklf_id'=>$tklf_id,'grup'=>$grup,'kod'=>$kod)));
    }
    
    
    public static function getBeyanListResmi($tklf_id,$link){
        $result=array();
        try {
            $mk = Crud::getSqlTek(new MusteriKabul(), MusteriKabul::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
            $result[0]['key']  = sablonConfig::MK_BEYAN_BAGIMSIZ;
            if ($mk->belge1->deger != null){
                $drive = driveSoa::getir($mk->belge1->deger);
                $result[0]['id']   = $drive->id;
                $result[0]['url']  = $drive->url;
                $result[0]['web']  = $drive->webUrl;
            }else{
                $result[0]['id']   = null;
            }
            
            $result[1]['key']  = sablonConfig::MK_BEYAN_SOZLESME;
            if ($mk->belge2->deger != null){
                $drive = driveSoa::getir($mk->belge2->deger);
                $result[1]['id']   = $drive->id;
                $result[1]['url']  = $drive->url;
                $result[1]['web']  = $drive->webUrl;
            }else{
                $result[1]['id']   = null;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $result;
    }
    
    public static function getDenetciListResmi($tklf_id,$link){
        $list = mkSoa::getDenetciList($tklf_id);
        $nList = array();
        if (isset($list)){
            foreach ($list as $l){
                if ($l['ekip'] == mkConfig::EKIP_ASIL_EKIP[1] || $l['ekip'] == mkConfig::EKIP_YEDEK_EKIP[1]){
                    array_push($nList , $l);
                }
            }
            $mk = Crud::getSqlTek(new MusteriKabul(), MusteriKabul::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
            if (isset($mk) && $mk->denetci_drive_id->deger != null){
                $client = driveSoa::baglan($link);
                $driveList   = driveSoa::dosyaListesi($client, $mk->denetci_drive_id->deger);
                for ($x = 0; $x < count($nList); $x++) {
                    foreach ($driveList as $drive){
                        if ($drive->id == $nList[$x]['drive_id']){
                            $nList[$x]['url']     =$drive->url;
                            $nList[$x]['webUrl']  =$drive->webUrl;
                            break;
                        }
                    }
                }
                
            }
        }
        return $nList;
    }
    
    public static function getDenetciUygunmu($tklf_id, $list = null){
        $snc = array(
            'asil'   => 0,
            'yedek'  => 0,
            'asil_s' => 0,
            'yedek_s'=> 0,
            'saat'   => 0,
            'ucret'  => 0,
        );
        if ($list == null){
            $list = mkSoa::getDenetciList($tklf_id);
        }
        if ($list != null){
            foreach ($list as $gln){
                if ($gln['ekip'] == mkConfig::EKIP_ASIL_EKIP[1]){
                    $snc['asil'] = $snc['asil'] + 1;
                    if ($gln['gorev'] == mkConfig::GOREV_SORUMLU[1]){
                        $snc['asil_s'] = $snc['asil_s'] + 1;
                    }
                    if ($gln['saat'] == ''){
                        $snc['saat']++;
                    }
                    if ($gln['saat_ucret'] == ''){
                        $snc['ucret']++;
                    }
                }else if ($gln['ekip'] == mkConfig::EKIP_YEDEK_EKIP[1]){
                    $snc['yedek'] = $snc['yedek'] + 1;
                    if ($gln['gorev'] == mkConfig::GOREV_SORUMLU[1]){
                        $snc['yedek_s'] = $snc['yedek_s'] + 1;
                    }
                    if ($gln['saat'] == ''){
                        $snc['saat']++; 
                    }
                }
            }
        }
        return $snc;
    }
    
    public static function prosedurGrupDriveId($tklf_id,$grup){
        $list = Crud::getSqlCok(new Prosedur(), Prosedur::GET_TEKLIF_BY_GRUP, array('tklf_id'=>$tklf_id,'grup'=>$grup));
        $arr = array();
        foreach ($list as $v){
            $arr[$v->kod->deger]=$v->drive_id->deger;
        }
        return $arr;
    }
    
    public static function prosedurGrupKodDriveId($tklf_id,$grup,$kod){
        $v = Crud::getSqlTek(new Prosedur(), Prosedur::GET_TEKLIF_BY_GRUP_KOD, array('tklf_id'=>$tklf_id,'grup'=>$grup,'kod'=>$kod));
        return $v->drive_id->deger;
    }
    
    public static function prosedurlerHepsi($tklf_id){
        //$list = Crud::getSqlCok(new Prosedur(), Prosedur::GET_BY_TEKLIF, array('tklf_id'=>$tklf_id));
        $list = Crud::getSqlCok(new Prosedur(), Prosedur::GET_BY_TEKLIF_TIP, array('tklf_id'=>$tklf_id,'tip'=>mkConfig::PROSEDUR_TIP_MK));
        $arr = array();
        foreach ($list as $v){
            if (!isset($arr[$v->grup->deger])){
                $arr[$v->grup->deger] = array();
            }
            $arr[$v->grup->deger][$v->kod->deger]=$v;
        }
        return $arr;
    }

    public static function notlarKontrolGrup($tklf_id,$grup){
        $list = Crud::selectSqlWithPrm(MKNot::GET_TEKLIF_ID_GRUP_CEVAPSIZ, array('tklf_id'=>$tklf_id,'grup'=>$grup));
        $arr = array();
        
        if ($list != null ){
            foreach ($list as $v){
                $arr[$v['kod']]=$v['snc'];
            }
        }
        return $arr;
    }
    
    public static function notlarKontrol($tklf_id){
        $list = Crud::selectSqlWithPrm(MKNot::GET_TEKLIF_ID_CEVAPSIZ, array('tklf_id'=>$tklf_id));
        $arr = array();
        if ($list != null ){
            foreach ($list as $v){
                $arr[$v['grup']][$v['kod']]=$v['snc'];
            }
        }
        return $arr;
    }
    
    public static function prosedurlerDuzensizHepsi($tklf_id){
        
        $list = Crud::getSqlCok(new Prosedur(), Prosedur::GET_BY_TEKLIF, array('tklf_id'=>$tklf_id));
        $arr = array();
        foreach ($list as $v){
            array_push($arr,
            array(
                'grup' => $v->grup->deger, 
                'kod'  => $v->kod->deger, 
                'ack'  => $v->aciklama->deger,
            ));
        }
        return $arr;
    }
    
    public static function mkListesiGetirHepsi(){
        $list = Crud::getSqlCok(new MKListesi(), MKListesi::GET_BY_AKTIF, array());
        $arr = array();
        foreach ($list as $v){
            if (!isset($arr[$v->grup->deger])){
                $arr[$v->grup->deger] = array();
            }
            $arr[$v->grup->deger][$v->kod->deger]=$v->aciklama->deger;
        }
        return $arr;
    }
    
    public static function mkListesiGetir($grup){
        $prsAck = Crud::getSqlCok(new MKListesi(), MKListesi::GET_BY_GRUP, array('grup'=>$grup));
        $arr = array();
        foreach ($prsAck as $v){
            $arr[$v->kod->deger]=$v->aciklama->deger;
        }
        return $arr;
    }
    
    public static function mkRiskListesiGetir($tklfId){
        $list = Crud::selectSqlWithPrm(MKRisk::OZEL_TEKLIF, array('tklf_id'=>$tklfId));
        $arr = array();
        if (isset($list)){
            foreach ($list as $v){
                if (!isset($arr[$v['pGrup']][$v['pKod']])){
                    $arr[$v['pGrup']][$v['pKod']] = array();
                }
                array_push($arr[$v['pGrup']][$v['pKod']], $v);
            }
        }
        return $arr;
    }
    
    public static function mkRiskGrupListesiGetir($tklfId,$grup){
        $list = Crud::selectSqlWithPrm(MKRisk::OZEL_TEKLIF_GRUP, array('tklf_id'=>$tklfId,'grup'=>$grup));
        $arr = array();
        if (isset($list)){
            foreach ($list as $v){
                if (!isset($arr[$v['pKod']])){
                    $arr[$v['pKod']] = array();
                }
                array_push($arr[$v['pKod']], $v);
            }
        }
        return $arr;
    }
    
    public static function mkRefsListesiGetir($tklfId){
        $list = Crud::selectSqlWithPrm(MKRefs::OZEL_TEKLIF, array('tklf_id'=>$tklfId));
        $arr = array();
        if (isset($list)){
            foreach ($list as $v){
                if (!isset($arr[$v['pGrup']][$v['pKod']])){
                    $arr[$v['pGrup']][$v['pKod']] = array();
                }
                array_push($arr[$v['pGrup']][$v['pKod']], $v);
            }
        }
        return $arr;
    }
    
    public static function mkRefsGrupListesiGetir($tklfId,$grup){
        $list = Crud::selectSqlWithPrm(MKRefs::OZEL_TEKLIF_GRUP, array('tklf_id'=>$tklfId,'grup'=>$grup));
        $arr = array();
        if (isset($list)){
            foreach ($list as $v){
                if (!isset($arr[$v['pKod']])){
                    $arr[$v['pKod']] = array();
                }
                array_push($arr[$v['pKod']], $v);
            }
        }
        return $arr;
    }
    
    public static function _mkRefsDosyaIsimListesiGetir($link){
        return mkSoa::mkRefsDosyaIsimListesiGetirWithPros(mkSoa::prosedurlerHepsi($tklf_id),$link);
    }
    
    public static function _mkRefsDosyaIsimListesiGetirWithPros($prosedurs,$link){
        $client = driveSoa::baglan($link);
        $arr = array();
        foreach ($prosedurs as $k => $v) {
            foreach ($v as $k2 => $v2) {
                if ($v2->drive_id->deger != null){
                    $list = driveSoa::dosyaListesi($client, $v2->drive_id->deger);
                    if ($list != null ){
                        if (!isset($arr[$k][$k2])){
                            $arr[$k][$k2] = array();
                        }
                        foreach ($list as $one){
                            array_push($arr[$k][$k2], $one->name);
                        }
                    }
                }
            }
        }
        return $arr;
    }
    
}


// $result = mkSoa::riskGetir(20211,false);

//  echo '<pre>';
//  print_r($result);
//  echo '</pre>';

// $result = mkSoa::deleteMusteriKabul(202076);
// if ($result['hata']){
//     echo 'HATA : '.$result['ht_ack'];
// }else{
//     echo 'tamamlandı.';
// }

// echo '<pre>';
// print_r($tklf);
// echo '</pre>';