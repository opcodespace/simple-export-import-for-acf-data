<div class="card">
    <h4>Import Page/Post</h4>
    <div class="import-form-wrapper">
        <form action="<?= admin_url('/admin-post.php') ?>" method="post" enctype="multipart/form-data">
            <?php    wp_nonce_field('seip_import'); ?>
            <input type="hidden" name="action" value="seip_import">
            <div class="form-group">
                <input type="checkbox" id="bulk_import"><label for="bulk_import">Bulk Import</label>
            </div>
            <div class="block_imports">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <label class="label_block">Type</label>
                            </td>
                            <td>
                                <select name="post_type" class="chosen-select">
                                    <option value="">Please Select Type</option>
                                    <?php foreach(get_post_types([], 'objects') as $post_type):
                                        echo "<option value='{$post_type->name}'>{$post_type->label}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr class="bulk_import_block">
                            <td>
                                <label class="label_block">Post/Page</label>
                            </td>
                            <td>
                                <select name="post_id" id="" class="chosen-select">

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <div>
                                    <input type="checkbox" id="update_post_page_ttl"><label for="update_post_page_ttl">Update Post/Page Title</label>
                                </div>
                                <div class="bulk_import_block">
                                    <input type="checkbox" id="update_post_page_slug"><label for="update_post_page_slug">Update Post/Page Slug</label>
                                </div>

                            </td>
                        </tr>
                        <tr class="bulk_import_block">
                            <td><label for="file">Upload File</label></td>
                            <td>
                                <input type="file" name="file" id="file">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table>
                <tr>
                    <td></td>
                    <td>
                       <input type="submit" class="button button-primary" value="Import">
                   </td>
               </tr>
           </table>
       </form>
   </div>
</div>


<div class="card">
    <h4>Import Options</h4>
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