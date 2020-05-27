<?php

class CozyTouchCmdDisplay
{
	const DISPLAY_DASH = [
		'numeric'=>'tilecozy',
		'string'=>'badge',
		'binaire'=>''
	];
	const DISPLAY_MOBILE = [
		'numeric'=>'tile',
		'string'=>'badge',
		'binaire'=>''
	];
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
	// class
	const CTDTD_HEATINGSYSTEM = "HeatingSystem";
	const CTDTD_WATERHEATINGSYSTEM = "WaterHeatingSystem";
	const CTDTD_VENTILATIONSYSTEM ="VentilationSystem";
	// widget
	const CTDTD_HEATPUMPSYSTEM ="HeatPump";
	const CTDTD_ZONECONTROLMAINSYSTEM ="AtlanticPassAPCZoneControl";
	const CTDTD_ZONECONTROLZONESYSTEM ="AtlanticPassAPCHeatingAndCoolingZone";

	const CTDTD_ATLANTICELECTRICHEATER = "io:AtlanticElectricalHeaterIOComponent";

	const CTDTD_ATLANTICELECTRICHEATERAJUSTTEMP ="io:AtlanticElectricalHeaterWithAdjustableTemperatureSetpointIOComponent";
	const CTDTD_ATLANTICTEMPERATURESENSOR = "io:TemperatureInCelciusIOSystemDeviceSensor";
	const CTDTD_ATLANTICELECTRICHEATEROCCUPANCYSENSOR = "io:OccupancyIOSystemDeviceSensor";
	const CTDTD_ATLANTICELECTRICHEATERELECTRICITYSENSOR = "io:CumulatedElectricalEnergyConsumptionIOSystemDeviceSensor";
	const CTDTD_ATLANTICDIMMABLELIGHT = "io:AtlanticDimmableLightIOComponent";

	const CTDTD_ATLANTICTOWELDRYER= "io:AtlanticElectricalTowelDryerIOComponent";
	const CTDTD_ATLANTICLIGHT = "io:LightIOSystemDeviceSensor";
	const CTDTD_ATLANTICRELATIVEHUMIDITY = "io:RelativeHumidityIOSystemDeviceSensor";

	const CTDTD_ATLANTICHOTWATER ="io:AtlanticDomesticHotWaterProductionIOComponent";
	const CTDTD_ATLANTICHOTWATERSPLIT ="io:AtlanticDomesticHotWaterProductionV2_SPLIT_IOComponent";
	const CTDTD_ATLANTICHOTWATERCES4 ="io:AtlanticDomesticHotWaterProductionV2_CE_S4_IOComponent";
	const CTDTD_ATLANTICHOTWATERFLATC2 ="io:AtlanticDomesticHotWaterProductionV2_CE_FLAT_C2_IOComponent";
	const CTDTD_ATLANTICHOTWATERCETHIV4 ="io:AtlanticDomesticHotWaterProductionV2_CETHI_V4_IOComponent";
	const CTDTD_ATLANTICHOTWATERV3="io:AtlanticDomesticHotWaterProductionV3IOComponent";
	const CTDTD_ATLANTICHOTWATERV2AEX="io:AtlanticDomesticHotWaterProductionV2_AEX_IOComponent";
	const CTDTD_ATLANTICHOTWATERELECTRICITYSENSOR ="io:DHWCumulatedElectricalEnergyConsumptionIOSystemDeviceSensor";

	const CTDTD_ATLANTICHEATRECOVERYVENT ="io:AtlanticHeatRecoveryVentilationIOComponent";
	const CTDTD_ATLANTICC02SENSOR ="io:CO2IOSystemDeviceSensor";

	const CTDTD_ATLANTICPASSAPCHEATPUMPMAIN="io:AtlanticPassAPCHeatPumpMainComponent";
	const CTDTD_ATLANTICPASSAPCDHW="io:AtlanticPassAPCDHWComponent";
	const CTDTD_ATLANTICPASSAPCOUTSIDETEMPERATURESENSOR ="io:AtlanticPassAPCOutsideTemperatureSensor";
	const CTDTD_TOTALENERGYCONSUMPTIONSENSOR ="io:TotalElectricalEnergyConsumptionSensor";
	const CTDTD_DHWENERGYCONSUMPTIONSENSOR ="io:DHWRelatedElectricalEnergyConsumptionSensor";
	const CTDTD_HEATINGENERGYCONSUMPTIONSENSOR ="io:HeatingRelatedElectricalEnergyConsumptionSensor";
	const CTDTD_ATLANTICPASSAPCHEATINGCOOLINGZONE ="io:AtlanticPassAPCHeatingAndCoolingZoneComponent";
	const CTDTD_ATLANTICPASSAPCZONETEMPERATURESENSOR ="io:AtlanticPassAPCZoneTemperatureSensor";
	
	const CTDTD_ATLANTICPASSAPCZONECTRLMAIN="io:AtlanticPassAPCZoneControlMainComponent";
	const CTDTD_ATLANTICPASSAPCZONECTRLZONE="io:AtlanticPassAPCZoneControlZoneComponent";

	const CTDTD_ATLANTICPASSAPCBOILER="io:AtlanticPassAPCBoilerMainComponent";
	const CTDTD_TOTALFOSSILENERGYCONSUMPTION="io:TotalFossilEnergyConsumptionSensor";
	const CTDTD_DHWRELATEDFOSSILENERGYCONSUMPTION="io:DHWRelatedFossilEnergyConsumptionSensor";
	const CTDTD_HEATINGRELATEDFOSSILENERGYCONSUMPTION="io:HeatingRelatedFossilEnergyConsumptionSensor";
	const CTDTD_ATLANTICPASSAPCHEATINGZONE="io:AtlanticPassAPCHeatingZoneComponent";

	const CTDTD_DEVICEMODEL = [
		self::CTDTD_ATLANTICELECTRICHEATER,
		self::CTDTD_ATLANTICELECTRICHEATERAJUSTTEMP,
		self::CTDTD_ATLANTICTOWELDRYER,
		self::CTDTD_ATLANTICHOTWATER,
		self::CTDTD_ATLANTICHOTWATERSPLIT,
		self::CTDTD_ATLANTICHOTWATERCETHIV4,
		self::CTDTD_ATLANTICHOTWATERCES4,
		self::CTDTD_ATLANTICHOTWATERFLATC2,
		self::CTDTD_ATLANTICHOTWATERV3,
		self::CTDTD_ATLANTICHOTWATERV2AEX,
		self::CTDTD_ATLANTICHEATRECOVERYVENT,
		self::CTDTD_ATLANTICPASSAPCHEATPUMPMAIN,
		self::CTDTD_ATLANTICPASSAPCZONECTRLMAIN
	];

	const CTDTD_NAME = [
		self::CTDTD_HEATINGSYSTEM=>"Radiateur",
		self::CTDTD_WATERHEATINGSYSTEM=>"Chauffe eau",
		self::CTDTD_VENTILATIONSYSTEM=>"VMC",
		self::CTDTD_HEATPUMPSYSTEM=>"Pompe à chaleur",
		self::CTDTD_ZONECONTROLMAINSYSTEM=>"PAC Centrale",
		self::CTDTD_ZONECONTROLZONESYSTEM=>"PAC Zone",

	];

