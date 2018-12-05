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

/******************************* Includes *******************************/ 
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
if (!class_exists('CozyTouchApiClient')) {
	require_once dirname(__FILE__) . '/../../3rdparty/CozyTouch-API-PHP/Clients/CozyTouchApiClient.php';
}
if (!class_exists('CozyTouchAction')) {
	require_once dirname(__FILE__) . '/../../3rdparty/CozyTouch-API-PHP/Objects/CozyTouchAction.php';
}
if (!class_exists('CozyTouchCommand')) {
	require_once dirname(__FILE__) . '/../../3rdparty/CozyTouch-API-PHP/Objects/CozyTouchCommand.php';
}
if (!class_exists('CozyTouchCommands')) {
	require_once dirname(__FILE__) . '/../../3rdparty/CozyTouch-API-PHP/Objects/CozyTouchCommands.php';
}
if (!class_exists('CozyTouchDeviceCommand')) {
	require_once dirname(__FILE__) . '/../../3rdparty/CozyTouch-API-PHP/Objects/CozyTouchDeviceCommand.php';
}
if (!class_exists('CozyTouchStateCommand')) {
	require_once dirname(__FILE__) . '/../../3rdparty/CozyTouch-API-PHP/Objects/CozyTouchStateCommand.php';
}
if (!class_exists('CozyTouchEqLogicBuilder')) {
	require_once dirname(__FILE__) . '/CozyTouchEqLogicBuilder.class.php';
}

class cozytouch extends eqLogic {
    /******************************* Attributs *******************************/ 
    /* Ajouter ici toutes vos variables propre é votre classe */

	private static $_client = null;
	
    /***************************** Methode static ****************************/ 

    /*
    // Fonction exécutée automatiquement toutes les minutes par Jeedom
    public static function cron() {

    }
    */

    /*
    // Fonction exécutée automatiquement toutes les heures par Jeedom
    public static function cronHourly() {

    }
    */

    /*
    // Fonction exécutée automatiquement tous les jours par Jeedom
    public static function cronDayly() {

    }
    */
 
    /*************************** Methode d'instance **************************/ 
 

    /************************** Pile de mise  jour **************************/ 
    
    /* fonction permettant d'initialiser la pile 
     * plugin: le nom de votre plugin
     * action: l'action qui sera utilisé dans le fichier ajax du pulgin 
     * callback: fonction appelée coté client(JS) pour mettre é jour l'affichage 
     */ 
    public function initStackData() {
        nodejs::pushUpdate('cozytouch::initStackDataEqLogic', array('plugin' => 'cozytouch', 'action' => 'saveStack', 'callback' => 'displayEqLogic'));
    }
    
    /* fonnction permettant d'envoyer un nouvel équipement pour sauvegarde et affichage, 
     * les données sont envoyé au client(JS) pour étre traité de maniére asynchrone
     * Entrée: 
     *      - $params: variable contenant les paramétres eqLogic
     */
    public function stackData($params) {
        if(is_object($params)) {
            $paramsArray = utils::o2a($params);
        }
        nodejs::pushUpdate('cozytouch::stackDataEqLogic', $paramsArray);
    }
	
	public function getClient($_force=false) {
		if (self::$_client == null || $_force) {
			self::$_client = new CozyTouchApiClient(array(
					'userId' => config::byKey('username', 'cozytouch'),
					'userPassword' => config::byKey('password', 'cozytouch')
			));
		}
		return self::$_client;
	}
	
