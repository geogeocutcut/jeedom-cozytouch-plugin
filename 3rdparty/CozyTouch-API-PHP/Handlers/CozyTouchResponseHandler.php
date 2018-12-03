<?php
require_once dirname(__FILE__) . '/../../../../../core/php/core.inc.php';

if (!class_exists('CozyTouchObject')) {
	require_once dirname(__FILE__) . "/../Objects/CozyTouchObject.php";
}
if (!class_exists('CozyTouchPlace')) {
	require_once dirname(__FILE__) . "/../Objects/CozyTouchPlace.php";
}
if (!class_exists('CozyTouchDevice')) {
	require_once dirname(__FILE__) . "/../Objects/CozyTouchDevice.php";
}
/**
 *
 * Cozytouch Response Handler
 * class handling Api client response : enables to get either Raw Data or Instantiated Objects
 */
class CozyTouchResponseHandler {
	private $decodedBody;

	public function __construct($responseBody) {
		$this->decodedBody = $responseBody;
	}

	/**
	 * @return array $decodedBody
	 * @brief return raw data retrieved from Netatmo API
	 */
	public function getDecodedBody() {
		return $this->decodedBody;
	}

	/**
	 * return array $dataCollection : array of home or event objects
	 * @brief return data as collection objects
	 * @throw NASDKException
	 */
	public function getData($route) {
		if (!is_null($this->decodedBody) && !empty($this->decodedBody)) {
			switch($route)
			{
				case "setup":
					return $this->_deserializeSetupResponse($this->decodedBody);
					break;
				case "devices":
					return $this->_deserializeDevicesResponse($this->decodedBody);
					break;
				default :
					return $this->decodedBody;
					break;
			}

		}
		else {
			throw new Exception("Serveur not responding : no response found / ");
		}
	}
	
