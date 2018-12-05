<?php
class CozyTouchDeviceExecute
{
    protected static function genericApplyCommand($device_url,$cmds)
	{
	
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
	
		$clientApi = $eqLogic->getClient();
		$post_data = $commandsMsg;//'setOperatingMode'
		$clientApi->applyCommand($post_data);
	}
}

?>