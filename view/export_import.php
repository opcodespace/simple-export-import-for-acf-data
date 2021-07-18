<style>
    .submin_btn_wrapper {
        text-align: center;
        margin-top: 20px;
    }
    select{
        min-width: 190px;
    }
</style>
<div class="wrap">
    <h1>Simple Export Import</h1>
    <div class="card">
        <h2>Export Page/Post</h2>
        <div class="export-form-wrapper">
            <form action="<?= admin_url('/admin-post.php') ?>" method="post">
                <?php    wp_nonce_field('seip_export'); ?>
                <input type="hidden" name="action" value="seip_export">
                <table>
                    <tbody>
                        <tr>
                            <td><label for="">Type</label></td>
                            <td>: <select name="post_type">
                                <option value="">Please Select Type</option>
                                <?php foreach(get_post_types([], 'objects') as $post_type):
                                    echo "<option value='{$post_type->name}'>{$post_type->label}</option>";
                                endforeach;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="">Post/Page</label></td>
                        <td>: <select name="post_id" id="">

                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="submin_btn_wrapper">
            <input type="submit" class="button button-primary" value="Export">
        </div>
    </form>
</div>
</div>

<div class="card">
    <h2>Import Page/Post</h2>
    <div class="import-form-wrapper">
        <form action="<?= admin_url('/admin-post.php') ?>" method="post" enctype="multipart/form-data">
            <?php    wp_nonce_field('seip_import'); ?>
            <input type="hidden" name="action" value="seip_import">
            <table>
                <tbody>
                    <tr>
                        <td>Override existing page/post </td>
                        <td>: 
                            <input type="checkbox">
                            <label for="">Yes</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="">Type</label>
                        </td>
                        <td>: <select name="post_type">
                            <option value="">Please Select Type</option>
                            <?php foreach(get_post_types([], 'objects') as $post_type):
                                echo "<option value='{$post_type->name}'>{$post_type->label}</option>";
                            endforeach;
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="">Post/Page</label></td>
                    <td>: <select name="post_id" id="">

                    </select></td>
                </tr>
                <tr>
                    <td><label for="file">Upload File</label></td>
                    <td>: <input type="file" name="file" id="file"></td>
                </tr>
            </tbody>
        </table>
        <div class="submin_btn_wrapper">
            <input type="submit" class="button button-primary" value="Export">
        </div>
    </form>
</div>
</div>
</div>

<script>
    jQuery(function ($){
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