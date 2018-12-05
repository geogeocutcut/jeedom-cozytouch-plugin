<?php
class CozyTouchServiceDiscovery
{
	const BASE_URL = "https://ha110-1.overkiz.com/enduser-mobile-web/enduserAPI";
	Const END_POINT = [
		"login"=>self::BASE_URL."/login",
		"setup"=>self::BASE_URL."/setup",
		"devices"=>self::BASE_URL."/setup/devices",
		"deviceInfo"=>self::BASE_URL."/setup/devices/{deviceURL}/states",
		"stateInfo"=>self::BASE_URL."/setup/devices/{deviceURL}/states/{nameState}",
		"apply"=>self::BASE_URL."/exec/apply"
	];

	public static function Resolve($key,$arg=null)
	{
		if(!array_key_exists($key,self::END_POINT))
		{
			return "";
		}
		$url = self::END_POINT[$key];
		
		if(!empty($arg) && is_array($arg))
		{
			foreach ($arg as $key => $value)
			{
				$url = str_replace("{".$key."}", $value, $url);
			}
		}
		return $url;
	}
}

class CozyTouchPlaceInfo
{
    const CTPI_OID = "oid";
    const CTPI_NAME = "label";
    const CTPI_DEVICES = "devices";
}

class CozyTouchDeviceInfo
{
    const CTDI_OID = "oid";
    const CTDI_URL = "deviceURL";
    const CTDI_LABEL = "label";
    const CTDI_STATES = "states";
    const CTDI_SENSORS = "sensors";
    const CTDI_PLACEOID = "placeOID";
    const CTDI_TYPEDEVICE = "uiClass";
    const CTDI_REFERENCE = "referenceURL";
	const CTDI_OBJECT = "object";
	const CTDI_CONTROLLABLENAME = "controllableName";
}

class CozyTouchDeviceStateInfo
{
	const CTDSI_NAME = "name";
	const CTDSI_VALUE = "value";
}

class CozyTouchDeviceToDisplay
{
	const CTDTD_HEATINGSYSTEM = "HeatingSystem";
	const CTDTD_WATERHEATINGSYSTEM = "WaterHeatingSystem";

	const CTDTD_ATLANTICELECTRICHEATER ="io:AtlanticElectricalHeaterWithAdjustableTemperatureSetpointIOComponent";
	const CTDTD_ATLANTICELECTRICHEATERTEMPERATURESENSOR = "io:TemperatureInCelciusIOSystemDeviceSensor";
	const CTDTD_ATLANTICELECTRICHEATEROCCUPANCYSENSOR = "io:OccupancyIOSystemDeviceSensor";
	const CTDTD_ATLANTICELECTRICHEATERELECTRICITYSENSOR = "io:CumulatedElectricalEnergyConsumptionIOSystemDeviceSensor";
	
	const CTDTD_ATLANTICHOTWATER ="io:AtlanticDomesticHotWaterProductionIOComponent";
	const CTDTD_ATLANTICHOTWATERELECTRICITYSENSOR ="io:DHWCumulatedElectricalEnergyConsumptionIOSystemDeviceSensor";

	
	const CTDTD_DEVICEMODEL = [self::CTDTD_ATLANTICELECTRICHEATER,self::CTDTD_ATLANTICHOTWATER];
	const CTDTD_NAME = [
		self::CTDTD_HEATINGSYSTEM=>"Radiateur",
		self::CTDTD_WATERHEATINGSYSTEM=>"Chauffe eau"
	];

	const CTDTD_DEVICESANDSENSORS = [
		self::CTDTD_ATLANTICELECTRICHEATER,
		self::CTDTD_ATLANTICELECTRICHEATERTEMPERATURESENSOR,
		self::CTDTD_ATLANTICELECTRICHEATEROCCUPANCYSENSOR,
		self::CTDTD_ATLANTICELECTRICHEATERELECTRICITYSENSOR,
		self::CTDTD_ATLANTICHOTWATER,
		self::CTDTD_ATLANTICHOTWATERELECTRICITYSENSOR
	];
}

