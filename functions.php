<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @param boolean $success
 * @param string $message
 * @param array $data
 * @return array
 */
if(!function_exists('_return')) {
    function _return($success, $message = "", $data = [])
    {
        $data['success'] = $success;
        $data['message'] = $message;

        return $data;
    }
}