<?php
	
	/**
		* MyBB Prune Old PMs - plugin for MyBB 1.8.x forum software
		* 
		* @package MyBB Plugin
		* @author MyBB Group - Eldenroot - <eldenroot@gmail.com>
		* @copyright 2021 MyBB Group <http://mybb.group>
		* @link <https://github.com/mybbgroup/MyBB_Prune-old-PMs>
		* @license GPL-3.0
		* 
	*/
	
	/**
		* This program is free software: you can redistribute it and/or modify
		* it under the terms of the GNU General Public License as published by
		* the Free Software Foundation, either version 3 of the License,
		* or (at your option) any later version.
		*
		* This program is distributed in the hope that it will be useful,
		* but WITHOUT ANY WARRANTY; without even the implied warranty of
		* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
		* See the GNU General Public License for more details.
		*
		* You should have received a copy of the GNU General Public License
		* along with this program.
		* If not, see <http://www.gnu.org/licenses/>.
	*/
	
	// Disallow direct access to this file for security reasons
	if(!defined("IN_MYBB"))
	{
		die("Direct initialization of this file is not allowed.");
	}
	
	// Plugin info
	function prunepms_info()
	{
		global $lang;
		$lang->load("config_prunepms");
		
		return array(
        "name"          => $lang->prunepms_name,
        "description"   => $lang->prunepms_desc,
        "website"       => "https://github.com/mybbgroup/MyBB_Prune-old-PMs",
        "author"        => "MyBB Group (Eldenroot)",
        "authorsite"    => "https://github.com/mybbgroup/MyBB_Prune-old-PMs",
        "version"       => "1.1.0",
        "codename"      => "prune",
        "compatibility" => "18*"
		);
	}
	
	// Plugin activate
	function prunepms_activate()
	{
		global $db, $cache, $lang;
	
    // Load lang file
    $lang->load("config_prunepms");
	
	// Create task - Prune old PMs
	// Have we already added this task?
	$query = $db->simple_select('tasks', 'tid', "file='prunepms'", array('limit' => '1'));
	if($db->num_rows($query) == 0)
	{
	// Load tasks function needed to run a task and add nextrun time
	require_once MYBB_ROOT."/inc/functions_task.php";
	
	// If not then add
	$new_task = array(
	"title" => $db->escape_string($lang->prunepms_task_name),
	"description" => $db->escape_string($lang->prunepms_task_desc),
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
	global $db, $cache;
    
	// Remove task from task manager in ACP
	$db->delete_query('tasks', 'file=\'prunepms\''); // Delete Prune old PMs task
	
    // Update task cache
    $cache->update_tasks();
	}
		