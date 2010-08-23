<?php
/* 
Plugin Name: Get Joomla Menu
Plugin URI: http://cuscosoft.com/get-menu-joomla/
Version: v1.00
Author: <a href="http://www.cuscosoft.com/">Roger Torres</a> - Email: <a href="mailto:roypool@gmail.com">roypool@gmail.com</a>
Description: Get Menú Joomla, obtiene un menú de joomla y lo muestra en wordpress este plugin esta especialmente diseñado para aquellos que usan joomla y wordpress de forma complementaria, con este plugin podrá mostrar un menú de joomla en wordpress como widget o incrustado en el template. 

	Copyright 2010 Roger Torres - E-Mail: roypool@gmail.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Paul Roger Torres Silva, roypool@gmail.com, Cusco - Peru
*/

function get_menu_joomla_init() {
	get_menu_joomla_getAdminOptions();
}
function get_menu_joomla_getAdminOptions() {
	$confic=$_SERVER['DOCUMENT_ROOT'].'/configuration.php';
	$url='httml://'.$_SERVER['HTTP_HOST'].'/';
	
	$devloungeAdminOptions = array(
		'menutype' => 'mainmenu',
		'tree' => '1',
		'urlJommla' => $url,
		'configuration' => '1',
		'joomlaConfiguration' => $confic,
		'host' => 'localhost',
		'user' => 'root',
		'pass' => 'root',
		'bd' => 'database_joomla',
		'prefix' => 'jos_',
		'sef' => '0',
		'sef_rewrite' => '0',
		'sef_suffix' => '0'
	);
	
	$devOptions = get_option('NombreAdminOptionsName');
	if (!empty($devOptions)) {
		foreach ($devOptions as $key => $option)
			$devloungeAdminOptions[$key] = $option;
	}				
	update_option('NombreAdminOptionsName', $devloungeAdminOptions);
	return $devloungeAdminOptions;
}