class CozyTouchStateName
{
	const CTSN_NAME = "core:NameState";
	const CTSN_OPEMODE = "core:OperatingModeState";
	const CTSN_ONOFF = "core:OnOffState";
	const CTSN_TARGETHEATLEVEL = "io:TargetHeatingLevelState";
	const CTSN_TARGETTEMP = "core:TargetTemperatureState";
	const CTSN_COMFROOMTEMP = "core:ComfortRoomTemperatureState";
	const CTSN_ECOROOMTEMP = "core:EcoRoomTemperatureState";
	const CTSN_DEROGTARGETTEMP = "core:DerogatedTargetTemperatureState";
	const CTSN_EFFTEMPSETPOINT = "io:EffectiveTemperatureSetpointState";
	const CTSN_TEMPPROBECALIBR = "io:TemperatureProbeCalibrationOffsetState";

	const CTSN_BOOSTMODEDURATION = "core:BoostModeDurationState";
	const CTSN_TEMP = "core:TemperatureState";
	const CTSN_WATERCONSUMPTION = "core:WaterConsumptionState";
	const CTSN_AWAYMODEDURATION = "io:AwayModeDurationState";
	const CTSN_DHWMODE = "io:DHWModeState";
	const CTSN_DHWCAPACITY = "io:DHWCapacityState";
	
	const CTSN_OCCUPANCY = "core:OccupancyState";
	
	const CTSN_ELECNRJCONSUMPTION = "core:ElectricEnergyConsumptionState";

	const CTSN_TYPE = [
		self::CTSN_NAME=>"string",
		self::CTSN_OPEMODE=>"string",
		self::CTSN_TARGETHEATLEVEL=>"string",
		self::CTSN_DHWMODE=>"string",

		self::CTSN_TARGETTEMP=>"numeric",
		self::CTSN_COMFROOMTEMP=>"numeric",
		self::CTSN_ECOROOMTEMP=>"numeric",
		self::CTSN_DEROGTARGETTEMP=>"numeric",
		self::CTSN_EFFTEMPSETPOINT=>"numeric",
		self::CTSN_TEMPPROBECALIBR=>"numeric",
		self::CTSN_BOOSTMODEDURATION=>"numeric",
		self::CTSN_TEMP=>"numeric",
		self::CTSN_WATERCONSUMPTION=>"numeric",
		self::CTSN_AWAYMODEDURATION=>"numeric",
		self::CTSN_DHWCAPACITY=>"numeric",
		self::CTSN_ELECNRJCONSUMPTION=>"numeric",

		self::CTSN_OCCUPANCY=>"binary",
		self::CTSN_ONOFF=>"binary"
	];

	const CTSN_LABEL = [
		self::CTSN_NAME=>"Label",
		self::CTSN_OPEMODE=>"Programation",
		self::CTSN_ONOFF=>"On/Off",
		self::CTSN_TARGETHEATLEVEL=>"Mode",
		self::CTSN_DHWMODE=>"Programation",

		self::CTSN_TARGETTEMP=>"Temp. Cible",
		self::CTSN_COMFROOMTEMP=>"Temp. Comfort",
		self::CTSN_ECOROOMTEMP=>"Temp. Eco",
		self::CTSN_DEROGTARGETTEMP=>"Derogation",
		self::CTSN_EFFTEMPSETPOINT=>"Effective",
		self::CTSN_TEMPPROBECALIBR=>"Calibrage",
		self::CTSN_BOOSTMODEDURATION=>"Boost Durée",
		self::CTSN_TEMP=>"Temp.",
		self::CTSN_WATERCONSUMPTION=>"Conso Eau",
		self::CTSN_AWAYMODEDURATION=>"Absent Durée",
		self::CTSN_DHWCAPACITY=>"Capacité Eau",
		self::CTSN_ELECNRJCONSUMPTION=>"Conso Elec",

		self::CTSN_OCCUPANCY=>"Présence"
	];
}

class CozyTouchDeviceStateName
{
	const DEVICE_STATENAME = [
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_OPEMODE,
			CozyTouchStateName::CTSN_ONOFF,
			CozyTouchStateName::CTSN_TARGETHEATLEVEL,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_COMFROOMTEMP,
			CozyTouchStateName::CTSN_ECOROOMTEMP,
			CozyTouchStateName::CTSN_DEROGTARGETTEMP,
			CozyTouchStateName::CTSN_EFFTEMPSETPOINT,
			CozyTouchStateName::CTSN_TEMPPROBECALIBR],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_OPEMODE,
			CozyTouchStateName::CTSN_TEMP,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_WATERCONSUMPTION,
			CozyTouchStateName::CTSN_AWAYMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERTEMPERATURESENSOR=>[
			CozyTouchStateName::CTSN_TEMP],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATEROCCUPANCYSENSOR=>[
			CozyTouchStateName::CTSN_OCCUPANCY],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERELECTRICITYSENSOR=>[
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERELECTRICITYSENSOR=>[
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION]
	];

