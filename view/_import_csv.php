<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="seip_row">
    <div class="seip_col-md-6 seip_col-lg-6">
        <div class="card">
            <h2>Importing CSV File</h2>
            <div class="import-form-wrapper">
                <form action="<?php echo esc_url(admin_url('/admin-post.php')) ?>" method="post" enctype="multipart/form-data">

                    <div class="block_imports">
                        <table>
                            <tbody>
                                <tr class="">
                                    <td><label class="label_block" for="file">Select Your CSV File</label></td>
                                    <td>
                                        <input type="file" name="file" id="file" accept="application/json">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            <h2>Maping</h2>
            <div class="import-form-wrapper">
                <form action="<?php echo esc_url(admin_url('/admin-post.php')) ?>" method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field('seip_import'); ?>
                    <input type="hidden" name="action" value="seip_import">
                    <div class="block_csv_box">
                        <table>
                            <tbody>
                                <tr class="maping_row">
                                    <td>
                                        <div class="maping_block_wrapper">
                                            <div class="item_number">1.</div>
                                            <div class="csv_ttl">Post Image</div>
                                            <div class="csv_meta_key"><b>Meta Key : </b>Image</div>
                                            <div class="csv_type"><b>Type : </b>Image</div>
                                        </div>
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
                                <tr class="maping_row">
                                    <td>
                                        <div class="maping_block_wrapper">
                                            <div class="item_number">2.</div>
                                            <div class="csv_ttl">Gallery</div>
                                            <div class="csv_meta_key"><b>Meta Key : </b>Gallery</div>
                                            <div class="csv_type"><b>Type : </b>Gallery</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="export_mulit_pages"
                                        multiple
                                        placeholder="Select page/post"
                                        name="post_ids"
                                        autofocus
                                        >
                                    </div>
                                </td>
                            </tr>
                                <tr class="maping_row">
                                    <td>
                                        <div class="maping_block_wrapper">
                                            <div class="item_number">3.</div>
                                            <div class="csv_ttl">Object</div>
                                            <div class="csv_meta_key"><b>Meta Key : </b>Object</div>
                                            <div class="csv_type"><b>Type : </b>Object</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px;" id="export_mulit_pages"
                                        multiple
                                        placeholder="Select page/post"
                                        name="post_ids"
                                        autofocus
                                        >
                                    </div>
                                        <div id="export_mulit_pages"
                                        multiple
                                        placeholder="Select page/post"
                                        name="post_ids"
                                        autofocus
                                        >
                                    </div>
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
</div>
<div class="seip_col-md-4 seip_col-lg-4">
    <?php include '_sidebar.php'; ?>
</div>
</div>
