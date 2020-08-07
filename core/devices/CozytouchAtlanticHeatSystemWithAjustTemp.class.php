<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

if (!class_exists('CozytouchAtlanticDimmableLight')) {
	require_once dirname(__FILE__) . "/CozytouchAtlanticDimmableLight.class.php";
}
class CozytouchAtlanticHeatSystemWithAjustTemp extends AbstractCozytouchDevice
{
	//[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
		CozyTouchStateName::CTSN_TARGETHEATLEVEL=>[1,0,1],
		CozyTouchStateName::CTSN_OPEMODE=>[2,0,1],

		CozyTouchStateName::CTSN_TARGETTEMP=>[10,1,0],
		CozyTouchStateName::CTSN_DEROGTARGETTEMP=>[11,0,0],
		CozyTouchStateName::CTSN_EFFTEMPSETPOINT=>[12,0,1],
		CozyTouchStateName::CTSN_TEMP=>[13,0,0],
		CozyTouchStateName::CTSN_ELECNRJCONSUMPTION=>[14,0,0],
		CozyTouchStateName::CTSN_OCCUPANCY=>[15,0,1],

		CozyTouchDeviceEqCmds::SET_THERMOSTAT=>[20,1,1],
		CozyTouchDeviceEqCmds::SET_STANDBY=>[21,0,0],
		CozyTouchDeviceEqCmds::SET_BASIC=>[22,0,0],
		CozyTouchDeviceEqCmds::SET_EXTERNAL=>[23,0,1],
		CozyTouchDeviceEqCmds::SET_INTERNAL=>[24,0,0],
		CozyTouchDeviceEqCmds::SET_AUTO=>[25,0,1],
		CozyTouchDeviceEqCmds::SET_FROSTPROTECT=>[31,0,0],
		CozyTouchDeviceEqCmds::SET_ECO=>[32,0,1],
		CozyTouchDeviceEqCmds::SET_COMFORT2=>[33,1,0],
		CozyTouchDeviceEqCmds::SET_COMFORT1=>[34,0,0],
		CozyTouchDeviceEqCmds::SET_COMFORT=>[35,0,1],
		CozyTouchStateName::CTSN_CONNECT=>[99,1,1],
		'refresh'=>[1,0,0]
	];


	public static function BuildEqLogic($device)
    {
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
		$eqLogic = self::BuildDefaultEqLogic($device);
		$eqLogic->setCategory('heating', 1);
		if ($eqLogic->getConfiguration('order_max') === '') {
    		$eqLogic->setConfiguration('order_max', 28);
    	}
    	if ($eqLogic->getConfiguration('order_min') === '') {
    		$eqLogic->setConfiguration('order_min', 12);
		}

		$states = CozyTouchDeviceStateName::EQLOGIC_STATENAME[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
        $sensors = array();
		foreach ($device->getSensors() as $sensor)
		{
			$sensorURL = $sensor->getURL();
			$sensorModel = $sensor->getModel();
			if($sensorModel== CozyTouchDeviceToDisplay::CTDTD_ATLANTICDIMMABLELIGHT)
			{
				$sensor->setVar(CozyTouchDeviceInfo::CTDI_LABEL,"Lumière ".$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
				CozytouchAtlanticDimmableLight::BuildEqLogic($sensor);
			}
			else
			{
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
		}
		$eqLogic->setConfiguration('sensors',$sensors);
		$eqLogic->save();

		$cmd= $eqLogic->getCmd(null, $device->getURL().'_'.CozyTouchStateName::CTSN_TARGETHEATLEVEL);
		$cmd->setTemplate('dashboard', 'heatmode');
		$cmd->setTemplate('mobile', 'heatmode');
		$cmd->save();

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
    	
    	$thermostat = $eqLogic->getCmd(null, CozyTouchDeviceEqCmds::SET_THERMOSTAT);
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
    	$thermostat->setLogicalId(CozyTouchDeviceEqCmds::SET_THERMOSTAT);
    	$thermostat->setTemplate('dashboard', 'thermheatelec');
    	$thermostat->setTemplate('mobile', 'thermheatelec');
    	$thermostat->setIsVisible(1);
		$thermostat->setValue($order->getId());
		$thermostat->setOrder(1);
		$thermostat->save();

		self::orderCommand($eqLogic);

		self::refresh($eqLogic);
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
    		case CozyTouchDeviceEqCmds::SET_STANDBY:
                self::setOperationMode($device_url,'standby');
    			break;
    			
    		case CozyTouchDeviceEqCmds::SET_BASIC:
                self::setOperationMode($device_url,'basic');
    			break;
    			
    		case CozyTouchDeviceEqCmds::SET_EXTERNAL:
                self::setOperationMode($device_url,'external');
    			break;

    		case CozyTouchDeviceEqCmds::SET_INTERNAL:
                self::setOperationMode($device_url,'internal');
    			break;

    		case CozyTouchDeviceEqCmds::SET_AUTO:
                self::setOperationMode($device_url,'auto');
				break;
			case CozyTouchDeviceEqCmds::RESET_HEATINGLEVEL:
				self::cancelHeatingLevel($device_url);
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
				
    		case CozyTouchDeviceEqCmds::SET_THERMOSTAT:
    			$min = $cmd->getConfiguration('minValue');
    			$max = $cmd->getConfiguration('maxValue');
    			if (!isset($_options['slider']) || $_options['slider'] == '' || !is_numeric(floatval($_options['slider']))) {
    				$_options['slider'] = (($max - $min) / 2) + $min;
    			}
    			if ($_options['slider'] > $max) {
    				$_options['slider'] = $max;
    			}
    			if ($_options['slider'] < $min) {
    				$_options['slider'] = $min;
				}
				log::add('cozytouch', 'debug', 'slider : '.$_options['slider']);
    			$eqLogic->getCmd(null, 'order')->event($_options['slider']);
    			
    			self::setTemperature($device_url,floatval($_options['slider']));
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
					
					$value = CozyTouchManager::get_state_value($state);
					if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
						$cmd->setCollectDate('');
						$cmd->event($value);
					}
				}
			}
			
			self::refresh_thermostat($eqLogic);
	
		} 
		catch (Exception $e) {
	
		}
	}

	public static function refresh_thermostat($eqDevice) 
    {
    	
		log::add('cozytouch', 'debug', 'Refresh thermostat');
		$valuetmp = array();
		$deviceURL = $eqDevice->getConfiguration('device_url');
		
		$cmd_array = Cmd::byLogicalId($deviceURL.'_'.CozyTouchStateName::CTSN_TARGETHEATLEVEL);
		if(is_array($cmd_array) && $cmd_array!=null)
		{
			$cmd=$cmd_array[0];
			$mode=$cmd->execCmd();
		}
			
		$cmd_array = Cmd::byLogicalId($deviceURL.'_'.CozyTouchStateName::CTSN_TARGETTEMP);
		if(is_array($cmd_array) && $cmd_array!=null)
		{
			$cmd=$cmd_array[0];
			$valuetmp['cible']=$cmd->execCmd();
		}
		
		$cmd_array = Cmd::byLogicalId($deviceURL.'_'.CozyTouchStateName::CTSN_ECOROOMTEMP);
		if(is_array($cmd_array) && $cmd_array!=null)
		{
			$cmd=$cmd_array[0];
			$valuetmp['eco']=$cmd->execCmd();
		}
			
		$cmd_array = Cmd::byLogicalId($deviceURL.'_'.CozyTouchStateName::CTSN_DEROGTARGETTEMP);
		if(is_array($cmd_array) && $cmd_array!=null)
		{
			$cmd=$cmd_array[0];
			$valuetmp['derogation']=$cmd->execCmd();
		}
			
		$cmd_array = Cmd::byLogicalId($deviceURL.'_'.CozyTouchStateName::CTSN_EFFTEMPSETPOINT);
		if(is_array($cmd_array) && $cmd_array!=null)
		{
			$cmd=$cmd_array[0];
			$valuetmp['effective']=$cmd->execCmd();
		}

		$cmd=Cmd::byEqLogicIdAndLogicalId($eqDevice->getId(),'order');
		if (is_object($cmd)) {
			if($mode=='eco')
			{
				if($valuetmp['derogation']>0)
				{
					$cmd->setCollectDate('');
					$cmd->event($valuetmp['derogation']);
					log::add('cozytouch', 'info', __('Derogation ', __FILE__).$valuetmp['derogation']);
				}
				else
				{
					$tempEco = $valuetmp['cible']-$valuetmp['eco'];
					$cmd->setCollectDate('');
					$cmd->event($tempEco);
					log::add('cozytouch', 'info', __('Eco ', __FILE__).$tempEco);
				}
			}
			else
			{
				$cmd->setCollectDate('');
				$cmd->event($valuetmp['cible']);
				log::add('cozytouch', 'info', __('Effective ', __FILE__).$valuetmp['effective']);
				
			}
		}
	}
	
    public static function setOperationMode($device_url,$value)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETMODE,
                "values"=>$value
            ),
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHHEATINGLVL,
                    "values"=>null
            )
        );
        parent::genericApplyCommand($device_url,$cmds);
        $cmds = array(
				array(
						"name"=>CozyTouchDeviceActions::CTPC_RSHHEATINGLVL,
						"values"=>null
				)
		);
		parent::genericApplyCommand($device_url,$cmds);
		
		$cmds = array(
				array(
						"name"=>CozyTouchDeviceActions::CTPC_RSHMODE,
						"values"=>null
				)
		);
		parent::genericApplyCommand($device_url,$cmds);
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
			
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHMODE,
                    "values"=>null
            )
		);
		parent::genericApplyCommand($device_url,$cmds);
	}
	
	public static function setTargetTemperature($device_url,$value)
	{
		log::add('cozytouch', 'info', __('set target temperature ', __FILE__).$value);
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_SETTARGETTEMP,
                    "values"=>$value
            ),
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHTARGETTEMP,
                    "values"=>null
            )
		);
        parent::genericApplyCommand($device_url,$cmds);
		
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHEFFTEMP,
                    "values"=>null
            )
		);
		parent::genericApplyCommand($device_url,$cmds);
	}
	
	public static function setDerogatedTargetTemperature($device_url,$value)
	{
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_SETDEROGTEMP,
                    "values"=>$value
            ),
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHDEROGTEMP,
                    "values"=>null
            )
		);
        parent::genericApplyCommand($device_url,$cmds);
		
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHEFFTEMP,
                    "values"=>null
            )
		);
		parent::genericApplyCommand($device_url,$cmds);
	}
	
	public static function setComfortTemperature($device_url,$value)
	{
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_SETCOMTEMP,
                    "values"=>$value
            ),
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHCOMTEMP,
                    "values"=>null
            )
		);
        parent::genericApplyCommand($device_url,$cmds);
	}
	
	public static function setEcoTemperature($device_url,$value)
	{
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_SETECOTEMP,
                    "values"=>$value
            ),
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHECOTEMP,
                    "values"=>null
            )
		);
        parent::genericApplyCommand($device_url,$cmds);
		
    }
    
    public static function cancelHeatingLevel($device_url,$value='comfort')
	{
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_CANCELHEATINGLVL,
                    "values"=>$value
            ),
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

	public static function setTemperature($device_url,$value)
	{
		$cmd_array = Cmd::byLogicalId($device_url."_".CozyTouchStateName::CTSN_TARGETHEATLEVEL);
		if(is_array($cmd_array) && $cmd_array!=null)
		{
			$cmd=$cmd_array[0];
			$mode=$cmd->execCmd();
		}
		log::add('cozytouch', 'info', __('set temperature : ', __FILE__).$mode.' '.$value);
				
		if($mode=='eco')
		{
			self::setDerogatedTargetTemperature($device_url,$value);
		}
		else
		{
			self::setTargetTemperature($device_url,$value);
		}
	}
    
    
}
?>