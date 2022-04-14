<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
?>
<style>
    .chosen-container-single .chosen-single {
        height: 30px;
        border: 1px solid #c3c4c7;
        border-radius: 4px;
        background: transparent;
        box-shadow: none;
        line-height: 24px;
    }
    .submin_btn_wrapper {
        text-align: center;
        margin-top: 20px;
    }
    select{
        min-width: 190px;
    }
    td{
        min-width: 166px;
    }
    tr{
        margin-bottom: 10px;
        display: block;
    }
    .export_import_wrapper{
        margin-top: 30px;
    }
    /* Tablet Layout: 768px. */
    @media only screen and (min-width: 768px) and (max-width: 991px) {
        select {
            min-width: 210px;
        }
    }
    /* Mobile Layout: 320px. */
    @media only screen and (max-width: 767px) {
        td {
            min-width: 100%;
            display: block;
        }
        select {
            min-width: 100%;
            display: block;
        }

    }
</style>
<div class="bootstrap-wrapper">
    <div class="export_import_wrapper">
        <h5 class="main_title">Simple Export Import for ACF Data</h5>
        <?php $tab = sanitize_text_field( $_GET['tab'] ) ?>
        <nav  class="nav-tab-wrapper">
            <a class="nav-tab <?php echo $tab === 'export' || empty($tab) ? 'nav-tab-active' : ''  ?>" href="<?php echo esc_url(admin_url( 'options-general.php?page=seip-simple-export-import&tab=export' )) ?>">Export</a>
            <a class="nav-tab <?php echo $tab === 'import' ? 'nav-tab-active' : ''  ?>" href="<?php echo esc_url(admin_url( 'options-general.php?page=seip-simple-export-import&tab=import' )) ?>">Import</a>
            <a class="nav-tab <?php echo $tab === 'license' ? 'nav-tab-active' : ''  ?>" href="<?php echo  esc_url(admin_url( 'options-general.php?page=seip-simple-export-import&tab=license' )) ?>">License</a>
        </nav>
        <div class="tap-contet-wrapper">
            <?php
            switch($tab){
                case 'import':
                $path = '_import.php';
                break;
                case 'license':
                $path = '_license.php';
                break;
                default:
                $path = '_export.php';
                break;
            }
            include $path;
            ?>
        </div>

    </div>
</div>

<script>
    jQuery(function ($){

     $("#export_option_data").click(function() {
        if($('#export_option_data').is(':checked')) {
            $('.block_exports').slideUp();
        } else {
            $('.block_exports').slideDown();
        }
    });
     $("#import_option_data").click(function() {
        if($('#import_option_data').is(':checked')) {
            $('.block_imports').slideUp();
        } else {
            $('.block_imports').slideDown();
        }
    });

     $('.bulk_export_visible').slideUp();
     $("#bulk_export").click(function() {
        if($('#bulk_export').is(':checked')) {
            $('.bulk_export_block').slideUp();
            $('.bulk_export_visible').slideDown();
        } else {
            $('.bulk_export_block').slideDown();
            $('.bulk_export_visible').slideUp();
        }
    });

     $("#bulk_import").click(function() {
        if($('#bulk_import').is(':checked')) {
            $('.bulk_import_block').slideUp();
        } else {
            $('.bulk_import_block').slideDown();
        }
    });
     $('#export_mulit_pages').multiselect({
        includeSelectAllOption: true,
        buttonTextAlignment: 'left',
        maxHeight: 200,
        enableFiltering: true
    });

     $('[name="post_type"]').change(function (){
        let _this_parent = $(this).parents('form');
        $.ajax({
          method: "POST",
          url: "<?php echo esc_url(admin_url('/admin-ajax.php')); ?>",
          data: { action: "seip_get_all_posts", post_type: $(this).val(), _wpnonce: $('#_wpnonce').val()}
      })
        .done(function( response ) {
            if(response.success){
                let options = '';

                response.data.posts.map(post => {
                    options += `<option value="${post.ID}">${post.post_name}</option>`;
                })

                _this_parent.find('[name="post_id"]').html(options).trigger("chosen:updated");
                $('#export_mulit_pages').html(options);

                $('#export_mulit_pages').multiselect('destroy').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true
                }).change();
            }
        }, _this_parent);
    })
 })
</script>