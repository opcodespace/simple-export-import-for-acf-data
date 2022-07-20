<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
?>


<div class="seip_row">
	<div class="seip_col-md-6 seip_col-lg-6">
		<div class="card">
			<h2>Export Page/Post</h2>
			<div class="export-form-wrapper">
				<form action="<?php echo esc_url(admin_url('/admin-post.php')) ?>" method="post">
					<?php    wp_nonce_field('seip_export'); ?>
					<input type="hidden" name="action" value="seip_export">
					<div class="form-group">
						<input type="checkbox" id="bulk_export" name="bulk_export" <?php echo !SeipOpcodespace::isPaid() ? 'disabled' : '' ?>><label class="checkbox_label" for="bulk_export">Bulk Export <br>
							<?php echo !SeipOpcodespace::isPaid() ? PAID_TEXT : '' ?></label>
					</div>
					<div class="block_exports">
						<table>
							<tr>
								<td>
									<label for="" class="label_block">Type</label>
								</td>
								<td>
									<select name="post_type" class="chosen-select">
										<option value="">Please Select Type</option>
										<?php foreach(get_post_types([], 'objects') as $post_type):
											?>
											<option value='<?php echo esc_attr($post_type->name) ?>'><?php echo esc_attr($post_type->label) ?></option>
											<?php
										endforeach;
										?>
									</select>
								</td>
							</tr>
							<tr class="bulk_export_block">
								<td>
									<label class="label_block">Post/Page</label>
								</td>
								<td>
									<select name="post_id" class="chosen-select">

									</select>
								</td>
							</tr>
							<tr class="bulk_export_visible">
								<td>
									<label class="label_block">Posts/Pages</label>
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

						</table>
					</div>
					<div class="form-group">
						<input type="checkbox" id="export_taxonomy" name="export_taxonomy" <?php echo !SeipOpcodespace::isPaid() ? 'disabled' : 'checked' ?>><label class="checkbox_label" for="export_taxonomy">Export Taxonomy of Post / Custom Post Type</label>
						<span class="dashicons dashicons-info-outline" title="If you have already related terms of post, this plugin can import and attach terms to the post or custom post type. If you have hierarchical taxonomies, you must have taxonomies in your destination site. If slug of term is matched, it attaches to post. Otherwise, it creates a new term, but does not maintain hierarchy."></span> <br>
							<?php echo !SeipOpcodespace::isPaid() ? PAID_TEXT : '' ?>
					</div>

					<div class="">
						<input type="submit" class="button button-primary" value="Export">
					</div>
				</form>
			</div>
		</div>

		<div class="card">
			<h2>Export Options</h2>
			<div class="export-form-wrapper">
				<form action="<?php echo esc_url(admin_url('/admin-post.php')) ?>" method="post">
					<?php    wp_nonce_field('seip_option_export'); ?>
					<input type="hidden" name="action" value="seip_option_export">
					<div>
						<input type="submit" class="button button-primary" value="Export" <?php echo !SeipOpcodespace::isPaid() ? 'disabled' : '' ?>> <br>
						<?php echo !SeipOpcodespace::isPaid() ? PAID_TEXT : '' ?>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="seip_col-md-4 seip_col-lg-4">
		<?php include '_sidebar.php'; ?>
	</div>
</div>
