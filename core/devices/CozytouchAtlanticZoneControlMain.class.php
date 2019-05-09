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
		CozyTouchStateName::EQ_ZONECTRLMODE=>[2,0,1],
		CozyTouchDeviceEqCmds::SET_OFF=>[30,1,0],
		CozyTouchDeviceEqCmds::SET_AUTO=>[31,0,1],
		CozyTouchDeviceEqCmds::SET_ZONECTRLHEAT=>[32,0,0],
		CozyTouchDeviceEqCmds::SET_ZONECTRLCOOL=>[33,0,0],
		CozyTouchDeviceEqCmds::SET_ZONECTRLDRY=>[34,0,1],
		'refresh'=>[1,0,0]
    ];
    
	public static function BuildEqLogic($device)
    {
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
        $eqLogic =self::BuildDefaultEqLogic($device);
		$eqLogic->setCategory('energy', 1);

		$cmd= $eqLogic->getCmd(null, $device->getURL().'_'.CozyTouchStateName::EQ_ZONECTRLMODE);
		if (!is_object($cmd)) {
    		$cmd = new cozytouchCmd();
    		$cmd->setIsVisible(1);
    	}
    	$cmd->setEqLogic_id($eqLogic->getId());
    	$cmd->setName(__(CozyTouchStateName::CTSN_LABEL[CozyTouchStateName::EQ_ZONECTRLMODE], __FILE__));
    	$cmd->setType('info');
    	$cmd->setSubType('string');
        $cmd->setLogicalId( $device->getURL().'_'.CozyTouchStateName::EQ_ZONECTRLMODE);
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
			case CozyTouchDeviceEqCmds::SET_OFF:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_OFF);
				self::set_stop_mode($device_url);
				break;
			case CozyTouchDeviceEqCmds::SET_ZONECTRLHEAT:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_ZONECTRLHEAT);
				self::set_heating_mode($device_url);
				break;
			case CozyTouchDeviceEqCmds::SET_ZONECTRLCOOL:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_ZONECTRLCOOL);
				self::set_cooling_mode($device_url);
				break;
			case CozyTouchDeviceEqCmds::SET_ZONECTRLDRY:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_ZONECTRLDRY);
				self::set_drying_mode($device_url);
				break;
			case CozyTouchDeviceEqCmds::SET_AUTO:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_AUTO);
				self::set_auto_mode($device_url);
				break;
		}
		if($refresh)
		{
			sleep(6);
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
			
			self::refresh_mode($eqLogic);
		} 
		catch (Exception $e) {
	
        }
	}

	public static function refresh_mode($eqDevice) 
    {
		log::add('cozytouch', 'debug', 'Refresh mode');
		$deviceURL = $eqDevice->getConfiguration('device_url');

		$cmd_array = Cmd::byLogicalId($deviceURL.'_'.CozyTouchStateName::CTSN_PASSAPCOPERATINGMODE);
		if(is_array($cmd_array) && $cmd_array!=null)
		{
			$cmd=$cmd_array[0];
			$mode=$cmd->execCmd();
		}

		$cmd_array = Cmd::byLogicalId($deviceURL.'_'.CozyTouchStateName::CTSN_HEATINGCOOLINGAUTOSWITCH);
		if(is_array($cmd_array) && $cmd_array!=null)
		{
			$cmd=$cmd_array[0];
			$auto=$cmd->execCmd();
		}

		$cmd=Cmd::byEqLogicIdAndLogicalId($eqDevice->getId(),$deviceURL.'_'.CozyTouchStateName::EQ_ZONECTRLMODE);
		if(is_object($cmd))
		{
			if($auto=='on')
			{
				$cmd->setCollectDate('');
				$cmd->event('auto');
				log::add('cozytouch', 'info', __('Mode ', __FILE__).' auto');
			}
			else
			{
				$cmd->setCollectDate('');
				$cmd->event($mode);
				log::add('cozytouch', 'info', __('Mode ', __FILE__).$mode);
			}
		}
	}

	protected static function set_heating_mode($device_url)
	{
		$cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_RSHZONESAPCHEATINGPROFILE,
				"values"=>null
			),
			array(
				"name"=>CozyTouchDeviceActions::CTPC_RSHZONESTARGETTEMP,
				"values"=>null
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
		$cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETHEATINGCOOLINGAUTOSWITCH,
				"values"=>'off'
			),
			array(
				"name"=>CozyTouchDeviceActions::CTPC_SETAPCOPERATINGMODE,
				"values"=>'heating'
			)
        );
        parent::genericApplyCommand($device_url,$cmds);
	}

	protected static function set_cooling_mode($device_url)
	{
		$cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_RSHZONESAPCCOOLINGPROFILE,
				"values"=>null
			),
			array(
				"name"=>CozyTouchDeviceActions::CTPC_RSHZONESTARGETTEMP,
				"values"=>null
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
		$cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETHEATINGCOOLINGAUTOSWITCH,
				"values"=>'off'
			),
			array(
				"name"=>CozyTouchDeviceActions::CTPC_SETAPCOPERATINGMODE,
				"values"=>'cooling'
			)
        );
        parent::genericApplyCommand($device_url,$cmds);
	}

	protected static function set_drying_mode($device_url)
	{
		$cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_RSHZONESAPCCOOLINGPROFILE,
				"values"=>null
			),
			array(
				"name"=>CozyTouchDeviceActions::CTPC_RSHZONESTARGETTEMP,
				"values"=>null
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
		$cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETHEATINGCOOLINGAUTOSWITCH,
				"values"=>'off'
			),
			array(
				"name"=>CozyTouchDeviceActions::CTPC_SETAPCOPERATINGMODE,
				"values"=>'drying'
			)
        );
        parent::genericApplyCommand($device_url,$cmds);
	}

	protected static function set_stop_mode($device_url)
	{
		$cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETHEATINGCOOLINGAUTOSWITCH,
				"values"=>'off'
			),
			array(
				"name"=>CozyTouchDeviceActions::CTPC_SETAPCOPERATINGMODE,
				"values"=>'stop'
			)
        );
        parent::genericApplyCommand($device_url,$cmds);
	}

	protected static function set_auto_mode($device_url)
	{
		$cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETHEATINGCOOLINGAUTOSWITCH,
				"values"=>'on'
			),
			array(
				"name"=>CozyTouchDeviceActions::CTPC_RSHMODE,
				"values"=>null
			)
        );
        parent::genericApplyCommand($device_url,$cmds);
	}
}
?>