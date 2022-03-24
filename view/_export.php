<div class="card">
	<h2>Export Page/Post</h2>
	<div class="export-form-wrapper">
		<form action="<?= admin_url('/admin-post.php') ?>" method="post">
			<?php    wp_nonce_field('seip_export'); ?>
			<input type="hidden" name="action" value="seip_export">
			<div class="form-group">
				<input type="checkbox" id="export_option_data" checked="0"><label for="export_option_data">Option Data</label>
			</div>
			<div class="block_exports">
				<div class="form-group">
					<label for="" class="label_block">Type</label>
					<select name="post_type">
						<option value="">Please Select Type</option>
						<?php foreach(get_post_types([], 'objects') as $post_type):
							echo "<option value='{$post_type->name}'>{$post_type->label}</option>";
						endforeach;
						?>
					</select>
				</div>
				<div class="form-group">
					<input type="checkbox" id="bulk_export"><label for="bulk_export">Bulk Export</label>
				</div>
				<div class="bulk_export_block">
					<div class="form-group">
						<label class="label_block">Post/Page</label>
						<select name="post_id" id="">

						</select>
					</div>     
					<div class="form-group">
						<label class="label_block">Posts/Pages</label>
						<select id="export_mulit_pages" multiple="multiple">
							<option value="cheese">Cheese</option>
							<option value="tomatoes">Tomatoes</option>
							<option value="mozarella">Mozzarella</option>
							<option value="mushrooms">Mushrooms</option>
							<option value="pepperoni">Pepperoni</option>
							<option value="onions">Onions</option>
						</select>
					</div>                   
				</div>
			</div>

			<div>
				<input type="submit" class="button button-primary" value="Export">
			</div>
		</form>
	</div>
</div>

<div class="card">
	<h2>Export Options</h2>
	<div class="export-form-wrapper">
		<form action="<?= admin_url('/admin-post.php') ?>" method="post">
			<?php    wp_nonce_field('seip_option_export'); ?>
			<input type="hidden" name="action" value="seip_option_export">
			<div>
				<input type="submit" class="button button-primary" value="Export">
			</div>
		</form>
	</div>
</div>