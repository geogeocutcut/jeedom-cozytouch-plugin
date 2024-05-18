<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
if (!class_exists('CozyTouchManager')) {
	require_once dirname(__FILE__) . "/../core/class/CozyTouchManager.class.php";
}

const COZY_VERSION = '2.0.1';
function cozytouch_install() {
	$cron = cron::byClassAndFunction('cozytouch', 'cozyRefresh');
	if (!is_object($cron)) {
		// Define a cron every 15 minutes based on random value
		// Very important so that all box do not make calls at the same time 
		$minute = rand(1, 14);
		$cron = new cron();
		$cron->setClass('cozytouch');
		$cron->setFunction('cozyRefresh');
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->setSchedule($minute . '-59/15 * * * *');
		$cron->setTimeout(5);
		$cron->save();
	}
	$current_version = config::byKey('version', 'cozytouch');
	if(!isset($current_version) || $current_version<COZY_VERSION)
	{
		log::add('cozytouch','info','Reset device, version trop ancienne : '.$current_version);
		CozyTouchManager::resetCozyTouch();
	}
	config::save('version', COZY_VERSION,'cozytouch');
}

function cozytouch_update() {
	$cron = cron::byClassAndFunction('cozytouch', 'refresh');
	if (is_object($cron)) {
		$cron->remove();
	}
	$cron = cron::byClassAndFunction('cozytouch', 'cozyRefresh');
	if (!is_object($cron)) {
		// Define a cron every 15 minutes based on random value
		// Very important so that all box do not make calls at the same time 
		$minute = rand(1, 14);
		$cron = new cron();
		$cron->setClass('cozytouch');
		$cron->setFunction('cozyRefresh');
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->setSchedule($minute . '-59/15 * * * *');
		$cron->setTimeout(5);
		$cron->save();
	}

	$current_version = config::byKey('version', 'cozytouch');
	log::add('cozytouch','info','Version : '.$current_version);
	if(!isset($current_version) || $current_version<COZY_VERSION)
	{
		log::add('cozytouch','info','Reset device, version trop ancienne : '.$current_version);
		CozyTouchManager::resetCozyTouch();
	}
	config::save('version', COZY_VERSION,'cozytouch');
}

function cozytouch_remove() {
	$cron = cron::byClassAndFunction('cozytouch', 'cozyRefresh');
	if (is_object($cron)) {
		$cron->remove();
	}
	// CozyTouchManager::resetCozyTouch();
	// config::remove('username', 'cozytouch');
	// config::remove('password', 'cozytouch');
	// config::remove('version', 'cozytouch');
}

?>
