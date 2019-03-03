<?php
/**
 * 邮件模块
 * User: bihua
 * Date: 16/5/10
 * Time: 11:12
 */
namespace App\Http\Logics\Email;

use Log;
use App\Http\Logics\Logic;
use App\Http\Models\Mail\EmailModel;
use App\Http\Models\Common\ValidateModel;

class EmailLogic extends Logic
{

    private $email          = '';
    private $title          = '';
    private $subject        = '';
    private $cc             = '';
    private $attachment     = '';

    private $model  = null;

    public function __construct($email,$title,$subject,$cc,$attachment){

        $this->log    = [
            'email'     => $email,
            'title'     => $title,
            'subject'   => $subject,
            'cc'        => $cc,
            'file'      => $attachment
        ];

        $this->email      = $email;
        $this->title      = $title;
        $this->subject    = $subject;
        $this->cc         = $cc;
        $this->attachment = $attachment;

        $this->model  = new EmailModel();
    }

    private function validate(){

        //验证收件人邮箱是否为空
        ValidateModel::isEamil($this->email);
        //验证标题是否为空
        ValidateModel::isTitle($this->title);
        //验证内容是否为空
        ValidateModel::isSubject($this->subject);
    }

    /**
     * @param string $func  类型，默认为内容为字符型
     * @return array
     * 发送邮件
     */
    public function sendContent($func = 'sendContent'){

        try{
            self::validate();

            $this->model->sendContent($this->email,$this->title,$this->subject,$this->cc,$this->attachment,$func);

        }catch (\Exception $e){

            self::saveLog($e);

            return self::callError($e->getMessage());
        }
        return self::callSuccess();

    }

    /**
     * @param \Exception $e
     * 保存日志
     */
    private function saveLog(\Exception $e){
        $this->log["code"] = $e->getCode();
        $this->log["msg"]  = $e->getMessage();
        Log::error(__METHOD__."Error",$this->log);
    }
}