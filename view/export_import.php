<style>
<?php include'bootstrap-multiselect.min.css'?>
<?php include'bootstrap.min.css'?>
<?php include'style.css'?>
</style>



<div class="export_import_wrapper">
    <?php $tab = sanitize_text_field( $_GET['tab'] ) ?>
    <nav  class="nav-tab-wrapper">
        <a class="nav-tab <?= $tab === 'export' ? 'nav-tab-active' : ''  ?>" href="<?= admin_url( 'options-general.php?page=seip-simple-export-import&tab=export' ) ?>">Export</a>
        <a class="nav-tab <?= $tab === 'import' ? 'nav-tab-active' : ''  ?>" href="<?= admin_url( 'options-general.php?page=seip-simple-export-import&tab=import' ) ?>">Import</a>
    </nav>
    <div class="tap-contet-wrapper">
        <?php 
        switch($tab){
            case 'import':
            $path = '_import.php';
            break;
            default:
            $path = '_export.php';
            break;
        }
        include $path;
        ?>
    </div>  

</div>

<script>
    <?php include'bootstrap-multiselect.min.js' ?>
    <?php include'bootstrap.bundle.js' ?>
</script>
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

         $("#bulk_export").click(function() {
            if($('#bulk_export').is(':checked')) { 
                $('.bulk_export_block').slideUp();
            } else {
                $('.bulk_export_block').slideDown();
            }
        });

         $("#bulk_import").click(function() {
            if($('#bulk_import').is(':checked')) { 
                $('.bulk_import_block').slideUp();
            } else {
                $('.bulk_import_block').slideDown();
            }
        });


        $('#export_mulit_pages').multiselect();

        $('[name="post_type"]').change(function (){
            let _this_parent = $(this).parents('form');
            $.ajax({
              method: "POST",
              url: "<?=admin_url('/admin-ajax.php'); ?>",
              data: { action: "seip_get_all_posts", post_type: $(this).val(), _wpnonce: $('#_wpnonce').val()}
            })
              .done(function( response ) {
                if(response.success){
                    let options = '';

                    response.data.posts.map(post => {
                        options += `<option value="${post.ID}">${post.post_name}</option>`;
                    })

                    _this_parent.find('[name="post_id"]').html(options);
                }
              }, _this_parent);
        })
    })
</script>