<?php
// /*
// * Plugin Name: WP jQuery Spam
// * Plugin URI: http://www.sum16.com/my/wp-jquery-spam.html
// * Description: 利用jQuer插入评论隐藏域达到防止垃圾评论的目的
// * Author: Soar
// * Author URI: http://www.sum16.com/
// * Version: 1.2
// * Put in /wp-content/plugins/ of your Wordpress installation
// */
if (! function_exists ( 'wp_jquery_spam_head' )) {
	require dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'functions.php';
	function wp_jquery_spam_head() {
		if (is_page () || is_single ()) {
			wp_enqueue_script ( 'jquery' );
			echo "<script type=\"text/javascript\" src=\"" . plugins_url ( 'wp-jquery-spam/dynamic.php' ) . "?id=" . get_the_ID () . "\"></script>\n";
		}
	}
	add_action ( 'wp_head', 'wp_jquery_spam_head' );
	function wp_jquery_spam_filter($incoming_comment) {
		if (is_admin ())
			return $incoming_comment; // 如果是管理员
		$post_id = $incoming_comment ["comment_post_ID"];
		if ($_POST ["post-$post_id"] != wp_jquery_spam_hash_id ( $post_id )) {
			$newline = PHP_EOL;
			$json = '时间:' . date ( 'Y-m-d H:i:s', time () ) . $newline;
			$json .= 'User:' . $incoming_comment ['comment_author'] . $newline;
			$json .= 'PostId:' . $post_id . $newline;
			$json .= 'URL:' . $_SERVER ['HTTP_REFERER'] . $newline;
			$json .= 'IP:' . $_SERVER ['SERVER_ADDR'] . $newline;
			$json .= 'UA:' . $_SERVER ['HTTP_USER_AGENT'] . $newline;
			$json .= 'Content:' . $incoming_comment ['comment_content'] . $newline;
			$json .= '====================' . $newline;
			$path = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'robot';
			if (! is_dir ( $path ))
				mkdir ( $path );
			$path .= DIRECTORY_SEPARATOR . date ( "Y-m-d" ) . '.txt';
			file_put_contents ( $path, $json, FILE_APPEND );
			$msg = '禁止机器人提交评论';
			if (function_exists ( 'mfthemes_ajax_comment_err' )) {
				mfthemes_ajax_comment_err ( $msg );
			} else if (function_exists ( 'wp_die' )) {
				wp_die ( $msg );
			} else {
				die ( $msg );
			}
		}
		return ($incoming_comment);
	}
	add_filter ( 'preprocess_comment', 'wp_jquery_spam_filter' );
}