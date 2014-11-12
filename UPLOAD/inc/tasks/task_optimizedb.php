<?php

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.");
}

// Optimize DB
function task_optimizedb($task)
{
	global $db;
	
	$db->query("OPTIMIZE TABLE `".TABLE_PREFIX."_adminlog`, `".TABLE_PREFIX."_adminoptions`, `".TABLE_PREFIX."_adminsessions`, `".TABLE_PREFIX."_adminviews`, `".TABLE_PREFIX."_announcements`, `".TABLE_PREFIX."_attachments`, `".TABLE_PREFIX."_attachtypes`, `".TABLE_PREFIX."_awaitingactivation`, `".TABLE_PREFIX."_badwords`, `".TABLE_PREFIX."_banfilters`, `".TABLE_PREFIX."_banned`, `".TABLE_PREFIX."_calendarpermissions`, `".TABLE_PREFIX."_calendars`, `".TABLE_PREFIX."_captcha`, `".TABLE_PREFIX."_datacache`, `".TABLE_PREFIX."_delayedmoderation`, `".TABLE_PREFIX."_events`, `".TABLE_PREFIX."_forumpermissions`, `".TABLE_PREFIX."_forums`, `".TABLE_PREFIX."_forumsread`, `".TABLE_PREFIX."_forumsubscriptions`, `".TABLE_PREFIX."_groupleaders`, `".TABLE_PREFIX."_helpdocs`, `".TABLE_PREFIX."_helpsections`, `".TABLE_PREFIX."_icons`, `".TABLE_PREFIX."_joinrequests`, `".TABLE_PREFIX."_mailerrors`, `".TABLE_PREFIX."_maillogs`, `".TABLE_PREFIX."_mailqueue`, `".TABLE_PREFIX."_massemails`, `".TABLE_PREFIX."_moderatorlog`, `".TABLE_PREFIX."_moderators`, `".TABLE_PREFIX."_modtools`, `".TABLE_PREFIX."_mycode`, `".TABLE_PREFIX."_polls`, `".TABLE_PREFIX."_pollvotes`, `".TABLE_PREFIX."_posts`, `".TABLE_PREFIX."_privatemessages`, `".TABLE_PREFIX."_profilefields`, `".TABLE_PREFIX."_promotionlogs`, `".TABLE_PREFIX."_promotions`, `".TABLE_PREFIX."_reportedposts`, `".TABLE_PREFIX."_reputation`, `".TABLE_PREFIX."_searchlog`, `".TABLE_PREFIX."_sessions`, `".TABLE_PREFIX."_settinggroups`, `".TABLE_PREFIX."_settings`, `".TABLE_PREFIX."_smilies`, `".TABLE_PREFIX."_spiders`, `".TABLE_PREFIX."_stats`, `".TABLE_PREFIX."_tasklog`, `".TABLE_PREFIX."_tasks`, `".TABLE_PREFIX."_templategroups`, `".TABLE_PREFIX."_templates`, `".TABLE_PREFIX."_templatesets`, `".TABLE_PREFIX."_themes`, `".TABLE_PREFIX."_themestylesheets`, `".TABLE_PREFIX."_threadprefixes`, `".TABLE_PREFIX."_threadratings`, `".TABLE_PREFIX."_threads`, `".TABLE_PREFIX."_threadsread`, `".TABLE_PREFIX."_threadsubscriptions`, `".TABLE_PREFIX."_threadviews`, `".TABLE_PREFIX."_userfields`, `".TABLE_PREFIX."_usergroups`, `".TABLE_PREFIX."_users`, `".TABLE_PREFIX."_usertitles`, `".TABLE_PREFIX."_warninglevels`, `".TABLE_PREFIX."_warnings`, `".TABLE_PREFIX."_warningtypes`");
	
	// Add a log
	add_task_log($task, "The database optimization task successfully ran.");
}
