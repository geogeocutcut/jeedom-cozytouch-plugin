<?php

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
    const CTDI_STATES = "states";
    const CTDI_SENSORS = "sensors";
    const CTDI_PLACEOID = "placeOID";
    const CTDI_TYPEDEVICE = "uiClass";
    const CTDI_REFERENCE = "referenceURL";
	const CTDI_OBJECT = "object";
	const CTDI_CONTROLLERNAME = "controllerName";
}

class CozyTouchDeviceStateInfo
{
	const CTDSI_NAME = "name";
	const CTDSI_VALUE = "value";
}

class CozyTouchDeviceToDisplay
{
	public static $CTDTD_HEATINGSYSTEM = "HeatingSystem";
	public static $CTDTD_WATERHEATINGSYSTEM = "WaterHeatingSystem";
	public static $CTDTD_TEMPERATURESENSOR = "TemperatureSensor";
	public static $CTDTD_OCCUPANCYSENSOR = "OccupancySensor";
	public static $CTDTD_ELECTRICITYSENSOR = "ElectricitySensor";

	public static $CTDTD_CLASS = [CTDTD_HEATINGSYSTEM,CTDTD_WATERHEATINGSYSTEM,CTDTD_TEMPERATURESENSOR,CTDTD_OCCUPANCYSENSOR,CTDTD_ELECTRICITYSENSOR];
	//public static $CTDTD_SUFFIXE = ["#1","#2","#4","#5"];
}

class CozyTouchDeviceStateName
{
	public static $DEVICE_STATENAME = array(
		CTDTD_HEATINGSYSTEM=>[
			"core:NameState",
			"core:OperatingModeState",
			"core:OnOffState",
			"io:TargetHeatingLevelState",
			"core:TargetTemperatureState",
			"core:ComfortRoomTemperatureState",
			"core:EcoRoomTemperatureState",
			"core:DerogatedTargetTemperatureState",
			"io:EffectiveTemperatureSetpointState",
			"io:TemperatureProbeCalibrationOffsetState"],
		CTDTD_WATERHEATINGSYSTEM=>[
			"core:NameState",
			"core:TemperatureState",
			"core:BoostModeDurationState",
			"core:WaterConsumptionState",
			"io:HeatPumpOperatingTimeState",
			"io:DHWModeState",
			"core:TargetTemperatureState",
			"core:ManufacturerNameState"],
		CTDTD_TEMPERATURESENSOR=>[
			"core:TemperatureState"],
		CTDTD_OCCUPANCYSENSOR=>[
			"core:OccupancyState"],
		CTDTD_ELECTRICITYSENSOR=>[
			"core:ElectricEnergyConsumptionState"],
	);
	public static $CTDS1_NAME = [
		"core:NameState",
		"core:OperatingModeState",
		"core:OnOffState",
		"io:TargetHeatingLevelState",
		"core:TargetTemperatureState",
		"core:ComfortRoomTemperatureState",
		"core:EcoRoomTemperatureState",
		"core:DerogatedTargetTemperatureState",
		"io:EffectiveTemperatureSetpointState",
		"io:TemperatureProbeCalibrationOffsetState"];
	
	public static $CTDS2_NAME = ["core:TemperatureState"];
	
	public static $CTDS4_NAME = ["core:OccupancyState"];
	
	public static $CTDS5_NAME = ["core:ElectricEnergyConsumptionState"];
	
	
}
	

class CozyTouchDeviceSensorInfo
{
	const CTDSI_OID = "oid";
    const CTDSI_URL = "deviceURL";
    const CTDSI_STATES = "states";
}

class CozyTouchDeviceActions
{
	const CTPC_SETMODE = "setOperatingMode"; // parameters : standby / basic / internal / external / auto
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
	
	
	/* [{
		"commandName": "off",
		"nparams": 0,
		"qualifiedName": "core: OffCommand"
	},
	{
		"commandName": "refreshComfortTemperature",
		"nparams": 0,
		"qualifiedName": "core: RefreshComfortTemperatureCommand"
	},
	{
		"commandName": "refreshDerogatedTargetTemperature",
		"nparams": 0,
		"qualifiedName": "core: RefreshDerogatedTargetTemperatureCommand"
	},
	{
		"commandName": "refreshEcoTemperature",
		"nparams": 0,
		"qualifiedName": "core: RefreshEcoTemperatureCommand"
	},
	{
		"commandName": "refreshHeatingLevel",
		"nparams": 0,
		"qualifiedName": "core: RefreshHeatingLevelCommand"
	},
	{
		"commandName": "refreshOperatingMode",
		"nparams": 0,
		"qualifiedName": "core: RefreshOperatingModeCommand"
	},
	{
		"commandName": "refreshTargetTemperature",
		"nparams": 0,
		"qualifiedName": "core: RefreshTargetTemperatureCommand"
	},
	{
		"commandName": "setComfortTemperature",
		"nparams": 1,
		"qualifiedName": "core: SetComfortTemperatureCommand"
	},
	{
		"commandName": "setDerogatedTargetTemperature",
		"nparams": 1,
		"qualifiedName": "core: SetDerogatedTargetTemperatureCommand"
	},
	{
		"commandName": "setEcoTemperature",
		"nparams": 1,
		"qualifiedName": "core: SetEcoTemperatureCommand"
	},
	{
		"commandName": "setOccupancyActivation",
		"nparams": 1,
		"qualifiedName": "core: SetOccupancyActivationCommand"
	},
	{
		"commandName": "setOpenWindowDetectionActivation",
		"nparams": 1,
		"qualifiedName": "core: SetOpenWindowDetectionActivationCommand"
	},
	{
		"commandName": "setOperatingMode",
		"nparams": 1,
		"qualifiedName": "core: SetOperatingModeCommand"
	},
	{
		"commandName": "setSchedulingType",
		"nparams": 1,
		"qualifiedName": "core: SetSchedulingTypeCommand"
	},
	{
		"commandName": "setTargetTemperature",
		"nparams": 1,
		"qualifiedName": "core: SetTargetTemperatureCommand"
	},
	{
		"commandName": "refreshEffectiveTemperatureSetpoint",
		"nparams": 0,
		"qualifiedName": "io: RefreshEffectiveTemperatureSetpointCommand"
	},
	{
		"commandName": "refreshTemperatureProbeCalibrationOffset",
		"nparams": 0,
		"qualifiedName": "io: RefreshTemperatureProbeCalibrationOffsetCommand"
	},
	{
		"commandName": "setTemperatureProbeCalibrationOffset",
		"nparams": 1,
		"qualifiedName": "io: SetTemperatureProbeCalibrationOffsetCommand"
	},
	{
		"commandName": "setHeatingLevel",
		"nparams": 1,
		"qualifiedName": "core: SetHeatingLevelCommand"
	}] */
}

// class CozyTouchPlaceDeviceState
// {
// 	// core:OperatingModeState 
// 	// core:TargetTemperatureState
// 	const CTPDS_STATETEMP = "core:TargetTemperatureState"; //parameters : 18
// 	const CTPDS_STATEMODE = "core:OperatingModeState "; // parameters : standby / basic / internal / external / auto
// }



// setup
//   devices
//      states
//   place

// off
// mode : standby / basic / internal / external / auto
// target temp�rature
// derogation temp�rature

?>