	const CTDTD_DEVICESANDSENSORS = [
		self::CTDTD_ATLANTICELECTRICHEATER,
		self::CTDTD_ATLANTICELECTRICHEATERAJUSTTEMP,
		self::CTDTD_ATLANTICTEMPERATURESENSOR,
		self::CTDTD_ATLANTICELECTRICHEATEROCCUPANCYSENSOR,
		self::CTDTD_ATLANTICELECTRICHEATERELECTRICITYSENSOR,
		self::CTDTD_ATLANTICDIMMABLELIGHT,
		self::CTDTD_ATLANTICTOWELDRYER,
		self::CTDTD_ATLANTICLIGHT,
		self::CTDTD_ATLANTICRELATIVEHUMIDITY,

		self::CTDTD_ATLANTICHOTWATER,
		self::CTDTD_ATLANTICHOTWATERSPLIT,
		self::CTDTD_ATLANTICHOTWATERCETHIV4,
		self::CTDTD_ATLANTICHOTWATERCES4,
		self::CTDTD_ATLANTICHOTWATERFLATC2,
		self::CTDTD_ATLANTICHOTWATERV3,
		self::CTDTD_ATLANTICHOTWATERV2AEX,
		self::CTDTD_ATLANTICHOTWATERELECTRICITYSENSOR,
		self::CTDTD_ATLANTICHEATRECOVERYVENT,
		self::CTDTD_ATLANTICC02SENSOR,
		
		self::CTDTD_ATLANTICPASSAPCHEATPUMPMAIN,
		self::CTDTD_ATLANTICPASSAPCDHW,
		self::CTDTD_ATLANTICPASSAPCOUTSIDETEMPERATURESENSOR,
		self::CTDTD_TOTALENERGYCONSUMPTIONSENSOR,
		self::CTDTD_DHWENERGYCONSUMPTIONSENSOR,
		self::CTDTD_HEATINGENERGYCONSUMPTIONSENSOR,
		self::CTDTD_ATLANTICPASSAPCHEATINGCOOLINGZONE,
		self::CTDTD_ATLANTICPASSAPCZONETEMPERATURESENSOR,
		self::CTDTD_ATLANTICPASSAPCZONECTRLMAIN,
		self::CTDTD_ATLANTICPASSAPCZONECTRLZONE
	
	];
}

class CozyTouchStateName
{
	const CTSN_NAME = "core:NameState";
	const CTSN_CONNECT = "core:StatusState";
	const CTSN_OPEMODE = "core:OperatingModeState";
	const CTSN_ONOFF = "core:OnOffState";
	const CTSN_TARGETHEATLEVEL = "io:TargetHeatingLevelState";
	const CTSN_TARGETTEMP = "core:TargetTemperatureState";
	const CTSN_COMFROOMTEMP = "core:ComfortRoomTemperatureState";
	const CTSN_ECOROOMTEMP = "core:EcoRoomTemperatureState";
	const CTSN_DEROGTARGETTEMP = "core:DerogatedTargetTemperatureState";
	const CTSN_EFFTEMPSETPOINT = "io:EffectiveTemperatureSetpointState";
	const CTSN_TEMPPROBECALIBR = "io:TemperatureProbeCalibrationOffsetState";
	const CTSN_OCCUPANCY = "core:OccupancyState";
	const CTSN_ELECNRJCONSUMPTION = "core:ElectricEnergyConsumptionState";

	// Seche serviette
	const CTSN_BOOSTDURATIONUSERPARAM = "io:BoostDurationUserParameterState";
	const CTSN_BOOSTDURATIONMAX = "io:BoostDurationMaxState";
	const CTSN_DRYINGDURATION = "io:DryingDurationState";
	const CTSN_DRYINGDURATIONUSERPARAM = "io:DryingDurationUserParameterState";
	const CTSN_DRYINGDURATIONMAX = "io:DryingDurationMaxState";
	const CTSN_TOWELDRYERTEMPORARY = "io:TowelDryerTemporaryStateState";
	const CTSN_LUMINANCE = "core:LuminanceState";
	const CTSN_RELATIVEHUMIDITY = "core:RelativeHumidityState";

	//Dimmable light
	const CTSN_LIGHTSTATE ="core:LightState";
	const CTSN_LIGHTINTENSITY ="core:LightIntensityState";
	const CTSN_AUTOTURNOFF ="core:AutomaticTurnOffDelayConfigurationState";
	const CTSN_REMAININGTIME ="core:RemainingTimeState";
	const CTSN_OCCUPANCYACTIVATION ="core:OccupancyActivationState";
	const CTSN_NIGHTOCCUPANCYACTIVATION ="core:NightOccupancyActivationState";

	// HotWater
	const CTSN_BOOSTMODEDURATION = "core:BoostModeDurationState";
	const CTSN_TEMP = "core:TemperatureState";
	const CTSN_WATERCONSUMPTION = "core:WaterConsumptionState";
	const CTSN_AWAYMODEDURATION = "io:AwayModeDurationState";
	const CTSN_MIDDLETEMP = "io:MiddleWaterTemperatureState";
	const CTSN_DHWMODE = "io:DHWModeState";
	const CTSN_DHWCAPACITY = "io:DHWCapacityState";

	const CTSN_DHWBOOSTMODE = "io:DHWBoostModeState";
	const CTSN_DHWABSENCEMODE = "io:DHWAbsenceModeState";
	const CTSN_NUMBERTANK = "core:NumberOfTankState";
	const CTSN_V40WATERVOLUME = "core:V40WaterVolumeEstimationState";
	const CTSN_WATERTEMP = "core:WaterTemperatureState";
	const CTSN_WATERTARGETTEMP = "core:WaterTargetTemperatureState";
	const CTSN_EXPECTEDNBSHOWER = "core:ExpectedNumberOfShowerState";
	const CTSN_CTRLWATERTARGETTEMP = "core:ControlWaterTargetTemperatureState";
	const CTSN_MIDDLEWATERTEMPIN = "core:MiddleWaterTemperatureInState";
	const CTSN_HEATINGSTATUS = "core:HeatingStatusState";
	const CTSN_NBSHOWERREMAINING = "core:NumberOfShowerRemainingState";
	const CTSN_MINISHOWERMANUAL = "core:MinimalShowerManualModeState";
	const CTSN_MAXISHOWERMANUAL = "core:MaximalShowerManualModeState";
	const CTSN_OPEMODECAPABILITIES = "io:OperatingModeCapabilitiesState"; // {"energyDemandStatus": 0,"relaunch": 1,"absence": 1,"rateManagement": 0}

	const CTSN_AIRDEMANDE ="core:AirDemandState";
	const CTSN_VENTILATIONCONFIG ="io:VentilationConfigurationModeState";
	const CTSN_AIRDEMANDEMODE ="io:AirDemandModeState";
	const CTSN_VENTILATIONMODE ="io:VentilationModeState"; // Attention {"prog": "off",	"endOfLineTest": "off",	"test": "off","month": 12,"cooling": "off","leapYear": "off","day": 18,"dayNight": "night"	}
	const CTSN_CO2CONCENTRATION = "core:CO2ConcentrationState";


	
	// Pompe à chaleur / zone controle
	const CTSN_PASSAPCPRODUCTTYPE = "io:PassAPCProductTypeState"; // "values": ["accumulationDomesticHotWater","airConditioning","boiler","convector","doubleFlowControlledMechanicalVentilation","heatPump","heater","hybrid","singleFlowControlledMechanicalVentilation","thermodynamicDomesticHotWater","zoneController"],
	const CTSN_ZONESNUMBER = "core:ZonesNumberState";
	const CTSN_PASSAPCOPERATINGMODE = "io:PassAPCOperatingModeState";
	const CTSN_THERMALSCHEDULINGMODE = "io:ThermalSchedulingModeState"; // "values": ["heatingAndCoolingCommonScheduling","heatingAndCoolingSeparatedScheduling"]
	const CTSN_ABSENCESCHEDULINGMODE = "io:AbsenceSchedulingModeState"; // dateScheduling / numberOfDaysScheduling
	const CTSN_ABSENCEHEATINGTARGETTEMP = "core:AbsenceHeatingTargetTemperatureState";
	const CTSN_ABSENCECOOLINGTARGETTEMP = "core:AbsenceCoolingTargetTemperatureState";
	const CTSN_ABSENCEENDDATETIME = "core:AbsenceEndDateTimeState";//"value": {"minute": 0,"month": 11,"year": 2018,"hour": 0,"day": 30}
	const CTSN_LASTPASSAPCOPERATINGMODE = "io:LastPassAPCOperatingModeState"; // cooling / drying / heating / stop
	const CTSN_HEATINGCOOLINGAUTOSWITCH = "core:HeatingCoolingAutoSwitchState"; // on / off

