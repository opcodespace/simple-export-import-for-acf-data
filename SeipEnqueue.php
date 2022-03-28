<?php


class SeipEnqueue
{

    public static function init()
    {
        $self = new self;
        add_action('admin_enqueue_scripts', array($self, 'enqueue_admin_script' ));
    }

    public function enqueue_admin_script()
    {
        if($_GET['page'] === 'seip-simple-export-import'){
            wp_enqueue_style('chosen-style', SEIP_ASSETSURL . "add-on/chosen-js/chosen.min.css");
            // wp_enqueue_style('font-awesome', SEIP_ASSETSURL . "add-on/font-awesome/css/font-awesome.min.css");
            wp_enqueue_style('bootstrap-css', SEIP_ASSETSURL . "add-on/bootstrap/bootstrap-wrapper.css", false, '1.0.0');
            wp_enqueue_style('multiselect-css', SEIP_ASSETSURL . "add-on/multiselect/bootstrap-multiselect.min.css", false, '1.0.0');

            wp_enqueue_style('main-style', SEIP_ASSETSURL . "add-on/style.css", array(), time());


            wp_enqueue_script('chosen-script', SEIP_ASSETSURL . "add-on/bootstrap/bootstrap.bundle.js", array(), '1.0.0', true);
            wp_enqueue_script('multiselect-js', SEIP_ASSETSURL . "add-on/multiselect/bootstrap-multiselect.min.js", array(), '1.0.0', true);
            wp_enqueue_script('bootstrap-js', SEIP_ASSETSURL . "add-on/chosen-js/chosen.jquery.min.js", array(), '1.0.0', true);
            wp_enqueue_script('admin-script', SEIP_ASSETSURL . "add-on/admin-script.js", array(), time(), true);
        }

        // wp_localize_script(
        //     'admin-script',
        //     'frontend_form_object',
        //     array(
        //         'ajaxurl' => admin_url('admin-ajax.php')
        //     )
        // );
    }
}
