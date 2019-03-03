<?php
/**
 * 回复短信
 * User: bihua
 * Date: 16/4/25
 * Time: 17:25
 */
namespace App\Http\Dbs;

class PhoneReplyDb extends JdyDb
{
    protected $table = "phone_reply";

    //取消同时更新pdated_at字段，为true则加入 created_at 和 pdated_at 字段
    public $timestamps = false;

    public function addReply($data){
        if(empty($data)){return false;}
        $id = self::insertGetId($data);
        if($id > 0){
            return $id;
        }else{
            return false;
        }
    }
}