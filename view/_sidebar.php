<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
?>
<?php  if(!SeipOpcodespace::isPaid()): ?>
<div class="sidebar_box">
	<div class="sidebar_ttl">Pro facilities </div>
	<div class="sidebar_box_body">
		<ul>
			<li><span class="li_icon"><i class="fa fa-angle-double-right"></i></span>Bulk Export/Import</li>
			<li><span class="li_icon"><i class="fa fa-angle-double-right"></i></span>Export/Import ACF Options Data</li>
			<li><span class="li_icon"><i class="fa fa-angle-double-right"></i></span>Pro Fields: Image, Gallery</li>
			<li><span class="li_icon"><i class="fa fa-angle-double-right"></i></span>Upcoming all pro fields will be available.</li>
		</ul>
		<a href="https://opcodespace.com/product/simple-export-import-pro-for-acf/" class="btn btn-warning">Upgrade Now (only $9 for two sites)</a>
        <br><br>
		<a href="https://opcodespace.com/product/simple-export-import-pro-for-acf-unlimited/" class="btn btn-warning">Upgrade Now (only $49/year for unlimited sites)</a>
	</div>
</div>
<?php endif ?>