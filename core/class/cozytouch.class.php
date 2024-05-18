<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/******************************* Includes *******************************/ 
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

require_once dirname(__FILE__) . '/CozyTouchManager.class.php';

class cozytouch extends eqLogic {
    /******************************* Attributs *******************************/ 
    /* Ajouter ici toutes vos variables propre à votre classe */

	private static $_client = null;
	
    /***************************** Methode static ****************************/ 
    public static $_widgetPossibility = array('custom' => true);

    public static function templateWidget() {
        $return = array('info' => array('string' => array(), 'numeric' => array(), 'binary' => array()), 'action' => array('slider' => array()));
        $return['info']['string']['vmc'] = array(
			'template' => 'tmplmultistate',
			'test' => array(
				array('operation' => "#value# == 'boost'", 'state_light' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_boost.png">',
                        'state_dark' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_boost.png">'),
				array('operation' => "#value# == 'refresh'", 'state_light' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_refresh.png">',
                        'state_dark' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_refresh.png">'),
				array('operation' => "#value# == 'prog'", 'state_light' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_prog.png">',
                        'state_dark' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_prog.png">'),
				array('operation' => "#value# == 'manual'", 'state_light' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_manual.png">',
                        'state_dark' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_manual.png">'),
				array('operation' => "#value# == 'high'", 'state_light' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_high.png">',
                        'state_dark' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_high.png">'),
				array('operation' => "#value# == 'auto'", 'state_light' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_auto.png">',
                        'state_dark' => '<img style="width:100px;min-height:100px;" src="plugins/cozytouch/core/template/images/vent_auto.png">')
			)
		);
        $return['info']['string']['hotwatermode'] = array(
			'template' => 'tmplmultistate',
			'test' => array(
				array('operation' => "#value# == 'off'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_off.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_off.png">'),
				array('operation' => "#value# == 'eco'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_eco.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_eco.png">'),
				array('operation' => "#value# == 'prog'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_prog.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_prog.png">'),
				array('operation' => "#value# == 'on'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_on.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_on.png">')
			)
		);
        $return['info']['string']['heatmode'] = array(
			'template' => 'tmplmultistate',
			'test' => array(
				array('operation' => "#value# == 'off'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_arret.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_arret.png">'),
				array('operation' => "#value# == 'frostprotection'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_hg.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_hg.png">'),
				array('operation' => "#value# == 'eco'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_eco.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_eco.png">'),
				array('operation' => "#value# == 'comfort-2'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_confort-2.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_confort-2.png">'),
				array('operation' => "#value# == 'comfort-1'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_confort-1.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_confort-1.png">'),
				array('operation' => "#value# == 'comfort'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_confort.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/radiateur_confort.png">')
			)
		);
        $return['info']['string']['zonectlzonemode'] = array(
			'template' => 'tmplmultistate',
			'test' => array(
				array('operation' => "#value# == 'off'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_off.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_off.png">'),
				array('operation' => "#value# == 'heating_manu'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_heat_manu.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_heat_manu.png">'),
				array('operation' => "#value# == 'heating_prog'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_heat_prog.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_heat_prog.png">'),
				array('operation' => "#value# == 'cooling_manu'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_cool_manu.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_cool_manu.png">'),
				array('operation' => "#value# == 'cooling_prog'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_cool_prog.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_cool_prog.png">'),
				array('operation' => "#value# == ''", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_off.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrlzone_off.png">')
			)
		);
        $return['info']['string']['zonetctlmode'] = array(
			'template' => 'tmplmultistate',
			'test' => array(
				array('operation' => "#value# == 'stop'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrl_off.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrl_off.png">'),
				array('operation' => "#value# == 'heating'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrl_heat.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrl_heat.png">'),
				array('operation' => "#value# == 'cooling'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrl_cool.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrl_cool.png">'),
				array('operation' => "#value# == 'drying'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrl_dry.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrl_dry.png">'),
				array('operation' => "#value# == 'auto'", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrl_auto.png">',
                        'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/zonectrl_auto.png">')
			)
		);
        $return['info']['binary']['connect'] = array(
			'template' => 'tmplicon',
            'replace' => array(
				'#_icon_on_#' => '<i class=\'fa fa-signal\'></i>',
				'#_icon_off_#' => '<i class=\'fa fa-times\'></i>'
			)
        );
        $return['info']['binary']['hotwater_onoff'] = array(
            'template' => 'tmplimg',
            'replace' => array('#_img_light_on_#' => '<img width="100px" height="164px" src="plugins/cozytouch/core/template/images/hotwater_on.png">',
                               '#_img_dark_on_#' => '<img width="100px" height="164px" src="plugins/cozytouch/core/template/images/hotwater_on.png">',
                               '#_img_light_off_#' => '<img width="100px" height="164px" src="plugins/cozytouch/core/template/images/hotwater_off.png">',
                               '#_img_dark_off_#' => '<img width="100px" height="164px" src="plugins/cozytouch/core/template/images/hotwater_off.png">'
			)
        );
		$return['info']['numeric']['hotwater'] = array(
			'template' => 'tmplmultistate',
			'test' => array(
				array('operation' => "#value# <10", 'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_0.png">', 'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_0.png">'),
				array('operation' => "#value# >= 10 && #value# < 20",'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_1.png">', 'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_1.png">'),
				array('operation' => "#value# >= 20 && #value# < 30",'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_3.png">', 'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_3.png">'),
				array('operation' => "#value# >= 30 && #value# < 40",'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_4.png">', 'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_4.png">'),
				array('operation' => "#value# >= 40 && #value# < 50",'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_5.png">', 'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_5.png">'),
				array('operation' => "#value# >= 50 && #value# < 60",'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_6.png">', 'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_6.png">'),
				array('operation' => "#value# >= 60 && #value# < 70",'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_7.png">', 'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_7.png">'),
				array('operation' => "#value# >= 70 && #value# < 80",'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_8.png">', 'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_8.png">'),
				array('operation' => "#value# >= 80 && #value# < 90",'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_9.png">', 'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_9.png">'),
				array('operation' => "#value# >= 90",'state_light' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_10.png">', 'state_dark' => '<img style="width:80px;height: 140px;" src="plugins/cozytouch/core/template/images/hotwater_temp_10.png">'),
			)
		);
        return $return;
    }

    public static function getConfigForCommunity() {
        $update=update::byTypeAndLogicalId('plugin',__CLASS__);
        $ver=$update->getLocalVersion();
        $conf=$update->getConfiguration();
        $CommunityInfo="== Jeedom ".jeedom::version()." sur ".trim(shell_exec("lsb_release -d -s")).'/'.trim(shell_exec('dpkg --print-architecture')).'/'.trim(shell_exec('arch')).'/'.trim(shell_exec('getconf LONG_BIT'))."bits aka '".jeedom::getHardwareName()."' avec nodeJS ".trim(shell_exec('node -v'))." NPM " . trim(shell_exec("npm -v")) . " et jsonrpc:".config::byKey('api::core::jsonrpc::mode', 'core', 'enable')." et ".__CLASS__." (".$conf['version'].") ".$ver." (avant:".config::byKey('previousVersion',__CLASS__,'inconnu',true).')';
        return $CommunityInfo;
    }
    public static function cozyRefresh() {
        CozyTouchManager::refresh_all();
    }	
    /*
    // Fonction exécutée automatiquement toutes les minutes par Jeedom
    public static function cron() {

    }
    */

    /*
    // Fonction exécutée automatiquement toutes les heures par Jeedom
    public static function cronHourly() {

    }
    */

    /*
    // Fonction exécutée automatiquement tous les jours par Jeedom
    public static function cronDayly() {

    }
    */

    /*
    public static function cron15() {
        CozyTouchManager::refresh_all();
    }
    */
    public static function postConfig_password($value) {
        $client = CozytouchManager::getClient();
		$client->userPassword = $value;
		config::save('atlantic_token', '','cozytouch');
		config::save('atlantic_token_expire', 0,'cozytouch');
    }

	public static function postConfig_username($value) {
        $client = CozytouchManager::getClient();
		$client->userId = $value;
		config::save('atlantic_token', '','cozytouch');
		config::save('atlantic_token_expire', 0,'cozytouch');
    }

    /************************** Pile de mise  jour **************************/ 
    
    /* fonction permettant d'initialiser la pile 
     * plugin: le nom de votre plugin
     * action: l'action qui sera utilisé dans le fichier ajax du pulgin 
     * callback: fonction appelée coté client(JS) pour mettre é jour l'affichage 
     */ 
    public static function initStackData() {
        nodejs::pushUpdate('cozytouch::initStackDataEqLogic', array('plugin' => 'cozytouch', 'action' => 'saveStack', 'callback' => 'displayEqLogic'));
    }
    
    /* fonnction permettant d'envoyer un nouvel équipement pour sauvegarde et affichage, 
     * les données sont envoyé au client(JS) pour étre traité de maniére asynchrone
     * Entrée: 
     *      - $params: variable contenant les paramétres eqLogic
     */
    public static function stackData($params) {
        if(is_object($params)) {
            $paramsArray = utils::o2a($params);
        }
        nodejs::pushUpdate('cozytouch::stackDataEqLogic', $paramsArray);
	}
	
    /* fonction appelée pour la sauvegarde asynchrone
     * Entrée: 
     *      - $params: variable contenant les paramètres eqLogic
     */
    public static function saveStack($params) {
        // inserer ici le traitement pour sauvegarde de vos données en asynchrone
        
    }

    /*************************** Methode d'instance **************************/

    /* fonction appelée avant le début de la séquence de sauvegarde */
    public function preSave() {
    }

    /* fonction appelée pendant la séquence de sauvegarde avant l'insertion 
     * dans la base de données pour une mise à jour d'une entrée */
    public function preUpdate() {
        
    }

    /* fonction appelée pendant la séquence de sauvegarde après l'insertion 
     * dans la base de données pour une mise à jour d'une entrée */
    public function postUpdate() {
        
    }

    /* fonction appelée pendant la séquence de sauvegarde avant l'insertion 
     * dans la base de données pour une nouvelle entrée */
    public function preInsert() {

    }

    /* fonction appelée pendant la séquence de sauvegarde après l'insertion 
     * dans la base de données pour une nouvelle entrée */
    public function postInsert() {
        
    }

    /* fonction appelée après la fin de la séquence de sauvegarde */
    public function postSave() {
    }

    /* fonction appelée avant l'effacement d'une entrée */
    public function preRemove() {
        
    }

    /* fonnction appelée aprés l'effacement d'une entrée */
    public function postRemove() {
        
    }
    
    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class cozytouchCmd extends cmd {
    /******************************* Attributs *******************************/ 
    /* Ajouter ici toutes vos variables propre é votre classe */

    /***************************** Methode static ****************************/ 

    /*************************** Methode d'instance **************************/ 

    /* Non obligatoire permet de demander de ne pas supprimer les commandes méme si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
    public function dontRemoveCmd() {
        return true;
    }
    */

    public function execute($_options = array()) {
        CozytouchManager::execute($this,$_options);
    }

    /***************************** Getteur/Setteur ***************************/ 

    
}

