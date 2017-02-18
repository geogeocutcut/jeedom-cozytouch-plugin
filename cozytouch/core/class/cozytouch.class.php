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

class cozytouch extends eqLogic {
    /******************************* Attributs *******************************/ 
    /* Ajouter ici toutes vos variables propre a  votre classe */

	private static $_client = null;
	
    /***************************** Methode static ****************************/ 

    /*
    // Fonction executee automatiquement toutes les minutes par Jeedom
    public static function cron() {

    }
    */

    /*
    // Fonction executee automatiquement toutes les heures par Jeedom
    public static function cronHourly() {

    }
    */

    /*
    // Fonction executee automatiquement tous les jours par Jeedom
    public static function cronDayly() {

    }
    */
 
    /*************************** Methode d'instance **************************/ 
 

    /************************** Pile de mise a  jour **************************/ 
    
    /* fonction permettant d'initialiser la pile 
     * plugin: le nom de votre plugin
     * action: l'action qui sera utilise dans le fichier ajax du pulgin 
     * callback: fonction appele cote client(JS) pour mettre a  jour l'affichage 
     */ 
    public function initStackData() {
        nodejs::pushUpdate('cozytouch::initStackDataEqLogic', array('plugin' => 'cozytouch', 'action' => 'saveStack', 'callback' => 'displayEqLogic'));
    }
    
