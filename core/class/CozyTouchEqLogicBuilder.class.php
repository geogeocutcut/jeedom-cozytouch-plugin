<?php
if (!class_exists('CozyTouchDeviceStateName')) {
	require_once dirname(__FILE__) . '/../../3rdparty/CozyTouch-API-PHP/Constants/AppliCommonPublic.php';
}
class CozyTouchEqLogicBuilder
{
    public static function BuildDefaultDevice($eqLogic,$device)
    {
        $deviceURL = $device->getURL();
        $deviceURLShort = explode("#",$device->getURL())[0];
        if(!empty($deviceURLShort))
        {
            log::add('cozytouch', 'info', 'State : '.$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME));
            $states =CozyTouchDeviceStateName::EQLOGIC_STATENAME[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
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
    }
    public static function BuildAtlanticHeatSystem($eqLogic,$device)
    {
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
        self::BuildDefaultDevice($eqLogic,$device);
        log::add('cozytouch', 'info', 'creation ou update thermostat');

    	$order = $eqLogic->getCmd(null, 'order');

    	if (!is_object($order)) {
    		$order = new cozytouchCmd();
    		$order->setIsVisible(0);
    	}

    	$order->setEqLogic_id($eqLogic->getId());
    	$order->setName(__('Consigne', __FILE__));
    	$order->setType('info');
    	$order->setSubType('numeric');
    	$order->setIsHistorized(1);
    	$order->setLogicalId('order');
    	$order->setUnite('°C');
    	$order->setConfiguration('maxValue', $eqLogic->getConfiguration('order_max'));
        $order->setConfiguration('minValue', $eqLogic->getConfiguration('order_min'));
    	$order->save();
    	
    	$thermostat = $eqLogic->getCmd(null, 'cozytouchThermostat');
    	if (!is_object($thermostat)) {
    		$thermostat = new cozytouchCmd();
    	}
    	$thermostat->setEqLogic_id($eqLogic->getId());
    	$thermostat->setName(__('Thermostat', __FILE__));
    	$thermostat->setConfiguration('maxValue', $eqLogic->getConfiguration('order_max'));
    	$thermostat->setConfiguration('minValue', $eqLogic->getConfiguration('order_min'));
    	$thermostat->setType('action');
    	$thermostat->setSubType('slider');
    	$thermostat->setUnite('°C');
    	$thermostat->setLogicalId('cozytouchThermostat');
    	$thermostat->setTemplate('dashboard', 'thermostat');
    	$thermostat->setTemplate('mobile', 'thermostat');
    	$thermostat->setIsVisible(1);
		$thermostat->setValue($order->getId());
		$thermostat->setOrder(99);
    	$thermostat->save();
    }

    public static function BuildAtlanticHotWater($eqLogic,$device)
    {
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
        self::BuildDefaultDevice($eqLogic,$device);
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
}
?>