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
            add_action('admin_post_seip_option_export', [$self, 'seip_export_options']);
        }

        public function seip_export()
        {

            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'],
                    'seip_export') || !current_user_can('administrator')) {
                wp_send_json_error(['message' => 'You are not allowed to submit data.']);
            }

            $post_data = [];
            if(isset($_POST['bulk_export'])){
                if(!SeipOpcodespace::isPaid()){
                    wp_send_json_error(['message' => 'You are using free plugin. Please upgrade to access this feature.']);
                }

                foreach ($_POST['post_ids'] as $post_id) {
                    $post_data[] = $this->post_data($post_id);
                }
            }
            else{
                $post_id    = (int) $_POST['post_id'];
                $post_data[] = $this->post_data($post_id);
            }

            $data = json_encode($post_data);

            $json_file_name = 'post-export-'.date('y-m-d').'.json';

            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename='.$json_file_name);
            header('Expires: 0'); //No caching allowed
            header('Cache-Control: must-revalidate');
            header('Content-Length: '.strlen($data));
            file_put_contents('php://output', $data);

        }

        private function post_data($post_id)
        {
            $post       = get_post($post_id);
            $post_metas = get_post_meta($post_id);

            $this->post_metas = $post_metas;

            $sorted_metas = [];

            foreach ($post_metas as $key => $value) {
                $sorted_metas[$key] = $this->get_field_value($key, $value[0]);
            }

            return [
                'post_title'   => $post->post_title,
                'post_name'    => $post->post_name,
                'post_content' => $post->post_content,
                'metas'        => (array) $sorted_metas
            ];
        }

        public function seip_export_options()
        {

            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'],
                    'seip_option_export') || !current_user_can('administrator')) {
                wp_send_json_error(['message' => 'You are not allowed to submit data.']);
            }

            if(!SeipOpcodespace::isPaid()){
                wp_send_json_error(['message' => 'You are using free plugin. Please upgrade to access this feature.']);
            }

            $options = get_fields('options');

            $sorted_metas = [];

            foreach ($options as $key => $value) {
                // Need work here
                $field = get_field_object(get_option('_options_' .$key));

                if(empty($field)){
                    $sorted_metas[$key] = $value;
                    continue;
                }

                $sorted_metas[$key] = $this->get_value_based_on_field_type($field, $value);
            }

            $data = [
                'type'   => 'options',
                'options'        => (array) $sorted_metas
            ];

            $data = json_encode($data);

            $json_file_name = 'Options-'.date('y-m-d').'.json';

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
            if(!function_exists('get_field_object')){
                return $value;
            }

            if (!isset($this->post_metas['_'.$key][0]) || empty($this->post_metas['_'.$key][0])) {
                return $value;
            }

            $related_field = get_field_object($this->post_metas['_'.$key][0]);


            if (!$related_field) {
                return $value;
            }

            return $this->get_value_based_on_field_type($related_field, $value);
        }

        private function get_value_based_on_field_type($field, $value)
        {
            if(!SeipOpcodespace::isPaid()){
                $value;
            }

            if ($field['type'] === 'image') {
                return $this->get_image_link($value);
            }

            if ($field['type'] === 'gallery') {
                $image_links = [];
                foreach(maybe_unserialize( $value ) as $attach_id){
                    $image_links[] = $this->get_image_link($attach_id);
                }
                return maybe_serialize( $image_links );
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