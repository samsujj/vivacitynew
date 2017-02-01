<?php

require_once ai_cascadepath('includes/plugins/modules/class.module_base.php');
require_once ai_cascadepath(dirname(__FILE__) . '/includes/class.te_share_asset.php');

/**
 * share_links module
 */
class share_asset_module extends module_base
{
	public $mod_system_name = 'share_asset'; // Not static because parent needs to access this
	public $mod_name = 'Share Asset Manager';
	public $mod_description = 'Share Asset Manager';
	public $mod_version = '2.6';
	public $mod_ignore_lock_at_or_before_version = '0.0';

	/**
	 * Called when module is loaded AND is initiated
	 *
	 * @param $settings Array of settings, unrealized from the database
	 */
	public function mod_load_settings( $settings )
	{	}

	/**
	 * mod_upgrade()
	 *
	 * Run any version upgrades.  Only triggered when db version # is out of date when compared to static version # within the module.
	 */
	public function mod_upgrade( $db_version )
	{
		global $AI;

		if ( $this->mod_is_older_version($db_version, '.1') )
		{
			db_query("
			   CREATE TABLE `share_asset` (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `name` varchar(255) NOT NULL DEFAULT '',
				 `url` varchar(255) NOT NULL DEFAULT '',
				 `height` int(11) NOT NULL DEFAULT '0',
				 `width` int(11) NOT NULL DEFAULT '0',
				 `sort_order` int(11) NOT NULL DEFAULT '0',
				 `share_link_id` int(11) NOT NULL DEFAULT '0',
				 `status` tinyint(1) NOT NULL DEFAULT '0',
				 `type` ENUM( 'Banner Ads', 'Display Ads', 'Mobile Ads' ) NOT NULL,
				 PRIMARY KEY (`id`)
			   ) ENGINE=MyISAM DEFAULT CHARSET=latin1
			;");

			//CREATE PAGE(s)
			$AI->skin->create_dynamicpage(
				'share_asset' //$pagename
				, array('body' => 'includes/modules/share_asset/share_asset.php') //$content
				, array('body' => 'file') //$types
				, 'default' //$skinname = 'default'
				, 'N' //$requires_ssl = 'N'
				, 'en' //$lang = ''
				);

			//ADD PERMISSIONS
			$perm_classes = array('share_asset');
			$perm_groups = array('Website Developers', 'Administrators','Distributors');
			$perm_types = array('ajax','ajax_cmd_inline_edit','ajax_cmd_inline_save','asearch','copy','delete','insert','multidelete','table','update','view');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$perm_groups = array('Users');
			$perm_types = array('ajax','table');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$AI->grant_page_perm( 'share_asset', array('Website Developers','Administrators','Users','Anonymous','Distributors') );

			$this->mod_set_db_version('.1');
		}

		if ( $this->mod_is_older_version($db_version, '2.4') ) {
			//ADD PERMISSIONS
			$perm_classes = array('share_asset');
			$perm_groups = array('Distributors');
			$perm_types = array('ajax','ajax_cmd_inline_edit','ajax_cmd_inline_save','asearch','copy','delete','insert','multidelete','table','update','view');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$AI->grant_page_perm( 'share_asset', array('Distributors') );
		}

        if ( $this->mod_is_older_version($db_version, '2.6') ) {
            db_query("ALTER TABLE  `share_asset` CHANGE  `type`  `type` ENUM(  'Banner Ads',  'Display Ads',  'Mobile Ads' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;");
        }


	}

	/**
	 * Display help documents
	 */
	public function mod_help()
	{
		echo '<p>This module creates a share_asset manager database.</p>';
	}

	/**
	 * Draw a form to build settings.
	 * @param $fieldstart The starting string to use for input fields
	 * @return null
	 */
	public function mod_settings( $fieldstart )
	{

	}

	/**
	 * Run though the inputed fields
	 *
	 * @see mod_settings
	 * @param $form_items The values submitted by the form drawn in mod_settings()
	 */
	public function mod_settings_validate( $form_items )
	{
		return true;
	}

	////////////////////////////////////////////////////////////////
	// HOOKS ///////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////


};
