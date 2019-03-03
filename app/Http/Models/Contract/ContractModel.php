<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/12/1
 * Time: 上午10:48
 */

namespace App\Http\Models\Contract;

use Laravel\Lumen\Application;

abstract class ContractModel
{
    protected $config = [];

    public function __construct($key)
    {
        $app        = new Application();
        $app->configure('contract');
        $config     = $app['config']['contract'][$key];
        $this->config =  (array)$config;
    }

}