	public static function syncWithCozyTouch() 
	{
		$client = self::getClient();
		$devices = $client->getSetup();
		log::add('cozytouch', 'debug', 'Recupération des données ok '); 

		foreach ($devices as $device) {
			
			$eqLogic = eqLogic::byLogicalId($device->getVar('oid'), 'cozytouch');
			if (!is_object($eqLogic)) {
				log::add('cozytouch', 'info', 'Device '.$device->getVar('oid').' non existant : creation en cours');
				$eqLogic = new cozytouch();
				$eqLogic->setEqType_name('cozytouch');
				$eqLogic->setIsEnable(1);
				$eqLogic->setName($device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
				$eqLogic->setLogicalId($device->getVar(CozyTouchDeviceInfo::CTDI_OID));

				$eqLogic->setConfiguration('type_device', $device->getVar(CozyTouchDeviceInfo::CTDI_TYPEDEVICE));
				$eqLogic->setConfiguration('device_model', $device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME));
				$eqLogic->setConfiguration('device_url', $device->getVar(CozyTouchDeviceInfo::CTDI_URL));

				$eqLogic->setCategory('heating', 1);
				$eqLogic->setIsVisible(1);

				$eqLogic->save();
			}
			$deviceModel = $device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME);
			switch ($deviceModel)
			{
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER:
					CozyTouchEqLogicBuilder::BuildAtlanticHeatSystem($eqLogic,$device);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER:
					CozyTouchEqLogicBuilder::BuildAtlanticHotWater($eqLogic,$device);
					break;
			}
			// $list_devices = array();
			// $devices = $place->getDevices();
			// $i=0;
			// foreach ($devices as $device) {
			// 	log::add('cozytouch', 'info', 'Creation des commandes pour '.$device->getVar(CozyTouchDeviceInfo::CTDI_URL));
			// 	$i+=1;
			// 	$order=($i)*20;
			// 	$deviceURL = $device->getURL();
			// 	$deviceURLShort = explode("#",$deviceURL)[0];
			// 	if (!isset($deviceURL) || $deviceURL== '') {
			// 		continue;
			// 	}
			// 	$list_devices[$deviceURL] = $deviceURLShort;
			// 	$deviceType = $device->getVar(CozyTouchDeviceInfo::CTDI_TYPEDEVICE);
			// 	switch ($deviceType)
			// 	{
			// 		case CozyTouchDeviceToDisplay::$CTDTD_HEATINGSYSTEM:
			// 			break;
			// 		case CozyTouchDeviceToDisplay::$CTDTD_WATERHEATINGSYSTEM:
			// 			break;
			// 	}

			// 	//Device URL
			// 	self::createCommand($eqLogic,'deviceURL_' . $i,'info','string',__('device URL', __FILE__) . ' ' . $i,0,$deviceURLShort,999);

			// 	//core:OnOffState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_core:OnOffState','info','binary',__('On/Off', __FILE__) . ' ' . $i,1,'',$order+1,'','',1);
				
			// 	//core:OperatingModeState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_core:OperatingModeState','info','string',__('Mode interne', __FILE__) . ' ' . $i,1,'',$order+2);
				
			// 	//io:TargetHeatingLevelState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_io:TargetHeatingLevelState','info','string',__('Mode', __FILE__) . ' ' . $i,1,'',$order+3);
				
			// 	//core:TargetTemperatureState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_core:TargetTemperatureState','info','numeric',__('Cible', __FILE__) . ' ' . $i,1,'',$order+4);
			
			// 	//core:ComfortRoomTemperatureState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_core:ComfortRoomTemperatureState','info','numeric',__('Comfort', __FILE__) . ' ' . $i,0,'',999);
				
			// 	//core:EcoRoomTemperatureState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_core:EcoRoomTemperatureState','info','numeric',__('Delta Eco', __FILE__) . ' ' . $i,0,'',999);
				
			// 	//core:DerogatedTargetTemperatureState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_core:DerogatedTargetTemperatureState','info','numeric',__('Dérogation', __FILE__) . ' ' . $i,1,'',$order+5);

			// 	//io:EffectiveTemperatureSetpointState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_io:EffectiveTemperatureSetpointState','info','numeric',__('Effective', __FILE__) . ' ' . $i,1,'',$order+6);
				
			// 	//io:TemperatureProbeCalibrationOffsetState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_io:TemperatureProbeCalibrationOffsetState','info','numeric',__('Calibration', __FILE__) . ' ' . $i,0,'',999);
				
				
			// 	//#2 : core:TemperatureState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_core:TemperatureState','info','numeric',__('Température', __FILE__) . ' ' . $i,1,'',$order+7);

			// 	//#4 : core:OccupancyState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_core:OccupancyState','info','binary',__('Présence', __FILE__) . ' ' . $i,1,'',$order+8,'presence','presence',1);

			// 	//#5 : core:ElectricEnergyConsumptionState
			// 	self::createCommand($eqLogic,$deviceURLShort.'_core:ElectricEnergyConsumptionState','info','string',__('Consommation', __FILE__) . ' ' . $i,1,'',$order+9);
		
				
			// }

			
			// // ations :
			// // Refresh
			// self::createCommand($eqLogic,'refresh','action','other',__('Refresh', __FILE__),1,'',100);
			
			// // Mode
			// self::createCommand($eqLogic,'setOperationModeOff','action','other',__('Mode Veille', __FILE__),1,'',101);
			// self::createCommand($eqLogic,'setOperationModeBasic','action','other',__('Mode Basic', __FILE__),1,'',102);
			// self::createCommand($eqLogic,'setOperationModeExternal','action','other',__('Mode Externe', __FILE__),1,'',103);
			// self::createCommand($eqLogic,'setOperationModeInternal','action','other',__('Mode Interne', __FILE__),1,'',104);
			// self::createCommand($eqLogic,'setOperationModeAuto','action','other',__('Mode Auto', __FILE__),1,'',105);
				
			// self::createCommand($eqLogic,'setHeatingLvlEco','action','other',__('Eco', __FILE__),1,'',106);
			// self::createCommand($eqLogic,'setHeatingLvlComfort','action','other',__('Comfort', __FILE__),1,'',107);
			// self::createCommand($eqLogic,'setHeatingLvlFrostprotection','action','other',__('Hors Gel', __FILE__),1,'',108);
			// self::createCommand($eqLogic,'setHeatingLvlOff','action','other',__('Off', __FILE__),1,'',109);
			// self::createCommand($eqLogic,'cancelForcedHeatingLvl','action','other',__('Reset Mode', __FILE__),1,'',110);
				
			
			// // Temp�rature
			// //self::createCommand($eqLogic,'setTargetTemperature','action','other',__('Consigne T�', __FILE__),1,'',106);
			
			// $eqLogic->setConfiguration('devices_list', $list_devices);
			// $eqLogic->save();
		}
		