	const CTSN_PASSAPCDHWCONFIGURATION = "io:PassAPCDHWConfigurationState";
	const CTSN_PASSAPCDHWMODE= "io:PassAPCDHWModeState";
	const CTSN_PASSAPCDHWPROFILE= "io:PassAPCDHWProfileState";
	const CTSN_COMFORTTARGETDHWTEMPERATURE= "core:ComfortTargetDHWTemperatureState";
	const CTSN_ECOTARGETDHWTEMPERATURE= "core:EcoTargetDHWTemperatureState";
	const CTSN_TARGETDHWTEMPERATURE= "core:TargetDHWTemperatureState";
	const CTSN_BOOSTONOFF= "core:BoostOnOffState";
	const CTSN_DHWONOFF= "core:DHWOnOffState";

	const CTSN_THERMALCONFIGURATION= "core:ThermalConfigurationState";// "values": ["cooling","heating","heatingAndCooling"	]
	const CTSN_PASSAPCHEATINGMODE= "io:PassAPCHeatingModeState";//"values": ["absence","auto","comfort","eco","externalScheduling","internalScheduling","manu","stop"],
	const CTSN_PASSAPCCOOLINGMODE="io:PassAPCCoolingModeState"; //"values": ["absence","auto","comfort","eco","externalScheduling","internalScheduling","manu","stop"],
	const CTSN_PASSAPCHEATINGPROFILE="io:PassAPCHeatingProfileState";// "values": ["absence","comfort","derogation","eco","externalSetpoint","frostprotection","manu","stop"]
	const CTSN_PASSAPCCOOLINGPROFILE="io:PassAPCCoolingProfileState";// "values": ["absence","comfort","derogation","eco","externalSetpoint","frostprotection","manu","stop"]

	const CTSN_COMFORTHEATINGTARGETTEMP="core:ComfortHeatingTargetTemperatureState";
	const CTSN_ECOHEATINGTARGETTEMP="core:EcoHeatingTargetTemperatureState";
	const CTSN_COMFORTCOOLINGTARGETTEMP="core:ComfortCoolingTargetTemperatureState";
	const CTSN_ECOCOOLINGTARGETTEMP="core:EcoCoolingTargetTemperatureState";
	const CTSN_DEROGATIONREMAININGTIME="io:DerogationRemainingTimeState";
	const CTSN_HEATINGONOFF="core:HeatingOnOffState";
	const CTSN_COOLINGONOFF="core:CoolingOnOffState";
	const CTSN_DEROGATIONONOFF="core:DerogationOnOffState";
	const CTSN_HEATINGTARGETTEMP="core:HeatingTargetTemperatureState";
	const CTSN_COOLINGTARGETTEMP="core:CoolingTargetTemperatureState";
	const CTSN_MINHEATTARGETTEMP="core:MinimumHeatingTargetTemperatureState";
	const CTSN_MAXHEATTARGETTEMP="core:MaximumHeatingTargetTemperatureState";
	const CTSN_MINCOOLTARGETTEMP="core:MinimumCoolingTargetTemperatureState";
	const CTSN_MAXCOOLTARGETTEMP="core:MaximumCoolingTargetTemperatureState";
	
	const CTSN_FOSSILENERGYCONSUMPTION="core:FossilEnergyConsumptionState";
	const CTSN_HEATINGCOMFORTMODEAVAILABILITY="io:HeatingComfortModeAvailabilityState";
	const CTSN_ACIVEHETAINGPROGRAM="core:ActiveHeatingTimeProgramState";

	const EQ_VMCMODE ="vmcMode";
	const EQ_VMCTEMPINSUFFLE="vmcTempInsuffle";
	const EQ_VMCTEMPEXT="vmcTempExt";
	const EQ_HOTWATERCOEFF = "hotWaterCoefficient";
	const EQ_ZONECTRLMODE ="zoneMode";
	
	const EQ_ISHOTWATERHEATING ="isHotWaterHeating";

	const CTSN_TYPE = [
		self::CTSN_NAME=>"string",
		self::CTSN_OPEMODE=>"string",
		self::CTSN_TARGETHEATLEVEL=>"string",
		self::CTSN_DHWMODE=>"string",
		self::CTSN_VENTILATIONCONFIG=>"string",
		self::CTSN_VENTILATIONMODE=>"string",
		self::CTSN_AIRDEMANDEMODE=>"string",

		self::CTSN_HEATINGSTATUS=>"string",
		self::CTSN_DHWBOOSTMODE=>"string", // on / off / prog
		self::CTSN_DHWABSENCEMODE=>"string",
		self::CTSN_OPEMODECAPABILITIES=>"string",

		self::CTSN_PASSAPCDHWPROFILE=>"string",
		self::CTSN_PASSAPCDHWMODE=>"string",
		self::CTSN_PASSAPCDHWCONFIGURATION=>"string",
		self::CTSN_THERMALCONFIGURATION=>"string",
		self::CTSN_PASSAPCHEATINGMODE=>"string",
		self::CTSN_PASSAPCCOOLINGMODE=>"string",
		self::CTSN_PASSAPCHEATINGPROFILE=>"string",
		self::CTSN_PASSAPCCOOLINGPROFILE=>"string",

		self::CTSN_LIGHTSTATE=>"string",
		self::CTSN_TOWELDRYERTEMPORARY => "string",

		self::EQ_VMCMODE=>"string",
		self::EQ_ZONECTRLMODE=>"string",

		self::CTSN_TARGETTEMP=>"numeric",
		self::CTSN_COMFROOMTEMP=>"numeric",
		self::CTSN_ECOROOMTEMP=>"numeric",
		self::CTSN_DEROGTARGETTEMP=>"numeric",
		self::CTSN_EFFTEMPSETPOINT=>"numeric",
		self::CTSN_TEMPPROBECALIBR=>"numeric",
		self::CTSN_BOOSTMODEDURATION=>"numeric",
		
		self::CTSN_BOOSTDURATIONUSERPARAM =>"numeric",
		self::CTSN_BOOSTDURATIONMAX =>"numeric",
		self::CTSN_DRYINGDURATION =>"numeric",
		self::CTSN_DRYINGDURATIONUSERPARAM =>"numeric",
		self::CTSN_DRYINGDURATIONMAX =>"numeric",
		self::CTSN_LUMINANCE =>"numeric",
		self::CTSN_RELATIVEHUMIDITY =>"numeric",

		self::CTSN_TEMP=>"numeric",
		self::CTSN_WATERCONSUMPTION=>"numeric",
		self::CTSN_AWAYMODEDURATION=>"numeric",
		self::CTSN_DHWCAPACITY=>"numeric",
		self::CTSN_ELECNRJCONSUMPTION=>"numeric",
		self::EQ_HOTWATERCOEFF => "numeric",
		self::CTSN_AIRDEMANDE => "numeric",
		self::CTSN_CO2CONCENTRATION => "numeric",
		self::CTSN_MIDDLETEMP=>"numeric",

		self::CTSN_NUMBERTANK=>"numeric",
		self::CTSN_V40WATERVOLUME=>"numeric",
		self::CTSN_WATERTEMP=>"numeric",
		self::CTSN_WATERTARGETTEMP=>"numeric",
		self::CTSN_EXPECTEDNBSHOWER=>"numeric",
		self::CTSN_CTRLWATERTARGETTEMP=>"numeric",
		self::CTSN_MIDDLEWATERTEMPIN=>"numeric",
		self::CTSN_NBSHOWERREMAINING=>"numeric",
		self::CTSN_MINISHOWERMANUAL=>"numeric",
		self::CTSN_MAXISHOWERMANUAL=>"numeric",

		self::CTSN_ECOTARGETDHWTEMPERATURE=>"numeric",
		self::CTSN_COMFORTTARGETDHWTEMPERATURE=>"numeric",
		self::CTSN_TARGETDHWTEMPERATURE=>"numeric",
		self::CTSN_COMFORTHEATINGTARGETTEMP=>"numeric",
		self::CTSN_ECOHEATINGTARGETTEMP=>"numeric",
		self::CTSN_COMFORTCOOLINGTARGETTEMP=>"numeric",
		self::CTSN_ECOCOOLINGTARGETTEMP=>"numeric",
		self::CTSN_DEROGATIONREMAININGTIME=>"numeric",

		self::EQ_VMCTEMPINSUFFLE => "numeric",
		self::EQ_VMCTEMPEXT=>"numeric",

		self::CTSN_OCCUPANCY=>"binary",
		self::CTSN_ONOFF=>"binary",
		self::CTSN_CONNECT=>"binary",

		self::CTSN_BOOSTONOFF=>"binary",
		self::CTSN_DHWONOFF=>"binary",
		self::CTSN_HEATINGONOFF=>"binary",
		self::CTSN_COOLINGONOFF=>"binary",
		self::CTSN_DEROGATIONONOFF=>"binary",
		self::EQ_ISHOTWATERHEATING=>"binary",

		
		self::CTSN_HEATINGTARGETTEMP=>"numeric",
		self::CTSN_COOLINGTARGETTEMP=>"numeric",
		self::CTSN_MINHEATTARGETTEMP=>"numeric",
		self::CTSN_MAXHEATTARGETTEMP=>"numeric",
		self::CTSN_MINCOOLTARGETTEMP=>"numeric",
		self::CTSN_MAXCOOLTARGETTEMP=>"numeric",

		self::CTSN_PASSAPCPRODUCTTYPE =>"string",
		self::CTSN_ZONESNUMBER =>"numeric",
		self::CTSN_PASSAPCOPERATINGMODE =>"string",
		self::CTSN_THERMALSCHEDULINGMODE =>"string",
		self::CTSN_ABSENCESCHEDULINGMODE =>"string",
		self::CTSN_ABSENCEHEATINGTARGETTEMP =>"numeric",
		self::CTSN_ABSENCECOOLINGTARGETTEMP =>"numeric",
		self::CTSN_ABSENCEENDDATETIME =>"string",
		self::CTSN_LASTPASSAPCOPERATINGMODE =>"string",
		self::CTSN_HEATINGCOOLINGAUTOSWITCH =>"string",
		
		self::CTSN_LIGHTSTATE=>"string",
		self::CTSN_LIGHTINTENSITY =>"numeric",
		self::CTSN_AUTOTURNOFF =>"numeric",
		self::CTSN_REMAININGTIME=>"numeric",
		self::CTSN_OCCUPANCYACTIVATION=>"binary",
		self::CTSN_NIGHTOCCUPANCYACTIVATION=>"binary"
	];

