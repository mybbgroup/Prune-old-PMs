<?php

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
    $secs = 30*24*3600; // 30 days
    $usecs = 60*24*3600; // 60 days
    // Do not delete unread PMs
    if ($db->delete_query("privatemessages", "(dateline<".(TIME_NOW-$secs)." AND readtime>0) OR dateline<".(TIME_NOW-$usecs)." AND (folder='1' OR folder='2' OR folder='3' OR folder='4')"))
    add_task_log($task, "Old read PMs were deleted successfully!");
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
	
// Add a log
	add_task_log($task, "The Prune old PMs task successfully ran.");
}
