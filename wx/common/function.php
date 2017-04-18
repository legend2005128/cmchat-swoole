<?php
/**
 * Created by PhpStorm.
 * User: mj
 * Date: 2017/3/28
 * Time: 16:24
 */

ini_set('date.timezone','Asia/Shanghai');
global $email_config_arr;
$email_config_arr = array(
    'email_user'=>'mokasz@mail.ldbnx.com',
    'email_password' =>'mokasz12345678',
    'email_host' => 'mail.ldbnx.com'
);
function admin_send_emails( $to = array(), $subject, $content, $cc = false, $attach = false)
{
    global $email_config_arr;
    $mail = new PHPMailer();
    $mail->CharSet='UTF-8';
    //$mail->SMTPDebug = 3;
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $email_config_arr['email_host'];  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username =  $email_config_arr['email_user'];          // SMTP username
    $mail->Password =  $email_config_arr['email_password'];                            // SMTP password
    $mail->SMTPAutoTLS = false;
    $mail->SMTPSecure = '';
    $mail->Port = 25;                                    // TCP port to connect to
    $mail->setFrom($email_config_arr['email_user'], '');
    $mail->IsHTML(true);
    if ($to) {
        if(is_array($to))
        {
            foreach ($to as $it_o) {
                $mail->addAddress($it_o,'');     // Add a recipient
            }
        }elseif (is_string($to))
        {
            $mail->addAddress($to,'');     // Add a recipient
        }
    }
    if ($cc) {
        if(is_array($cc)) {
            foreach ($cc as $it_c) {
                $mail->addCC($it_c);     // Add a recipient
            }
        }else{
            $mail->addCC($cc);     // Add a recipient
        }
    }
    if ($attach) {
        if (file_exists($attach)) {
            $mail->addAttachment($attach);         // Add attachments
        }

    }
    $mail->Subject = $subject;
    $mail->Body = $content;
    if (!$mail->send()) {
        return $mail->ErrorInfo;
    } else {
        return true;
    }
}
function curlGet($url, $data = '', $type = 'array',$timeout=4)
{
    if (is_array($data)) {
        $url = $url . '?' . http_build_query($data);
    } elseif (is_string($data) && !empty($data)) {
        $url = $url . '?' . $data;
    }
    //初始化
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($type == 'json') {
        return $result;
    }
    else {
        if($result) {
            return json_decode($result, true);
        }
        else{
            return false;
        }
    }
}

/**
 * @param $url
 * @param array $data
 * @param string $type  返回类型 array json
 * @return mixed
 * curl  post方式Http请求封装
 */
function curlPost($url,$data=array(),$type='array'){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_TIMEOUT,30);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);
    //记录非正常接口日志信息
    if($httpCode != 200){
        \Think\Log::write('接口状态：'.$httpCode.' URL地址：'.$url.', 参数:'.json_encode($data));
    }

    if($type == 'json'){
        return $result;
    }else{
        return json_decode($result,true);
    }
}