<?php
if (!class_exists('CozyTouchObject')) {
	require_once dirname(__FILE__) . "/../Objects/CozyTouchObject.php";
}
if (!class_exists('CozyTouchPlace')) {
	require_once dirname(__FILE__) . "/../Objects/CozyTouchPlace.php";
}
if (!class_exists('CozyTouchDevice')) {
	require_once dirname(__FILE__) . "/../Objects/CozyTouchDevice.php";
}
/* if (!class_exists('CozyTouchDeviceCommand')) {
	require_once dirname(__FILE__) . "/../Objects/CozyTouchDeviceCommand.php";
}
if (!class_exists('CozyTouchStateCommand')) {
	require_once dirname(__FILE__) . "/../Objects/CozyTouchStateCommand.php";
} */
/**
 *
 * Netatmo Welcome Response Handler
 * class handling Api client response : enables to get either Raw Data or Instantiated Objects
 */
class CozyTouchResponseHandler {
	private $decodedBody;
	private $dataCollection;

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
	public function getData() {
		if (!is_null($this->decodedBody) && !empty($this->decodedBody)) {
			if (isset($this->decodedBody->setup)) {
				$this->buildPlaceCollectionFromResponse();
			} else if (isset($this->decodedBody->devices)) {
				$this->buildStatesCollectionFromResponse();
			}

			return $this->dataCollection;
		}
		else {
			throw new Exception("Serveur not responding : ".$this->decodedBody." / ");
		}
	}
		
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
			// #1 = new device
			// #other = sensor
			// 		Search device
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

				if(substr($device->deviceURL, -1, 1)=="1")
				{
					$devices[explode("#",$device->deviceURL)[0]]=$deviceClss;
					
				}
				else
				{
					if(array_key_exists( explode("#",$device->deviceURL)[0], $devices )==true)
					{
						$sensors = $devices[explode("#",$device->deviceURL)[0]]->getSensors();
						$sensors[] = $deviceClss;
						$devices[explode("#",$device->deviceURL)[0]]->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, $sensors);
					}
				}
			}
		}
		foreach ($devices as $device)
		{
			if(array_key_exists( $device->getVar(CozyTouchDeviceInfo::CTDI_PLACEOID), $places )==true)
			{
				$devices2 = $places[$device->getVar(CozyTouchDeviceInfo::CTDI_PLACEOID)]->getDevices();
				$devices2[] = $device;
				$places[$device->getVar(CozyTouchDeviceInfo::CTDI_PLACEOID)]->setVar(CozyTouchPlaceInfo::CTPI_DEVICES, $devices2);
			}
		}	
		$this->dataCollection = $places;
	}
		
	public function buildStatesCollectionFromResponse() {
		
		$devices = array();
		$sensors = array();
	
		foreach($this->decodedBody->devices as $device) 
		{
			// if(in_array(substr($device->deviceURL, -2, 2),CozyTouchDeviceToDisplay::$CTDTD_SUFFIXE))
			// {
				$deviceClss = new CozyTouchDevice(array());
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_URL, $device->deviceURL);
				$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, array());
				foreach ($device->states as $state)
				{
					// if (in_array($state->name,CozyTouchDeviceStateName::$CTDS1_NAME)
					// 		|| in_array($state->name,CozyTouchDeviceStateName::$CTDS2_NAME)
					// 		|| in_array($state->name,CozyTouchDeviceStateName::$CTDS4_NAME)
					// 		|| in_array($state->name,CozyTouchDeviceStateName::$CTDS5_NAME))
					// {
						$vartmp = $deviceClss->getVar(CozyTouchDeviceInfo::CTDI_STATES);
						$vartmp[] = $state;
						$deviceClss->setVar(CozyTouchDeviceInfo::CTDI_STATES,$vartmp);
					// }
				}
				if(substr($device->deviceURL, -1, 1)=="1")
				{
					$devices[explode("#",$device->deviceURL)[0]]=$deviceClss;
					
				}
				else
				{
					if(array_key_exists( explode("#",$device->deviceURL)[0], $devices )==true)
					{
						$sensors = $devices[explode("#",$device->deviceURL)[0]]->getSensors();
						$sensors[] = $deviceClss;
						$devices[explode("#",$device->deviceURL)[0]]->setVar(CozyTouchDeviceInfo::CTDI_SENSORS, $sensors);
					}
				}
			//}
		}
		$this->dataCollection = $devices;
	}
}
?>
