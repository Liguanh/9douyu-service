<?php
/**
 * 回复短信
 * User: bihua
 * Date: 16/4/25
 * Time: 17:25
 */
namespace App\Http\Models\Sms;

use App\Http\Models\Model;
use App\Http\Dbs\PhoneReplyDb;
use Log;

class PhoneReplyModel extends Model
{
    /**
     * 添加数据
     * @param $data
     * @return mixed
     */
    public function addReply($data){
        if(empty($data)){return false;}
        $db = new PhoneReplyDb();
        $id = $db->addReply($data);
        return $id;
    }
}