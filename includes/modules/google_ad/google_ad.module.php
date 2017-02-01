<?php

require_once ai_cascadepath('includes/plugins/modules/class.module_base.php');
require_once ai_cascadepath(dirname(__FILE__) . '/includes/class.te_google_ad.php');

/**
 * google_ad module
 */
class google_ad_module extends module_base
{
	public $mod_system_name = 'google_ad'; // Not static because parent needs to access this
	public $mod_name = 'Google Ad Manager';
	public $mod_description = 'Google Ad Manager';
	public $mod_version = '2.4';
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
				CREATE TABLE `google_ad` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL DEFAULT '0',
				  `share_link_id` int(11) NOT NULL DEFAULT '0',
				  `name` varchar(255) NOT NULL DEFAULT '',
				  `type` ENUM( 'Traffic', 'Confirmation' ) NOT NULL,
				  `code` text NOT NULL,
				  `code1` text NOT NULL,
				  `url` varchar(255) NOT NULL DEFAULT '',
				  `added_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `last_modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `status` tinyint(1) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1
			;");

			//CREATE PAGE(s)
			$AI->skin->create_dynamicpage(
				'google_ad' //$pagename
				, array('body' => 'includes/modules/google_ad/google_ad.php') //$content
				, array('body' => 'file') //$types
				, 'default' //$skinname = 'default'
				, 'N' //$requires_ssl = 'N'
				, 'en' //$lang = ''
				);

			//ADD PERMISSIONS
			$perm_classes = array('google_ad');
			$perm_groups = array('Website Developers', 'Administrators','Distributor');
			$perm_types = array('ajax','ajax_cmd_inline_edit','ajax_cmd_inline_save','asearch','copy','delete','insert','multidelete','table','update','view');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$perm_groups = array('Users');
			$perm_types = array('ajax','table');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$AI->grant_page_perm( 'google_ad', array('Website Developers','Administrators','Users','Anonymous','Distributor') );

			// SETUP DYNAMIC AREAS
			/*$sql = "
				SELECT id
				FROM dynamic_areas
				WHERE name = 'my-urls-header'
				LIMIT 1
			;";
			$existing_id = (int) db_lookup_scalar($sql);
			if ( $exisitng_id < 1 )
			{
				$sql_now = date('Y-m-d H:i:s');
				$sql = "
					INSERT INTO dynamic_areas
					SET name = 'my-urls-header'
					, content = '<h2>Share Links</h2><p style=\"text-align:center\"><strong>The links below are to your personal website and landing pages.  Note that each of the website addresses do have a unique indentifier that ensures you are given credit for all activity related to your links.  You will want to drive your traffic to these links.</strong></p>'
					, lang = 'en'
					, created_on = '" . db_in($sql_now) . "'
					, saved_on = '" . db_in($sql_now) . "'
				;";
				db_query($sql);
			}*/

			$this->mod_set_db_version('.1');
		}

		if ( $this->mod_is_older_version($db_version, '2.4') ) {
			//ADD PERMISSIONS
			$perm_classes = array('google_ad');
			$perm_groups = array('Distributors');
			$perm_types = array('ajax','ajax_cmd_inline_edit','ajax_cmd_inline_save','asearch','copy','delete','insert','multidelete','table','update','view');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$AI->grant_page_perm( 'google_ad', array('Distributors') );
		}

	}

	/**
	 * Display help documents
	 */
	public function mod_help()
	{
		echo '<p>This module creates a google_ad manager database.</p>';
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
