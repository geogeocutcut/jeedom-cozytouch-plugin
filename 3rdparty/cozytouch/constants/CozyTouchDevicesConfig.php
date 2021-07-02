<?php
# Code non utilisé pour le moment

require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
# definition->type ACTUATOR vs SENSOR
class CozyTouchDeviceConfig
{
    const Devices = [
        CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER => [
            "label" => "Radiateur",
            "states" => [
                CozyTouchStateName::CTSN_NAME => [
                    "type" => "string",
                    "label" => "Label"
                ],
                CozyTouchStateName::CTSN_CONNECT=> [
                    "type" => "binary",
                    "label" => "Connect"
                ],
                CozyTouchStateName::CTSN_ONOFF=> [
                    "type" => "binary",
                    "label" => "OnOff"
                ],
                CozyTouchStateName::CTSN_TARGETHEATLEVEL=> [
                    "type" => "string",
                    "label" => "Mode"
                ],
            ],
            "sensors" => [
            ],
            "commands" => [
                CozyTouchDeviceEqCmds::SET_OFF => [
                    "label" => "Off"
                ],
                CozyTouchDeviceEqCmds::SET_FROSTPROTECT => [
                    "label" => "Hors gel"
                ],
                CozyTouchDeviceEqCmds::SET_ECO => [
                    "label" => "Eco"
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT2 => [
                    "label" => "Confort -2"
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT1 => [
                    "label" => "Confort -1"
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT => [
                    "label" => "Confort"
                ]
            ],
            "display" => [
                CozyTouchStateName::CTSN_TARGETHEATLEVEL => [
                    "dashbord" => "heatmode",
                    "mobile" => "heatmode",
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchDeviceEqCmds::SET_OFF => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchDeviceEqCmds::SET_FROSTPROTECT => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_ECO => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT2 => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT1 => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ]
            ]
        ],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERAJUSTTEMP => [
            "label" => "Radiateur",
            "states" => [
                CozyTouchStateName::CTSN_NAME => [
                    "type" => "string",
                    "label" => "Label"
                ],
                CozyTouchStateName::CTSN_OPEMODE => [
                    "type" => "string",
                    "label" => "Label"
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "type" => "binary",
                    "label" => "Connect"
                ],
                CozyTouchStateName::CTSN_ONOFF => [
                    "type" => "binary",
                    "label" => "OnOff"
                ],
                CozyTouchStateName::CTSN_TARGETHEATLEVEL => [
                    "type" => "string",
                    "label" => "Mode"
                ],
                CozyTouchStateName::CTSN_TARGETTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. Cible"
                ],
                CozyTouchStateName::CTSN_COMFROOMTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. Confort"
                ],
                CozyTouchStateName::CTSN_ECOROOMTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. Eco"
                ],
                CozyTouchStateName::CTSN_DEROGTARGETTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. Dérogation"
                ],
                CozyTouchStateName::CTSN_EFFTEMPSETPOINT => [
                    "type" => "numeric",
                    "label" => "Effective"
                ],
                CozyTouchStateName::CTSN_TEMPPROBECALIBR => [
                    "type" => "numeric",
                    "label" => "Calibrage"
                ]
            ],
            "sensors" => [
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR+"#1" => [
                    CozyTouchStateName::CTSN_TEMP => [
                        "type" => "numeric",
                        "label" => "Température"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATEROCCUPANCYSENSOR+"#1" => [
                    CozyTouchStateName::CTSN_OCCUPANCY => [
                        "type" => "binary",
                        "label" => "Présence"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERELECTRICITYSENSOR+"#1"=>[
                    CozyTouchStateName::CTSN_ELECNRJCONSUMPTION => [
                        "type" => "numeric",
                        "label" => "Conso Elec"
                    ]
                ]
            ],
            "commands" => [
                CozyTouchDeviceEqCmds::SET_STANDBY => [
                    "label" => "StandBy"
                ],
                CozyTouchDeviceEqCmds::SET_BASIC => [
                    "label" => "Basic"
                ],
                CozyTouchDeviceEqCmds::SET_EXTERNAL => [
                    "label" => "Externe"
                ],
                CozyTouchDeviceEqCmds::SET_INTERNAL => [
                    "label" => "Interne"
                ],
                CozyTouchDeviceEqCmds::SET_AUTO => [
                    "label" => "Auto"
                ],
                CozyTouchDeviceEqCmds::RESET_HEATINGLEVEL => [
                    "label" => "Reset"
                ],
                CozyTouchDeviceEqCmds::SET_FROSTPROTECT => [
                    "label" => "Hors gel"
                ],
                CozyTouchDeviceEqCmds::SET_ECO => [
                    "label" => "Eco"
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT2 => [
                    "label" => "Confort -2"
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT1 => [
                    "label" => "Confort -1"
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT => [
                    "label" => "Confort"
                ],
                CozyTouchDeviceEqCmds::SET_THERMOSTAT => [
                    "label" => "Thermostat",
                    "type" => "thermostat", // => création consigne + thermostat
                    "min" => 12,
                    "max" => 28
                ]
            ],
            "display" => [
                CozyTouchStateName::CTSN_TARGETHEATLEVEL => [
                    "dashbord" => "heatmode",
                    "mobile" => "heatmode",
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_OPEMODE => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_TARGETTEMP => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_DEROGTARGETTEMP => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_EFFTEMPSETPOINT => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],

                CozyTouchStateName::CTSN_TEMP+"#1" => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_OCCUPANCY+"#1" => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],

                CozyTouchDeviceEqCmds::SET_THERMOSTAT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchDeviceEqCmds::SET_STANDBY => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_BASIC => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_EXTERNAL => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchDeviceEqCmds::SET_INTERNAL => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_AUTO => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchDeviceEqCmds::SET_FROSTPROTECT=> [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_ECO=> [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT2=> [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT1 => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_COMFORT => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ]
            ]
        ],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICTOWELDRYER => [
            "label" => "Sèche serviette",
            "states" => [
                CozyTouchStateName::CTSN_NAME => [
                    "type" => "string",
                    "label" => "Label"
                ],
                CozyTouchStateName::CTSN_OPEMODE => [
                    "type" => "string",
                    "label" => "Label"
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "type" => "binary",
                    "label" => "Connect"
                ],
                CozyTouchStateName::CTSN_ONOFF => [
                    "type" => "binary",
                    "label" => "OnOff"
                ],
                CozyTouchStateName::CTSN_TARGETHEATLEVEL => [
                    "type" => "string",
                    "label" => "Mode"
                ],
                CozyTouchStateName::CTSN_TARGETTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. Cible"
                ],
                CozyTouchStateName::CTSN_COMFROOMTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. Confort"
                ],
                CozyTouchStateName::CTSN_ECOROOMTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. Eco"
                ],
                CozyTouchStateName::CTSN_DEROGTARGETTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. Dérogation"
                ],
                CozyTouchStateName::CTSN_EFFTEMPSETPOINT => [
                    "type" => "numeric",
                    "label" => "Effective"
                ],
                CozyTouchStateName::CTSN_TEMPPROBECALIBR => [
                    "type" => "numeric",
                    "label" => "Calibrage"
                ]
            ],
            "sensors" => [
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR+"#1" => [
                    CozyTouchStateName::CTSN_TEMP+"#1" => [
                        "type" => "numeric",
                        "label" => "Température 1"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR+"#2" => [
                    CozyTouchStateName::CTSN_TEMP+"#2" => [
                        "type" => "numeric",
                        "label" => "Température 2"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATEROCCUPANCYSENSOR+"#1" => [
                    CozyTouchStateName::CTSN_OCCUPANCY+"#1" => [
                        "type" => "binary",
                        "label" => "Présence"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERELECTRICITYSENSOR+"#1"=>[
                    CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                        "type" => "numeric",
                        "label" => "Conso Elec"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICRELATIVEHUMIDITY+"#1"=>[
                    CozyTouchStateName::CTSN_RELATIVEHUMIDITY+"#1" => [
                        "type" => "numeric",
                        "label" => "Humidité"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICLIGHT+"#1"=>[
                    CozyTouchStateName::CTSN_LUMINANCE+"#1"=> [
                        "type" => "numeric",
                        "label" => "Luminance"
                    ]
                ]
            ],
            "commands" => [
                
                CozyTouchDeviceEqCmds::SET_THERMOSTAT => [
                    "label" => "Thermostat",
                    "type" => "thermostat", // => création consigne + thermostat
                    "min" => 12,
                    "max" => 28
                ],
                CozyTouchDeviceEqCmds::SET_STANDBY => [
                    "label" => "StandBy"
                ],
                CozyTouchDeviceEqCmds::SET_EXTERNAL => [
                    "label" => "Externe"
                ],
                CozyTouchDeviceEqCmds::SET_INTERNAL => [
                    "label" => "Interne"
                ],
                CozyTouchDeviceEqCmds::SET_AUTO => [
                    "label" => "Auto"
                ],
                CozyTouchDeviceEqCmds::SET_BOOST => [
                    "label" => "Boost",
                    "type" => "toggle" // => création consigne + toggle
                ],
                CozyTouchDeviceEqCmds::SET_DRY => [
                    "label" => "Dry",
                    "type" => "toggle" // => création consigne + toggle
                ]
            ],
            "display" => [
                CozyTouchStateName::CTSN_TARGETHEATLEVEL => [
                    "dashbord" => "heatmode",
                    "mobile" => "heatmode",
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_TEMP+"#1" => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_RELATIVEHUMIDITY => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_BOOSTMODEDURATION => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_BOOST => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_DRYINGDURATION => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_DRY => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                
                CozyTouchDeviceEqCmds::SET_THERMOSTAT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchDeviceEqCmds::SET_STANDBY => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_EXTERNAL => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_INTERNAL => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_AUTO => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ]
            ]
        ],
        CozyTouchDeviceToDisplay::CTDTD_ATLANTICDIMMABLELIGHT=>[
            
            "label" => "Lumière dimmable",
            "states" => [
                CozyTouchStateName::CTSN_NAME => [
                    "type" => "string",
                    "label" => "Label"
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "type" => "binary",
                    "label" => "Connect"
                ],
                CozyTouchStateName::CTSN_ONOFF => [
                    "type" => "binary",
                    "label" => "OnOff"
                ],
                CozyTouchStateName::CTSN_LIGHTSTATE => [
                    "type" => "string",
                    "label" => "Lumière"
                ],
                CozyTouchStateName::CTSN_LIGHTINTENSITY => [
                    "type" => "numeric",
                    "label" => "Intensité"
                ],
                CozyTouchStateName::CTSN_AUTOTURNOFF => [
                    "type" => "numeric",
                    "label" => "Timer Extinction"
                ],
                CozyTouchStateName::CTSN_REMAININGTIME => [
                    "type" => "numeric",
                    "label" => "Remaining"
                ],
                CozyTouchStateName::CTSN_OCCUPANCYACTIVATION => [
                    "type" => "binary",
                    "label" => "Automatique"
                ],
                CozyTouchStateName::CTSN_NIGHTOCCUPANCYACTIVATION => [
                    "type" => "binary",
                    "label" => "Automatique Nuit"
                ]
            ],
            "sensors" => [
            ],
            "commands" => [
                CozyTouchDeviceActions::CTPC_SETONOFFLIGHT => [
                    "label" => "Off"
                ],
                CozyTouchDeviceActions::CTPC_SETOCCUPANCYACTIVATION => [
                    "label" => "Hors gel"
                ],
                CozyTouchDeviceActions::CTPC_SETINTENSITY => [
                    "label" => "Eco"
                ]
            ],
            "display" => [
                
                CozyTouchDeviceActions::CTPC_SETONOFFLIGHT => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchDeviceActions::CTPC_SETOCCUPANCYACTIVATION => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceActions::CTPC_SETINTENSITY => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ]
            ]
        ],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER=>[
            
            "label" => "Chauffe eau",
            "states" => [
                CozyTouchStateName::CTSN_NAME => [
                    "type" => "string",
                    "label" => "Label"
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "type" => "binary",
                    "label" => "Connect"
                ],
                CozyTouchStateName::CTSN_TEMP => [
                    "type" => "numeric",
                    "label" => "Température"
                ],

                CozyTouchStateName::CTSN_BOOSTMODEDURATION => [
                    "type" => "numeric",
                    "label" => "Durée Boost"
                ],
                CozyTouchStateName::CTSN_WATERCONSUMPTION => [
                    "type" => "numeric",
                    "label" => "Eau Conso."
                ],
                CozyTouchStateName::CTSN_AWAYMODEDURATION => [
                    "type" => "numeric",
                    "label" => "Durée Absence"
                ],
                CozyTouchStateName::CTSN_DHWMODE => [
                    "type" => "string",
                    "label" => "Mode"
                ],
                CozyTouchStateName::CTSN_TARGETTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. cible"
                ],
                CozyTouchStateName::CTSN_DHWCAPACITY => [
                    "type" => "numeric",
                    "label" => "Capacité"
                ],
                CozyTouchStateName::CTSN_OPEMODECAPABILITIES => [
                    "type" => "string",
                    "label" => "Operation mode"
                ],
                CozyTouchStateName::EQ_ISHOTWATERHEATING => [
                    "type" => "binary",
                    "label" => "En cours"
                ],
                CozyTouchStateName::EQ_HOTWATERCOEFF=> [
                    "type" => "numeric",
                    "label" => "Proportion eau chaude"
                ]
            ],
            "sensors" => [
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR+"#1" => [
                    CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                        "type" => "numeric",
                        "label" => "Conso (Watt)"
                    ]
                ],
            ],

            "commands" => [
                CozyTouchDeviceEqCmds::SET_BOOST=> [
                    "label" => "Boost",
                    "type" => "toggle" // => création consigne + toggle
                ],
                CozyTouchDeviceEqCmds::SET_THERMOSTAT => [
                    "label" => "Thermostat",
                    "type" => "thermostat", // => création consigne + thermostat
                    "min" => 12,
                    "max" => 28
                ],
                CozyTouchDeviceToDisplay::SET_AUTOMODE => [
                    "label" => "Auto"
                ],
                CozyTouchDeviceToDisplay::SET_MANUECOACTIVE => [
                    "label" => "Eco"
                ],
                CozyTouchDeviceToDisplay::SET_MANUECOINACTIVE => [
                    "label" => "Manuel"
                ]
            ],
            "display" => [
                
                CozyTouchStateName::EQ_HOTWATERCOEFF => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_DHWMODE => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::EQ_ISHOTWATERHEATING => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_TEMP => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_TARGETTEMP => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_WATERCONSUMPTION => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_BOOST => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_AWAYMODEDURATION => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_THERMOSTAT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::SET_AUTOMODE => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::SET_MANUECOACTIVE => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::SET_MANUECOINACTIVE => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ]
            ]
        ],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERSPLIT => [
            
            "label" => "Chauffe eau",
            "states" => [
                CozyTouchStateName::CTSN_NAME => [
                    "type" => "string",
                    "label" => "Label"
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "type" => "binary",
                    "label" => "Connect"
                ],
                CozyTouchStateName::CTSN_MIDDLETEMP => [
                    "type" => "numeric",
                    "label" => "Température"
                ],

                CozyTouchStateName::CTSN_BOOSTMODEDURATION => [
                    "type" => "numeric",
                    "label" => "Durée Boost"
                ],
                CozyTouchStateName::CTSN_WATERCONSUMPTION => [
                    "type" => "numeric",
                    "label" => "Eau Conso."
                ],
                CozyTouchStateName::CTSN_AWAYMODEDURATION => [
                    "type" => "numeric",
                    "label" => "Durée Absence"
                ],
                CozyTouchStateName::CTSN_DHWMODE => [
                    "type" => "string",
                    "label" => "Mode"
                ],
                CozyTouchStateName::CTSN_TARGETTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. cible"
                ],
                CozyTouchStateName::CTSN_DHWCAPACITY => [
                    "type" => "numeric",
                    "label" => "Capacité"
                ],
                CozyTouchStateName::EQ_HOTWATERCOEFF=> [
                    "type" => "numeric",
                    "label" => "Proportion eau chaude"
                ]
            ],
            "sensors" => [
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR+"#1" => [
                    CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                        "type" => "numeric",
                        "label" => "Conso (Watt)"
                    ]
                ],
            ],

            "commands" => [
                CozyTouchDeviceEqCmds::SET_BOOST=> [
                    "label" => "Boost",
                    "type" => "toggle" // => création consigne + toggle
                ],
                CozyTouchDeviceEqCmds::SET_THERMOSTAT => [
                    "label" => "Thermostat",
                    "type" => "thermostat", // => création consigne + thermostat
                    "min" => 12,
                    "max" => 28
                ],
                CozyTouchDeviceToDisplay::SET_AUTOMODE => [
                    "label" => "Auto"
                ],
                CozyTouchDeviceToDisplay::SET_MANUECOACTIVE => [
                    "label" => "Eco"
                ],
                CozyTouchDeviceToDisplay::SET_MANUECOINACTIVE => [
                    "label" => "Manuel"
                ]
            ],
            "display" => [
                
                CozyTouchStateName::EQ_HOTWATERCOEFF => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_DHWMODE => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::EQ_ISHOTWATERHEATING => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_MIDDLETEMP => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_TARGETTEMP => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_WATERCONSUMPTION => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_BOOST => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_AWAYMODEDURATION => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_THERMOSTAT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::SET_AUTOMODE => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::SET_MANUECOACTIVE => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::SET_MANUECOINACTIVE => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ]
            ]
        ],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCETHIV4 => [
            
            "label" => "Chauffe eau",
            "states" => [
                CozyTouchStateName::CTSN_NAME => [
                    "type" => "string",
                    "label" => "Label"
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "type" => "binary",
                    "label" => "Connect"
                ],
                CozyTouchStateName::CTSN_MIDDLETEMP => [
                    "type" => "numeric",
                    "label" => "Température"
                ],

                CozyTouchStateName::CTSN_BOOSTMODEDURATION => [
                    "type" => "numeric",
                    "label" => "Durée Boost"
                ],
                CozyTouchStateName::CTSN_V40WATERVOLUME => [
                    "type" => "numeric",
                    "label" => "Vol eau à 40°C"
                ],
                CozyTouchStateName::CTSN_AWAYMODEDURATION => [
                    "type" => "numeric",
                    "label" => "Durée Absence"
                ],
                CozyTouchStateName::CTSN_DHWMODE => [
                    "type" => "string",
                    "label" => "Mode"
                ],
                CozyTouchStateName::CTSN_TARGETTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. cible"
                ],
                CozyTouchStateName::CTSN_DHWCAPACITY => [
                    "type" => "numeric",
                    "label" => "Capacité"
                ],
                CozyTouchStateName::EQ_HOTWATERCOEFF=> [
                    "type" => "numeric",
                    "label" => "Proportion eau chaude"
                ]
            ],
            "sensors" => [
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR+"#1" => [
                    CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                        "type" => "numeric",
                        "label" => "Conso (Watt)"
                    ]
                ],
            ],

            "commands" => [
                CozyTouchDeviceEqCmds::SET_BOOST=> [
                    "label" => "Boost",
                    "type" => "toggle" // => création consigne + toggle
                ],
                CozyTouchDeviceEqCmds::SET_THERMOSTAT => [
                    "label" => "Thermostat",
                    "type" => "thermostat", // => création consigne + thermostat
                    "min" => 50,
                    "max" => 62
                ],
                CozyTouchDeviceToDisplay::SET_AUTOMODE => [
                    "label" => "Auto"
                ],
                CozyTouchDeviceToDisplay::SET_MANUECOACTIVE => [
                    "label" => "Eco"
                ],
                CozyTouchDeviceToDisplay::SET_MANUECOINACTIVE => [
                    "label" => "Manuel"
                ]
            ],
            "display" => [
                
                CozyTouchStateName::EQ_HOTWATERCOEFF => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_DHWMODE => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::EQ_ISHOTWATERHEATING => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_MIDDLETEMP => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_TARGETTEMP => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_WATERCONSUMPTION => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_BOOST => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_AWAYMODEDURATION => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_THERMOSTAT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::SET_AUTOMODE => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::SET_MANUECOACTIVE => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::SET_MANUECOINACTIVE => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ]
            ]
        ],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCES4 => [
            
            "label" => "Chauffe eau",
            "states" => [
                CozyTouchStateName::CTSN_NAME => [
                    "type" => "string",
                    "label" => "Label"
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "type" => "binary",
                    "label" => "Connect"
                ],
                CozyTouchStateName::CTSN_DHWMODE => [
                    "type" => "string",
                    "label" => "Mode"
                ],
                CozyTouchStateName::CTSN_WATERCONSUMPTION => [
                    "type" => "numeric",
                    "label" => "Eau Conso."
                ],
                CozyTouchStateName::CTSN_DHWCAPACITY => [
                    "type" => "numeric",
                    "label" => "Capacité"
                ],
                CozyTouchStateName::CTSN_DHWBOOSTMODE => [
                    "type" => "string",
                    "label" => "Durée Boost"
                ],
                CozyTouchStateName::CTSN_DHWABSENCEMODE => [
                    "type" => "string",
                    "label" => "Durée Absence"
                ],
                CozyTouchStateName::CTSN_MIDDLETEMP => [
                    "type" => "numeric",
                    "label" => "Température"
                ],
                CozyTouchStateName::CTSN_V40WATERVOLUME => [
                    "type" => "numeric",
                    "label" => "Vol eau à 40°C"
                ],
                CozyTouchStateName::CTSN_WATERTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. eau 2"
                ],
                CozyTouchStateName::CTSN_WATERTARGETTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. cible"
                ],
                CozyTouchStateName::CTSN_EXPECTEDNBSHOWER => [
                    "type" => "numeric",
                    "label" => "Douches souhaitées"
                ],
                CozyTouchStateName::CTSN_CTRLWATERTARGETTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. ctrl temp. eau cible"
                ],
                CozyTouchStateName::CTSN_MIDDLEWATERTEMPIN => [
                    "type" => "numeric",
                    "label" => "Temp. eau 1"
                ],
                CozyTouchStateName::CTSN_HEATINGSTATUS => [
                    "type" => "string",
                    "label" => "Heat status"
                ],
                CozyTouchStateName::CTSN_NBSHOWERREMAINING => [
                    "type" => "numeric",
                    "label" => "Douches restantes"
                ],
                CozyTouchStateName::CTSN_MINISHOWERMANUAL => [
                    "type" => "numeric",
                    "label" => "Mini douche"
                ],
                CozyTouchStateName::CTSN_MAXISHOWERMANUAL=> [
                    "type" => "numeric",
                    "label" => "Maxi douche"
                ],
                CozyTouchStateName::EQ_HOTWATERCOEFF=> [
                    "type" => "numeric",
                    "label" => "Proportion eau chaude"
                ]
            ],
            "sensors" => [
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR+"#1" => [
                    CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                        "type" => "numeric",
                        "label" => "Conso (Watt)"
                    ]
                ],
            ],

