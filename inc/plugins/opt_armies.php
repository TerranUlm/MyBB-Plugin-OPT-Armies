<?php

/*
OPT Armies Plugin for MyBB
Copyright (C) 2013 Dieter Gobbers aka Terran

The MIT License (MIT)

Copyright (c) 2016 Dieter Gobbers

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/

/* Exported by Hooks plugin Mon, 02 Sep 2013 09:26:22 GMT */

if (!defined('IN_MYBB'))
{
	die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

if (!defined("PLUGINLIBRARY"))
{
	define("PLUGINLIBRARY", MYBB_ROOT . "inc/plugins/pluginlibrary.php");
}

// if (rename_function('format_name', 'format_name_orig'))
// {
	// die('success');
// }
// else
// {
	// die('fail');
// }

/* --- Plugin API: --- */

function opt_armies_info()
{
	return array(
		'name' => 'OPT Armies',
		'description' => 'An Army Management System',
		'website' => 'http://opt-community.de/',
		'author' => 'Dieter Gobbers (@Terran_ulm)',
		'authorsite' => 'http://opt-community.de/',
		'version' => '1.1.0',
		'guid' => '',
		'compatibility' => '18*'
	);
}

function opt_armies_activate()
{
	if (!file_exists(PLUGINLIBRARY))
	{
		flash_message("PluginLibrary is missing.", "error");
		admin_redirect("index.php?module=config-plugins");
	}
	
	global $PL;
	$PL or require_once PLUGINLIBRARY;
	
	if ($PL->version < 12)
	{
		flash_message("PluginLibrary is too old: " . $PL->version, "error");
		admin_redirect("index.php?module=config-plugins");
	}
	
	global $db, $lang, $cache;
	
	$lang->load('opt_armies');
	
	opt_armies_deactivate();
	
	// activate stylesheet
	opt_armies_setup_stylessheet();
	
	// Modify some templates.
	require_once MYBB_ROOT . '/inc/adminfunctions_templates.php';
	// find_replace_templatesets('postbit', '#'.preg_quote('{$post[\'user_details\']}').'#', '{$post[\'user_details\']}{$post[\'opt_armies\']}');
	
	// $result = $db->update_query("tasks", array("enabled" => intval(1)), "title='".$db->escape_string($lang->opt_armies_title)."'");
	// $cache->update_tasks();
	
	change_admin_permission('tools', 'opt_armies');
}

function opt_armies_deactivate()
{
	if (!file_exists(PLUGINLIBRARY))
	{
		flash_message("PluginLibrary is missing.", "error");
		admin_redirect("index.php?module=config-plugins");
	}
	
	global $PL;
	$PL or require_once PLUGINLIBRARY;
	
	if ($PL->version < 12)
	{
		flash_message("PluginLibrary is too old: " . $PL->version, "error");
		admin_redirect("index.php?module=config-plugins");
	}
	
	global $db, $lang, $cache;
	
	$lang->load('opt_armies');
	
	$PL->stylesheet_deactivate('opt_armies');
	
	// Remove added variables.
	require_once MYBB_ROOT . '/inc/adminfunctions_templates.php';
	// find_replace_templatesets('postbit', '#'.preg_quote('{$post[\'opt_armies\']}').'#', '', 0);
	
	// $result = $db->update_query("tasks", array("enabled" => intval(0)), "title='".$db->escape_string($lang->opt_armies_title)."'");
	// $cache->update_tasks();
	
	change_admin_permission('tools', 'opt_armies', -1);
}

function opt_armies_is_installed()
{
	
	if (!file_exists(PLUGINLIBRARY))
	{
		flash_message("PluginLibrary is missing.", "error");
		admin_redirect("index.php?module=config-plugins");
	}
	
	global $PL;
	$PL or require_once PLUGINLIBRARY;
	
	if ($PL->version < 12)
	{
		flash_message("PluginLibrary is too old: " . $PL->version, "error");
		admin_redirect("index.php?module=config-plugins");
	}
	
	global $db;
	
	// setup some helper functions
	function opt_armies_settinggroups_defined($settinggroup)
	{
		global $db;
		$query  = $db->simple_select('settinggroups', '*', 'name="' . $db->escape_string($settinggroup) . '"');
		$result = $db->fetch_array($query);
		$db->free_result($query);
		return (!empty($result));
	}
	
	function opt_armies_setting_defined($setting)
	{
		global $db;
		$query  = $db->simple_select('settings', '*', 'name="' . $db->escape_string($setting) . '"');
		$result = $db->fetch_array($query);
		$db->free_result($query);
		return (!empty($result));
	}
	
	// definitions:
	$settinggroups = array(
		'opt_armies'
	);
	$settings      = array(
		'opt_armies_registration_open',
		'opt_armies_random_join_only',
		'opt_armies_max_member_difference'
	);
	$tables        = array(
		'armies_user_ranks',
		'armies_army_ranks',
		'armies_ranks',
		'armies',
		'armies_structures'
	);
	
	// now check if the DB is setup
	$is_installed = true;
	foreach ($settinggroups as $settinggroup)
	{
		if (!opt_armies_settinggroups_defined($settinggroup))
		{
			$is_installed = false;
		}
	}
	foreach ($settings as $setting)
	{
		if (!opt_armies_setting_defined($setting))
		{
			$is_installed = false;
		}
	}
	foreach ($tables as $table)
	{
		if (!$db->table_exists($table))
		{
			$is_installed = false;
		}
	}
	
	return $is_installed;
}

function opt_armies_install()
{
	if (!file_exists(PLUGINLIBRARY))
	{
		flash_message("PluginLibrary is missing.", "error");
		admin_redirect("index.php?module=config-plugins");
	}
	
	global $PL;
	$PL or require_once PLUGINLIBRARY;
	
	if ($PL->version < 12)
	{
		flash_message("PluginLibrary is too old: " . $PL->version, "error");
		admin_redirect("index.php?module=config-plugins");
	}
	
	global $db, $lang, $cache;
	
	$lang->load('opt_armies');
	
	$myplugin = opt_armies_info();
	
	// create ACP settings
	{
		$PL->settings('opt_armies', $myplugin[ 'name' ], $myplugin[ 'description' ] . '. Configure the Army System Settings.', array(
			'registration_open' => array(
				'title' => $lang->opt_armies_registration_open_title,
				'description' => $lang->opt_armies_registration_open_description,
				'optionscode' => 'yesno',
				'value' => 1
			),
			'random_join_only' => array(
				'title' => $lang->opt_armies_registration_random_only_title,
				'description' => $lang->opt_armies_registration_random_only_description,
				'optionscode' => 'yesno',
				'value' => 0
			),
			'max_member_difference' => array(
				'title' => $lang->opt_armies_max_member_difference_title,
				'description' => $lang->opt_armies_max_member_difference_description,
				'optionscode' => 'text',
				'value' => 10
			)
		));
	}
	
	// tables definition statements
	{
		$create_table_armies            = "CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "armies` (
			`aid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Army ID',
			`gid` smallint(5) unsigned NOT NULL COMMENT 'usergroup ID',
			`uugid` smallint(5) unsigned NOT NULL COMMENT 'unassigned users usergroup ID',
			`HCO_gid` smallint(5) unsigned DEFAULT NULL COMMENT 'High Command Officer Group ID',
			`CO_gid` smallint(5) unsigned DEFAULT NULL COMMENT 'Commanding Officer Group ID',
			`shortcut` varchar(5) NOT NULL COMMENT 'shortcut of the army name (aka \"Tag\")',
			`name` varchar(255) NOT NULL COMMENT 'Name of the Army',
			`nation` varchar(255) NOT NULL COMMENT 'Nation of the Army',
			`icon` varchar(255) NOT NULL DEFAULT '' COMMENT 'Army Icon (optional)',
			`leader_uid` int(10) unsigned NOT NULL COMMENT 'the army leaders'' UID',
			`displayorder` int(10) unsigned NOT NULL,
			`is_locked` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '0=users can join the army, 1=users cannot join the army',
			`is_invite_only` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0=users can request to join the army, 1=users must be invited to join the army',
			`welcome_pm` text COMMENT 'templates for the PMs send to new recruits',
			PRIMARY KEY (`aid`),
			UNIQUE KEY `name` (`name`),
			UNIQUE KEY `gid` (`gid`),
			UNIQUE KEY `uugid` (`uugid`),
			UNIQUE KEY `HCO_gid` (`HCO_gid`),
			UNIQUE KEY `CO_gid` (`CO_gid`),
			KEY `displayorder` (`displayorder`),
			KEY `leader_uid` (`leader_uid`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Armies'";
		$create_table_armies_structures = "CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "armies_structures` (
			`agrid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Army to Groups Relations ID',
			`pagrid` int(11) unsigned DEFAULT NULL COMMENT 'Parent''s agrid',
			`aid` int(11) NOT NULL COMMENT 'Army ID',
			`gid` smallint(5) unsigned NOT NULL COMMENT 'usergroup ID',
			`shortcut` varchar(5) DEFAULT NULL COMMENT 'shortcut of the group name (aka \"Tag\")',
			`leader_uid` int(10) unsigned NOT NULL COMMENT 'the groups leaders'' UID',
			`displayorder` int(11) NOT NULL,
			PRIMARY KEY (`agrid`),
			UNIQUE KEY `gid` (`gid`),
			KEY `displayorder` (`displayorder`),
			KEY `acid` (`aid`),
			KEY `pagrid` (`pagrid`),
			KEY `leader_uid` (`leader_uid`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Army and Group Relationships'";
		$alter_table_armies_structures  = "ALTER TABLE `" . TABLE_PREFIX . "armies_structures`
			ADD CONSTRAINT `" . TABLE_PREFIX . "armies_structures_ibfk_1` FOREIGN KEY (`aid`) REFERENCES `" . TABLE_PREFIX . "armies` (`aid`) ON DELETE CASCADE ON UPDATE CASCADE";
		$create_table_armies_ranks = "CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "armies_ranks` (
			`arid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Army Rank ID',
			`armies` varchar(255) NOT NULL COMMENT 'Rank available for those Armies (comma separated)',
			`name` varchar(255) NOT NULL COMMENT 'Name of the Rank',
			`shortcut` varchar(6) NOT NULL COMMENT 'Rank Shortcut',
			`rcid` int(11) unsigned NOT NULL COMMENT 'Rank Class ID',
			`icon` varchar(255) DEFAULT NULL COMMENT 'URL of the Rank icon',
			`displayorder` int(11) unsigned NOT NULL COMMENT 'Displayorder of the Rank',
			PRIMARY KEY (`arid`),
			KEY `rcid` (`rcid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		$create_table_army_ranks = "CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "armies_army_ranks` (
			`aid` int(11) NOT NULL COMMENT 'Army ID',
			`arid` int(11) unsigned NOT NULL COMMENT 'Army Rank ID',
			PRIMARY KEY (`aid`,`arid`),
			KEY `arid` (`arid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$alter_table_army_ranks = "ALTER TABLE `" . TABLE_PREFIX . "armies_army_ranks`
			ADD CONSTRAINT `" . TABLE_PREFIX . "armies_army_ranks_ibfk_2` FOREIGN KEY (`aid`) REFERENCES `" . TABLE_PREFIX . "armies` (`aid`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `" . TABLE_PREFIX . "armies_army_ranks_ibfk_1` FOREIGN KEY (`arid`) REFERENCES `" . TABLE_PREFIX . "armies_ranks` (`arid`) ON DELETE CASCADE ON UPDATE CASCADE";
		$create_table_user_ranks = "CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "armies_user_ranks` (
			`uid` int(10) NOT NULL COMMENT 'User ID',
			`arid` int(11) unsigned NOT NULL COMMENT 'Army Rank ID',
			PRIMARY KEY (`uid`),
			KEY `arid` (`arid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Each User may hold one rank at a time.'";
		$alter_table_user_ranks = "ALTER TABLE `" . TABLE_PREFIX . "armies_user_ranks`
			ADD CONSTRAINT `" . TABLE_PREFIX . "armies_user_ranks_ibfk_1` FOREIGN KEY (`arid`) REFERENCES `" . TABLE_PREFIX . "armies_ranks` (`arid`) ON DELETE CASCADE ON UPDATE CASCADE";
		
		// create tables
		$db->write_query($create_table_armies);
		$db->write_query($create_table_armies_structures);
		$db->write_query($create_table_armies_ranks);
		$db->write_query($create_table_army_ranks);
		$db->write_query($create_table_user_ranks);
		
		// alter tables
		$db->write_query($alter_table_armies_structures);
		$db->write_query($alter_table_army_ranks);
		$db->write_query($alter_table_user_ranks);
	}
	
	// create stylesheet
	opt_armies_setup_stylessheet();
	
	// create templates
	opt_armies_setup_templates();
	
	// create task
	// require_once MYBB_ROOT."/inc/functions_task.php";
	
	// $new_task = array(
	// "title" => $db->escape_string($lang->opt_armies_title),
	// "description" => $db->escape_string($lang->opt_armies_task_description),
	// "file" => $db->escape_string('opt_armies'),
	// "minute" => $db->escape_string('27'),
	// "hour" => $db->escape_string('3'),
	// "day" => $db->escape_string('*'),
	// "month" => $db->escape_string('*'),
	// "weekday" => $db->escape_string('*'),
	// "enabled" => intval(0),
	// "logging" => intval(1)
	// );
	
	// $new_task['nextrun'] = fetch_next_run($new_task);
	// $tid = $db->insert_query("tasks", $new_task);
	// $cache->update_tasks();
	
}

function opt_armies_uninstall()
{
	global $PL;
	$PL or require_once PLUGINLIBRARY;
	
	$myplugin = opt_armies_info();
	$PL->settings_delete('opt_armies');
	
	$PL->settings_delete('opt_armies');
	
	global $db, $lang, $cache;
	
	$lang->load('opt_armies');
	
	// drop tables
	$tables = array(
		'armies_user_ranks',
		'armies_army_ranks',
		'armies_ranks',
		'armies_structures',
		'armies'
	);
	foreach ($tables as $table)
	{
		$db->write_query("DROP TABLE " . TABLE_PREFIX . $table);
	}
	
	$PL->stylesheet_delete('opt_armies');
	$PL->templates_delete('optarmies');
	
	// $db->delete_query("tasks", "title='{$db->escape_string($lang->opt_armies_title)}'");
	// $cache->update_tasks();
	
}


/* --- Hooks: --- */

$plugins->add_hook('admin_config_permissions', 'opt_armies_admin_permissions');

function opt_armies_admin_permissions(&$admin_permissions)
{
	global $lang;
	
	$lang->load('opt_armies');
	
	$admin_permissions[ 'opt_armies' ] = $lang->opt_armies_can_manage_armies;
}

/* --- Hook #15 - ACP Configuration Tab Handler --- */

$plugins->add_hook('admin_config_action_handler', 'opt_armies_admin_config_action_handler_15', 10);

function opt_armies_admin_config_action_handler_15(&$action)
{
	$action[ 'opt_armies' ] = array(
		'active' => 'opt_armies'
	);
}

/* --- Hook #14 - ACP Armies Menu Entry --- */

$plugins->add_hook('admin_config_menu', 'opt_armies_admin_config_menu_14', 10);

function opt_armies_admin_config_menu_14(&$submenu)
{
	global $lang;
	$lang->load('opt_armies');
	$submenu[] = array(
		'id' => 'opt_armies',
		'title' => $lang->opt_armies_title,
		'link' => 'index.php?module=config-opt_armies'
	);
}

/* --- Hook #16 - ACP OPT Armies Settings Tab --- */

$plugins->add_hook('admin_load', 'opt_armies_admin_load_16', 10);

function opt_armies_admin_load_16()
{
	global $lang, $mybb, $db, $page, $cache, $errors;
	
	// echo $page->active_action;
	
	if ($page->active_action != 'opt_armies')
		return false;
	
	$lang->load('opt_armies');
	
	$page->add_breadcrumb_item($lang->opt_armies_title, 'index.php?module=config-opt_armies');
	
	$tabs[ 'opt_armies_list_armies' ] = array(
		'title' => $lang->opt_armies_list_armies,
		'link' => 'index.php?module=config-opt_armies',
		'description' => $lang->opt_armies_list_armies_description
	);
	$tabs[ 'opt_armies_list_ranks' ]    = array(
		'title' => $lang->opt_armies_list_ranks,
		'link' => 'index.php?module=config-opt_armies&action=listranks',
		'description' => $lang->opt_armies_list_ranks_description
	);
	
	// default page
	if (!$mybb->input[ 'action' ])
	{
		$tabs[ 'opt_armies_add_army' ] = array(
			'title' => $lang->opt_armies_add_army,
			'link' => 'index.php?module=config-opt_armies&action=addarmy',
			'description' => $lang->opt_armies_add_army_description
		);
		$usergroups                    = $cache->read('usergroups');
		$page->output_header($lang->opt_armies_list_armies);
		$page->output_nav_tabs($tabs, 'opt_armies_list_armies');
		
		$form = new Form("index.php?module=config-opt_armies&amp;action=updatearmies", "post");
		
		$table = new Table;
		
		$table->construct_header($lang->opt_armies_army_shortcut);
		$table->construct_header($lang->opt_armies_army_name);
		$table->construct_header($lang->opt_armies_army_nation);
		$table->construct_header($lang->opt_armies_army_icon, array(
			'class' => 'align_center'
		));
		$table->construct_header($lang->opt_armies_army_leader);
		$table->construct_header($lang->opt_armies_army_primary_group);
		$table->construct_header($lang->opt_armies_army_hco_group);
		$table->construct_header($lang->opt_armies_army_co_group);
		$table->construct_header($lang->opt_armies_army_default_group);
		$table->construct_header($lang->opt_armies_army_displayorder, array(
			'class' => 'align_center'
		));
		$table->construct_header($lang->opt_armies_army_locked, array(
			'class' => 'align_center'
		));
		$table->construct_header($lang->opt_armies_army_invite_only, array(
			'class' => 'align_center'
		));
		$table->construct_header($lang->options, array(
			'class' => 'align_center'
		));
		
		$query = $db->simple_select('armies', '*', '', array(
			'order_by' => 'displayorder',
			'order_dir' => 'ASC'
		));
		while ($army = $db->fetch_array($query))
		{
			$table->construct_cell($army[ 'shortcut' ]);
			$table->construct_cell($army[ 'name' ]);
			$table->construct_cell($army[ 'nation' ]);
			$table->construct_cell(opt_armies_build_image_url($army[ 'icon' ]), array(
				'class' => 'align_center'
			));
			$table->construct_cell(opt_armies_get_username_by_uid($army[ 'leader_uid' ]));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'gid' ]));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'HCO_gid' ], '-'));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'CO_gid' ], '-'));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'uugid' ]));
			$table->construct_cell('<input type="text" name="army[' . $army[ 'aid' ] . ']" value="' . $army[ 'displayorder' ] . '" size="3" class="align_center"/>', array(
				'class' => 'align_center'
			));
			$table->construct_cell(($army[ 'is_locked' ]) == 0 ? $lang->no : $lang->yes, array(
				'class' => 'align_center'
			));
			$table->construct_cell(($army[ 'is_invite_only' ]) == 0 ? $lang->no : $lang->yes, array(
				'class' => 'align_center'
			));
			
			$popup = new PopupMenu("army_{$army['aid']}", $lang->options);
			$popup->add_item($lang->opt_armies_edit_army, "index.php?module=config-opt_armies&amp;action=editarmy&amp;aid={$army['aid']}");
			$popup->add_item($lang->opt_armies_configure_army, "index.php?module=config-opt_armies&amp;action=configarmy&amp;aid={$army['aid']}");
			$popup->add_item($lang->opt_armies_add_user, "index.php?module=config-opt_armies&amp;action=adduser&amp;aid={$army['aid']}");
			$popup->add_item($lang->opt_armies_delete_army, "index.php?module=config-opt_armies&amp;action=deletearmy&amp;aid={$army['aid']}&my_post_key=" . $mybb->post_code, 'return AdminCP.deleteConfirmation(this,\'' . $lang->opt_armies_delete_army_question . '\')');
			$table->construct_cell($popup->fetch(), array(
				'class' => 'align_center'
			));
			
			$table->construct_row();
		}
		if ($db->num_rows($query) == 0)
		{
			$table->construct_cell($lang->opt_armies_no_armies, array(
				'colspan' => '13',
				'class' => 'align_center'
			));
			$table->construct_row();
		}
		else
		{
			$table->construct_cell('<input type="submit" value="' . $lang->opt_armies_update_order . '" />', array(
				'colspan' => 13
			));
			$table->construct_row();
		}
		$table->output($lang->opt_armies_table_armies);
		
		$form->end;
		$db->free_result($query);
		
		$page->output_footer();
	}
	
	// update sort order of the armies
	if ($mybb->input[ 'action' ] == 'updatearmies')
	{
		if (!verify_post_check($mybb->input[ 'my_post_key' ]))
		{
			$highlight = '';
			flash_message($lang->invalid_post_verify_key2, 'error');
		}
		else
		{
			$armies = $_REQUEST[ 'army' ];
			foreach ($armies as $army => $key)
			{
				$key  = (int) $key;
				$army = (int) $army;
				
				$updated_record = array(
					"displayorder" => $db->escape_string($key)
				);
				$db->update_query('armies', $updated_record, "aid='" . $db->escape_string($army) . "'");
			}
			
			opt_armies_reorder_armies();
			
			admin_redirect("index.php?module=config-opt_armies");
		}
	}
	
	// add or edit army
	if ($mybb->input[ 'action' ] == 'editarmy' || $mybb->input[ 'action' ] == 'addarmy')
	{
		if ($mybb->input[ 'action' ] == 'addarmy')
		{
			$shortcut       = '';
			$name           = '';
			$nation         = '';
			$icon           = '';
			$leader         = '';
			$p_gid          = '2';
			$d_gid          = '2';
			$hco_gid        = '';
			$co_gid         = '';
			$displayorder   = '1000';
			$is_locked      = '1';
			$is_invite_only = '1';
			$pm             = $lang->opt_armies_army_welcome_pm_template_default;
		}
		else
		{
			$aid = (int) $_REQUEST[ 'aid' ];
			
			$query = $db->simple_select('armies', '*', 'aid=' . $aid, array(
				'limit' => '1'
			));
			$army  = $db->fetch_array($query);
			$db->free_result($query);
			
			$shortcut       = $army[ 'shortcut' ];
			$name           = $army[ 'name' ];
			$nation         = $army[ 'nation' ];
			$icon           = $army[ 'icon' ];
			$leader         = opt_armies_get_username_by_uid($army[ 'leader_uid' ]);
			$p_gid          = $army[ 'gid' ];
			$d_gid          = $army[ 'uugid' ];
			$hco_gid        = $army[ 'HCO_gid' ];
			$co_gid         = $army[ 'CO_gid' ];
			$displayorder   = $army[ 'displayorder' ];
			$is_locked      = $army[ 'is_locked' ];
			$is_invite_only = $army[ 'is_invite_only' ];
			$pm             = $army[ 'welcome_pm' ];
		}
		
		if ($mybb->request_method == 'post')
		{
			if (!verify_post_check($mybb->input[ 'my_post_key' ]))
			{
				$highlight = '';
				flash_message($lang->invalid_post_verify_key2, 'error');
			}
			else
			{
				// Check Post
				$shortcut       = $mybb->input[ 'shortcut' ];
				$name           = $mybb->input[ 'name' ];
				$nation         = $mybb->input[ 'nation' ];
				$icon           = $mybb->input[ 'icon' ];
				$leader         = $mybb->input[ 'username' ];
				$p_gid          = $mybb->input[ 'p_gid' ];
				$d_gid          = $mybb->input[ 'd_gid' ];
				$hco_gid        = $mybb->input[ 'hco_gid' ];
				$co_gid         = $mybb->input[ 'co_gid' ];
				$displayorder   = $mybb->input[ 'displayorder' ];
				$is_locked      = $mybb->input[ 'is_locked' ];
				$is_invite_only = $mybb->input[ 'is_invite_only' ];
				$pm             = $mybb->input[ 'pm' ];
				
				if (empty($shortcut))
				{
					$errors[] = $lang->opt_armies_error_no_army_shortcut;
				}
				if (empty($name))
				{
					$errors[] = $lang->opt_armies_error_no_army_name;
				}
				if (empty($leader))
				{
					$errors[] = $lang->opt_armies_error_no_leader;
				}
				if (empty($pm))
				{
					$errors[] = $lang->opt_armies_error_no_pm;
				}
				
				if ($errors)
				{
					$page->output_inline_error($errors);
				}
				else
				{
					$record     = array(
						"shortcut" => $db->escape_string($shortcut),
						"name" => $db->escape_string($name),
						"nation" => $db->escape_string($nation),
						"icon" => $db->escape_string($icon),
						"leader_uid" => opt_armies_get_uid_by_username($leader),
						"gid" => intval($p_gid),
						"uugid" => intval($d_gid),
						"displayorder" => $db->escape_string($displayorder),
						"is_locked" => intval($is_locked),
						"is_invite_only" => intval($is_invite_only),
						"welcome_pm" => $db->escape_string($pm)
					);
					$postrecord = array();
					if (intval($hco_gid) > 0)
					{
						$record[ 'HCO_gid' ] = intval($hco_gid);
					}
					else
					{
						$postrecord[ 'HCO_gid' ] = null;
					}
					if (intval($co_gid) > 0)
					{
						$record[ 'CO_gid' ] = intval($co_gid);
					}
					else
					{
						$postrecord[ 'CO_gid' ] = null;
					}
					if ($mybb->input[ 'action' ] == 'addarmy')
					{
						$db->insert_query('armies', $record);
						opt_armies_reorder_armies();
						flash_message($lang->opt_armies_army_added, 'success');
					}
					else
					{
						$db->update_query('armies', $record, "aid='" . $db->escape_string($aid) . "'");
						if (!empty($postrecord))
						{
							foreach ($postrecord as $key => $val)
							{
								$db->write_query('UPDATE ' . TABLE_PREFIX . 'armies SET ' . $db->escape_string($key) . ' = NULL WHERE aid=' . $db->escape_string($aid));
							}
						}
						flash_message($lang->opt_armies_army_edited, 'success');
					}
					
					opt_armies_reorder_armies();
					
					opt_armies_cache_armies();
					
					admin_redirect("index.php?module=config-opt_armies");
				}
			}
		}
		
		$usergroups = array();
		
		$query            = $db->simple_select("usergroups", "gid, title", "gid > '7'", array(
			'order_by' => 'title'
		));
		$usergroups[ '' ] = $lang->opt_armies_no_group_selected;
		while ($usergroup = $db->fetch_array($query))
		{
			$usergroups[ $usergroup[ 'gid' ] ] = $usergroup[ 'title' ];
		}
		if ($mybb->input[ 'action' ] == 'addarmy')
		{
			$usergroups = opt_armies_remove_assigned_groups($usergroups);
		}
		
		if ($mybb->input[ 'action' ] == 'addarmy')
		{
			$tabs[ 'opt_armies_add_army' ] = array(
				'title' => $lang->opt_armies_add_army,
				'link' => 'index.php?module=config-opt_armies&action=addarmy',
				'description' => $lang->opt_armies_add_army_description
			);
			$page->add_breadcrumb_item($lang->opt_armies_add_army, 'index.php?module=config-opt_armies&amp;action=addarmy');
			$page->output_header($lang->opt_armies_add_army);
			$page->output_nav_tabs($tabs, 'opt_armies_add_army');
			$form = new Form("index.php?module=config-opt_armies&amp;action=addarmy", "post");
		}
		else
		{
			$tabs[ 'opt_armies_edit_army' ] = array(
				'title' => $lang->opt_armies_edit_army,
				'link' => 'index.php?module=config-opt_armies&action=editrmy',
				'description' => $lang->opt_armies_edit_army_description
			);
			$page->add_breadcrumb_item($lang->opt_armies_edit_army, 'index.php?module=config-opt_armies&amp;action=editarmy');
			$page->output_header($lang->opt_armies_edit_army);
			$page->output_nav_tabs($tabs, 'opt_armies_edit_army');
			$form = new Form("index.php?module=config-opt_armies&amp;action=editarmy", "post");
		}
		
		$table = new Table;
		
		$table->construct_cell($lang->opt_armies_army_shortcut);
		$table->construct_cell('<input type="text" size="50" name="shortcut" value="' . $shortcut . '" />');
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_name);
		$table->construct_cell('<input type="text" size="50" name="name" value="' . $name . '" />');
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_nation);
		$table->construct_cell('<input type="text" size="50" name="nation" value="' . $nation . '" />');
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_icon);
		$table->construct_cell('<input type="text" size="150" name="icon" value="' . $icon . '" />' . opt_armies_build_image_url($icon));
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_leader);
		$table->construct_cell('<input type="text" size="50" name="username" value="' . $leader . '" />');
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_primary_group);
		$table->construct_cell($form->generate_select_box('p_gid', $usergroups, $p_gid, array(
			'id' => 'p_gid'
		)));
		$table->construct_row();
		
		
		$table->construct_cell($lang->opt_armies_army_hco_group);
		$table->construct_cell($form->generate_select_box('hco_gid', $usergroups, $hco_gid, array(
			'id' => 'hco_gid'
		)));
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_co_group);
		$table->construct_cell($form->generate_select_box('co_gid', $usergroups, $co_gid, array(
			'id' => 'co_gid'
		)));
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_default_group);
		$table->construct_cell($form->generate_select_box('d_gid', $usergroups, $d_gid, array(
			'id' => 'd_gid'
		)));
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_welcome_pm_template);
		$table->construct_cell('<textarea cols="80" rows="15" name="pm" id="pm">' . $pm . '</textarea>');
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_displayorder);
		$table->construct_cell('<input type="text" size="3" name="displayorder" value="' . $displayorder . '" />');
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_is_locked);
		$table->construct_cell($form->generate_check_box('is_locked', 1, $lang->opt_armies_army_is_locked_2, array(
			'checked' => $is_locked,
			'id' => 'is_locked'
		)));
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_is_invite_only);
		$table->construct_cell($form->generate_check_box('is_invite_only', 1, $lang->opt_armies_army_is_invite_only_2, array(
			'checked' => $is_invite_only,
			'id' => 'is_invite_only'
		)));
		$table->construct_row();
		
		if ($mybb->input[ 'action' ] == 'addarmy')
		{
			$table->construct_cell('<input type="submit" value="' . $lang->opt_armies_add_army . '" />', array(
				'colspan' => 2
			));
		}
		else
		{
			$table->construct_cell('<input type="hidden" name="aid" value="' . $aid . '" /><input type="submit" value="' . $lang->opt_armies_edit_army . '" />', array(
				'colspan' => 2
			));
		}
		$table->construct_row();
		
		$form->end;
		if ($mybb->input[ 'action' ] == 'addarmy')
		{
			$table->output($lang->opt_armies_add_army_description);
		}
		else
		{
			$table->output($lang->opt_armies_edit_army_description);
		}
		
		$page->output_footer();
	}
	
	// delete an army including all subgroups
	if ($mybb->input[ 'action' ] == 'deletearmy')
	{
		if (!verify_post_check($mybb->input[ 'my_post_key' ]))
		{
			$highlight = '';
			flash_message($lang->invalid_post_verify_key2, 'error');
		}
		else
		{
			// delete all subgroups
			$db->delete_query('armies_structures', 'aid=' . intval($mybb->input[ 'aid' ]));
			$db->delete_query('armies', 'aid=' . intval($mybb->input[ 'aid' ]));
		}
		admin_redirect("index.php?module=config-opt_armies");
	}
	
	// configure an armys structure
	if ($mybb->input[ 'action' ] == 'configarmy')
	{
		$tabs[ 'opt_armies_config_army' ]    = array(
			'title' => $lang->opt_armies_config_army,
			'link' => 'index.php?module=config-opt_armies&action=configarmy',
			'description' => $lang->opt_armies_config_army_description
		);
		$tabs[ 'opt_armies_add_army_group' ] = array(
			'title' => $lang->opt_armies_add_army_group,
			'link' => 'index.php?module=config-opt_armies&action=addgroup&aid=' . intval($mybb->input[ 'aid' ]),
			'description' => $lang->opt_armies_add_army_group_description
		);
		$page->add_breadcrumb_item($lang->opt_armies_config_army, 'index.php?module=config-opt_armies&amp;action=configarmy&aid=' . $mybb->input[ 'aid' ]);
		$page->output_header($lang->opt_armies_config_army);
		$page->output_nav_tabs($tabs, 'opt_armies_config_army');
		
		// show some details about the current army
		{
			$table = new Table;
			$table->construct_header($lang->opt_armies_army_shortcut);
			$table->construct_header($lang->opt_armies_army_name);
			$table->construct_header($lang->opt_armies_army_nation);
			$table->construct_header($lang->opt_armies_army_icon, array(
				'class' => 'align_center'
			));
			$table->construct_header($lang->opt_armies_army_leader);
			$table->construct_header($lang->opt_armies_army_primary_group);
			$table->construct_header($lang->opt_armies_army_hco_group);
			$table->construct_header($lang->opt_armies_army_co_group);
			$table->construct_header($lang->opt_armies_army_default_group);
			$table->construct_header($lang->options, array(
				'class' => 'align_center'
			));
			$query = $db->simple_select('armies', '*', 'aid=' . intval($mybb->input[ 'aid' ]));
			$army  = $db->fetch_array($query);
			
			$table->construct_cell($army[ 'shortcut' ]);
			$table->construct_cell($army[ 'name' ]);
			$table->construct_cell($army[ 'nation' ]);
			$table->construct_cell(opt_armies_build_image_url($army[ 'icon' ]), array(
				'class' => 'align_center'
			));
			$table->construct_cell(opt_armies_get_username_by_uid($army[ 'leader_uid' ]));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'gid' ]));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'HCO_gid' ], '-'));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'CO_gid' ], '-'));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'uugid' ]));
			
			$table->construct_cell('<a href="index.php?module=config-opt_armies&amp;action=editarmy&aid=' . $army[ 'aid' ] . '">' . $lang->opt_armies_edit_army . '</a>', array(
				'class' => 'align_center'
			));
			
			$table->construct_row();
			$table->output($lang->opt_armies_this_army);
		}
		
		$usergroups = $cache->read('usergroups');
		
		$form = new Form("index.php?module=config-opt_armies&amp;action=updategroups&aid=" . $mybb->input[ 'aid' ], "post");
		
		$table->construct_header($lang->opt_armies_group_name);
		$table->construct_header($lang->opt_armies_group_shortcut);
		$table->construct_header($lang->opt_armies_parent_group_name);
		$table->construct_header($lang->opt_armies_group_leader);
		$table->construct_header($lang->opt_armies_army_displayorder, array(
			'class' => 'align_center'
		));
		$table->construct_header($lang->options, array(
			'class' => 'align_center'
		));
		
		$query = $db->simple_select('armies_structures', '*', 'pagrid is NULL AND aid=' . intval($mybb->input[ 'aid' ]), array(
			'order_by' => 'displayorder',
			'order_dir' => 'ASC'
		));
		while ($group = $db->fetch_array($query))
		{
			// show primary groups
			opt_armies_show_group($mybb->input[ 'aid' ], $form, $table, $group[ 'agrid' ], $group[ 'leader_uid' ], $group[ 'displayorder' ], $group[ 'pagrid' ]);
		}
		if ($db->num_rows($query) == 0)
		{
			$table->construct_cell($lang->opt_armies_no_groups, array(
				'colspan' => '6',
				'class' => 'align_center'
			));
			$table->construct_row();
		}
		else
		{
			$table->construct_cell('<input type="submit" value="' . $lang->opt_armies_update_order . '" />', array(
				'colspan' => 6
			));
			$table->construct_row();
		}
		$db->free_result($query);
		$table->output($lang->opt_armies_configure_army_groups);
		
		$form->end;
		$page->output_footer();
	}
	
	// add or edit army group
	if ($mybb->input[ 'action' ] == 'addgroup' || $mybb->input[ 'action' ] == 'editgroup')
	{
		if ($mybb->input[ 'action' ] == 'addgroup')
		{
			$leader       = '';
			$gid          = '2';
			$pagrid       = '';
			$pgid         = '';
			$shortcut     = '';
			$displayorder = '10000';
			$aid          = $mybb->input[ 'aid' ];
		}
		else
		{
			$query = $db->simple_select('armies_structures', '*', 'agrid=' . intval($mybb->input[ 'agrid' ]));
			$data  = $db->fetch_array($query);
			$db->free_result($query);
			
			$leader       = opt_armies_get_username_by_uid($data[ 'leader_uid' ]);
			$gid          = $data[ 'gid' ];
			$shortcut     = $data[ 'shortcut' ];
			$aid          = $data[ 'aid' ];
			$displayorder = $data[ 'displayorder' ];
			$pagrid       = $data[ 'pagrid' ];
			
			$query = $db->simple_select('armies_structures', '*', 'agrid=' . intval($pagrid));
			$data  = $db->fetch_array($query);
			$db->free_result($query);
			
			$pgid = $data[ 'gid' ];
			
		}
		if ($mybb->request_method == 'post')
		{
			if (!verify_post_check($mybb->input[ 'my_post_key' ]))
			{
				$highlight = '';
				flash_message($lang->invalid_post_verify_key2, 'error');
			}
			else
			{
				if ($mybb->input[ 'submit' ] == $lang->cancel)
				{
					admin_redirect("index.php?module=config-opt_armies&action=configarmy&aid=" . $aid);
				}
				
				$pagrid       = null;
				$agrid        = $mybb->input[ 'agrid' ];
				$leader       = $mybb->input[ 'username' ];
				$leader_uid   = opt_armies_get_uid_by_username($leader);
				$pgid         = $mybb->input[ 'pgid' ];
				$gid          = $mybb->input[ 'gid' ];
				$shortcut     = $mybb->input[ 'shortcut' ];
				$displayorder = $mybb->input[ 'displayorder' ];
				
				if (empty($gid))
				{
					$errors[] = $lang->opt_armies_error_no_group_selected;
				}
				
				if ($pgid <> "thearmy")
				{
					$query  = $db->simple_select('armies_structures', 'agrid', 'gid=' . $mybb->input[ 'pgid' ]);
					$pagrid = $db->fetch_field($query, 'agrid');
					$db->free_result($query);
					
					if (empty($pagrid))
					{
						$errors[] = $lang->opt_armies_error_invalid_parent_group;
					}
				}
				if ($agrid > 0 && $agrid == $pagrid)
				{
					$errors[] = $lang->opt_armies_error_agrid_same_as_pagrid . $agrid . '=' . $pagrid;
				}
				
				if (empty($shortcut))
				{
					$errors[] = $lang->opt_armies_error_no_group_shortcut;
				}
				
				if ($leader <> '' && $leader_uid == null)
				{
					$placeholders = array(
						'username' => $leader
					);
					$errors[]     = opt_armies_fill_placeholders($lang->opt_armies_error_no_unknown_user, $placeholders);
				}
				if ($leader == '')
				{
					$errors[] = $lang->opt_armies_error_no_group_leader;
				}
				if (empty($displayorder))
				{
					$displayorder = '10000';
				}
				
				if ($errors)
				{
					$page->output_inline_error($errors);
				}
				else
				{
					//die("not yet implemented");
					$record     = array(
						"aid" => intval($aid),
						"gid" => intval($gid),
						"shortcut" => $db->escape_string($shortcut),
						"leader_uid" => $db->escape_string(opt_armies_get_uid_by_username($leader)),
						"displayorder" => intval($displayorder)
					);
					$postrecord = array();
					if (intval($pagrid) > 0)
					{
						$record[ 'pagrid' ] = intval($pagrid);
					}
					else
					{
						$postrecord[ 'pagrid' ] = null;
					}
					if ($mybb->input[ 'action' ] == 'addgroup')
					{
						$db->insert_query('armies_structures', $record);
						opt_armies_reorder_groups();
						flash_message($lang->opt_armies_group_added, 'success');
					}
					else
					{
						$db->update_query('armies_structures', $record, "agrid='" . $db->escape_string($agrid) . "'");
						if (!empty($postrecord))
						{
							foreach ($postrecord as $key => $val)
							{
								$db->write_query('UPDATE ' . TABLE_PREFIX . 'armies_structures SET ' . $db->escape_string($key) . ' = NULL WHERE agrid=' . $db->escape_string($agrid));
							}
						}
						flash_message($lang->opt_armies_group_edited, 'success');
					}
					
					opt_armies_reorder_groups($aid);
					
					opt_armies_cache_armies();
					
					admin_redirect("index.php?module=config-opt_armies&action=configarmy&aid=" . $aid);
				}
			}
		}
		if ($mybb->input[ 'action' ] == 'addgroup')
		{
			$tabs[ 'opt_armies_add_group' ] = array(
				'title' => $lang->opt_armies_add_group,
				'link' => 'index.php?module=config-opt_armies&action=addgroup&aid=' . $mybb->input[ 'aid' ],
				'description' => $lang->opt_armies_add_group_description
			);
			$page->add_breadcrumb_item($lang->opt_armies_add_group, 'index.php?module=config-opt_armies&amp;action=addgroup&aid=' . $mybb->input[ 'aid' ]);
			$page->output_header($lang->opt_armies_add_group);
			$page->output_nav_tabs($tabs, 'opt_armies_add_group');
			$form       = new Form("index.php?module=config-opt_armies&amp;action=addgroup", "post");
			$tabletitle = $lang->opt_armies_add_group_description;
		}
		else
		{
			$tabs[ 'opt_armies_edit_group' ] = array(
				'title' => $lang->opt_armies_edit_group,
				'link' => 'index.php?module=config-opt_armies&action=addgroup&agrid=' . $mybb->input[ 'agrid' ],
				'description' => $lang->opt_armies_edit_group_description
			);
			$page->add_breadcrumb_item($lang->opt_armies_edit_group, 'index.php?module=config-opt_armies&amp;action=editgroup&agrid=' . $mybb->input[ 'agrid' ]);
			$page->output_header($lang->opt_armies_edit_group);
			$page->output_nav_tabs($tabs, 'opt_armies_edit_group');
			$form       = new Form("index.php?module=config-opt_armies&amp;action=editgroup", "post");
			$tabletitle = $lang->opt_armies_edit_group_description;
		}
		
		$table = new Table;
		
		$usergroups = array();
		if ($gid == '')
		{
			$gid = 'thearmy';
		}
		if ($pgid == '')
		{
			$pgid = 'thearmy';
		}
		
		$query            = $db->simple_select("usergroups", "gid, title", "gid > '7'", array(
			'order_by' => 'title'
		));
		$usergroups[ '' ] = $lang->opt_armies_no_group_selected;
		while ($usergroup = $db->fetch_array($query))
		{
			$usergroups[ $usergroup[ 'gid' ] ] = $usergroup[ 'title' ];
		}
		$db->free_result($query);
		$usergroups = opt_armies_remove_assigned_groups($usergroups);
		if ($mybb->input[ 'action' ] == 'addgroup')
		{
			$usergroups = opt_armies_remove_assigned_groups_2($usergroups);
		}
		
		$table->construct_cell($lang->opt_armies_group_name);
		$table->construct_cell($form->generate_select_box('gid', $usergroups, $gid, array(
			'id' => 'gid'
		)));
		$table->construct_row();
		
		$usergroups              = array();
		$usergroups[ 'thearmy' ] = $lang->opt_armies_no_parent_group;
		// should use a custom select instead of this code...
		$query                   = $db->simple_select('armies_structures', '*', 'aid=' . intval($aid));
		while ($data = $db->fetch_array($query))
		{
			$usergroups[ $data[ 'gid' ] ] = opt_armies_get_groupname_by_gid($data[ 'gid' ]);
		}
		$db->free_result($query);
		// TODO: remove subgroups of the currently edited group to avoid loops
		
		$table->construct_cell($lang->opt_armies_parent_group_name);
		$table->construct_cell($form->generate_select_box('pgid', $usergroups, $pgid, array(
			'id' => 'pgid'
		)));
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_group_shortcut);
		$table->construct_cell('<input type="text" size="5" name="shortcut" value="' . $shortcut . '" />');
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_group_leader);
		$table->construct_cell('<input type="text" size="50" name="username" value="' . $leader . '" style="width: 20em" />');
		$table->construct_row();
		
		$table->construct_cell($lang->opt_armies_army_displayorder);
		$table->construct_cell('<input type="text" size="3" name="displayorder" value="' . $displayorder . '" />');
		$table->construct_row();
		
		if ($mybb->input[ 'action' ] == 'addgroup')
		{
			$table->construct_cell('<input type="hidden" name="aid" value="' . $aid . '" /><input type="submit" value="' . $lang->opt_armies_add_group . '" name="submit" /><input type="submit" value="' . $lang->cancel . '" name="submit" />', array(
				'colspan' => 2
			));
		}
		else
		{
			$table->construct_cell('<input type="hidden" name="aid" value="' . $aid . '" /><input type="hidden" name="agrid" value="' . $mybb->input[ 'agrid' ] . '" /><input type="submit" value="' . $lang->opt_armies_edit_group . '" />', array(
				'colspan' => 2
			));
		}
		$table->construct_row();
		
		$form->end;
		$table->output($tabletitle);
		
		$page->output_footer();
	}
	
	// update sort order of the armys' groups
	if ($mybb->input[ 'action' ] == 'updategroups')
	{
		if (!verify_post_check($mybb->input[ 'my_post_key' ]))
		{
			$highlight = '';
			flash_message($lang->invalid_post_verify_key2, 'error');
		}
		else
		{
			$groups = $_REQUEST[ 'group' ];
			foreach ($groups as $group => $key)
			{
				$key   = (int) $key;
				$group = (int) $group;
				
				$updated_record = array(
					"displayorder" => $db->escape_string($key)
				);
				$db->update_query('armies_structures', $updated_record, "agrid='" . $db->escape_string($group) . "'");
			}
			
			opt_armies_reorder_groups($mybb->input[ 'aid' ]);
			
			admin_redirect("index.php?module=config-opt_armies&action=configarmy&aid=" . $mybb->input[ 'aid' ]);
		}
	}
	
	// delete an army group including all subgroups
	if ($mybb->input[ 'action' ] == 'deletegroup')
	{
		if (!verify_post_check($mybb->input[ 'my_post_key' ]))
		{
			$highlight = '';
			flash_message($lang->invalid_post_verify_key2, 'error');
		}
		else
		{
			// die("not implemented");
			opt_armies_delete_group($mybb->input[ 'agrid' ]);
		}
		admin_redirect("index.php?module=config-opt_armies&action=configarmy&aid=" . $mybb->input[ 'aid' ]);
	}
	
	// configure an armys structure
	if ($mybb->input[ 'action' ] == 'adduser')
	{
		$aid      = intval($mybb->input[ 'aid' ]);
		$username = '';
		
		if ($mybb->request_method == 'post')
		{
			if (!verify_post_check($mybb->input[ 'my_post_key' ]))
			{
				$highlight = '';
				flash_message($lang->invalid_post_verify_key2, 'error');
			}
			else
			{
				$username = trim($mybb->input[ 'username' ]);
				if (empty($username))
				{
					flash_message($lang->opt_armies_error_no_username, 'error');
				}
				else
				{
					
					$uid = opt_armies_get_uid_by_username($username);
					
					$placeholders = array(
						'username' => $username
					);
					$message      = opt_armies_fill_placeholders($lang->opt_armies_error_no_unknown_user, $placeholders);
					if (empty($uid))
					{
						flash_message($message, 'error');
					}
					else
					{
						// validate user
						$user         = opt_armies_get_user_by_uid($uid);
						$users_groups = array_merge(explode(',', $user[ 'usergroup' ]), explode(',', $user[ 'additionalgroups' ]));
						
						$user_aid = opt_armies_get_aid_by_uid($uid);
						if ($user_aid <> -1)
						{
							$army         = opt_armies_get_army_by_aid($user_aid);
							$placeholders = array(
								'army_name' => $army[ 'name' ]
							);
							flash_message(opt_armies_fill_placeholders($lang->opt_armies_error_already_in_army, $placeholders), 'error');
						}
						else
						{
							$army = opt_armies_get_army_by_aid($aid);
							
							opt_armies_join_army($uid, $users_groups, $army);
							
							$placeholders = array(
								'username' => $username,
								'army_name' => $army[ 'name' ]
							);
							$message      = opt_armies_fill_placeholders($lang->opt_armies_user_added, $placeholders);
							flash_message($message, 'success');
							// admin_redirect("index.php?module=config-opt_armies");
							$username = ''; // clear username so we can add another user
						}
					}
				}
			}
		}
		$tabs[ 'opt_armies_add_user' ] = array(
			'title' => $lang->opt_armies_add_user,
			'link' => 'index.php?module=config-opt_armies&action=configarmy',
			'description' => $lang->opt_armies_add_user_description
		);
		$page->add_breadcrumb_item($lang->opt_armies_add_user, 'index.php?module=config-opt_armies&amp;action=adduser&aid=' . $mybb->input[ 'aid' ]);
		$page->output_header($lang->opt_armies_add_user);
		$page->output_nav_tabs($tabs, 'opt_armies_add_user');
		
		// show some details about the current army
		{
			$table = new Table;
			$table->construct_header($lang->opt_armies_army_shortcut);
			$table->construct_header($lang->opt_armies_army_name);
			$table->construct_header($lang->opt_armies_army_nation);
			$table->construct_header($lang->opt_armies_army_icon, array(
				'class' => 'align_center'
			));
			$table->construct_header($lang->opt_armies_army_leader);
			$table->construct_header($lang->opt_armies_army_primary_group);
			$table->construct_header($lang->opt_armies_army_hco_group);
			$table->construct_header($lang->opt_armies_army_co_group);
			$table->construct_header($lang->opt_armies_army_default_group);
			$table->construct_header($lang->options, array(
				'class' => 'align_center'
			));
			$query = $db->simple_select('armies', '*', 'aid=' . intval($mybb->input[ 'aid' ]));
			$army  = $db->fetch_array($query);
			
			$table->construct_cell($army[ 'shortcut' ]);
			$table->construct_cell($army[ 'name' ]);
			$table->construct_cell($army[ 'nation' ]);
			$table->construct_cell(opt_armies_build_image_url($army[ 'icon' ]), array(
				'class' => 'align_center'
			));
			$table->construct_cell(opt_armies_get_username_by_uid($army[ 'leader_uid' ]));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'gid' ]));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'HCO_gid' ], '-'));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'CO_gid' ], '-'));
			$table->construct_cell(opt_armies_get_groupname_by_gid($army[ 'uugid' ]));
			
			$table->construct_cell('<a href="index.php?module=config-opt_armies&amp;action=editarmy&aid=' . $army[ 'aid' ] . '">' . $lang->opt_armies_edit_army . '</a>', array(
				'class' => 'align_center'
			));
			
			$table->construct_row();
			$table->output($lang->opt_armies_this_army);
		}
		
		$usergroups = $cache->read('usergroups');
		
		$form = new Form("index.php?module=config-opt_armies&amp;action=adduser", "post");
		
		$table->construct_cell($lang->username);
		$table->construct_cell('<input type="text" size="50" name="username" value="' . $username . '" style="width: 20em" /><input type="hidden" name="aid" value="' . $aid . '" />');
		$table->construct_row();
		
		$table->construct_cell('<input type="submit" value="' . $lang->opt_armies_add_user . '" />', array(
			'colspan' => 6
		));
		$table->construct_row();
		
		$table->output($lang->opt_armies_add_user);
		
		$form->end;
		$page->output_footer();
	}
	
	// list (and edit) the army ranks
	if ($mybb->input[ 'action' ] == 'listranks')
	{
		if ($mybb->request_method == 'post')
		{
			if (!verify_post_check($mybb->input[ 'my_post_key' ]))
			{
				$highlight = '';
				flash_message($lang->invalid_post_verify_key2, 'error');
			}
			else
			{
				foreach($mybb->input['rank'] as $arid => $rank)
				{
					$rank_record=array(
						'arid' => intval($arid),
						'name' => $db->escape_string($rank['name']),
						'shortcut' => $db->escape_string($rank['shortcut']),
						'rcid' => intval($rank['rcid']),
						'icon' => $db->escape_string($rank['icon']),
						'displayorder' => intval($rank['displayorder'])
					);
					if ($arid<>'new')
					{
						$db->update_query(
							'armies_ranks',
							$rank_record,
							'arid='.intval($arid)
						);
						$db->delete_query(
							'armies_army_ranks',
							'arid='.intval($arid)
						);
						if(!empty($rank['army']))
						{
							foreach ($rank['army'] as $aid)
							{
								$new_record=array(
									'aid' => intval($aid),
									'arid' => intval($arid)
								);
								$db->insert_query(
									'armies_army_ranks',
									$new_record
								);
							}
						}
					}
					elseif (!empty($rank['name']))
					{
						$db->insert_query(
							'armies_ranks',
							$rank_record
						);
					}
					
				}
		
				opt_armies_update_rank_displayorder();
				flash_message($lang->opt_armies_ranks_updated, 'success');
				admin_redirect('index.php?module=config-opt_armies&action=listranks');
			}
		}

		$page->add_breadcrumb_item($lang->opt_armies_list_ranks, 'index.php?module=config-opt_armies&action=listranks');
		$page->output_header($lang->opt_armies_list_ranks);
		$page->output_nav_tabs($tabs, 'opt_armies_list_ranks');
		
		// load army data
		$query=$db->simple_select(
			'armies',
			'*',
			'',
			array(
				'order_by' => 'displayorder',
				'order_dir' => 'ASC'
			)
		);
		$armies=array();
		while($army=$db->fetch_array($query))
		{
			$armies[$army['aid']]=$army;
		}
		$db->free_result($query);
		
		// load army rank data
		$query=$db->simple_select(
			'armies_army_ranks',
			'*'
		);
		$army_ranks=array();
		while($army_rank=$db->fetch_array($query))
		{
			$army_ranks[$army_rank['aid']][$army_rank['arid']]=$army_rank;
		}
		$db->free_result($query);

		// rank class data
		$rankclasses = array(
			0 => $lang->opt_armies_rank_group_civilian,
			1 => $lang->opt_armies_rank_group_enlisted,
			2 => $lang->opt_armies_rank_group_officers,
			3 => $lang->opt_armies_rank_group_HCOs
		);

		
		$form = new Form("index.php?module=config-opt_armies&amp;action=listranks", "post");
		
		$table = new Table;

		$cols=7;
		$table->construct_header($lang->opt_armies_rank_name);
		$table->construct_header($lang->opt_armies_rank_shortcut, array(
			'class' => 'align_center'
		));
		$table->construct_header($lang->opt_armies_rank_class);
		$table->construct_header($lang->opt_armies_rank_icon, array(
			'class' => 'align_center'
		));
		$table->construct_header($lang->opt_armies_rank_icon_url);
		$table->construct_header($lang->opt_armies_army_displayorder, array(
			'class' => 'align_center'
		));
		foreach($armies as $army)
		{
			$cols++;
			$table->construct_header($army['name'], array(
				'class' => 'align_center'
			));
		}
		$table->construct_header($lang->options, array(
			'class' => 'align_center'
		));
		
		$query=$db->simple_select(
			'armies_ranks',
			'*',
			'',
			array(
				'order_by' => 'displayorder',
				'order_dir' => 'ASC'
			)
		);
		while($rank=$db->fetch_array($query))
		{
			opt_armies_list_ranks($rank['arid'], $rank, $armies, $army_ranks, $rankclasses, $table, $form);
		}
		opt_armies_list_ranks('new', array('displayorder' => 999), $armies, $army_ranks, $rankclasses, $table, $form);

		$table->construct_cell('<input type="submit" name="displayorder" value="' . $lang->opt_armies_update_ranks . '" />', array(
			'colspan' => $cols
		));
		$table->construct_row();

		$db->free_result($query);
		
		$table->construct_row();
		$table->output($lang->opt_armies_army_ranks);

		$form->end;
		$page->output_footer();
	}
	
	// delete an army group including all subgroups
	if ($mybb->input[ 'action' ] == 'deleterank')
	{
		if (!verify_post_check($mybb->input[ 'my_post_key' ]))
		{
			$highlight = '';
			flash_message($lang->invalid_post_verify_key2, 'error');
		}
		else
		{
			// die("not implemented");
			$db->delete_query(
				'armies_army_ranks',
				'arid='.intval($mybb->input[ 'arid' ])
			);
			$db->delete_query(
				'armies_ranks',
				'arid='.intval($mybb->input[ 'arid' ])
			);
			opt_armies_update_rank_displayorder();
		}
		admin_redirect("index.php?module=config-opt_armies&action=listranks");
	}

	else
	{
		$page->output_header($lang->opt_armies_title);
		$page->output_nav_tabs($tabs, 'opt_armies_add_army');
		
		$page->output_inline_error('undefined action');
		
		$page->output_footer();
	}
}

/* --- Hook #17 - misc.php?action=joinarmy --- */

$plugins->add_hook('misc_start', 'opt_armies_misc_start_17', 10);

function opt_armies_misc_start_17()
{
	global $db, $mybb, $lang, $templates, $headerinclude, $header, $footer, $theme, $cache;
	
	$lang->load('opt_armies');
	
	$uid = intval($mybb->user[ 'uid' ]);
	
	// select an army
	if ($mybb->input[ 'action' ] == 'selectarmy')
	{
		opt_armies_pre_join_checks($uid);
		
		add_breadcrumb($lang->opt_armies_select_army, "misc.php?action=selectarmy");
		
		// build page
		$armiesrows = '';
		$query      = $db->simple_select('armies', '*', '', array(
			'order_by' => 'displayorder',
			'order_dir' => 'ASC'
		));
		while ($army = $db->fetch_array($query))
		{
			$trow = alt_trow();
			if ($army[ 'icon' ] <> "")
			{
				$army_icon = opt_armies_build_image_url($army[ 'icon' ]);
			}
			else
			{
				$army_icon = $lang->opt_armies_no_icon;
			}
			$army_name   = $army[ 'name' ];
			$army_nation = $army[ 'nation' ];
			$army_leader = opt_armies_get_username_by_uid($army[ 'leader_uid' ]);
			if ($army[ 'is_invite_only' ] == 1)
			{
				$army_status  = $lang->opt_armies_army_status_invite_only;
				$army_options = $lang->opt_armies_select_army_option_none;
			}
			elseif ($mybb->settings[ 'opt_armies_random_join_only' ])
			{
				$army_status  = $lang->opt_armies_army_status_random;
				$army_options = $lang->opt_armies_select_army_option_none;
			}
			elseif ($army[ 'is_locked' ] == 1)
			{
				$army_status  = $lang->opt_armies_army_status_locked;
				$army_options = $lang->opt_armies_select_army_option_none;
			}
			elseif (opt_armies_is_army_temp_locked($army[ 'aid' ]))
			{
				$army_status  = $lang->opt_armies_army_status_temp_locked;
				$army_options = $lang->opt_armies_select_army_option_none;
			}
			else
			{
				$army_status     = $lang->opt_armies_army_status_open;
				$army_option     = $lang->opt_armies_select_army_option_join;
				$army_option_url = $mybb->settings[ 'bburl' ] . '/misc.php?action=joinarmy&aid=' . $army[ 'aid' ];
				eval("\$army_options = \"" . $templates->get("optarmies_select_army_list_row_option") . "\";");
			}
			eval("\$armiesrows .= \"" . $templates->get("optarmies_select_army_list_row") . "\";");
		}
		if ($db->num_rows($query) == 0)
		{
			eval("\$armiesrows .= \"" . $templates->get("optarmies_select_army_list_empty") . "\";");
		}
		else
		{
			$random_join_url = $mybb->settings[ 'bburl' ] . '/misc.php?action=randomarmy';
			eval("\$randomjoin .= \"" . $templates->get("optarmies_select_army_list_random") . "\";");
		}
		
		eval("\$content = \"" . $templates->get("optarmies_select_army_list") . "\";");
		
		eval("\$selectarmy = \"" . $templates->get("optarmies_misc_page") . "\";");
		output_page($selectarmy);
	}
	
	// join a random army
	if ($mybb->input[ 'action' ] == 'randomarmy')
	{
		$usergroups = opt_armies_pre_join_checks($uid);
		
		// which armies are currently open?
		$armies = array();
		$query  = $db->simple_select('armies', '*', 'is_locked=0 AND is_invite_only=0');
		while ($army = $db->fetch_array($query))
		{
			if (!opt_armies_is_army_temp_locked($army[ 'aid' ]))
			{
				$armies[] = $army;
			}
		}
		$db->free_result($query);
		
		if (empty($armies))
		{
			error($lang->opt_armies_all_armies_closed, $lang->opt_armies_title);
		}
		
		if ($mybb->request_method == 'post')
		{
			if (!verify_post_check($mybb->input[ 'my_post_key' ]))
			{
				$highlight = '';
				flash_message($lang->invalid_post_verify_key2, 'error');
			}
			else
			{
				// roll the dice!
				$army = $armies[ rand(0, count($armies) - 1) ];
				
				opt_armies_join_army($uid, $usergroups, $army);
				redirect("index.php", $lang->opt_armies_join_request_done);
			}
		}
		
		// join_army_form
		add_breadcrumb($lang->opt_armies_join_random_army, "misc.php?action=randomarmy");
		
		eval("\$content = \"" . $templates->get("optarmies_random_army_form") . "\";");
		
		eval("\$randomarmy = \"" . $templates->get("optarmies_misc_page") . "\";");
		output_page($randomarmy);
		
	}
	
	// join an army
	if ($mybb->input[ 'action' ] == 'joinarmy')
	{
		$usergroups = opt_armies_pre_join_checks($uid);
		
		if ($mybb->settings[ 'opt_armies_random_join_only' ] == 1)
		{
			error($lang->opt_armies_registration_random_only, $lang->opt_armies_title);
		}
		
		// gather data about the army to join
		$aid  = intval($mybb->input[ 'aid' ]);
		$army = opt_armies_get_army_by_aid($aid);
		
		if (opt_armies_is_army_temp_locked($army[ 'aid' ]))
		{
			$army = array();
		}
		
		if (empty($army))
		{
			error_no_permission();
		}
		
		if ($mybb->request_method == 'post')
		{
			if (!verify_post_check($mybb->input[ 'my_post_key' ]))
			{
				$highlight = '';
				flash_message($lang->invalid_post_verify_key2, 'error');
			}
			else
			{
				opt_armies_join_army($uid, $usergroups, $army);
				redirect("index.php", $lang->opt_armies_join_request_done);
			}
		}
		
		$placeholders                                 = array(
			'army_name' => $army[ 'name' ]
		);
		$lang->opt_armies_join_army                   = opt_armies_fill_placeholders($lang->opt_armies_join_army, $placeholders);
		$lang->opt_armies_join_army_confirmation_text = opt_armies_fill_placeholders($lang->opt_armies_join_army_confirmation_text, $placeholders);
		
		// join_army_form
		add_breadcrumb($lang->opt_armies_join_army, "misc.php?action=joinarmy&aid=" . $mybb->input[ 'aid' ]);
		
		eval("\$content = \"" . $templates->get("optarmies_join_army_form") . "\";");
		
		eval("\$joinarmy = \"" . $templates->get("optarmies_misc_page") . "\";");
		output_page($joinarmy);
		//die("not yet implemented");
	}
	
	// show armies, subgroups and soldiers
	if ($mybb->input[ 'action' ] == 'showarmies')
	{
		add_breadcrumb($lang->opt_armies_show_armies, "misc.php?action=showarmies");
		
		$army_layout  = '';
		$query_armies = $db->simple_select('armies', '*');
		while ($army = $db->fetch_array($query_armies))
		{
			opt_armies_fix_army($army[ 'aid' ]);
			$groupslead = opt_armies_get_army_groups_lead($uid, $army[ 'aid' ]);
	
			$army_icon     = opt_armies_build_image_url($army[ 'icon' ]);
			$army_leader   = opt_armies_nice_username($army[ 'leader_uid' ]);
			$army_nation   = $army[ 'nation' ];
			$army_name     = $army[ 'name' ];
			$army_shortcut = $army['shortcut'];
			$army_gid      = $army['gid'];
			$army_members  = count(opt_armies_get_groupmembers($army[ 'gid' ]));

			if (!empty($army[ 'HCO_gid' ]))
			{
				$army_members += count(opt_armies_get_groupmembers($army[ 'uugid' ]));
			}
		
			if ($army[ 'is_locked' ] == 1)
			{
				$army_status = $lang->opt_armies_army_is_locked;
			}
			elseif ($army[ 'is_invite_only' ] == 1)
			{
				$army_status = $lang->opt_armies_army_is_invite_only;
			}
			elseif (opt_armies_is_army_temp_locked($army[ 'aid' ]))
			{
				$army_status  = $lang->opt_armies_army_status_temp_locked;
				$army_options = $lang->opt_armies_select_army_option_none;
			}
			else
			{
				$army_status = $lang->opt_armies_army_is_open;
			}
			
			// build the group display
			$armygroups = '<br>';
			
			if (!empty($army[ 'HCO_gid' ]))
			{
				$shortcut = '-';
				$armygroups .= opt_armies_show_group3($army[ 'HCO_gid' ], $shortcut, $army[ 'leader_uid' ], '', $groupslead, $t, true, false);
				$armygroups .= '<br>';
			}
			
			if (!empty($army[ 'CO_gid' ]))
			{
				$shortcut = '-';
				$armygroups .= opt_armies_show_group3($army[ 'CO_gid' ], $shortcut, $army[ 'leader_uid' ], '', $groupslead, $t, true, false);
				$armygroups .= '<br>';
			}
			
			// recursively build all subgroups
			$query_groups = $db->simple_select('armies_structures', '*', 'pagrid IS NULL AND aid=' . $army[ 'aid' ], array(
				'order_by' => 'displayorder',
				'order_dir' => 'ASC'
			));
			$t            = 0;
			while ($group = $db->fetch_array($query_groups))
			{
				$t = 1 - $t;
				$armygroups .= opt_armies_show_group2($group[ 'agrid' ], $groupslead, $t);
			}
			$db->free_result($query_groups);
			$armygroups .= '<br>';
			
			// and finally (resulting on top) the default group
			$query_default = $db->simple_select('usergroups', '*', 'gid=' . $army[ 'uugid' ]);
			$defaultgroup  = $db->fetch_array($query_default);
			$db->free_result($query_default);
			
			$shortcut = '-';
			$armygroups .= opt_armies_show_group3($defaultgroup[ 'gid' ], $shortcut, $army[ 'leader_uid' ], '', $groupslead, $t, true, true);
			
			eval("\$army_layout .= \"" . $templates->get("optarmies_show_army") . "\";");
		}
		$db->free_result($query_armies);
		eval("\$content = \"" . $templates->get("optarmies_show_armies") . "\";");
		eval("\$showarmies = \"" . $templates->get("optarmies_misc_page") . "\";");
		output_page($showarmies);
	}
	
	// show armies, subgroups and soldiers
	if ($mybb->input[ 'action' ] == 'managegroup')
	{
		if ($mybb->request_method == 'post')
		{
			if (verify_post_check($mybb->input[ 'my_post_key' ]))
			{
				// opt_armies_mydump($mybb->input, 'POST values');
				
				// only privileged users may access this page so we'll take some time to figure all the permissions of the user
				$aid = $mybb->input[ 'aid' ];
				$gid = $mybb->input[ 'gid' ];
				
				// test some values
				if (empty($gid) || empty($aid))
				{
					error_no_permission();
				}
				
				// is the gid a group of the given army?
				$group = opt_armies_get_army_group_by_gid($gid);
				$aid2  = opt_armies_get_aid_by_gid($gid);
				if ($aid <> $aid2)
				{
					error_no_permission();
				}
				
				if (!empty($mybb->input[ 'cancel' ]))
				{
					redirect("misc.php?action=showarmies", $lang->opt_armies_cancel_manage);
				}
				
				// get more values based on the already known ones
				$army     = opt_armies_get_army_by_aid($aid);
				$hco_gid  = $army[ 'HCO_gid' ];
				$co_gid   = $army[ 'CO_gid' ];
				$main_gid = $army[ 'gid' ];
				$uu_gid   = $army[ 'uugid' ];
				
				$is_top_group = ($gid == $main_gid || $gid == $hco_gid || $gid == $co_gid || $gid == $uu_gid);
				
				$groupslead = opt_armies_get_army_groups_lead($uid, $aid);
				
				if (!empty($groupslead) && in_array($gid, $groupslead))
				{
					$errors = array();
					
					// Army Leader?
					$is_army_leader = ($uid == $army[ 'leader_uid' ]);
					
					// HCO?
					$is_hco = $is_army_leader || opt_armies_is_hco($uid, $army[ 'HCO_gid' ]);
					
					// super group leader?
					if (!$is_hco)
					{
						$is_super = in_array(opt_armies_get_parent_gid($gid), $groupslead);
					}
					else
					{
						$is_super = true; // HCOs are always super group leaders
					}
					
					// multi group leader
					$is_multi = (count($groupslead) > 1);
					
					// primary group leader?
					$is_primary = ($uid == $group[ 'leader_uid' ]);
					
					// assistant group leader? (always true, but for better readability...)
					$is_assistant = true;
					
					$is_hco_group          = ($gid == $army[ 'HCO_gid' ]);
					$is_co_group           = ($gid == $army[ 'CO_gid' ]);
					$is_member_group       = (!$is_hco_group && !$is_co_group);
					$is_hco_or_super_or_co = ($is_super || $is_primary || $is_hco);
					
					$usergroups = $cache->read('usergroups');
					$group_name = $usergroups[ $gid ][ 'title' ];
					
					// TODO: remove this section once coding is done
					// if ($aid==0)
					// {
					// opt_armies_mydump($army,'$army');
					// opt_armies_mydump($is_army_leader,'$is_army_leader');
					// opt_armies_mydump($is_hco,'$is_hco');
					// opt_armies_mydump($is_super,'$is_super');
					// opt_armies_mydump($is_multi,'$is_multi');
					// opt_armies_mydump($is_primary,'$is_primary');
					// opt_armies_mydump($is_top_group,'$is_top_group');
					// opt_armies_mydump($is_hco_group,'$is_hco_group');
					// opt_armies_mydump($is_co_group,'$is_co_group');
					// opt_armies_mydump($is_member_group,'$is_member_group');
					// opt_armies_mydump($is_hco_or_super_or_co,'$is_hco_or_super_or_co');
					// }
					
					// 1st: which button was pressed?
					if (!empty($mybb->input[ 'manageleader' ]))
					{
						if ($is_super && !$is_top_group)
						{
							if (empty($mybb->input[ 'makeleader' ]))
							{
								if (empty($co_gid))
								{
									$co_gid = $main_gid;
								}
								$officers          = opt_armies_get_groupmembers($co_gid);
								$group_leader      = $group[ 'leader_uid' ];
								$group_leader_name = opt_armies_get_username_by_uid($group_leader);
								$officers          = opt_armies_remove_array_value($group_leader, $officers);
								
								$officerlist = '';
								foreach ($officers as $officer)
								{
									$officer_name = opt_armies_get_username_by_uid($officer);
									$officerlist .= '<option value="' . $officer . '">' . $officer_name . '</option>';
								}
								
								eval("\$content = \"" . $templates->get("optarmies_manage_group_leader_form") . "\";");
							}
							else
							{
								// update army group
								$record = array(
									'leader_uid' => intval($mybb->input[ 'makeleader' ])
								);
								$db->update_query('armies_structures', $record, 'gid=' . intval($gid));
								// update groupleaders table
								// is there already an entry for this user/group combination?
								$query = $db->simple_select('groupleaders', '*', 'gid=' . intval($gid) . ' AND uid=' . intval($mybb->input[ 'makeleader' ]));
								if ($db->num_rows($query) == 0)
								{
									$record = array(
										'gid' => intval($gid),
										'uid' => intval($mybb->input[ 'makeleader' ])
									);
									$db->insert_query('groupleaders', $record);
								}
								$cache->update_groupleaders();
								
								$group_leader_name = opt_armies_get_username_by_uid($mybb->input[ 'makeleader' ]);
								$content           = 'makeleader ' . opt_armies_get_username_by_uid($mybb->input[ 'makeleader' ]);
								
								$placeholders = array(
									'username' => $group_leader_name,
									'groupname' => opt_armies_get_groupname_by_gid($gid)
								);
								$message      = opt_armies_fill_placeholders($lang->opt_armies_new_group_leader, $placeholders);
								redirect("misc.php?action=showarmies", $message);
							}
						}
						else
						{
							error_no_permission();
						}
					}
					elseif (!empty($mybb->input[ 'manageassistants' ]))
					{
						if (($is_super || $is_primary) && !$is_top_group)
						{
							// if(empty($mybb->input['manage_user']) )
							if ($mybb->input[ 'manageassistants' ] == $lang->opt_armies_manage_XOs)
							{
								if (empty($co_gid))
								{
									$co_gid = $main_gid;
								}
								$officers     = opt_armies_get_groupmembers($co_gid);
								$XOs          = opt_armies_get_groupleaders($gid);
								$group_leader = $group[ 'leader_uid' ];
								$officers     = array_unique(array_merge($officers, $XOs));
								$officers     = opt_armies_remove_array_value($group_leader, $officers);
								$officerlist  = opt_armies_build_member_list($officers, true, $XOs);
								eval("\$content = \"" . $templates->get("optarmies_manage_group_assistants_form") . "\";");
							}
							else
							{
								//error('makeassistants update not yet implemented',$lang->error);
								// remove all previous group leaders
								$db->delete_query('groupleaders', 'gid=' . intval($gid));
								// add the new group leaders
								$XOs   = $mybb->input[ 'manage_user' ];
								$XOs[] = $group[ 'leader_uid' ];
								foreach ($XOs as $as_uid)
								{
									$record = array(
										'gid' => intval($gid),
										'uid' => intval($as_uid)
									);
									$db->insert_query('groupleaders', $record);
								}
								$placeholders = array(
									'username' => $group_leader_name,
									'groupname' => opt_armies_get_groupname_by_gid($gid)
								);
								$message      = opt_armies_fill_placeholders($lang->opt_armies_group_assistants_updated, $placeholders);
								redirect("misc.php?action=showarmies", $message);
							}
						}
						else
						{
							error_no_permission();
						}
					}
					elseif (!empty($mybb->input[ 'managemembers' ]))
					{
						// we need to figure what member action
						$member_action = $mybb->input[ 'manage_member_action' ];
						if (!empty($mybb->input[ 'manage_all' ]))
						{
							$members = opt_armies_get_groupmembers($gid);
						}
						else
						{
							$members = $mybb->input[ 'manage_user' ];
						}
						if (empty($members))
						{
							error($lang->opt_armies_error_no_members_selected, $lang->error);
						}
						
						if ($member_action == "changerank")
						{
							$members    = opt_armies_remove_array_value($army[ 'leader_uid' ], $members);
							$rankgroups = array(
								0 => $lang->opt_armies_rank_group_civilian,
								1 => $lang->opt_armies_rank_group_enlisted,
								2 => $lang->opt_armies_rank_group_officers,
								3 => $lang->opt_armies_rank_group_HCOs
							);
							// TODO: implement rank system, for now it is hardcoded
							$maxrcid=1;
							$ranks      = array(
								1 => array(
									'name' => $rankgroups[ 1 ],
									'rankgroup' => 1
								)
							);
							if ($is_hco)
							{
								$maxrcid=2;
								$ranks[ 2 ] = array(
									'name' => $rankgroups[ 2 ],
									'rankgroup' => 2
								);
							}
							if ($is_army_leader)
							{
								$maxrcid=3;
								$ranks[ 3 ] = array(
									'name' => $rankgroups[ 3 ],
									'rankgroup' => 3
								);
							}
							else
							{
								$hcos = opt_armies_get_groupmembers($hco_gid);
								foreach ($hcos as $hco)
								{
									$members = opt_armies_remove_array_value($hco, $members);
								}
							}
							$query=$db->write_query('SELECT r.* FROM '.TABLE_PREFIX.'armies_ranks AS r JOIN '.TABLE_PREFIX.'armies_army_ranks as ar ON r.arid=ar.arid WHERE ar.aid='.intval($aid).' AND rcid<='.intval($maxrcid).' ORDER BY r.displayorder');
							$ranks      = array();
							while($rank=$db->fetch_array($query))
							{
								// mydump($rank, '$rank');
								$ranks[ $rank['arid'] ] = array(
									'name' => $rank['name'],
									'rankgroup' => $rank['rcid']
								);
							}
							// mydump($ranks, '$ranks');

							if (!$is_hco && !$is_army_leader)
							{
								$officers = opt_armies_get_groupmembers($co_gid);
								foreach ($officers as $officer)
								{
									$members = opt_armies_remove_array_value($officer, $members);
								}
							}
							if (empty($members))
							{
								error($lang->opt_armies_error_invalid_member_selection, $lang->error);
							}
							if (empty($mybb->input[ 'confirm' ]))
							{
								$memberlist = opt_armies_build_member_list($members, true, $members);
								$ranklist   = '';
								foreach ($ranks as $rank_id => $rank)
								{
									$ranklist .= '<option value="' . $rank_id . '">' . $rankgroups[ $rank[ 'rankgroup' ] ] . '/' . $rank[ 'name' ] . '</option>';
								}
								eval("\$content = \"" . $templates->get("optarmies_changerank_group_members_form") . "\";");
							}
							else
							{
								$army_groups = opt_armies_get_army_structure_groups_by_aid($aid);
								foreach ($members as $member)
								{
									$users_groups = opt_armies_get_usergroups($member);
									if (in_array($hco_gid, $users_groups))
									{
										$users_groups = opt_armies_remove_array_value($hco_gid, $users_groups);
										$users_groups = opt_armies_remove_array_value($co_gid, $users_groups);
										foreach ($army_groups as $group)
										{
											$users_groups = opt_armies_remove_array_value($group, $users_groups);
										}
										$member_is_hco = true;
									}
									else
									{
										$member_is_hco = false;
									}
									if (!$member_is_hco && in_array($co_gid, $users_groups))
									{
										$users_groups = opt_armies_remove_array_value($co_gid, $users_groups);
										$member_is_co = true;
									}
									else
									{
										$member_is_co = false;
									}
									if (!$member_is_hco && !$member_is_co)
									{
										$member_is_enlisted = true;
									}
									else
									{
										$member_is_enlisted = false;
									}
									if (in_array($uu_gid, $users_groups))
									{
										$errors[] = $lang->opt_armies_cannot_promote_recuits_directly . opt_armies_get_username_by_uid($member);
										// break;
									}
									// changing the rank group:
									switch ($ranks[ $mybb->input[ 'newrank' ] ][ 'rankgroup' ])
									{
										case 0:
											// they shall kick members to make them a civilian...
											error($lang->opt_armies_rank_group_civilian);
											break;
										case 1:
											// demotion an HCO always makes him a recruit (workaround for my bad DB design)
											if ($member_is_hco)
											{
												$users_groups   = opt_armies_remove_array_value($main_gid, $users_groups);
												$users_groups[] = $uu_gid;
												$errors[]       = $lang->opt_armies_demoting_HCO_workaround . opt_armies_get_username_by_uid($member);
												opt_armies_set_rank_recruit($member, $aid);
											}
											elseif ($member_is_enlisted)
											{
												// nothing to do
												opt_armies_set_rank($member, $mybb->input[ 'newrank']);
											}
											else
											{
												$tmp = opt_armies_remove_non_army_groups($aid, $users_groups);
												$tmp = opt_armies_remove_array_value($main_gid, $users_groups);
												$tmp = opt_armies_remove_array_value($co_gid, $users_groups);
												if (!empty($tmp))
												{
													$users_groups = opt_armies_remove_array_value($uu_gid, $users_groups);
												}
												else
												{
													$errors[]       = $lang->opt_armies_demoting_officer_workaround . opt_armies_get_username_by_uid($member);
													$users_groups   = opt_armies_remove_array_value($main_gid, $users_groups);
													$users_groups   = opt_armies_remove_array_value($co_gid, $users_groups);
													$users_groups[] = $uu_gid;
												}
												opt_armies_set_rank($member, $mybb->input[ 'newrank']);
											}
											break;
										case 2:
											// demotion an HCO always makes him a recruit (workaround for my bad DB design)
											if ($member_is_hco)
											{
												$users_groups   = opt_armies_remove_array_value($main_gid, $users_groups);
												$users_groups[] = $uu_gid;
												$errors[]       = $lang->opt_armies_demoting_HCO_workaround . opt_armies_get_username_by_uid($member);
												opt_armies_set_rank_recruit($member, $aid);
											}
											else
											{
												$tmp = opt_armies_remove_non_army_groups($aid, $users_groups);
												$tmp = opt_armies_remove_array_value($main_gid, $users_groups);
												$tmp = opt_armies_remove_array_value($hco_gid, $users_groups);
												$tmp = opt_armies_remove_array_value($co_gid, $users_groups);
												if (!empty($tmp))
												{
													$users_groups[] = $co_gid;
												}
												else
												{
													$errors[]       = $lang->opt_armies_promoting_enlisted_workaround . opt_armies_get_username_by_uid($member);
													$users_groups   = opt_armies_remove_array_value($main_gid, $users_groups);
													$users_groups   = opt_armies_remove_array_value($hco_gid, $users_groups);
													$users_groups   = opt_armies_remove_array_value($co_gid, $users_groups);
													$users_groups[] = $uu_gid;
												}
												opt_armies_set_rank($member, $mybb->input[ 'newrank']);
											}
											break;
										case 3:
											// remove from all structure groups
											$users_groups = opt_armies_remove_array_value($uu_gid, $users_groups);
											foreach ($army_groups as $group)
											{
												$users_groups = opt_armies_remove_array_value($group, $users_groups);
											}
											// add to HCO and CO group
											$users_groups[] = $hco_gid;
											$users_groups[] = $co_gid;
											$users_groups[] = $main_gid;
											opt_armies_set_rank($member, $mybb->input[ 'newrank']);
											break;
										default:
											error_no_permission();
									}
									opt_armies_update_usergroups($member, $users_groups);
								}
								$placeholders = array(
									'groupname' => $usergroups[ $gid ][ 'title' ]
								);
								$message      = opt_armies_fill_placeholders($lang->opt_armies_group_members_rank_changed, $placeholders);
								if (empty($errors))
								{
									redirect("misc.php?action=showarmies", $message);
								}
							}
						}
						elseif ($member_action == "transfer")
						{
							// primary group leader which leads other groups too only
							if ($is_multi && $is_hco_or_super_or_co && $is_member_group)
							{
								$usergroups = $cache->read('usergroups');
								if (empty($mybb->input[ 'confirm' ]))
								{
									$memberlist = opt_armies_build_member_list($members, true, $members);
									$grouplist  = '';
									if ($main_gid <> $uu_gid)
									{
										$groupslead = opt_armies_remove_array_value($main_gid, $groupslead);
									}
									$groupslead = opt_armies_remove_array_value($hco_gid, $groupslead);
									$groupslead = opt_armies_remove_array_value($co_gid, $groupslead);
									$groupslead = opt_armies_remove_array_value($gid, $groupslead);
									$groupslead = opt_armies_remove_array_value($uu_gid, $groupslead);
									foreach ($groupslead as $group)
									{
										$grouplist .= '<option value="' . $group . '">' . $usergroups[ $group ][ 'title' ] . '</option>';
									}
									eval("\$content = \"" . $templates->get("optarmies_transfer_group_members_form") . "\";");
								}
								else
								{
									foreach ($members as $member)
									{
										$primary_group = $mybb->input[ 'newgroup' ];
										$users_groups  = opt_armies_get_usergroups($member);
										$users_groups  = opt_armies_remove_array_value($gid, $users_groups);
										$users_groups  = opt_armies_remove_array_value($primary_group, $users_groups);
										if ($primary_group <> $uu_gid) // transfer member to non-default group -> add to main group
										{
											$users_groups[] = $main_gid;
										}
										if ($primary_group == $uu_gid) // transfer member to the default group -> remove from main group
										{
											$users_groups = opt_armies_remove_array_value($main_gid, $users_groups);
										}
										opt_armies_update_usergroups($member, $users_groups, $primary_group);
									}
									$placeholders = array(
										'groupname_old' => $usergroups[ $gid ][ 'title' ],
										'groupname_new' => $usergroups[ $primary_group ][ 'title' ]
									);
									$message      = opt_armies_fill_placeholders($lang->opt_armies_group_members_transfered, $placeholders);
									redirect("misc.php?action=showarmies", $message);
								}
							}
							else
							{
								error_no_permission();
							}
						}
						elseif ($member_action == "kick")
						{
							// HCOs only
							if ($is_super)
							{
								// the army leader can never be kicked from his army
								$members = opt_armies_remove_array_value($army[ 'leader_uid' ], $members);
								// only the army leader can kick HCOs
								if (!$is_army_leader)
								{
									$members2 = $members;
									foreach ($members2 as $member)
									{
										$users_groups = opt_armies_get_usergroups($member);
										if (in_array($hco_gid, $users_groups))
										{
											$members == opt_armies_remove_array_value($member, $members);
										}
									}
								}
								if (empty($members))
								{
									error($lang->opt_armies_error_invalid_member_selection, $lang->error);
								}
								if (empty($mybb->input[ 'confirm' ]))
								{
									$memberlist = opt_armies_build_member_list($members, true, $members);
									eval("\$content = \"" . $templates->get("optarmies_kick_group_members_form") . "\";");
								}
								else
								{
									foreach ($members as $member)
									{
										opt_armies_kick_army_member($aid, $member);
									}
									$placeholders = array(
										'armyname' => $army[ 'name' ]
									);
									$message      = opt_armies_fill_placeholders($lang->opt_armies_group_members_kicked, $placeholders);
									redirect("misc.php?action=showarmies", $message);
								}
							}
							else
							{
								error_no_permission();
							}
						}
						else
						{
							error($lang->opt_armies_error_unknown_member_action, $lang->error);
						}
					}
					else
					{
						error($lang->opt_armies_error_unknown_manage_action, $lang->error);
					}
					
					if (!empty($errors))
					{
						$errors = inline_error($errors);
						$content .= '<br><a href="misc.php?action=showarmies">' . $lang->opt_armies_return_to_showarmies . '</a>';
					}
					
					// build page
					add_breadcrumb($lang->opt_armies_manage_group_title, "misc.php?action=managegroup");
					
					$content .= "<br>";
					eval("\$showarmies = \"" . $templates->get("optarmies_misc_page") . "\";");
					output_page($showarmies);
				}
				else
				{
					error_no_permission();
				}
			}
			else
			{
				error($lang->invalid_post_verify_key2, $lang->error);
			}
		}
		else
		{
			error_no_permission();
		}
	}
}




// Plugin Helper Functions *********************************************************************************

function opt_armies_reorder_armies()
{
	global $db;
	
	$query = $db->simple_select('armies', '*', '', array(
		'order_by' => 'displayorder',
		'order_dir' => 'ASC'
	));
	
	$count = 1;
	while ($row = $db->fetch_array($query))
	{
		$updated_record = array(
			'displayorder' => intval($count)
		);
		$db->update_query('armies', $updated_record, 'aid=' . intval($row[ 'aid' ]));
		$count++;
	}
	$db->free_result($query);
}

function opt_armies_reorder_groups($aid)
{
	global $db;
	
	$query1 = $db->simple_select('armies_structures', 'pagrid', 'aid=' . intval($aid), array(
		'order_by' => 'displayorder',
		'order_dir' => 'ASC'
	));
	
	while ($row = $db->fetch_array($query1))
	{
		$pagrid = $row[ 'pagrid' ];
		if (empty($pagrid))
		{
			$where = 'pagrid IS NULL';
		}
		else
		{
			$where = 'pagrid="' . $pagrid . '"';
		}
		$query = $db->simple_select('armies_structures', '*', $where . ' AND aid=' . intval($aid), array(
			'order_by' => 'displayorder',
			'order_dir' => 'ASC'
		));
		
		$count = 1;
		while ($row = $db->fetch_array($query))
		{
			$updated_record = array(
				'displayorder' => intval($count)
			);
			$db->update_query('armies_structures', $updated_record, 'agrid=' . intval($row[ 'agrid' ]));
			$count++;
		}
		$db->free_result($query);
	}
	$db->free_result($query1);
}

function opt_armies_update_rank_displayorder()
{
	global $db;
	
	$query = $db->simple_select('armies_ranks', '*', '', array(
		'order_by' => 'displayorder',
		'order_dir' => 'ASC'
	));
	
	$count = 1;
	while ($row = $db->fetch_array($query))
	{
		$updated_record = array(
			'displayorder' => intval($count)
		);
		$db->update_query('armies_ranks', $updated_record, 'arid=' . intval($row[ 'arid' ]));
		$count++;
	}
	$db->free_result($query);
}

function opt_armies_cache_armies($clear = false)
{
	global $cache;
	if ($clear == true)
	{
		$cache->update('opt_armies', false);
	}
	else
	{
		global $db;
		$armies = array();
		$query  = $db->simple_select('armies', '*');
		while ($army = $db->fetch_array($query))
		{
			$armies[ $army[ 'aid' ] ] = array(
				'name' => $army[ 'name' ],
				'icon' => $army[ 'icon' ]
			);
		}
		$db->free_result($query);
		$cache->update('opt_armies', $armies);
	}
}

function opt_armies_get_army_group_name($agrid)
{
	global $db;
	
	$query = $db->simple_select('armies_structures', 'gid', 'agrid=' . intval($agrid));
	$gid   = $db->fetch_field($query, 'gid');
	$db->free_result($query);
	
	return opt_armies_get_groupname_by_gid($gid);
}

function opt_armies_get_army_group_shortcut($agrid)
{
	global $db;
	
	$query    = $db->simple_select('armies_structures', 'shortcut', 'agrid=' . intval($agrid));
	$shortcut = $db->fetch_field($query, 'shortcut');
	$db->free_result($query);
	
	return $shortcut;
}

function opt_armies_show_group($aid, $form, $table, $agrid, $leader_uid, $displayorder, $pagrid = null, $indent = 0)
{
	global $lang, $db, $mybb;
	
	$style = '';
	if ($pagrid <> null)
	{
		$table->construct_cell(opt_armies_get_army_group_name($agrid), array(
			'style' => 'padding-left: ' . $indent . 'px'
		));
		$table->construct_cell(opt_armies_get_army_group_shortcut($agrid), array(
			'style' => 'padding-left: ' . $indent . 'px'
		));
		$table->construct_cell(opt_armies_get_army_group_name($pagrid), array(
			'style' => 'padding-left: ' . $indent . 'px'
		));
	}
	else
	{
		$style = 'font-weight: bold; ';
		$table->construct_cell(opt_armies_get_army_group_name($agrid), array(
			'style' => $style
		));
		$table->construct_cell(opt_armies_get_army_group_shortcut($agrid), array(
			'style' => $style
		));
		$table->construct_cell($lang->opt_armies_no_parent_group, array(
			'style' => $style
		));
	}
	$table->construct_cell(opt_armies_get_username_by_uid($leader_uid), array(
		'style' => $style
	));
	$table->construct_cell($form->generate_text_box('group[' . $agrid . ']', $displayorder, array(
		'class' => 'align_center',
		'style' => 'width: 3em; ' . $style
	)), array(
		'class' => 'align_center'
	));
	$popup = new PopupMenu("group_{$agrid}", $lang->options);
	$popup->add_item($lang->opt_armies_edit_group, "index.php?module=config-opt_armies&amp;action=editgroup&agrid={$agrid}");
	$popup->add_item($lang->opt_armies_delete_group, "index.php?module=config-opt_armies&amp;action=deletegroup&editgroup&aid={$aid}&agrid={$agrid}&my_post_key=" . $mybb->post_code, 'return AdminCP.deleteConfirmation(this,\'' . $lang->opt_armies_delete_group_question . '\')');
	$table->construct_cell($popup->fetch(), array(
		'class' => 'align_center'
	));
	
	$table->construct_row();
	
	// show sub groups
	$query = $db->simple_select('armies_structures', '*', 'pagrid=' . intval($agrid), array(
		'order_by' => 'displayorder',
		'order_dir' => 'ASC'
	));
	while ($group = $db->fetch_array($query))
	{
		opt_armies_show_group($aid, $form, $table, $group[ 'agrid' ], $group[ 'leader_uid' ], $group[ 'displayorder' ], $agrid, $indent + 40);
	}
	$db->free_result($query);
}

function opt_armies_show_group2($agrid, $groupslead, $t = 1)
{
	global $db, $templates, $lang, $theme, $cache;
	
	// find all sub groups of this group
	$query = $db->simple_select('armies_structures', '*', 'pagrid=' . intval($agrid), array(
		'order_by' => 'displayorder',
		'order_dir' => 'ASC'
	));
	$t2    = $t;
	while ($group = $db->fetch_array($query))
	{
		$subgroups .= opt_armies_show_group2($group[ 'agrid' ], $groupslead, $t2 = 1 - $t2);
	}
	$db->free_result($query);
	
	if (!empty($subgroups))
	{
		eval("\$subgroups = \"" . $templates->get("optarmies_show_army_group_subgroups") . "\";");
	}
	
	// now generate our own output
	$query = $db->simple_select('armies_structures', '*', 'agrid=' . intval($agrid));
	$group = $db->fetch_array($query);
	$db->free_result($query);
	
	$shortcut = $group[ 'shortcut' ];
	
	return opt_armies_show_group3($group[ 'gid' ], $shortcut, $group[ 'leader_uid' ], $subgroups, $groupslead, $t, false, false);
}

function opt_armies_show_group3($gid, $shortcut, $leader_uid, $subgroups, $groupslead, $t = 1, $no_leader_menu = false, $no_transfer_menu = false)
{
	global $db, $cache, $templates, $lang, $theme, $mybb;
	
	$usergroups = $cache->read('usergroups');
	$uid        = $mybb->user[ 'uid' ];
	
	if (in_array($gid, $groupslead) || $uid == $leader_uid)
	{
	
		// it's me, I'm one of the groups leaders :D
		$I_am_leader = '[' . $lang->opt_armies_manage_group . ']';
		
		// current army's data
		$aid  = opt_armies_get_aid_by_gid($gid);
		$army = opt_armies_get_army_by_aid($aid);
		
		// Army Leader?
		$is_army_leader = ($uid == $army[ 'leader_uid' ]);
		
		// HCO?
		$is_hco = $is_army_leader || opt_armies_is_hco($uid, $army[ 'HCO_gid' ]);
		
		// super group leader?
		if (!$is_hco)
		{
			$is_super = in_array(opt_armies_get_parent_gid($gid), $groupslead);
		}
		else
		{
			$is_super = true; // HCOs are always super group leaders
		}
		
		// multi group leader
		$is_multi = (count($groupslead) > 1);
		
		// primary group leader?
		$is_primary = ($uid == $leader_uid);
		
		// assistant group leader? (always true, but for better readability...)
		$is_assistant = true;
		
		$is_hco_group          = ($gid == $army[ 'HCO_gid' ]);
		$is_co_group           = ($gid == $army[ 'CO_gid' ]);
		$is_member_group       = (!$is_hco_group && !$is_co_group);
		$is_hco_or_super_or_co = ($is_super || $is_primary || $is_hco);
		
		// can manage assistants?
		if ($is_hco_or_super_or_co && !$no_leader_menu)
		{
			$agrid = opt_armies_get_agrid_from_gid($gid);
			eval("\$manage_assistents_menu = \"" . $templates->get("optarmies_manage_assistents_menu") . "\";");
		}
		else
		{
			$manage_assistents_menu = '';
		}
		
		// can manage group leader?
		if (($is_hco || $is_super) && !$no_leader_menu)
		{
			eval("\$manage_leader_menu = \"" . $templates->get("optarmies_manage_leader_menu") . "\";");
		}
		else
		{
			$manage_leader_menu = '';
		}
		
		$manage_members = true;
		$manage_all     = ' (<input type="checkbox" name="manage_all" value="' . $gid . '" class="checkbox"> ' . $lang->opt_armies_manage_all . ')';
		
		if ($is_multi && $is_hco_or_super_or_co && $is_member_group)
		{
			eval("\$manage_member_menu_multi_leader = \"" . $templates->get("optarmies_manage_member_menu_multi_leaders") . "\";");
		}
		else
		{
			$manage_member_menu_multi_leader = '';
		}
		if ($is_hco)
		{
			eval("\$manage_member_menu_hco = \"" . $templates->get("optarmies_manage_member_menu_hco") . "\";");
		}
		if ($is_army_leader)
		{
			eval("\$manage_member_menu_army_leader = \"" . $templates->get("optarmies_manage_member_menu_army_leader") . "\";");
		}
		if (!$no_transfer_menu)
		{
			eval("\$manage_member_menu_changerank = \"" . $templates->get("optarmies_manage_member_menu_changerank") . "\";");
		}
		eval("\$manage_member_menu = \"" . $templates->get("optarmies_manage_member_menu_group_leaders") . "\";");
	}
	else
	{
		$I_am_leader            = '';
		$manage_members         = false;
		$manage_all             = '';
		$manage_leader_menu     = '';
		$manage_assistents_menu = '';
		$manage_member_menu     = '';
	}
	
	$group_name     = $usergroups[ $gid ][ 'title' ] . '(' . $gid . ')';
	$group_shortcut = $shortcut;
	$group_leader   = opt_armies_nice_username($leader_uid);
	$group_XOs_uids = opt_armies_get_groupleaders($gid);
	unset($group_XOs_uids[ $leader_uid ]);
	if (empty($group_XOs_uids))
	{
		$group_XOs = '-';
	}
	else
	{
		$group_XOs = opt_armies_build_member_list($group_XOs_uids);
	}
	$group_members_uids = opt_armies_get_groupmembers($gid);
	if (empty($group_members_uids))
	{
		$group_members = '-';
	}
	else
	{
		$group_members = opt_armies_build_member_list($group_members_uids, $manage_members);
	}
	$trow = 'trow' . (1 + $t);
	eval("\$armygroups = \"" . $templates->get("optarmies_show_army_group") . "\";");
	return $armygroups;
}

function opt_armies_remove_assigned_groups($usergroups)
{
	global $db;
	$query = $db->simple_select('armies', '*');
	while ($army = $db->fetch_array($query))
	{
		if ($army[ 'gid' ] <> '' && array_key_exists($army[ 'gid' ], $usergroups))
		{
			unset($usergroups[ $army[ 'gid' ] ]);
		}
		if ($army[ 'uugid' ] <> '' && array_key_exists($army[ 'uugid' ], $usergroups))
		{
			unset($usergroups[ $army[ 'uugid' ] ]);
		}
		if ($army[ 'HCO_gid' ] <> '' && array_key_exists($army[ 'HCO_gid' ], $usergroups))
		{
			unset($usergroups[ $army[ 'HCO_gid' ] ]);
		}
		if ($army[ 'CO_gid' ] <> '' && array_key_exists($army[ 'CO_gid' ], $usergroups))
		{
			unset($usergroups[ $army[ 'CO_gid' ] ]);
		}
	}
	return $usergroups;
}

function opt_armies_remove_assigned_groups_2($usergroups)
{
	global $db;
	$query = $db->simple_select('armies_structures', '*');
	while ($data = $db->fetch_array($query))
	{
		if ($data[ 'gid' ] <> '' && array_key_exists($data[ 'gid' ], $usergroups))
		{
			unset($usergroups[ $data[ 'gid' ] ]);
		}
		$gid = opt_armies_get_gid_from_agrid($data[ 'pagrid' ]);
		if ($data[ 'gid' ] <> '' && array_key_exists($data[ 'gid' ], $usergroups))
		{
			unset($usergroups[ $data[ 'gid' ] ]);
		}
	}
	return $usergroups;
}

function opt_armies_get_gid_from_agrid($agrid)
{
	global $db;
	
	$query = $db->simple_select('armies_structures', 'gid', 'agrid=' . intval($agrid));
	$gid   = $db->fetch_field($query, 'gid');
	$db->free_result($query);
	
	return $gid;
}

function opt_armies_get_agrid_from_gid($gid)
{
	global $db;
	
	$query = $db->simple_select('armies_structures', 'agrid', 'gid=' . intval($gid));
	$agrid = $db->fetch_field($query, 'agrid');
	$db->free_result($query);
	
	return $agrid;
}

function opt_armies_get_army_group_by_gid($gid)
{
	global $db;
	
	$query = $db->simple_select('armies_structures', '*', 'gid=' . intval($gid));
	$group = $db->fetch_array($query);
	$db->free_result($query);
	return $group;
}

function opt_armies_delete_group($agrid)
{
	global $db;
	
	// delete all sub groups first
	$query = $db->simple_select('armies_structures', 'agrid', 'pagrid=' . intval($agrid));
	while ($sagrid = $db->fetch_field($query, 'agrid'))
	{
		opt_armies_delete_group($sagrid);
	}
	$db->free_result($query);
	
	// now delete this group
	$db->delete_query('armies_structures', 'agrid=' . intval($agrid));
}

function opt_armies_get_aid_by_uid($uid)
{
	global $db;
	
	$aid        = -1; // not in an army
	$usergroups = opt_armies_get_usergroups($uid);
	$query      = $db->simple_select('armies', '*');
	while ($army = $db->fetch_array($query))
	{
		if (in_array($army[ 'gid' ], $usergroups))
		{
			$aid = $army[ 'aid' ];
			break;
		}
	}
	$db->free_result($query);
	if ($aid==-1)
	{
		$query=$db->simple_select(
			'armies'
		);
		while($army=$db->fetch_array($query))
		{
			if (in_array($army['gid'],$usergroups))
			{
				$aid=$army['aid'];
			}
			if (!empty($army['hco_gid']) && in_array($army['hco_gid'],$usergroups))
			{
				$aid=$army['aid'];
			}
			if (!empty($army['co_gid']) && in_array($army['co_gid'],$usergroups))
			{
				$aid=$army['aid'];
			}
			if (in_array($army['uugid'],$usergroups))
			{
				$aid=$army['aid'];
			}
		}
		$db->free_result($query);
	}
	
	return $aid;
}

function opt_armies_get_army_by_aid($aid)
{
	global $db;
	
	$query  = $db->simple_select('armies', '*', 'aid=' . intval($aid));
	$result = $db->fetch_array($query);
	$db->free_result($query);
	return $result;
}

function opt_armies_build_image_url($url, $class = "armyicon")
{
	global $mybb, $theme;
	
	$finalIconUrl = $url;
	$finalIconUrl = str_replace('$mybburl', $mybb->settings[ 'bburl' ], $finalIconUrl);
	$finalIconUrl = str_replace('$imgdir', $theme[ 'imgdir' ], $finalIconUrl);
	
	if (!empty($url))
	{
		$result = '<img src="' . $finalIconUrl . '" class="' . $class . '" />';
	}
	else
	{
		$result = '';
	}
	
	return $result;
}

function opt_armies_build_member_list($uids = array(), $can_manage = false, $selected = array())
{
	global $db, $templates;
	
	$memberlist = '';
	foreach ($uids as $uid)
	{
		$army_member = opt_armies_nice_username($uid);
		if ($can_manage)
		{
			if (in_array($uid, $selected))
			{
				$checked = 'checked="checked"';
			}
			else
			{
				$checked = '';
			}
			$army_member = '<input type="checkbox" name="manage_user[]" value="' . $uid . '" class="checkbox" ' . $checked . '"> ' . $army_member;
		}
		eval("\$army_members .= \"" . $templates->get("optarmies_show_army_group_member_list_entry") . "\";");
	}
	
	eval("\$memberlist = \"" . $templates->get("optarmies_show_army_group_member_list") . "\";");
	
	return $memberlist;
}

function opt_armies_nice_username($uid)
{
	$user_data = opt_armies_get_user_by_uid($uid);
	$user      = build_profile_link(format_name($user_data[ 'username' ], $user_data[ 'usergroup' ]), $uid);
	
	return $user;
}

function opt_armies_is_army_temp_locked($aid)
{
	global $db, $mybb;
	
	// get the members of all currently opened armies
	$armies      = array();
	$min_members = 10000;
	$query       = $db->simple_select('armies', '*', 'is_locked = 0 AND is_invite_only = 0');
	while ($army = $db->fetch_array($query))
	{
		$army_members  = count(opt_armies_get_groupmembers($army[ 'gid' ]));
		if (!empty($army[ 'HCO_gid' ]))
		{
			$army_members += count(opt_armies_get_groupmembers($army[ 'uugid' ]));
		}
		$army['members'] = $army_members;
		$armies[ $army[ 'aid' ] ] = $army;
		if ($army[ 'members' ] < $min_members)
		{
			$min_members = $army[ 'members' ];
		}
	}
	$db->free_result($query);
	
	if ($armies[ $aid ][ 'members' ] > $min_members + $mybb->settings[ 'opt_armies_max_member_difference' ])
	{
		return true;
	}
	else
	{
		return false;
	}
}

function opt_armies_pre_join_checks($uid)
{
	global $mybb, $lang;
	
	if ($mybb->settings[ 'opt_armies_registration_open' ] == 0)
	{
		error($lang->opt_armies_registration_closed, $lang->opt_armies_title);
	}
	
	// gather some data about the user accessing this page
	$usergroups = opt_armies_get_usergroups($uid);
	
	// requires "Registered User" group membership
	if (!in_array(2, opt_armies_get_usergroups($mybb->user[ 'uid' ])))
	{
		error_no_permission();
	}
	
	$user_aid = opt_armies_get_aid_by_uid($uid);
	if ($user_aid <> -1)
	{
		$army         = opt_armies_get_army_by_aid($user_aid);
		$placeholders = array(
			'army_name' => $army[ 'name' ]
		);
		error(opt_armies_fill_placeholders($lang->opt_armies_error_already_in_army, $placeholders), $lang->error);
	}
	
	return $usergroups;
}

function opt_armies_join_army($uid, $usergroups, $army)
{
	global $mybb, $db, $lang;
	
	$army_name = $army[ 'name' ];
	$gid       = $army[ 'gid' ];
	$uugid     = $army[ 'uugid' ];
	
	if ($mybb->input[ 'cancel' ] == $lang->opt_armies_cancel_join_request)
	{
		redirect("misc.php?action=selectarmy", $lang->opt_armies_join_request_canceled);
	}
	// add user to the default group of the army (but not to the primary one to prevent spying)
	
	// $usergroups[]=$uugid;
	$usergroups = opt_armies_remove_array_value($gid, $usergroups);
	$usergroups = array_unique($usergroups);
	
	opt_armies_update_usergroups($uid, $usergroups, $uugid);
	opt_armies_set_rank_recruit($uid, $army['aid']);
	
	$placeholders                       = array(
		'army_name' => $army_name,
		'username' => opt_armies_get_username_by_uid($uid),
		'army_leader' => opt_armies_get_username_by_uid($army[ 'leader_uid' ])
	);
	$lang->opt_armies_join_request_done = opt_armies_fill_placeholders($lang->opt_armies_join_request_done, $placeholders);
	
	$message = opt_armies_fill_placeholders($army[ 'welcome_pm' ], $placeholders);
	opt_armies_send_pm($uid, $army[ 'leader_uid' ], $lang->opt_armies_welcome_pm_subject, $message);
}

function opt_armies_get_subgroups($agrid)
{
	global $db;
	
	$subgroups = array();
	
	$query = $db->simple_select('armies_structures', '*', 'pagrid=' . intval($agrid));
	while ($group = $db->fetch_array($query))
	{
		$subgroups = opt_armies_get_subgroups($group[ 'agrid' ]);
	}
	$db->free_result($query);
	
	$subgroups = array_merge($subgroups, array(
		opt_armies_get_gid_from_agrid($agrid)
	));
	
	return $subgroups;
}

function opt_armies_remove_non_army_groups($aid, $groups)
{
	global $db;
	
	// get all army groups
	$armygroups = array();
	$query      = $db->simple_select('armies_structures', 'gid', 'aid=' . intval($aid));
	while ($gid = $db->fetch_field($query, 'gid'))
	{
		$armygroups[ $gid ] = $gid;
	}
	$db->free_result($query);
	$query = $db->simple_select('armies', '*', 'aid=' . intval($aid));
	$army  = $db->fetch_array($query);
	$db->free_result($query);
	$armygroups[ $army[ 'gid' ] ]   = $army[ 'gid' ];
	$armygroups[ $army[ 'uugid' ] ] = $army[ 'uugid' ];
	
	if (!empty($army[ 'HCO_gid' ]))
	{
		$armygroups[ $army[ 'HCO_gid' ] ] = $army[ 'HCO_gid' ];
	}
	
	if (!empty($army[ 'CO_gid' ]))
	{
		$armygroups[ $army[ 'CO_gid' ] ] = $army[ 'CO_gid' ];
	}
	
	$armygroups_out = array();
	foreach ($groups as $group)
	{
		if (in_array($group, $armygroups))
		{
			$armygroups_out[] = $group;
		}
	}
	return $armygroups_out;
}

function opt_armies_is_hco($uid, $hco_gid)
{
	// get HCO group
	$usergroups = opt_armies_get_usergroups($uid);
	
	return in_array($hco_gid, $usergroups);
}

function opt_armies_get_aid_by_gid($gid)
{
	global $db;
	
	$query = $db->simple_select('armies_structures', 'aid', 'gid=' . intval($gid));
	$aid   = $db->fetch_field($query, 'aid');
	$db->free_result($query);
	
	if (empty($aid))
	{
		$query = $db->simple_select('armies', '*');
		while ($army = $db->fetch_array($query))
		{
			if ($army[ 'gid' ] == $gid)
			{
				$aid = $army[ 'aid' ];
				break;
			}
			elseif ($army[ 'uugid' ] == $gid)
			{
				$aid = $army[ 'aid' ];
				break;
			}
			elseif ($army[ 'HCO_gid' ] == $gid)
			{
				$aid = $army[ 'aid' ];
				break;
			}
			elseif ($army[ 'CO_gid' ] == $gid)
			{
				$aid = $army[ 'aid' ];
				break;
			}
			else
			{
				$aid = 0;
			}
		}
	}
	
	return $aid;
}

function opt_armies_get_parent_gid($gid)
{
	global $db;
	
	$query  = $db->simple_select('armies_structures', 'pagrid', 'gid=' . intval($gid));
	$pagrid = $db->fetch_field($query, 'pagrid');
	$db->free_result($query);
	
	return opt_armies_get_gid_from_agrid($pagrid);
}

function opt_armies_get_army_groups_lead($uid, $aid)
{
	global $db;
	
	// get army data
	$army = opt_armies_get_army_by_aid($aid);
	
	if ($uid == $army[ 'leader_uid' ])
	{
		// army leaders are special, they can always manage everyone in their armies
		$groupslead_army = array(
			$army[ 'gid' ],
			$army[ 'uugid' ]
		);
		if (!empty($army[ 'HCO_gid' ]))
		{
			$groupslead_army[] = $army[ 'HCO_gid' ];
		}
		if (!empty($army[ 'CO_gid' ]))
		{
			$groupslead_army[] = $army[ 'CO_gid' ];
		}
		$groupslead_groups = array();
		$query             = $db->simple_select('armies_structures', '*', 'aid=' . intval($army[ 'aid' ]));
		while ($group = $db->fetch_array($query))
		{
			$groupslead_groups[] = $group[ 'gid' ];
		}
		$db->free_result($query);
		$groupslead = array_unique(array_merge($groupslead_army, $groupslead_groups));
	}
	elseif (!empty($army[ 'HCO_gid' ]) && opt_armies_is_hco($uid, $army[ 'HCO_gid' ]))
	{
		$groupslead = array(
			$army[ 'uugid' ]
		);
		if (!empty($army[ 'CO_gid' ]))
		{
			$groupslead = array_merge($groupslead, array(
				$army[ 'CO_gid' ]
			));
		}
		
		// add all regular army groups
		$groupslead_groups = array();
		$query             = $db->simple_select('armies_structures', '*', 'aid=' . intval($army[ 'aid' ]));
		while ($group = $db->fetch_array($query))
		{
			$groupslead_groups[] = $group[ 'gid' ];
		}
		$db->free_result($query);
		$groupslead = array_unique(array_merge($groupslead, $groupslead_groups));
	}
	else
	{
		// non-army leaders but maybe still a leader for some (or all) army groups
		// get all forum groups lead
		$groupslead = opt_armies_get_groupslead($uid);
		
		// get rid of all non-army groups
		$groupslead = opt_armies_remove_non_army_groups($aid, $groupslead);
		
		// add subgroups of the army groups leaded
		$groupslead2 = $groupslead;
		foreach ($groupslead2 as $gid)
		{
			$agrid = opt_armies_get_agrid_from_gid($gid);
			if (!empty($agrid))
			{
				$groupslead = array_merge($groupslead, opt_armies_get_subgroups($agrid));
			}
		}
		
		$groupslead = array_unique($groupslead);
	}
	
	return $groupslead;
}

function opt_armies_kick_army_member($aid, $uid)
{
	global $db;
	
	$users_groups = opt_armies_get_usergroups($uid);
	$army_groups  = opt_armies_remove_non_army_groups($aid, $users_groups);
	foreach ($army_groups as $army_group)
	{
		$users_groups = opt_armies_remove_array_value($army_group, $users_groups);
	}
	
	opt_armies_update_usergroups($uid, $users_groups);
}

function opt_armies_remove_array_value($value, $array)
{
	$t1 = array_search($value, $array);
	if ($t1 !== false)
	{
		unset($array[ $t1 ]);
	}
	return $array;
}

function opt_armies_get_army_structure_groups_by_aid($aid)
{
	global $db;
	
	$query  = $db->simple_select('armies_structures', 'gid', 'aid=' . intval($aid), array(
		'order_by' => 'displayorder',
		'order_dir' => 'ASC'
	));
	$groups = array();
	while ($gid = $db->fetch_field($query, 'gid'))
	{
		$groups[ $gid ] = $gid;
	}
	return $groups;
}

function opt_armies_fix_army($aid)
{
	global $db;
	
	$army = opt_armies_get_army_by_aid($aid);
	
	$main_gid = $army[ 'gid' ];
	$hco_gid  = $army[ 'HCO_gid' ];
	$co_gid   = $army[ 'CO_gid' ];
	$uu_gid   = $army[ 'uugid' ];
	
	if ($main_gid <> $uu_gid)
	{
		$army_groups = opt_armies_get_army_structure_groups_by_aid($aid);
		
		// test if all army members are in at least one structure group, including the hco and default group. Also make sure the are in only ONE structure group
		$army_groups2 = $army_groups;
		if (!empty($hco_gid))
		{
			$army_groups2[] = $hco_gid;
		}
		$army_groups2[] = $uu_gid;
		$members        = opt_armies_get_groupmembers($main_gid);
		foreach ($members as $member)
		{
			$ok           = false;
			$count        = 0;
			$users_groups = opt_armies_get_usergroups($member);
			foreach ($users_groups as $group)
			{
				if (in_array($group, $army_groups2))
				{
					$ok = true;
					$count++;
				}
			}
			if (!$ok)
			{
				// make member a Recruit
				$users_groups = opt_armies_remove_array_value($main_gid, $users_groups);
				opt_armies_update_usergroups($member, $users_groups, $uu_gid);
			}
			elseif ($count > 1)
			{
				// keep the highest priority group
				$tmp  = opt_armies_remove_non_army_groups($aid, $users_groups);
				$tmp2 = opt_armies_remove_array_value($main_gid, $tmp);
				$tmp2 = opt_armies_remove_array_value($co_gid, $tmp2);
				$u2   = array();
				$u2[] = array_shift($tmp2);
				if (in_array($main_gid, $tmp))
				{
					$u2[] = $main_gid;
				}
				if (in_array($co_gid, $tmp))
				{
					$u2[] = $co_gid;
				}
				foreach ($users_groups as $group)
				{
					if (!in_array($group, $tmp))
					{
						$u2[] = $group;
					}
				}
				opt_armies_update_usergroups($member, $u2);
			}
		}
		
		// now test all army structures member (including the HCO and CO group) if they are part of the main group
		$army_groups2 = $army_groups;
		if (!empty($hco_gid))
		{
			$army_groups2[] = $hco_gid;
		}
		if (!empty($co_gid))
		{
			$army_groups2[] = $co_gid;
		}
		foreach ($army_groups2 as $group)
		{
			$members = opt_armies_get_groupmembers($group);
			foreach ($members as $member)
			{
				$ok           = false;
				$users_groups = opt_armies_get_usergroups($member);
				if (in_array($main_gid, $users_groups))
				{
					$ok = true;
				}
				if (!$ok)
				{
					$users_groups[] = $main_gid;
					opt_armies_update_usergroups($member, $users_groups);
				}
			}
		}
	}
}

function opt_armies_list_ranks($arid, $rank, $armies, $army_ranks, $rankclasses, $table, $form)
{
	global $db, $lang, $mybb;

	$table->construct_cell('<input type="text" size="40" name="rank['. $arid. '][name]" value="' . $rank['name'] . '" />');
	$table->construct_cell('<input type="text" size="6" name="rank['. $arid. '][shortcut]" value="' . $rank['shortcut'] . '" class="align_center" />', array(
		'class' => 'align_center'
	));
	$table->construct_cell($form->generate_select_box('rank['. $arid. '][rcid]', $rankclasses, $rank['rcid'], array(
		'id' => 'p_gid'
	)));
	$table->construct_cell('<img src="'.$rank['icon'].'" style="max-width: 75px; max-height: 75px;" />', array(
		'class' => 'align_center'
	));
	$table->construct_cell('<input type="text" size="80" name="rank['. $arid. '][icon]" value="' . $rank['icon'] . '" />');
	$table->construct_cell('<input type="text" size="3" name="rank['. $arid. '][displayorder]" value="' . $rank['displayorder'] . '" class="align_center" />', array(
		'class' => 'align_center'
	));
	foreach($armies as $army)
	{
		if (!empty($army_ranks[$army['aid']][$arid]))
		{
			$active=1;
		}
		else
		{
			$active=0;
		}
		if ($arid<>'new')
		{
			$table->construct_cell(
				$form->generate_check_box(
					'rank['. $arid . '][army]['. $army['aid']. ']', 
					$army['aid'], 
					'', 
					array(
						'checked' => $active
					)
				), 
				array(
					'class' => 'align_center'
				)
			);
		}
		else
		{
			$table->construct_cell('&nbsp;');
		}
	}
	if ($arid<>'new')
	{
		$popup = new PopupMenu("rank_{$arid}", $lang->options);
		$popup->add_item($lang->opt_armies_delete_rank, "index.php?module=config-opt_armies&amp;action=deleterank&amp;arid={$arid}&my_post_key=" . $mybb->post_code, 'return AdminCP.deleteConfirmation(this,\'' . $lang->opt_armies_delete_rank_question . '\')');
		$table->construct_cell($popup->fetch(), array(
			'class' => 'align_center'
		));
	}
	else
	{
		$table->construct_cell($lang->opt_armies_no_options);
	}

	$table->construct_row();
}

function opt_armies_set_rank($uid, $arid)
{
	global $db;
	
	// update user rank
	$db->delete_query(
		'armies_user_ranks',
		'uid='.intval($uid)
	);
	$db->insert_query(
		'armies_user_ranks',
		array(
			'uid' => intval($uid),
			'arid' => intval($arid)
		)
	);
}

function opt_armies_set_rank_recruit($uid, $aid)
{
	global $db;
	
	// bad assumption: the ranks are in a specific order...
	$query=$db->write_query('
		SELECT r.arid 
		FROM '.TABLE_PREFIX.'armies_ranks AS r 
		JOIN '.TABLE_PREFIX.'armies_army_ranks AS ar
			ON r.arid=ar.arid 
		WHERE ar.aid='.intval($aid).'
		ORDER BY r.displayorder DESC
		LIMIT 1
	');
	$arid=$db->fetch_field($query, 'arid');
	$db->free_result($query);
	
	opt_armies_set_rank($uid, $arid);
}

function opt_armies_get_group_shortcut_by_gid($gid)
{
	$group=opt_armies_get_army_group_by_gid($gid);
	return $group['shortcut'];
}

// Forum helper functions ****************************************************************************

// get username
function opt_armies_get_username_by_uid($uid)
{
	global $db;
	
	$result = opt_armies_get_user_by_uid($uid);
	if (empty($result[ 'username' ]))
		$result[ 'username' ] = $lang->opt_armies_uid_unknown;
	return $result[ 'username' ];
}

function opt_armies_get_user_by_uid($uid)
{
	global $db;
	
	$query = $db->simple_select('users', '*', 'uid=' . intval($uid));
	$user  = $db->fetch_array($query);
	$db->free_result($query);
	
	$user[ 'usergroups' ] = array_merge(array(
		$user[ 'usergroup' ]
	), explode(',', $user[ 'additionalgroups' ]));
	
	return $user;
}

function opt_armies_get_uid_by_username($username)
{
	global $db;
	
	$query  = $db->simple_select('users', 'uid', 'username="' . $db->escape_string($username) . '"');
	$result = $db->fetch_field($query, 'uid');
	$db->free_result($query);
	return $result;
}

function opt_armies_get_usergroups($uid)
{
    global $db;
    
    $query = $db->simple_select('users', 'usergroup, additionalgroups', 'uid=' . intval($uid));
    $data  = $db->fetch_array($query);
    $db->free_result($query);
    $usergroup_string=$data['usergroup'];
    if(!empty($data['additionalgroups']))
        $usergroup_string.=','.$data['additionalgroups'];
    $usergroups = explode(',', $usergroup_string);
    $usergroups = array_diff( $usergroups, array(''),array(' '));//remove all empty elements
    return $usergroups;
}

function opt_armies_get_groupslead($uid)
{
	global $cache;
	
	$groupleaders = $cache->read('groupleaders');
	$groupslead   = array();
	
	$groupleaders = $groupleaders[ $uid ];
	
	if (!empty($groupleaders))
	{
		foreach ($groupleaders as $groupdata)
		{
			$groupslead[] = $groupdata[ 'gid' ];
		}
	}
	return $groupslead;
}

function opt_armies_get_groupname_by_gid($gid, $default = 'thearmy')
{
	global $db;
	
	if ($gid == '')
	{
		return $default;
	}
	
	$query  = $db->simple_select('usergroups', 'title', 'gid=' . intval($gid));
	$result = $db->fetch_field($query, 'title');
	$db->free_result($query);
	return $result;
}

// send a PM
function opt_armies_send_pm($recipient, $fromid, $subject, $message, $icon = 0)
{
	global $mybb, $lang, $cache;
	
	// Check if send this PM.
	if ($mybb->settings[ 'enablepms' ] != 1)
	{
		return false;
	}
	
	// We are ready to send it.
	require_once MYBB_ROOT . "inc/datahandlers/pm.php";
	$pmhandler = new PMDataHandler();
	
	// build PM data
	
	// recipient
	$toid   = array();
	$toid[] = intval($recipient);
	
	// sender
	if ($fromid < 1)
	{
		$fromid = -1;
	}
	$pm = array(
		'subject' => $subject,
		'message' => $message,
		'icon' => $icon,
		'fromid' => intval($fromid),
		'toid' => $toid
	);
	
	$pmhandler->admin_override = true;
	$pmhandler->set_data($pm);
	
	if (!$pmhandler->validate_pm())
	{
		$pmhandler->is_validated = true;
		$pmhandler->errors       = array();
	}
	$pminfo = $pmhandler->insert_pm();
	return $pminfo;
}

function opt_armies_get_groupleaders($gid)
{
	global $db;
	
	$groupleaders = array();
	$query        = $db->simple_select('groupleaders', 'uid', 'gid=' . intval($gid), array(
		'order_by' => 'uid',
		'order_dir' => 'ASC'
	));
	while ($group = $db->fetch_array($query))
	{
		$groupleaders[ $group[ 'uid' ] ] = $group[ 'uid' ];
	}
	$db->free_result($query);
	
	return $groupleaders;
}

function opt_armies_get_groupmembers($gid)
{
	global $db;
	
	$groupmembers = array();
	$query        = $db->simple_select('users', 'uid,usergroup,additionalgroups', '', array(
		'order_by' => 'uid',
		'order_dir' => 'ASC'
	));
	while ($user = $db->fetch_array($query))
	{
		$groups   = explode(',', $user[ 'additionalgroups' ]);
		$groups[] = $user[ 'usergroup' ];
		if (in_array($gid, $groups))
		{
			$groupmembers[ $user[ 'uid' ] ] = $user[ 'uid' ];
		}
	}
	$db->free_result($query);
	
	return $groupmembers;
}

function opt_armies_update_usergroups($uid, $usergroups, $primary_group = 0)
{
	global $db, $lang;
	
	$usergroups = array_unique($usergroups);
	
	if (empty($primary_group))
	{
		$primary_group = array_shift($usergroups);
	}
	if (empty($primary_group))
	{
		$primary_group = 2;
	}
	
	$update_record = array(
		'usergroup' => intval($primary_group),
		'additionalgroups' => implode(',', $usergroups)
	);
	$db->update_query('users', $update_record, 'uid=' . $uid);
	
	if (function_exists('groupsort_reorder_user'))
	{
		$query          = $db->simple_select('usergroups', '*', '', array(
			'order_by' => 'disporder',
			'order_dir' => 'ASC'
		));
		$all_usergroups = array();
		
		while ($usergroup = $db->fetch_array($query))
		{
			$all_usergroups[ $usergroup[ 'gid' ] ] = $usergroup[ 'disporder' ];
		}
		$db->free_result($query);
		
		groupsort_reorder_user($uid, $all_usergroups);
	}
}

// String Processing Functions ******************************************************************************

// replace the placeholders by their content/values
function opt_armies_fill_placeholders($parseme, $placeholders = array())
{
	if (!empty($parseme))
	{
		foreach ($placeholders as $key => $value)
		{
			$parseme = str_replace('{' . $key . '}', $value, $parseme);
		}
	}
	
	return $parseme;
}

// Parse data with the mybb parser
function opt_armies_parse_text($message)
{
	global $parser;
	if (!is_object($parser))
	{
		require_once MYBB_ROOT . 'inc/class_parser.php';
		$parser = new postParser;
	}
	$parser_options = array(
		'allow_html' => 0,
		'allow_smilies' => 1,
		'allow_mycode' => 1,
		'filter_badwords' => 1,
		'shorten_urls' => 1
	);
	$message        = $parser->parse_message($message, $parser_options);
	return $message;
}

// display or log warnings
function opt_armies_warning($message)
{
	global $error_handler;
	$error_handler->error(E_USER_WARNING, $message);
}

function opt_armies_mydump($var, $title)
{
	if (function_exists('mydump'))
	{
		mydump($var, $title);
	}
	else
	{
		// remove comment for debugging without mydump:
		// opt_armies_warning($title.': '.serialize($var));
	}
}

// templates are a big mess so I put it to the end of the file
function opt_armies_setup_templates()
{
	global $PL;
	
	$PL->templates('optarmies', 'OPT Armies', array(
		'changerank_group_members_form' => '<form action="misc.php?action=managegroup" method="post">
<input type="hidden" name="aid" value="{$aid}">
<input type="hidden" name="gid" value="{$gid}">
<input type="hidden" name="manage_member_action" value="{$member_action}">
<input type="hidden" name="managemembers" value="{$member_action}">
<input type="hidden" name="my_post_key" value="{$mybb->post_code}">
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" width="100%" colspan="1">{$lang->opt_armies_changerank_group_members_title}: {$usergroups[$gid][\'title\']}</td>
	</tr>
	<tr>
		<td class="tcat" width="100%">{$lang->opt_armies_changerank_group_members}</td>
	</tr>
	<tr>
		<td class="trow1">
			{$memberlist}
		</td>
	</tr>
	<tr>
		<td class="trow2">
			{$lang->opt_armies_changerank_group_members_select_rank}
		</td>
	</tr>
	<tr>
		<td class="trow1">
			<select name="newrank">
				{$ranklist}
			</select>
		</td>
	</tr>
	<tr>
		<td class="trow2">
			<input type="submit" name="confirm" value="$lang->go">
			<input type="submit" name="cancel" value="$lang->cancel">
		</td>
	</tr>
</table>
</form>',
		'join_army_form' => '<form action="misc.php?action=joinarmy" method="post">
	<input type="hidden" name="aid" value="{$mybb->input[\'aid\']}">
	<input type="hidden" name="my_post_key" value="{$mybb->post_code}">
	<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
		<tr>
			<td class="thead" width="100%"><strong>{$lang->opt_armies_join_army_confirmation}</strong></td>
		</tr>
		<tr>
			<td class="tcat">{$lang->opt_armies_join_army_confirmation_text}</td>
		</tr>
		<tr>
			<td class="trow1"><input type="submit" name="confirm" value="{$lang->opt_armies_confirm_join_request}"><input type="submit" name="cancel" value="{$lang->opt_armies_cancel_join_request}"></td>
		</tr>
	</table>
</form>',
		'kick_group_members_form' => '<form action="misc.php?action=managegroup" method="post">
<input type="hidden" name="aid" value="{$aid}">
<input type="hidden" name="gid" value="{$gid}">
<input type="hidden" name="manage_member_action" value="{$member_action}">
<input type="hidden" name="managemembers" value="{$member_action}">
<input type="hidden" name="my_post_key" value="{$mybb->post_code}">
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" width="100%" colspan="1">{$lang->opt_armies_kick_group_members_title}</td>
	</tr>
	<tr>
		<td class="tcat" width="100%">{$lang->opt_armies_kick_group_members}</td>
	</tr>
	<tr>
		<td class="trow1">
			{$memberlist}
		</td>
	</tr>
	<tr>
		<td class="trow2">
			<input type="submit" name="confirm" value="$lang->go">
			<input type="submit" name="cancel" value="$lang->cancel">
		</td>
	</tr>
</table>
</form>',
		'manage_assistents_menu' => '<input type="submit" name="manageassistants" value="$lang->opt_armies_manage_XOs">',
		'manage_group_assistants_form' => '<form action="misc.php?action=managegroup" method="post">
<input type="hidden" name="aid" value="{$aid}">
<input type="hidden" name="gid" value="{$gid}">
<input type="hidden" name="my_post_key" value="{$mybb->post_code}">
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" width="100%" colspan="1">{$lang->opt_armies_manage_group_assistants_title}: {$group_name}</td>
	</tr>
	<tr>
		<td class="tcat" width="100%">{$lang->opt_armies_select_group_assistants}</td>
	</tr>
	<tr>
		<td class="trow1">
			{$officerlist}
		</td>
	</tr>
	<tr>
		<td class="trow2">
			<input type="submit" name="manageassistants" value="$lang->go">
			<input type="submit" name="cancel" value="$lang->cancel">
		</td>
	</tr>
</table>
</form>',
		'manage_group_leader_form' => '<form action="misc.php?action=managegroup" method="post">
<input type="hidden" name="aid" value="{$aid}">
<input type="hidden" name="gid" value="{$gid}">
<input type="hidden" name="my_post_key" value="{$mybb->post_code}">
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" width="100%" colspan="1">{$lang->opt_armies_manage_group_leader_title}: {$group_name}</td>
	</tr>
	<tr>
		<td class="tcat" width="100%">{$lang->opt_armies_select_group_leader}</td>
	</tr>
	<tr>
		<td class="trow1">
			<select name="makeleader">
<option value="{$group_leader}" selected="selected">{$group_leader_name}</option>
				{$officerlist}
			</select>
		</td>
	</tr>
		<td class="trow2">
			<input type="submit" name="manageleader" value="$lang->go">
		</td>
	</tr>
</table>
</form>',
		'manage_leader_menu' => '<input type="submit" name="manageleader" value="$lang->opt_armies_manage_CO">',
		'manage_member_menu_army_leader' => '<!-- nix besonderes -->',
		'show_army_group_member_list_entry' => '<div class="army_member_list_entry">
{$army_member}
</div>',
		'show_army_group_member_list' => '<div class="army_member_list">
{$army_members}
</div>',
		'show_army_group' => '<form action="misc.php?action=managegroup" method="post">
<input type="hidden" name="gid" value="{$gid}">
<input type="hidden" name="my_post_key" value="{$mybb->post_code}">
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" width="100%" colspan="4"><div class="army_group_name">{$lang->opt_armies_group}: {$group_name}</div><div class="army_group_manage">{$I_am_leader}</div></td>
	</tr>
	<tr>
		<td class="tcat" align="center" width="1%"><strong>{$lang->opt_armies_group_shortcut}</strong></td>
		<td class="tcat" width="20%"><div class="army_group_title"><strong>{$lang->opt_armies_group_leader}</strong></div><div class="army_group_manage">{$manage_leader_menu}</div></td>
		<td class="tcat" width="20%"><div class="army_group_title"><strong>{$lang->opt_armies_group_XOs}</strong></div><div class="army_group_manage">{$manage_assistents_menu}</div></td>
		<td class="tcat" ><div class="army_group_title"><strong>{$lang->opt_armies_group_members}{$manage_all}</div></strong><div class="army_group_manage">{$manage_member_menu}</div></td>
	</tr>
	<tr>
		<td class="{$trow} armygroup" align="center">[{$group_shortcut}]</td>
		<td class="{$trow} armygroup" >{$group_leader}</td>
		<td class="{$trow} armygroup" >{$group_XOs}</td>
		<td class="{$trow} armygroup" >{$group_members}</td>
	</tr>
</table>
</form>
{$subgroups}',
		'show_armies' => '<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" width="100%"><strong>{$lang->opt_armies_show_armies}</strong></td>
	</tr>
	<tr>
		<td class="tcat ">{$lang->opt_armies_show_armies_description}</td>
	</tr>
</table>
{$army_layout}',
		'select_army_list' => '<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" width="100%"><strong>{$lang->opt_armies_select_army_welcome}</strong></td>
	</tr>
	<tr>
		<td class="tcat ">{$lang->opt_armies_select_army_description}</td>
	</tr>
</table>
{$randomjoin}
<br>
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" width="100%" colspan="6"><strong>{$lang->opt_armies_available_armies}</strong></td>
	</tr>
	<tr>
		<td class="tcat" style="white-space: nowrap;" align="center" width="1%"><strong>{$lang->opt_armies_army_icon}</strong></td>
		<td class="tcat" ><strong>{$lang->opt_armies_army_name}</strong></td>
		<td class="tcat" ><strong>{$lang->opt_armies_army_nation}</strong></td>
		<td class="tcat" ><strong>{$lang->opt_armies_army_leader}</strong></td>
		<td class="tcat" ><strong>{$lang->opt_armies_army_status}</strong></td>
		<td class="tcat" align="center"><strong>{$lang->opt_armies_select_army_options}</strong></td>
	</tr>
	{$armiesrows}
</table>',
		'select_army_list_random' => '<br>
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" colspan="2" width="100%"><strong>{$lang->opt_armies_random_army_title}</strong></td>
	</tr>
	<tr>
		<td class="tcat ">{$lang->opt_armies_random_army_description}</td>
		<td class="tcat " align="center"><a href="{$random_join_url}" class="army_option">{$lang->opt_armies_random_army_title}</a></td>
	</tr>
</table>',
		'random_army_form' => '<form action="misc.php?action=randomarmy" method="post">
	<input type="hidden" name="my_post_key" value="{$mybb->post_code}">
	<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
		<tr>
			<td class="thead" width="100%"><strong>{$lang->opt_armies_join_random_army_confirmation}</strong></td>
		</tr>
		<tr>
			<td class="tcat">{$lang->opt_armies_join_random_army_confirmation_text}</td>
		</tr>
		<tr>
			<td class="trow1"><input type="submit" name="confirm" value="{$lang->opt_armies_confirm_join_request}"><input type="submit" name="cancel" value="{$lang->opt_armies_cancel_join_request}"></td>
		</tr>
	</table>
</form>',
		'manage_member_menu_group_leaders' => '<input type="hidden" name="aid" value="$aid">
<select name="manage_member_action">
	<optgroup label="{$lang->opt_armies_member_action_menu}">
		{$manage_member_menu_changerank}
		{$manage_member_menu_multi_leader}
		{$manage_member_menu_hco}
		{$manage_member_menu_army_leader}
	</optgroup>
</select>
<input type="submit" name="managemembers" value="{$lang->go}">',
		'manage_member_menu_hco' => '<option value="kick">&nbsp;&nbsp;&nbsp;{$lang->opt_armies_kick_member}</option>',
		'manage_member_menu_multi_leaders' => '<option value="transfer">&nbsp;&nbsp;&nbsp;{$lang->opt_armies_member_transfer}</option>',
		'show_army_group_subgroups' => '<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="{$trow}" colspan="4" style="padding-left: 40px">{$subgroups}</td>
	</tr>
</table>',
		'transfer_group_members_form' => '<form action="misc.php?action=managegroup" method="post">
<input type="hidden" name="aid" value="{$aid}">
<input type="hidden" name="gid" value="{$gid}">
<input type="hidden" name="manage_member_action" value="{$member_action}">
<input type="hidden" name="managemembers" value="{$member_action}">
<input type="hidden" name="my_post_key" value="{$mybb->post_code}">
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" width="100%" colspan="1">{$lang->opt_armies_transfer_group_members_title}: {$usergroups[$gid][\'title\']}</td>
	</tr>
	<tr>
		<td class="tcat" width="100%">{$lang->opt_armies_transfer_group_members}</td>
	</tr>
	<tr>
		<td class="trow1">
			{$memberlist}
		</td>
	</tr>
	<tr>
		<td class="trow2">
			{$lang->opt_armies_transfer_group_members_select_target}
		</td>
	</tr>
	<tr>
		<td class="trow1">
			<select name="newgroup">
<option value="{$gid}" selected="selected">{$group_name}</option>
				{$grouplist}
			</select>
		</td>
	</tr>
	<tr>
		<td class="trow2">
			<input type="submit" name="confirm" value="$lang->go">
			<input type="submit" name="cancel" value="$lang->cancel">
		</td>
	</tr>
</table>
</form>',
		'select_army_list_row' => '<tr>
	<td class="{$trow}" align="center" width="1%">{$army_icon}</td>
	<td class="{$trow}" >{$army_name}</td>
	<td class="{$trow}" >{$army_nation}</td>
	<td class="{$trow}" >{$army_leader}</td>
	<td class="{$trow}" >{$army_status}</td>
	<td class="{$trow}" align="center">{$army_options}</td>
</tr>',
		'select_army_list_row_option' => '<a href="{$army_option_url}"  class="army_option">{$army_option}</a>',
		'misc_page' => '<html>
	<head>
		<title>{$mybb->settings[\'bbname\']} - {$lang->opt_armies_page_title}</title>
		{$headerinclude}
	</head>
	<body>
		{$header}
		{$errors}
		{$content}
		{$footer}
	</body>
</html>',
		'select_army_list_empty' => '<tr><td class="trow1" align="center" colspan="6">{$lang->opt_armies_no_armies}</td> </tr>',
		'manage_member_menu_changerank' => '<option value="changerank">&nbsp;&nbsp;&nbsp;{$lang->opt_armies_member_changerank}</option>',
		'show_army' => '<br>
<br>
<br>
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" width="100%" colspan="6"><strong>{$army_name}</strong></td>
	</tr>
	<tr>
		<td class="tcat" style="white-space: nowrap;" align="center" width="15%"><strong>{$lang->opt_armies_army_icon}</strong></td>
		<td class="tcat" align="center" width="10%"><strong>{$lang->opt_armies_army_shortcut}</strong></td>
		<td class="tcat" width="30%"><strong>{$lang->opt_armies_army_leader}</strong></td>
		<td class="tcat" ><strong>{$lang->opt_armies_army_nation}</strong></td>
		<td class="tcat" ><strong>{$lang->opt_armies_army_members}</strong></td>
		<td class="tcat" width="20%" ><strong>{$lang->opt_armies_army_status}</strong></td>
	</tr>
	<tr>
		<td class="trow1" style="white-space: nowrap;" align="center" >{$army_icon}</td>
		<td class="trow1" align="center" >[{$army_shortcut}]</td>
		<td class="trow1" >{$army_leader}</td>
		<td class="trow1" >{$army_nation}</td>
		<td class="trow1" >{$army_members}</td>
		<td class="trow1" >{$army_status}</td>
	</tr>
	<tr>
		<td class="trow2" width="100%" colspan="6">{$armygroups}</td>
	</tr>
</table>'
	));
}

function opt_armies_setup_stylessheet()
{
	global $PL;
	
	$styles = array(
		'.army_member_list_entry' => array(
			'float' => 'left',
			'padding-right' => '10px',
			'margin-left' => '15px',
			'display' => 'list-item'
		),
		'.armygroup' => array(
			'vertical-alig' => 'top'
		),
		'.army_group_name' => array(
			'float' => 'left'
		),
		'.army_group_manage' => array(
			'float' => 'right'
		),
		'.army_group_title' => array(
			'float' => 'left'
		)
	);
	$PL->stylesheet('opt_armies', $styles);
}

function opt_armies_format_username($username)
{
	// This function requires a patch to "inc/functions.php" - for OPT it is implemented using the "Patches" plugin:
	/*
	+	if (function_exists('opt_armies_format_username'))
	+	{
	+		   $username = opt_armies_format_username($username);
	+	}
	@	return str_replace("{username}", $username, $format);
	*/

	global $db;

	// BIG TODO: really cache the data, this is a performance killer!
	
	// cache assigned ranks
	$query=$db->simple_select(
		'armies_user_ranks',
		'*'
	);
	$user_ranks=array();
	while($user_rank=$db->fetch_array($query))
	{
		$user_ranks[$user_rank['uid']]=$user_rank['arid'];
	}
	$db->free_result($query);
	
	// cache army ranks
	$query=$db->simple_select(
		'armies_ranks',
		'*'
	);
	$army_ranks=array();
	while($army_rank=$db->fetch_array($query))
	{
		$army_ranks[$army_rank['arid']]=$army_rank;
	}
	$db->free_result($query);
	
	$uid=opt_armies_get_uid_by_username($username);
	
	// build rank
	$rank=$army_ranks[$user_ranks[$uid]]['shortcut'];
	if (!empty($rank))
	{
		$rank .= '.';
	}
	
	// build group tag
	$users_groups=opt_armies_get_usergroups($uid);
	$aid=opt_armies_get_aid_by_uid($uid);
	// $users_groups=opt_armies_remove_non_army_groups($aid, $users_groups);
	$army_groups=opt_armies_get_army_structure_groups_by_aid($aid);
	$army=opt_armies_get_army_by_aid($aid);
	if (!empty($army['HCO_gid']))
	{
		$army_groups[]=$army['HCO_gid'];
	}
	$army_groups[]=$army['uugid'];

	// $group='#';
	foreach($users_groups as $gid)
	{
		if(in_array($gid, $army_groups))
		{
			$group=opt_armies_get_group_shortcut_by_gid($gid);
			break;
		}
	}
	if (!empty($group))
	{
		$group .= '|';
	}
	
	return $group.$rank.$username;
}

/* Exported by Hooks plugin Mon, 02 Sep 2013 09:26:22 GMT */
?>
