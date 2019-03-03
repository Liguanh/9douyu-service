<?php
/**
 * 邮件
 * User: bihua
 * Date: 16/4/29
 * Time: 15:08
 */
namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Http\Logics\Email\EmailLogic;
use App\Http\Logics\Logic;
use Illuminate\Http\Request;


class EmailController extends Controller
{
    private  $logic = null;
    public function __construct(Request $request){

        //如果是非正式环境,不发邮件
        if( env('APP_ENV') != 'production' ){
            return self::returnJson(
           		Logic::callSuccess()
            );
        }

        parent::__construct($request);
        $email          = $request->input("email","");  //收件人邮箱
        $title          = $request->input("subject","");  //邮件标题
        $subject        = $request->input("body","");  //邮件内容
        $cc             = $request->input("cc","");      //抄送人邮箱
        $attachment     = $request->allFiles();  //附件，完整路径
        $this->logic =  new EmailLogic($email,$title,$subject,$cc,$attachment);
    }

    /**
     * 发送字符内容邮件
     * @param Request $request
     */
    public function sendMail(){

        $result = $this->logic->sendContent();

        return self::returnJson($result);

    }

    /**
     * 发送HTML格式邮件
     * @param Request $request
     */
    public function sendMailHtml(){

        $result = $this->logic->sendContent('sendHtml');

        return self::returnJson($result);

    }

}
