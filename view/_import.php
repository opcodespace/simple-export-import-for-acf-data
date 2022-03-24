<div class="card">
    <h2>Import Page/Post</h2>
    <div class="import-form-wrapper">
        <form action="<?= admin_url('/admin-post.php') ?>" method="post" enctype="multipart/form-data">
            <?php    wp_nonce_field('seip_import'); ?>
            <input type="hidden" name="action" value="seip_import">
            <div class="form-group">
                <input type="checkbox" id="import_option_data" checked="0"><label for="import_option_data">Option Data</label>
            </div>
            <div class="block_imports">
               <div class="form-group">
                <label class="label_block">Type</label>
                <select name="post_type">
                    <option value="">Please Select Type</option>
                    <?php foreach(get_post_types([], 'objects') as $post_type):
                        echo "<option value='{$post_type->name}'>{$post_type->label}</option>";
                    endforeach;
                    ?>
                </select>
            </div>  
            <div class="form-group">
                <input type="checkbox" id="bulk_import"><label for="bulk_import">Bulk Import</label>
            </div>
            <div class="bulk_import_block">
                <div class="form-group">
                    <label class="label_block">Post/Page</label>
                    <select name="post_id" id="">

                    </select>
                </div>
                <div class="form-group">
                    <input type="checkbox" id="update_post_page_ttl"><label for="update_post_page_ttl">Update Post/Page Title</label>
                </div> 
                <div class="form-group">
                    <input type="checkbox" id="update_post_page_slug"><label for="update_post_page_slug">Update Post/Page Slug</label>
                </div> 
                <div class="form-group">
                    <label for="file">Upload File</label>
                    <input type="file" name="file" id="file">
                </div>  
            </div>  
        </div>  
        <div>
            <input type="submit" class="button button-primary" value="Import">
        </div>
    </form>
</div>
</div>


<div class="card">
    <h2>Import Options</h2>
    <div class="import-form-wrapper">
        <form action="<?= admin_url('/admin-post.php') ?>" method="post" enctype="multipart/form-data">
            <?php    wp_nonce_field('seip_option_import'); ?>
            <input type="hidden" name="action" value="seip_option_import">
            <div class="form-group">
                <label for="file" class="label_block">Upload File</label>
                <input type="file" name="file" id="file">
            </div>  
            <div>
                <input type="submit" class="button button-primary" value="Import">
            </div>
        </form>
    </div>
</div>