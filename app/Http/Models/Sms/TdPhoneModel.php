<?php
/**
 * 退订短信
 * User: bihua
 * Date: 16/4/25
 * Time: 16:40
 */
namespace App\Http\Models\Sms;

use App\Http\Models\Model;
use App\Http\Dbs\TdPhoneDb;

class TdPhoneModel extends Model
{
    /**
     * 添加数据
     * @param string $phone
     * @return mixed
     */
    public function addPhone($phone = ''){
        if(empty($phone)){return false;}
        $db = new TdPhoneDb();
        $id = $db->addPhone($phone);
        return $id;
    }
}