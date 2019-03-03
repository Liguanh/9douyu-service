<?php
/**
 * 短信模块
 * User: bihua
 * Date: 16/4/22
 * Time: 18:20
 */
namespace App\Http\Models\Mail;

use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use App\Services\Mail\Email;
use Log;
use App\Http\Models\Model;
use Illuminate\Support\Facades\Storage;

class EmailModel extends Model
{
    public static $codeArr = [
        'sendEmail'         => 1
    ];
    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_EMAIL;

    /**
     * @param $email
     * @param $title
     * @param $subject
     * @param string $cc
     * @param string $attachment
     * @param string $func
     * @throws \Exception
     * 发送邮件
     */
    public function sendContent($email,$title,$subject,$cc = '',$attachment = '',$func = 'sendContent')
    {

        $to = self::formatEmail($email);
        //保存邮件的附件
        $attachment = $this->saveAttachment($attachment);

        $mail = new Email($to);

        $res = $mail->$func($to, $title, $subject, $cc,$attachment);

        if(empty($res)){

        }else{

            Log::info("邮件发送结果：".print_r($res,true));

            throw new \Exception(LangModel::getLang("ERROR_SEND_EMAIL_FAILED"), self::getFinalCode("sendEmail"));
        }

        //删除附件
        $this->deleteAttachment($attachment);

    }

    /**
     * @param $attachment
     * @return array
     * 保存上传的附件
     */
    private function saveAttachment($attachment){

        $fileArr = [];

        //存在附件,先保存到本地
        if($attachment){

            $dirName    = base_path() . "/storage/attachment/";
            if(!is_dir($dirName)) {
                mkdir($dirName, 0777);
                chmod($dirName, 0777);
            }

            foreach ($attachment as $file=>$fileObj){

                $ext = $fileObj->getClientOriginalExtension();
                $savePath   = $dirName.$file.'.'.$ext;
                $res = $fileObj->move($dirName,$savePath);
                if($res)
                    $fileArr[] = $savePath;
            }

        }
        return $fileArr;
    }

    /**
     * @param $attachment
     * 发送完附件直接删除
     */
    private function deleteAttachment($attachment){

        if($attachment){
            foreach($attachment as $file) {
                @unlink($file);
            }
        }

    }

    /**
     * @param $email
     * @return array
     * 格式化邮件收件人
     */
    private function formatEmail($email){

        $arr = explode(",",$email);
        if(count($arr) > 1){
            $to = $arr;
        }else{
            $to = $email;
        }
        return $to;

    }

}