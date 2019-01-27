<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

if (!class_exists('CozytouchAtlanticHeatPumpDHWComponent')) {
	require_once dirname(__FILE__) . "/CozytouchAtlanticHeatPumpDHWComponent.class.php";
}
if (!class_exists('CozytouchAtlanticHeatPumpHeatZoneComponent')) {
	require_once dirname(__FILE__) . "/CozytouchAtlanticHeatPumpHeatZoneComponent.class.php";
}

class CozytouchAtlanticHeatPump extends AbstractCozytouchDevice
{
	// io:AtlanticPassAPCHeatPumpMainComponent
	//		Equipements à créer
	// 		io:AtlanticPassAPCDHWComponent
	//		io:AtlanticPassAPCHeatingAndCoolingZoneComponent
	//			io:AtlanticPassAPCZoneTemperatureSensor
	//
	//		Capteur à associée
	//		io:AtlanticPassAPCOutsideTemperatureSensor
	//		io:TotalElectricalEnergyConsumptionSensor
	// 		io:DHWRelatedElectricalEnergyConsumptionSensor
	//		io:HeatingRelatedElectricalEnergyConsumptionSensor

    const cold_water = 15;
    //[{order},{beforeLigne},{afterLigne}]
	const DISPLAY = [
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
			$sensorModel = $sensor->getModel();
			// création des équipements
			//  getModel()
			// controllableName: io:AtlanticPassAPCDHWComponent,
			//					 io:AtlanticPassAPCHeatingAndCoolingZoneComponent
			// 					 rattacher io:AtlanticPassAPCZoneTemperatureSensor à l'équipement précédent créé.
			// sinon
			$sensors[] = array($sensorURL,$sensor->getModel());
			log::add('cozytouch', 'info', 'Sensor : '.$sensorURL);
			switch($sensorModel)
			{
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCOUTSIDETEMPERATURESENSOR :
					// state du capteur
					foreach ($sensor->getStates() as $state)
					{
						if($state->name==CozyTouchStateName::CTSN_TEMP)
						{
							log::add('cozytouch', 'info', 'State : '.$state->name);
							$cmdId = $sensorURL.'_'.$state->name;
							$type ="info";
							$subType = CozyTouchStateName::CTSN_TYPE[$state->name];
							$name = "Temp. extérieur";
							$dashboard =CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
							$mobile =CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
							$value =$subType=="numeric"?0:($subType=="string"?'value':0);
							self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,$value,$dashboard,$mobile,$i+1);
							break;
						}
					}
					break;
				case CozyTouchDeviceToDisplay::CTDTD_TOTALENERGYCONSUMPTIONSENSOR :
					// state du capteur
					foreach ($sensor->getStates() as $state)
					{
						if($state->name==CozyTouchStateName::CTSN_ELECNRJCONSUMPTION)
						{
							log::add('cozytouch', 'info', 'State : '.$state->name);
							$cmdId = $sensorURL.'_'.$state->name;
							$type ="info";
							$subType = CozyTouchStateName::CTSN_TYPE[$state->name];
							$name = "Conso. totale";
							$dashboard =CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
							$mobile =CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
							$value =$subType=="numeric"?0:($subType=="string"?'value':0);
							self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,$value,$dashboard,$mobile,$i+1);
							break;
						}
					}
					break;
				case CozyTouchDeviceToDisplay::CTDTD_DHWENERGYCONSUMPTIONSENSOR :
					// state du capteur
					foreach ($sensor->getStates() as $state)
					{
						if($state->name==CozyTouchStateName::CTSN_ELECNRJCONSUMPTION)
						{
							log::add('cozytouch', 'info', 'State : '.$state->name);
							$cmdId = $sensorURL.'_'.$state->name;
							$type ="info";
							$subType = CozyTouchStateName::CTSN_TYPE[$state->name];
							$name = "Conso. hotwater";
							$dashboard =CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
							$mobile =CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
							$value =$subType=="numeric"?0:($subType=="string"?'value':0);
							self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,$value,$dashboard,$mobile,$i+1);
							break;
						}
					}
					break;
				case CozyTouchDeviceToDisplay::CTDTD_HEATINGENERGYCONSUMPTIONSENSOR :
					// state du capteur
					foreach ($sensor->getStates() as $state)
					{
						if($state->name==CozyTouchStateName::CTSN_ELECNRJCONSUMPTION)
						{
							log::add('cozytouch', 'info', 'State : '.$state->name);
							$cmdId = $sensorURL.'_'.$state->name;
							$type ="info";
							$subType = CozyTouchStateName::CTSN_TYPE[$state->name];
							$name = "Conso. heatsystem";
							$dashboard =CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
							$mobile =CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
							$value =$subType=="numeric"?0:($subType=="string"?'value':0);
							self::upsertCommand($eqLogic,$cmdId,$type,$subType,$name,1,$value,$dashboard,$mobile,$i+1);
							break;
						}
					}
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCDHW :
					CozytouchAtlanticHeatPumpDHWComponent::BuildEqLogic($sensor);
					break;
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCHEATINGCOOLINGZONE :
					CozytouchAtlanticHeatPumpHeatZoneComponent::BuildEqLogic($sensor);
					break;
			}
			
		}

		$eqLogic->setConfiguration('sensors',$sensors);

		$eqLogic->save();

		
        

        self::orderCommand($eqLogic);

        CozyTouchManager::refresh_all();
    }
    
	public static function orderCommand($eqLogic)
	{
		
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
}
?>