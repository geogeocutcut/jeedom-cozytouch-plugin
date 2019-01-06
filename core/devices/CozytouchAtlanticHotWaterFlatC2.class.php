<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

class CozytouchAtlanticHotWaterFlatC2 extends AbstractCozytouchDevice
{
    const cold_water = 15;
    //[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
		CozyTouchStateName::EQ_HOTWATERCOEFF=>[3,1,1],
		CozyTouchStateName::CTSN_DHWMODE=>[4,0,1],
		CozyTouchStateName::CTSN_MIDDLETEMP=>[5,1,0],
		CozyTouchStateName::CTSN_MIDDLEWATERTEMPIN=>[6,0,0],
		CozyTouchStateName::CTSN_WATERCONSUMPTION=>[7,1,0],
		CozyTouchStateName::CTSN_V40WATERVOLUME=>[8,0,0],
		CozyTouchStateName::CTSN_ELECNRJCONSUMPTION=>[9,0,1],
		CozyTouchStateName::CTSN_EXPECTEDNBSHOWER=>[10,1,0],
		CozyTouchStateName::CTSN_NBSHOWERREMAINING=>[11,0,0],
		
		CozyTouchDeviceEqCmds::SET_BOOST=>[12,0,0],
		
		CozyTouchDeviceEqCmds::SET_AUTOMODE=>[21,1,0],
		CozyTouchDeviceEqCmds::SET_MANUECOINACTIVE=>[22,0,1],

		CozyTouchStateName::CTSN_CONNECT=>[99,1,1],
		'refresh'=>[1,0,0]
    ];
    
	public static function BuildEqLogic($device)
    {
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
        $eqLogic =self::BuildDefaultEqLogic($device);
		
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
		
		$boost = $eqLogic->getCmd(null, 'boost_state');
    	if (!is_object($boost)) {
    		$boost = new cozytouchCmd();
    		$boost->setIsVisible(0);
    	}

    	$boost->setEqLogic_id($eqLogic->getId());
    	$boost->setName(__('boost_state', __FILE__));
    	$boost->setType('info');
    	$boost->setSubType('binary');
    	$boost->setIsHistorized(1);
    	$boost->setLogicalId('boost_state');
    	$boost->save();
    	
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
		$boost_toogle->setValue($boost->getId());
		$boost_toogle->save();


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
			case CozyTouchDeviceEqCmds::SET_AUTOMODE:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_AUTOMODE);
				self::setDHWMode($device_url,'autoMode');
				break;
			case CozyTouchDeviceEqCmds::SET_MANUECOINACTIVE:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_MANUECOINACTIVE);
				self::setDHWMode($device_url,'manualEcoInactive');
				break;
			case CozyTouchDeviceEqCmds::SET_BOOST:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_BOOST." value : ".$_options['slider']);
				self::setBoostDuration($device_url,$_options['slider']);
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
			self::refresh_boost($eqLogic);
			self::refresh_hotwatercoeff($eqLogic);
	
		} 
		catch (Exception $e) {
	
        }
	}
	
	public static function refresh_boost($eqLogic) 
    {
        log::add('cozytouch', 'debug', __('Refresh boost', __FILE__));
        $deviceURL = $eqLogic->getConfiguration('device_url');
        $value="";

		$cmd = Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),$deviceURL.'_'.CozyTouchStateName::CTSN_BOOSTMODEDURATION);
		if (is_object($cmd)) {
			$value=$cmd->execCmd();
        }

        $cmd=Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'boost_state');
		if (is_object($cmd)) {
			$boost =$value>0?1:0;
            $cmd->setCollectDate('');
            $cmd->event($boost);
            log::add('cozytouch', 'debug', __('Boost : ', __FILE__).$boost. "(".$value.")");
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
		
        $cmd = Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),$deviceURL.'_'.CozyTouchStateName::CTSN_MIDDLETEMP);
		if (is_object($cmd)) {
			$valuetmp['middle_temp']=$cmd->execCmd();
        }

        $cmd=Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),CozyTouchStateName::EQ_HOTWATERCOEFF);
		if (is_object($cmd)) {
			$temp=0;
			if(array_key_exists('middle_temp',$valuetmp))
			{
				$temp=$valuetmp['middle_temp'];
			}
			else
			{
				$temp=$valuetmp['temp'];
			}
            $hotwatercoeff = 100*($temp-self::cold_water)/($valuetmp['targettemp']-self::cold_water);
            $cmd->setCollectDate('');
            $cmd->event($hotwatercoeff);
            log::add('cozytouch', 'debug', __('Calcul proportion d eau chaude : ', __FILE__).$hotwatercoeff);
        }
	}

	
	public static function setDHWMode($device_url,$value)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETDHWMODE,
                "values"=>$value
			)
        );
        parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
		$cmds = array(
			array(
					"name"=>CozyTouchDeviceActions::CTPC_RSHDHWMODE,
					"values"=>null
			)
		);
		parent::genericApplyCommand($device_url,$cmds);

		$cmds = array(
			array(
					"name"=>CozyTouchDeviceActions::CTPC_RSHTARGETTEMP,
					"values"=>null
			)
		);
		parent::genericApplyCommand($device_url,$cmds);
	}
}
?>