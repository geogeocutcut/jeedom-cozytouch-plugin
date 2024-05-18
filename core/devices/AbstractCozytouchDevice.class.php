<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";

if (!class_exists('CozyTouchManager')) {
	require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";
}
if (!class_exists('CozyTouchAction')) {
	require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/objects/CozyTouchAction.class.php";
}
if (!class_exists('CozyTouchCommand')) {
	require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/objects/CozyTouchCommand.class.php";
}
if (!class_exists('CozyTouchCommands')) {
	require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/objects/CozyTouchCommands.class.php";
}

class AbstractCozytouchDevice
{
    public static function BuildDefaultEqLogic($device,$attached='')
    {
        $eqLogic = eqLogic::byLogicalId($device->getVar(CozyTouchDeviceInfo::CTDI_OID), 'cozytouch');
        if (!is_object($eqLogic)) {
            log::add('cozytouch', 'info', 'Device '.$device->getVar(CozyTouchDeviceInfo::CTDI_OID).' non existant : creation en cours');
            $eqLogic = new eqLogic();
            $eqLogic->setEqType_name('cozytouch');
            $eqLogic->setIsEnable(1);
            $eqLogic->setName($device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
            $eqLogic->setLogicalId($device->getVar(CozyTouchDeviceInfo::CTDI_OID));

            $eqLogic->setConfiguration('attached_device', $attached);
            $eqLogic->setConfiguration('type_device', $device->getVar(CozyTouchDeviceInfo::CTDI_TYPEDEVICE));
            $eqLogic->setConfiguration('device_model', $device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME));
            $eqLogic->setConfiguration('device_url', $device->getVar(CozyTouchDeviceInfo::CTDI_URL));

            
            $eqLogic->setIsVisible(1);

            $eqLogic->save();
        }
        $deviceURL = $device->getURL();
        if(!empty($deviceURL))
        {
            log::add('cozytouch', 'debug', 'State : '.$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME));
            $states = CozyTouchDeviceStateName::EQLOGIC_STATENAME[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
            if(!empty($states) && is_array($states))
            {
                foreach($device->getStates() as $state)
                {
                    if(in_array($state->name,$states))
                    {
                        log::add('cozytouch', 'debug', 'State : '.$state->name);
                        $order = 0;
                        $cmdId = $deviceURL.'_'.$state->name;
                        $type ="info";
                        $subType = CozyTouchStateName::CTSN_TYPE[$state->name];
                        $name = CozyTouchStateName::CTSN_LABEL[$state->name];
                        if(CozyTouchStateName::CTSN_CONNECT==$state->name)
                        {
                            $dashboard ='cozytouch::connect';
                            $mobile ='cozytouch::connect';
                            $order =99;
                        }
                        else{
                            $dashboard =CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
                            $mobile =CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
                        }
                        $value =$subType=="numeric"?0:($subType=="string"?'value':0);
                        self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,$value,$dashboard,$mobile,$order==0?$i+1:$order);
                    }
                }
			}
			
			$actions = CozyTouchDeviceEqCmds::EQLOGIC_ACTIONS[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
			if(!empty($actions) && is_array($actions))
            {
                for($i=0;$i<count($actions);$i++)
                {
                    log::add('cozytouch', 'debug', 'action : '.$actions[$i]);
        
                    $cmdId = $actions[$i];
                    $type ="action";
                    $subType = "other";
                    $name = __(CozyTouchDeviceEqCmds::ACTION_LABEL[$actions[$i]], __FILE__);
                    
                    self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,'');
                }
            }
            self::upsertCommand($eqLogic,'refresh','action','other',__('Refresh', __FILE__),1,'');

            
        }

        return $eqLogic;
    }

    protected static function upsertCommand($eqLogic,$cmdId,$type,$subType,$name,$visible=1,$value='',$dashboard ='',$mobile='',$order=0,$isHistorized=0)
	{

		log::add('cozytouch', 'info', 'command '.$name.'  : creation ou update en cours');
        $cmd = $eqLogic->getCmd($type, $cmdId);
        if (!is_object($cmd)) 
        {
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
        
        return $cmd;
	}

    protected static function genericApplyCommand($device_url,$cmds)
	{
		$actions = array();

        $action = new CozyTouchAction();
        $action->deviceURL = $device_url;
        
        foreach($cmds as $cmd)	
        {
            $command = new CozyTouchCommand();
            $command->name=$cmd['name'];
            if($cmd['values'] !== null)
            {
                $command->parameters[]=$cmd['values'];
            }

            $action->commands[]=$command;
        }

        $actions[]=$action;
			
	
		$commandsMsg = new CozyTouchCommands();
		$commandsMsg->label= "Mise a jour du device";
		$commandsMsg->actions=$actions;
	
		$clientApi = CozyTouchManager::getClient();
		$post_data = $commandsMsg;
		$clientApi->applyCommand($post_data);
    }
}


?>