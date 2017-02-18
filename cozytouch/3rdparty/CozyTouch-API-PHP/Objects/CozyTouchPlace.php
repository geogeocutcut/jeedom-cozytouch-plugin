<?php

require_once('CozyTouchObject.php');


/**
* Class CozyTouchPlace
*
*/
class CozyTouchPlace extends CozyTouchObject
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
        return $this->getVar(CozyTouchPlaceInfo::CTPI_OID);
    }
    
    public function getName()
    {
    	return $this->getVar(CozyTouchPlaceInfo::CTPI_NAME);
    }

    public function getDevices()
    {
        return $this->getVar(CozyTouchPlaceInfo::CTPI_DEVICES, array());
    }
}
?>
