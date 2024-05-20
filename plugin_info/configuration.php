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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>
<form class="form-horizontal">
    <fieldset>
      <div class="form-group">
      <label class="col-md-4 control-label">{{Nom d'utilisateur}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Nom utilisateur compte Cozytouch}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="username"/>
          </div>
      </div>
      <div class="form-group">
      <label class="col-md-4 control-label">{{Mot de passe}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Mot de passe compte Cozytouch}}"></i></sup>
      </label>
      <div class="col-md-4" style="display:flex;">
        <input type="password" class="configKey form-control" data-l1key="password"/>
		<a class="btn btn-danger  " id="bt_show_pass"><i class="fas fa-eye"></i></a>
        </div>
      </div>
  </fieldset>
</form>
<script>
$("#bt_show_pass").on('mousedown', function(){
    $("input[data-l1key='password']").attr('type', 'text');
    $(this).find("i").removeClass('fa-eye').addClass('fa-eye-slash')

})
$("#bt_show_pass").on('mouseup mouseleave', function(){
    $("input[data-l1key='password']").attr('type', 'password');
    $(this).find("i").removeClass('fa-eye-slash').addClass('fa-eye')
})
</script>
