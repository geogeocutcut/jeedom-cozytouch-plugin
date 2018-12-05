<?php
class CozyTouchDeviceExecute
{
    public $eqLogic = null;

    public function __construct($eqLogic) 
    {
        $this->eqLogic= $eqLogic;
    }
    
    protected function genericApplyCommand($cmds)
	{
        $device_url=$this->eqLogic->getConfiguration('device_url');
		$actions = array();

        $action = new CozyTouchAction();
        $action->deviceURL = $device_url;
        
        foreach($cmds as $cmd)	
        {
            $command = new CozyTouchCommand();
            $command->name=$cmd['name'];
            if($cmd['values'] != null)
            {
                $command->parameters[]=$cmd['values'];
            }

            $action->commands[]=$command;
        }

        $actions[]=$action;
			
	
		$commandsMsg = new CozyTouchCommands();
		$commandsMsg->label= "Mise a jour du device";
		$commandsMsg->actions=$actions;
	
		$clientApi = $this->eqLogic->getClient();
		$post_data = $commandsMsg;
		$clientApi->applyCommand($post_data);
	}
}

?>