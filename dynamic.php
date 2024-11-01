<?php
$id = $_GET ['id'] * 1;
require_once dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'functions.php';
header ( 'Content-Type:application/javascript' );
$html = "<input type='hidden' name='post-$id' />";
?>jQuery(function(){var hide = jQuery("<?php echo $html ?>");hide.val('<?php echo wp_jquery_spam_hash_id($id) ?>');jQuery('#comment_post_ID').after(hide);});