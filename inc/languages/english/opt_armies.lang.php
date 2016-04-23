<?php

// english

// general use
$l['opt_armies_title'] = 'OPT Armies';
$l['opt_armies_uid_unknown'] = 'unknown';

// ACP
$l['opt_armies_can_manage_armies'] = 'Can manage the armies?';

$l['opt_armies_registration_open_title'] = 'Registration opened?';
$l['opt_armies_registration_open_description'] = 'Users can join Armies only when the registration is opened.';
$l['opt_armies_registration_random_only_title'] = 'Join random Army only?';
$l['opt_armies_registration_random_only_description'] = 'If enabled: users cannot select which army to join, they will be assigned to a random (opened) Army.';
$l['opt_armies_max_member_difference_title'] = 'Max. team size difference?';
$l['opt_armies_max_member_difference_description'] = 'How many members more then the smallest Army may an Army have?';

$l['opt_armies_list_armies'] = 'List Armies';
$l['opt_armies_list_armies_description'] = 'Currently defined Armies';
$l['opt_armies_add_army'] = 'Add Army';
$l['opt_armies_add_army_description'] = 'Add a new Army, only the basic ínformation at this point.';
$l['opt_armies_army_shortcut'] = 'Shortcut';
$l['opt_armies_army_name'] = 'Army name';
$l['opt_armies_army_nation'] = 'Nation';
$l['opt_armies_army_leader'] = 'Leader';
$l['opt_armies_army_icon'] = 'Army Icon';
$l['opt_armies_edit_army'] = 'Edit Army';
$l['opt_armies_edit_army_description'] = 'Edit the basic info of an Army';
$l['opt_armies_delete_army'] = 'Delete Army';
$l['opt_armies_delete_army_question'] = 'Do you really want to delete this Army? This includes all Army Groups as well!';
$l['opt_armies_update_order'] = 'Update Display Order';
$l['opt_armies_no_armies'] = 'No Armies found in the database';
$l['opt_armies_table_armies'] = 'Available Armies';
$l['opt_armies_error_no_army_shortcut'] = 'Missing shortcut for the Army';
$l['opt_armies_error_no_army_name'] = 'Missing name for the Army';
$l['opt_armies_error_no_leader'] = 'Missing leader for the Army';
$l['opt_armies_army_added'] = 'Army added';
$l['opt_armies_army_edited'] = 'Army edited';
$l['opt_armies_config_army'] = 'Configure Army';
$l['opt_armies_config_army_description'] = 'Configure the structure of an Army (aka "Squads", including the hierarchy)';
$l['opt_armies_army_displayorder'] = 'Displayorder';
$l['opt_armies_army_primary_group'] = 'Primary Army Group';
$l['opt_armies_army_default_group'] = 'Default Army Group (for new Recruits)';
$l['opt_armies_army_hco_group'] = 'High Command Officer Group';
$l['opt_armies_army_co_group'] = 'Commanding Officer Group';
$l['opt_armies_army_welcome_pm_template'] = 'Template for the welcome PM. You can use those placeholders:
<ul>
<li>{username} - the users name</li>
<li>{army_name} - the name of the Army</li>
<li>{army_leader} - the name of the Army\'s leader</li>
</ul>';
$l['opt_armies_welcome_pm_subject'] = 'Welcome Soldier!';
$l['opt_armies_army_welcome_pm_template_default'] = 'Welcome Recruit {username},

Welcome to the {army_name}!

Please check in to our barracks (URL here) and see what\'s the next step to do.


Best regards,

