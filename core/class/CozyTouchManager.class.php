<?php

require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

if (!class_exists('CozyTouchApiClient')) {
	require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/client/CozyTouchApiClient.class.php";
}

if (!class_exists('AbstractCozytouchDevice')) {
	require_once dirname(__FILE__) . "/../devices/AbstractCozytouchDevice.class.php";
}

if (!class_exists('CozytouchAtlanticHeatSystem')) {
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticHeatSystem.class.php";
}

if (!class_exists('CozytouchAtlanticHeatSystemWithAjustTemp')) {
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticHeatSystemWithAjustTemp.class.php";
}

if (!class_exists('CozytouchAtlanticHotWater')) {
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticHotWater.class.php";
}

if (!class_exists('CozytouchAtlanticHotWaterV2AEX')) {
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticHotWaterV2AEX.class.php";
}
if (!class_exists('CozytouchAtlanticHotWaterV3')) {
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticHotWaterV3.class.php";
}

if (!class_exists('CozytouchAtlanticHotWaterCES4')) {
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticHotWaterCES4.class.php";
}

if (!class_exists('CozytouchAtlanticHotWaterFlatC2')) {
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticHotWaterFlatC2.class.php";
}

if (!class_exists('CozytouchAtlanticVentilation')) {
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticVentilation.class.php";
}

if (!class_exists('CozytouchAtlanticHeatPump')){
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticHeatPump.class.php";
}

if (!class_exists('CozytouchAtlanticZoneControlMain')){
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticZoneControlMain.class.php";
}

if (!class_exists('CozytouchAtlanticZoneControlZone')){
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticZoneControlZone.class.php";
}

if (!class_exists('CozytouchAtlanticDimmableLight')){
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticDimmableLight.class.php";
}

if (!class_exists('CozytouchAtlanticTowelDryer')){
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticTowelDryer.class.php";
}

if (!class_exists('CozytouchAtlanticPassAPCBoilerMain')){
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticPassAPCBoilerMain.class.php";
}

if (!class_exists('CozytouchAtlanticAPCBoilerDHWComponent')){
	require_once dirname(__FILE__) . "/../devices/CozytouchAtlanticAPCBoilerDHWComponent.class.php";
}

class CozyTouchManager
{
    private static $_client = null;

    public static function getClient($_force=false) 
    {
        if (self::$_client == null || $_force) 
        {
			
			self::$_client = new CozyTouchApiClient(array(
					'userId' => config::byKey('username', 'cozytouch'),
					'userPassword' => utf8_decode(config::byKey('password', 'cozytouch'))
			));
		}
		return self::$_client;
	}

	public static function resetCozyTouch()
	{
		$eqLogics = eqLogic::byType('cozytouch');
		foreach($eqLogics as $eqLogic)
		{
			$eqLogic->remove();
		}
	}

	public static function syncWithCozyTouch() 
	{
		$client = self::getClient();
		$devices = $client->getSetup();
		log::add('cozytouch', 'debug', 'Recupération des données ok '); 

        foreach ($devices as $device) 
        {
			
			$deviceModel = $device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME);
			switch ($deviceModel)
			{
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER:
					CozytouchAtlanticHeatSystem::BuildEqLogic($device);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERAJUSTTEMP:
					CozytouchAtlanticHeatSystemWithAjustTemp::BuildEqLogic($device);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICTOWELDRYER:
					CozytouchAtlanticTowelDryer::BuildEqLogic($device);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER:
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERSPLIT:
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCETHIV4 :
					CozytouchAtlanticHotWater::BuildEqLogic($device);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV2AEX:
					CozytouchAtlanticHotWaterV2AEX::BuildEqLogic($device);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCES4:
					CozytouchAtlanticHotWaterCES4::BuildEqLogic($device);
					break;	
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERFLATC2:
					CozytouchAtlanticHotWaterFlatC2::BuildEqLogic($device);
					break;	
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHEATRECOVERYVENT:
					CozytouchAtlanticVentilation::BuildEqLogic($device);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV3:
					CozytouchAtlanticHotWaterV3::BuildEqLogic($device);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCHEATPUMPMAIN:
					CozytouchAtlanticHeatPump::BuildEqLogic($device);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLMAIN:
					CozytouchAtlanticZoneControlMain::BuildEqLogic($device);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCBOILER :
					CozytouchAtlanticPassAPCBoilerMain::BuildEqLogic($device);
					break;
                default:
                    AbstractCozytouchDevice::BuildDefaultEqLogic($device);
					break;
			}
		}
		
