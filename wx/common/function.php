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