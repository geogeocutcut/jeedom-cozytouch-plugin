<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

class CozytouchAtlanticDimmableLight extends AbstractCozytouchDevice
{
    //[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
		CozyTouchStateName::CTSN_NAME=>[1,0,0],
		
		CozyTouchDeviceActions::CTPC_SETONOFFLIGHT=>[10,1,0],
		CozyTouchDeviceActions::CTPC_SETOCCUPANCYACTIVATION=>[11,0,0],
		CozyTouchDeviceActions::CTPC_SETINTENSITY=>[20,1,0],
		CozyTouchStateName::CTSN_CONNECT=>[99,1,1],
		'refresh'=>[1,0,0]
    ];
    
	public static function BuildEqLogic($device)
    {
        $deviceURL = $device->getURL();
       	log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
		$eqLogic =self::BuildDefaultEqLogic($device);
		$states = CozyTouchDeviceStateName::EQLOGIC_STATENAME[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
		$sensors = array();
		$i=0;
		foreach ($device->getSensors() as $sensor)
		{
			$sensorURL = $sensor->getURL();
			$sensors[] = array($sensorURL,$sensor->getModel());
			log::add('cozytouch', 'info', 'Sensor : '.$sensorURL);
			// state du capteur
			
			foreach ($sensor->getStates() as $state)
			{
				if(in_array($state->name,$states))
				{
					log::add('cozytouch', 'info', 'State : '.$state->name);
					$i++;
					$cmdId = $sensorURL.'_'.$state->name;
					$type ="info";
					$subType = CozyTouchStateName::CTSN_TYPE[$state->name];
					$name = CozyTouchStateName::CTSN_LABEL[$state->name];
					$dashboard =CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
					$mobile =CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
					$value =$subType=="numeric"?0:($subType=="string"?'value':0);
					self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,$value,$dashboard,$mobile,$i+1);
				}
			}
		}
		$eqLogic->setConfiguration('sensors',$sensors);
		$eqLogic->setCategory('energy', 1);
		$eqLogic->save();
		
		self::refresh($eqLogic);

		// on off
		$onoff_state = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_LIGHTSTATE);
		if(is_object($onoff_state))
		{
			$onoff_toogle = $eqLogic->getCmd(null, CozyTouchDeviceActions::CTPC_SETONOFFLIGHT);
			if (!is_object($onoff_toogle)) {
				$onoff_toogle = new cozytouchCmd();
				$onoff_toogle->setLogicalId(CozyTouchDeviceActions::CTPC_SETONOFFLIGHT);
			}
			$onoff_toogle->setEqLogic_id($eqLogic->getId());
			$onoff_toogle->setName(__('Light On/Off', __FILE__));
			$onoff_toogle->setType('action');
			$onoff_toogle->setSubType('slider');
			$onoff_toogle->setTemplate('dashboard', 'cozytouch::toggle');
			$onoff_toogle->setTemplate('mobile', 'cozytouch::toggle');
			$onoff_toogle->setIsVisible(1);
			$onoff_toogle->setValue($onoff_state->getId());
			$onoff_toogle->save();
		}

		// auto on off
		$auto_state = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_OCCUPANCYACTIVATION);
		if(is_object($auto_state))
		{
			$auto_toogle = $eqLogic->getCmd(null, CozyTouchDeviceActions::CTPC_SETOCCUPANCYACTIVATION);
			if (!is_object($auto_toogle)) {
				$auto_toogle = new cozytouchCmd();
				$auto_toogle->setLogicalId(CozyTouchDeviceActions::CTPC_SETOCCUPANCYACTIVATION);
			}
			$auto_toogle->setEqLogic_id($eqLogic->getId());
			$auto_toogle->setName(__('Auto On/Off', __FILE__));
			$auto_toogle->setType('action');
			$auto_toogle->setSubType('slider');
			$auto_toogle->setTemplate('dashboard', 'cozytouch::toggle');
			$auto_toogle->setTemplate('mobile', 'cozytouch::toggle');
			$auto_toogle->setIsVisible(1);
			$auto_toogle->setValue($auto_state->getId());
			$auto_toogle->save();
		}
		
		// intensity
		$intensity_state = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_LIGHTINTENSITY);
		if(is_object($intensity_state))
		{

			$intensity = $eqLogic->getCmd(null,CozyTouchDeviceActions::CTPC_SETINTENSITY );
			if (!is_object($intensity)) {
				$intensity = new cozytouchCmd();
				$intensity->setLogicalId(CozyTouchDeviceActions::CTPC_SETINTENSITY);
			}
			$intensity->setEqLogic_id($eqLogic->getId());
			$intensity->setName(__('Dimmer', __FILE__));
			$intensity->setType('action');
			$intensity->setSubType('slider');
			$intensity->setTemplate('dashboard', 'default');
			$intensity->setTemplate('mobile', 'default');
			$intensity->setValue($intensity_state->getId());
			$intensity->save();
		}

		
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
				
			case CozyTouchDeviceActions::CTPC_SETONOFFLIGHT:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceActions::CTPC_SETONOFFLIGHT." value : ".$_options['slider']);
				self::setLightOnOffMode($device_url,$_options['slider']);
				$eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_LIGHTSTATE)->event($_options['slider']);
				break;
				
			case CozyTouchDeviceActions::CTPC_SETOCCUPANCYACTIVATION:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceActions::CTPC_SETOCCUPANCYACTIVATION." value : ".$_options['slider']);
				self::setAutoMode($device_url,$_options['slider']);
				$eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_OCCUPANCYACTIVATION)->event($_options['slider']);
				break;
			
			case CozyTouchDeviceActions::CTPC_SETINTENSITY:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceActions::CTPC_SETINTENSITY." value : ".$_options['slider']);
				self::setLightIntensite($device_url,$_options['slider']);
				$eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_LIGHTINTENSITY)->event($_options['slider']);
				break;

			default:
				$refresh=false;
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

	public static function setLightOnOffMode($device_url,$value)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETONOFFLIGHT,
                "values"=>intval($value)==0?"off":"on"
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(3);
	}

	public static function setAutoMode($device_url,$value)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETOCCUPANCYACTIVATION,
                "values"=>intval($value)==0?"inactive":"active"
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(3);
	}

	public static function setLightIntensite($device_url,$value)
	{
		$val = ((intval($value)>50) ? 100 : ((intval($value)>20) ? 30 : ((intval($value)>5) ? 10 : 1)));
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETINTENSITY,
                "values"=>$val
			)
        );
        parent::genericApplyCommand($device_url,$cmds);
		sleep(3);
	}
}
?>