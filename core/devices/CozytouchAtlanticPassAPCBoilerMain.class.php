<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

if (!class_exists('CozytouchAtlanticPassAPCBoilerDHWComponent')) {
	require_once dirname(__FILE__) . "/CozytouchAtlanticPassAPCBoilerDHWComponent.class.php";
}
if (!class_exists('CozytouchAtlanticPassAPCHeatingZone')) {
	require_once dirname(__FILE__) . "/CozytouchAtlanticPassAPCHeatingZone.class.php";
}

class CozytouchAtlanticPassAPCBoilerMain extends AbstractCozytouchDevice
{
	// io:CozytouchAtlanticPassAPCHeatingZone
	//		Equipements à créer
	// 		io:AtlanticPassAPCDHWComponent
	//		io:CozytouchAtlanticPassAPCHeatingZone
	//			io:AtlanticPassAPCZoneTemperatureSensor
	//
	//		Capteur à associée
	//		io:TotalFossilEnergyConsumptionSensor
	//			core:FossilEnergyConsumptionState
	//		io:DHWRelatedFossilEnergyConsumptionSensor
	//			core:FossilEnergyConsumptionState
	// 		io:HeatingRelatedFossilEnergyConsumptionSensor
	//			core:FossilEnergyConsumptionState

	// Reste à faire le mode on/off => assez complexe 
	// on => heating
	// off => stop

