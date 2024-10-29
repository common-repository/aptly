<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
?>

<div>

<br>
You are about to remove Aptly completely. The OrgId and your hash will be permanently removed.
<br>

</div>

<?php
// $option = get_option('cerkl_option');
// delete_option('cerkl_options');
delete_option('aptly_hash');
delete_option('aptly_orgId');
delete_option('aptly_image_width');
delete_option('aptly_image_type');
delete_option('aptly_image_height');
delete_option('aptly_headline_bold');
delete_option('aptly_headline_font_size');
delete_option('aptly_headline_line_height');
delete_option('aptly_show_recent');
delete_option('aptly_show_trending');
delete_option('aptly_show_new');
delete_option('aptly_opt_in');
?>