	const CTSN_LABEL = [
		self::CTSN_NAME=>"Label",
		self::CTSN_OPEMODE=>"Programation",
		self::CTSN_ONOFF=>"On/Off",
		self::CTSN_TARGETHEATLEVEL=>"Mode",
		self::CTSN_DHWMODE=>"Mode",
		self::CTSN_TOWELDRYERTEMPORARY => "io:TowelDryerTemporaryStateState",

		self::CTSN_TARGETTEMP=>"Temp. Cible",
		self::CTSN_COMFROOMTEMP=>"Temp. Comfort",
		self::CTSN_ECOROOMTEMP=>"Temp. Eco",
		self::CTSN_DEROGTARGETTEMP=>"Temp. Derogation",
		self::CTSN_EFFTEMPSETPOINT=>"Effective",
		self::CTSN_TEMPPROBECALIBR=>"Calibrage",
		self::CTSN_BOOSTMODEDURATION=>"Boost Durée",

		self::CTSN_BOOSTDURATIONUSERPARAM =>"Durée boost param",
		self::CTSN_BOOSTDURATIONMAX =>"Durée boost max",
		self::CTSN_DRYINGDURATION =>"Séchage Durée",
		self::CTSN_DRYINGDURATIONUSERPARAM =>"Durée séchage param",
		self::CTSN_DRYINGDURATIONMAX =>"Durée séchage max",
		self::CTSN_LUMINANCE =>"Luminance",
		self::CTSN_RELATIVEHUMIDITY =>"Humidité",

		self::CTSN_TEMP=>"Température",
		self::CTSN_MIDDLETEMP=>"Température",
		self::CTSN_WATERCONSUMPTION=>"Eau chaude",
		self::CTSN_AWAYMODEDURATION=>"Absent Durée",
		self::CTSN_DHWCAPACITY=>"Capacité Eau",
		self::CTSN_ELECNRJCONSUMPTION=>"Conso Elec",
		self::CTSN_OCCUPANCY=>"Présence",
		self::CTSN_CONNECT=>"Connect",

		self::CTSN_HEATINGSTATUS=>"Heat status",
		self::CTSN_DHWBOOSTMODE=>"Boost Mode",
		self::CTSN_DHWABSENCEMODE=>"Absence Mode",
		self::CTSN_NUMBERTANK=>"Nb Tank",
		self::CTSN_V40WATERVOLUME=>"Volume Eau à 40",
		self::CTSN_WATERTEMP=>"Temp. eau 1",
		self::CTSN_WATERTARGETTEMP=>"Temp. eau cible",
		self::CTSN_EXPECTEDNBSHOWER=>"Douches souhaitées",
		self::CTSN_CTRLWATERTARGETTEMP=>"Temp. ctrl temp. eau cible",
		self::CTSN_MIDDLEWATERTEMPIN=>"Temp. eau 2",
		self::CTSN_NBSHOWERREMAINING=>"Douches restantes",
		self::CTSN_MINISHOWERMANUAL=>"Mini douche",
		self::CTSN_MAXISHOWERMANUAL=>"Maxi douche",
		self::CTSN_OPEMODECAPABILITIES=>"Operation mode",

		self::CTSN_VENTILATIONCONFIG=>"Configuration",
		self::CTSN_VENTILATIONMODE=>"Ventilation mode",
		self::CTSN_AIRDEMANDEMODE=>"Air mode",
		self::CTSN_AIRDEMANDE => "Air demande",
		self::CTSN_CO2CONCENTRATION => "C02",
		
		self::CTSN_PASSAPCDHWMODE=>"Mode",
		self::CTSN_PASSAPCDHWPROFILE=>"Profil",
		self::CTSN_PASSAPCDHWCONFIGURATION=>"Config",
		self::CTSN_ECOTARGETDHWTEMPERATURE=>"Temp. eco",
		self::CTSN_COMFORTTARGETDHWTEMPERATURE=>"Temp. confort",
		self::CTSN_TARGETDHWTEMPERATURE=>"Temp. cible",
		self::CTSN_BOOSTONOFF=>"Boost State",
		self::CTSN_DHWONOFF=>"On/Off State",
		self::CTSN_HEATINGONOFF=>"Heating",
		self::CTSN_COOLINGONOFF=>"Cooling",
		self::CTSN_DEROGATIONONOFF=>"Derogation",
		self::CTSN_COMFORTHEATINGTARGETTEMP=>"Temp. confort heat",
		self::CTSN_ECOHEATINGTARGETTEMP=>"Temp. eco heat",
		self::CTSN_COMFORTCOOLINGTARGETTEMP=>"Temp confort cool",
		self::CTSN_ECOCOOLINGTARGETTEMP=>"Temp eco cool",
		self::CTSN_DEROGATIONREMAININGTIME=>"Derogation durée",
		self::CTSN_THERMALCONFIGURATION=>"Profil",
		self::CTSN_PASSAPCHEATINGMODE=>"Mode heat",
		self::CTSN_PASSAPCCOOLINGMODE=>"Mode cool",
		self::CTSN_PASSAPCHEATINGPROFILE=>"Profile heat",
		self::CTSN_PASSAPCCOOLINGPROFILE=>"Profile cool",

		self::CTSN_HEATINGTARGETTEMP=>"Temp Chauffage",
		self::CTSN_COOLINGTARGETTEMP=>"Temp climatisation",
		self::CTSN_MINHEATTARGETTEMP=>"Min Temp Chauffage",
		self::CTSN_MAXHEATTARGETTEMP=>"Max Temp Chauffage",
		self::CTSN_MINCOOLTARGETTEMP=>"Min Temp climatisation",
		self::CTSN_MAXCOOLTARGETTEMP=>"Max Temp climatisation",

		self::CTSN_PASSAPCPRODUCTTYPE =>"APC type produit",
		self::CTSN_ZONESNUMBER =>"Nb zone",
		self::CTSN_PASSAPCOPERATINGMODE =>"Ope Mode",
		self::CTSN_THERMALSCHEDULINGMODE =>"Thermal schedule mode",
		self::CTSN_ABSENCESCHEDULINGMODE =>"Abs schedule mode",
		self::CTSN_ABSENCEHEATINGTARGETTEMP =>"Abs temp chauffage",
		self::CTSN_ABSENCECOOLINGTARGETTEMP =>"Abs temp climatisation",
		self::CTSN_ABSENCEENDDATETIME =>"Abs date",
		self::CTSN_LASTPASSAPCOPERATINGMODE =>"Last ope mode",
		self::CTSN_HEATINGCOOLINGAUTOSWITCH =>"Switch heat cool",



		self::EQ_VMCMODE => "VMC Mode",
		self::EQ_HOTWATERCOEFF => "Proportion eau chaude",
		
		self::EQ_VMCTEMPINSUFFLE => "Temp insufflé",
		self::EQ_VMCTEMPEXT=>"Temp extérieur",
		self::EQ_ZONECTRLMODE=>"Mode",
		self::EQ_ISHOTWATERHEATING=>"Chauffage en cours",
		
		self::CTSN_LIGHTSTATE=>"Lumière",
		self::CTSN_LIGHTINTENSITY =>"Intensité",
		self::CTSN_AUTOTURNOFF =>"Timer Extinction",
		self::CTSN_REMAININGTIME=>"Remaining",
		self::CTSN_OCCUPANCYACTIVATION=>"Automatique",
		self::CTSN_NIGHTOCCUPANCYACTIVATION=>"Automatique Nuit"
	];
}