{army_leader}';
$l['opt_armies_error_no_pm'] = 'Missing PM template';
$l['opt_armies_table_groups'] = 'Army Groups';
$l['opt_armies_army_is_locked'] = 'Is the Army locked?';
$l['opt_armies_army_is_locked_2'] = 'Users cannot join the Army if set';
$l['opt_armies_army_is_invite_only'] = 'Is the Army "invite only"?';
$l['opt_armies_army_is_invite_only_2'] = 'Users need to be invited to the Army if set';
$l['opt_armies_army_locked'] = 'Army is locked';
$l['opt_armies_army_invite_only'] = 'Army is invite only';
$l['opt_armies_configure_army'] = 'Configure Army';  // duplicate?
$l['opt_armies_configure_army_groups'] = 'Configure this Army\'s Groups';
$l['opt_armies_this_army'] = 'Details about this Army';
$l['opt_armies_group_name'] = 'Forum Group Name';
$l['opt_armies_group_shortcut'] = 'Group Tag';
$l['opt_armies_parent_group_name'] = 'Parent Forum Group Name (never create a loop!)';
$l['opt_armies_group_leader'] = 'Group Leader';
$l['opt_armies_no_groups'] = 'No groups found in the database for this Army';
$l['opt_armies_add_army_group'] = 'Add Army Group';
$l['opt_armies_add_army_group_description'] = 'Add a new sub group for this Army';
$l['opt_armies_edit_group'] = 'Edit Army Group';
$l['opt_armies_no_parent_group'] = 'No Parent Group (The Army is the Parent)';
$l['opt_armies_add_group'] = 'Add an Army Group';
$l['opt_armies_add_group_description'] = 'Add an Army Group and set Leader and Forum Group';
$l['opt_armies_edit_group'] = 'Edit this Army Group';
$l['opt_armies_edit_group_description'] = 'Edit this Army Groups\' Leader and Forum Groups';
$l['opt_armies_delete_group'] = 'Delete this Army Group';
$l['opt_armies_group_added'] = 'Added an Army Group';
$l['opt_armies_group_edited'] = 'Edited an Army Group';
$l['opt_armies_error_agrid_same_as_pagrid'] = 'Forum Group Name and Parent Forum Group Name cannot be the same...';
$l['opt_armies_error_no_group_shortcut'] = 'Missing shortcut for the Group';
$l['opt_armies_error_no_group_leader'] = 'Missing Leader for the Group';
$l['opt_armies_no_group_selected'] = ' << no group selected >> ';
$l['opt_armies_error_no_group_selected'] = 'Missing Forum Group for the Army Group';
$l['opt_armies_error_no_unknown_user'] = 'unknown username "{username}"';
$l['opt_armies_error_invalid_parent_group'] = 'invalid Parent Forum Group selected (the forum group isn\'t defined as an Army Group)';
$l['opt_armies_delete_group_question'] = 'Do you really want to delete this group? This includes all sub groups of this group as well!';
$l['opt_armies_add_user'] = 'Add an User to this Army';
$l['opt_armies_add_user_description'] = 'You can add any user to the Army - be careful!';
$l['opt_armies_error_no_username'] = 'Missing Username';
$l['opt_armies_user_added'] = 'User "{username}" added to "{army_name}"';


// misc pages
$l['opt_armies_page_title'] = 'OPT Armies';
$l['ok'] = 'Ok';
$l['cancel'] = 'Cancel';
$l['error'] = 'Error';


// ...selectarmy...
$l['opt_armies_select_army'] = 'Select An Army';
$l['opt_armies_page_list_army'] = 'Army';
$l['opt_armies_registration_closed'] = 'The registration is currently closed. Check the forum for details when it will be opened again.';
$l['opt_armies_registration_random_only'] = 'The registration is random only, please use the "Join a random Army!" link.';
$l['opt_armies_select_army_welcome'] = 'Join an Army!';
$l['opt_armies_select_army_description'] = 'Hello Soldier!
<p>
Here you can select which Army you want to join.
</p>
<p>
Not all Armies are available to join at times (due to too many soldiers in one Army), other Armies are available on invitation only.
</p>';
$l['opt_armies_random_army_title'] = 'Join a random Army!';
$l['opt_armies_random_army_description'] = 'Select this option to join a random Army:';
$l['opt_armies_available_armies'] = 'Select your Army';
$l['opt_armies_select_army_options'] = 'Join this Army';
$l['opt_armies_error_already_in_army'] = 'You are already member of the Army "{army_name}"!';
$l['opt_armies_no_icon'] = 'No Icon';
$l['opt_armies_army_status'] = 'Status';
$l['opt_armies_army_status_open'] = 'open';
$l['opt_armies_army_status_locked'] = 'locked';
$l['opt_armies_army_status_temp_locked'] = 'temporarily locked (e.g. army has too many members)';
$l['opt_armies_army_status_invite_only'] = 'invite only';
$l['opt_armies_army_status_random'] = 'random join only';
$l['opt_armies_select_army_option_join'] = 'Join Now!';
$l['opt_armies_select_army_option_none'] = 'n/a';

