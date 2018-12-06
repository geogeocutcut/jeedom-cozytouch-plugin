<?php

require_once('CozyTouchObject.php');


/**
* Class CozyTouchPlace
*
*/
class CozyTouchState extends CozyTouchObject
{

    public function __construct($array)
    {
        parent::__construct($array);
    }

    
    public function getName()
    {
    	return $this->getVar(CozyTouchDeviceStateInfo::CTDSI_NAME);
    }

    public function getValue()
    {
        return $this->getVar(CozyTouchDeviceStateInfo::CTDSI_VALUE);
    }
}
?>
