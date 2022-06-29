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
        }

        public function upload()
        {
            if (empty($_FILES['file']['size'])) {
                seip_notices_with_redirect('msg1', __('No file selected', 'simple-export-import-for-acf-data'), 'success');
            }

            $file = $_FILES['file'];

            if ($file['error']) {
                seip_notices_with_redirect('msg1', __('Error uploading file. Please try again', 'simple-export-import-for-acf-data'), 'success');
            }

            if (pathinfo($file['name'], PATHINFO_EXTENSION) !== 'json') {
                seip_notices_with_redirect('msg1', __('Incorrect file type', 'simple-export-import-for-acf-data'), 'success');
            }

            if (function_exists('wp_json_file_decode')) {
                $posts = wp_json_file_decode($file['tmp_name'], ['associative' => true]);
            } else {
                $content = file_get_contents($file['tmp_name']);
                if (empty($content)) {
                    wp_send_json_error(['message' => "File is empty"]);
                }
                $posts = json_decode($content, 1);
            }

            if (!$posts || !is_array($posts)) {
                seip_notices_with_redirect('msg1', __('Import file empty', 'simple-export-import-for-acf-data'), 'success');
            }

            return $posts;
        }

        public function seip_import()
        {
            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(
                $_POST['_wpnonce'],
                'seip_import'
            ) || (!current_user_can('administrator') && !current_user_can('editor'))) {
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

            $posts = $this->upload();

            foreach ($posts as $post) {
                $this->post_data($post, $settings);
            }

            seip_notices_with_redirect('msg1', 'Successfully imported', 'success');
        }

        public function seip_import_options()
        {
            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(
                $_POST['_wpnonce'],
                'seip_option_import'
            ) || (!current_user_can('administrator') && !current_user_can('editor'))) {
                wp_send_json_error(['message' => 'You are not allowed to submit data.']);
            }

            $data = $this->upload();

            foreach ($data['options'] as $key => $value) {
                update_field($key, $value, 'option');
            }

            seip_notices_with_redirect('msg1', 'Successfully imported', 'success');
        }

        private function post_data($data, $settings)
        {

            $post_data = [
                'post_content' => wp_kses_post($data['post_content']),
                'post_status'  => sanitize_text_field($data['post_status']),
                'post_excerpt' => sanitize_textarea_field($data['post_excerpt']),
                'post_password' => sanitize_text_field($data['post_password']),
            ];

            if ($settings['update_post_page_ttl']) {
                $post_data['post_title'] = sanitize_text_field($data['post_title']);
            }
            if ($settings['update_post_page_slug']) {
                $post_data['post_name'] = sanitize_title($data['post_name']);
            }

            if ($settings['bulk_import']) {

                if (!SeipOpcodespace::isPaid()) {
                    wp_send_json_error(['message' => 'You are using free plugin. Please upgrade to access this feature.']);
                }

                $post = get_posts([
                    'name' => sanitize_title($data['post_name']),
                    'post_type' => sanitize_text_field($settings['post_type'])
                ]);

                if (empty($post)) {
                    $primary_data = [
                        'post_content' => wp_kses_post($data['post_content']),
                        'post_title'   => sanitize_text_field($data['post_title']),
                        'post_status'  => sanitize_text_field($data['post_status']),
                        'post_excerpt' => sanitize_textarea_field($data['post_excerpt']),
                        'post_password' => sanitize_text_field($data['post_password']),
                    ];

                    $post_id = wp_insert_post($primary_data);
                } else {
                    $post_id = $post[0]->ID;
                    $post_data['ID'] = $post_id;
                    wp_update_post($post_data);
                }
            } else {
                $post_id = (int)$settings['single_post_id'];
                $post_data['ID'] = $post_id;

                wp_update_post(
                    $post_data
                );
            }

            $this->post_metas = $data['metas'];

            foreach ($data['metas'] as $key => $value) {
                update_post_meta($post_id, $key, $this->get_field_value($key, $value));
            }

            # Adding Featured image
            $featured_image = sanitize_url($data['featured_image']);
            if (!empty($featured_image)) {
                $upload = $this->download($featured_image);
                $this->set_featured_image($post_id, $upload);
            }
        }

        /**
         * @param $key
         * @param $value
         * @return false|mixed|string
         */
        public function get_field_value($key, $value)
        {
            if (!function_exists('get_field_object')) {
                return $value;
            }

            if (!isset($this->post_metas['_' . $key]) || empty($this->post_metas['_' . $key])) {
                return $value;
            }

            $related_field = get_field_object($this->post_metas['_' . $key]);

            if (!$related_field) {
                return $value;
            }

            if (!SeipOpcodespace::isPaid()) {
                return $value;
            }

            if ($related_field['type'] === 'image') {
                $upload = $this->download($value);
                return $this->attach($upload);
            }

            if ($related_field['type'] === 'checkbox') {
                return maybe_unserialize($value);
            }

            if ($related_field['type'] === 'gallery') {
                $images = maybe_unserialize($value);

                $new_images = [];
                foreach ($images as $image) {
                    $upload = $this->download($image);
                    $new_images[] = $this->attach($upload);
                }

                return $new_images;
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

        /**
         * @param $upload
         * @param $post
         * @return int|WP_Error
         */
        protected function set_featured_image($post, $upload)
        {
            $attachment_id = $this->attach($upload);

            if (is_wp_error($attachment_id)) {
                return $attachment_id;
            }

            return set_post_thumbnail($post, $attachment_id);
        }
    }
}
