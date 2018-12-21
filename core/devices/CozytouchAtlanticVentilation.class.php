<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

class CozytouchAtlanticVentilation extends AbstractCozytouchDevice
{
    const cold_water = 15;
    //[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
		CozyTouchStateName::CTSN_CONNECT=>[1,1,1],
		CozyTouchStateName::EQ_VMCMODE=>[2,1,1],
		CozyTouchStateName::CTSN_AIRDEMANDE=>[3,0,0],
		CozyTouchStateName::CTSN_AIRDEMANDEMODE=>[4,0,0],
		CozyTouchStateName::CTSN_VENTILATIONCONFIG=>[5,0,0],
		CozyTouchStateName::CTSN_CO2CONCENTRATION=>[6,0,0],
		'refresh'=>[1,0,0]
    ];
    
	public static function BuildEqLogic($device)
    {
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
        $eqLogic =self::BuildDefaultEqLogic($device);
		$eqLogic->setCategory('energy', 1);

        $states = CozyTouchDeviceStateName::EQLOGIC_STATENAME[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
        $sensors = array();
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
		$eqLogic->save();

		// $cmd= $eqLogic->getCmd(null, $device->getURL().'_'.CozyTouchStateName::CTSN_AIRDEMANDE);
		// $cmd->setTemplate('dashboard', 'vmc');
		// $cmd->setTemplate('mobile', 'vmc');
		// $cmd->save();
		$vent_mode = $eqLogic->getCmd(null,CozyTouchStateName::EQ_VMCMODE );

    	if (!is_object($vent_mode)) {
    		$vent_mode = new cozytouchCmd();
    		$vent_mode->setIsVisible(1);
    	}

    	$vent_mode->setEqLogic_id($eqLogic->getId());
    	$vent_mode->setName(__('Mode VMC', __FILE__));
    	$vent_mode->setType('info');
    	$vent_mode->setSubType('string');
        $vent_mode->setLogicalId(CozyTouchStateName::EQ_VMCMODE);
    	$vent_mode->setTemplate('dashboard', 'vmc');
    	$vent_mode->setTemplate('mobile', 'vmc');
		$vent_mode->save();
		
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

	public static function refresh_vmcmode($eqLogic)
	{
		$deviceURL = $eqLogic->getConfiguration('device_url');
        $value="";
		$cmd = Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),$deviceURL.'_'.CozyTouchStateName::CTSN_AIRDEMANDEMODE);
		if (is_object($cmd)) {
			$value=$cmd->execCmd();
		}
		
		switch($value)
		{
			case 'auto';
				$cmd = Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),$deviceURL.'_'.CozyTouchStateName::CTSN_VENTILATIONMODE);
				log::add('cozytouch', 'debug', __('Ventilation Demand Mode : ', __FILE__).$cmd->execCmd());
				if (is_object($cmd)) {
					$tmp =json_decode($cmd->execCmd());
					$value= $tmp->{'cooling'}=='on'?'refresh':'auto';
				}
				break;
			case 'high':
			case 'boost':
				$value='boost';
				break;
		}

		$cmd=Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),CozyTouchStateName::EQ_VMCMODE);
		if (is_object($cmd)) {
            $cmd->setCollectDate('');
            $cmd->event($value);
            log::add('cozytouch', 'debug', __('VMC Mode : ', __FILE__).$value);
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
			
			self::refresh_vmcmode($eqLogic);
		} 
		catch (Exception $e) {
	
        }
    }

    
}
?>