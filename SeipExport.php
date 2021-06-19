<?php

if ( ! defined( 'ABSPATH' ) ) {exit;}
if(!class_exists('SeipExport')) {
    class SeipExport
    {
        public static function init()
        {
            $self = new self;
            add_action('admin_post_seip_export', [$self, 'seip_export']);
        }

        public function seip_export()
        {
            if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'seip_export' ) ) {
                wp_send_json_error(['message' => 'You are not allowed to submit data.']);
            }

            $post_id = (int) $_POST['post_id'];

            $post_metas = get_post_meta($post_id);
            echo "<pre>"; print_r($post_metas); echo "</pre>";

            echo "<pre>"; print_r(get_field_object($post_metas['_image'][0])); echo "</pre>";
        }
    }
}