	const EQLOGIC_STATENAME = [
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_OPEMODE,
			CozyTouchStateName::CTSN_ONOFF,
			CozyTouchStateName::CTSN_TARGETHEATLEVEL,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_COMFROOMTEMP,
			CozyTouchStateName::CTSN_ECOROOMTEMP,
			CozyTouchStateName::CTSN_DEROGTARGETTEMP,
			CozyTouchStateName::CTSN_EFFTEMPSETPOINT,
			CozyTouchStateName::CTSN_TEMPPROBECALIBR,
			CozyTouchStateName::CTSN_TEMP,
			CozyTouchStateName::CTSN_OCCUPANCY,
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_OPEMODE,
			CozyTouchStateName::CTSN_TEMP,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_WATERCONSUMPTION,
			CozyTouchStateName::CTSN_AWAYMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION]
	];
}


class CozyTouchDeviceSensorInfo
{
	const CTDSI_OID = "oid";
    const CTDSI_URL = "deviceURL";
    const CTDSI_STATES = "states";
}

class CozyTouchDeviceActions
{
	const SET_STANDBY = "setStandByMode";
	const SET_BASIC = "setBasicMode";
	const SET_EXTERNAL = "setExternalMode";
	const SET_INTERNAL = "setInternalMode";
	const SET_AUTO = "setAutoMode";
	const SET_TARGETTEMP ="setTargetTemperature";
	const SET_THERMOSTAT ='cozytouchThermostat';
	const EQLOGIC_ACTIONS = [
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER=>[
			self::SET_STANDBY,
			self::SET_BASIC,
			self::SET_EXTERNAL,
			self::SET_INTERNAL,
			self::SET_AUTO
		]
	];
	const ACTION_LABEL = [
		self::SET_STANDBY=>"StandBy",
		self::SET_BASIC=>"Basic",
		self::SET_EXTERNAL=>"Externe",
		self::SET_INTERNAL=>"Interne",
		self::SET_AUTO=>"Auto"
	];
}

class CozyTouchActionLst
{
	// Atlantic chauffage electrique
	const CTA_SETMODE = "setOperatingMode"; // parameters : standby / basic / internal / external / auto
	const CTPC_SETTARGETTEMP = "setTargetTemperature"; //parameters : 18
	const CTPC_SETDEROGTEMP = "setDerogatedTargetTemperature"; //parameters : 18
	const CTPC_SETCOMTEMP = "setComfortTemperature"; //parameters : 18
	const CTPC_SETECOTEMP = "setEcoTemperature"; //parameters : 3,5  // => 18-3,5

	const CTPC_SETHEATINGLVL = "setHeatingLevel"; //parameters : eco, comfort
	const CTPC_CANCELHEATINGLVL = "cancelHeatingLevel"; //parameters : eco, comfort

	const CTPC_RSHMODE = "refreshOperatingMode";
	
	const CTPC_RSHTARGETTEMP = "refreshTargetTemperature";
	const CTPC_RSHDEROGTEMP = "refreshDerogatedTargetTemperature"; //parameters : 18
	const CTPC_RSHCOMTEMP = "refreshComfortTemperature"; //parameters : 18
	const CTPC_RSHECOTEMP = "refreshEcoTemperature"; //parameters : 3,5  // => 18-3,5
	const CTPC_RSHEFFTEMP = "refreshEffectiveTemperatureSetpoint"; //parameters : 3,5  // => 18-3,5
	const CTPC_RSHHEATINGLVL = "refreshHeatingLevel"; //parameters : eco, comfort
	
	// Atlantic ballon d'eau chaude
	const CTPC_RSHWATERCONS = "refreshWaterConsumption";
	const CTPC_RSHAWAYDUR = "refreshAwayModeDuration";
	const CTPC_RSHBOOSTDUR = "refreshBoostModeDuration";
	const CTPC_RSHDHWCAPACITY = "refreshDHWCapacity";
	const CTPC_RSHDHWMODE = "refreshDHWMode";
	const CTPC_SETAWAYDUR = "setAwayModeDuration";
	const CTPC_SETBOOSTDUR = "setBoostModeDuration";
	const CTPC_SETDHWMODE = "setDHWMode";
}
?>
