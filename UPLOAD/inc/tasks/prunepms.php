<?php

/**
 *	Prune old PMs
 *
 *	@author Eldenroot <http://community.mybb.com/user-84065.html>
 *	@GitHub <https://github.com/Cu8eR/MyBB_Prune-old-PMs>
 *	@version 1.0
 */

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.");
}

// Prune old PMs
function task_prunepms($task)
{
    global $db;

    // PMs older than x seconds will be deleted
    $secs = 30*24*3600; // 30 days for read PMs
    $usecs = 90*24*3600; // 90 days for unread PMs
	
    // Do not delete unread PMs
    if ($db->delete_query("privatemessages", "(dateline<".(TIME_NOW-$secs)." AND readtime>0) OR dateline<".(TIME_NOW-$usecs)." AND (folder='1' OR folder='2' OR folder='3' OR folder='4') ")) {
    add_task_log($task, "Prune old PMs task successfully ran - old PMs were deleted!");
	} else {
		add_task_log($task, "Something went wrong while cleaning up the old PMs...");
	}
	
	// Recount PMs after cleaning
    $queryString  = <<<SQL
    UPDATE mybb_users u SET 
        totalpms = (SELECT COUNT(pmid) FROM mybb_privatemessages pm WHERE pm.uid = u.uid),
        unreadpms = (SELECT COUNT(pmid) FROM mybb_privatemessages pm WHERE
            pm.uid = u.uid AND status='0' AND (folder='1' OR folder='2' OR folder='3' OR folder='4'));
SQL;

    $db->write_query($queryString);
	
	// Optimize PM table
	if ($db->query("OPTIMIZE TABLE `".TABLE_PREFIX."_privatemessages`, `".TABLE_PREFIX."_tasklog`, `".TABLE_PREFIX."_tasks`")) {
	add_task_log($task, "Prune old PMs - privatemessages table was optimized successfully!");
	} else {
		add_task_log($task, "Prune old PMs - privatemessages table was NOT optimized! Something went wrong...");
	}
}
