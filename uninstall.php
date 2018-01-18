<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

delete_option('co_footer_textarea_field');
delete_site_option('co_footer_textarea_field');

delete_option('co_facebook_url_field');
delete_site_option('co_facebook_url_field');

delete_option('co_twitter_url_field');
delete_site_option('co_twitter_url_field');

delete_option('co_instagram_url_field');
delete_site_option('co_instagram_url_field');

delete_option('co_youtube_url_field');
delete_site_option('co_youtube_url_field');



