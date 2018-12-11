<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

class CozytouchAtlanticHeatSystem extends AbstractCozytouchDevice
{
	//[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
		CozyTouchStateName::CTSN_TARGETHEATLEVEL=>[1,0,1],
		CozyTouchDeviceEqCmds::SET_OFF=>[20,1,0],
		CozyTouchDeviceEqCmds::SET_FROSTPROTECT=>[21,0,0],
		CozyTouchDeviceEqCmds::SET_ECO=>[22,0,1],
		CozyTouchDeviceEqCmds::SET_COMFORT2=>[23,1,0],
		CozyTouchDeviceEqCmds::SET_COMFORT1=>[24,0,0],
		CozyTouchDeviceEqCmds::SET_COMFORT=>[25,0,1],
		'refresh'=>[1,0,0]
	];


	public static function BuildEqLogic($device)
    {
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
		$eqLogic = self::BuildDefaultEqLogic($device);
		$eqLogic->setCategory('heating', 1);
		$eqLogic->save();

		$cmd= $eqLogic->getCmd(null, $device->getURL().'_'.CozyTouchStateName::CTSN_TARGETHEATLEVEL);
		$cmd->setTemplate('dashboard', 'heatmode');
		$cmd->setTemplate('mobile', 'heatmode');
		$cmd->save();

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
			else
			{
				$cmd->setIsVisible(0);
			}
			$cmd->save();
		}
	}

    public static function Execute($cmd,$_options= array())
    {
		$refresh=true;
		$eqLogic = $cmd->getEqLogic();
		$device_url=$eqLogic->getConfiguration('device_url');
        switch($cmd->getLogicalId())
        {
    		case 'refresh':
    			break;
    		case CozyTouchDeviceEqCmds::SET_OFF:
                self::setOff($device_url,'off');
    			break;
    			
    		case CozyTouchDeviceEqCmds::SET_FROSTPROTECT:
                self::setHeatingLevel($device_url,'frostprotection');
    			break;
    			
			case CozyTouchDeviceEqCmds::SET_ECO:
                self::setHeatingLevel($device_url,'eco');
    			break;

			case CozyTouchDeviceEqCmds::SET_COMFORT2:
                self::setHeatingLevel($device_url,'comfort-2');
    			break;
    			
			case CozyTouchDeviceEqCmds::SET_COMFORT1:
                self::setHeatingLevel($device_url,'comfort-1');
    			break;
			
			case CozyTouchDeviceEqCmds::SET_COMFORT:
                self::setHeatingLevel($device_url,'comfort');
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
		log::add('cozytouch', 'info', 'refresh : '.$eqLogic->getName());
		try {

			$device_url=$eqLogic->getConfiguration('device_url');
			$controllerName = $eqLogic->getConfiguration('device_model');

			self::refreshHeatingLevel($device_url);

			$clientApi = CozyTouchManager::getClient();
			$states = $clientApi->getDeviceInfo($device_url,$controllerName);
			foreach ($states as $state)
			{
				$cmd_array = Cmd::byLogicalId($device_url."_".$state->name);
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
		} 
		catch (Exception $e) {
	
		}
	}
    
    public static function refreshHeatingLevel($device_url)
	{
			
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHHEATINGLVL,
                    "values"=>null
            )
		);
		parent::genericApplyCommand($device_url,$cmds);
			
	}
	
	public static function setHeatingLevel($device_url,$value)
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
		parent::genericApplyCommand($device_url,$cmds);
    }
}
?>