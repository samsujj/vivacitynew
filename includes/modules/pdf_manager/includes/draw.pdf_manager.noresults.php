<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc. 
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>


<div class="te_table pixel_table">
	<fieldset class="te">
		<legend class="te">
			<?php echo htmlspecialchars( $this->_tableTitle ); ?>
		</legend>
		
		<div class="te_noresults">
			<img class="spacer_top" border="0" src="images/spacer.gif" alt="" />
			<div>

					<a class="te_button te_new_button" href="<?php echo $this->url( 'te_mode=insert' ); ?>" title="New"><span class="te_button te_new_button" style="background: #004c92 none repeat scroll 0 0 !important; border: medium none !important; border-radius: 0 !important; box-shadow: none !important; color: #ffffff !important; display: inline-block !important; font-size: 14px !important; height: 32px !important; line-height: 32px !important; margin: 0 !important; text-align: center !important; min-width: 80px !important;">New</span></a>

			</div>
			<p class="to_noresults">No <?php echo htmlspecialchars($this->_unit_label); ?> Found.</p>
			<img class="spacer_bottom" border="0" src="images/spacer.gif" alt="" />
		</div>
		
	</fieldset>
</div>
