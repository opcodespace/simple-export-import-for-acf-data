<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

    <div class="seip_row">
        <div class="seip_col-md-6 seip_col-lg-6">
            <div class="card">
                <h2>Import Page/Post</h2>
                <div class="import-form-wrapper">
                    <form action="<?php echo esc_url(admin_url('/admin-post.php')) ?>" method="post" enctype="multipart/form-data">
                        <?php wp_nonce_field('seip_import'); ?>
                        <input type="hidden" name="action" value="seip_import">
                        <div class="form-group">
                            <input type="checkbox" id="bulk_import"
                                   name="bulk_import" <?php echo !SeipOpcodespace::isPaid() ? 'disabled' : '' ?>><label
                                    for="bulk_import" class="checkbox_label">Bulk Import</label> <br>
                            <?php echo !SeipOpcodespace::isPaid() ? PAID_TEXT : '(If slug is matched, it will update that post/page. Otherwise, it creates a new post.)' ?>
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
                                            <?php foreach (get_post_types([], 'objects') as $post_type):
                                                ?>
                                                <option value='<?php echo esc_attr($post_type->name) ?>'><?php echo esc_attr($post_type->label) ?></option>
                                            <?php
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
                                            <input type="checkbox" id="update_post_page_ttl" name="update_post_page_ttl"
                                                   checked><label for="update_post_page_ttl">Update Post/Page
                                                Title</label>
                                        </div>
                                        <div class="bulk_import_block">
                                            <input type="checkbox" id="update_post_page_slug"
                                                   name="update_post_page_slug"><label for="update_post_page_slug">Update
                                                Post/Page Slug</label>
                                        </div>

                                    </td>
                                </tr>
                                <tr class="">
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
                <h2>Import Options</h2>
                <div class="import-form-wrapper">
                    <form action="<?php echo esc_url(admin_url('/admin-post.php')) ?>" method="post" enctype="multipart/form-data">
                        <?php wp_nonce_field('seip_option_import'); ?>
                        <input type="hidden" name="action" value="seip_option_import">
                        <table>
                            <tbody>
                            <tr>
                                <td>
                                    <label for="file" class="label_block">Upload File</label>
                                </td>
                                <td>
                                    <input type="file" name="file"
                                           id="file" <?php echo !SeipOpcodespace::isPaid() ? 'disabled' : '' ?>>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="submit" class="button button-primary"
                                           value="Import" <?php echo !SeipOpcodespace::isPaid() ? 'disabled' : '' ?>> <br>
                                    <?php echo !SeipOpcodespace::isPaid() ? PAID_TEXT : '' ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <div class="seip_col-md-4 seip_col-lg-4">
            <?php include '_sidebar.php'; ?>
        </div>
    </div>