class CozyTouchDeviceStateName
{
	const DEVICE_STATENAME = [
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_ONOFF,
			CozyTouchStateName::CTSN_TARGETHEATLEVEL],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERAJUSTTEMP=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_OPEMODE,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_ONOFF,
			CozyTouchStateName::CTSN_TARGETHEATLEVEL,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_COMFROOMTEMP,
			CozyTouchStateName::CTSN_ECOROOMTEMP,
			CozyTouchStateName::CTSN_DEROGTARGETTEMP,
			CozyTouchStateName::CTSN_EFFTEMPSETPOINT,
			CozyTouchStateName::CTSN_TEMPPROBECALIBR],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICDIMMABLELIGHT=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_ONOFF,
			CozyTouchStateName::CTSN_LIGHTSTATE,
			CozyTouchStateName::CTSN_LIGHTINTENSITY,
			CozyTouchStateName::CTSN_AUTOTURNOFF,
			CozyTouchStateName::CTSN_REMAININGTIME,
			CozyTouchStateName::CTSN_OCCUPANCYACTIVATION,
			CozyTouchStateName::CTSN_NIGHTOCCUPANCYACTIVATION],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICTOWELDRYER=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_OPEMODE,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_ONOFF,
			CozyTouchStateName::CTSN_TARGETHEATLEVEL,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_ECOROOMTEMP,
			CozyTouchStateName::CTSN_DEROGTARGETTEMP,
			CozyTouchStateName::CTSN_EFFTEMPSETPOINT,
			CozyTouchStateName::CTSN_TEMPPROBECALIBR,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_BOOSTDURATIONUSERPARAM,
			CozyTouchStateName::CTSN_BOOSTDURATIONMAX,
			CozyTouchStateName::CTSN_DRYINGDURATION,
			CozyTouchStateName::CTSN_DRYINGDURATIONUSERPARAM,
			CozyTouchStateName::CTSN_DRYINGDURATIONMAX,
			CozyTouchStateName::CTSN_TOWELDRYERTEMPORARY],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_TEMP,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_WATERCONSUMPTION,
			CozyTouchStateName::CTSN_AWAYMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_OPEMODECAPABILITIES],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV3=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_TEMP,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_OPEMODE,
			CozyTouchStateName::CTSN_OPEMODECAPABILITIES],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV2AEX=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_MIDDLETEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_OPEMODE,
			CozyTouchStateName::CTSN_OPEMODECAPABILITIES],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERSPLIT=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_MIDDLETEMP,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_WATERCONSUMPTION,
			CozyTouchStateName::CTSN_AWAYMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCETHIV4=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_MIDDLETEMP,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_V40WATERVOLUME,
			CozyTouchStateName::CTSN_AWAYMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERFLATC2=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_WATERCONSUMPTION,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_DHWBOOSTMODE,
			CozyTouchStateName::CTSN_DHWABSENCEMODE,
			CozyTouchStateName::CTSN_MIDDLETEMP,
			CozyTouchStateName::CTSN_V40WATERVOLUME,
			CozyTouchStateName::CTSN_WATERTEMP,
			CozyTouchStateName::CTSN_WATERTARGETTEMP,
			CozyTouchStateName::CTSN_EXPECTEDNBSHOWER,
			CozyTouchStateName::CTSN_CTRLWATERTARGETTEMP,
			CozyTouchStateName::CTSN_MIDDLEWATERTEMPIN,
			CozyTouchStateName::CTSN_HEATINGSTATUS,
			CozyTouchStateName::CTSN_NBSHOWERREMAINING,
			CozyTouchStateName::CTSN_MINISHOWERMANUAL,
			CozyTouchStateName::CTSN_MAXISHOWERMANUAL
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCES4=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_WATERCONSUMPTION,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_DHWBOOSTMODE,
			CozyTouchStateName::CTSN_DHWABSENCEMODE,
			CozyTouchStateName::CTSN_MIDDLETEMP,
			CozyTouchStateName::CTSN_V40WATERVOLUME,
			CozyTouchStateName::CTSN_WATERTEMP,
			CozyTouchStateName::CTSN_WATERTARGETTEMP,
			CozyTouchStateName::CTSN_EXPECTEDNBSHOWER,
			CozyTouchStateName::CTSN_CTRLWATERTARGETTEMP,
			CozyTouchStateName::CTSN_MIDDLEWATERTEMPIN,
			CozyTouchStateName::CTSN_HEATINGSTATUS,
			CozyTouchStateName::CTSN_NBSHOWERREMAINING,
			CozyTouchStateName::CTSN_MINISHOWERMANUAL,
			CozyTouchStateName::CTSN_MAXISHOWERMANUAL
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHEATRECOVERYVENT=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_AIRDEMANDEMODE,
			CozyTouchStateName::CTSN_AIRDEMANDE,
			CozyTouchStateName::CTSN_VENTILATIONMODE,
			CozyTouchStateName::CTSN_VENTILATIONCONFIG],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCHEATPUMPMAIN=>[
			CozyTouchStateName::CTSN_CONNECT],
				
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCDHW=>[
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_DHWONOFF,
			CozyTouchStateName::CTSN_PASSAPCDHWMODE,
			CozyTouchStateName::CTSN_PASSAPCDHWPROFILE,
			CozyTouchStateName::CTSN_PASSAPCDHWCONFIGURATION,
			CozyTouchStateName::CTSN_TARGETDHWTEMPERATURE,
			CozyTouchStateName::CTSN_ECOTARGETDHWTEMPERATURE,
			CozyTouchStateName::CTSN_COMFORTTARGETDHWTEMPERATURE,
			CozyTouchStateName::CTSN_BOOSTONOFF],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCHEATINGCOOLINGZONE=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_THERMALCONFIGURATION,
			CozyTouchStateName::CTSN_PASSAPCHEATINGMODE,
			CozyTouchStateName::CTSN_PASSAPCCOOLINGMODE,
			CozyTouchStateName::CTSN_PASSAPCHEATINGPROFILE,
			CozyTouchStateName::CTSN_PASSAPCCOOLINGPROFILE,
			CozyTouchStateName::CTSN_COMFORTHEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_ECOHEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_COMFORTCOOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_ECOCOOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DEROGTARGETTEMP,
			CozyTouchStateName::CTSN_DEROGATIONREMAININGTIME,
			CozyTouchStateName::CTSN_DEROGATIONONOFF,
			CozyTouchStateName::CTSN_HEATINGONOFF,
			CozyTouchStateName::CTSN_COOLINGONOFF,
			CozyTouchStateName::CTSN_CONNECT],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLMAIN=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_PASSAPCPRODUCTTYPE,
			CozyTouchStateName::CTSN_ZONESNUMBER,
			CozyTouchStateName::CTSN_PASSAPCOPERATINGMODE,
			CozyTouchStateName::CTSN_THERMALSCHEDULINGMODE,
			CozyTouchStateName::CTSN_ABSENCESCHEDULINGMODE,
			CozyTouchStateName::CTSN_ABSENCEHEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_ABSENCECOOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_ABSENCEENDDATETIME,
			CozyTouchStateName::CTSN_HEATINGCOOLINGAUTOSWITCH,
			CozyTouchStateName::CTSN_LASTPASSAPCOPERATINGMODE,
			CozyTouchStateName::CTSN_CONNECT
		],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLZONE=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_THERMALCONFIGURATION,
			CozyTouchStateName::CTSN_PASSAPCHEATINGMODE,
			CozyTouchStateName::CTSN_PASSAPCCOOLINGMODE,
			CozyTouchStateName::CTSN_PASSAPCHEATINGPROFILE,
			CozyTouchStateName::CTSN_PASSAPCCOOLINGPROFILE,
			CozyTouchStateName::CTSN_COMFORTHEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_ECOHEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_COMFORTCOOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_ECOCOOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_HEATINGONOFF,
			CozyTouchStateName::CTSN_COOLINGONOFF,
			CozyTouchStateName::CTSN_HEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_COOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_MINHEATTARGETTEMP,
			CozyTouchStateName::CTSN_MAXHEATTARGETTEMP,
			CozyTouchStateName::CTSN_MINCOOLTARGETTEMP,
			CozyTouchStateName::CTSN_MAXCOOLTARGETTEMP,
			CozyTouchStateName::CTSN_DEROGATIONONOFF,
			CozyTouchStateName::CTSN_CONNECT],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR=>[
			CozyTouchStateName::CTSN_TEMP],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONETEMPERATURESENSOR=>[
			CozyTouchStateName::CTSN_TEMP],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCOUTSIDETEMPERATURESENSOR=>[
			CozyTouchStateName::CTSN_TEMP],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATEROCCUPANCYSENSOR=>[
			CozyTouchStateName::CTSN_OCCUPANCY],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICLIGHT=>[
			CozyTouchStateName::CTSN_LUMINANCE],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICRELATIVEHUMIDITY=>[
			CozyTouchStateName::CTSN_RELATIVEHUMIDITY],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERELECTRICITYSENSOR=>[
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION],
		CozyTouchDeviceToDisplay::CTDTD_TOTALENERGYCONSUMPTIONSENSOR=>[
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION],
		CozyTouchDeviceToDisplay::CTDTD_DHWENERGYCONSUMPTIONSENSOR=>[
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION],
		CozyTouchDeviceToDisplay::CTDTD_HEATINGENERGYCONSUMPTIONSENSOR=>[
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERELECTRICITYSENSOR=>[
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICC02SENSOR=>[
			CozyTouchStateName::CTSN_CO2CONCENTRATION],
	];

	const EQLOGIC_STATENAME = [
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_ONOFF,
			CozyTouchStateName::CTSN_TARGETHEATLEVEL],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERAJUSTTEMP=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
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
		
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICTOWELDRYER=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_OPEMODE,
			CozyTouchStateName::CTSN_ONOFF,
			CozyTouchStateName::CTSN_TARGETHEATLEVEL,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_ECOROOMTEMP,
			CozyTouchStateName::CTSN_DEROGTARGETTEMP,
			CozyTouchStateName::CTSN_EFFTEMPSETPOINT,
			CozyTouchStateName::CTSN_TEMPPROBECALIBR,
			CozyTouchStateName::CTSN_TEMP,
			CozyTouchStateName::CTSN_OCCUPANCY,
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION,
			CozyTouchStateName::CTSN_LUMINANCE,
			CozyTouchStateName::CTSN_RELATIVEHUMIDITY,
		
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_BOOSTDURATIONUSERPARAM,
			CozyTouchStateName::CTSN_BOOSTDURATIONMAX,
			CozyTouchStateName::CTSN_DRYINGDURATION,
			CozyTouchStateName::CTSN_DRYINGDURATIONUSERPARAM,
			CozyTouchStateName::CTSN_DRYINGDURATIONMAX,
			CozyTouchStateName::CTSN_TOWELDRYERTEMPORARY],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICDIMMABLELIGHT=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_ONOFF,
			CozyTouchStateName::CTSN_LIGHTSTATE,
			CozyTouchStateName::CTSN_LIGHTINTENSITY,
			CozyTouchStateName::CTSN_AUTOTURNOFF,
			CozyTouchStateName::CTSN_REMAININGTIME,
			CozyTouchStateName::CTSN_OCCUPANCYACTIVATION,
			CozyTouchStateName::CTSN_NIGHTOCCUPANCYACTIVATION],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_TEMP,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_WATERCONSUMPTION,
			CozyTouchStateName::CTSN_AWAYMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION,
			CozyTouchStateName::CTSN_OPEMODECAPABILITIES,
			CozyTouchStateName::EQ_ISHOTWATERHEATING],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV3=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_TEMP,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION,
			CozyTouchStateName::CTSN_OPEMODE,
			CozyTouchStateName::CTSN_OPEMODECAPABILITIES,
			CozyTouchStateName::EQ_ISHOTWATERHEATING],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV2AEX=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_MIDDLETEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION,
			CozyTouchStateName::CTSN_OPEMODE,
			CozyTouchStateName::CTSN_OPEMODECAPABILITIES,
			CozyTouchStateName::EQ_ISHOTWATERHEATING],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERSPLIT=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_MIDDLETEMP,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_WATERCONSUMPTION,
			CozyTouchStateName::CTSN_AWAYMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCETHIV4=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_MIDDLETEMP,
			CozyTouchStateName::CTSN_V40WATERVOLUME,
			CozyTouchStateName::CTSN_BOOSTMODEDURATION,
			CozyTouchStateName::CTSN_AWAYMODEDURATION,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERFLATC2=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_WATERCONSUMPTION,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_DHWBOOSTMODE,
			CozyTouchStateName::CTSN_DHWABSENCEMODE,
			CozyTouchStateName::CTSN_MIDDLETEMP,
			CozyTouchStateName::CTSN_V40WATERVOLUME,
			CozyTouchStateName::CTSN_WATERTEMP,
			CozyTouchStateName::CTSN_WATERTARGETTEMP,
			CozyTouchStateName::CTSN_EXPECTEDNBSHOWER,
			CozyTouchStateName::CTSN_CTRLWATERTARGETTEMP,
			CozyTouchStateName::CTSN_MIDDLEWATERTEMPIN,
			CozyTouchStateName::CTSN_HEATINGSTATUS,
			CozyTouchStateName::CTSN_NBSHOWERREMAINING,
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION,
			CozyTouchStateName::CTSN_MINISHOWERMANUAL,
			CozyTouchStateName::CTSN_MAXISHOWERMANUAL],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCES4=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_DHWMODE,
			CozyTouchStateName::CTSN_WATERCONSUMPTION,
			CozyTouchStateName::CTSN_DHWCAPACITY,
			CozyTouchStateName::CTSN_DHWBOOSTMODE,
			CozyTouchStateName::CTSN_DHWABSENCEMODE,
			CozyTouchStateName::CTSN_MIDDLETEMP,
			CozyTouchStateName::CTSN_V40WATERVOLUME,
			CozyTouchStateName::CTSN_WATERTEMP,
			CozyTouchStateName::CTSN_WATERTARGETTEMP,
			CozyTouchStateName::CTSN_EXPECTEDNBSHOWER,
			CozyTouchStateName::CTSN_CTRLWATERTARGETTEMP,
			CozyTouchStateName::CTSN_MIDDLEWATERTEMPIN,
			CozyTouchStateName::CTSN_HEATINGSTATUS,
			CozyTouchStateName::CTSN_NBSHOWERREMAINING,
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION,
			CozyTouchStateName::CTSN_MINISHOWERMANUAL,
			CozyTouchStateName::CTSN_MAXISHOWERMANUAL],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHEATRECOVERYVENT=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_TEMP,
			CozyTouchStateName::EQ_VMCTEMPINSUFFLE,
			CozyTouchStateName::EQ_VMCTEMPEXT,

			CozyTouchStateName::CTSN_AIRDEMANDEMODE, // "auto","away","boost","high"
			CozyTouchStateName::CTSN_AIRDEMANDE,
			CozyTouchStateName::CTSN_VENTILATIONMODE,
			CozyTouchStateName::CTSN_VENTILATIONCONFIG, //"comfort","eco","standard"
			CozyTouchStateName::CTSN_CO2CONCENTRATION],
		
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCHEATPUMPMAIN=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_ELECNRJCONSUMPTION],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCDHW=>[
			CozyTouchStateName::CTSN_CONNECT,
			CozyTouchStateName::CTSN_DHWONOFF,
			CozyTouchStateName::CTSN_PASSAPCDHWMODE,
			CozyTouchStateName::CTSN_PASSAPCDHWPROFILE,
			CozyTouchStateName::CTSN_PASSAPCDHWCONFIGURATION,
			CozyTouchStateName::CTSN_TARGETDHWTEMPERATURE,
			CozyTouchStateName::CTSN_ECOTARGETDHWTEMPERATURE,
			CozyTouchStateName::CTSN_COMFORTTARGETDHWTEMPERATURE,
			CozyTouchStateName::CTSN_BOOSTONOFF],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCHEATINGCOOLINGZONE=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_THERMALCONFIGURATION,
			CozyTouchStateName::CTSN_PASSAPCHEATINGMODE,
			CozyTouchStateName::CTSN_PASSAPCCOOLINGMODE,
			CozyTouchStateName::CTSN_PASSAPCHEATINGPROFILE,
			CozyTouchStateName::CTSN_PASSAPCCOOLINGPROFILE,
			CozyTouchStateName::CTSN_COMFORTHEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_ECOHEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_COMFORTCOOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_ECOCOOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_DEROGTARGETTEMP,
			CozyTouchStateName::CTSN_DEROGATIONREMAININGTIME,
			CozyTouchStateName::CTSN_DEROGATIONONOFF,
			CozyTouchStateName::CTSN_HEATINGONOFF,
			CozyTouchStateName::CTSN_COOLINGONOFF,
			CozyTouchStateName::CTSN_TEMP,
			CozyTouchStateName::CTSN_CONNECT],
		
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLMAIN=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_PASSAPCPRODUCTTYPE,
			CozyTouchStateName::CTSN_ZONESNUMBER,
			CozyTouchStateName::CTSN_PASSAPCOPERATINGMODE,
			CozyTouchStateName::CTSN_THERMALSCHEDULINGMODE,
			CozyTouchStateName::CTSN_ABSENCESCHEDULINGMODE,
			CozyTouchStateName::CTSN_ABSENCEHEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_ABSENCECOOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_ABSENCEENDDATETIME,
			CozyTouchStateName::CTSN_HEATINGCOOLINGAUTOSWITCH,
			CozyTouchStateName::CTSN_LASTPASSAPCOPERATINGMODE,
			CozyTouchStateName::EQ_ZONECTRLMODE,
			CozyTouchStateName::CTSN_CONNECT
		],

		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLZONE=>[
			CozyTouchStateName::CTSN_NAME,
			CozyTouchStateName::CTSN_THERMALCONFIGURATION,
			CozyTouchStateName::CTSN_PASSAPCHEATINGMODE,
			CozyTouchStateName::CTSN_PASSAPCCOOLINGMODE,
			CozyTouchStateName::CTSN_PASSAPCHEATINGPROFILE,
			CozyTouchStateName::CTSN_PASSAPCCOOLINGPROFILE,
			CozyTouchStateName::CTSN_COMFORTHEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_ECOHEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_COMFORTCOOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_ECOCOOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_TEMP,
			CozyTouchStateName::CTSN_TARGETTEMP,
			CozyTouchStateName::CTSN_HEATINGONOFF,
			CozyTouchStateName::CTSN_COOLINGONOFF,
			CozyTouchStateName::CTSN_HEATINGTARGETTEMP,
			CozyTouchStateName::CTSN_COOLINGTARGETTEMP,
			CozyTouchStateName::CTSN_MINHEATTARGETTEMP,
			CozyTouchStateName::CTSN_MAXHEATTARGETTEMP,
			CozyTouchStateName::CTSN_MINCOOLTARGETTEMP,
			CozyTouchStateName::CTSN_MAXCOOLTARGETTEMP,
			CozyTouchStateName::CTSN_DEROGATIONONOFF,
			CozyTouchStateName::EQ_ZONECTRLMODE,
			CozyTouchStateName::CTSN_CONNECT]
	];
}


