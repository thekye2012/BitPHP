<?php 
	
	class HtmlToMail {

		const FROM = 'bot@root404.com';
		const REPLY_TO = 'no-reply@root404.com';

		public function send( $to, $html, $subject, $vars ) {
			global $_APP;

			$html = @file_get_contents( $_APP . '/views/mails/' . $html . '.html' );
			if( $html === FALSE ) { return 0; }

			$search = array();
			$replace = array();

			foreach ($vars as $var => $val) {
				array_push( $search , '@' . $var);
				array_push(  $replace, $val);
			}

			$html = str_replace($search, $replace, $html);

			$headers = 'From: ' . self::FROM . "\n";
			$headers .= 'Reply-To: ' . self::REPLY_TO . "\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: text/html; charset=utf-8\n";

			mail( $to, $subject, $html, $headers );
		}
	}
?>