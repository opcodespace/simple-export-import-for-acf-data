<div class="wrap">
    <h1>Simple Export Import</h1>
    <div class="card">
        <h2>Export Page/Post</h2>
        <div class="export-form-wrapper">
            <form action="<?= admin_url('/admin-post.php') ?>" method="post">
                <?php    wp_nonce_field('seip_export'); ?>
                <input type="hidden" name="action" value="seip_export">
                <p>
                    <label for="">Type</label>
                    <select name="post_type">
                        <option value="">Please Select Type</option>
                        <?php foreach(get_post_types([], 'objects') as $post_type):
                        echo "<option value='{$post_type->name}'>{$post_type->label}</option>";
                        endforeach;
                        ?>
                    </select>
                </p>
                <p>
                    <label for="">Post/Page</label>
                    <select name="post_id" id="">

                    </select>
                </p>
                <div>
                    <input type="submit" class="button button-primary" value="Export">
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <h2>Import Page/Post</h2>
    </div>
</div>

<script>
    jQuery(function ($){
        $('[name="post_type"]').change(function (){
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

                    $('[name="post_id"]').html(options);
                }
              });
        })
    })
</script>