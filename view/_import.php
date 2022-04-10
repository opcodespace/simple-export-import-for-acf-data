<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
?>
<div class="bootstrap-wrapper">
    <div class="error notice">
        <p><strong>Please keep your site backup before importing data.</strong></p>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <h4>Import Page/Post</h4>
                <div class="import-form-wrapper">
                    <form action="<?= admin_url('/admin-post.php') ?>" method="post" enctype="multipart/form-data">
                        <?php    wp_nonce_field('seip_import'); ?>
                        <input type="hidden" name="action" value="seip_import">
                        <div class="form-group">
                            <input type="checkbox" id="bulk_import" name="bulk_import"><label for="bulk_import" class="checkbox_label">Bulk Import</label> <br>
                            <small>(If slug is matched, update that post/page. Otherwise, it creates a new post.)</small>
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
                                                <input type="checkbox" id="update_post_page_ttl" name="update_post_page_ttl" checked><label for="update_post_page_ttl">Update Post/Page Title</label>
                                            </div>
                                            <div class="bulk_import_block">
                                                <input type="checkbox" id="update_post_page_slug" name="update_post_page_slug"><label for="update_post_page_slug">Update Post/Page Slug</label>
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
            <h4>Import Options</h4>
            <div class="import-form-wrapper">
                <form action="<?= admin_url('/admin-post.php') ?>" method="post" enctype="multipart/form-data">
                    <?php    wp_nonce_field('seip_option_import'); ?>
                    <input type="hidden" name="action" value="seip_option_import">
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <label for="file" class="label_block">Upload File</label>
                                </td>
                                <td>
                                    <input type="file" name="file" id="file">
                                </td>
                            </tr>
                            <tr>
                             <td></td>
                             <td>
                               <input type="submit" class="button button-primary" value="Import">
                           </td>
                       </tr>
                   </tbody>
               </table>
           </form>
       </div>
   </div>
</div>
<div class="col-md-3">
    <?php include '_sidebar.php'; ?>
</div>
</div>
</div>