function get_menu_joomla_printAdminPage() {
	///
	$devOptions = get_menu_joomla_getAdminOptions();
	///
		
	if (isset($_POST['update_get_menu_joomlaPluginSettings'])) { 
		if (isset($_POST['menutype'])) {
			$devOptions['menutype'] = $_POST['menutype'];
		}
		if (isset($_POST['tree'])) {
			$devOptions['tree'] = $_POST['tree'];
		}
		if (isset($_POST['urlJommla'])) {
			$devOptions['urlJommla'] = $_POST['urlJommla'];
		}	
		if (isset($_POST['configuration'])) {
			$devOptions['configuration'] = $_POST['configuration'];
		}		
		if (isset($_POST['joomlaConfiguration'])) {
			$devOptions['joomlaConfiguration'] = $_POST['joomlaConfiguration'];
		}		
		if (isset($_POST['host'])) {
			$devOptions['host'] = $_POST['host'];
		}		
		if (isset($_POST['user'])) {
			$devOptions['user'] = $_POST['user'];
		}		
		if (isset($_POST['pass'])) {
			$devOptions['pass'] = $_POST['pass'];
		}		
		if (isset($_POST['bd'])) {
			$devOptions['bd'] = $_POST['bd'];
		}		
		if (isset($_POST['prefix'])) {
			$devOptions['prefix'] = $_POST['prefix'];
		}		
		if (isset($_POST['sef'])) {
			$devOptions['sef'] = $_POST['sef'];
		}		
		if (isset($_POST['sef_rewrite'])) {
			$devOptions['sef_rewrite'] = $_POST['sef_rewrite'];
		}		
		if (isset($_POST['sef_suffix'])) {
			$devOptions['sef_suffix'] = $_POST['sef_suffix'];
		}
		update_option('NombreAdminOptionsName', $devOptions);
		
		?>
<div class="updated">
  <p><strong>
    <?php _e("Settings Updated."); ?>
    </strong></p>
</div>

<?php
	}
	
	include('cMenuJoomla.php');
	$joomla=new cMenuJoomla();
	$joomla->prefix=$devOptions['prefix'];
	$joomla->urlJommla=$devOptions['urlJommla'];
	$joomla->sef=$devOptions['sef'];
	$joomla->sef_rewrite=$devOptions['sef_rewrite'];
	$joomla->sef_suffix=$devOptions['sef_suffix'];
	//$joomla->sef_rewrite=$params->sef_rewrite;
	if($devOptions['configuration']==1 && $devOptions['configuration']){
		if($joomla->conex_configuration($devOptions['configuration'])){
			$conexion=true;
		}else{
			$conexion=false;
		}
	}else{
		if($joomla->conex_joomla($devOptions['host'],$devOptions['user'],$devOptions['pass'],$devOptions['bd'])){
			$conexion=true;
		}else{
			$conexion=false;
		}
	}
	?>
<div class=wrap>
  <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <h2>Get Menu Joomla - Setting</h2>
    <? 
	if($conexion){
		?><div class="updated"><?php _e("Perfect conection."); ?></div><?
	}else{
		?><div class="error"><?php _e("Error Conextion."); ?></div><?
	} 
	?>
    <h3>Conection configuration </h3>
    <div class="datos">
      <p>
        <label for="configuration1"> <b>I know where is Configuration.php:</b>
          <input type="radio" id="configuration1" name="configuration" value="1" <? if($devOptions['configuration']=='1') echo 'checked="checked"'; ?>  />
        </label>
      </p>
      <div class="contenido">
        <label for="devloungeHeader_yes"> physical address Configuration.php:
          <input type="text" id="joomlaConfiguration" name="joomlaConfiguration" size="50" value="<? echo apply_filters('format_to_edit',$devOptions['joomlaConfiguration']); ?>"  /><span class="description">Example: /home/usuario/public_html/configuration.php</span>
        </label>
      </div>
      <p>
        <label for="configuration"> <b>I don't know where is Configuration.php:</b>
          <input type="radio" id="configuration" name="configuration" value="0" <? if($devOptions['configuration']=='0') echo 'checked="checked"'; ?> />
        </label>
      </p>
      <div  class="contenido">
        <p>
          <label for="host"> Host:
            <input type="text" id="host" name="host" value="<? echo $devOptions['host']; ?>"  /><span class="description">Example: localhost</span>
          </label>
        </p>
        <p>
          <label for="user"> Database User:
            <input type="text" id="user" name="user" value="<? echo $devOptions['user']; ?>"  />
          </label>
        </p>
        <p>
          <label for="pass"> Database Password:
            <input type="text" id="pass" name="pass" value="<? echo $devOptions['pass']; ?>"  />
          </label>
        </p>
        <p>
          <label for="bd"> Database Name:
            <input type="text" id="bd" name="bd" value="<? echo $devOptions['bd']; ?>"  />
          </label>
        </p>
        <p>
          <label for="prefix"> Table joomla Prefix:
            <input type="text" id="prefix" name="prefix" value="<? echo $devOptions['prefix']; ?>"  /><span class="description">Example: jos_</span>
          </label>
        </p>
        <p>Search Engine Friendly URLs:<br>
          <label for="sef1"> Yes:
            <input type="radio" id="sef1" name="sef" value="1" <? if($devOptions['sef']=='1') echo 'checked="checked"'; ?> />
          </label>
          <label for="sef"> No:
            <input type="radio" id="sef" name="sef" value="0" <? if($devOptions['sef']=='0') echo 'checked="checked"'; ?> />
          </label>
        </p>
        <p> Use Apache mod_rewrite:<br>
          <label for="sef_rewrite1"> Yes:
            <input type="radio" id="sef_rewrite1" name="sef_rewrite" value="1" <? if($devOptions['sef_rewrite']=='1') echo 'checked="checked"'; ?> />
          </label>
          <label for="sef_rewrite"> No:
            <input type="radio" id="sef_rewrite" name="sef_rewrite" value="0" <? if($devOptions['sef_rewrite']=='0') echo 'checked="checked"'; ?> />
          </label>
        </p>
        <p> Add suffix to URLs:<br>
          <label for="sef_suffix1"> Yes:
            <input type="radio" id="sef_suffix1" name="sef_suffix" value="1" <? if($devOptions['sef_suffix']=='1') echo 'checked="checked"'; ?> />
          </label>
          <label for="sef_suffix"> No:
            <input type="radio" id="sef_suffix" name="sef_suffix" value="0" <? if($devOptions['sef_suffix']=='0') echo 'checked="checked"'; ?> />
          </label>
        </p>
      </div>
    </div>
      <h3>Menu Config</h3>
      <div class="datos">
      <p>
        <label for="urlJommla"> URL Joomla:
          <input type="text" id="urlJommla" name="urlJommla" value="<? echo $devOptions['urlJommla']; ?>" size="50"  /><span class="description">Example: http://cuscosoft.com/</span>
        </label>
      </p>
      <p>
        <label for="menutype"> Menu Type:
        <? if($conexion){ ?>
        <select name="menutype">
		  <? 
          $result=$joomla->get_menutype();
          $cuantos=mysql_num_rows($result);
          for($k=0;$k<$cuantos;$k++){
            $row=mysql_fetch_array($result);
            ?>
				<option value="<? echo $row['menutype']; ?>" <? if($devOptions['menutype']==$row['menutype']){ echo 'selected="selected"';} ?>><? echo $row['title']; ?></option>
			<?
          }

          ?>
      	</select>
        <? }else{ ?>
        	<span class="description">No conexion</span>
        <? } ?>    
        </label>
      </p>
      <p> Always show sub-menu Items:<br>
        <label for="tree1"> Yes:
          <input type="radio" id="tree1" name="tree" value="1" <? if($devOptions['tree']=='1') echo 'checked="checked"'; ?> />
        </label>
        <label for="tree"> No:
          <input type="radio" id="tree" name="tree" value="0" <? if($devOptions['tree']=='0') echo 'checked="checked"'; ?> />
        </label>
      </p>
    </div>
    <? //}else{ ?>
    <? //} ?>
    <div class="submit">
      <input type="submit" name="update_get_menu_joomlaPluginSettings" value="<?php _e('Update Settings') ?>" />
    </div>
  </form>
</div>
<style>
.contenido{
	margin-left:50px;
}
.datos{
	margin-left:25px;
}
</style>
<?
}

