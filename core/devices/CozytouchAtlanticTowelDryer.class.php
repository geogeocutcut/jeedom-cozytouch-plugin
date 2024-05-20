<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

if (!class_exists('CozytouchAtlanticDimmableLight')) {
	require_once dirname(__FILE__) . "/CozytouchAtlanticDimmableLight.class.php";
}

class CozytouchAtlanticTowelDryer extends AbstractCozytouchDevice
{
	//[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
		CozyTouchStateName::CTSN_TARGETHEATLEVEL=>[1,0,1],

		CozyTouchStateName::CTSN_TEMP=>[13,0,0],
		CozyTouchStateName::CTSN_RELATIVEHUMIDITY=>[17,0,1],
		CozyTouchStateName::CTSN_BOOSTMODEDURATION=>[18,0,0],
		CozyTouchDeviceEqCmds::SET_BOOST=>[19,0,1],
		CozyTouchStateName::CTSN_DRYINGDURATION=>[20,0,0],
		CozyTouchDeviceEqCmds::SET_DRY=>[21,0,1],

		CozyTouchDeviceEqCmds::SET_THERMOSTAT=>[40,1,1],
		CozyTouchDeviceEqCmds::SET_STANDBY=>[41,1,0],
		CozyTouchDeviceEqCmds::SET_EXTERNAL=>[43,0,0],
		CozyTouchDeviceEqCmds::SET_INTERNAL=>[44,0,0],
		CozyTouchDeviceEqCmds::SET_AUTO=>[45,0,1],
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
    		$eqLogic->setConfiguration('order_min', -0.5);
		}

		$states = CozyTouchDeviceStateName::EQLOGIC_STATENAME[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
		$sensors = array();
		$statecount = array();
		foreach ($device->getSensors() as $sensor)
		{
			$sensorURL = $sensor->getURL();
			$sensorModel = $sensor->getModel();
			$sensors[] = array($sensorURL,$sensor->getModel());
			log::add('cozytouch', 'info', 'Sensor : '.$sensorURL);
			
			// state du capteur
			foreach ($sensor->getStates() as $state)
			{
				if(in_array($state->name,$states))
				{
					log::add('cozytouch', 'info', 'State : '.$state->name);
					$statecount[$state->name]+=1;
					$cmdId = $sensorURL.'_'.$state->name;
					$type ="info";
					$subType = CozyTouchStateName::CTSN_TYPE[$state->name];
					$name = $statecount[$state->name]>1 ? CozyTouchStateName::CTSN_LABEL[$state->name]." ".$statecount[$state->name]:CozyTouchStateName::CTSN_LABEL[$state->name];
					$dashboard =CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
					$mobile =CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
					$value =$subType=="numeric"?0:($subType=="string"?'value':0);
					self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,$value,$dashboard,$mobile,$i+1);
				}
			}
		}
		$eqLogic->setConfiguration('sensors',$sensors);
		$eqLogic->save();

		$cmd= $eqLogic->getCmd(null, $device->getURL().'_'.CozyTouchStateName::CTSN_TARGETHEATLEVEL);
		$cmd->setTemplate('dashboard', 'cozytouch::heatmode');
		$cmd->setTemplate('mobile', 'cozytouch::heatmode');
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
    	$thermostat->setTemplate('dashboard', 'cozytouch::thermheatelec');
    	$thermostat->setTemplate('mobile', 'cozytouch::thermheatelec');
    	$thermostat->setIsVisible(1);
		$thermostat->setValue($order->getId());
		$thermostat->setOrder(1);
		$thermostat->save();


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
    	$boost_toogle->setTemplate('dashboard', 'cozytouch::toggle');
    	$boost_toogle->setTemplate('mobile', 'cozytouch::toggle');
    	$boost_toogle->setIsVisible(1);
		$boost_toogle->setValue($boost->getId());
		$boost_toogle->save();

		
		$dry = $eqLogic->getCmd(null, 'dry_state');
    	if (!is_object($dry)) {
    		$dry = new cozytouchCmd();
    		$dry->setIsVisible(0);
    	}

    	$dry->setEqLogic_id($eqLogic->getId());
    	$dry->setName(__('dry_state', __FILE__));
    	$dry->setType('info');
    	$dry->setSubType('binary');
    	$dry->setIsHistorized(1);
    	$dry->setLogicalId('dry_state');
    	$dry->save();
    	
    	$dry_toogle = $eqLogic->getCmd(null, CozyTouchDeviceEqCmds::SET_DRY);
    	if (!is_object($dry_toogle)) {
    		$dry_toogle = new cozytouchCmd();
			$dry_toogle->setLogicalId(CozyTouchDeviceEqCmds::SET_DRY);
    	}
    	$dry_toogle->setEqLogic_id($eqLogic->getId());
    	$dry_toogle->setName(__('Séchage', __FILE__));
    	$dry_toogle->setType('action');
    	$dry_toogle->setSubType('slider');
    	$dry_toogle->setTemplate('dashboard', 'cozytouch::toggle');
    	$dry_toogle->setTemplate('mobile', 'cozytouch::toggle');
    	$dry_toogle->setIsVisible(1);
		$dry_toogle->setValue($dry->getId());
		$dry_toogle->save();


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
                self::setTowelDryerOperatingMode($device_url,'standby');
    			break;
    			
    		case CozyTouchDeviceEqCmds::SET_EXTERNAL:
                self::setTowelDryerOperatingMode($device_url,'external');
    			break;

    		case CozyTouchDeviceEqCmds::SET_INTERNAL:
                self::setTowelDryerOperatingMode($device_url,'internal');
    			break;

    		case CozyTouchDeviceEqCmds::SET_AUTO:
                self::setTowelDryerOperatingMode($device_url,'auto');
				break;
			
			case CozyTouchDeviceEqCmds::SET_BOOST:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_BOOST." value : ".$_options['slider']);
				self::setBoost($device_url,$_options['slider']);
				break;	
			case CozyTouchDeviceEqCmds::SET_DRY:
				log::add('cozytouch', 'debug', 'command : '.$device_url.' '.CozyTouchDeviceEqCmds::SET_DRY." value : ".$_options['slider']);
				self::setDry($device_url,$_options['slider']);
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
			
			self::refresh_boost($eqLogic);
			self::refresh_dry($eqLogic);
			self::refresh_thermostat($eqLogic);
	
		} 
		catch (Exception $e) {
	
		}
	}
	public static function refresh_boost($eqLogic) 
    {
        log::add('cozytouch', 'debug', __('Refresh boost', __FILE__));
        $deviceURL = $eqLogic->getConfiguration('device_url');
        $value="";

		$cmd = Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),$deviceURL.'_'.CozyTouchStateName::CTSN_TOWELDRYERTEMPORARY);
		if (is_object($cmd)) {
			$value=$cmd->execCmd();
        }

        $cmd=Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'boost_state');
		if (is_object($cmd)) {
			$boost = $value=="boost"?1:0;
            $cmd->setCollectDate('');
            $cmd->event($boost);
            log::add('cozytouch', 'debug', __('Boost : ', __FILE__).$boost. "(".$value.")");
        }
	}
	public static function refresh_dry($eqLogic) 
    {
        log::add('cozytouch', 'debug', __('Refresh dry', __FILE__));
        $deviceURL = $eqLogic->getConfiguration('device_url');
        $value="";

		$cmd = Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),$deviceURL.'_'.CozyTouchStateName::CTSN_TOWELDRYERTEMPORARY);
		if (is_object($cmd)) {
			$value=$cmd->execCmd();
        }

        $cmd=Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'dry_state');
		if (is_object($cmd)) {
			$dry =$value=="drying"?1:0;
            $cmd->setCollectDate('');
            $cmd->event($dry);
            log::add('cozytouch', 'debug', __('Dry : ', __FILE__).$dry. "(".$value.")");
        }
	}
	public static function refresh_thermostat($eqDevice) 
    {
    	
		log::add('cozytouch', 'debug', 'Refresh thermostat');
		$valuetmp = array();
		$deviceURL = $eqDevice->getConfiguration('device_url');
		
		$cmd_array = Cmd::byLogicalId($deviceURL.'_'.CozyTouchStateName::CTSN_TARGETTEMP);
		if(is_array($cmd_array) && $cmd_array!=null)
		{
			$cmd=$cmd_array[0];
			$valuetmp['cible']=$cmd->execCmd();
		}

		$cmd=Cmd::byEqLogicIdAndLogicalId($eqDevice->getId(),'order');
		if (is_object($cmd)) {
			$cmd->setCollectDate('');
			$cmd->event($valuetmp['cible']);
			log::add('cozytouch', 'info', __('Target ', __FILE__).$valuetmp['cible']);
		}
	}
	
    public static function setTowelDryerOperatingMode($device_url,$value)
	{
        $cmds = array(
            array(
                "name"=>CozyTouchDeviceActions::CTPC_SETTOWELDRYINGMODE,
                "values"=>$value
            )
        );
        parent::genericApplyCommand($device_url,$cmds);
	}
	
    public static function setBoost($device_url,$value)
	{
		if($value>0)
		{
			$cmds = array(
				array(
					"name"=>CozyTouchDeviceActions::CTPC_SETDRYERTEMPORARYMODE,
					"values"=>"boost"
				),
				array(
					"name"=>CozyTouchDeviceActions::CTPC_SETBOOSTDURATION,
					"values"=>30
				)
			);

			parent::genericApplyCommand($device_url,$cmds);
		}
		else
		{
			self::setPermanentHeating($device_url);
		}
		sleep(2);
		self::refreshHeatingLevel($device_url);
	}
	
    public static function setDry($device_url,$value)
	{
		if($value>0)
		{
			$cmds = array(
				array(
					"name"=>CozyTouchDeviceActions::CTPC_SETDRYERTEMPORARYMODE,
					"values"=>"drying"
				),
				array(
					"name"=>CozyTouchDeviceActions::CTPC_SETDRYDURATION,
					"values"=>30
				)
			);

			parent::genericApplyCommand($device_url,$cmds);
		}
		else
		{
			self::setPermanentHeating($device_url);
		}
		sleep(5);
		self::refreshHeatingLevel($device_url);
	}

	public static function setPermanentHeating ($device_url)
	{
		$cmds = array(
			array(
				"name"=>CozyTouchDeviceActions::CTPC_SETDRYERTEMPORARYMODE,
				"values"=>"permanentHeating"
			),
			array(
				"name"=>CozyTouchDeviceActions::CTPC_RSHBOOSTDURATION,
				"values"=>null
			),
			array(
				"name"=>CozyTouchDeviceActions::CTPC_RSHDRYINGDURATION,
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
                    "values"=>floatval($value)
            ),
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHHEATINGLVL,
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
				
		self::setTargetTemperature($device_url,$value);
	}
    
    
}
?>