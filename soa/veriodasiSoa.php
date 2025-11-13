<?php 
include_once 'baseSoa.php';
include_once PREPATH.'config/sozlesmeConfig.php';
include_once 'driveSoa.php';

Class veriodasiSoa extends BaseSoa{
    
    public static function veriodasiGetir($tklf_id, $grup, $kod){
        $veriler = Crud::getSqlCok(new VeriodasiProsedur(), VeriodasiProsedur::GET_BY_TKLF_GRUP_KOD, array('tklf'=>$tklf_id, 'grup'=>$grup, 'kod'=>$kod));
        if ($veriler != null){
            $veriler = Base::basitList($veriler);
        }
        return $veriler;
    }
    
    public static function veriodasiBelgeGetir($tklf_id, $grup, $kod, $link){
        $veriler = veriodasiSoa::veriodasiGetir($tklf_id, $grup, $kod);
        $results = array();
        if ($veriler != null){
            foreach ($veriler as $veri){
                $drives = driveSoa::dosyaListesiWithLink($veri['klasor_drive_id'],$link);
                $result = null;
                $result['id']       = $veri['id'];
                $result['grup']     = $veri['grup'];
                $result['kod']      = $veri['kod'];
                $result['belge_id'] = $veri['risk_belge_id'];
                $belges = array();
                for ($i = 0; $i < count($drives); $i++) {
                    $belge = null;
                    $belge['name'] = $drives[$i]->name;   
                    $belge['url']  = $drives[$i]->url;    
                    $belge['web']  = $drives[$i]->webUrl; 
                    $belge['key']   = $drives[$i]->id;
                    array_push($belges, $belge);
                }
                $result['belge'] = $belges;
                array_push($results, $result);
            }
        }
        return $results;
    }
    
    public static function veriOdasiBelgeSil($data){
        driveSoa::belgeDrivedenSil($data['driveId'],$data['link']);
        $prsdr  = Crud::getById(new VeriodasiProsedur(),$data['belgeId']);
        $result = 1;
        if ($prsdr->belge_sayi->deger > 0){
            $prsdr->belge_sayi->deger = $prsdr->belge_sayi->deger - 1;
            $result = Crud::update($prsdr);
            if ($result != 1){
                throw new Exception($result);
            }
        }
        return $result;
    }
    
    public static function veriodasiProsedurBelgeYukle($data,$files){
        if ($data['id'] == 0){
            $data['denetci_onay'] = 'H';
            $data['denetci_tarihi'] = null;
            $data['aciklama'] = null ;
            $snc = veriodasiSoa::veriOdasiKaydet($data);
            $data['id'] = $snc['id'];
        }
        $prsdr  = Crud::getById(new VeriodasiProsedur(),$data['id']);
        for ($i = 0; $i < count($files['dosya']['name']) ; $i++) {
            $drive  = driveSoa::getir($prsdr->klasor_drive_id->deger,$data['link']);
            $stream = fopen($files['dosya']['tmp_name'][$i], "rb");
            $drive->upload($files['dosya']['name'][$i], $stream);
        }
        $prsdr->belge_sayi->deger = $prsdr->belge_sayi->deger + count($files['dosya']['name']);
        $result = Crud::update($prsdr);
        if ($result != 1){
            throw new Exception($result);
        }
        return $result;
    }
    
    public static function veriOdasiKaydet($post){
        if ($post['id'] == 0){
            $prsdr = new VeriodasiProsedur();
            $prsdr->grup->deger         = $post['grup'];
            $prsdr->kod->deger          = $post['kod'];
            $prsdr->tklf_id->deger      = $post['tklf_id'];
            $prsdr->risk_belge_id->deger= $post['risk_belge_id'];
            $prsdr->musteri_bitir->deger= 'H';
            
            $client  = driveSoa::baglan($post['link']);
            $driveId = driveSoa::veriOdasiOlustur($client,$post['tklf_id']);
            
            $veriodasiDrive =  driveSoa::klasorOlustur($client, $driveId, $post['adi']);
            $prsdr->klasor_drive_id->deger = $veriodasiDrive;
        }else{
            $prsdr = Crud::getById(new VeriodasiProsedur(),$post['id']);
            if ($prsdr->musteri_bitir->deger == 'H' && $post['musteri_bitir'] == 'E'){
                $prsdr->musteri_tarihi->deger   = date("Y:m:d H:i:s");
                $prsdr->musteri_bitir->deger = $post['musteri_bitir'];
            }else if($prsdr->musteri_bitir->deger == 'E' && $post['musteri_bitir'] == 'H'){
                $prsdr->musteri_tarihi->deger   = null;
                $prsdr->musteri_bitir->deger = $post['musteri_bitir'];
            }
        }
        
        $prsdr->denetci_onay->deger     = $post['denetci_onay'];
        $prsdr->denetci_tarihi->deger   = $post['denetci_tarihi'];
        $prsdr->aciklama->deger         = $post['aciklama'];
        $snc = array();
        if ($post['id'] == 0){
            $db = new Db();
            $ses = $db->getCon();
            $result = Crud::save($prsdr,$ses);
            $snc['id'] = $ses->insert_id;
        }else{
            $result = Crud::update($prsdr);
        }
        $snc['result'] = $result;
        return $snc;
    }
}

