<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
 * example to see how to use XOAUTH2.
 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
 */

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

require './vendor/autoload.php';
require './.env.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//echo "POST!"; TODO:ログを残す
    error_log('Request Post');
    if($_POST['email'] != $_POST['reemail']){
        //echo "メールアドレスをご確認ください";       TODO:ログを残す
	error_log('mail adress not equal');
    }

    else{
        //Create a new PHPMailer instance
        $mail = new PHPMailer;

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 2;

        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;

        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "soulspice2006@gmail.com";

        //Password to use for SMTP authentication
        $mail->Password = $password;

        //Set who the message is to be sent from
        $mail->setFrom('soulspice2006@gmail.com', $_POST['name']);

        //Set an alternative reply-to address
        $mail->addReplyTo($_POST['email'], $_POST['name']);

        //Set who the message is to be sent to
        $mail->addAddress('soulspice2006@gmail.com', 'SoulSpice窓口');

        //Set the subject line
        $mail->Subject = "ホームページからからお問い合わせがありました";

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        $mail->Body =   "【お名前】" . "\r" . $_POST['name'] . "\r" . "\r" .
                        "【ご連絡先】" . "\r" . $_POST['email'] . "\r" . "\r" .
                        "【問い合わせ内容】" . "\r" . $_POST['content'] . "\r" . "\r" ;

        //Replace the plain text body with one created manually
        $mail->AltBody = $_POST['content'];

        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');

		//send the message, check for errors
		ob_start();
        if (!$mail->send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo;  TODO:ログを残す、エラー報知する
            error_log("error log Mailer Error\n");
        } else { 
            error_log("success log Mailer sent\n");     
            //echo "Message sent!"; TODO:ログを残す、エラー報知する
            //Section 2: IMAP
            //Uncomment these to save your message in the 'Sent Mail' folder.
            #if (save_mail($mail)) {
            #    echo "Message saved!";
            #}
		}
		ob_get_clean();
    }
}
//Section 2: IMAP
//IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
//Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
//You can use imap_getmailboxes($imapStream, '/imap/ssl') to get a list of available folders or labels, this can
//be useful if you are trying to get this working on a non-Gmail IMAP server.
function save_mail($mail)
{
    //You can change 'Sent Mail' to any other folder or tag
    $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";

    //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
    $imapStream = imap_open($path, $mail->Username, $mail->Password);

    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
    imap_close($imapStream);

    return $result;
}
?>
