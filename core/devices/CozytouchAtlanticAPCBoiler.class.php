<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/constants/CozyTouchConstants.class.php";
require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";

if (! class_exists('CozytouchAtlanticAPCHeatingZone')) {
    require_once dirname(__FILE__) . "/CozytouchAtlanticAPCHeatingZone.class.php";
}

class CozytouchAtlanticAPCBoiler extends AbstractCozytouchDevice
{
    
    // [{order},{beforeLigne},{afterLigne}]
    const DISPLAY = [
        CozyTouchStateName::EQ_ZONECTRLMODE=>[2,0,1],
        
        CozyTouchDeviceEqCmds::SET_OFF=>[30,1,0],
        CozyTouchDeviceEqCmds::SET_ZONECTRLHEAT=>[32,0,0],
        
        CozyTouchStateName::CTSN_TEMP => [10,1,1],
        CozyTouchStateName::CTSN_ZONESNUMBER => [11,1,1],
        // CozyTouchStateName::CTSN_ABSENCESCHEDULINGMODE => [5, 1, 1],
        // CozyTouchStateName::CTSN_ABSENCEHEATINGTARGETTEMP => [6, 1, 1],
        // CozyTouchStateName::CTSN_ABSENCEENDDATETIME => [7, 1, 1],
        
        CozyTouchStateName::CTSN_CONNECT => [99, 1, 1],
        
        'refresh' => [1,0,0]
    ];
    
    // Construction des commandes jeedom
    public static function BuildEqLogic($device)
    {
        $deviceURL = $device->getURL();
        log::add('cozytouch', 'info', 'creation (ou mise à jour) ' . $device->getVar(CozyTouchDeviceInfo::CTDI_LABEL));
        $eqLogic = self::BuildDefaultEqLogic($device);
        
        $states = CozyTouchDeviceStateName::EQLOGIC_STATENAME[$device->getVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME)];
        $sensors = array();
        
        $deviceSensors = $device->getSensors();
        $nbSensors = count($deviceSensors);
        log::add('cozytouch', 'info', 'Sensor count : ' . $nbSensors);
        for ($i = 0; $i < $nbSensors; $i ++) {
            $sensor = $deviceSensors[$i];
            $sensorURL = $sensor->getURL();
            $sensorModel = $sensor->getModel();
            
            log::add('cozytouch', 'info', $i . ' ' . $sensorModel);
            $sensors[] = array(
                $sensorURL,
                $sensor->getModel()
            );
            log::add('cozytouch', 'info', 'Sensor : ' . $sensorURL);
            
            switch ($sensorModel) {
                
                // Création de l'objet lié (zone)
                case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCHEATINGZONE:
                    
                    log::add('cozytouch', 'info', 'Zone control To Create');
                    CozytouchAtlanticAPCHeatingZone::BuildEqLogic($sensor);
                    break;
                    
                    // Récupération du capteur de température extérieur depuis le sensor io:AtlanticPassAPCOutsideTemperatureSensor
                case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCOUTSIDETEMPERATURESENSOR:
                    // state du capteur
                    foreach ($sensor->getStates() as $state) {
                        if ($state->name == CozyTouchStateName::CTSN_TEMP) {
                            log::add('cozytouch', 'info', 'State : ' . $state->name);
                            $cmdId = $sensorURL . '_' . $state->name;
                            $type = "info";
                            $subType = CozyTouchStateName::CTSN_TYPE[$state->name];
                            $name = "Temp. extérieur";
                            $dashboard = CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
                            $mobile = CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
                            $value = $subType == "numeric" ? 0 : ($subType == "string" ? 'value' : 0);
                            self::upsertCommand($eqLogic, $cmdId, $type, $subType, $name, 1, $value, $dashboard, $mobile, $i + 1);
                            break;
                        }
                    }
                    break;
                    // Récupération du capteur de température intérieur depuis le sensor io:AtlanticPassAPCZoneTemperatureSensor
                case CozyTouchDeviceToDisplay::CTDTD_ATLANTICPASSAPCZONETEMPERATURESENSOR:
                    // state du capteur
                    foreach ($sensor->getStates() as $state) {
                        if ($state->name == CozyTouchStateName::CTSN_TEMP) {
                            log::add('cozytouch', 'info', 'State : ' . $state->name);
                            $cmdId = $sensorURL . '_' . $state->name;
                            $type = "info";
                            $subType = CozyTouchStateName::CTSN_TYPE[$state->name];
                            $name = "Temp. intérieur";
                            $dashboard = CozyTouchCmdDisplay::DISPLAY_DASH[$subType];
                            $mobile = CozyTouchCmdDisplay::DISPLAY_MOBILE[$subType];
                            $value = $subType == "numeric" ? 0 : ($subType == "string" ? 'value' : 0);
                            self::upsertCommand($eqLogic, $cmdId, $type, $subType, $name, 1, $value, $dashboard, $mobile, $i + 1);
                            break;
                        }
                    }
                    break;
            }
        }
        
