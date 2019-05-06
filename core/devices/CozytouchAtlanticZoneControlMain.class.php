<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

if (!class_exists('CozytouchAtlanticZoneControlZone')) {
	require_once dirname(__FILE__) . "/CozytouchAtlanticZoneControlZone.class.php";
}

class CozytouchAtlanticZoneControlMain extends AbstractCozytouchDevice
{
	// io:AtlanticPassAPCHeatPumpMainComponent
	//		Equipements à créer
	// 		io:AtlanticPassAPCDHWComponent
	//		io:AtlanticPassAPCHeatingAndCoolingZoneComponent
	//			io:AtlanticPassAPCZoneTemperatureSensor
	//
	//		Capteur à associée
	//		io:AtlanticPassAPCOutsideTemperatureSensor
	//		io:TotalElectricalEnergyConsumptionSensor
	// 		io:DHWRelatedElectricalEnergyConsumptionSensor
	//		io:HeatingRelatedElectricalEnergyConsumptionSensor

    const cold_water = 15;
    //[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
		CozyTouchStateName::CTSN_CONNECT=>[99,1,1],
		CozyTouchStateName::CTSN_PASSAPCOPERATINGMODE=>[2,0,1],
		CozyTouchDeviceEqCmds::SET_OFF=>[30,1,0],
		CozyTouchDeviceEqCmds::SET_ZONECTRLHEAT=>[31,0,0],
		CozyTouchDeviceEqCmds::SET_ZONECTRLCOOL=>[32,0,1],
		CozyTouchDeviceEqCmds::SET_ZONECTRLDRY=>[33,0,0],
		CozyTouchDeviceEqCmds::SET_AUTO=>[34,0,0],
		'refresh'=>[1,0,0]
    ];
    
	public static function BuildEqLogic($device)
    {
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
        $eqLogic =self::BuildDefaultEqLogic($device);
		$eqLogic->setCategory('energy', 1);
		$cmd= $eqLogic->getCmd(null, $device->getURL().'_'.CozyTouchStateName::CTSN_PASSAPCOPERATINGMODE);
		$cmd->setTemplate('dashboard', 'zonetctlmode');
		$cmd->setTemplate('mobile', 'zonetctlmode');
		$cmd->save();

        $states = CozyTouchDeviceStateName::EQLOGIC_STATENAME[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
		$sensors = array();
		$deviceSensors = $device->getSensors();
		$nbSensors = count($deviceSensors);
		log::add('cozytouch', 'info', 'Zones count : '.$nbSensors);
		for ($i = 0; $i <$nbSensors; $i++)
		{
			$sensor=$deviceSensors[$i];
			$sensorURL = $sensor->getURL();
			$sensorModel = $sensor->getModel();
			
			log::add('cozytouch', 'info', $i.' '.$sensorModel);
			// création des équipements
			//  getModel()
			// controllableName: io:AtlanticPassAPCDHWComponent,
			//					 io:AtlanticPassAPCHeatingAndCoolingZoneComponent
			// 					 rattacher io:AtlanticPassAPCZoneTemperatureSensor à l'équipement précédent créé.
			// sinon
			$sensors[] = array($sensorURL,$sensor->getModel());
			log::add('cozytouch', 'info', 'Sensor : '.$sensorURL);
			switch($sensorModel)
			{
				
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLZONE :
					
					log::add('cozytouch', 'info', 'Zone control To Create');
					$j = $i+1;
					if($j<$nbSensors && $deviceSensors[$j]->getModel()===CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONETEMPERATURESENSOR)
					{
						log::add('cozytouch', 'info', 'Add Temperature Sensor');
						$sensorstemp = $sensor->getSensors();
						$sensorstemp[] = $deviceSensors[$j];
						$sensor->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, $sensorstemp);
					}
					CozytouchAtlanticZoneControlZone::BuildEqLogic($sensor);
					break;
			}
			
		}

		$eqLogic->setConfiguration('sensors',$sensors);

		$eqLogic->save();

        self::orderCommand($eqLogic);

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
			else
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
	
		} 
		catch (Exception $e) {
	
        }
	}
}
?>