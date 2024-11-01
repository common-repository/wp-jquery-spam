<?php
if (! function_exists ( 'wp_jquery_spam_hash_id' )) {
	date_default_timezone_set ( 'PRC' );
	function wp_jquery_spam_hash_id($id) {
		return md5 ( str_replace ( 'a', '', md5 ( base64_encode ( base64_encode ( $id ) . '-' . $id ) ) ) );
	}
}