		// $cron = cron::byClassAndFunction('cozytouch', 'cron15');
		// if (!is_object($cron)) {

		// 	log::add('cozytouch', 'info', 'cron non existant : creation en cours cron15');
		// 	$cron = new cron();
		// 	$cron->setClass('cozytouch');
		// 	$cron->setFunction('cron15');
		// 	$cron->setEnable(1);
		// 	$cron->setDeamon(0);
		// 	$cron->setSchedule('*/5 * * * * *');
		// 	$cron->save();
		// }
		

		// self::refresh_all($client);
	}
	
	
	
	
	
	
	
	

	
    /* fonction appelée pour la sauvegarde asynchrone
     * Entrée: 
     *      - $params: variable contenant les paramètres eqLogic
     */
    public function saveStack($params) {
        // inserer ici le traitement pour sauvegarde de vos données en asynchrone
        
    }

    /* fonction appelée avant le début de la séquence de sauvegarde */
    public function preSave() {
    }

    /* fonction appelée pendant la séquence de sauvegarde avant l'insertion 
     * dans la base de données pour une mise à jour d'une entrée */
    public function preUpdate() {
        
    }

    /* fonction appelée pendant la séquence de sauvegarde après l'insertion 
     * dans la base de données pour une mise à jour d'une entrée */
    public function postUpdate() {
        
    }

    /* fonction appelée pendant la séquence de sauvegarde avant l'insertion 
     * dans la base de données pour une nouvelle entrée */
    public function preInsert() {

    }

    /* fonction appelée pendant la séquence de sauvegarde après l'insertion 
     * dans la base de données pour une nouvelle entrée */
    public function postInsert() {
        
    }

    /* fonction appelée après la fin de la séquence de sauvegarde */
    public function postSave() {
    }

    /* fonction appelée avant l'effacement d'une entrée */
    public function preRemove() {
        
    }

    /* fonnction appelée aprés l'effacement d'une entrée */
    public function postRemove() {
        
    }
    
    public static function cron15() {
    	//self::refresh_all();
    }
    
    
    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class cozytouchCmd extends cmd {
    /******************************* Attributs *******************************/ 
    /* Ajouter ici toutes vos variables propre é votre classe */

    /***************************** Methode static ****************************/ 

    /*************************** Methode d'instance **************************/ 

    /* Non obligatoire permet de demander de ne pas supprimer les commandes méme si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
    public function dontRemoveCmd() {
        return true;
    }
    */

    public function execute($_options = array()) {
    	$eqLogic = $this->getEqLogic();
		$refresh = true;
		$device_type = $eqLogic->getConfiguration('device_model');
    	switch($device_type){
    		case 'refresh':
    			$eqLogic->refresh_place();
    			$refresh = false;
    			break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER:
				CozyTouchAtlanticHeatSystemExecute::execute($this);
    			break;
    			
    		case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER :
				CozyTouchAtlanticHotWaterExecute::execute($this);
    			break;
    			
    	}
    }

    /***************************** Getteur/Setteur ***************************/ 

    
}

?>
