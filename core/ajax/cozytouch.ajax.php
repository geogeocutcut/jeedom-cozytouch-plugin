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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');
    if (!class_exists('CozyTouchManager')) {
        require_once dirname(__FILE__) . "/../class/CozyTouchManager.class.php";
    }
    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
    // action qui permet d'obtenir l'ensemble des eqLogic
    if (init('action') == 'getAll') {
        $eqLogics = eqLogic::byType('cozytouch');
        // la liste des Ã©quipements
        foreach ($eqLogics as $eqLogic) {
            $data['id'] = $eqLogic->getId();
            $data['humanSidebar'] = $eqLogic->getHumanName(true, false);
            $data['humanContainer'] = $eqLogic->getHumanName(true, true);
            $return[] = $data;
        }
        ajax::success($return);
    }
    // action qui permet d'effectuer la sauvegarde des donnÃ©es en asynchrone
    if (init('action') == 'saveStack') {
        $params = init('params');
        ajax::success(cozytouch::saveStack($params));
    }
  
    if (init('action') == 'syncWithCozyTouch') {
        CozyTouchManager::syncWithCozyTouch();
        ajax::success();
    }

    throw new Exception(__('Aucune methode correspondante à  : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} 
catch (Exception $e) {
    log::add('cozytouch', 'error', 'Error page de configuration : '.$e->getMessage());
			
    ajax::error(displayExeption($e), $e->getCode());
}
?>