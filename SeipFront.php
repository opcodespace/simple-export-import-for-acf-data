<?php

if ( ! defined( 'ABSPATH' ) ) {exit;}

if(!class_exists('SeipFront')) {
    class SeipFront
    {
        public static function init()
        {
            $self = new self;
            add_action( 'admin_menu', [$self, 'export_import'] );
            add_action('wp_ajax_seip_get_all_posts', [$self, 'seip_get_all_posts']);

        }

        public function export_import()
        {
            add_submenu_page(
                'options-general.php',
                'Simple Export Import',
                'Simple Export Import',
                'manage_options',
                'seip-simple-export-import',
                [$this, 'display_export_import'] );
        }

        public function display_export_import()
        {
            ob_start();
            include_once SEIP_VIEW_PATH . 'export_import.php';
            $html = ob_get_contents();
            ob_end_clean();
            echo $html;
        }

        public function seip_get_all_posts()
        {
            if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'seip_export' ) ) {
                wp_send_json_error(['message' => 'You are not allowed to submit data.']);
            }

            $post_type = sanitize_text_field($_POST['post_type']);

            $posts = get_posts([
                'post_type' => $post_type,
                'numberposts' => - 1
            ]);

            $sorted_posts = [];

            foreach($posts as $post){
                $sorted_posts[] = [
                  'ID' => $post->ID,
                  'post_name' => $post->post_name
                ];
            }

            wp_send_json_success(['posts' => $sorted_posts]);
        }
    }
}