// ...joinarmy...
$l['opt_armies_join_army'] = 'Join "{army_name}" Army';
$l['opt_armies_join_army_confirmation'] = 'Confirm Join Request';
$l['opt_armies_join_army_confirmation_text'] = 'Are you sure you want to join the "{army_name}" Army? You cannot join another army once confirmed without assistance!';
$l['opt_armies_army_joined'] = 'You\'ve successfully joined the "{armyname}" Army!';
$l['opt_armies_confirm_join_request'] = 'Confirm Join Request'; // duplicate?
$l['opt_armies_cancel_join_request'] = 'Cancel';
$l['opt_armies_join_request_canceled'] = 'You\'ve canceled your join request.';
$l['opt_armies_join_request_done'] = 'You\'re now a recruit of the Army "{army_name}".';

// ...showarmies...
$l['opt_armies_show_armies'] = 'Show Armies';
$l['opt_armies_show_armies_description'] = 'This is an overview of all Armies displaying their structures and members';
$l['opt_armies_group_shortcut'] = 'Tag';
$l['opt_armies_group'] = 'Group';
$l['opt_armies_group_leader'] = 'Group Leader';
$l['opt_armies_group_XOs'] = 'Group Assistents';
$l['opt_armies_group_members'] = 'Group Members';
$l['opt_armies_army_is_locked'] = 'locked<br>(you cannot join)';
$l['opt_armies_army_is_invite_only'] = 'invite only<br>(an admin has to invite you)';
$l['opt_armies_army_is_open'] = 'open';
$l['opt_armies_manage_group'] = 'you can manage this group';
$l['opt_armies_manage_all'] = 'manage all members at once';
$l['opt_armies_member_action_menu'] = 'manage members';
$l['opt_armies_member_changerank'] = 'change member ranks';
$l['opt_armies_no_option_selected'] = '&lt;&lt;select an option&gt;&gt;';
$l['opt_armies_member_transfer'] = 'transfer members';
$l['opt_armies_manage_XOs'] = 'manage';
$l['opt_armies_manage_CO'] = 'manage';
// $l['opt_armies_make_officer'] = 'make officer';
// $l['opt_armies_make_HCO'] = 'make HCO';
$l['opt_armies_kick_member'] = 'kick member';
$l['opt_armies_army_members'] = 'Members';

// ...randomarmy...
$l['opt_armies_all_armies_closed'] = 'All Armies are currently closed. Sorry, you cannot join an Army.';
$l['opt_armies_join_random_army'] = 'Join a random Army';
$l['opt_armies_join_random_army_confirmation_text'] = 'Are you sure you want to join a random Army? You cannot join another army once confirmed without assistance!';

