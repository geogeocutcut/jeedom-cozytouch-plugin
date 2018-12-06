<?php
require_once dirname(__FILE__) . '/../../../../../core/php/core.inc.php';

class CozytouchAtlanticHeatSystem extends AbstractCozytouchDevice
{
	
	public static function BuildEqLogic($device)
    {
        log::add('cozytouch', 'info', 'creation (ou mise à jour) '.$device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
		$eqLogic = self::BuildDefaultEqLogic($device);

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
		

		
    	if ($eqLogic->getConfiguration('order_max') === '') {
    		$eqLogic->setConfiguration('order_max', 28);
    	}
    	if ($eqLogic->getConfiguration('order_min') === '') {
    		$eqLogic->setConfiguration('order_min', 12);
    	}
	}
	
    public static function Execute($cmd,$_options= array())
    {
		$device_url=$this->eqLogic->getConfiguration('device_url');
        switch($cmd->getLogicalId())
        {
    		case 'refresh':
    			self::refresh_place();
    			$refresh = false;
    			break;
    		case CozyTouchDeviceActions::SET_STANDBY:
                self::setOperationMode($device_url,'standby');
    			break;
    			
    		case CozyTouchDeviceActions::SET_BASIC:
                self::setOperationMode($device_url,'basic');
    			break;
    			
    		case CozyTouchDeviceActions::SET_EXTERNAL:
                self::setOperationMode($device_url,'external');
    			break;

    		case CozyTouchDeviceActions::SET_INTERNAL:
                self::setOperationMode($device_url,'internal');
    			break;

    		case CozyTouchDeviceActions::SET_AUTO:
                self::setOperationMode($device_url,'auto');
				break;
				
    		case CozyTouchDeviceActions::SET_TARGETTEMP:
                self::setTargetTemperature($device_url);
				break;
				
    		case CozyTouchDeviceActions::SET_THERMOSTAT:
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
    			$this->eqLogic->getCmd(null, 'order')->event($_options['slider']);
    			
    			self::setTemperature($device_url,floatval($_options['slider']));
    			break;
        }
    }

    public static function setOperationMode($value)
	{
        //$this->cancelHeatingLevel();
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
        $this->genericApplyCommand($device_url,$cmds);
        
        $cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHHEATINGLVL,
                    "values"=>null
            )
        );
        $this->genericApplyCommand($device_url,$cmds);
        