    const cold_water = 15;
    //[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
		CozyTouchDeviceEqCmds::SET_ONOFF=>[1,1,1],
		CozyTouchStateName::CTSN_CONNECT=>[99,1,1],
		'refresh'=>[1,0,0]
    ];
    
	public static function BuildEqLogic($device)
    {
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
        $eqLogic =self::BuildDefaultEqLogic($device);
		$eqLogic->setCategory('energy', 1);
        $states = CozyTouchDeviceStateName::EQLOGIC_STATENAME[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
		$sensors = array();
		$deviceSensors = $device->getSensors();
		$nbSesnsors = count($deviceSensors);
		for ($i = 0; $i <$nbSesnsors; $i++)
		{
			$sensor=$deviceSensors[$i];
			$sensorURL = $sensor->getURL();
			$sensorModel = $sensor->getModel();
			//  getModel()
			// controllableName: 
			//					 io:TotalFossilEnergyConsumptionSensor
			//							core:FossilEnergyConsumptionState
			//					 io:DHWRelatedFossilEnergyConsumptionSensor
			//							core:FossilEnergyConsumptionState
			//					 io:HeatingRelatedFossilEnergyConsumptionSensor
			//							core:FossilEnergyConsumptionState

			// création des équipements
			//					 io:AtlanticPassAPCDHWComponent,

			//					 io:AtlanticPassAPCHeatingAndCoolingZoneComponent
			// 					 rattacher io:AtlanticPassAPCZoneTemperatureSensor à l'équipement précédent créé.
			// sinon
			$sensors[] = array($sensorURL,$sensor->getModel());
			log::add('cozytouch', 'info', 'Sensor : '.$sensorURL);
			switch($sensorModel)
			{
				case CozyTouchDeviceToDisplay::CTDTD_TOTALFOSSILENERGYCONSUMPTION :
					// state du capteur
					foreach ($sensor->getStates() as $state)
					{
						if($state->name==CozyTouchStateName::CTSN_FOSSILENERGYCONSUMPTION)
						{
							log::add('cozytouch', 'info', 'State : '.$state->name);
							$cmdId = $sensorURL.'_'.$state->name;
							$type ="info";
							$subType = CozyTouchStateName::CTSN_TYPE[$state->name];
							$name = "Conso. totale";
							$dashboard =CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
							$mobile =CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
							$value =$subType=="numeric"?0:($subType=="string"?'value':0);
							self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,$value,$dashboard,$mobile,10);
							break;
						}
					}
					break;
				case CozyTouchDeviceToDisplay::CTDTD_DHWRELATEDFOSSILENERGYCONSUMPTION :
					// state du capteur
					foreach ($sensor->getStates() as $state)
					{
						if($state->name==CozyTouchStateName::CTSN_FOSSILENERGYCONSUMPTION)
						{
							log::add('cozytouch', 'info', 'State : '.$state->name);
							$cmdId = $sensorURL.'_'.$state->name;
							$type ="info";
							$subType = CozyTouchStateName::CTSN_TYPE[$state->name];
							$name = "Conso. hotwater";
							$dashboard =CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
							$mobile =CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
							$value =$subType=="numeric"?0:($subType=="string"?'value':0);
							self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,$value,$dashboard,$mobile,11);
							break;
						}
					}
					break;
				case CozyTouchDeviceToDisplay::CTDTD_HEATINGRELATEDFOSSILENERGYCONSUMPTION :
					// state du capteur
					foreach ($sensor->getStates() as $state)
					{
						if($state->name==CozyTouchStateName::CTSN_FOSSILENERGYCONSUMPTION)
						{
							log::add('cozytouch', 'info', 'State : '.$state->name);
							$cmdId = $sensorURL.'_'.$state->name;
							$type ="info";
							$subType = CozyTouchStateName::CTSN_TYPE[$state->name];
							$name = "Conso. heatsystem";
							$dashboard =CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
							$mobile =CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
							$value =$subType=="numeric"?0:($subType=="string"?'value':0);
							self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,$value,$dashboard,$mobile,12);
							break;
						}
					}
					break;

				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCDHW :
					// setDHWOnOffState : on / off
					// setPassAPCDHWMode : comfort / eco / internalScheduling
					CozytouchAtlanticPassAPCBoilerDHWComponent::BuildEqLogic($sensor);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCHEATINGZONE :
					$j = $i+1;
					if($j<$nbSesnsors && $deviceSensors[$j]->getModel()==CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONETEMPERATURESENSOR)
					{
						$sensorstemp = $sensor->getSensors();
						$sensorstemp[] = $deviceSensors[$j];
						$sensor->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, $sensorstemp);
					}
					CozytouchAtlanticPassAPCHeatingZone::BuildEqLogic($sensor);
					break;
			}
			
		}

		$onoff_state = $eqLogic->getCmd(null, 'onoffstate');
    	if (!is_object($onoff_state)) {
    		$onoff_state = new cozytouchCmd();
    		$onoff_state->setIsVisible(0);
    	}

    	$onoff_state->setEqLogic_id($eqLogic->getId());
    	$onoff_state->setName(__('onoffstate', __FILE__));
    	$onoff_state->setType('info');
    	$onoff_state->setSubType('binary');
    	$onoff_state->setIsHistorized(1);
    	$onoff_state->setLogicalId('onoffstate');
		$onoff_state->save();
		
		$onoff_toogle = $eqLogic->getCmd(null, CozyTouchDeviceEqCmds::SET_ONOFF);
		if (!is_object($onoff_toogle)) {
			$onoff_toogle = new cozytouchCmd();
			$onoff_toogle->setLogicalId(CozyTouchDeviceEqCmds::SET_ONOFF);
		}
		$onoff_toogle->setEqLogic_id($eqLogic->getId());
		$onoff_toogle->setName(__('Etat', __FILE__));
		$onoff_toogle->setType('action');
		$onoff_toogle->setSubType('slider');
		$onoff_toogle->setTemplate('dashboard', 'cozytouch::toggle');
		$onoff_toogle->setTemplate('mobile', 'cozytouch::toggle');
		$onoff_toogle->setIsVisible(1);
		$onoff_toogle->setValue($onoff_state->getId());
		$onoff_toogle->save();

		$eqLogic->setConfiguration('sensors',$sensors);

		$eqLogic->save();

		
        

        self::orderCommand($eqLogic);

        CozyTouchManager::refresh_all();
    }
    
	public static function orderCommand($eqLogic)
	{
		
		$cmds = $eqLogic->getCmd();
		foreach($cmds as $cmd)
		{
			$logicalId=explode('_',$cmd->getLogicalId());
			$key = $logicalId[(count($logicalId)-1)];
			log::add('cozytouch','debug','Mise en ordre : '.$key);
			if(array_key_exists($key,self::DISPLAY))
			{
				$cmd->setIsVisible(1);
				$cmd->setOrder(self::DISPLAY[$key][0]);
				$cmd->setDisplay('forceReturnLineBefore',self::DISPLAY[$key][1]);
				$cmd->setDisplay('forceReturnLineAfter',self::DISPLAY[$key][2]);
			}
			elseif($key != CozyTouchStateName::CTSN_FOSSILENERGYCONSUMPTION)
			{
				$cmd->setIsVisible(0);
			}
			$cmd->save();
		}
	}
    
    public static function Execute($cmd,$_options= array())
    {
        log::add('cozytouch', 'debug', 'command : '.$cmd->getLogicalId());
        $refresh=true;
		$eqLogic = $cmd->getEqLogic();
		$device_url=$eqLogic->getConfiguration('device_url');
        switch($cmd->getLogicalId())
        {
			case 'refresh':
				log::add('cozytouch', 'debug', 'command : '.$device_url.' refresh');
				break;

			case CozyTouchDeviceEqCmds::SET_ONOFF:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_ONOFF);
				self::setOnOff($device_url,$_options['slider']);
				break;	
		}
		if($refresh)
		{
			sleep(2);
			self::refresh($eqLogic);
		}
    }

    protected static function refresh($eqLogic)
	{
		log::add('cozytouch', 'debug', 'refresh : '.$eqLogic->getName());
		try {

			$device_url=$eqLogic->getConfiguration('device_url');
			$controllerName = $eqLogic->getConfiguration('device_model');

            $clientApi = CozyTouchManager::getClient();
            $states = $clientApi->getDeviceInfo($device_url,$controllerName);
			foreach ($states as $state)
			{
				$cmd_array = Cmd::byLogicalId($device_url."_".$state->name);
				if(is_array($cmd_array) && $cmd_array!=null)
				{
					$cmd=$cmd_array[0];
					
					$value = CozyTouchManager::get_state_value($state);
					if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
						$cmd->setCollectDate('');
						$cmd->event($value);
					}
				}
			}
			self::refresh_onoff($eqLogic);
		} 
		catch (Exception $e) {
	
        }
	}

	public static function refresh_onoff($eqDevice) 
    {
		$deviceURL = $eqDevice->getConfiguration('device_url');

		log::add('cozytouch', 'debug', 'Zone refresh mode : '.$deviceURL);
		$mode=Cmd::byEqLogicIdAndLogicalId($eqDevice->getId(),$deviceURL.'_'.CozyTouchStateName::CTSN_PASSAPCOPERATINGMODE);
		if(is_object($mode))
		{
			$mode_value = $mode->execCmd();
			$heating_state=Cmd::byEqLogicIdAndLogicalId($eqDevice->getId(),'onoffstate');
			if(is_object($heating_state))
			{
				if($mode_value=="heating")
				{
					$value=1;
				}
				else
				{
					$value=0;
				}
				$heating_state->setCollectDate('');
				$heating_state->event($value);
			}
		}
	}

	public static function setOnOff($device_url,$value)
	{
		if($value>0)
		{
			$cmds = array(
				array(
					"name"=>CozyTouchDeviceActions::CTPC_SETAPCOPERATINGMODE,
					"values"=>"heating"
				)
			);

			parent::genericApplyCommand($device_url,$cmds);
		}
		else
		{
			$cmds = array(
				array(
					"name"=>CozyTouchDeviceActions::CTPC_SETAPCOPERATINGMODE,
					"values"=>"stop"
				)
			);

			parent::genericApplyCommand($device_url,$cmds);
		}
		
		sleep(2);
	}
}
?>