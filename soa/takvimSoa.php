<?php 
include_once 'baseSoa.php';
include_once PREPATH.'config/takvimConfig.php';

Class takvimSoa extends BaseSoa{
    
    public static function takvimGetir($denetci_id){
        $prms = takvimSoa::takvimPrmKey();
        $rList = array();
        $list = Base::basitList(Crud::getSqlCok(new Takvim(), Takvim::LIST_TEKLIF_ID, array('did' => $denetci_id)));
        if ($list != null){
            foreach ($list as $v){
                $data = array(
                    'id' 	=> $v['id'],
                    'title'	=> $v['aciklama'],
                    'start'	=> $v['allDay'] == 'E' ? str_replace(" 00:00:00", "", $v["ilk"]) : $v["ilk"],
                    'end'   => $v['allDay'] == 'E' ? str_replace(" 00:00:00", "", $v["son"]) : $v["son"],
                    'allDay'=> $v['allDay'] == 'E' ? true : false,
                    'color'	=> $prms[$v['konu']]['color']
                );
                array_push($rList, $data);
            }
        }
        return json_encode($rList,JSON_UNESCAPED_UNICODE);
    }
    
    public static function kayitDuzenle($id,$denetci_id,$aciklama,$konu,$ilk,$son,$allDay){
        if ( $id == null){
            $tkvm = new Takvim();
        }else{
            $tkvm = Crud::getById(new Takvim(),$id);
        }
        $tkvm->aciklama->deger  = $aciklama;
        $tkvm->denetci_id->deger= $denetci_id;
        $tkvm->ilk->deger       = $ilk;
        $tkvm->son->deger       = $son;
        $tkvm->allDay->deger    = $allDay;
        $tkvm->konu->deger      = $konu;
        if ( $id == null ){
            $result = Crud::save($tkvm);
        }else{
            $result = Crud::update($tkvm);
        }
        if ($result!=1){
            return $result;
        }
        return true;
    }
    
    public static function sil($id){
        $result = Crud::deleteSqlTek(new Takvim() , Takvim::DELETE_ID , array('id' =>$id));
        if ($result==1){
            return true;
        }else{
            return false;
        }
    }

    public static function takvimPrmKey(){
        $rList = array();
        $list = Base::basitList(Crud::all(new TakvimPrm()));
        if ($list != null){
            foreach ($list as $v){
                $rList[$v['id']] = array(
                    'adi' 	=> $v['adi'],
                    'color'	=> $v['color']
                );
            }
        }
        return $rList;
    }
    
    public static function takvimPrm(){
        $rList = array();
        $list = Base::basitList(Crud::all(new TakvimPrm()));
        if ($list != null){
            foreach ($list as $v){
                $data = array(
                    'id' 	=> $v['id'],
                    'adi' 	=> $v['adi'],
                    'color'	=> $v['color']
                    );
                array_push($rList, $data);
            }
        }
        return $rList;
    }
}

