<?php
if (!class_exists('CozyTouchApiClient')) {
	require_once dirname(__FILE__) . "/../../3rdparty/cozytouch/client/CozyTouchApiClient.class.php";
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
					'userPassword' => config::byKey('password', 'cozytouch')
			));
		}
		return self::$_client;
    }

    public static function syncWithCozyTouch() 
	{
	}
	
	public static function syncWithCozyTouch2() 
	{
		log::add('cozytouch', 'info', 'cron non existant : creation en cours cron');
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
				case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER:
                    CozytouchAtlanticHeatSystem::BuildEqLogic($device);
					break;
                default:
                    AbstractCozytouchDevice::BuildDefaultEqLogic($device);
					break;
			}
		}
		
		$cron = cron::byClassAndFunction('cozytouch', 'cron15');
		if (!is_object($cron)) {

			log::add('cozytouch', 'info', 'cron non existant : creation en cours cron15');
			$cron = new cron();
			$cron->setClass('CozyTouchHelper');
			$cron->setFunction('cron15');
			$cron->setEnable(1);
			$cron->setDeamon(0);
			$cron->setSchedule('*/5 * * * * *');
			$cron->save();
		}
		
		CozyTouchHelper::refresh_all();
	}
	
	public static function cron15() {
    	CozyTouchHelper::refresh_all();
	}
	
    public static function refresh_all() 
	{
    	try {
    		
    		$clientApi = self::getClient();
    		$resp = $clientApi->getDevices();
			$devices = $resp->getData();
			foreach ($devices as $device)
			{
				$urlShort = explode("#",$device->getURL())[0];
				// state du device
				foreach ($device->getStates() as $state)
				{
					$cmd_array = Cmd::byLogicalId($urlShort.$state->name);
					if(is_array($cmd_array) && $cmd_array!=null)
					{
						$cmd=$cmd_array[0];
						if($state->name==CozyTouchStateName::CTSN_ONOFF)
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
						$cmd_array = Cmd::byLogicalId($urlShort.$state->name);
						if(is_array($cmd_array) && $cmd_array!=null)
						{
							$cmd=$cmd_array[0];
							if($state->name==CozyTouchStateName::CTSN_OCCUPANCY)
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
        } 
        catch (Exception $e) {
    
    	}
    }
}
?>