        // Ajout des actions
        // Mode
        $cmd = $eqLogic->getCmd(null, $device->getURL() . '_' . CozyTouchStateName::EQ_ZONECTRLMODE);
        if (! is_object($cmd)) {
            $cmd = new cozytouchCmd();
            $cmd->setIsVisible(1);
        }
        $cmd->setEqLogic_id($eqLogic->getId());
        $cmd->setName(__(CozyTouchStateName::CTSN_LABEL[CozyTouchStateName::EQ_ZONECTRLMODE], __FILE__));
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setLogicalId($device->getURL() . '_' . CozyTouchStateName::EQ_ZONECTRLMODE);
        $cmd->setTemplate('dashboard', 'zonetctlmode');
        $cmd->setTemplate('mobile', 'zonetctlmode');
        $cmd->save();
        
        $eqLogic->setConfiguration('sensors', $sensors);
        $eqLogic->setCategory('energy', 1);
        $eqLogic->save();
        
        self::refresh($eqLogic);
        self::orderCommand($eqLogic);
        
        CozyTouchManager::refresh_all();
    }
        
    // Ordre d'affichage des commandes
    public static function orderCommand($eqLogic)
    {
        $cmds = $eqLogic->getCmd();
        foreach ($cmds as $cmd) {
            $logicalId = explode('_', $cmd->getLogicalId());
            $key = $logicalId[(count($logicalId) - 1)];
            log::add('cozytouch', 'debug', 'Mise en ordre : ' . $key);
            if (array_key_exists($key, self::DISPLAY)) {
                $cmd->setIsVisible(1);
                $cmd->setOrder(self::DISPLAY[$key][0]);
                $cmd->setDisplay('forceReturnLineBefore', self::DISPLAY[$key][1]);
                $cmd->setDisplay('forceReturnLineAfter', self::DISPLAY[$key][2]);
            } else {
                $cmd->setIsVisible(0);
            }
            $cmd->save();
        }
    }
    
    // Execution des commandes
    public static function Execute($cmd, $_options = array())
    {
        log::add('cozytouch', 'debug', 'command : ' . $cmd->getLogicalId());
        $refresh = true;
        $eqLogic = $cmd->getEqLogic();
        $device_url = $eqLogic->getConfiguration('device_url');
        switch ($cmd->getLogicalId()) {
            case 'refresh':
                log::add('cozytouch', 'debug', 'command : ' . $device_url . ' refresh');
                break;
            case CozyTouchDeviceEqCmds::SET_OFF:
                log::add('cozytouch', 'debug', 'command : ' . $device_url . ' ' . CozyTouchDeviceEqCmds::SET_OFF);
                self::set_stop_mode($device_url);
                break;
            case CozyTouchDeviceEqCmds::SET_ZONECTRLHEAT:
                log::add('cozytouch', 'debug', 'command : ' . $device_url . ' ' . CozyTouchDeviceEqCmds::SET_ZONECTRLHEAT);
                self::set_heating_mode($device_url);
                break;
        }
        if ($refresh) {
            sleep(6);
            self::refresh($eqLogic);
        }
    }
    
    // Rafraichissement des états
    protected static function refresh($eqLogic)
    {
        log::add('cozytouch', 'debug', 'refresh : ' . $eqLogic->getName());
        try {
            
            $device_url = $eqLogic->getConfiguration('device_url');
            $controllerName = $eqLogic->getConfiguration('device_model');
            
            $clientApi = CozyTouchManager::getClient();
            $states = $clientApi->getDeviceInfo($device_url, $controllerName);
            foreach ($states as $state) {
                $cmd_array = Cmd::byLogicalId($device_url . "_" . $state->name);
                if (is_array($cmd_array) && $cmd_array != null) {
                    $cmd = $cmd_array[0];
                    
                    $value = CozyTouchManager::get_state_value($state);
                    if (is_object($cmd) && $cmd->execCmd() !== $cmd->formatValue($value)) {
                        $cmd->setCollectDate('');
                        $cmd->event($value);
                    }
                }
            }
            
            self::refresh_mode($eqLogic);
        } catch (Exception $e) {}
    }
    
    // Refresh des temperatures int. et ext.
    public static function refresh_temp($eqLogic)
    {
        $deviceURL = $eqLogic->getConfiguration('device_url');
        $temp_int = 0;
        $temp_ext = 0;
        
        $sensorURL = explode('#', $deviceURL)[0] . '#8';
        $cmd = Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), $sensorURL . '_' . CozyTouchStateName::CTSN_TEMP);
        if (is_object($cmd)) {
            $temp_int = $cmd->execCmd();
            $cmd->event($temp_int);
        }
        
        $sensorURL = explode('#', $deviceURL)[0] . '#3';
        $cmd = Cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), $sensorURL . '_' . CozyTouchStateName::CTSN_TEMP);
        if (is_object($cmd)) {
            $temp_ext = $cmd->execCmd();
            $cmd->event($temp_ext);
        }
    }
    
    public static function refresh_mode($eqDevice)
    {
        log::add('cozytouch', 'debug', 'Refresh mode');
        $deviceURL = $eqDevice->getConfiguration('device_url');
        
        $cmd_array = Cmd::byLogicalId($deviceURL . '_' . CozyTouchStateName::CTSN_PASSAPCOPERATINGMODE);
        if (is_array($cmd_array) && $cmd_array != null) {
            $cmd = $cmd_array[0];
            $mode = $cmd->execCmd();
        }
        

        $cmd = Cmd::byEqLogicIdAndLogicalId($eqDevice->getId(), $deviceURL . '_' . CozyTouchStateName::EQ_ZONECTRLMODE);
        if (is_object($cmd)) {
            $cmd->setCollectDate('');
            $cmd->event($mode);
            log::add('cozytouch', 'info', __('Mode ', __FILE__) . $mode);
        }
    }
    
    protected static function set_heating_mode($device_url)
    {
        $cmds = array(
            array(
                "name" => CozyTouchDeviceActions::CTPC_RSHZONESAPCHEATINGPROFILE,
                "values" => null
            ),
            array(
                "name" => CozyTouchDeviceActions::CTPC_RSHZONESTARGETTEMP,
                "values" => null
            )
        );
        parent::genericApplyCommand($device_url, $cmds);
        sleep(1);
        $cmds = array(
            array(
                "name" => CozyTouchDeviceActions::CTPC_SETAPCOPERATINGMODE,
                "values" => 'heating'
            )
        );
        parent::genericApplyCommand($device_url, $cmds);
    }
    
    protected static function set_stop_mode($device_url)
    {
        $cmds = array(
            array(
                "name" => CozyTouchDeviceActions::CTPC_SETAPCOPERATINGMODE,
                "values" => 'stop'
            )
        );
        parent::genericApplyCommand($device_url, $cmds);
    }
    
     
}

?>