// ...managegroup...
$l['opt_armies_manage_group_title'] = 'Manage Group';
$l['opt_armies_error_unknown_manage_action'] = 'Unknown group management action.';
$l['opt_armies_error_unknown_member_action'] = 'Unknown member action.';
$l['opt_armies_manage_group_leader_title'] = 'Manage Leader for Group';
$l['opt_armies_select_group_leader'] = 'Select the new group leader:';
$l['opt_armies_new_group_leader'] = '{username} is now the new group leader of the group "{groupname}".';
$l['opt_armies_manage_group_assistants_title'] = 'Manage Assistants for Group';
$l['opt_armies_select_group_assistants'] = 'Select the new group assistants:';
$l['opt_armies_group_assistants_updated'] = 'Group assistents of the group "{groupname}" updated.';
$l['opt_armies_cancel_manage'] = 'Operation canceled.';
$l['opt_armies_kick_group_members_title'] = 'Kick Members from the Army';
$l['opt_armies_kick_group_members'] = 'Confirm kick';
$l['opt_armies_group_members_kicked'] = 'Kicked some members from the Army "{armyname}"';
$l['opt_armies_transfer_group_members_title'] = 'Transfer Members to another Army Group, current Group';
$l['opt_armies_transfer_group_members'] = 'Select Members to transfer:';
$l['opt_armies_transfer_group_members_select_target'] = 'Select new Army Group for those Members:';
$l['opt_armies_group_members_transfered'] = 'Transfered Members from "{groupname_old}" to "{groupname_new}".';
$l['opt_armies_changerank_group_members_title'] = 'Promote/Demote Army Members';
$l['opt_armies_changerank_group_members'] = 'Select the Members to promote/demote:';
$l['opt_armies_changerank_group_members_select_rank'] = 'Select the new rank, the old rank is ignored, so be careful who you promote/demote. Demoting an HCO makes him a Recruit!:';#
$l['opt_armies_group_members_rank_changed'] = 'Changed the rank of some "{groupname}" Members.';
$l['opt_armies_rank_group_enlisted'] = 'Enlisted';
$l['opt_armies_rank_group_officers'] = 'Officers';
$l['opt_armies_rank_group_HCOs'] = 'High Command Officers';
$l['opt_armies_rank_group_civilian'] = 'Civilian';
$l['opt_armies_error_invalid_member_selection'] = 'Invalid Member selection. You cannot promote, demote or kick member with the same or higher rank then your own rank.';

// to be translated:

// managegroup
$l['opt_armies_error_no_members_selected'] = 'No members selected!';
$l['opt_armies_cannot_promote_recuits_directly'] = 'Recruits need to be transfered to a structure group before they can be promoted. User=';
$l['opt_armies_demoting_officer_workaround'] = 'Demoting an Officer to Enlisted but he is not a member of any structure groups. Moving him back to recruits! User=';
$l['opt_armies_demoting_HCO_workaround'] = 'Demoting an HCO is always moving him back to recruits! User=';
$l['opt_armies_promoting_enlisted_workaround'] = 'Promoting an Enlisted to Officer but he is not a member of any structure groups. Moving him back to recruits! User=';
$l['opt_armies_return_to_showarmies'] = 'Click here to return to the "Show Armies" page.';

// listranks
$l['opt_armies_list_ranks'] = 'List Ranks';
$l['opt_armies_list_ranks_description'] = 'List currently defined Ranks';
$l['opt_armies_add_rank'] = 'Add Rank';
$l['opt_armies_add_rank_description'] = 'Add a new Army Rank';
$l['opt_armies_rank_name'] = 'Rank Name';
$l['opt_armies_rank_shortcut'] = 'Rank Shortcut';
$l['opt_armies_rank_class'] = 'Rank Class';
$l['opt_armies_rank_icon'] = 'Rank Icon';
$l['opt_armies_rank_icon_url'] = 'Rank Icon URL';
$l['opt_armies_army_ranks'] = 'Army Ranks';
$l['opt_armies_no_ranks'] = 'No Ranks found in the database';
$l['opt_armies_ranks_updated'] = 'Army Ranks updated.';
$l['opt_armies_delete_rank'] = 'Delete this Army Rank';
$l['opt_armies_delete_rank_question'] = 'Do you really want to delete this rank? This cannot be undone.';
$l['opt_armies_no_options'] = 'no options';
$l['opt_armies_update_ranks'] = 'Update Ranks';

// english

?>
