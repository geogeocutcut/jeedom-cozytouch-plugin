<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

class CozytouchAtlanticAPCBoilerDHWComponent extends AbstractCozytouchDevice
{
    const cold_water = 15;
	//[{order},{beforeLigne},{afterLigne}]
	
	
	const DISPLAY = [
		//CozyTouchStateName::CTSN_DHWONOFF=>[1,0,1],
		//CozyTouchStateName::CTSN_PASSAPCDHWMODE=>[3,1,1],
		CozyTouchDeviceEqCmds::SET_OFF=>[13,1,0],
		CozyTouchDeviceEqCmds::SET_COMFORT=>[14,0,0],
		CozyTouchDeviceEqCmds::SET_ECO=>[15,0,0],
		CozyTouchDeviceEqCmds::SET_INTERNAL=>[16,0,1],
		CozyTouchStateName::CTSN_CONNECT=>[99,1,1],
		'refresh'=>[1,0,0]
    ];
    
	public static function BuildEqLogic($device)
    {
		$deviceURL = $device->getURL();
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
		$eqLogic =self::BuildDefaultEqLogic($device,'boiler');
		$eqLogic->setCategory('energy', 1);
		$eqLogic->save();

		CozyTouchManager::refresh_all();
		
		$onoff_state = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_DHWONOFF );
		if(is_object($onoff_state))
		{
			$onoff_state->setTemplate('mobile', 'hotwater_onoff');
			$onoff_state->setTemplate('dashboard', 'hotwater_onoff');
			$onoff_state->save();
		}

		$off_button = $eqLogic->getCmd(null, CozyTouchDeviceEqCmds::SET_OFF);
		if (!is_object($off_button)) {
			$off_button = new cozytouchCmd();
			$off_button->setLogicalId(CozyTouchDeviceEqCmds::SET_OFF);
		}
		$off_button->setEqLogic_id($eqLogic->getId());
		$off_button->setName(__('Off', __FILE__));
		$off_button->setType('action');
		$off_button->setSubType('other');
		$off_button->save();

		$comfort_button = $eqLogic->getCmd(null, CozyTouchDeviceEqCmds::SET_COMFORT);
		if (!is_object($comfort_button)) {
			$comfort_button = new cozytouchCmd();
			$comfort_button->setLogicalId(CozyTouchDeviceEqCmds::SET_COMFORT);
		}
		$comfort_button->setEqLogic_id($eqLogic->getId());
		$comfort_button->setName(__('Comfort', __FILE__));
		$comfort_button->setType('action');
		$comfort_button->setSubType('other');
		$comfort_button->save();

		$eco_button = $eqLogic->getCmd(null, CozyTouchDeviceEqCmds::SET_ECO);
		if (!is_object($eco_button)) {
			$eco_button = new cozytouchCmd();
			$eco_button->setLogicalId(CozyTouchDeviceEqCmds::SET_ECO);
		}
		$eco_button->setEqLogic_id($eqLogic->getId());
		$eco_button->setName(__('Eco', __FILE__));
		$eco_button->setType('action');
		$eco_button->setSubType('other');
		$eco_button->save();

		
		$internal_button = $eqLogic->getCmd(null, CozyTouchDeviceEqCmds::SET_INTERNAL);
		if (!is_object($internal_button)) {
			$internal_button = new cozytouchCmd();
			$internal_button->setLogicalId(CozyTouchDeviceEqCmds::SET_INTERNAL);
		}
		$internal_button->setEqLogic_id($eqLogic->getId());
		$internal_button->setName(__('Prog', __FILE__));
		$internal_button->setType('action');
		$internal_button->setSubType('other');
		$internal_button->save();

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
				self::setOff($device_url);
				break;
			case CozyTouchDeviceEqCmds::SET_COMFORT:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_COMFORT);
				self::setComfortMode($device_url);
				break;
			case CozyTouchDeviceEqCmds::SET_ECO:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_ECO);
				self::setEcoMode($device_url);
				break;
			case CozyTouchDeviceEqCmds::SET_INTERNAL:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_INTERNAL);
				self::setInternalMode($device_url);
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

	public static function setOff($device_url)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETDHWONOFF,
                "values"=>"off"
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
	}

	public static function setComfortMode($device_url)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETDHWONOFF,
                "values"=>"on"
			),
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETPASSAPCDHWMODE,
                "values"=>"comfort"
			)
        );
        parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
	}

	
	public static function setEcoMode($device_url)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETDHWONOFF,
                "values"=>"on"
			),
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETPASSAPCDHWMODE,
                "values"=>"eco"
			)
        );
        parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
	}

	public static function setInternalMode($device_url)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETDHWONOFF,
                "values"=>"on"
			),
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETPASSAPCDHWMODE,
                "values"=>"internalScheduling"
			)
        );
        parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
	}
}
?>