            "commands" => [
                CozyTouchDeviceEqCmds::SET_BOOST=> [
                    "label" => "Boost",
                    "type" => "toggle" // => création consigne + toggle
                ],
                CozyTouchDeviceEqCmds::SET_EXPECTEDSHOWER => [
                    "label" => "Nb douche cible",
                    "type" => "slider", // => création consigne + thermostat
                    "min" => 2,
                    "max" => 4
                ],
                CozyTouchDeviceToDisplay::SET_AUTOMODE => [
                    "label" => "Auto"
                ],
                CozyTouchDeviceToDisplay::SET_MANUECOINACTIVE => [
                    "label" => "Manuel"
                ]
            ],
            "display" => [
                
                CozyTouchStateName::EQ_HOTWATERCOEFF => [
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_DHWMODE => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_MIDDLETEMP => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_MIDDLEWATERTEMPIN => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_WATERCONSUMPTION => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_V40WATERVOLUME => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_EXPECTEDNBSHOWER => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_NBSHOWERREMAINING => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_BOOST => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_EXPECTEDSHOWER => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::SET_AUTOMODE => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::SET_MANUECOINACTIVE => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ]
            ]
        ],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERFLATC2 => [
            
            "label" => "Chauffe eau",
            "states" => [
                CozyTouchStateName::CTSN_NAME => [
                    "type" => "string",
                    "label" => "Label"
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "type" => "binary",
                    "label" => "Connect"
                ],
                CozyTouchStateName::CTSN_DHWMODE => [
                    "type" => "string",
                    "label" => "Mode"
                ],
                CozyTouchStateName::CTSN_WATERCONSUMPTION => [
                    "type" => "numeric",
                    "label" => "Eau Conso."
                ],
                CozyTouchStateName::CTSN_DHWCAPACITY => [
                    "type" => "numeric",
                    "label" => "Capacité"
                ],
                CozyTouchStateName::CTSN_DHWBOOSTMODE => [
                    "type" => "string",
                    "label" => "Durée Boost"
                ],
                CozyTouchStateName::CTSN_DHWABSENCEMODE => [
                    "type" => "string",
                    "label" => "Durée Absence"
                ],
                CozyTouchStateName::CTSN_MIDDLETEMP => [
                    "type" => "numeric",
                    "label" => "Température"
                ],
                CozyTouchStateName::CTSN_V40WATERVOLUME => [
                    "type" => "numeric",
                    "label" => "Vol eau à 40°C"
                ],
                CozyTouchStateName::CTSN_WATERTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. eau 2"
                ],
                CozyTouchStateName::CTSN_WATERTARGETTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. cible"
                ],
                CozyTouchStateName::CTSN_EXPECTEDNBSHOWER => [
                    "type" => "numeric",
                    "label" => "Douches souhaitées"
                ],
                CozyTouchStateName::CTSN_CTRLWATERTARGETTEMP => [
                    "type" => "numeric",
                    "label" => "Temp. ctrl temp. eau cible"
                ],
                CozyTouchStateName::CTSN_MIDDLEWATERTEMPIN => [
                    "type" => "numeric",
                    "label" => "Temp. eau 1"
                ],
                CozyTouchStateName::CTSN_HEATINGSTATUS => [
                    "type" => "string",
                    "label" => "Heat status"
                ],
                CozyTouchStateName::CTSN_NBSHOWERREMAINING => [
                    "type" => "numeric",
                    "label" => "Douches restantes"
                ],
                CozyTouchStateName::CTSN_MINISHOWERMANUAL => [
                    "type" => "numeric",
                    "label" => "Mini douche"
                ],
                CozyTouchStateName::CTSN_MAXISHOWERMANUAL=> [
                    "type" => "numeric",
                    "label" => "Maxi douche"
                ],
                CozyTouchStateName::EQ_HOTWATERCOEFF=> [
                    "type" => "numeric",
                    "label" => "Proportion eau chaude"
                ]
            ],
            "sensors" => [
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR+"#1" => [
                    CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                        "type" => "numeric",
                        "label" => "Conso (Watt)"
                    ]
                ],
            ],

            "commands" => [
                CozyTouchDeviceEqCmds::SET_BOOST=> [
                    "label" => "Boost",
                    "type" => "toggle" // => création consigne + toggle
                ],
                CozyTouchDeviceEqCmds::SET_EXPECTEDSHOWER => [
                    "label" => "Nb douche cible",
                    "type" => "slider", // => création consigne + thermostat
                    "min" => 2,
                    "max" => 4
                ],
                CozyTouchDeviceToDisplay::SET_AUTOMODE => [
                    "label" => "Auto"
                ],
                CozyTouchDeviceToDisplay::SET_MANUECOINACTIVE => [
                    "label" => "Manuel"
                ]
            ],
            "display" => [
                
                CozyTouchStateName::EQ_HOTWATERCOEFF => [
                    "dashbord" => "hotwater",
                    "mobile" => "hotwater",
                    "beforeline" => 1,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_DHWMODE => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_MIDDLETEMP => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_MIDDLEWATERTEMPIN => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_WATERCONSUMPTION => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_V40WATERVOLUME => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_EXPECTEDNBSHOWER => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::CTSN_NBSHOWERREMAINING => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_BOOST => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_EXPECTEDSHOWER => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::SET_AUTOMODE => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchStateName::SET_MANUECOINACTIVE => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
                CozyTouchStateName::CTSN_CONNECT => [
                    "beforeline" => 1,
                    "afterline" => 1
                ]
            ]
        ],
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV3,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV2MURAL,
        CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV2AEX,
        
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHEATRECOVERYVENT,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCHEATPUMPMAIN,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLMAIN
    ];
}