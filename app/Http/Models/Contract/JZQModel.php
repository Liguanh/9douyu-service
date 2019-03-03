<?php
/**
 * Created by PhpStorm.
 * User: scofie
 * Date: 6/5/17
 * Time: 5:29 PM
 */

namespace App\Http\Models\Contract;

use App\Services\Contract\JzqEbq\JzqEbq;

class JZQModel extends ContractModel
{
    private $service;

    public function __construct()
    {
        parent::__construct('JZQ_CONFIG');

        $this->service = new JzqEbq($this->config);
    }

    /**
     * @return string
     * @desc 测试ping服务
     */
    public function ping ()
    {
        $result =   $this->service->ping();

        return $result;
    }

    /**
     * @param array $data
     * @return mixed
     * @desc 上传文件到签约中心
     */
    public function doUpdateApplySignFile( $data = [] )
    {
        $result =   $this->service->doUpdateApplySignFile( $data ) ;

        return $result;
    }

    /**
     * @param array $data
     * @return mixed
     * @desc 根据保全编号查询保全用户的确认状态
     */
    public function getSignStatus( $data = [] )
    {
        return  $this->service->getSignStatus( $data ) ;
    }

    /**
     * @param array $data
     * @return mixed
     * @desc 获取签章处理结果，并进行数据回调
     */
    public function getSignNotify( $data =[] )
    {
        return $this->service->getSignNotify($data) ;
    }

    /**
     * @param array $data
     * @return mixed
     * @desc 获取签约的地址
     */
    public function getSignLink( $data = [] )
    {
        return $this->service->getSignLink( $data ) ;
    }

    /**
     * @param array $data
     * @return mixed
     * @desc 合同文件下载地址
     */
    public function cfDownloadUrl( $data = [] )
    {
        return  $this->service->getPresFileLine( $data ) ;
        //return  $this->service->getFileLink( $data ) ;
    }

    /**
     * @param array $data
     * @return mixed
     * @desc 获取合同地址
     */
    public function certificateLinkGet( $data =[] )
    {
        return $this->service->getPresFileLine( $data ) ;
    }

    /**
     * @param array $data
     * @return mixed
     * @deprecated 在易保全电子数据保全中心获取签约详情查看链接
     */
    public function cfViewUrl( $data =[] )
    {
        return $this->service->getDetailAnonymityLink( $data ) ;
    }

    /**
     * @param array $data
     * @return string
     * @desc 回调信息验证
     */
    public function returnInfo( $data = [] )
    {
        return $this->service->returnInfo( $data ) ;
    }
}
