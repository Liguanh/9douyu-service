<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/12/1
 * Time: 上午10:51
 */

namespace App\Http\Models\Contract;

use App\Services\Contract\EBaoQuan\EBaoQuan;

class EBQModel extends ContractModel
{

    private $service;

    public function __construct()
    {
        parent::__construct('E_BAO_QUAN');

        $this->service = new EBaoQuan($this->config);

    }

    /**
     * @return mixed
     * 测试服务可用性
     */
    public function ping()
    {

        $result = $this->service->ping();

        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * 创建保全
     */
    public function cfPreservationCreate( $data )
    {

        $result = $this->service->contractFilePreservationCreate($data);

        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * 获取合同保全文件下载地址
     */
    public function cfDownloadUrl( $data ){

        $result = $this->service->contractFileDownloadUrl($data);

        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * 根据保全编号查询保全
     */
    public function preservationGet( $data ){

        $result = $this->service->preservationGet( $data );

        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * 保全证书的证书链接
     */
    public function certificateLinkGet( $data )
    {

        $result = $this->service->certificateLinkGet( $data );

        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * 获取合同查看页URL
     */
    public function cfViewUrl( $data )
    {

        $result = $this->service->contractFileViewUrl( $data );

        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * 根据保全编号查询保全用户的确认状态
     */
    public function contractStatusGet( $data )
    {

        $result = $this->service->contractStatusGet( $data );

        return $result;

    }

    /**
     * @param $data
     * @return string
     * @throws \Exception
     * 回调验证
     */
    public function returnInfo( $data )
    {

        $result = $this->service->returnInfo( $data );

        return $result;

    }



}