        $cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHMODE,
                    "values"=>null
            )
        );
        $this->genericApplyCommand($device_url,$cmds);
    }
    
    public static function refreshHeatingLevel($device_url)
	{
			
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHHEATINGLVL,
                    "values"=>null
            )
		);
		$this->genericApplyCommand($device_url,$cmds);
			
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHMODE,
                    "values"=>null
            )
		);
		$this->genericApplyCommand($device_url,$cmds);
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
        $this->genericApplyCommand($device_url,$cmds);
		
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHEFFTEMP,
                    "values"=>null
            )
		);
		$this->genericApplyCommand($device_url,$cmds);
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
        $this->genericApplyCommand($device_url,$cmds);
		
		$cmds = array(
            array(
                    "name"=>CozyTouchDeviceActions::CTPC_RSHEFFTEMP,
                    "values"=>null
            )
		);
		$this->genericApplyCommand($device_url,$cmds);
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
        $this->genericApplyCommand($device_url,$cmds);
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
        $this->genericApplyCommand($device_url,$cmds);
		
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
		$this->genericApplyCommand($device_url,$cmds);
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
		$this->genericApplyCommand($device_url,$cmds);
    }



	public static function setTemperature($device_url,$value)
	{
		$list_device = $this->getConfiguration('devices_list');
		$mode='';
		foreach($list_device as $urlLong => $urlShort)
		{
			$cmd_array = Cmd::byLogicalId($urlShort."_io:TargetHeatingLevelState");
			if(is_array($cmd_array) && $cmd_array!=null)
			{
				$cmd=$cmd_array[0];
				$mode=$cmd->execCmd();
			}
		
			break;
		}
		log::add('cozytouch', 'info', __('Mode ', __FILE__).$mode);
				
		if($mode=='eco')
		{
			$this->setDerogatedTargetTemperature($value);
		}
		else
		{
			$this->setTargetTemperature($value);
		}
	}
    
	
    
    public function refresh() 
    {
    	try {

    		$this->refreshHeatingLevel();
    		
    		$devicesCmd=array();
    
    		$list_device = $this->getConfiguration('devices_list');
    		foreach($list_device as $urlLong => $urlShort)
    		{
    			foreach(CozyTouchDeviceToDisplay::$CTDTD_SUFFIXE as $extension)
    			{
    				$deviceCmd = new CozyTouchDeviceCommand();
    				$deviceCmd->deviceURL=$urlShort.$extension;
    				foreach (self::getListState($extension) as $stateName)
    				{
    					$stateCmd = new CozyTouchStateCommand();
    					$stateCmd->name = $stateName;
    					$deviceCmd->states[]=$stateCmd;
    				}
    				$devicesCmd[]=$deviceCmd;
    			}
    		}
    
    		$clientApi = self::getClient();
    
    
    		$post_data = $devicesCmd;
    		$resp = $clientApi->getStates($post_data);
    		$devices = $resp->getData();
    		foreach ($devices as $device)
    		{
    			$urlShort = explode("#",$device->getURL())[0];
    			// state du device
    			foreach ($device->getStates() as $state)
    			{
    				//echo "  ".$state->name." : ".$state->value."<br>";
    
    				$cmd_array = Cmd::byLogicalId($urlShort."_".$state->name);
    				if(is_array($cmd_array) && $cmd_array!=null)
    				{
    					$cmd=$cmd_array[0];
    					if($state->name=='core:OnOffState')
						{
							$value = ($state->value=='on');
						}
						else
						{
							$value = $state->value;
						}
						if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
    						$cmd->setCollectDate('');
							$cmd->event($value);
						}
    				}
    			}
    			// Liste des capteurs du device
    			foreach ($device->getSensors() as $sensor)
    			{
    				// state du capteur
    				foreach ($sensor->getStates() as $state)
    				{
    					//echo "  ".$state->name." : ".$state->value."<br>";
    
    					$cmd_array = Cmd::byLogicalId($urlShort."_".$state->name);
    					if(is_array($cmd_array) && $cmd_array!=null)
    					{
    						$cmd=$cmd_array[0];
    						if($state->name=='core:OccupancyState')
    						{
								$value = ($state->value=='noPersonInside');
    						}
    						else
    						{
    							$value = $state->value;
    						}
    						if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
    							$cmd->setCollectDate('');
    							$cmd->event($value);
    						}
    					}
    				}
    			}
    		}
    		
    		self::refresh_thermostat(array($this));
    		
    		//$resp = $client->getStates($post_data);
    		//$devices = $resp->getData();
    
        } 
        catch (Exception $e) {
    
    	}
    }
    
    public function refresh_thermostat($eqDevices) 
    {
    	foreach($eqDevices as $eqDevice)
    	{
    		$valuetmp = array();
			$urlShort = explode("#",$device->getURL())[0];
			
			$cmd_array = Cmd::byLogicalId($urlShort.CozyTouchStateName::CTSN_TARGETHEATLEVEL);
			if(is_array($cmd_array) && $cmd_array!=null)
			{
				$cmd=$cmd_array[0];
				$mode=$cmd->execCmd();
			}
				
			$cmd_array = Cmd::byLogicalId($urlShort.CozyTouchStateName::CTSN_TARGETEMP);
			if(is_array($cmd_array) && $cmd_array!=null)
			{
				$cmd=$cmd_array[0];
				$valuetmp['cible']=$cmd->execCmd();
			}
			
			$cmd_array = Cmd::byLogicalId($urlShort.CozyTouchStateName::CTSN_ECOROOMTEMP);
			if(is_array($cmd_array) && $cmd_array!=null)
			{
				$cmd=$cmd_array[0];
				$valuetmp['eco']=$cmd->execCmd();
			}
				
			$cmd_array = Cmd::byLogicalId($urlShort.CozyTouchStateName::CTSN_DEROGTARGETTEMP);
			if(is_array($cmd_array) && $cmd_array!=null)
			{
				$cmd=$cmd_array[0];
				$valuetmp['derogation']=$cmd->execCmd();
			}
				
			$cmd_array = Cmd::byLogicalId($urlShort.CozyTouchStateName::CTSN_EFFTEMPSETPOINT);
			if(is_array($cmd_array) && $cmd_array!=null)
			{
				$cmd=$cmd_array[0];
				$valuetmp['effective']=$cmd->execCmd();
			}

			$cmd=Cmd::byEqLogicIdAndLogicalId($placeEq->getId(),'order');
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
    }
}
?>