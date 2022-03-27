<?php

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('SeipImport')) {
    class SeipImport
    {
        private $post_metas;

        public static function init()
        {
            $self = new self;
            add_action('admin_post_seip_import', [$self, 'seip_import']);
            add_action('admin_post_seip_option_import', [$self, 'seip_import_options']);
            add_filter('upload_mimes', [$self, 'mime_types']);
        }

        public function seip_import()
        {
            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(
                $_POST['_wpnonce'],
                'seip_import'
            ) || !current_user_can('administrator')) {
                wp_send_json_error(['message' => 'You are not allowed to submit data.']);
            }

            $post_id = (int) $_POST['post_id'];
            $settings = [
                'bulk_import' => isset($_POST['bulk_import']),
                'update_post_page_ttl'  => isset($_POST['update_post_page_ttl']),
                'update_post_page_slug' => isset($_POST['update_post_page_slug']),
                'single_post_id' => $post_id,
                'post_type' => sanitize_text_field($_POST['post_type'])
            ];

            if (!function_exists('wp_handle_upload')) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
            }

            $uploadedfile = $_FILES['file'];

            $upload_overrides = array(
                'test_form' => false
            );

            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

            if (!$movefile || isset($movefile['error'])) {
                wp_send_json_success(['message' => $movefile['error']]);
            }

            // if ($movefile['type'] !== 'application/json') {
            //     wp_send_json_error(['message' => "This file is not supported"]);
            // }

            $content = wp_json_file_decode($movefile['file'], ['associative' => true]);

            if (empty($posts)) {
                wp_send_json_error(['message' => "File is empty"]);
            }

            foreach ($posts as $post) {
                $this->post_data($post, $settings);
            }

            wp_delete_file($movefile['file']);
        }

        private function post_data($data, $settings)
        {

            $post_data = [
                'post_content' => $data['post_content'],
            ];

            if($settings['update_post_page_ttl']){
                $post_data['post_title'] = $data['post_title'];
            }
            if($settings['update_post_page_slug']){
                $post_data['post_name'] = $data['post_name'];
            }

            if ($settings['bulk_import']) {
                $post = get_posts([
                    'name' => $data['post_name'],
                    'post_type' => $settings['post_type']
                ]);

                if(empty($post)){
                    $post_id = wp_insert_post( [
                        'post_content' => $data['post_content'],
                        'post_title'   => $data['post_title'],
                    ] );
                }
                else{
                    $post_id = $post[0]->ID;
                    $post_data['ID'] = $post_id;
                    wp_update_post($post_data);
                }
            }
            else{
                $post_id = $settings['single_post_id'];
                $post_data['ID'] = $post_id;

                wp_update_post(
                    $post_data
                );
            }

            $this->post_metas = $data['metas'];

            foreach ($data['metas'] as $key => $value) {
                update_post_meta($post_id, $key, $this->get_field_value($key, $value));
            }
        }

        public function seip_import_options()
        {
            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(
                $_POST['_wpnonce'],
                'seip_option_import'
            ) || !current_user_can('administrator')) {
                wp_send_json_error(['message' => 'You are not allowed to submit data.']);
            }

            if (!function_exists('wp_handle_upload')) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
            }

            $uploadedfile = $_FILES['file'];

            $upload_overrides = array(
                'test_form' => false
            );

            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

            if (!$movefile || isset($movefile['error'])) {
                wp_send_json_success(['message' => $movefile['error']]);
            }

            // if ($movefile['type'] !== 'application/json') {
            //     wp_send_json_error(['message' => "This file is not supported"]);
            // }

            $content = file_get_contents($movefile['file']);

            if (empty($content)) {
                wp_send_json_error(['message' => "File is empty"]);
            }

            $data = json_decode($content, 1);
            print_r($data);


            foreach ($data['options'] as $key => $value) {
                update_field($key, $value, 'option');
            }

            wp_delete_file($movefile['file']);

            // wp_redirect($_POST['_wp_http_referer']);
            // exit();
        }

        public function mime_types($mimes)
        {
            // $mimes['json'] = 'application/json';
            $mimes['json'] = 'text/plain';
            return $mimes;
        }

        /**
         * @param $key
         * @param $value
         * @return false|mixed|string
         */
        public function get_field_value($key, $value)
        {
            if(!function_exists('get_field_object')){
                return $value;
            }

            $field = get_field_object($value);

            if ($field) {
                return $value;
            }

            if (!isset($this->post_metas['_' . $key]) || empty($this->post_metas['_' . $key])) {
                return $value;
            }

            $related_field = get_field_object($this->post_metas['_' . $key]);

            if (!$related_field) {
                return $value;
            }

            if ($related_field['type'] === 'image') {
                $upload = $this->download($value);
                return $this->attach($upload);
            }

            if ($related_field['type'] === 'checkbox') {
                return maybe_unserialize($value);
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
            $content       = wp_remote_retrieve_body($response);

            if ($response_code != 200) {
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