		CozyTouchManager::refresh_all();
	}
	
	public static function cron15() {
    	CozyTouchManager::refresh_all();
	}
	
    public static function refresh_all() 
	{
    	try {
    		
    		$clientApi = self::getClient();
    		$devices = $clientApi->getDevices();
			foreach ($devices as $device)
			{
				$device_url = $device->getURL();
				// state du device
				foreach ($device->getStates() as $state)
				{
					$cmd_array = Cmd::byLogicalId($device_url.'_'.$state->name);
					if(is_array($cmd_array) && $cmd_array!=null)
					{
						$cmd=$cmd_array[0];
						$value = self::get_state_value($state);
						if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
    						$cmd->setCollectDate('');
							$cmd->event($value);
						}
					}
				}
				
				
				// Liste des capteurs du device
				foreach ($device->getSensors() as $sensor)
				{
					$sensor_url=$sensor->getURL();
					// state du capteur
					foreach ($sensor->getStates() as $state)
					{
						$cmd_array = Cmd::byLogicalId($sensor_url.'_'.$state->name);
						if(is_array($cmd_array) && $cmd_array!=null)
						{
							$cmd=$cmd_array[0];
							$value = self::get_state_value($state);
							if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
    							$cmd->setCollectDate('');
								$cmd->event($value);
							}
							
						}
					}
				}
				log::add('cozytouch','debug','Refresh info : '.$device->getVar(CozyTouchDeviceInfo::CTDI_OID));
				$eqLogicTmp = eqLogic::byLogicalId($device->getVar(CozyTouchDeviceInfo::CTDI_OID), 'cozytouch');
				if (is_object($eqLogicTmp)) {
					$device_type = $eqLogicTmp->getConfiguration('device_model');
					switch($device_type){
						case CozyTouchDeviceToDisplay::CTDTD_ATLANTICTOWELDRYER:
							CozytouchAtlanticTowelDryer::refresh_boost($eqLogicTmp);
							CozytouchAtlanticTowelDryer::refresh_dry($eqLogicTmp);
							CozytouchAtlanticTowelDryer::refresh_thermostat($eqLogicTmp);
							break;
						case CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERAJUSTTEMP:
							CozytouchAtlanticHeatSystemWithAjustTemp::refresh_thermostat($eqLogicTmp);
							break;
						case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER:
							CozytouchAtlanticHotWater::refresh_isheating($eqLogicTmp);
							CozytouchAtlanticHotWater::refresh_boost($eqLogicTmp);
							CozytouchAtlanticHotWater::refresh_hotwatercoeff($eqLogicTmp);
							CozytouchAtlanticHotWater::refresh_thermostat($eqLogicTmp);
							break;
						case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV2AEX :
							CozytouchAtlanticHotWaterV2AEX::refresh_isheating($eqLogicTmp);
							CozytouchAtlanticHotWaterV2AEX::refresh_boost($eqLogicTmp);
							CozytouchAtlanticHotWaterV2AEX::refresh_hotwatercoeff($eqLogicTmp);
							CozytouchAtlanticHotWaterV2AEX::refresh_thermostat($eqLogicTmp);
							break;
						case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERSPLIT:
						case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCETHIV4 :
							CozytouchAtlanticHotWater::refresh_boost($eqLogicTmp);
							CozytouchAtlanticHotWater::refresh_hotwatercoeff($eqLogicTmp);
							CozytouchAtlanticHotWater::refresh_thermostat($eqLogicTmp);
							break;
						case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCES4:
							CozytouchAtlanticHotWaterCES4::refresh_boost($eqLogicTmp);
							CozytouchAtlanticHotWaterCES4::refresh_hotwatercoeff($eqLogicTmp);
							break;
						case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERFLATC2:
							CozytouchAtlanticHotWaterFlatC2::refresh_boost($eqLogicTmp);
							CozytouchAtlanticHotWaterFlatC2::refresh_hotwatercoeff($eqLogicTmp);
							break;		
						case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHEATRECOVERYVENT:
							CozytouchAtlanticVentilation::refresh_vmcmode($eqLogicTmp);
							CozytouchAtlanticVentilation::refresh_temp($eqLogicTmp);
							break;
						case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLMAIN:
							CozytouchAtlanticZoneControlMain::refresh_mode($eqLogicTmp);
							break;
						case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLZONE:
							CozytouchAtlanticZoneControlZone::refresh_mode($eqLogicTmp);
							break;	
					}
					$eqLogicTmp->refreshWidget();
				}
			}
        } 
		catch (Exception $e) 
		{
    
		}
	}
	
	public static function get_state_value($state)
	{
		if($state->name==CozyTouchStateName::CTSN_ONOFF)
		{
			$value = ($state->value=='on');
		}
		else if($state->name==CozyTouchStateName::CTSN_LIGHTSTATE)
		{
			$value = ($state->value=='on');
		}
		else if($state->name==CozyTouchStateName::CTSN_BOOSTONOFF)
		{
			$value = ($state->value=='on');
		}
		else if($state->name==CozyTouchStateName::CTSN_DHWONOFF)
		{
			$value = ($state->value=='on');
		}
		else if($state->name==CozyTouchStateName::CTSN_HEATINGONOFF)
		{
			$value = ($state->value=='on');
		}
		else if($state->name==CozyTouchStateName::CTSN_DEROGATIONONOFF)
		{
			$value = ($state->value=='on');
		}
		else if($state->name==CozyTouchStateName::CTSN_DEROGATIONREMAININGTIME)
		{
			$value = ($state->value/60);
		}
		else if($state->name==CozyTouchStateName::CTSN_COOLINGONOFF)
		{
			$value = ($state->value=='on');
		}
		else if($state->name==CozyTouchStateName::CTSN_CONNECT)
		{
			$value = ($state->value=='available');
		}
		else if($state->name==CozyTouchStateName::CTSN_OCCUPANCY)
		{
			$value = ($state->value=='noPersonInside');
		}
		else if($state->name==CozyTouchStateName::CTSN_OCCUPANCYACTIVATION)
		{
			$value = ($state->value=='active');
		}
		else if($state->name==CozyTouchStateName::CTSN_NIGHTOCCUPANCYACTIVATION)
		{
			$value = ($state->value=='active');
		}
		else if($state->name==CozyTouchStateName::CTSN_VENTILATIONMODE)
		{
			log::add('cozytouch','debug','Vmc mode info : '.json_encode($state->value));
			$value = json_encode($state->value);
		}
		else if($state->name==CozyTouchStateName::CTSN_OPEMODECAPABILITIES)
		{
			log::add('cozytouch','debug','Ope mode info : '.json_encode($state->value));
			$value = json_encode($state->value);
		}
		else
		{
			$value = $state->value;
		}
		return $value;
	}

	public static function execute($cmd,$_options)
	{
		$eqLogic = $cmd->getEqLogic();
		$attached_device = $eqLogic->getConfiguration('attached_device');
		$device_type = $eqLogic->getConfiguration('device_model');
		switch($device_type){
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER:
				CozytouchAtlanticHeatSystem::execute($cmd,$_options);
    			break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICTOWELDRYER:
				CozytouchAtlanticTowelDryer::execute($cmd,$_options);
    			break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERAJUSTTEMP:
				CozytouchAtlanticHeatSystemWithAjustTemp::execute($cmd,$_options);
    			break;
    		case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER :
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERSPLIT :
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCETHIV4 :
				CozyTouchAtlanticHotWater::execute($cmd,$_options);
				break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV2AEX :
				CozyTouchAtlanticHotWaterV2AEX::execute($cmd,$_options);
				break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCES4 :
				CozytouchAtlanticHotWaterCES4::execute($cmd,$_options);
				break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERFLATC2 :
				CozytouchAtlanticHotWaterFlatC2::execute($cmd,$_options);
    			break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV3 :
				CozytouchAtlanticHotWaterV3::execute($cmd,$_options);
				break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHEATRECOVERYVENT :
				CozytouchAtlanticVentilation::execute($cmd,$_options);
				break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCHEATINGCOOLINGZONE :
				CozytouchAtlanticHeatPumpHeatZoneComponent::execute($cmd,$_options);
				break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLMAIN:
				CozytouchAtlanticZoneControlMain::execute($cmd,$_options);
				break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLZONE:
				CozytouchAtlanticZoneControlZone::execute($cmd,$_options);
				break;
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICDIMMABLELIGHT:
				CozytouchAtlanticDimmableLight::execute($cmd,$_options);
				break;

			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCBOILER:
				CozytouchAtlanticPassAPCBoilerMain::execute($cmd,$_options);
				break;

			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCDHW:
				if($attached_device=='boiler')
				{
					CozytouchAtlanticAPCBoilerDHWComponent::execute($cmd,$_options);
				}
				else
				{
					CozytouchAtlanticHeatPumpDHWComponent::execute($cmd,$_options);
				}
				break;
		}
		$eqLogic->refreshWidget();
	}
}
?>