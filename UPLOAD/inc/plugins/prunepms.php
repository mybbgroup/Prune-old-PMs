<?php

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.");
}

function prunepms_info()
{
    return array(
        "name"          => "Prune old PMs",
        "description"   => "Automatically delete old PMs in Inbox&Sent&Trash folders",
        "website"       => "http://community.mybb.com/user-84065.html",
        "author"        => "Elfew /Jakub Kořistka/",
        "authorsite"    => "http://community.mybb.com/user-84065.html",
        "version"       => "0.7",
        "guid"          => "",
        "compatibility" => "18*"
    );
}

function prunepms_activate()
{
global $db, $cache;
// Create task - Prune old PMs
// Have we already added this task?
    $query = $db->simple_select('tasks', 'tid', "file='task_prunepms'", array('limit' => '1'));
    if($db->num_rows($query) == 0)
    {
        // Load tasks function needed to run task and add nextrunt time
        require_once MYBB_ROOT."/inc/functions_task.php";
        // If not then add
        $new_task = array(
            "title" => "Prune old PMs",
            "description" => "Checks for old PMs, deletes them and optimizes PMs table in your database after cleaning.",
            "file" => "task_prunepms",
            "minute" => '1',
            "hour" => '0',
            "day" => '*',
            "month" => '*',
            "weekday" => '*',
            "enabled" => '1',
            "logging" => '1',
        );
        
        $new_task['nextrun'] = fetch_next_run($new_task);
        $tid = $db->insert_query("tasks", $new_task);
        
        // Update the task and run it right now
        $cache->update_tasks();
        run_task($tid);
    }
}

function prunepms_deactivate()
{
global $db, $mybb;
    
	// Remove task from task manager
    $db->delete_query('tasks', 'file=\'task_prunepms\''); // Delete Prune PMs task
	
	// Rebuild settings
    rebuild_settings();
}