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

<!DOCTYPE html>
<html lang="ja">
<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113487092-3"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-113487092-3');
	</script>

	<title>小平市で活動しているキッズダンスサークル、ソウルスパイスです</title>

	<!-- meta -->
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="小平市で活動しているキッズダンスサークル、ソウルスパイスです。"/>

	<!-- css -->
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="bower_components/ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="assets/css/owl.carousel.css">
	<link rel="stylesheet" href="assets/css/owl.theme.css">
	<link rel="stylesheet" href="assets/css/animate.css">
	<link rel="stylesheet" href="assets/css/style.css">

	<!-- fonts -->
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic|Roboto+Condensed:300italic,400italic,700italic,400,300,700|Oxygen:400,300,700' rel='stylesheet'>

	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!--[if lt IE 9]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<meta name="google-site-verification" content="s-tDk-yL-JSXvitS47FMNocaMZ2owAqFHqeFTEGhNU0" />
</head>
<body id="home">

	<!-- ****************************** Preloader ************************** -->

	<div id="preloader"></div>

	<!-- ****************************** Sidebar ************************** -->

	<nav id="sidebar-wrapper">
		<a id="menu-close" href="#" class="close-btn toggle"><i class="ion-ios-close-empty"></i></a>
	    <ul class="sidebar-nav">
		    <li><a href="#home">HOME -最初のページ-</a></li>
			<li><a href="#features">NEWS -最新情報-</a></li>
			<li><a href="#gallery">ABOUT -サークル紹介-</a></li>
			<li><a href="#team">INSTRUCTOR -コーチ紹介-</a></li>
			<li><a href="#testimonial">TIME TABLE -スケジュール-</a></li>
			<li><a href="#movie">MOVIE -メンバーが踊る動画-</a></li>
			<li><a href="#contact">CONTACT -お問い合わせ-</a></li>
	    </ul>
	</nav>

	<!-- ****************************** Header ************************** -->

	<header class="sticky" id="header">
		<section class="container">
			<section class="row" id="logo_menu">
				<section class="col-xs-6"><a class="logo" href="">SOULSPiCE</a></section>
				<section class="col-xs-6"><a id="menu-toggle" href="#" class="toggle wow rotateIn" data-wow-delay="1s"><i class="ion-navicon"></i></a></section>
			</section>
		</section>
	</header>

	<!-- ****************************** Banner ************************** -->


	<section id="banner" >
		<section class="container">
			<!-- <a class="slidedown wow animated zoomIn" data-wow-delay="2s" href="#features"><i class="ion-ios-download-outline"></i></a> -->
			<section class="row">
				<div class="col-md-6">
					<div class="headings">
						<h1 class="wow animated fadeInDown">SOUL SPiCE</h1>
						<p class="wow animated fadeInLeft">小平で活動する<BR/>キッズダンスサークル</p>
					</div>
				</div>
			</section>
		</section>
	</section>

	<section id="topnews" >
		<section class="container">
			<section class="row"><a href="#contact" style="padding:0em;margin:0em">
					<div class="col-md-6">
						<div class="topnews">
							<p class="wow animated fadeInLeft">新規メンバー ＆ イベントブッキング 募集中！
							</p>
						</div>
					</div>
			</section></a>
		</section>
	</section>

	<section id="sns" >
		<section class="container">
			<section class="row">
				<ul class="team-social">
					<li class="wow animated fadeInLeft email"><a href="#contact"><i class="ion-email"></i></a></li>
					<li class="wow animated fadeInLeft instagram"><a href="https://www.instagram.com/soulspice2006/"><i class="ion-social-instagram"></i></a></li>
					<li class="wow animated fadeInRight facebook"><a href="https://www.facebook.com/soulspice/"><i class="ion-social-facebook"></i></a></li>
					<li class="wow animated fadeInRight youtube"><a href="https://youtu.be/WaeXxwmHWYI?list=PLJgPtg4l-yVZ8FlF_mMu439KD6322ytoj"><i class="ion-social-youtube"></i></a></li>
				</ul>
			</section>
		</section>
	</section>


	<!-- ****************************** NEWS Section ************************** -->

	<section id="features" class="block">
		<section class="container">
			<section class="row">
				<div class="title-box"><h1 class="block-title fadeInUp animated" data-wow-delay="0.3s">
				<span class="bb-top-left"></span>
				<span class="bb-bottom-left"></span>
				NEWS
				<span class="bb-top-right"></span>
				<span class="bb-bottom-right"></span>
				</h1></div>
			</section>
			
			<section class="row">
				<div class="col-sm-6 col-md-4">
					<div class="feature-box fadeInUp animated" data-wow-delay="0.3s">
						<div class="item"><img src="assets/img/NEWS-2018-08-19.jpeg" class="img_res fadeInUp animated"></div>
						<p>2018/08/19</p>
						<h2>町田踊り場建設さん主催のわっしょいに参加</h2>
						<p>町田市民ホールで行われた、町田踊り場建設さん主催の、わっしょいに、soulspiceも参加しました！いつもとは違う、照明に、大きなステージ、みんな楽しそうに踊っていました！お疲れ様でした💕</p>
					</div>
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="feature-box fadeInUp animated" data-wow-delay="0.3s">
						<div class="item"><img src="assets/img/NEWS-2018-05-27.jpg" class="img_res fadeInUp animated"></div>
						<p>2018/05/27</p>
						<h2>brustfloraがRUNUPダンスコンテスト2018 KANTO MAYで優勝!!</h2>
						<p>東大和ハミングホールにて行われたRUNUPダンスコンテスト2018 KANTO MAYにてSOUL SPiCEからyorika,harukaの2組brustfloraがU15部門にて優勝しました！</p>
					</div>
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="feature-box wow fadeInUp animated" data-wow-delay="0.3s">
						<div class="item"><img src="assets/img/NEWS-2018-05-13.jpg" class="img_res fadeInUp animated"></div>
						<p>2018/05/13</p>
						<h2>小平のグリーンフェスティバルに出演!!</h2>
						<p>今年も小平のグリーンフェスティバルに出演させていただきました！</p>
						</div>
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="feature-box wow fadeInUp animated" data-wow-delay="0.3s">
						<div class="item"><img src="assets/img/NEWS-2018-03-18.jpg" class="img_res fadeInUp animated"></div>
						<p>2018/03/18</p>
						<h2>TokyoFootWorksのShinさん主催のイベントにて3年連続優勝!!</h2>
						<p>TokyoFootWorksのShinさん主催のイベントにて、SOUL SPiCEが3年連続優勝しました！</p>
						</div>
				</div>				
			</section>
			<div class="clearfix"></div>
		</section>
	</section>
	
	<!-- ****************************** Gallery Section ************************** -->

	<section id="gallery" class="block">
		<section class="container">
				<section class="row">
					<div class="title-box" style="color:#fff;" >
						<h1 class="block-title wow animated rollIn">
							<span class="bb-top-left" style="border-color:#fff;"></span>
							<span class="bb-bottom-left" style="border-color:#fff;"></span>
							ABOUT
							<span class="bb-top-right" style="border-color:#fff;"></span>
							<span class="bb-bottom-right" style="border-color:#fff;"></span>
						</h1>
					</div>
				</section>

				<section class="row">
						<div class="col-sm-10 col-sm-offset-1">
							<p style="color:#fff;"><BR/>
							<strong>小平を中心に活動しているキッズダンスサークルです</strong><BR/>
							<BR/>
							活動をはじめてから１０年が経ちました！<BR/>
							小学校１年生から中学校3年生までのキッズ達が、楽しく活動しています。
							ほとんどの子供たちが初心者からダンスを始めました。<BR/>
							ヒップホップを中心に、ロック、ハウスとなかなかキッズダンスでは習えないダンスに挑戦しています。<BR/>
							３～４ヶ月に１回はイベントに参加し、みんなの練習の成果を発表する場を設けていますので、子ども達のモチベーションもＵＰ♪<BR/>
							子ども達はいつも次のイベントを楽しみにレッスンを受けています★<BR/></p>
						</div>
					</section>
		
		</section>
	</section>

	<!-- ****************************** Team Section ************************** -->

	<section id="team" class="block">
		<section class="container">
			<section class="row">
				<div class="col-md-12">
					<div class="title-box">
						<h1 class="block-title wow animated rollIn">
							<span class="bb-top-left"></span>
							<span class="bb-bottom-left"></span>
							Instructor
							<span class="bb-top-right"></span>
							<span class="bb-bottom-right"></span>
						</h1>
					</div>
				</div>
			</section>
			<section class="row">
				<section class="col-md-6 col-sm-6 center-block">
					<div class="team-member wow animated fadeIn center-block" data-wow-delay=="0.3s">
						<img src="assets/img/yukky.jpg" class="img_res team-pic"style="border-radius:400px" >
						<h2 class="wow animated fadeInDown" data-wow-delay=="0.7s">YUKKY</h2>
						<ul class="team-social">
							<li class="wow animated fadeInLeft instagram"><a href="https://www.instagram.com/yukky0204/"><i class="ion-social-instagram"></i></a></li>
							<li class="wow animated fadeInRight facebook"><a href="https://www.facebook.com/tanobe.yuuki"><i class="ion-social-facebook"></i></a></li>
						</ul>
						<p class="wow animated fadeIn" data-wow-delay=="0.7s">
								土曜日：HOUSE・HIPHOPクラス担当<BR/><BR/>
								HOUSEを中心に、HIPHOPなどを取り入れていきます。<BR/>
								基礎からHOUSEのノリ、応用までしっかりレッスンしていきます。<BR/>
								<BR/>
								ナオトインティライミ 出演<BR/>
								『ナイテタッテ』PV出演<BR/>
								ももいろクローバーZ<BR/>
								ファミリーマートCM振付用ビデオ<BR/>
								<BR/><BR/>
								自身のチーム1zmとして以下の成績を残す。<BR/>
								<BR/>
								RUNUPダンスコンテスト予選オープン部門<BR/>準優勝<BR/><BR/>
								dancetimeボカンコンテストvol11一般部門<BR/>特別賞<BR/><BR/>
								学園p天国ダンスコンテストファイナル一般部門<BR/>優勝<BR/><BR/>
								その他、コンテスト受賞多数 <BR/>
								<BR/><BR/>
								自身のプロデュースする<BR/>ソウルスパイスの中のKidsチーム<BR/>
								<h3>🎀TEMPEST🎀</h3>(miona,rio,yuuka,yui)<BR/><BR/>
								2015年3月<BR/>RUNUPダンスコンテストキッズ部門<BR/>準優勝<BR/><BR/>
								2015年6月<BR/>avexセブンアンドアイ<BR/>キッズダンスコンテスト横浜予選 <BR/>ジュニア部門優勝<BR/><BR/>
								2015年7月<BR/>調布ストリートダンスコンテスト<BR/>審査員特別賞<BR/><BR/>
								2015年8月<BR/>avexセブンアンドアイ<BR/>キッズダンスコンテスト全国決勝大会 <BR/>ジュニア部門SAM(TRF)賞<BR/><BR/>
								2016年7月<BR/>調布ストリートダンスコンテスト<BR/>審査員特別賞<BR/><BR/>
								2016年12月<BR/>ドラドラコンテスト2nd U15<BR/>優勝<BR/><BR/>
								<h3>🎀Brust Flora🎀</h3>(haruka,yorika)<BR/><BR/>
								2018年5月<BR/>RUNUPダンスコンテスト関東MAY<BR/>U15部門 優勝<BR/>
						</p>
					</div>
				</section>
				<section class="col-md-6 col-sm-6 center-block">
					<div class="team-member wow animated fadeIn center-block" data-wow-delay=="0.3s">
						<img src="assets/img/takuya.jpg" class="img_res team-pic" style="border-radius:400px" >
						<h2 class="wow animated fadeInDown" data-wow-delay=="0.7s">TAKUYA</h2>
						<ul class="team-social">
							<li class="wow animated fadeInLeft instagram"><a href="https://www.instagram.com/takuya_820/"><i class="ion-social-instagram"></i></a></li>
							<li class="wow animated fadeInRight facebook"><a href="https://www.facebook.com/takuya.tanobe"><i class="ion-social-facebook"></i></a></li>
						</ul>
						<p class="wow animated fadeIn" data-wow-delay=="0.7s">
								水曜日：LOCK・FREE STYLEクラス担当<BR/><BR/>
								LOCKを中心に様々なジャンルを取り入れたFREESTYLEとしてレッスンを行なっています。<BR/>
								現在アーティスト、CM振付やバックアップダンサーとして活動中。<BR/><BR/>

								ポルノグラフィティ<BR/>「俺たちのセレブレーション」バックダンサー<BR/>
								<BR/>
								嵐<BR/>「GUTS」紅白歌合戦バックダンサー<BR/>
								<BR/>
								ウルフルズ<BR/>「ロッキン50肩ブギウギックリ腰」<BR/>
								MV、バックダンサー<BR/>
								<BR/>
								嵐<BR/>「I seek」バックダンサー<BR/>
								<BR/>
								炭酸飲料水「Metz」<BR/>
								振付アシスタント<BR/>
								<BR/>
								嵐<BR/>「LIVE TOUR」<BR/> 2015〜2017 振付アシスタント、Jr振付<BR/><BR/>
								嵐<BR/>アルバム untitled 「抱擁」振付<BR/><BR/>
								亀梨和也<BR/>「Follow me〜LIVE TOUR2017」振付、演出アシスタント<BR/><BR/>
								karam<BR/>「Rise up」<BR/>リリースイベントツアー バックダンサー<BR/>
					</div>
				</section>
			</section>
		</section>
	</section>

	<!-- ****************************** TimeTable ************************** -->

	<section id="testimonial" class="block">
		<section class="container">
			<section class="row">
				<div class="title-box"><h1 class="block-title wow animated rollIn">
				<span class="bb-top-left"></span>
				<span class="bb-bottom-left"></span>
				TIME TABLE
				<span class="bb-top-right"></span>
				<span class="bb-bottom-right"></span>
				</h1></div>
			</section>
		</section>
		<section class="container">
				<table class="table" style="color:#fff;">
						<thead>
						  <tr>
							<th scope="col">#</th>
							<th scope="col">水曜</th>
							<th scope="col">土曜</th>
						  </tr>
						</thead>
						<tbody>
						  <tr>
							<th scope="row">18:00～18:50</th>
							<td>LOCK・FREESTYLE<BR/>スタートクラス<BR/>TAKUYA</td>
							<td>HOUSE・HIPHOP<BR/>スタートクラス<BR/>YUKKY</td>
						  </tr>
						  <tr>
							<th scope="row">19:00～21:00</th>
							<td>LOCK・FREESTYLE<BR/>初中級<BR/>TAKUYA</td>
							<td>HOUSE・HIPHOP<BR/>初中級<BR/>YUKKY</td>
						  </tr>
						</tbody>
					  </table>
		</section>
	</section>

	<!-- ****************************** MOVIE Section ************************** -->

	<section id="movie" class="block">
			<section class="container">
				<section class="row">
					<div class="col-md-12">
						<div class="title-box">
							<h1 class="block-title wow animated rollIn">
								<span class="bb-top-left"></span>
								<span class="bb-bottom-left"></span>
								MOVIE
								<span class="bb-top-right"></span>
								<span class="bb-bottom-right"></span>
							</h1>
						</div>
					</div>
				</section>

				<section class="row">
						<p><BR/></p>
						<!-- 16:9 aspect ratio -->
						<div class="embed-responsive embed-responsive-16by9 col-md-12">
						<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/videoseries?list=PLJgPtg4l-yVZ8FlF_mMu439KD6322ytoj&amp;ecver=1" frameborder="0" allow="autoplay; " allowfullscreen></iframe>						
						</div>
				</section>
			</section>
		</section>
		

	<!-- ****************************** Contact Section ************************** -->

	<section id="contact">
			<section class="container contact-wrap">
				<section class="row">
					<div class="title-box"><h1 class="block-title wow animated rollIn">
					<span class="bb-top-left"></span>
					<span class="bb-bottom-left"></span>
					Contact Us
					<span class="bb-top-right"></span>
					<span class="bb-bottom-right"></span>
					</h1></div>
				</section>
			</section>
			<section class="mailbox">
				<div class="container">
					<div class="col-sm-12">
						<form  action="" method="POST" name="sentMessage" id="contactForm">
	                        <div class="row">
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <input type="text" name="name" class="form-control" placeholder="お名前" id="name" required data-validation-required-message="Please enter your name." value="">
	                                    <p class="help-block text-danger"></p>
	                                </div>
	                                <div class="form-group">
	                                    <input type="email" name="email" class="form-control" placeholder="メールアドレス" id="email" required data-validation-required-message="Please enter your email address."value="">
	                                    <p class="help-block text-danger"></p>
	                                </div>
	                                <div class="form-group">
	                                    <input type="email" name="reemail" class="form-control" placeholder="メールアドレスをもう一度ご入力ください" id="email" required data-validation-required-message="Please enter your email address."value="">
	                                    <p class="help-block text-danger"></p>
	                                </div>
	                            </div>
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <textarea class="form-control"  name="content" placeholder="お問い合わせ内容  (例：サークル参加についての詳細や、イベントブッキングについてなど)" id="message" required data-validation-required-message="Please enter a message."value=""></textarea>
	                                    <p class="help-block text-danger"></p>
	                                    <div id="success"></div>
		                                <button type="submit" class="polo-btn contact-submit"><i class="ion-paper-airplane"></i></button>
	                                </div>
	                            </div>
	                        </div>
	                    </form>
					</div>
				</div>
			</section>
			<div class="clearfix"></div>
		</section>

		<!-- ****************************** Footer ************************** -->

		<section id="footer">
			<section class="container">
				<section class="row">
					<div class="col-sm-6">
						<p class="copyright">All Copyright Reserved 2018</p>
					</div>
				</section>
			</section>
		</section>


	<!-- All the scripts -->

	<script src="bower_components/jquery/dist/jquery.min.js"></script>
	<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="assets/js/wow.min.js"></script>
	<script src="assets/js/owl.carousel.js"></script>
	<script src="assets/js/script.js"></script>
	
</body>
</html>
