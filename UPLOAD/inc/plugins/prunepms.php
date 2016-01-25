<?php

/**
 *	Prune old PMs
 *
 *	@author Eldenroot <http://community.mybb.com/user-84065.html>
 *	@GitHub <https://github.com/Cu8eR/MyBB_Prune-old-PMs>
 */
 
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.");
}

// Plugin info
function prunepms_info()
{
    return array(
        "name"          => "Prune old PMs",
        "description"   => "Automatically delete old PMs + optimize DB tables",
        "website"       => "http://community.mybb.com/user-84065.html",
        "author"        => "Eldenroot",
        "authorsite"    => "http://community.mybb.com/user-84065.html",
        "version"       => "1.0",
        "codename"      => "",
        "compatibility" => "18*"
    );
}

// Plugin activate
function prunepms_activate()
{
	global $db, $cache;
	
		// Create task - Prune old PMs
		// Have we already added this task?
			$query = $db->simple_select('tasks', 'tid', "file='prunepms'", array('limit' => '1'));
			if($db->num_rows($query) == 0)
			{
				// Load tasks function needed to run a task and add nextrun time
					require_once MYBB_ROOT."/inc/functions_task.php";
				
				// If not then add
					$new_task = array(
						"title" => "Prune old PMs",
						"description" => "Checks for old PMs, deletes them and optimizes PMs table in your database after cleaning.",
						"file" => "prunepms",
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

// Plugin deactivate
function prunepms_deactivate()
{
	global $db, $mybb;
    
		// Remove task from task manager
			$db->delete_query('tasks', 'file=\'prunepms\''); // Delete Prune old PMs task
	
		// Rebuild settings
			rebuild_settings();
}