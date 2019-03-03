<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/12/1
 * Time: 上午11:32
 */

namespace App\Http\Controllers\Swagger\Contract;


class EBQ
{

    /**
     * @SWG\Post(
     *   path="/contract/index?random=EBQ_ping",
     *   tags={"EBQ:易宝全合同接口"},
     *   summary="ping测试服务可用性",
     *   @SWG\Parameter(
     *      name="partner_id",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *      default="100000178",
     *   ),
     *    @SWG\Parameter(
     *      name="secret_sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="合同渠道",
     *      required=true,
     *      type="string",
     *      default="EBQ",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称",
     *      required=true,
     *      type="string",
     *     default="ping",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="测试服务可用性",
     *   )
     * )
     */
    public function ping(){}

    /**
     * @SWG\Post(
     *   path="/contract/index?random=EBQ_cfPreservationCreate",
     *   tags={"EBQ:易宝全合同接口"},
     *   summary="创建保全",
     *   @SWG\Parameter(
     *      name="partner_id",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *      default="100000178",
     *   ),
     *    @SWG\Parameter(
     *      name="secret_sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="合同渠道",
     *      required=true,
     *      type="string",
     *      default="EBQ",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称",
     *      required=true,
     *      type="string",
     *     default="cfPreservationCreate",
     *   ),
     *   @SWG\Parameter(
     *      name="contract_num",
     *      in="formData",
     *      description="合同编号",
     *      required=true,
     *      type="string",
     *      default="JDY_132519_1894_196645",
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="合同金额",
     *      required=true,
     *      type="string",
     *      default="10000",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="string",
     *      default="132519",
     *   ),
     *   @SWG\Parameter(
     *      name="identity",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *      default="123123123412341234",
     *   ),
     *   @SWG\Parameter(
     *      name="real_name",
     *      in="formData",
     *      description="真实姓名",
     *      required=true,
     *      type="string",
     *      default="刘刘刘",
     *   ),
     *   @SWG\Parameter(
     *      name="email",
     *      in="formData",
     *      description="邮箱",
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="13161781368",
     *   ),
     *   @SWG\Parameter(
     *      name="desc",
     *      in="formData",
     *      description="保全描述",
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="file_path",
     *      in="formData",
     *      description="保全文件路径",
     *      required=true,
     *      type="string",
     *      default="test/九斗鱼享乐计划投资协议132519-1894-196645.pdf",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="创建保全",
     *   )
     * )
     */
    /**
     * *   @SWG\Parameter(
     *      name="preservation_title",
     *      in="formData",
     *      description="保全标题",
     *      required=true,
     *      type="string",
     *      default="九斗鱼用户投资合同保全",
     *   ),
     */
    public function cfPreservationCreate(){}

    /**
     * @SWG\Post(
     *   path="/contract/index?random=EBQ_cfDownloadUrl",
     *   tags={"EBQ:易宝全合同接口"},
     *   summary="获取合同保全文件下载地址",
     *   @SWG\Parameter(
     *      name="partner_id",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *      default="100000178",
     *   ),
     *    @SWG\Parameter(
     *      name="secret_sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="合同渠道",
     *      required=true,
     *      type="string",
     *      default="EBQ",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称",
     *      required=true,
     *      type="string",
     *     default="cfDownloadUrl",
     *   ),
     *   @SWG\Parameter(
     *      name="preservation_id",
     *      in="formData",
     *      description="保全编号",
     *      required=true,
     *      type="string",
     *      default="427402",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取合同保全文件下载地址",
     *   )
     * )
     */
    public function cfDownloadUrl(){}

    /**
     * @SWG\Post(
     *   path="/contract/index?random=EBQ_preservationGet",
     *   tags={"EBQ:易宝全合同接口"},
     *   summary="根据保全编号查询保全",
     *   @SWG\Parameter(
     *      name="partner_id",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *      default="100000178",
     *   ),
     *    @SWG\Parameter(
     *      name="secret_sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="合同渠道",
     *      required=true,
     *      type="string",
     *      default="EBQ",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称",
     *      required=true,
     *      type="string",
     *     default="preservationGet",
     *   ),
     *   @SWG\Parameter(
     *      name="preservation_id",
     *      in="formData",
     *      description="保全编号",
     *      required=true,
     *      type="string",
     *      default="427402",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="根据保全编号查询保全",
     *   )
     * )
     */
    public function preservationGet(){}

    /**
     * @SWG\Post(
     *   path="/contract/index?random=EBQ_certificateLinkGet",
     *   tags={"EBQ:易宝全合同接口"},
     *   summary="保全证书的证书链接",
     *   @SWG\Parameter(
     *      name="partner_id",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *      default="100000178",
     *   ),
     *    @SWG\Parameter(
     *      name="secret_sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="合同渠道",
     *      required=true,
     *      type="string",
     *      default="EBQ",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称",
     *      required=true,
     *      type="string",
     *     default="certificateLinkGet",
     *   ),
     *   @SWG\Parameter(
     *      name="preservation_id",
     *      in="formData",
     *      description="保全编号",
     *      required=true,
     *      type="string",
     *      default="427402",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="保全证书的证书链接",
     *   )
     * )
     */
    public function certificateLinkGet(){}

    /**
     * @SWG\Post(
     *   path="/contract/index?random=EBQ_cfViewUrl",
     *   tags={"EBQ:易宝全合同接口"},
     *   summary="获取合同查看页URL",
     *   @SWG\Parameter(
     *      name="partner_id",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *      default="100000178",
     *   ),
     *    @SWG\Parameter(
     *      name="secret_sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="合同渠道",
     *      required=true,
     *      type="string",
     *      default="EBQ",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称",
     *      required=true,
     *      type="string",
     *     default="cfViewUrl",
     *   ),
     *   @SWG\Parameter(
     *      name="preservation_id",
     *      in="formData",
     *      description="保全编号",
     *      required=true,
     *      type="string",
     *      default="427402",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取合同查看页URL",
     *   )
     * )
     */
    public function cfViewUrl(){}

    /**
     * @SWG\Post(
     *   path="/contract/index?random=EBQ_contractStatusGet",
     *   tags={"EBQ:易宝全合同接口"},
     *   summary="根据保全编号查询保全用户的确认状态",
     *   @SWG\Parameter(
     *      name="partner_id",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *      default="100000178",
     *   ),
     *    @SWG\Parameter(
     *      name="secret_sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="合同渠道",
     *      required=true,
     *      type="string",
     *      default="EBQ",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称",
     *      required=true,
     *      type="string",
     *     default="contractStatusGet",
     *   ),
     *   @SWG\Parameter(
     *      name="preservation_id",
     *      in="formData",
     *      description="保全编号",
     *      required=true,
     *      type="string",
     *      default="427402",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="根据保全编号查询保全用户的确认状态",
     *   )
     * )
     */
    public function contractStatusGet(){}
}