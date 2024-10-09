<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

class CozytouchAtlanticHeatPumpHeatZoneComponent extends AbstractCozytouchDevice
{
    //[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
		CozyTouchStateName::CTSN_NAME=>[1,0,0],
		//CozyTouchStateName::CTSN_THERMALCONFIGURATION=>[2,0,0],
		CozyTouchStateName::CTSN_PASSAPCHEATINGPROFILE=>[2,0,1],
		CozyTouchStateName::CTSN_TARGETTEMP=>[3,0,0],
		
		CozyTouchStateName::CTSN_TEMP=>[17,0,1],
		CozyTouchDeviceEqCmds::SET_ONOFF=>[18,1,0],
		CozyTouchDeviceActions::CTPC_SETECOHEATINGTARGET=>[19,0,0],
		CozyTouchDeviceActions::CTPC_SETCOMFORTHEATINGTARGET=>[20,0,0],
		CozyTouchDeviceActions::CTPC_SETDEROGONOFF=>[30,1,0],
		CozyTouchDeviceActions::CTPC_SETDEROGTEMP=>[31,0,0],
		//CozyTouchDeviceActions::CTPC_SETDEROGTIME=>[32,0,0],
		CozyTouchStateName::CTSN_CONNECT=>[99,1,1],
		'refresh'=>[1,0,0]
    ];
    
	public static function BuildEqLogic($device)
    {
        $deviceURL = $device->getURL();
       	log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
		$eqLogic =self::BuildDefaultEqLogic($device);
		$profil_state = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_PASSAPCHEATINGPROFILE);
		$profil_state->setTemplate('dashboard', 'cozytouch::heatmode');
		$profil_state->setTemplate('mobile', 'cozytouch::heatmode');
		$profil_state->save();
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
		$eqLogic->setCategory('energy', 1);
		$eqLogic->save();

		self::refresh($eqLogic);
		
		$onoff_state = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_HEATINGONOFF);
		if(is_object($onoff_state))
		{
			$onoff_toogle = $eqLogic->getCmd(null, CozyTouchDeviceEqCmds::SET_ONOFF);
			if (!is_object($onoff_toogle)) {
				$onoff_toogle = new cozytouchCmd();
				$onoff_toogle->setLogicalId(CozyTouchDeviceEqCmds::SET_ONOFF);
			}
			$onoff_toogle->setEqLogic_id($eqLogic->getId());
			$onoff_toogle->setName(__('Heat On/Off', __FILE__));
			$onoff_toogle->setType('action');
			$onoff_toogle->setSubType('slider');
			$onoff_toogle->setTemplate('dashboard', 'cozytouch::toggle');
			$onoff_toogle->setTemplate('mobile', 'cozytouch::toggle');
			$onoff_toogle->setIsVisible(1);
			$onoff_toogle->setValue($onoff_state->getId());
			$onoff_toogle->save();
		}

		$derog_state = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_DEROGATIONONOFF);
		if(is_object($derog_state))
		{
			$derog_toogle = $eqLogic->getCmd(null, CozyTouchDeviceActions::CTPC_SETDEROGONOFF);
			if (!is_object($derog_toogle)) {
				$derog_toogle = new cozytouchCmd();
				$derog_toogle->setLogicalId(CozyTouchDeviceActions::CTPC_SETDEROGONOFF);
			}
			$derog_toogle->setEqLogic_id($eqLogic->getId());
			$derog_toogle->setName(__('Derog On/Off', __FILE__));
			$derog_toogle->setType('action');
			$derog_toogle->setSubType('slider');
			$derog_toogle->setTemplate('dashboard', 'cozytouch::toggle');
			$derog_toogle->setTemplate('mobile', 'cozytouch::toggle');
			$derog_toogle->setIsVisible(1);
			$derog_toogle->setValue($derog_state->getId());
			$derog_toogle->save();
		}
		// comfort
		$targettemp = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_COMFORTHEATINGTARGETTEMP );
		if(is_object($targettemp))
		{
			$mini = 18;
			$maxi = 28;

			$thermo = $eqLogic->getCmd(null,CozyTouchDeviceActions::CTPC_SETCOMFORTHEATINGTARGET );
			if (!is_object($thermo)) {
				$thermo = new cozytouchCmd();
				$thermo->setLogicalId(CozyTouchDeviceActions::CTPC_SETCOMFORTHEATINGTARGET);
			}
			$thermo->setEqLogic_id($eqLogic->getId());
			$thermo->setName(__('Heating Comfort', __FILE__));
			$thermo->setType('action');
			$thermo->setSubType('slider');
			$thermo->setTemplate('dashboard', 'button');
			$thermo->setTemplate('mobile', 'button');
			$thermo->setConfiguration('maxValue', $maxi);
			$thermo->setConfiguration('minValue', $mini);
			$thermo->setValue($targettemp->getId());
			$thermo->save();
		}

		//Eco
		$targettemp = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_ECOHEATINGTARGETTEMP );
		if(is_object($targettemp))
		{
			$mini = 7;
			$maxi = 25;

			$thermo = $eqLogic->getCmd(null,CozyTouchDeviceActions::CTPC_SETECOHEATINGTARGET );
			if (!is_object($thermo)) {
				$thermo = new cozytouchCmd();
				$thermo->setLogicalId(CozyTouchDeviceActions::CTPC_SETECOHEATINGTARGET);
			}
			$thermo->setEqLogic_id($eqLogic->getId());
			$thermo->setName(__('Heating Eco', __FILE__));
			$thermo->setType('action');
			$thermo->setSubType('slider');
			$thermo->setTemplate('dashboard', 'button');
			$thermo->setTemplate('mobile', 'button');
			$thermo->setConfiguration('maxValue', $maxi);
			$thermo->setConfiguration('minValue', $mini);
			$thermo->setValue($targettemp->getId());
			$thermo->save();
		}

		// Derog
		$targettemp = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_DEROGTARGETTEMP );
		if(is_object($targettemp))
		{
			$mini = 15;
			$maxi = 28;

			$thermo = $eqLogic->getCmd(null,CozyTouchDeviceActions::CTPC_SETDEROGTEMP );
			if (!is_object($thermo)) {
				$thermo = new cozytouchCmd();
				$thermo->setLogicalId(CozyTouchDeviceActions::CTPC_SETDEROGTEMP);
			}
			$thermo->setEqLogic_id($eqLogic->getId());
			$thermo->setName(__('Derogation Temp.', __FILE__));
			$thermo->setType('action');
			$thermo->setSubType('slider');
			$thermo->setTemplate('dashboard', 'button');
			$thermo->setTemplate('mobile', 'button');
			$thermo->setConfiguration('maxValue', $maxi);
			$thermo->setConfiguration('minValue', $mini);
			$thermo->setValue($targettemp->getId());
			$thermo->save();
		}

		$targettemp = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_DEROGATIONREMAININGTIME );
		if(is_object($targettemp))
		{
			$mini = 0;
			$maxi = 24;

			$thermo = $eqLogic->getCmd(null,CozyTouchDeviceActions::CTPC_SETDEROGTIME );
			if (!is_object($thermo)) {
				$thermo = new cozytouchCmd();
				$thermo->setLogicalId(CozyTouchDeviceActions::CTPC_SETDEROGTIME);
			}
			$thermo->setEqLogic_id($eqLogic->getId());
			$thermo->setName(__('Derogation time', __FILE__));
			$thermo->setType('action');
			$thermo->setSubType('slider');
			$thermo->setTemplate('dashboard', 'cozytouch::numeric');
			$thermo->setTemplate('mobile', 'cozytouch::numeric');
			$thermo->setConfiguration('maxValue', $maxi);
			$thermo->setConfiguration('minValue', $mini);
			$thermo->setValue($targettemp->getId());
			$thermo->save();
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
				$eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_HEATINGONOFF)->event($_options['slider']);
				break;

			case CozyTouchDeviceActions::CTPC_SETCOMFORTHEATINGTARGET:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceActions::CTPC_SETCOMFORTHEATINGTARGET." value : ".$_options['slider']);
				$min = $cmd->getConfiguration('minValue');
				$max = $cmd->getConfiguration('maxValue');
				
				if (!isset($_options['slider']) || $_options['slider'] == '' || !is_numeric(intval($_options['slider']))) {
					$_options['slider'] = (($max - $min) / 2) + $min;
				}
				if ($_options['slider'] > $max) {
					$_options['slider'] = $max;
				}
				if ($_options['slider'] < $min) {
					$_options['slider'] = $min;
				}
				
				$eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_COMFORTHEATINGTARGETTEMP)->event($_options['slider']);
				self::setComfortTargetTemp($device_url,$_options['slider']);
				break;

			case CozyTouchDeviceActions::CTPC_SETECOHEATINGTARGET:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceActions::CTPC_SETECOHEATINGTARGET." value : ".$_options['slider']);
				$min = $cmd->getConfiguration('minValue');
				$max = $cmd->getConfiguration('maxValue');
				
				if (!isset($_options['slider']) || $_options['slider'] == '' || !is_numeric(intval($_options['slider']))) {
					$_options['slider'] = (($max - $min) / 2) + $min;
				}
				if ($_options['slider'] > $max) {
					$_options['slider'] = $max;
				}
				if ($_options['slider'] < $min) {
					$_options['slider'] = $min;
				}
				
				$eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_ECOHEATINGTARGETTEMP)->event($_options['slider']);
				self::setEcoTargetTemp($device_url,$_options['slider']);
				break;

			case CozyTouchDeviceActions::CTPC_SETDEROGTEMP:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceActions::CTPC_SETDEROGTEMP." value : ".$_options['slider']);
				$min = $cmd->getConfiguration('minValue');
				$max = $cmd->getConfiguration('maxValue');
				
				if (!isset($_options['slider']) || $_options['slider'] == '' || !is_numeric(intval($_options['slider']))) {
					$_options['slider'] = (($max - $min) / 2) + $min;
				}
				if ($_options['slider'] > $max) {
					$_options['slider'] = $max;
				}
				if ($_options['slider'] < $min) {
					$_options['slider'] = $min;
				}
				
				$eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_DEROGTARGETTEMP)->event($_options['slider']);
				$refresh=false;
				break;
			case CozyTouchDeviceActions::CTPC_SETDEROGTIME:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceActions::CTPC_SETDEROGTIME." value : ".$_options['slider']);
				$min = $cmd->getConfiguration('minValue');
				$max = $cmd->getConfiguration('maxValue');
				
				if (!isset($_options['slider']) || $_options['slider'] == '' || !is_numeric(intval($_options['slider']))) {
					$_options['slider'] = (($max - $min) / 2) + $min;
				}
				if ($_options['slider'] > $max) {
					$_options['slider'] = $max;
				}
				if ($_options['slider'] < $min) {
					$_options['slider'] = $min;
				}
				
				$eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_DEROGATIONREMAININGTIME)->event($_options['slider']);
				$refresh=false;
				break;
			case CozyTouchDeviceActions::CTPC_SETDEROGONOFF:
				$derog_temp =floatval($eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_DEROGTARGETTEMP)->execCmd());
				$derog_time =intval($eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_DEROGATIONREMAININGTIME)->execCmd()); 
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceActions::CTPC_SETDEROGONOFF." value : ".$_options['slider']." temp : ".$derog_temp. " time : ".$derog_time);
				
				self::setDerogTemp($device_url,intval($_options['slider']),$derog_temp,$derog_time);
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

	public static function setOnOffMode($device_url,$value)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETHEATINGONOFF,
                "values"=>intval($value)==0?"off":"on"
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
	}

	public static function setDerogTemp($device_url,$value,$temp,$time)
	{
		if($value==1)
		{
			$cmds = array(
				array(
					"name"=>CozyTouchDeviceActions::CTPC_SETDEROGTEMP,
					"values"=>$temp>0?$temp:22
				),
				
				array(
					"name"=>CozyTouchDeviceActions::CTPC_SETDEROGTIME,
					"values"=>4
				),
				
				array(
					"name"=>CozyTouchDeviceActions::CTPC_SETDEROGONOFF,
					"values"=>'on',
				)
			);
			parent::genericApplyCommand($device_url,$cmds);
			
			
		}
		else
		{
			$cmds = array(
				array(
					"name"=>CozyTouchDeviceActions::CTPC_SETDEROGONOFF,
					"values"=>'off',
				)
			);
			parent::genericApplyCommand($device_url,$cmds);
		}
		sleep(1);
		$cmds = array(
			array(
				"name"=>CozyTouchDeviceActions::CTPC_RSHDEROGTIME,
				"values"=>null,
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

	public static function setComfortTargetTemp($device_url,$value)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETCOMFORTHEATINGTARGET,
                "values"=>floatval($value)
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
		$cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_RSHTARGETTEMP,
                "values"=>null
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
	}

	public static function setEcoTargetTemp($device_url,$value)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETECOHEATINGTARGET,
                "values"=>floatval($value)
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(1);
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