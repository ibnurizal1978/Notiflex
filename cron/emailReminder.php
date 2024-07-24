<?php
require "../config.php";
chdir("/var/www/getnotiflex.com/apps/cron/");
ini_set('display_errors',1);  error_reporting(E_ALL);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../plugins/PHPMailer/src/Exception.php';
require '../plugins/PHPMailer/src/PHPMailer.php';
require '../plugins/PHPMailer/src/SMTP.php';

/*
1. cek data yang ada expirednya
2. kalau ada, cek user email receiver utk kategori ini 
*/
$s = "SELECT a.object_id, a.reminder_type_id, b.category_master_id, a.object_expiration_id, c.category_master_name, date_format(start_date, '%M %d, %Y') as start_date, date_format(expired_date, '%M %d, %Y') as expired_date, date_format(expired_date, '%Y-%m-%d') as expired_date2, datediff(expired_date, CURDATE()) difference, b.name as name FROM tbl_object_expiration a INNER JOIN tbl_object b USING (object_id) INNER JOIN tbl_category_master c USING (category_master_id) WHERE archived_status = 0 AND date(expired_date) >= curdate()";
$h = mysqli_query($conn, $s);

if(mysqli_num_rows($h) == 0)
{
    echo 'No expiration data.';
}else{
    while($r = mysqli_fetch_assoc($h))
    {
        echo '<hr/><b>ID: </b> '.$r['object_id'].' | <b>Name: '.$r['name'].' | Expired in: '.$r['difference'].' | expired date: '.$r['expired_date'].' | cat: '.$r['category_master_name'].'</b><br/>';
        $s2 = "SELECT reminder_duration_id, reminder_day, client_id FROM tbl_reminder_duration WHERE reminder_type_id = '".$r['reminder_type_id']."'";
        $h2 = mysqli_query($conn, $s2);
        while($r2 = mysqli_fetch_assoc($h2))
        {
            echo 'Remind on '.$r2['reminder_day'].' day(s) before due date<br/>';
            if($r['difference'] == $r2['reminder_day'])
            {
                $s3 = "SELECT DISTINCT full_name, username FROM tbl_user a INNER JOIN tbl_group_receiver_detail b USING (user_id) INNER JOIN tbl_group_receiver c USING (group_receiver_id) INNER JOIN tbl_category_reminder_receiver d USING (group_receiver_id) WHERE category_reminder_id = '".$r2['category_reminder_id']."'";                
                $h3 = mysqli_query($conn, $s3);
                while($r3 = mysqli_fetch_assoc($h3))
                {
                    //send mail
                    $body = 'Hello '.$r3['full_name'].',<br/><br/>You have an upcoming expiration document:<br/><br/><b>'.$r['category_name'].'</b> for </b>'.$r['name'].'</b>.<br/><br/>Its expired date is <b>'.$r['expired_date'].'</b> ('.$r['difference'].' day(s) from now)'.$smtp_footer;
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->SMTPDebug = false;
                    $mail->do_debug = 0;
                    $mail->SMTPSecure   = $smtp_secure_method;
                    $mail->Host         = $smtp_host;
                    $mail->Port         = 587;
                    $mail->SMTPAuth     = true;
                    $mail->Timeout      = 60;
                    $mail->SMTPKeepAlive = true;
                    $mail->Username     = $smtp_username;
                    $mail->Password     = $smtp_password;
                    $mail->setFrom($smtp_sender_email, $smtp_sender_name);
                    $mail->addAddress($r3['username'], $r3['full_name']);
                    $mail->isHTML(true);
                    $mail->Subject      = 'Upcoming Expire Document';
                    $mail->Body         = $body;
                    if(!$mail->send()) {
                        echo 'Message was not sent.';
                        $email_sent_status = 'ERROR: '. $mail->ErrorInfo;
                        echo 'Mailer error: ' . $mail->ErrorInfo;
                    } else {
                        $email_sent_status = 'SUCCESS';
                        echo 'Message has been sent to '.$r3['username'].'<br/>';
                    }

                    //input to tbl_log_send_reminder so we can see what and when things to email
                    $s_log = "INSERT INTO tbl_log_send_email_reminder SET list_expiration_id = '".$r['list_expiration_id']."', name = '".$r['name']."', expired_in = '".$r['difference']."', expired_date = '".$r['expired_date2']."', category_name = '".$r['category_name']."', remind_in = '".$r2['reminder_day']."', recipient_name = '".$r3['full_name']."', recipient_email = '".$r3['username']."', email_sent_status = '".$email_sent_status."', client_id = '".$r2['client_id']."', created_at = UTC_TIMESTAMP()";
                    mysqli_query($conn, $s_log);

                }
            }
        }
    }
}