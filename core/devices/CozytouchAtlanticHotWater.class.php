<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

class CozytouchAtlanticHotWater extends AbstractCozytouchDevice
{
    const cold_water = 15;
    //[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
		CozyTouchStateName::CTSN_DHWMODE=>[4,0,1],
        CozyTouchStateName::EQ_HOTWATERCOEFF=>[3,1,1],
		CozyTouchStateName::CTSN_TEMP=>[10,1,0],
		CozyTouchStateName::CTSN_TARGETTEMP=>[11,0,0],
		CozyTouchStateName::CTSN_WATERCONSUMPTION=>[13,0,1],
		CozyTouchStateName::CTSN_ELECNRJCONSUMPTION=>[14,0,0],
		CozyTouchStateName::CTSN_BOOSTMODEDURATION=>[15,0,0],
		CozyTouchStateName::CTSN_AWAYMODEDURATION=>[16,0,0],
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



        $hotWaterCoefficient = $eqLogic->getCmd(null,CozyTouchStateName::EQ_HOTWATERCOEFF );

    	if (!is_object($hotWaterCoefficient)) {
    		$hotWaterCoefficient = new cozytouchCmd();
    		$hotWaterCoefficient->setIsVisible(1);
    	}

    	$hotWaterCoefficient->setEqLogic_id($eqLogic->getId());
    	$hotWaterCoefficient->setName(__('Proportion eau chaude', __FILE__));
    	$hotWaterCoefficient->setType('info');
    	$hotWaterCoefficient->setSubType('numeric');
        $hotWaterCoefficient->setLogicalId(CozyTouchStateName::EQ_HOTWATERCOEFF);
    	$hotWaterCoefficient->setTemplate('dashboard', 'hotwater');
    	$hotWaterCoefficient->setTemplate('mobile', 'hotwater');
        $hotWaterCoefficient->save();
        
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
					$value = $state->value;
					if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
						$cmd->setCollectDate('');
						$cmd->event($value);
					}
				}
            }
			self::refresh_hotwatercoeff($eqLogic);
	
		} 
		catch (Exception $e) {
	
        }
    }

    public static function refresh_hotwatercoeff($eqLogic) 
    {
        log::add('cozytouch', 'debug', __('Calcul proportion d eau chaude', __FILE__));
        $deviceURL = $eqLogic->getConfiguration('device_url');
        $valuetmp = array();

		$cmd = Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),$deviceURL.'_'.CozyTouchStateName::CTSN_TARGETTEMP);
		if (is_object($cmd)) {
			$valuetmp['targettemp']=$cmd->execCmd();
        }
        $cmd = Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),$deviceURL.'_'.CozyTouchStateName::CTSN_TEMP);
		if (is_object($cmd)) {
			$valuetmp['temp']=$cmd->execCmd();
        }

        $cmd=Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),CozyTouchStateName::EQ_HOTWATERCOEFF);
		if (is_object($cmd)) {
            $hotwatercoeff = 100*($valuetmp['temp']-self::cold_water)/($valuetmp['targettemp']-self::cold_water);
            $cmd->setCollectDate('');
            $cmd->event($hotwatercoeff);
            log::add('cozytouch', 'debug', __('Calcul proportion d eau chaude : ', __FILE__).$hotwatercoeff);
        }
    }
}
?>