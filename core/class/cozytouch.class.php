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



class cozytouch extends eqLogic {
    /******************************* Attributs *******************************/ 
    /* Ajouter ici toutes vos variables propre é votre classe */

	private static $_client = null;
	
    /***************************** Methode static ****************************/ 

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
	
    
    public static function cron15() {
    	CozyTouchHelper::refresh_all();
    }
    
    /*************************** Methode d'instance **************************/ 
 

    /************************** Pile de mise  jour **************************/ 
    
    /* fonction permettant d'initialiser la pile 
     * plugin: le nom de votre plugin
     * action: l'action qui sera utilisé dans le fichier ajax du pulgin 
     * callback: fonction appelée coté client(JS) pour mettre é jour l'affichage 
     */ 
    public function initStackData() {
        nodejs::pushUpdate('cozytouch::initStackDataEqLogic', array('plugin' => 'cozytouch', 'action' => 'saveStack', 'callback' => 'displayEqLogic'));
    }
    
    /* fonnction permettant d'envoyer un nouvel équipement pour sauvegarde et affichage, 
     * les données sont envoyé au client(JS) pour étre traité de maniére asynchrone
     * Entrée: 
     *      - $params: variable contenant les paramétres eqLogic
     */
    public function stackData($params) {
        if(is_object($params)) {
            $paramsArray = utils::o2a($params);
        }
        nodejs::pushUpdate('cozytouch::stackDataEqLogic', $paramsArray);
	}
	
	public static function syncWithCozyTouch() 
	{
		CozyTouchHelper::syncWithCozyTouch();
		
	}
    /* fonction appelée pour la sauvegarde asynchrone
     * Entrée: 
     *      - $params: variable contenant les paramètres eqLogic
     */
    public function saveStack($params) {
        // inserer ici le traitement pour sauvegarde de vos données en asynchrone
        
    }

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
    	$eqLogic = $this->getEqLogic();
		$refresh = true;
		$device_type = $eqLogic->getConfiguration('device_model');
    	switch($device_type){
			case CozyTouchDeviceToDisplay::CTDTD_ATLANTICELECTRICHEATER:
				CozyTouchAtlanticHeatSystem::execute($this);
    			break;
    			
    		case CozyTouchDeviceToDisplay::CTDTD_ATLANTICHOTWATER :
				CozyTouchAtlanticHotWater::execute($this);
    			break;
    			
    	}
    }

    /***************************** Getteur/Setteur ***************************/ 

    
}

?>
