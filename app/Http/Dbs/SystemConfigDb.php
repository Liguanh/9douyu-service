<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/10/27
 * Time: 17:23
 */

namespace App\Http\Dbs;


use App\Tools\ToolArray;

class SystemConfigDb extends JdyDb
{
    protected $table = 'system_config';

    const IS_OPEN =1;

    /**
     * @return mixed
     * @desc 获取列表
     */
    public function getList(){

        return self::orderBy('id','desc')->get()->toArray();
    }

    /**
     * @desc 通过key获取配置信息
     * @param $id
     * @return string
     */
    public function getInfoById($id){

        $res = self::where('id', $id)->get()->toArray();

        return ToolArray::arrayToSimple($res);
    }

    /**
     * @desc 通过key值获取配置信息
     * @param $key
     * @return string
     */
    public function getInfoByKey($key){

        $res = self::where('key', $key)->where('status', self::IS_OPEN)->get()->toArray();

        return ToolArray::arrayToSimple($res);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 编辑信息
     */
    public function updateInfo($id, $data)
    {

        return self::where('id', $id)
            ->update([
                'name'          => $data['name'],
                'key'           => $data['key'],
                'value'         => $data['value'],
                'user_id'       => $data['user_id'],
                'status'        => $data['status'],
                'second_des'    => $data['second_des']
            ]);

    }


    /**
     * @param array $data
     * @return mixed
     * @desc 创建配置信息
     */
    public function addInfo($data=[])
    {

        $this->name = $data['name'];

        $this->key = $data['key'];

        $this->value = $data['value'];

        $this->user_id = $data['user_id'];

        $this->status = $data['status'];

        $this->second_des = $data['second_des'];

        $this->save();

        return $this->id;

    }
}