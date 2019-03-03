<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/13
 * Time: 下午6:33
 */
namespace App\Http\Dbs;

use Illuminate\Database\Eloquent\Model as BaseDb;
use Illuminate\Support\Str;

/**
 * Class JdyDb
 * @package App\Http\Dbs
 */
class JdyDb extends BaseDb{

    /**
     * 白名单控制
     * @param array $attributes
     * @param array $fillable
     */
    public function __construct(array $attributes = [], array $fillable = []){

        // 子模型白名单
        if($fillable !== null)
            $this->fillable($fillable);
        // sql日志打印
        app('db')->connection()->enableQueryLog();

        parent::__construct($attributes);
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        if (isset($this->table)) {
            return $this->table;
        }

        return str_replace('\\', '', Str::snake(preg_replace('#DB$#i', '', class_basename($this))));
    }

    /**
     * 获取sql
     */
    public static function getSql(){
        echo '-------SQL-LOG# <br/>';
        echo print_r(app('db')->getQueryLog(), true);
        echo '<br/> -------END# <br/>';
    }


    public function __destruct(){
        //self::getSql();
    }
}
