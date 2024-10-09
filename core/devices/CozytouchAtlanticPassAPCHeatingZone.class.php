<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

class CozytouchAtlanticPassAPCHeatingZone extends AbstractCozytouchDevice
{
    //[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
		CozyTouchStateName::EQ_ZONECTRLMODE=>[2,1,0],
		CozyTouchStateName::CTSN_TEMP=>[3,1,0],
		CozyTouchDeviceActions::CTPC_SETTARGETTEMP=>[18,1,0],
		CozyTouchDeviceActions::CTPC_SETECOHEATINGTARGET=>[19,1,0],
		CozyTouchDeviceActions::CTPC_SETCOMFORTHEATINGTARGET=>[20,1,0],
		
		CozyTouchDeviceEqCmds::SET_ZONECTRLZONEOFF=>[30,1,0],// CTPC_SETAPCHEATINGMODE = stop vs on
		CozyTouchDeviceEqCmds::SET_ZONECTRLZONEMANU=>[31,0,0],// CTPC_SETAPCHEATINGMODE = comfort
		CozyTouchDeviceEqCmds::SET_ZONECTRLZONEPROGRAM=>[32,0,1],// CTPC_SETAPCHEATINGMODE = internalScheduling
		CozyTouchStateName::CTSN_CONNECT=>[99,1,1],
		'refresh'=>[1,0,0]
    ];
    
	public static function BuildEqLogic($device)
    {
        $deviceURL = $device->getURL();
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
		$eqLogic->setCategory('energy', 1);
		$eqLogic->save();

		$cmd= $eqLogic->getCmd(null, $device->getURL().'_'.CozyTouchStateName::EQ_ZONECTRLMODE);
		if (!is_object($cmd)) {
    		$cmd = new cozytouchCmd();
    		$cmd->setIsVisible(1);
    	}
    	$cmd->setEqLogic_id($eqLogic->getId());
    	$cmd->setName(__(CozyTouchStateName::CTSN_LABEL[CozyTouchStateName::EQ_ZONECTRLMODE], __FILE__));
    	$cmd->setType('info');
    	$cmd->setSubType('string');
        $cmd->setLogicalId( $device->getURL().'_'.CozyTouchStateName::EQ_ZONECTRLMODE);
		$cmd->setTemplate('dashboard', 'cozytouch::zonectlzonemode');
		$cmd->setTemplate('mobile', 'cozytouch::zonectlzonemode');
		$cmd->save();

		self::refresh($eqLogic,false);

		// Consigne
		$targettemp = $eqLogic->getCmd(null,$deviceURL.'_'.CozyTouchStateName::CTSN_TARGETTEMP );
		if(is_object($targettemp))
		{
			$mini = 18;
			$maxi = 28;

			$thermo = $eqLogic->getCmd(null,CozyTouchDeviceActions::CTPC_SETTARGETTEMP );
			if (!is_object($thermo)) {
				$thermo = new cozytouchCmd();
				$thermo->setLogicalId(CozyTouchDeviceActions::CTPC_SETTARGETTEMP);
			}
			$thermo->setEqLogic_id($eqLogic->getId());
			$thermo->setName(__('Consigne', __FILE__));
			$thermo->setType('action');
			$thermo->setSubType('slider');
			$thermo->setTemplate('dashboard', 'button');
			$thermo->setTemplate('mobile', 'button');
			$thermo->setConfiguration('maxValue', $maxi);
			$thermo->setConfiguration('minValue', $mini);
			$thermo->setValue($targettemp->getId());
			$thermo->save();
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
		self::refresh_mode($eqLogic);
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
		$mode = self::getOpeMod($device_url);
        log::add('cozytouch', 'debug', 'execute cmd mode : '.$mode);
        switch($cmd->getLogicalId())
        {
			case 'refresh':
				log::add('cozytouch', 'debug', 'command : '.$device_url.' refresh');
				break;

			case CozyTouchDeviceActions::CTPC_SETTARGETTEMP:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceActions::CTPC_SETTARGETTEMP." value : ".$_options['slider']);
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
				
				$eqLogic->getCmd(null, $device_url.'_'.CozyTouchStateName::CTSN_TARGETTEMP)->event($_options['slider']);
				$target_temp_field = ($mode=="heating"?CozyTouchDeviceActions::CTPC_SETCOMFORTHEATINGTARGET:CozyTouchDeviceActions::CTPC_SETCOOLINGTARGETTEMP);
				self::setTargetTempGeneric($device_url,$target_temp_field,$_options['slider']);
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
				self::setTargetTempGeneric($device_url,CozyTouchDeviceActions::CTPC_SETCOMFORTHEATINGTARGET,$_options['slider']);
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
				self::setTargetTempGeneric($device_url,CozyTouchDeviceActions::CTPC_SETECOHEATINGTARGET,$_options['slider']);
				break;
			
			

			case CozyTouchDeviceEqCmds::SET_ZONECTRLZONEOFF:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_ZONECTRLZONEOFF);
				self::setOnOffMode($device_url,'off',$mode);
				break;

			case CozyTouchDeviceEqCmds::SET_ZONECTRLZONEMANU:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_ZONECTRLZONEMANU);
				self::setManuMode($device_url,$mode);
				break;

			case CozyTouchDeviceEqCmds::SET_ZONECTRLZONEPROGRAM:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_ZONECTRLZONEPROGRAM);
				self::setProgramMode($device_url,$mode);
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

    protected static function refresh($eqLogic,$refresh_mode =true)
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
			if($refresh_mode)
			{
				self::refresh_mode($eqLogic);
			}
		} 
		catch (Exception $e) {
	
        }
	}

	public static function refresh_mode($eqDevice) 
    {
		$deviceURL = $eqDevice->getConfiguration('device_url');

		log::add('cozytouch', 'debug', 'Zone refresh mode : '.$deviceURL);
		$main_mode = self::getOpeMod($deviceURL);
		$mode=Cmd::byEqLogicIdAndLogicalId($eqDevice->getId(),$deviceURL.'_'.CozyTouchStateName::EQ_ZONECTRLMODE);
		$mode_value = "";
		if(is_object($mode))
		{
			$is_heating="off";
			$heating_state=Cmd::byEqLogicIdAndLogicalId($eqDevice->getId(),$deviceURL.'_'.CozyTouchStateName::CTSN_HEATINGONOFF);
			if(is_object($heating_state))
			{
				$is_heating=$heating_state->execCmd();
			}
			if($is_heating=="off")
			{
				$mode_value="off";
			}
			else
			{
				$profil=Cmd::byEqLogicIdAndLogicalId($eqDevice->getId(),$deviceURL.'_'.CozyTouchStateName::CTSN_PASSAPCHEATINGMODE)->execCmd();
				if($profil=="internalScheduling")
				{
					$mode_value="heating_prog";
				}
				else
				{
					$mode_value="heating_manu";
				}
			}
			
			$mode->setCollectDate('');
			$mode->event($mode_value);
		}

		self::updateVisibility($eqDevice,$main_mode,$mode_value);
	}

	public static function updateVisibility($eqDevice,$mode_main,$mode_value)
	{
		
		self::orderCommand($eqDevice);

		$deviceURL = $eqDevice->getConfiguration('device_url');
		log::add('cozytouch', 'debug', __('Visibility calculation ', __FILE__).$deviceURL.' mode : '.$mode_value .'/'.$mode_main);
		$consigne = $eqDevice->getCmd(null,CozyTouchDeviceActions::CTPC_SETTARGETTEMP );
		$eco_heating = $eqDevice->getCmd(null,CozyTouchDeviceActions::CTPC_SETECOHEATINGTARGET );
		$comfort_heating = $eqDevice->getCmd(null,CozyTouchDeviceActions::CTPC_SETCOMFORTHEATINGTARGET );


		if($mode_value=="off")
		{
			$consigne->setIsVisible(0);
			$eco_heating->setIsVisible(0);
			$comfort_heating->setIsVisible(0);
		}
		else if($mode_value=="heating_manu")
		{
			$consigne->setIsVisible(1);
			$eco_heating->setIsVisible(0);
			$comfort_heating->setIsVisible(0);
		}
		else if($mode_value=="heating_prog")
		{
			$consigne->setIsVisible(0);
			$eco_heating->setIsVisible(1);
			$comfort_heating->setIsVisible(1);
		}

		$consigne->save();
		$eco_heating->save();
		$comfort_heating->save();
		
	}
	//off / manu / prog
	//set eco, comfort heating / eco, comfort cooling
	public static function setOnOffMode($device_url,$value,$mode)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETHEATINGONOFF,
                "values"=>$value
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(5);
	}

	public static function setManuMode($device_url,$mode)
	{
		self::setOnOffMode($device_url,"on",$mode);
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETAPCHEATINGMODE,
                "values"=>"comfort"
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(3);
		self::refreshTempTarget($device_url);
	}

	public static function setProgramMode($device_url,$mode)
	{
		self::setOnOffMode($device_url,"on",$mode);
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETAPCHEATINGMODE,
                "values"=>"internalScheduling"
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(3);
		self::refreshTempTarget($device_url);
	}

	public static function setTargetTempGeneric($device_url,$temp_state,$value)
	{
		if(CozyTouchDeviceActions::CTPC_SETCOMFORTHEATINGTARGET)
		$cmds = array(
            array(
                "name"=>$temp_state,
                "values"=>floatval($value)
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(3);
		self::refreshTempTarget($device_url);
	}
	
	public static function refreshTempTarget($device_url)
	{
		$cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_RSHTARGETTEMP,
                "values"=>null
			)
        );
		parent::genericApplyCommand($device_url,$cmds);
		sleep(3);
	}

	public static function getOpeMod($deviceURL)
	{
		$deviceURL_main = explode("#",$deviceURL)[0]."#1";
		log::add('cozytouch', 'debug', 'getOpeMod : '.$deviceURL_main);
		$cmd_array = Cmd::byLogicalId($deviceURL_main.'_'.CozyTouchStateName::CTSN_PASSAPCOPERATINGMODE);
		if(is_array($cmd_array) && $cmd_array!=null)
		{
			$cmd=$cmd_array[0];
			$mode=$cmd->execCmd();
		}
		return $mode;
	}
}
?>