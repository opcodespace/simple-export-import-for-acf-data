<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('SeipExport')) {
    class SeipExport
    {
        private $post_metas;

        public static function init()
        {
            $self = new self;
            add_action('admin_post_seip_export', [$self, 'seip_export']);
        }

        public function seip_export()
        {

            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'],
                    'seip_export') || !current_user_can('administrator')) {
                wp_send_json_error(['message' => 'You are not allowed to submit data.']);
            }

            $post_id    = (int) $_POST['post_id'];
            $post       = get_post($post_id);
            $post_metas = get_post_meta($post_id);

            $this->post_metas = $post_metas;

            $sorted_metas = [];

            foreach ($post_metas as $key => $value) {
                $sorted_metas[$key] = $this->get_field_value($key, $value[0]);
            }

            $data = [
                'post_title'   => $post->post_title,
                'post_content' => $post->post_content,
                'metas'        => (array) $sorted_metas
            ];

            $data = json_encode($data);

            $json_file_name = $post->post_name.'-'.date('y-m-d').'.json';

            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename='.$json_file_name);
            header('Expires: 0'); //No caching allowed
            header('Cache-Control: must-revalidate');
            header('Content-Length: '.strlen($data));
            file_put_contents('php://output', $data);

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

            if (!isset($this->post_metas['_'.$key][0]) || empty($this->post_metas['_'.$key][0])) {
                return $value;
            }

            $related_field = get_field_object($this->post_metas['_'.$key][0]);

            if (!$related_field) {
                return $value;
            }

            if ($related_field['type'] === 'image') {
                return $this->get_image_link($value);
            }

            return $value;
        }


        /**
         * @param $acf_field_value
         * @return false|string
         */
        public function get_image_link($acf_field_value)
        {
            if (empty($acf_field_value)) {
                return '';
            }

            return wp_get_attachment_url($acf_field_value);
        }


    }
}