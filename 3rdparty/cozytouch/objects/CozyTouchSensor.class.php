<?php

require_once dirname(__FILE__) .'/../constants/CozyTouchConstants.class.php';

/**
* Class CozyTouchPlace
*
*/
class CozyTouchSensor extends CozyTouchObject
{

    public function __construct($array)
    {
        parent::__construct($array);
    }

    /**
    * @return string
    * @brief returns home's name
    */
    public function getId()
    {
        return $this->getVar(CozyTouchDeviceSensorInfo::CTDSI_OID);
    }
    
    public function getURL()
    {
    	return $this->getVar(CozyTouchDeviceSensorInfo::CTDSI_URL);
    }

    public function getStates()
    {
        return $this->getVar(CozyTouchDeviceSensorInfo::CTDSI_STATES, array());
    }
}
?>
