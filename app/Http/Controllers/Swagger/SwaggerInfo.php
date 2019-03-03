<?php
/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host=SWAGGER_HOST,
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="9douyu-service",
 *         description="",
 *         )
 *     )
 *     )
 * )
 */


/**
 * @SWG\Tag(
 *   name="JdOnline",
 *   description="京东网银相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="LLAuth",
 *   description="连连认证支付相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="YeeAuth",
 *   description="易宝认证支付相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="BFAuth",
 *   description="宝付认证支付相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="UCFAuth",
 *   description="先锋认证支付相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 *
 * @SWG\Tag(
 *   name="QdbWithholding",
 *   description="钱袋宝代扣",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="UmpWithholding",
 *   description="联动优势代扣",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 *  @SWG\Tag(
 *   name="ReaWithholding",
 *   description="融宝优势代扣",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 *  @SWG\Tag(
 *   name="Card",
 *   description="支付卡相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="Sms",
 *   description="短信相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="Email",
 *   description="邮件相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="filter",
 *   description="滤镜",
 *   @SWG\ExternalDocumentation(
 *     description="滤镜",
 *     url=SWAGGER_URL
 *   )
 * )
 */


/**
 * @SWG\SecurityScheme(
 *   securityDefinition="io84-api",
 *   type="oauth2",
 *   authorizationUrl=SWAGGER_AUTHORIZE_URL,
 *   tokenUrl=SWAGGER_ACCESS_TOKEN_URL,
 *   flow="application",
 *   scopes={
 *     "public": "public"
 *   }
 * )
 */



//flow' => ['implicit', 'password', 'application', 'accessCode']
?>