    /* fonnction permettant d'envoyer un nouvel equipement pour sauvegarde et affichage, 
     * les donnees sont envoye au client(JS) pour aªtre traite de maniere asynchrone
     * Entree: 
     *      - $params: variable contenant les parametres eqLogic
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
					'userPassword' => config::byKey('password', 'cozytouch'),
					'serviceUrl' => config::byKey('apiurl', 'cozytouch')
			));
		}
		return self::$_client;
	}
	
	public function syncWithCozyTouch() {
		$client = self::getClient();
		$resp = $client->getSetup();
	    $places = $resp->getData();
		
		foreach ($places as $place) {
			$eqLogic = eqLogic::byLogicalId($place->getVar('oid'), 'cozytouch');
			if (!is_object($eqLogic)) {
				$eqLogic = new cozytouch();
				$eqLogic->setEqType_name('cozytouch');
				$eqLogic->setIsEnable(1);
				$eqLogic->setName($place->getVar('label'));
				$eqLogic->setLogicalId($place->getVar('oid'));
				$eqLogic->setCategory('heating', 1);
				$eqLogic->setIsVisible(1);
				$eqLogic->save();
			}
			
			$list_devices = array();
			$devices = $place->getDevices();
			$i=0;
			foreach ($devices as $device) {
				$i+=1;
				$order=($i)*10;
				$device_array = utils::o2a($device);
				$device_array = $device_array['object'];
				$deviceURL = $device_array['deviceURL'];
				$deviceURLShort = explode("#",$deviceURL)[0];
				if (!isset($deviceURL) || $deviceURL== '') {
					continue;
				}
				$list_devices[$deviceURL] = $deviceURLShort;
				
				//Device URL
				self::createCommand($eqLogic,'deviceURL_' . $i,'info','string',__('device URL', __FILE__) . ' ' . $i,0,$deviceURLShort,999);

				//core:OnOffState
				self::createCommand($eqLogic,$deviceURLShort.'_core:OnOffState','info','binary',__('On/Off', __FILE__) . ' ' . $i,1,'',$order+1,'','',1);
				
				//core:OperatingModeState
				self::createCommand($eqLogic,$deviceURLShort.'_core:OperatingModeState','info','string',__('Mode interne', __FILE__) . ' ' . $i,1,'',$order+2);
				
				//io:TargetHeatingLevelState
				self::createCommand($eqLogic,$deviceURLShort.'_io:TargetHeatingLevelState','info','string',__('Mode', __FILE__) . ' ' . $i,1,'',$order+3);
				
				//core:TargetTemperatureState
				self::createCommand($eqLogic,$deviceURLShort.'_core:TargetTemperatureState','info','numeric',__('Cible', __FILE__) . ' ' . $i,1,'',$order+4);
			
				//core:ComfortRoomTemperatureState
				self::createCommand($eqLogic,$deviceURLShort.'_core:ComfortRoomTemperatureState','info','numeric',__('Comfort', __FILE__) . ' ' . $i,0,'',999);
				
				//core:EcoRoomTemperatureState
				self::createCommand($eqLogic,$deviceURLShort.'_core:EcoRoomTemperatureState','info','numeric',__('Delta Eco', __FILE__) . ' ' . $i,0,'',999);
				
				//core:DerogatedTargetTemperatureState
				self::createCommand($eqLogic,$deviceURLShort.'_core:DerogatedTargetTemperatureState','info','numeric',__('Dérogation', __FILE__) . ' ' . $i,1,'',$order+5);

				//io:EffectiveTemperatureSetpointState
				self::createCommand($eqLogic,$deviceURLShort.'_io:EffectiveTemperatureSetpointState','info','numeric',__('Effective', __FILE__) . ' ' . $i,1,'',$order+6);
				
				//io:TemperatureProbeCalibrationOffsetState
				self::createCommand($eqLogic,$deviceURLShort.'_io:TemperatureProbeCalibrationOffsetState','info','numeric',__('Calibration', __FILE__) . ' ' . $i,0,'',999);
				
				
				//#2 : core:TemperatureState
				self::createCommand($eqLogic,$deviceURLShort.'_core:TemperatureState','info','numeric',__('Température', __FILE__) . ' ' . $i,1,'',$order+7);

				//#4 : core:OccupancyState
				self::createCommand($eqLogic,$deviceURLShort.'_core:OccupancyState','info','binary',__('Présence', __FILE__) . ' ' . $i,1,'',$order+8,'presence','presence',1);

				//#5 : core:ElectricEnergyConsumptionState
				self::createCommand($eqLogic,$deviceURLShort.'_core:ElectricEnergyConsumptionState','info','string',__('Consommation', __FILE__) . ' ' . $i,1,'',$order+9);
		
				
			}

			
			// ations :
			// Refresh
			self::createCommand($eqLogic,'refresh','action','other',__('Refresh', __FILE__),1,'',100);
			
			// Mode
			self::createCommand($eqLogic,'setOperationModeOff','action','other',__('Mode Veille', __FILE__),1,'',101);
			self::createCommand($eqLogic,'setOperationModeBasic','action','other',__('Mode Basic', __FILE__),1,'',102);
			self::createCommand($eqLogic,'setOperationModeExternal','action','other',__('Mode Externe', __FILE__),1,'',103);
			self::createCommand($eqLogic,'setOperationModeInternal','action','other',__('Mode Interne', __FILE__),1,'',104);
			self::createCommand($eqLogic,'setOperationModeAuto','action','other',__('Mode Auto', __FILE__),1,'',105);
				
			self::createCommand($eqLogic,'setHeatingLvlEco','action','other',__('Eco', __FILE__),1,'',106);
			self::createCommand($eqLogic,'setHeatingLvlComfort','action','other',__('Comfort', __FILE__),1,'',107);
			self::createCommand($eqLogic,'setHeatingLvlFrostprotection','action','other',__('Hors Gel', __FILE__),1,'',108);
			self::createCommand($eqLogic,'setHeatingLvlOff','action','other',__('Off', __FILE__),1,'',109);
			self::createCommand($eqLogic,'cancelForcedHeatingLvl','action','other',__('Reset Mode', __FILE__),1,'',110);
				
			
			// Température
			//self::createCommand($eqLogic,'setTargetTemperature','action','other',__('Consigne T°', __FILE__),1,'',106);
			
			$eqLogic->setConfiguration('devices_list', $list_devices);
			$eqLogic->save();
		}
		
		$cron = cron::byClassAndFunction('cozytouch', 'cron15');
		if (!is_object($cron)) {
			$cron = new cron();
			$cron->setClass('cozytouch');
			$cron->setFunction('cron15');
			$cron->setEnable(1);
			$cron->setDeamon(0);
			$cron->setSchedule('*/5 * * * * *');
			$cron->save();
		}
		

		self::refresh_all($client);
	}
	
	protected function createCommand($eqLogic,$cmdId,$type,$subType,$name,$visible=1,$value='',$order=999,$dashboard ='',$mobile='',$isHistorized=0)
	{
		$cmd = $eqLogic->getCmd($type, $cmdId);
		if (!is_object($cmd)) {
			$cmd = new cozytouchCmd();
		
		}
		$cmd->setEqLogic_id($eqLogic->getId());
		$cmd->setLogicalId($cmdId);
		$cmd->setType($type);
		$cmd->setSubType($subType);
		$cmd->setName($name);
		$cmd->setIsVisible($visible);
		$cmd->setIsHistorized($isHistorized);
		$cmd->setOrder($order);
		if($dashboard!='')
		{
			$cmd->setTemplate('dashboard', $dashboard);
		}
		else 
		{
			$cmd->setTemplate('dashboard', 'default');
		}
		if($mobile!='')
		{
			$cmd->setTemplate('mobile', $mobile);
		}
		else 
		{
			$cmd->setTemplate('mobile', 'default');
		}
		$cmd->save();
		if($value!='')
		{
			$cmd->setValue($value);
			$cmd->event($value);
		}
	}
	
	protected function genericApplyCommand($cmds)
	{
	
		$list_devices = $this->getConfiguration('devices_list');
		$actions = array();
		foreach($list_devices as $urlLong => $urlShort)
		{

			$action = new CozyTouchAction();
			$action->deviceURL = $urlLong;
			
			foreach($cmds as $cmd)	
			{
				$command = new CozyTouchCommand();
				$command->name=$cmd['name'];
				if($cmd['values'] != null)
				{
					$command->parameters[]=$cmd['values'];
				}

				$action->commands[]=$command;
			}
	
			$actions[]=$action;
				
		}
	
		$commandsMsg = new CozyTouchCommands();
		$commandsMsg->label= "Changement de mode";
		$commandsMsg->actions=$actions;
	
		$clientApi = self::getClient();
		$post_data = $commandsMsg;//'setOperatingMode'
		$clientApi->applyCommand($post_data);
	
	
	}
	
	
	
	public function setOperationMode($value)
	{
			//$this->cancelHeatingLevel();
			$cmds = array(
						array(
							"name"=>CozyTouchDeviceActions::CTPC_SETMODE,
							"values"=>$value
						),
						array(
								"name"=>CozyTouchDeviceActions::CTPC_RSHHEATINGLVL,
								"values"=>null
						)
					);
			$this->genericApplyCommand($cmds);
			
			$cmds = array(
					array(
							"name"=>CozyTouchDeviceActions::CTPC_RSHHEATINGLVL,
							"values"=>null
					)
			);
			$this->genericApplyCommand($cmds);
			
			$cmds = array(
					array(
							"name"=>CozyTouchDeviceActions::CTPC_RSHMODE,
							"values"=>null
					)
			);
			$this->genericApplyCommand($cmds);
	}
	
	public function refreshHeatingLevel()
	{
			
		$cmds = array(
				array(
						"name"=>CozyTouchDeviceActions::CTPC_RSHHEATINGLVL,
						"values"=>null
				)
		);
		$this->genericApplyCommand($cmds);
			
		$cmds = array(
				array(
						"name"=>CozyTouchDeviceActions::CTPC_RSHMODE,
						"values"=>null
				)
		);
		$this->genericApplyCommand($cmds);
	}
	
	public function setTemperature($value)
	{
		$list_device = $this->getConfiguration('devices_list');
		$mode='';
		foreach($list_device as $urlLong => $urlShort)
		{
			$cmd_array = Cmd::byLogicalId($urlShort."_io:TargetHeatingLevelState");
			if(is_array($cmd_array) && $cmd_array!=null)
			{
				$cmd=$cmd_array[0];
				$mode=$cmd->execCmd();
			}
		
			break;
		}
		log::add('cozytouch', 'info', __('Mode ', __FILE__).$mode);
				
		if($mode=='eco')
		{
			$this->setDerogatedTargetTemperature($value);
		}
		else
		{
			$this->setTargetTemperature($value);
		}
	}
	
	public function setTargetTemperature($value)
	{
		log::add('cozytouch', 'info', __('Tmp ', __FILE__).$value);
		$cmds = array(
					array(
							"name"=>CozyTouchDeviceActions::CTPC_SETTARGETTEMP,
							"values"=>$value
					),
					array(
							"name"=>CozyTouchDeviceActions::CTPC_RSHTARGETTEMP,
							"values"=>null
					)
			);
		$this->genericApplyCommand($cmds);
		
		$cmds = array(
				array(
						"name"=>CozyTouchDeviceActions::CTPC_RSHEFFTEMP,
						"values"=>null
				)
		);
		$this->genericApplyCommand($cmds);
	}
	
	public function setDerogatedTargetTemperature($value)
	{
		$cmds = array(
					array(
							"name"=>CozyTouchDeviceActions::CTPC_SETDEROGTEMP,
							"values"=>$value
					),
					array(
							"name"=>CozyTouchDeviceActions::CTPC_RSHDEROGTEMP,
							"values"=>null
					)
			);
		$this->genericApplyCommand($cmds);
		
		$cmds = array(
				array(
						"name"=>CozyTouchDeviceActions::CTPC_RSHEFFTEMP,
						"values"=>null
				)
		);
		$this->genericApplyCommand($cmds);
	}
	
	public function setComfortTemperature($value)
	{
		$cmds = array(
					array(
							"name"=>CozyTouchDeviceActions::CTPC_SETCOMTEMP,
							"values"=>$value
					),
					array(
							"name"=>CozyTouchDeviceActions::CTPC_RSHCOMTEMP,
							"values"=>null
					)
			);
		$this->genericApplyCommand($cmds);
	}
	
	public function setEcoTemperature($value)
	{
		$cmds = array(
					array(
							"name"=>CozyTouchDeviceActions::CTPC_SETECOTEMP,
							"values"=>$value
					),
					array(
							"name"=>CozyTouchDeviceActions::CTPC_RSHECOTEMP,
							"values"=>null
					)
			);
		$this->genericApplyCommand($cmds);
		
	}

	public function cancelHeatingLevel($value='comfort')
	{
		$cmds = array(
				array(
						"name"=>CozyTouchDeviceActions::CTPC_CANCELHEATINGLVL,
						"values"=>$value
				),
				array(
						"name"=>CozyTouchDeviceActions::CTPC_RSHHEATINGLVL,
						"values"=>null
				)
		);
		$this->genericApplyCommand($cmds);
	}
	
	public function setHeatingLevel($value)
	{
		$cmds = array(
					array(
							"name"=>CozyTouchDeviceActions::CTPC_SETHEATINGLVL,
							"values"=>$value
					),
					array(
							"name"=>CozyTouchDeviceActions::CTPC_RSHHEATINGLVL,
							"values"=>null
					)
			);
		$this->genericApplyCommand($cmds);
	}
    /* fonction appele pour la sauvegarde asynchrone
     * Entree: 
     *      - $params: variable contenant les parametres eqLogic
     */
    public function saveStack($params) {
        // inserer ici le traitement pour sauvegarde de vos donnees en asynchrone
        
    }

    /* fonction appele avant le debut de la sequence de sauvegarde */
    public function preSave() {
    	if ($this->getConfiguration('order_max') === '') {
    		$this->setConfiguration('order_max', 28);
    	}
    	if ($this->getConfiguration('order_min') === '') {
    		$this->setConfiguration('order_min', 12);
    	}
    }

    /* fonction appele pendant la sequence de sauvegarde avant l'insertion 
     * dans la base de donnees pour une mise a  jour d'une entree */
    public function preUpdate() {
        
    }

    /* fonction appele pendant la sequence de sauvegarde apres l'insertion 
     * dans la base de donnees pour une mise a  jour d'une entree */
    public function postUpdate() {
        
    }

    /* fonction appele pendant la sequence de sauvegarde avant l'insertion 
     * dans la base de donnees pour une nouvelle entree */
    public function preInsert() {

    }

    /* fonction appele pendant la sequence de sauvegarde apres l'insertion 
     * dans la base de donnees pour une nouvelle entree */
    public function postInsert() {
        
    }

    /* fonction appele apres la fin de la sequence de sauvegarde */
    public function postSave() {
    	$order = $this->getCmd(null, 'order');
    	if (!is_object($order)) {
    		$order = new thermostatCmd();
    		$order->setIsVisible(0);
    	}
    	$order->setEqLogic_id($this->getId());
    	$order->setName(__('Consigne', __FILE__));
    	$order->setType('info');
    	$order->setSubType('numeric');
    	$order->setIsHistorized(1);
    	$order->setLogicalId('order');
    	$order->setUnite('°C');
    	$order->setConfiguration('maxValue', $this->getConfiguration('order_max'));
    	$order->setConfiguration('minValue', $this->getConfiguration('order_min'));
    	$order->setOrder(1);
    	$order->save();
    	
    	
    	$thermostat = $this->getCmd(null, 'cozytouchThermostat');
    	if (!is_object($thermostat)) {
    		$thermostat = new cozytouchCmd();
    	}
    	$thermostat->setEqLogic_id($this->getId());
    	$thermostat->setName(__('Thermostat', __FILE__));
    	$thermostat->setConfiguration('maxValue', $this->getConfiguration('order_max'));
    	$thermostat->setConfiguration('minValue', $this->getConfiguration('order_min'));
    	$thermostat->setType('action');
    	$thermostat->setSubType('slider');
    	$thermostat->setUnite('°C');
    	$thermostat->setLogicalId('cozytouchThermostat');
    	$thermostat->setTemplate('dashboard', 'thermostat');
    	$thermostat->setTemplate('mobile', 'thermostat');
    	$thermostat->setIsVisible(1);
    	$thermostat->setOrder(99);
		$thermostat->setValue($order->getId());
    	$thermostat->save();
    }

    /* fonction appele avant l'effacement d'une entree */
    public function preRemove() {
        
    }

    /* fonnction appele apres l'effacement d'une entree */
    public function postRemove() {
        
    }
    
    public static function cron15() {
    	self::refresh_all();
    }
    
    protected function getListState($extend)
    {
    	switch($extend)
    	{
    		case "#1":
    			return CozyTouchDeviceStateName::$CTDS1_NAME;
    		case "#2":
    			return CozyTouchDeviceStateName::$CTDS2_NAME;
    		case "#4":
    			return CozyTouchDeviceStateName::$CTDS4_NAME;
    		case "#5":
    			return CozyTouchDeviceStateName::$CTDS5_NAME;
    		default :
    			return null;
    	}
    }
    
    public static function refresh_all() {
    	try {
    		
    		$devicesCmd=array();
    		
    		// construct post data json
    		$placeEqs = eqLogic::byType('cozytouch',true);
    		foreach($placeEqs as $placeEq)
    		{
    			$placeEq->refreshHeatingLevel();
    			$list_device = $placeEq->getConfiguration('devices_list');
    			foreach($list_device as $urlLong => $urlShort)
    			{
    				foreach(CozyTouchDeviceToDisplay::$CTDTD_SUFFIXE as $extension)
    				{
    					$deviceCmd = new CozyTouchDeviceCommand();
    					$deviceCmd->deviceURL=$urlShort.$extension;
    					foreach (self::getListState($extension) as $stateName)
    					{
    						$stateCmd = new CozyTouchStateCommand();
    						$stateCmd->name = $stateName;
    						$deviceCmd->states[]=$stateCmd;
    					}
    					$devicesCmd[]=$deviceCmd;
    				}
    			}
    		}
    		
    		$clientApi = self::getClient();
    		
    		
    		$post_data = $devicesCmd;
    		$resp = $clientApi->getStates($post_data);
			$devices = $resp->getData();
			foreach ($devices as $device)
			{
				$urlShort = explode("#",$device->getURL())[0];
				// state du device
				foreach ($device->getStates() as $state)
				{
					//echo "  ".$state->name." : ".$state->value."<br>";

					$cmd_array = Cmd::byLogicalId($urlShort."_".$state->name);
					if(is_array($cmd_array) && $cmd_array!=null)
					{
						$cmd=$cmd_array[0];
						if($state->name=='core:OnOffState')
						{
							$value = ($state->value=='on');
						}
						else
						{
							$value = $state->value;
						}
						if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
    						$cmd->setCollectDate('');
							$cmd->event($value);
						}
					}
				}
				
				
				// Liste des capteurs du device
				foreach ($device->getSensors() as $sensor)
				{
					// state du capteur
					foreach ($sensor->getStates() as $state)
					{
						//echo "  ".$state->name." : ".$state->value."<br>";

						$cmd_array = Cmd::byLogicalId($urlShort."_".$state->name);
						if(is_array($cmd_array) && $cmd_array!=null)
						{
							$cmd=$cmd_array[0];
							if($state->name=='core:OccupancyState')
							{
								$value = ($state->value=='noPersonInside');
							}
							else
							{
								$value = $state->value;
							}
							if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
    							$cmd->setCollectDate('');
								$cmd->event($value);
							}
						}
					}
				}
			}
			
			self::refresh_thermostat($placeEqs);
			
    		//$resp = $client->getStates($post_data);
    		//$devices = $resp->getData();
    		
    	} catch (Exception $e) {
    
    	}
    }
    
    public function refresh_place() {
    	try {

    		$this->refreshHeatingLevel();
    		
    		$devicesCmd=array();
    
    		$list_device = $this->getConfiguration('devices_list');
    		foreach($list_device as $urlLong => $urlShort)
    		{
    			foreach(CozyTouchDeviceToDisplay::$CTDTD_SUFFIXE as $extension)
    			{
    				$deviceCmd = new CozyTouchDeviceCommand();
    				$deviceCmd->deviceURL=$urlShort.$extension;
    				foreach (self::getListState($extension) as $stateName)
    				{
    					$stateCmd = new CozyTouchStateCommand();
    					$stateCmd->name = $stateName;
    					$deviceCmd->states[]=$stateCmd;
    				}
    				$devicesCmd[]=$deviceCmd;
    			}
    		}
    
    		$clientApi = self::getClient();
    
    
    		$post_data = $devicesCmd;
    		$resp = $clientApi->getStates($post_data);
    		$devices = $resp->getData();
    		foreach ($devices as $device)
    		{
    			$urlShort = explode("#",$device->getURL())[0];
    			// state du device
    			foreach ($device->getStates() as $state)
    			{
    				//echo "  ".$state->name." : ".$state->value."<br>";
    
    				$cmd_array = Cmd::byLogicalId($urlShort."_".$state->name);
    				if(is_array($cmd_array) && $cmd_array!=null)
    				{
    					$cmd=$cmd_array[0];
    					if($state->name=='core:OnOffState')
						{
							$value = ($state->value=='on');
						}
						else
						{
							$value = $state->value;
						}
						if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
    						$cmd->setCollectDate('');
							$cmd->event($value);
						}
    				}
    			}
    			// Liste des capteurs du device
    			foreach ($device->getSensors() as $sensor)
    			{
    				// state du capteur
    				foreach ($sensor->getStates() as $state)
    				{
    					//echo "  ".$state->name." : ".$state->value."<br>";
    
    					$cmd_array = Cmd::byLogicalId($urlShort."_".$state->name);
    					if(is_array($cmd_array) && $cmd_array!=null)
    					{
    						$cmd=$cmd_array[0];
    						if($state->name=='core:OccupancyState')
    						{
								$value = ($state->value=='noPersonInside');
    						}
    						else
    						{
    							$value = $state->value;
    						}
    						if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
    							$cmd->setCollectDate('');
    							$cmd->event($value);
    						}
    					}
    				}
    			}
    		}
    		
    		self::refresh_thermostat(array($this));
    		
    		//$resp = $client->getStates($post_data);
    		//$devices = $resp->getData();
    
    	} catch (Exception $e) {
    
    	}
    }
    
    public static function refresh_thermostat($placeEqs) 
    {
    	foreach($placeEqs as $placeEq)
    	{
    		$list_device = $placeEq->getConfiguration('devices_list');
    		$mode='';
    		$valuetmp = array();
    	
    		foreach($list_device as $urlLong => $urlShort)
    		{
    			$cmd_array = Cmd::byLogicalId($urlShort."_io:TargetHeatingLevelState");
    			if(is_array($cmd_array) && $cmd_array!=null)
    			{
    				$cmd=$cmd_array[0];
    				$mode=$cmd->execCmd();
    			}
    				
    			$cmd_array = Cmd::byLogicalId($urlShort."_core:TargetTemperatureState");
    			if(is_array($cmd_array) && $cmd_array!=null)
    			{
    				$cmd=$cmd_array[0];
    				$valuetmp['cible']=$cmd->execCmd();
    			}
    			
    			$cmd_array = Cmd::byLogicalId($urlShort."_core:EcoRoomTemperatureState");
    			if(is_array($cmd_array) && $cmd_array!=null)
    			{
    				$cmd=$cmd_array[0];
    				$valuetmp['eco']=$cmd->execCmd();
    			}
    				
    			$cmd_array = Cmd::byLogicalId($urlShort."_core:DerogatedTargetTemperatureState");
    			if(is_array($cmd_array) && $cmd_array!=null)
    			{
    				$cmd=$cmd_array[0];
    				$valuetmp['derogation']=$cmd->execCmd();
    			}
    				
    			$cmd_array = Cmd::byLogicalId($urlShort."_io:EffectiveTemperatureSetpointState");
    			if(is_array($cmd_array) && $cmd_array!=null)
    			{
    				$cmd=$cmd_array[0];
    				$valuetmp['effective']=$cmd->execCmd();
    			}
    				
    			break;
    		}

    		log::add('cozytouch', 'info', __('eq Id ', __FILE__).$placeEq->getId());
    			
			$cmd=Cmd::byEqLogicIdAndLogicalId($placeEq->getId(),'order');
    		if (is_object($cmd)) {
    			if($mode=='eco')
    			{
    				if($valuetmp['derogation']>0)
    				{
    					$cmd->setCollectDate('');
    					$cmd->event($valuetmp['derogation']);
    					log::add('cozytouch', 'info', __('Derogation ', __FILE__).$valuetmp['derogation']);
    				}
    				else
    				{
    					$tempEco = $valuetmp['cible']-$valuetmp['eco'];
    					$cmd->setCollectDate('');
    					$cmd->event($tempEco);
    					log::add('cozytouch', 'info', __('Eco ', __FILE__).$tempEco);
    				}
    			}
    			else
    			{
    				$cmd->setCollectDate('');
    				$cmd->event($valuetmp['cible']);
    				log::add('cozytouch', 'info', __('Effective ', __FILE__).$valuetmp['effective']);
    				
    			}
    		}
    	}
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
    /* Ajouter ici toutes vos variables propre a  votre classe */

    /***************************** Methode static ****************************/ 

    /*************************** Methode d'instance **************************/ 

    /* Non obligatoire permet de demander de ne pas supprimer les commandes maªme si elles ne sont pas dans la nouvelle configuration de l'equipement envoye en JS
    public function dontRemoveCmd() {
        return true;
    }
    */

    public function execute($_options = array()) {
    	$eqLogic = $this->getEqLogic();
    	$refresh = true;
    	switch($this->getLogicalId()){
    		case 'refresh':
    			$eqLogic->refresh_place();
    			$refresh = false;
    			break;
    		case 'setOperationModeOff':
    			$eqLogic->setOperationMode('standby');
    			break;
    			
    		case 'setOperationModeBasic':
    			$eqLogic->setOperationMode('basic');
    			break;
    			
    		case 'setOperationModeExternal':
    			$eqLogic->setOperationMode('external');
    			break;

    		case 'setOperationModeInternal':
    			$eqLogic->setOperationMode('internal');
    			break;

    		case 'setOperationModeAuto':
    			$eqLogic->setOperationMode('auto');
    			break;
    					 
    		case 'setHeatingLvlComfort':
    			$eqLogic->setHeatingLevel('comfort');
    			break;
    			
    		case 'setHeatingLvlEco':
    			$eqLogic->setHeatingLevel('eco');
    			break;
    			
    		case 'setHeatingLvlFrostprotection':
    			$eqLogic->setHeatingLevel('frostprotection');
    			break;
    				 
    		case 'setHeatingLvlOff':
    			$eqLogic->setHeatingLevel('off');
    			break;
    				
    		case 'setTargetTemperature':
    			$eqLogic->setTargetTemperature();
    			break;
    		case 'cancelForcedHeatingLvl':
    			$eqLogic->cancelHeatingLevel();
    			break;
    		case 'cozytouchThermostat':
    			$min = $this->getConfiguration('minValue');
    			$max = $this->getConfiguration('maxValue');
    			if (!isset($_options['slider']) || $_options['slider'] == '' || !is_numeric(intval($_options['slider']))) {
    				$_options['slider'] = (($max - $min) / 2) + $min;
    			}
    			if ($_options['slider'] > $max) {
    				$_options['slider'] = $max;
    			}
    			if ($_options['slider'] < $min) {
    				$_options['slider'] = $min;
    			}
    			$eqLogic->getCmd(null, 'order')->event($_options['slider']);
    			
    			$eqLogic->setTemperature(floatval($_options['slider']));
    			break;
    	}
    	if($refresh)
    	{
			sleep(5);
	    	$eqLogic->refresh_place();
    	}
    }

    /***************************** Getteur/Setteur ***************************/ 

    
}

?>