	private function _deserializeSetupResponse($decodedBody)
	{
		log::add('cozytouch', 'info', 'Synchronisation start');
			
		$devices=array();

		// récupérer les pièces
		$placesResponse = $decodedBody->rootPlace->subPlaces;
		while(!empty($placesResponse))
		{
			log::add('cozytouch', 'info', 'Synchronisation pièce : '.$place->label);
			$place=array_shift($placesResponse);
			$placeClss = new CozyTouchPlace(array());
			$placeClss->setVar(CozyTouchPlaceInfo::CTPI_OID, $place->oid);
			$placeClss->setVar(CozyTouchPlaceInfo::CTPI_NAME, $place->label);
			$places[$place->oid]=$placeClss;
			if(!empty($place->subPlaces) && is_array($place->subPlaces))
			{
				$placesResponse = array_merge($placesResponse,$place->subPlaces);
			}
		}
		log::add('cozytouch', 'info', 'Synchronisation next');
		// récupèrer les devices
		foreach($decodedBody->devices as $device) 
		{
			if(in_array($device->controllableName,CozyTouchDeviceToDisplay::CTDTD_DEVICESANDSENSORS))
			{
				$deviceClss = new CozyTouchDevice(array());
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_OID, $device->oid);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_PLACEOID, $device->placeOID);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_URL, $device->deviceURL);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_TYPEDEVICE, $device->uiClass);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, array());
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_CONTROLLABLENAME,$device->controllableName);
				foreach ($device->states as $state)
				{
					if (in_array($state->name,CozyTouchDeviceStateName::DEVICE_STATENAME[$device->controllableName]))
					{
						$vartmp = $deviceClss->getVar(CozyTouchDeviceInfo::CTDI_STATES);
						$vartmp[] = $state;
						$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_STATES,$vartmp);
					}
				}

				// if device
				if(in_array($device->controllableName,CozyTouchDeviceToDisplay::CTDTD_DEVICEMODEL ))
				{
					log::add('cozytouch', 'info', 'Synchronisation device : '.$device->uiClass.' '.$device->deviceURL.' '.$device->controllableName);
					$devices[explode("#",$device->deviceURL)[0]]=$deviceClss;
				}
				// else sensor
				else
				{
					// if device existe
					if(array_key_exists( explode("#",$device->deviceURL)[0], $devices )==true)
					{
						log::add('cozytouch', 'info', 'Synchronisation capteur : '.$device->uiClass.' '.$device->deviceURL.' '.$device->controllableName);
					
						$sensors = $devices[explode("#",$device->deviceURL)[0]]->getSensors();
						$sensors[] = $deviceClss;
						$devices[explode("#",$device->deviceURL)[0]]->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, $sensors);
					}
					else
					{
						log::add('cozytouch', 'warning', 'Type de device (ou capteur) inconnu : '.$device->uiClass.' '.$device->deviceURL.' '.$device->controllableName);
					}
				}
			}
			else {
				log::add('cozytouch', 'warning', 'Type de device (ou capteur) inconnu : '.$device->uiClass.' '.$device->deviceURL.' '.$device->controllableName);
			}
		}

		// Creation du nom  : {deviceType} {place} {placeByDeviceTypeCount}
		// Exemple : Radiateur Salon 1
		foreach ($devices as $device)
		{
			$placeOID =$device->getVar(CozyTouchDeviceInfo::CTDI_PLACEOID);
			if(array_key_exists($placeOID , $places )==true)
			{
				$type = $device->getVar(CozyTouchDeviceInfo::CTDI_TYPEDEVICE);
				
				log::add('cozytouch', 'debug', 'Type de device '.$type);
				$PlaceCount[$placeOID][$type]+=1;
				$name= CozyTouchDeviceToDisplay::CTDTD_NAME[$type]
				." ".$places[$placeOID]->getVar(CozyTouchPlaceInfo::CTPI_NAME)
				." ".$PlaceCount[$placeOID][$type];
				$device->setVar(CozyTouchDeviceInfo::CTDI_LABEL,$name);
			}
		}
		log::add('cozytouch', 'debug', 'Synchronisation de '.count($devices).' device(s)');
		
		return $devices;
	}

	private function _deserializeDevicesResponse($decodedBody)
	{
		$devices = array();
		
		foreach($this->decodedBody as $device) 
		{
			if(in_array($device->controllableName,CozyTouchDeviceToDisplay::CTDTD_DEVICESANDSENSORS))
			{
				$deviceClss = new CozyTouchDevice(array());
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_OID, $device->oid);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_PLACEOID, $device->placeOID);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_URL, $device->deviceURL);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_TYPEDEVICE, $device->uiClass);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, array());
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_CONTROLLERNAME,$device->controllableName);
				foreach ($device->states as $state)
				{
					if (in_array($state->name,CozyTouchDeviceStateName::$DEVICE_STATENAME[$device->controllableName]))
					{
						$vartmp = $deviceClss->getVar(CozyTouchDeviceInfo::CTDI_STATES);
						$vartmp[] = $state;
						$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_STATES,$vartmp);
					}
				}

				// if device
				if(in_array($device->controllableName,CozyTouchDeviceToDisplay::CTDTD_DEVICEMODEL ))
				{
					$devices[explode("#",$device->deviceURL)[0]]=$deviceClss;
				}
				// else sensor
				else
				{
					// if device existe
					if(array_key_exists( explode("#",$device->deviceURL)[0], $devices )==true)
					{
						$sensors = $devices[explode("#",$device->deviceURL)[0]]->getSensors();
						$sensors[] = $deviceClss;
						$devices[explode("#",$device->deviceURL)[0]]->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, $sensors);
					}
				}
			}
		}
		$this->dataCollection = $devices;
	}


	// utiliser pour la synchronisation
	public function buildPlaceCollectionFromResponse() {	
		//$result_arr->setup->place->place
		//$result_arr->setup->devices
		$places = array();
		$devices = array();
		$sensors = array();
		
		foreach($this->decodedBody->setup->rootPlace->subPlaces as $place) {
			$placeClss = new CozyTouchPlace(array());
			$placeClss->setVar(CozyTouchPlaceInfo::CTPI_OID, $place->oid);
			$placeClss->setVar(CozyTouchPlaceInfo::CTPI_NAME, $place->label);
			$placeClss->setVar(CozyTouchPlaceInfo::CTPI_DEVICES, array());
			
			$places[$place->oid]=$placeClss;
		}
		
		
		foreach($this->decodedBody->setup->devices as $device) 
		{
			if(in_array($device->uiClass,CozyTouchDeviceToDisplay::$CTDTD_CLASS))
			{
				$deviceClss = new CozyTouchDevice(array());
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_OID, $device->oid);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_PLACEOID, $device->placeOID);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_URL, $device->deviceURL);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_TYPEDEVICE, $device->uiClass);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, array());
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_CONTROLLERNAME,$device->controllableName);
				foreach ($device->states as $state)
				{
					if (in_array($state->name,CozyTouchDeviceStateName::$DEVICE_STATENAME[$device->uiClass]))
					{
						$vartmp = $deviceClss->getVar(CozyTouchDeviceInfo::CTDI_STATES);
						$vartmp[] = $state;
						$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_STATES,$vartmp);
					}
				}

				// if device
				if(in_array($device->uiClass,CozyTouchDeviceToDisplay::CTDTD_DEVICETYPE ))
				{
					$devices[explode("#",$device->deviceURL)[0]]=$deviceClss;
					
				}
				// else sensor
				else
				{
					// if device existe
					if(array_key_exists( explode("#",$device->deviceURL)[0], $devices )==true)
					{
						$sensors = $devices[explode("#",$device->deviceURL)[0]]->getSensors();
						$sensors[] = $deviceClss;
						$devices[explode("#",$device->deviceURL)[0]]->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, $sensors);
					}
				}
			}
		}
		// rattachement des devices a sa piece (place)
		foreach ($devices as $device)
		{
			if(array_key_exists( $device->getVar(CozyTouchDeviceInfo::CTDI_PLACEOID), $places )==true)
			{
				$devices2 = $places[$device->getVar(CozyTouchDeviceInfo::CTDI_PLACEOID)]->getDevices();
				$devices2[$device->getVar(CozyTouchDeviceInfo::CTDI_TYPEDEVICE)][] = $device;
				$places[$device->getVar(CozyTouchDeviceInfo::CTDI_PLACEOID)]->setVar(CozyTouchPlaceInfo::CTPI_DEVICES, $devices2);
			}
		}	
		$this->dataCollection = $places;
	}
	
	// utiliser pour la mise a jour des infos
	public function buildStatesCollectionFromResponse() {
		
		$devices = array();
		$sensors = array();
		// l'api cozytouch ne renvoie que ce que nous avons demandé
		foreach($this->decodedBody->devices as $device) 
		{
			$deviceClss = new CozyTouchDevice(array());
			$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_URL, $device->deviceURL);
			$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, array());
			foreach ($device->states as $state)
			{
				$vartmp = $deviceClss->getVar(CozyTouchDeviceInfo::CTDI_STATES);
				$vartmp[] = $state;
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_STATES,$vartmp);
			}
			// if device
			if(substr($device->deviceURL, -1, 1)=="1")
			{
				$devices[explode("#",$device->deviceURL)[0]]=$deviceClss;
			}
			// else sensor
			else
			{
				// if device existe
				if(array_key_exists( explode("#",$device->deviceURL)[0], $devices )==true)
				{
					$sensors = $devices[explode("#",$device->deviceURL)[0]]->getSensors();
					$sensors[] = $deviceClss;
					$devices[explode("#",$device->deviceURL)[0]]->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, $sensors);
				}
			}
		}
		$this->dataCollection = $devices;
	}
}
?>
