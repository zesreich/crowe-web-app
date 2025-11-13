<?php
// PREPATH='';
// for($pathSayi = 2; $pathSayi < count(array_values(array_filter(explode('/', $_SERVER['PHP_SELF'])))); $pathSayi++) {
//     //if ($listSay>0){
//     PREPATH=PREPATH.'../';
//     //}else {
//     //    $listSay++;
//     //}
// }

include_once  __DIR__.'/../path.php';
include_once  PREPATH.'config/config.php';
include_once 'Db.php';

entImp(PREPATH."entity/",PREPATH);
function entImp($klsr,$lnk){
    foreach (scandir($klsr) as $ent){
        if ($ent!='.' && $ent!='..' && $ent!='Base.class.php'){
            if (strstr($ent,".php")){
                include_once $klsr.$ent;
            }else{
                entImp($klsr.$ent.'/',$lnk);
            }
        }
    }
}

class Crud
{
    private static function saveGmtUsr($tablo,$usrId){
        $tablo->create_gmt->deger       = date("Y:m:d H:i:s");
        $tablo->create_user_id->deger   = $usrId;
        return Crud::updateGmtUsr($tablo, $usrId);
    }
    
    private static function updateGmtUsr($tablo,$usrId){
        $tablo->gmt->deger      = date("Y:m:d H:i:s");
        $tablo->user_id->deger  = $usrId;
        return $tablo;
    }
    
    public static function deleteSqlTek($table, $sorgu, $prms, $ref = true, $ses = null)
    {
        foreach ($prms as $key => $value){
            if ($sorgu[1][$key] == base::SAYI){
                $sorgu = str_replace(':_'.$key, $value, $sorgu);
            }else if ($sorgu[1][$key] == base::KELIME){
                $sorgu = str_replace(':_'.$key, "'".$value."'", $sorgu);
            }
        }
        $son = Crud::selectSql($sorgu[0],$ses);
        return $son;
    }
    
    public static function getSqlTek($table, $sorgu, $prms, $ref = true, $ses = null)
    {
        // SQL Injection koruması - mysqli_real_escape_string kullan
        $db = ($ses != null) ? $ses : (new Db())->getCon();
        $query = $sorgu[0];
        
        foreach ($prms as $key => $value){
            if ($sorgu[1][$key] == base::SAYI){
                $value = (int)$value; // Integer casting
                $query = str_replace(':_'.$key, $value, $query);
            }else if ($sorgu[1][$key] == base::KELIME){
                $value = $db->real_escape_string($value); // SQL Injection koruması
                $query = str_replace(':_'.$key, "'".$value."'", $query);
            }
        }
        
        $son = Crud::selectSql($query,$ses);
        if ($son == null || $son[0]== null){
            return null;
        }else{
            return Crud::tablola($table,$son[0],$ref,$ses);
        }
    }
    
    public static function getSqlCokTblsiz($table, $sorgu, $prms, $ref = true, $ses = null)
    {
        //$s = new $table();
        foreach ($prms as $key => $value){
            if ($sorgu[1][$key] == base::SAYI){
                $sorgu[0] = str_replace(':_'.$key, $value, $sorgu[0]);
            }else if ($sorgu[1][$key] == base::KELIME){
                $sorgu[0] = str_replace(':_'.$key, "'".$value."'", $sorgu[0]);
            }
        }
        $son = Crud::selectSql($sorgu[0],$ses);
        if ($son == null || $son[0]== null){
            return null;
        }else{
            return $son;
        }
    }
    
    public static function getSqlCok($table, $sorgu, $prms, $ref = true, $ses = null)
    {
        //$s = new $table();
        foreach ($prms as $key => $value){
            if ($sorgu[1][$key] == base::SAYI){
                $sorgu[0] = str_replace(':_'.$key, $value, $sorgu[0]);
            }else if ($sorgu[1][$key] == base::KELIME){
                $sorgu[0] = str_replace(':_'.$key, "'".$value."'", $sorgu[0]);
            }
        }
        $son = Crud::selectSql($sorgu[0],$ses);
        if ($son == null || $son[0]== null){
            return null;
        }else{
            //print_r($sorgu);
            return Crud::listTablola($table,$son,$ref);
        }
    }
    
