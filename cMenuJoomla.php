<?php

/* 
Plugin Name: Get Joomla Menu
Plugin URI: http://cuscosoft.com/
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

class cMenuJoomla{
	public $conex;
	public $prefix;
	public $menutype;
	public $version='1.0';
	public $sef='0';
	public $sef_rewrite='0';
	public $sef_suffix='0';
	public $urlJommla;
	public $subLevel;
	public $levelMenu;
		
	function get_menu_tree($menutype=null){
		//echo $menutype;
		if($menutype){
			$this->menutype=$menutype;
		}
		$result=$this->get_menu(0,$this->menutype);
		if($result){
			$datos.= '<ul>';
			$cuantos = mysql_num_rows($result);
			for($k=0;$k<$cuantos;$k++){
				$row=mysql_fetch_array($result);
				$datos.= '<li class="li'.$row['id'].'">';
				if($this->sef_rewrite=='1' && $this->sef=='1' && $this->sef_suffix=='1'){
					$datos.= '<a href="'.$this->urlJommla.''.$row['alias'].'.html">'.$row['name'].'</a>';
				}elseif($this->sef_rewrite=='1' && $this->sef=='1' && $this->sef_suffix=='0'){
					$datos.= '<a href="'.$this->urlJommla.''.$row['alias'].'">'.$row['name'].'</a>';
				}elseif($this->sef_rewrite=='0' && $this->sef=='1' && $this->sef_suffix=='0'){
					$datos.= '<a href="'.$this->urlJommla.'index.php/'.$row['alias'].'">'.$row['name'].'</a>';
				}elseif($this->sef_rewrite=='0' && $this->sef=='1' && $this->sef_suffix=='1'){
					$datos.= '<a href="'.$this->urlJommla.'index.php/'.$row['alias'].'.html">'.$row['name'].'</a>';
				}else{
					$datos.= '<a href="'.$this->urlJommla.$row['link'].'&Itemid='.$row['id'].'">'.$row['name'].'</a>';
				}
				$this->levelMenu=''.$row['alias'].'/';
				$this->subLevel='';
				$datos.= $this->get_sub_tree($row['id']);
				$datos.= '</li>';
			}
			$datos.= '</ul>';
		}
		return $datos;
	}
	function get_sub_tree($id_menu){
		//echo $id_menu;
		$result=$this->get_parent($id_menu);
		if($result){
			$datos.= '<ul class="children">';
			//echo $result;
			$cuantos = mysql_num_rows($result);
			for($c=0;$c<$cuantos;$c++){
				$row=mysql_fetch_array($result);
				$datos.= '<li class="li'.$row['id'].'">';
				if($this->sef_rewrite=='1' && $this->sef=='1' && $this->sef_suffix=='1'){
					$datos.= '<a href="'.$this->urlJommla.$this->levelMenu.$this->subLevel.$row['alias'].'.html">'.$row['name'].'</a>';
					if($this->is_parent($row['id'])){
						$this->subLevel.=''.$row['alias'].'/';
					}else{
						$this->subLevel='';
					}
				}elseif($this->sef_rewrite=='0' && $this->sef=='1' && $this->sef_suffix=='1'){
					$datos.= '<a href="'.$this->urlJommla.'index.php/'.$this->levelMenu.$this->subLevel.$row['alias'].'.html">'.$row['name'].'</a>';
					if($this->is_parent($row['id'])){
						$this->subLevel.=''.$row['alias'].'/';
					}else{
						$this->subLevel='';
					}
				}elseif($this->sef_rewrite=='1' && $this->sef=='1' && $this->sef_suffix=='0'){
					$datos.= '<a href="'.$this->urlJommla.$this->levelMenu.$this->subLevel.$row['alias'].'">'.$row['name'].'</a>';
					if($this->is_parent($row['id'])){
						$this->subLevel.=''.$row['alias'].'/';
					}else{
						$this->subLevel='';
					}
				}elseif($this->sef_rewrite=='0' && $this->sef=='1' && $this->sef_suffix=='0'){
					$datos.= '<a href="'.$this->urlJommla.'index.php/'.$this->levelMenu.$this->subLevel.$row['alias'].'">'.$row['name'].'</a>';
					if($this->is_parent($row['id'])){
						$this->subLevel.=''.$row['alias'].'/';
					}else{
						$this->subLevel='';
					}
				}else{
					$datos.= '<a href="'.$this->urlJommla.$row['link'].'&Itemid='.$row['id'].'">'.$row['name'].'</a>';
				}
				$datos.= $this->get_sub_tree($row['id']);
				///
				$datos.= '</li>';
			}
			
			//echo $datos;
			$datos.= '</ul>';
		}
		return $datos;
	}
	function convert_ul_children($result){
		//echo $id_menu;
		if($result){
			$datos.= '<ul class="children">';
			//echo $result;
			$cuantos = mysql_num_rows($result);
			for($c=0;$c<$cuantos;$c++){
				$row=mysql_fetch_array($result);
				$datos.= '<li class="li'.$row['id'].'">';
				if($this->sef_rewrite=='1' && $this->sef=='1' && $this->sef_suffix=='1'){
					$datos.= '<a href="'.$this->urlJommla.''.$row['alias'].'.html">'.$row['name'].'</a>';
				}elseif($this->sef_rewrite=='1' && $this->sef=='1' && $this->sef_suffix=='0'){
					$datos.= '<a href="'.$this->urlJommla.''.$row['alias'].'">'.$row['name'].'</a>';
				}elseif($this->sef_rewrite=='0' && $this->sef=='1' && $this->sef_suffix=='0'){
					$datos.= '<a href="'.$this->urlJommla.'index.php/'.$row['alias'].'">'.$row['name'].'</a>';
				}elseif($this->sef_rewrite=='0' && $this->sef=='1' && $this->sef_suffix=='1'){
					$datos.= '<a href="'.$this->urlJommla.'index.php/'.$row['alias'].'.html">'.$row['name'].'</a>';
				}else{
					$datos.= '<a href="'.$this->urlJommla.$row['link'].'&Itemid='.$row['id'].'">'.$row['name'].'</a>';
				}				
				$datos.= $this->get_sub_tree($row['id']);
				///
				$datos.= '</li>';
			}
			//echo $datos;
			$datos.= '</ul>';
		}
		return $datos;
	}
	function convert_ul($result){
		//echo $id_menu;
		if($result){
			$datos.= '<ul class="children">';
			//echo $result;
			$cuantos = mysql_num_rows($result);
			for($c=0;$c<$cuantos;$c++){
				$row=mysql_fetch_array($result);
				$datos.= '<li class="li'.$row['id'].'">';
				if($this->sef_rewrite=='1' && $this->sef=='1' && $this->sef_suffix=='1'){
					$datos.= '<a href="'.$this->urlJommla.''.$row['alias'].'.html">'.$row['name'].'</a>';
				}elseif($this->sef_rewrite=='1' && $this->sef=='1' && $this->sef_suffix=='0'){
					$datos.= '<a href="'.$this->urlJommla.''.$row['alias'].'">'.$row['name'].'</a>';
				}elseif($this->sef_rewrite=='0' && $this->sef=='1' && $this->sef_suffix=='0'){
					$datos.= '<a href="'.$this->urlJommla.'index.php/'.$row['alias'].'">'.$row['name'].'</a>';
				}elseif($this->sef_rewrite=='0' && $this->sef=='1' && $this->sef_suffix=='1'){
					$datos.= '<a href="'.$this->urlJommla.'index.php/'.$row['alias'].'.html">'.$row['name'].'</a>';
				}else{
					$datos.= '<a href="'.$this->urlJommla.$row['link'].'&Itemid='.$row['id'].'">'.$row['name'].'</a>';
				}
				///
				$datos.= '</li>';
			}
			//echo $datos;
			$datos.= '</ul>';
		}
		return $datos;
	}
	function get_menutype(){
		$sql = 'SELECT * FROM `'.$this->prefix.'menu_types`;';
        $result=mysql_query($sql,$this->conex);
        if(mysql_num_rows($result)){
            return $result;
        }else{
            return false; //return 'ERROR: ' . mysql_error();
        }
	}
	function is_parent($id_menu){
		$sql = 'SELECT * FROM `'.$this->prefix.'menu` where `parent`='.$id_menu.' ORDER BY `ordering` ASC;';
		//echo $sql;
        $result=mysql_query($sql,$this->conex);
        if(mysql_num_rows($result)){
            return true;
        }else{
            return false; //return 'ERROR: ' . mysql_error();
        }
	}
	function get_parent($id_menu){
		$sql = 'SELECT * FROM `'.$this->prefix.'menu` where `parent`='.$id_menu.' ORDER BY `ordering` ASC;';
		//echo $sql;
        $result=mysql_query($sql,$this->conex);
        if(mysql_num_rows($result)){
            return $result;
        }else{
            return false; //return 'ERROR: ' . mysql_error();
        }
	}
	function get_numSublevel($menutype=null){
		if($menutype){
			$this->menutype=$menutype;
		}		
		//numero de niveles del Menu
		$sql = 'SELECT MAX(`sublevel`) FROM `'.$this->prefix.'menu` where `menutype`=\''.$this->menutype.'\';';
		//echo $sql;
        $result=mysql_query($sql,$this->conex);
        if($row = mysql_fetch_array($result)){
            return $row[0];
        }else{
            return false; //return 'ERROR: ' . mysql_error();
        }
	}
	function get_all_menu($menutype=null){
		if($menutype){
			$this->menutype=$menutype;
		}
		$sql = 'SELECT * FROM `'.$this->prefix.'menu` where menutype=\''.$this->menutype.'\' and `published`=1;';
		//echo $sql;
        $result=mysql_query($sql,$this->conex);
        if(mysql_num_rows($result)){
            return $result;
        }else{
            return false; //return 'ERROR: ' . mysql_error();
        }
	}
	function get_menu($sublevel,$menutype=null){
		
		if($menutype){
			$this->menutype=$menutype;
		}
		$sql = 'SELECT * FROM `'.$this->prefix.'menu` where menutype=\''.$this->menutype.'\' and `published`=1 and sublevel=\''.$sublevel.'\'  ORDER BY `ordering` ASC;';
		//echo $sql;
        $result=mysql_query($sql,$this->conex);
        if(mysql_num_rows($result)){
            return $result;
        }else{
            return false; //return 'ERROR: ' . mysql_error();
        }
	}
	function get_menu_ul($menutype=null, $sublevel=0){
		
		if($menutype){
			$this->menutype=$menutype;
		}
		$sql = 'SELECT * FROM `'.$this->prefix.'menu` where menutype=\''.$this->menutype.'\' and `published`=1 and sublevel=\''.$sublevel.'\'  ORDER BY `ordering` ASC;';
		//echo $sql;
        $result=mysql_query($sql,$this->conex);
        if(mysql_num_rows($result)){
			return $this->convert_ul($result);
        }else{
            return false; //return 'ERROR: ' . mysql_error();
        }
	}
	
	function conex_joomla($host,$user,$pass,$bd){
		$conexion = mysql_connect($host,$user,$pass);
		$conecto=mysql_select_db($bd,$conexion);
		if($conecto){
			$this->conex=$conexion;
			return true;
		}else{
			return false;
		}
	}
	function conex_configuration($configuration){
		if(is_file($configuration)){
			include($configuration);
			$com=new JConfig();
			$host=$com->host;
			$user=$com->user;
			$pass=$com->password;
			$bd=$com->bd;
			$this->prefix=$com->dbprefix;
			$this->sef=$com->sef;
			$this->sef_rewrite=$com->sef_rewrite;
			$this->sef_suffix=$com->sef_suffix;
		
			$conexion = mysql_connect($host,$user,$pass);
			$conecto=mysql_select_db($bd,$conexion);
			if($conecto){
				$this->conex=$conexion;
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}
	
	function get_data_wordpress(){
		//get wp_config
	}
	function get_data_wordpress2(){
		//get wp_config
	}
	
}
?>
