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

            $uploaded_file = $this->upload();

            $header = $this->get_header($uploaded_file);

            update_option('seip_import_csv', [
                'csv_file'  => $uploaded_file,
                'header'    => $header,
                'post_type' => $post_type
            ]);

            seip_notices_with_redirect('msg1',
                __('Successfully CSV file uploaded. Please map the ACF fields with CSV column properly.',
                    'simple-export-import-for-acf-data'),
                'success');

        }
    }


}
