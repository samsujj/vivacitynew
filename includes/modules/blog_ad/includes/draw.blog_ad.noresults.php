<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc. 
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>

<div class="sharelinkmainwrapper">
<div class="te_table noresult_table">
	<fieldset class="te">
		<legend class="te">
			<?php echo htmlspecialchars( $this->_tableTitle )." :: ".@$_GET['s_type']; ?>
		</legend>
		
		<div class="te_noresults">
			<img class="spacer_top" border="0" src="images/spacer.gif" alt="" />
			<div>

					<a class="te_button te_new_button" href="<?php echo $this->url( 'te_mode=insert&te_share_link_id='.@$_GET['te_share_link_id'].'&s_type='.@$_GET['s_type'] ); ?>" title="New"><span>New</span></a>
					<a class="te_button te_new_button" href="share_links" title="Back"><span>Back</span></a>

			</div>
			<p class="to_noresults">No <?php echo htmlspecialchars($this->_unit_label); ?> Found.</p>
			<img class="spacer_bottom" border="0" src="images/spacer.gif" alt="" />
		</div>
		
	</fieldset>
</div>
</div>