class CozyTouchDeviceSensorInfo
{
	const CTDSI_OID = "oid";
    const CTDSI_URL = "deviceURL";
    const CTDSI_STATES = "states";
}

class CozyTouchDeviceEqCmds
{
	const SET_STANDBY = "setStandByMode";
	const SET_BASIC = "setBasicMode";
	const SET_EXTERNAL = "setExternalMode";
	const SET_INTERNAL = "setInternalMode";
	const SET_AUTO = "setAutoMode";
	const SET_TARGETTEMP ="setTargetTemperature";
	const SET_THERMOSTAT ='cozytouchThermostat';

	const RESET_HEATINGLEVEL='resetHeatingLevel';

	const SET_OFF="setOff";
	const SET_FROSTPROTECT="setFrostProtection";
	const SET_ECO="setEco";
	const SET_COMFORT2="setComfort2";
	const SET_COMFORT1="setComfort1";
	const SET_COMFORT="setComfort";
	const SET_AUTOMODE="setAutoMode";
	const SET_MANUECOACTIVE="setManualEcoActive";
	const SET_MANUECOINACTIVE="setManualEcoInactive";
	const SET_BOOSTDURATION="setBoostModeDuration";
	const SET_BOOST='setBoost';
	const SET_DRY='setDry';
	const SET_AWAY='setAway';
	const SET_AWAYDURATION='setAwayDuration';
	const SET_EXPECTEDSHOWER='setExpectedNumberOfShower';

