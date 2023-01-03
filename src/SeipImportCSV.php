<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('SeipImportCSV')) {
    class SeipImportCSV
    {
        public static function init()
        {
            $self = new self;
            add_action('admin_post_seip_import_csv', [$self, 'seip_import_csv']);
        }

        private function upload()
        {
            if (empty($_FILES['file']['size'])) {
                seip_notices_with_redirect('msg1', __('No file selected', 'simple-export-import-for-acf-data'),
                    'error');
            }

            $file = $_FILES['file'];

            if ($file['error']) {
                seip_notices_with_redirect('msg1',
                    __('Error uploading file. Please try again', 'simple-export-import-for-acf-data'), 'error');
            }

            if (pathinfo($file['name'], PATHINFO_EXTENSION) !== 'csv') {
                seip_notices_with_redirect('msg1', __('Incorrect file type', 'simple-export-import-for-acf-data'),
                    'error');
            }

            $content = file_get_contents($file['tmp_name']);
            if (empty($content)) {
                seip_notices_with_redirect('msg1', __('File is empty', 'simple-export-import-for-acf-data'),
                    'error');
            }

            return $file['tmp_name'];

        }

        private function get_header($csv_file)
        {
            $row = 0;
            if (($handle = fopen($csv_file, 'rb')) !== false) {
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    $row++;
                    if ($row === 1) {
                        return $data;
                    }
                }
            }

            return false;
        }

        private function valid_location($locations, $post_type)
        {
            if (empty($locations)) {
                return false;
            }

            foreach ($locations as $location) {
                foreach ($location as $condition) {
                    if ($condition['param'] === 'post_type' && $condition['value'] === $post_type) {
                        return true;
                    }
                }
            }

            return false;
        }


        private function get_acf_fields($post_type)
        {
            $sorted_fields = [];

            $field_groups = acf_get_field_groups();
            foreach ($field_groups as $group) {
                if (!$this->valid_location($group['location'], $post_type)) {
                    continue;
                }

                $fields = $this->child_fields($group['ID']);

                foreach ($fields as $field) {
                    $sorted_fields[]     = $field;
                }
            }

            return $sorted_fields;
        }

        private function child_fields($field_key)
        {
            return acf_get_fields($field_key);
//           return get_posts(array(
//                'posts_per_page'         => -1,
//                'post_type'              => 'acf-field',
//                'orderby'                => 'menu_order',
//                'order'                  => 'ASC',
//                'suppress_filters'       => true, // DO NOT allow WPML to modify the query
//                'post_parent'            => $parent_id,
//                'post_status'            => 'any',
//                'update_post_meta_cache' => false
//            ));
        }

        private function get_default_fields()
        {
            return [
                [
                    'post_title'   => 'Post Date',
                    'post_excerpt' => 'post_date',
                    'field_type'   => 'default',
                    'note' => 'Date format: Y-m-d H:i:s (i.e 2023-01-01 10:34:56) <br>If you don\'t follow this format, it will set current date.'
                ],
                [
                    'post_title'   => 'Post Title',
                    'post_excerpt' => 'post_title',
                    'field_type'   => 'default',
                ],
                [
                    'post_title'   => 'Post Name (slug)',
                    'post_excerpt' => 'post_name',
                    'field_type'   => 'default',
                    'note' => 'It is post slug. It will create slug automatically, if you don\'t have post slug.'
                ],
                [
                    'post_title'   => 'Post Name',
                    'post_excerpt' => 'post_name',
                    'field_type'   => 'default',
                ],
                [
                    'post_title'   => 'Post Content',
                    'post_excerpt' => 'post_content',
                    'field_type'   => 'default',
                ],
                [
                    'post_title'   => 'Post Status',
                    'post_excerpt' => 'post_status',
                    'field_type'   => 'default',
                    'note' => 'Default value is published.'
                ],
                [
                    'post_title'   => 'Post Excerpt',
                    'post_excerpt' => 'post_excerpt',
                    'field_type'   => 'default',
                ],
                [
                    'post_title'   => 'Post Password',
                    'post_excerpt' => 'post_password',
                    'field_type'   => 'default',
                ],
                [
                    'post_title'   => 'Featured Image',
                    'post_excerpt' => 'featured_image',
                    'field_type'   => 'default',
                ],
                //                [
                //                    'post_title' => 'Terms',
                //                    'post_excerpt' => 'post_title',
                //                    'field_type' => 'default',
                //                ]
            ];
        }

        private function get_fields($post_type)
        {
            return [
                'meta'    => $this->get_acf_fields($post_type),
                'default' => $this->get_default_fields()
            ];
        }


        public function seip_import_csv()
        {
            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(
                    $_POST['_wpnonce'],
                    'seip_import_csv'
                ) || (!current_user_can('administrator') && !current_user_can('editor'))) {
                wp_send_json_error([
                    'message' => __('You are not allowed to submit data.', 'simple-export-import-for-acf-data')
                ]);
            }

            $post_type = sanitize_title($_POST['post_type']);

            if(empty($post_type)){
                seip_notices_with_redirect('msg1',
                    __('Please select post type.', 'simple-export-import-for-acf-data'), 'error');
            }

            $uploaded_file = $this->upload();

            $header = $this->get_header($uploaded_file);

            update_option('seip_import_csv', [
                'csv_file'  => $uploaded_file,
                'header'    => $header,
                'post_type' => $post_type,
                'fields'    => $this->get_fields($post_type)
            ]);

            seip_notices_with_redirect('msg1',
                __('Successfully CSV file uploaded. Please map the ACF fields with CSV column properly.',
                    'simple-export-import-for-acf-data'),
                'success');

        }

    }
}
