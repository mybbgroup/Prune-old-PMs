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
	
	// Prune old PMs
	function task_prunepms($task)
	{
		global $db, $lang;
		
		defined("IN_ADMINCP") {
			$lang->load("config_prunepms");
		}
		
		// PMs older than X seconds will be deleted
		$secs = 30*24*3600; // 30 days for read PMs
		$usecs = 90*24*3600; // 90 days for unread PMs
		
		// Do not delete unread PMs
		$db->delete_query("privatemessages", "(dateline<".(TIME_NOW-$secs)." AND readtime>0) OR dateline<".(TIME_NOW-$usecs)." AND (folder='1' OR folder='2' OR folder='3' OR folder='4') ")
		
		add_task_log($task, $lang->prunepms_task_log_delete_pms);
		
		// Recount PMs after cleaning
		$queryString  = <<<SQL
    UPDATE mybb_users u SET 
		totalpms = (SELECT COUNT(pmid) FROM mybb_privatemessages pm WHERE pm.uid = u.uid),
		unreadpms = (SELECT COUNT(pmid) FROM mybb_privatemessages pm WHERE
		pm.uid = u.uid AND status='0' AND (folder='1' OR folder='2' OR folder='3' OR folder='4'));
SQL;

    $db->write_query($queryString);

	// Optimize PM table
	$db->query("OPTIMIZE TABLE `".TABLE_PREFIX."_privatemessages`, `".TABLE_PREFIX."_tasklog`, `".TABLE_PREFIX."_tasks`") 
	
    add_task_log($task, $lang->prunepms_task_log_optimise_tables);
