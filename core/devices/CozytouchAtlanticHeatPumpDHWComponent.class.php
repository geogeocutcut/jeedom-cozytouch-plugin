<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

class CozytouchAtlanticHeatPumpDHWComponent extends AbstractCozytouchDevice
{
    const cold_water = 15;
	//[{order},{beforeLigne},{afterLigne}]
	
	
	const DISPLAY = [
		CozyTouchStateName::CTSN_DHWONOFF=>[1,0,1],
		CozyTouchStateName::CTSN_ECOTARGETDHWTEMPERATURE=>[5,1,0],
		CozyTouchStateName::CTSN_COMFORTTARGETDHWTEMPERATURE=>[6,0,0],
		CozyTouchStateName::CTSN_TARGETDHWTEMPERATURE=>[7,0,1],
		CozyTouchDeviceEqCmds::SET_BOOST=>[12,0,0],
		CozyTouchDeviceEqCmds::SET_ONOFF=>[13,0,0],
		CozyTouchStateName::CTSN_CONNECT=>[99,1,1],
		'refresh'=>[1,0,0]
    ];
    
	public static function BuildEqLogic($device)
    {
		$deviceURL = $device->getURL();
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
		$eqLogic =self::BuildDefaultEqLogic($device,'pump');
		$eqLogic->setCategory('energy', 1);
		$eqLogic->save();

		CozyTouchManager::refresh_all();
		
		$onoff_state = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_DHWONOFF );
		if(is_object($onoff_state))
		{
			$onoff_state->setTemplate('mobile', 'hotwater_onoff');
			$onoff_state->setTemplate('dashboard', 'hotwater_onoff');
			$onoff_state->save();
			$onoff_toogle = $eqLogic->getCmd(null, CozyTouchDeviceEqCmds::SET_ONOFF);
			if (!is_object($onoff_toogle)) {
				$onoff_toogle = new cozytouchCmd();
				$onoff_toogle->setLogicalId(CozyTouchDeviceEqCmds::SET_ONOFF);
			}
			$onoff_toogle->setEqLogic_id($eqLogic->getId());
			$onoff_toogle->setName(__('On/Off', __FILE__));
			$onoff_toogle->setType('action');
			$onoff_toogle->setSubType('slider');
			$onoff_toogle->setTemplate('dashboard', 'toggle');
			$onoff_toogle->setTemplate('mobile', 'toggle');
			$onoff_toogle->setIsVisible(1);
			$onoff_toogle->setValue($onoff_state->getId());
			$onoff_toogle->save();
		}

		$boost_state = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_BOOSTONOFF);
		if(is_object($boost_state))
		{
			$boost_toogle = $eqLogic->getCmd(null, CozyTouchDeviceEqCmds::SET_BOOST);
			if (!is_object($boost_toogle)) {
				$boost_toogle = new cozytouchCmd();
				$boost_toogle->setLogicalId(CozyTouchDeviceEqCmds::SET_BOOST);
			}
			$boost_toogle->setEqLogic_id($eqLogic->getId());
			$boost_toogle->setName(__('Boost', __FILE__));
			$boost_toogle->setType('action');
			$boost_toogle->setSubType('slider');
			$boost_toogle->setTemplate('dashboard', 'toggle');
			$boost_toogle->setTemplate('mobile', 'toggle');
			$boost_toogle->setIsVisible(1);
			$boost_toogle->setValue($boost_state->getId());
			$boost_toogle->save();
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
			case CozyTouchDeviceEqCmds::SET_ONOFF:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_ONOFF." value : ".$_options['slider']);
				self::setOnOffMode($device_url,$_options['slider']);
				$eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_DHWONOFF)->event($_options['slider']);
				
				break;
			case CozyTouchDeviceEqCmds::SET_BOOST:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_BOOST." value : ".$_options['slider']);
				self::setBoostMode($device_url,$_options['slider']);
				$eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_BOOSTONOFF)->event($_options['slider']);
				
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

	public static function setOnOffMode($device_url,$value)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETDHWONOFF,
                "values"=>intval($value)==0?"off":"on"
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
	}

	public static function setBoostMode($device_url,$value)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETBOOSTONOFF,
                "values"=>intval($value)==0?"off":"on"
			)
        );
        parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
	}
}
?>