<?php
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
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR => [
                    CozyTouchStateName::CTSN_TEMP => [
                        "type" => "numeric",
                        "label" => "Température"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATEROCCUPANCYSENSOR => [
                    CozyTouchStateName::CTSN_OCCUPANCY => [
                        "type" => "binary",
                        "label" => "Présence"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERELECTRICITYSENSOR=>[
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
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR => [
                    CozyTouchStateName::CTSN_TEMP+"#1" => [
                        "type" => "numeric",
                        "label" => "Température 1"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICTEMPERATURESENSOR => [
                    CozyTouchStateName::CTSN_TEMP+"#2" => [
                        "type" => "numeric",
                        "label" => "Température 2"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATEROCCUPANCYSENSOR => [
                    CozyTouchStateName::CTSN_OCCUPANCY+"#1" => [
                        "type" => "binary",
                        "label" => "Présence"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATERELECTRICITYSENSOR=>[
                    CozyTouchStateName::CTSN_ELECNRJCONSUMPTION+"#1" => [
                        "type" => "numeric",
                        "label" => "Conso Elec"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICRELATIVEHUMIDITY=>[
                    CozyTouchStateName::CTSN_RELATIVEHUMIDITY+"#1" => [
                        "type" => "numeric",
                        "label" => "Humidité"
                    ]
                ],
                CozyTouchDeviceToDisplay::CTDTD_ATLANTICLIGHT=>[
                    CozyTouchStateName::CTSN_LUMINANCE+"#1" => [
                        "type" => "numeric",
                        "label" => "Luminance"
                    ]
                ]
            ],
            "commands" => [
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
                    "afterline" => 1
                ],
                CozyTouchDeviceEqCmds::SET_AUTO => [
                    "beforeline" => 1,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_BOOST => [
                    "beforeline" => 0,
                    "afterline" => 0
                ],
                CozyTouchDeviceEqCmds::SET_DRY => [
                    "beforeline" => 0,
                    "afterline" => 1
                ],
            ]
        ],
        CozyTouchDeviceToDisplay::CTDTD_ATLANTICDIMMABLELIGHT=>[
            
            "label" => "Radiateur dimmable",
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
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERSPLIT,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCETHIV4,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERCES4,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERFLATC2,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV3,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATERV2AEX,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICHEATRECOVERYVENT,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCHEATPUMPMAIN,
		CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONECTRLMAIN
    ];
}