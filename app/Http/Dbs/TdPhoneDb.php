<?php
/**
 * 退订短信
 * User: bihua
 * Date: 16/4/25
 * Time: 16:40
 */
namespace App\Http\Dbs;

class TdPhoneDb extends JdyDb
{
    protected $table = "td_phone";

    public $timestamps = false;

    public function addPhone($phone = ''){
        if(empty($phone)){return false;}
        $id = self::insertGetId(['phone'=>$phone, 'created_at'=>date("Y-m-d H:i:s",time())]);
        if($id>0){
            return $id;
        }else{
            return false;
        }
    }
}