if (!function_exists("get_menu_joomla_DevloungePluginSeries_ap")) {
	function get_menu_joomla_DevloungePluginSeries_ap() {
		if (function_exists('add_options_page')) {
			add_options_page('Get Menu Joomla', 'Get Menu Joomla', 9, basename(__FILE__), 'get_menu_joomla_printAdminPage');
		}
	}	
}
function get_menu_joomla($titulo){
	$devOptions = get_menu_joomla_getAdminOptions();
	
	include('cMenuJoomla.php');
	$joomla=new cMenuJoomla();
	$joomla->prefix=$devOptions['prefix'];
	$joomla->urlJommla=$devOptions['urlJommla'];
	$joomla->sef=$devOptions['sef'];
	$joomla->sef_rewrite=$devOptions['sef_rewrite'];
	$joomla->sef_suffix=$devOptions['sef_suffix'];
	//$joomla->sef_rewrite=$params->sef_rewrite;
	if($devOptions['configuration']==1 && $devOptions['configuration']){
		if($joomla->conex_configuration($devOptions['configuration'])){
			$conexion=true;
		}else{
			$conexion=false;
		}
	}else{
		if($joomla->conex_joomla($devOptions['host'],$devOptions['user'],$devOptions['pass'],$devOptions['bd'])){
			$conexion=true;
		}else{
			$conexion=false;
		}
	}
	if($conexion){
		if($titulo)
			$titulo='Get Menu Joomla';
		echo '<h3 class="widget-title">'.$titulo.'</h3>';
		if($devOptions['tree']){
			echo $joomla->get_menu_tree($devOptions['menutype']);
		}else{
			echo $joomla->get_menu_ul($devOptions['menutype']);
		}
	}
}
function get_menu_joomla_init_widget() {
	register_sidebar_widget("Get Menu Joomla Widget", "get_menu_joomla");
}

	add_action("plugins_loaded", "get_menu_joomla_init_widget");
	add_action('admin_menu', 'get_menu_joomla_DevloungePluginSeries_ap');
	add_action('get-menu-joomla/get_menu_joomla.php','get_menu_joomla_init');
	
?>
