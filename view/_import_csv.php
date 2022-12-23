<?php
if (!defined('ABSPATH')) {
    exit;
}

$options = array();

$field_groups = acf_get_field_groups();
foreach ( $field_groups as $group ) {
    // DO NOT USE here: $fields = acf_get_fields($group['key']);
    // because it causes repeater field bugs and returns "trashed" fields
    $fields = get_posts(array(
        'posts_per_page'   => -1,
        'post_type'        => 'acf-field',
        'orderby'          => 'menu_order',
        'order'            => 'ASC',
        'suppress_filters' => true, // DO NOT allow WPML to modify the query
        'post_parent'      => $group['ID'],
        'post_status'      => 'any',
        'update_post_meta_cache' => false
    ));

    echo "<pre>"; print_r($fields); echo "</pre>";
    foreach ( $fields as $field ) {
        $options[$field->post_name] = $field->post_title;
    }
}

echo "<pre>"; print_r($options); echo "</pre>";

echo "<pre>"; print_r(acf_get_field('field_63178cd663ed3')); echo "</pre>";
?>

<div class="seip_row">
    <div class="seip_col-md-6 seip_col-lg-6">
        <div class="card">
            <h2>Importing CSV File</h2>
            <div class="import-form-wrapper">
                <form action="<?php echo esc_url(admin_url('/admin-post.php')) ?>" method="post"
                      enctype="multipart/form-data">
                    <?php wp_nonce_field('seip_import_csv'); ?>
                    <input type="hidden" name="action" value="seip_import_csv">
                    <div class="block_imports">
                        <table>
                            <tbody>
                            <tr class="">
                                <td><label class="label_block" for="file">Post Type</label></td>
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
                            <tr class="">
                                <td><label class="label_block" for="file">Upload File</label></td>
                                <td>
                                    <input type="file" name="file" id="file" accept="text/csv">
                                    <div class="description">- Accepting only CSV file</div>
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
                    </div>
                </form>
            </div>
        </div>
        <?php
        echo "<pre>"; print_r(get_option('seip_import_csv')); echo "</pre>";
        ?>
        <div class="card" style="max-width: 100%;">
            <h2>Mapping</h2>
            <div class="import-form-wrapper">
                <form action="<?php echo esc_url(admin_url('/admin-post.php')) ?>" method="post"
                      enctype="multipart/form-data">
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
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Porro, fuga. Facere
                                        placeat ratione rem expedita cum inventore. Culpa impedit omnis ducimus ab
                                        explicabo reiciendis, voluptates ipsam minima in, architecto consectetur.</p>
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
                                    <table>
                                        <tr>
                                            <td>
                                                <div class="seip_td_second_label">Label 1</div>
                                                <div id="export_mulit_pages"
                                                     multiple
                                                     placeholder="Select page/post"
                                                     name="post_ids"
                                                     autofocus
                                                ></div>
                                            </td>
                                            <td>
                                                <div class="seip_td_second_label">Label 2</div>
                                                <div id="export_mulit_pages"
                                                     multiple
                                                     placeholder="Select page/post"
                                                     name="post_ids"
                                                     autofocus
                                                >
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

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
