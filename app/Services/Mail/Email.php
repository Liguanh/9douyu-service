<?php
/**
 * 邮件服务
 * User: bihua
 * Date: 16/5/4
 * Time: 17:24
 */
namespace App\Services\Mail;

use Log;
use Laravel\Lumen\Application;
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;

class Email extends Message
{
    public $config;
    protected $to;

    public function __construct($to){
        $app = new Application();
        $app->configure('mail');
        $list = (array)$app["config"]["mail"];
        $this->config = array(
            'host'  => empty($list['host']) ? 'smtp.sunfund.com' : $list['host'],
            'port'  => empty($list['port']) ? 25 : $list['port'],
            'username' => empty($list['username']) ? 'mail@sunfund.com' : $list['username'],
            'password' => empty($list['password']) ? 'Txd3RA9g1234' : $list['password'],
            'name'     => empty($list["from"]["name"]) ? '九斗鱼' : $list["from"]["name"],
            'secure'   => 'ssl',
        );
        //$this->setFrom($this->config["username"]);
        if(is_array($to)){
            foreach ($to as $email) {
                $this->addTo($email);
            }

        }else{
            $this->addTo($to);
        }
    }

    public function from(){
        $this->setFrom($this->config["username"],$this->config["name"]);
        return $this;
    }

    /**
     * 设置收件人
     * @param null $to
     * @return Email|bool
     */
    public static function to($to = null){
        if(!$to){
            return false;
        }
        return new Email($to);
    }

    /**
     * 添加抄送人
     * @param null $cc
     * @return $this|bool
     */
    public function cc($cc = ''){
        if(!$cc){
            return $this;
        }
        $arr = explode(",",$cc);
        foreach ($arr as $email) {
            $this->addCc($email);
        }
        return $this;
    }

    /**
     * 设置邮件标题
     * @param null $title
     * @return $this|bool
     */
    public function subject($title = null){
        if(!$title){
            return false;
        }
        $this->setSubject($title);
        return $this;
    }

    /**
     * 带HTML格式的邮件内容
     * @param null $content
     * @return $this|bool
     */
    public function htmlContent($content = null){
        if(!$content){
            return $this;
        }
        $this->setHtmlBody($content);
        return $this;
    }

    /**
     * 纯字符型邮件内容
     * @param null $content
     * @return $this|bool
     */
    public function content($content = null){
        if(!$content){
            return $this;
        }
        $this->setBody($content);
        return $this;
    }

    /**
     * 添加附件
     * @param string $file
     * @return $this|bool
     */
    public function attachment($attachment = ''){
        if(!$attachment){
            return $this;
        }
        if(is_array($attachment)){
            foreach($attachment as $file)
                $this->addAttachment($file);
        }
        return $this;
    }

    /**
     * 发送字符型邮件
     * @param $to
     * @param $title
     * @param $content
     * @param string $cc
     * @return bool
     */
    public function sendContent($to,$title,$content,$cc = '',$attachment = ''){
        $mail = Email::to($to)
              ->cc($cc)
              ->from()
              ->subject($title)
              ->content($content)
              ->attachment($attachment);
        $mailer = new SmtpMailer($this->config);
        $res = $mailer->send($mail);
        return $res;
    }

    /**
     * 发送html格式邮件
     * @param $to
     * @param $title
     * @param $content
     * @param string $cc
     * @return bool
     */
    public function sendHtml($to,$title,$content,$cc = '',$attachment = ''){
        $mail = Email::to($to)
              ->cc($cc)
              ->from()
              ->subject($title)
              ->htmlContent($content)
              ->attachment($attachment);
        //$mail->setHeader("Content-type","text/html");
        $mail->setContentType("text/html","UTF-8");
        $mailer = new SmtpMailer($this->config);
        $res = $mailer->send($mail);
        return $res;
    }
}
