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
        "description"   => "Automatically delete old PMs in Inbox&Sent&Trash folders + Optimize all tables in forum database.",
        "website"       => "http://community.mybb.com/user-84065.html",
        "author"        => "Elfew /Jakub Kořistka/",
        "authorsite"    => "http://community.mybb.com/user-84065.html",
        "version"       => "0.4",
        "guid"          => "",
        "compatibility" => "18*"
    );
}

function prunepms_activate()
{
global $plugins, $db, $cache;
// Create task - Prune old PMs
// Have we already added this task?
	$query = $db->simple_select('tasks', 'tid', "file='task_prunepms'", array('limit' => '1'));
    if($db->num_rows($query) == 0)
	{
        // If not then add
		require_once MYBB_ROOT.'/inc/functions_task.php';
    $this_task = array(
        "title" => "Prune old PMs",
        "description" => "Checks for old PMs and deletes them. /Inbox&Sent&Trash folders/",
        "file" => "task_prunepms",
        "minute" => '1',
        "hour" => '0',
        "day" => '*',
        "month" => '*',
        "weekday" => '*',
        "enabled" => '1',
        "logging" => '1',
    );
	$task_id = (int) $db->insert_query('tasks', $this_task);
        $theTask = $db->fetch_array($db->simple_select('tasks', '*', 'tid = '.(int) $task_id, 1));
        $nextrun = fetch_next_run($this_task);
        $db->update_query('tasks', "nextrun='{$nextrun}', tid='{$task_id}'");
        $plugins->run_hooks('admin_tools_tasks_add_commit');
        
		// Update the task and run it right now
		$cache->update_tasks();
		run_task($task_id);
    }
	
// Create task - Optimize DB
// Have we already added this task?
	$query = $db->simple_select('tasks', 'tid', "file='task_optimizedb'", array('limit' => '1'));
    if($db->num_rows($query) == 0)
	{
        // If not then add
		require_once MYBB_ROOT.'/inc/functions_task.php';
    $this_task2 = array(
        "title" => "Optimize Database",
        "description" => "Optimizes all tables in forum database.",
        "file" => "task_optimizedb",
        "minute" => '3',
        "hour" => '0',
        "day" => '*',
        "month" => '*',
        "weekday" => '*',
        "enabled" => '1',
        "logging" => '1',
    );
	$task_id = (int) $db->insert_query('tasks', $this_task2);
        $theTask = $db->fetch_array($db->simple_select('tasks', '*', 'tid = '.(int) $task_id, 1));
        $nextrun = fetch_next_run($this_task2);
        $db->update_query('tasks', "nextrun='{$nextrun}', tid='{$task_id}'");
        $plugins->run_hooks('admin_tools_tasks_add_commit');
        
		// Update the task and run it right now
		$cache->update_tasks();
		run_task($task_id);
    }
}

function prunepms_deactivate()
{
global $db, $mybb;
    
	// Remove task from task manager
    $db->delete_query('tasks', 'file=\'task_prunepms\''); // Delete PrunePMs task 
	$db->delete_query('tasks', 'file=\'task_optimizedb\''); // Delete Optimize DB task

	// Rebuild settings
    rebuild_settings();
}