    public static function getById($table, $id, $ref = true, $ses = null)
    {
        // SQL Injection koruması - ID integer olmalı
        $id = (int)$id;
        if ($id <= 0) {
            return null;
        }
        
        // Prepared statement kullan
        $db = ($ses != null) ? $ses : (new Db())->getCon();
        $tableName = $db->real_escape_string($table->vt_dbAdi());
        $order = $db->real_escape_string($table->vt_Order());
        
        $stmt = $db->prepare("SELECT * FROM `{$tableName}` WHERE id = ? {$order}");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stmt->close();
                return Crud::tablola($table, $row, $ref, $ses);
            }
            $stmt->close();
        }
        return null;
    }
    
    public static function all($table, $ref = true, $ses = null)
    {
        //$s = new $table();
        $sorgu  = "SELECT * FROM ".$table->vt_dbAdi().' '.$table->vt_Order();
        $son = Crud::selectSql($sorgu,$ses);
//         print_r($son);
        if ($son == null || $son[0]== null){
            return null;
        }else{
            return Crud::listTablola($table,$son,$ref);
        }
    }
    
    public static function delete($table,$id,$ses = null){
        // SQL Injection koruması - ID integer olmalı
        $id = (int)$id;
        if ($id <= 0) {
            return false;
        }
        
        // Prepared statement kullan
        $db = ($ses != null) ? $ses : (new Db())->getCon();
        $tableName = $db->real_escape_string($table->vt_dbAdi());
        
        $stmt = $db->prepare("DELETE FROM `{$tableName}` WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $success = $stmt->affected_rows > 0;
            $stmt->close();
            return $success;
        }
        return false;
    }
    
    public static function save($tablo,$ses = null){
        try{
            if (isset($_SESSION['login']) && isset($_SESSION['login']['id'])){
                $usrId = $_SESSION['login']['id'];
            }else{
                $usrId = 1;
            }
            
            $tablo = Crud::saveGmtUsr($tablo, $usrId);
            $kk = '';
            $vv = '';
            $ht = '';
            $tbl = get_object_vars($tablo);
            foreach($tbl as $key => $value){
                //if ($key != 'id' ){
                if ($key != 'id' || !($value->deger == null  || $value->deger == '' )){
//                     print_r($value);
//                     echo '</br>';
                    if ($value->null == 'H' && ( $value->deger == null || $value->deger == '' )){
                        if ($ht == ''){
                            $ht=$ht.'"'.$value->adi.'"';// alanı boş olamaz.\n';
                        }else{
                            $ht=$ht.', "'.$value->adi.'"';// alanı boş olamaz.\n';
                        }
                    }
                    if ($ht == ''){
                        if ( $value->deger !=null  && $value->deger != '' ){
                            $val = '';
                            if ($value->tur == Base::KELIME){
                                if ( $value->deger !=null  && $value->deger != '' ){
                                    // SQL Injection koruması
                                    $db = ($ses != null) ? $ses : (new Db())->getCon();
                                    $val = "'".$db->real_escape_string($value->deger)."'";
                                }
                            }else if ($value->tur == Base::SAYI){
                                $val = (int)$value->deger; // Integer casting
                            }
                            if (!($value->tur == Base::SAYI && $val==null)){
                                if ($kk == ''){
                                    $kk = $key;
                                    $vv = $val;
                                }else{
                                    $kk = $kk.','.$key;
                                    $vv = $vv.','.$val;
                                }
                            }
                        }
                    }
                }
            }
            if ($ht == ''){
                $sorgu = "INSERT INTO ".$tablo->vt_dbAdi().' ('.$kk.') VALUES ('.$vv.')';
                return Crud::selectSql($sorgu,$ses);
            }else{
                return $ht.' alanı boş olamaz.';
            }
        } catch (Exception $e) {
            error_log("Crud error: " . $e->getMessage());
            if (defined('Config') && Config::DEBUG_MODE) {
                return $e->getMessage();
            }
            return "Bir hata oluştu.";
        }
    }
    
    public static function update($tablo,$ses = null){
        try{
            if (isset($_SESSION['login']) && isset($_SESSION['login']['id'])){
                $usrId = $_SESSION['login']['id'];
            }else{
                $usrId = 1;
            }
            
            $tablo = Crud::updateGmtUsr($tablo, $usrId);
            $wh = '';
            $vv = '';
            $ht = '';
            $tbl = get_object_vars($tablo);
            foreach($tbl as $key => $value){
                if ($value->null == 'H' && $value->deger !==0 && ( $value->deger ==null  || $value->deger == '' )){
                    if ($ht == ''){
                        $ht=$ht.'"'.$value->adi.'"';// alanı boş olamaz.\n';
                    }else{
                        $ht=$ht.', "'.$value->adi.'"';// alanı boş olamaz.\n';
                    }
                }
                if ($ht == ''){
                    if ($key != 'id' ){
                        if ( $value->deger !=null  && $value->deger != '' ){
                            $val = '';
                            if ($value->tur == Base::KELIME){
                                // SQL Injection koruması
                                $db = ($ses != null) ? $ses : (new Db())->getCon();
                                $val = $key."='".$db->real_escape_string($value->deger)."'";
                            }else if ($value->tur == Base::SAYI){
                                $val = $key."=".(($value->deger !== '' && $value->deger !== null) ? (int)$value->deger : 'NULL' );
                            }
                            if ($vv == ''){
                                $vv = $val;
                            }else{
                                $vv = $vv.', '.$val;
                            }
                        } else{
                            $val = '';
                            if ($value->tur == Base::KELIME){
                                $val = $key."= NULL";
                            }else if ($value->tur == Base::SAYI){
                                $val = $key."=".'NULL';
                            }
                            if ($vv == ''){
                                $vv = $val;
                            }else{
                                $vv = $vv.', '.$val;
                            }
                        }
                    }else{
                        $wh = 'id ='.$value->deger;
                    }
                }
            }
            if ($ht == ''){
                $sorgu = "UPDATE ".$tablo->vt_dbAdi().' SET '.$vv.' WHERE '.$wh.'';
                return Crud::selectSql($sorgu,$ses);
            }else{
                return $ht.' alanı boş olamaz.';
            }
        } catch (Exception $e) {
            error_log("Crud error: " . $e->getMessage());
            if (defined('Config') && Config::DEBUG_MODE) {
                return $e->getMessage();
            }
            return "Bir hata oluştu.";
        }
    }
    
    public static function selectSqlTek($sorgu,$ses = null){
        $snc = Crud::selectSql($sorgu,$ses);
        if ($snc != null){
            return $snc[0];
        }
        return $snc;
    }
    
    public static function selectSql($sorgu,$ses = null)
    {
        try{
//             echo "\n<br/> sorgu : \n<br/>";
//             print_r($sorgu);
//             echo "\n<br/>";
            if ($ses != null){
                $result = $ses->query($sorgu);
            }else{
                $db = new Db();
                $result = $db->getCon()->query($sorgu);
            }
            if ($result) {
                if (gettype($result) != 'object'){
                    return true;
                }else{
                    $rows = array();
                    if ($result->num_rows>1) {
                        while ($row = $result->fetch_assoc()) {
                            $rows[] = $row;
                        }
                    }else{
                        $rows[] = $result->fetch_assoc();
                    }
                    return $rows;
                }
            }else{
                if ($ses != null){
                    return $ses->error;
                }else{
                    return $db->getCon()->error;
                }
            }
        } catch (Exception $e) {
            error_log("Crud error: " . $e->getMessage());
            if (defined('Config') && Config::DEBUG_MODE) {
                return $e->getMessage();
            }
            return "Bir hata oluştu.";
        }
    }
    
    public static function selectSqlWithPrm($sorgu,$prms,$ses = null)
    {
        try{
            foreach ($prms as $key => $value){
                if ($sorgu[1][$key] == base::SAYI){
                    $sorgu[0] = str_replace(':_'.$key, $value, $sorgu[0]);
                }else if ($sorgu[1][$key] == base::KELIME){
                    $sorgu[0] = str_replace(':_'.$key, "'".$value."'", $sorgu[0]);
                }
            }
            if ($ses != null){
                $result = $ses->query($sorgu[0]);
            }else{
                $db = new Db();
                $result = $db->getCon()->query($sorgu[0]);
            }
            if ($result) {
                if (gettype($result) != 'object'){
                    return true;
                }else{
                    $rows = array();
                    if ($result->num_rows>1) {
                        while ($row = $result->fetch_assoc()) {
                            $rows[] = $row;
                        }
                    }else{
                        $dgr = $result->fetch_assoc();
                        if ($dgr != null ){
                            $rows[] = $dgr;
                        }
                    }
                    return $rows;
                }
            }else{
                if ($ses != null){
                    return $ses->error;
                }else{
                    return $db->getCon()->error;
                }
            }
        } catch (Exception $e) {
            error_log("Crud error: " . $e->getMessage());
            if (defined('Config') && Config::DEBUG_MODE) {
                return $e->getMessage();
            }
            return "Bir hata oluştu.";
        }
    }
    

    
    
    private static function listTablola($tablo,$data,$ref = true, $ses = null){
        $list =array();
        foreach($data as $value){
            array_push($list, Crud::tablola($tablo,$value,$ref,$ses));
        }
        return $list;
    }
    
    private static function tablola($tablo,$data,$ref = true, $ses = null){
        $rtrn = null;
        try {
            $rtrn = new $tablo();
            foreach($data as $key => $value){
                $rtrn->$key->deger = $value;
                if ($rtrn->$key->ref != null && $value != null){
                    if ($rtrn->$key->ref->baglanti == Base::TEK){
                        $rtrn->$key->ref->deger = Crud::getById( new $rtrn->$key->ref->tablo(), $value, $ref, $ses);
                    }
                }
            }
        } catch (Exception $e) {
            echo 'hata'.$e->getMessage();
        }
        return $rtrn;
    }
    
}