	const SET_ONOFF="setOnOff";

	const SET_VENTBOOST ='setVentBoost';
	const SET_VENTHIGH ='setVentHigh';
	const SET_VENTREFRESH ='setVentRefresh';
	const SET_VENTMANUAL ='setVentManual';
	const SET_VENTPROG ='setVentProg';
	const SET_VENTAUTO ='setVentAuto';

	
	const SET_ZONECTRLHEAT ='setHeatMode';
	const SET_ZONECTRLCOOL ='setCoolMode';
	const SET_ZONECTRLDRY ='setDryMode';

	const SET_ZONECTRLZONEOFF ='setZoneOff';
	const SET_ZONECTRLZONEMANU ='setZoneManu';
	const SET_ZONECTRLZONEPROGRAM ='setZoneProgram';

	const EQLOGIC_ACTIONS = [
		
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER=>[
			self::SET_OFF,
			self::SET_FROSTPROTECT,
			self::SET_ECO,
			self::SET_COMFORT2,
			self::SET_COMFORT1,
			self::SET_COMFORT
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICTOWELDRYER=>[
			self::SET_STANDBY,
			self::SET_EXTERNAL,
			self::SET_INTERNAL,
			self::SET_AUTO,
			self::SET_BOOST,
			self::SET_DRY,
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERAJUSTTEMP=>[
			self::SET_STANDBY,
			self::SET_BASIC,
			self::SET_EXTERNAL,
			self::SET_INTERNAL,
			self::SET_AUTO,
			self::RESET_HEATINGLEVEL,
			self::SET_FROSTPROTECT,
			self::SET_ECO,
			self::SET_COMFORT2,
			self::SET_COMFORT1,
			self::SET_COMFORT
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER=>[
			self::SET_AUTOMODE,
			self::SET_MANUECOACTIVE,
			self::SET_MANUECOINACTIVE
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV3=>[
			self::SET_AUTOMODE,
			self::SET_MANUECOACTIVE,
			self::SET_MANUECOINACTIVE
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV2AEX=>[
			self::SET_AUTOMODE,
			self::SET_MANUECOACTIVE,
			self::SET_MANUECOINACTIVE
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERSPLIT=>[
			self::SET_AUTOMODE,
			self::SET_MANUECOACTIVE,
			self::SET_MANUECOINACTIVE
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCETHIV4=>[
			self::SET_AUTOMODE,
			self::SET_MANUECOACTIVE,
			self::SET_MANUECOINACTIVE
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERFLATC2=>[
			self::SET_AUTOMODE,
			self::SET_MANUECOINACTIVE
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCES4=>[
			self::SET_AUTOMODE,
			self::SET_MANUECOINACTIVE
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHEATRECOVERYVENT=>[
			self::SET_VENTMANUAL,
			self::SET_VENTPROG,
			self::SET_VENTAUTO,
			self::SET_VENTBOOST,
			self::SET_VENTHIGH,
			self::SET_VENTREFRESH
			//self::SET_BOOSTDURATION
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLMAIN=>[
			self::SET_OFF,
			self::SET_ZONECTRLHEAT,
			self::SET_ZONECTRLCOOL,
			self::SET_ZONECTRLDRY,
			self::SET_AUTO
		],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLZONE=>[
			self::SET_ZONECTRLZONEOFF,
			self::SET_ZONECTRLZONEMANU,
			self::SET_ZONECTRLZONEPROGRAM
		]
	];
	const ACTION_LABEL = [
		self::SET_STANDBY=>"StandBy",
		self::SET_BASIC=>"Basic",
		self::SET_EXTERNAL=>"Externe",
		self::SET_INTERNAL=>"Interne",
		self::SET_AUTO=>"Auto",
		self::SET_OFF=>"Off",
		self::RESET_HEATINGLEVEL=>"Reset",
		self::SET_FROSTPROTECT=>"Hors-gel",
		self::SET_ECO=>"Eco",
		self::SET_COMFORT2=>"Confort-2",
		self::SET_COMFORT1=>"Confort-1",
		self::SET_COMFORT=>"Confort",
		self::SET_AUTOMODE=>"Auto",
		self::SET_MANUECOACTIVE=>"Manuel Eco",
		self::SET_MANUECOINACTIVE=>"Manuel",
		self::SET_BOOSTDURATION=>"Boost",
		self::SET_BOOST=>"Boost",
		self::SET_DRY=>"Séchage",
		self::SET_ONOFF=>"Off",

		self::SET_VENTBOOST=>"Boost Maison",
		self::SET_VENTHIGH=>"Boost Cuisine",
		self::SET_VENTREFRESH=>"Rafraichissement",
		self::SET_VENTMANUAL=>"Manual",
		self::SET_VENTPROG=>"Prog",
		self::SET_VENTAUTO=>"Auto",

		self::SET_ZONECTRLHEAT=>"Chauffage",
		self::SET_ZONECTRLCOOL=>"Rafraichissement",
		self::SET_ZONECTRLDRY=>"Deshumidification",

		self::SET_ZONECTRLZONEOFF=>"Off",
		self::SET_ZONECTRLZONEMANU=>"Manual",
		self::SET_ZONECTRLZONEPROGRAM=>"Program"
	];
}

class CozyTouchDeviceActions
{
	// Atlantic chauffage electrique
	const CTPC_ON = "on"; 
	const CTPC_OFF = "off"; 
	
	const CTPC_SETMODE = "setOperatingMode"; // parameters : standby / basic / internal / external / auto
	const CTPC_SETTOWELDRYINGMODE = "setTowelDryerOperatingMode"; // parameters : standby /   external / internal / auto / boost / drying
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
	
	// towel
	const CTPC_SETBOOSTDURATION = "setTowelDryerBoostModeDuration";
	const CTPC_SETDRYDURATION = "setDryingDuration";
	const CTPC_SETDRYERTEMPORARYMODE = "setTowelDryerTemporaryState"; // boost, permanentHeating, drying
	const CTPC_RSHBOOSTDURATION ="refreshBoostModeDuration";
	const CTPC_RSHDRYINGDURATION ="refreshDryingDuration";
	// Dimmable Light
	const CTPC_SETONOFFLIGHT = "setOnOffLight"; //parameters : on, off
	const CTPC_RSHREMAININGTIME = "refreshRemainingTime";
	const CTPC_SETAUTOTURNOFFDELAY = "setAutomaticTurnOffDelayConfiguration"; //parameters : int seconde
	const CTPC_SETINTENSITY = "setIntensity"; //parameters : 1, 100 
	const CTPC_SETOCCUPANCYACTIVATION= "setOccupancyActivation"; //parameters : active, inactive 

	// Atlantic ballon d'eau chaude
	const CTPC_RSHWATERCONS = "refreshWaterConsumption";
	const CTPC_RSHAWAYDUR = "refreshAwayModeDuration";
	const CTPC_RSHBOOSTDUR = "refreshBoostModeDuration";
	const CTPC_RSHDHWCAPACITY = "refreshDHWCapacity";
	const CTPC_RSHDHWMODE = "refreshDHWMode";
	const CTPC_SETAWAYDUR = "setAwayModeDuration";
	const CTPC_SETBOOSTDUR = "setBoostModeDuration";
	const CTPC_SETDHWMODE = "setDHWMode";
	const CTPC_SETCURRENTOPEMODE = "setCurrentOperatingMode";
	const CTPC_RSHCURRENTOPEMODE = "refreshCurrentOperatingMode";

	const CTPC_RSHBOOSTSTARTDATE = "refreshBoostStartDate";
	const CTPC_RSHBOOSTENDDATE = "refreshBoostEndDate";
	const CTPC_SETDATETIME = "setDateTime";
	const CTPC_SETBOOSTMODE = "setBoostMode";
	const CTPC_SETEXPECTEDSHOWER='setExpectedNumberOfShower';
	

	// Atlantic VMC
	const CTPC_SETAIRDEMANDMODE = "setAirDemandMode";
	const CTPC_RSHVENTILATION = "refreshVentilationState";
	const CTPC_SETVENTILATIONMODE = "setVentilationMode";

	const CTPC_SETVENTILATIONCONFIGMODE = "setVentilationConfigurationMode"; // standard = manuel, comfort = auto,
	const CTPC_RSHVENTILATIONCONFIGMODE = "refreshVentilationConfigurationMode";

	// Pompe à chaleur
	//      Ballon d'eau chaude
	const CTPC_SETDHWONOFF="setDHWOnOffState";
	const CTPC_SETBOOSTONOFF="setBoostOnOffState";

	//		Heat system
	const CTPC_SETECOHEATINGTARGET="setEcoHeatingTargetTemperature";
	const CTPC_SETCOMFORTHEATINGTARGET="setComfortHeatingTargetTemperature";
	const CTPC_SETECOCOOLINGTARGET="setEcoCoolingTargetTemperature";
	const CTPC_SETCOMFORTCOOLINGTARGET="setComfortCoolingTargetTemperature";
	const CTPC_SETDEROGTIME = "setDerogationTime";
	const CTPC_SETDEROGONOFF = "setDerogationOnOffState";
	const CTPC_RSHDEROGTIME = "refreshDerogationRemainingTime";

	
	const CTPC_SETHEATINGONOFF = "setHeatingOnOffState";
	const CTPC_SETHEATINGTARGETTEMP = "setHeatingTargetTemperature";
	const CTPC_SETAPCHEATINGMODE = "setPassAPCHeatingMode";// manu, internalScheduling
	const CTPC_SETCOOLINGONOFF = "setCoolingOnOffState";
	const CTPC_SETCOOLINGTARGETTEMP = "setCoolingTargetTemperature";
	const CTPC_SETAPCCOOLINGMODE = "setPassAPCCoolingMode";
	const CTPC_RSHAPCHEATINGPROFILE = "refreshPassAPCHeatingProfile";
	const CTPC_SETHEATINGCOOLINGAUTOSWITCH = "setHeatingCoolingAutoSwitch";// on, off
	const CTPC_SETAPCOPERATINGMODE = "setPassAPCOperatingMode";// stop, heating, cooling, drying
	const CTPC_RSHZONESTARGETTEMP = "refreshZonesTargetTemperature";
	const CTPC_RSHZONESAPCCOOLINGPROFILE = "refreshZonesPassAPCCoolingProfile";
	const CTPC_RSHZONESAPCHEATINGPROFILE = "refreshZonesPassAPCHeatingProfile";
}


?>
