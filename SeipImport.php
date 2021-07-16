<?php

if ( ! defined( 'ABSPATH' ) ) {exit;}
if(!class_exists('SeipImport')) {
    class SeipImport
    {
        private $post_metas;
        public static function init(){
            $self = new self;
            add_action('admin_post_seip_import', [$self, 'seip_import']);
            add_filter('upload_mimes', [$self, 'mime_types']);
        }

        public function seip_import()
        {
            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'seip_import')) {
                wp_send_json_error(['message' => 'You are not allowed to submit data.']);
            }

            $post_id    = (int) $_POST['post_id'];

            if ( ! function_exists( 'wp_handle_upload' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }

            $uploadedfile = $_FILES['file'];

            $upload_overrides = array(
                'test_form' => false
            );

            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

            if ( !$movefile || isset( $movefile['error'] ) ) {
                wp_send_json_success(['message' => $movefile['error']]);
            }

            if($movefile['type'] !== 'application/json'){
                wp_send_json_error(['message' => "This file is not supported"]);
            }

            $content = file_get_contents($movefile['file']);

            if(empty($content)){
                wp_send_json_error(['message' => "File is empty"]);
            }

            $data = json_decode($content, 1);

            wp_update_post(
                [
                    'ID' => $post_id,
                    'post_content' => $data['post_content'],
                    'post_title' => $data['post_title']
                ]
            );

            $this->post_metas = $data['metas'];

            foreach ($data['metas'] as $key => $value) {
                update_post_meta($post_id, $key, $this->get_field_value($key, $value));
            }

            wp_delete_file($movefile['file']);
        }

        public function mime_types($mimes)
        {
            $mimes['json'] = 'application/json';
            return $mimes;
        }

        /**
         * @param $key
         * @param $value
         * @return false|mixed|string
         */
        public function get_field_value($key, $value)
        {
            $field = get_field_object($value);

            if ($field) {
                return $value;
            }

            if (!isset($this->post_metas['_'.$key]) || empty($this->post_metas['_'.$key])) {
                return $value;
            }

            $related_field = get_field_object($this->post_metas['_'.$key]);

            if (!$related_field) {
                return $value;
            }

            if ($related_field['type'] === 'image') {
                $upload = $this->download($value);
                return $this->attach($upload);
            }

            return $value;
        }

        /**
         * @param $value
         * @return array|false
         */
        public function download($value)
        {

            $response = wp_remote_get(
                $value,
                array(
                    'timeout'  => 300,
                    'filename' => basename($value)
                )
            );

            $response_code = wp_remote_retrieve_response_code($response);
            $content = wp_remote_retrieve_body($response);

            if($response_code != 200){
                return false;
            }

            $upload = wp_upload_bits(basename($value), null, $content);
            if (!empty($upload['error'])) {
                return false;
            }

            return $upload;
        }

        /**
         * @param $upload
         * @return int|WP_Error
         */
        public function attach($upload)
        {
            $attachment = array(
                'post_mime_type' => $upload['type'],
                'guid'           => $upload['url'],
                'post_title'     => sanitize_title(basename($upload['file'])),
                'post_content'   => '',
            );
            return wp_insert_attachment($attachment, $upload['file']);
        }

    }
}