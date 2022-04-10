<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
?>
<div class="bootstrap-wrapper">
	<div class="row">
		<div class="col-md-4">
			<div class="card">
				 <h4>License Key</h4>
				 <?php
				 wp_nonce_field( 'seip_save_license_key');
				 ?>
				 <div class="license_wrapper">
				 	<table>
				 		<tbody>
				 			<tr>
				 				<td><label class="label_block">Type</label></td>
				 				<td>
				 					<input type="password" class="form-control" value="<?= get_option('seip_license_key') ?>" name="seip_license_key">
				 				</td>
				 			</tr>
				 			<tr>
				 				<td></td>
				 				<td><input type="submit" class="button button-primary save-license-key" value="Save"></td>
				 			</tr>
							 <div class="alert"></div>
				 		</tbody>
				 	</table>
				 </div>
			</div>
		</div>
	</div>
</div>

<script>

jQuery(function($){
	$('.save-license-key').click(function(){
		$.ajax({
		method: 'POST',
		url: frontend_form_object.ajaxurl,
		data: { action: 'seip_save_license_key', _wpnonce: $('#_wpnonce').val(), seip_license_key: $('[name="seip_license_key"]').val()}
		})
		.done(function( response ) {
			if(response.success){
				$('.license_wrapper .alert').html(`<p style='color: green'>${response.data.message}</p>`);
			}
			else{
				$('.license_wrapper .alert').html(`<p style='color: red'>${response.data.message}</p>`);
			}
		});
	})
})
</script>