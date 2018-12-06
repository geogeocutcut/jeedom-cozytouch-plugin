<?php
class AbstractCozytouchDevice
{
    
    
    public static function BuildDefaultEqLogic($device)
    {
        $eqLogic = eqLogic::byLogicalId($device->getVar('oid'), 'cozytouch');
        if (!is_object($eqLogic)) {
            log::add('cozytouch', 'info', 'Device '.$device->getVar('oid').' non existant : creation en cours');
            $eqLogic = new eqLogic();
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
        $deviceURL = $device->getURL();
        $deviceURLShort = explode("#",$device->getURL())[0];
        if(!empty($deviceURLShort))
        {
            log::add('cozytouch', 'info', 'State : '.$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME));
            $states = CozyTouchDeviceStateName::EQLOGIC_STATENAME[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
            if(!empty($states) && is_array($states))
            {
                for($i=0;$i<count($states);$i++)
                {
                    log::add('cozytouch', 'info', 'State : '.$states[$i]);
        
                    $cmdId = $deviceURLShort.$states[$i];
                    $type ="info";
                    $subType = CozyTouchStateName::CTSN_TYPE[$states[$i]];
                    $name = CozyTouchStateName::CTSN_LABEL[$states[$i]];
                    $dashboard =$subType=="numeric"?'tile':($subType=="string"?'badge':'');
                    $mobile =$subType=="numeric"?'tile':($subType=="string"?'badge':'');
                    $value =$subType=="numeric"?0:($subType=="string"?'value':0);
                    self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,$value,$dashboard,$mobile,$i+1);
                }
			}
			
			$actions = CozyTouchDeviceActions::EQLOGIC_ACTIONS[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
			if(!empty($actions) && is_array($actions))
            {
                for($i=0;$i<count($actions);$i++)
                {
                    log::add('cozytouch', 'info', 'action : '.$actions[$i]);
        
                    $cmdId = $actions[$i];
                    $type ="action";
                    $subType = "other";
                    $name = __(CozyTouchDeviceActions::ACTION_LABEL[$actions[$i]], __FILE__);
                    
                    self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,'');
                }
			}
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
	}

    protected static function genericApplyCommand($eqLogic,$cmds)
	{
        $device_url = $eqLogic->getConfiguration('device_url');
		$actions = array();

        $action = new CozyTouchAction();
        $action->deviceURL = $device_url;
        
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
			
	
		$commandsMsg = new CozyTouchCommands();
		$commandsMsg->label= "Mise a jour du device";
		$commandsMsg->actions=$actions;
	
		$clientApi = self::getClient();
		$post_data = $commandsMsg;
		$clientApi->applyCommand($post_data);
	}
}

?>