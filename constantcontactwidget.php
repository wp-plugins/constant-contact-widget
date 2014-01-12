<?php
/*
Plugin Name: Constant Contact Widget
Plugin URI: http://memberfind.me
Description: Constant Contant widget for submitting email address
Version: 1.5
Author: SourceFound
Author URI: http://memberfind.me
License: GPL2
*/

/*  Copyright 2013  SOURCEFOUND INC.  (email : info@sourcefound.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (class_exists('WP_Widget'))
	add_action('widgets_init','sf_constantcontact_widget');
if (is_admin()) {
	add_action('wp_ajax_nopriv_constantcontactadd','sf_constantcontact_ajax');
	add_action('wp_ajax_constantcontactadd','sf_constantcontact_ajax');
	add_action('admin_menu','sf_constantcontact_menu');
	add_action('admin_init','sf_constantcontact_admin');
}

function sf_constantcontact_ajax() {
	ob_clean();
	$set=get_option('sf_mcc');
	if (empty($set['log'])||empty($set['pwd']))
		echo __('Plugin settings incomplete');
	else if (empty($_POST['grp']))
		echo __('No contact list specified');
	else if (empty($_POST['eml']))
		echo __('No email provided');
	else {
		$rsp=wp_remote_get("http://ccprod.roving.com/roving/wdk/API_AddSiteVisitor.jsp?"
			.'loginName='.urlencode($set['log'])
			.'&loginPassword='.urlencode($set['pwd'])
			.'&ea='.urlencode($_POST['eml'])
			.'&ic='.urlencode($_POST['grp'])
			.(empty($_POST['fnm'])?'':('&First_Name='.urlencode(strip_tags($_POST['fnm']))))
			.(empty($_POST['lnm'])?'':('&Last_Name='.urlencode(strip_tags($_POST['lnm'])))));
		if (is_wp_error($rsp))
			echo __('Could not connect to Constant Contact');
		else {
			$rsp=explode("\n",$rsp['body']);
			if (intval($rsp[0]))
				echo count($rsp)>1?$rsp[1]:(intval($rsp[0])==400?__('Constant Contact username/password not accepted'):__('Constant Contact error'));
		}
	}
	die();
}
function sf_constantcontact_widget() {
	register_widget('sf_widget_constantcontact');
}
function sf_constantcontact_admin() {
	register_setting('sf_constantcontact_group','sf_mcc','sf_constantcontact_validate');
}
function sf_constantcontact_menu() {
	add_options_page('Constant Contact Settings','Constant Contact','manage_options','sf_constantcontact_options','sf_constantcontact_options');
}
function sf_constantcontact_options() {
	if (!current_user_can('manage_options'))  {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	echo '<div class="wrap"><h2>Constant Contact Settings</h2>'
		.'<form action="options.php" method="post">';
	settings_fields("sf_constantcontact_group");
	$set=get_option('sf_mcc');
	echo '<table class="form-table">'
		.'<tr valign="top"><th scope="row">Constant Contact Username</th><td><input type="text" name="sf_mcc[log]" value="'.(isset($set['log'])?$set['log']:'').'" /></td></tr>'
		.'<tr valign="top"><th scope="row">Constant Contact Password</th><td><input type="password" name="sf_mcc[pwd]" value="'.(isset($set['pwd'])?$set['pwd']:'').'" /></td></tr>'
		.'</table>'
		.'<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save"></p>'
		.'</form></div>';
}
function sf_constantcontact_validate($in) {
	$in['log']=trim($in['log']);
	$in['pwd']=trim($in['pwd']);
	return $in;
}

if (class_exists('WP_Widget')) { class sf_widget_constantcontact extends WP_Widget {
	public function __construct() {
		parent::__construct('sf_widget_constantcontact','Constant Contact',array('description'=>'Email subscription widget'));
	}
	public function widget($args,$instance) {
		extract($args);
		$id=str_replace('-','_',$this->id);
		$title=apply_filters('widget_title',$instance['title']);
		if (empty($title))
			echo str_replace('widget_sf_widget_constantcontact','widget_sf_widget_constantcontact widget_no_title',$before_widget);
		else
			echo $before_widget;
		if (!empty($title))
			echo $before_title.$title.$after_title;
		echo '<div id="'.$id.'_form">'
			.(empty($instance['txt'])?'':('<p>'.$instance['txt'].'</p>'))
			.'<input type="hidden" name="grp" value="'.esc_attr($instance['grp']).'" />'
			.(empty($instance['nam'])
				?('<input type="text" name="eml" class="input" placeholder="'.__('Email').'" />')
				:('<label for="fnm">'.__('First Name').'</label><input type="text" name="fnm" class="input" />'
				.'<label for="lnm">'.__('Last Name').'</label><input type="text" name="lnm" class="input" />'
				.'<label for="eml">'.__('Email').'</label><input type="text" name="eml" class="input" />'))
			.'<input type="submit" value="'.esc_attr($instance['btn']).'" onclick="'.$id.'_submit(this.parentNode)" />'
			.'</div>'
			.'<script>function '.$id.'_submit(n){'
				.'for(var i=n.firstChild,eml=false,val=["action=constantcontactadd"];i;i=i.nextSibling)if(!(i.nodeName!="INPUT"||!i.name)){if(!(i.name!="eml"||!i.value)) eml=true;val.push(i.name+"="+encodeURIComponent(i.value));}'
				.'if(!eml){alert("'.__('Please enter an email address').'");return;}'
				.'var xml=new XMLHttpRequest();'
				.'xml.open("POST","'.admin_url('admin-ajax.php').'",true);'
				.'xml.setRequestHeader("Content-type","application/x-www-form-urlencoded");'
				.'xml.onreadystatechange=function(){if(this.readyState==4){if(this.status==200){if(this.responseText) alert(this.responseText); else '.(preg_match('/^\/\/|^http:\/\/|^https:\/\//i',$instance['msg'])?('setTimeout(\'window.location="'.esc_attr($instance['msg']).'";\',100);'):('n.innerHTML="'.esc_attr($instance['msg']).'";')).'} else alert(this.statusText);}};'
				.'xml.send(val.join(String.fromCharCode(38)));'
			.'}</script>';
		echo $after_widget;
	}
	public function update($new_instance,$old_instance ) {
		$instance=$old_instance;
		$instance['title']=strip_tags($new_instance['title']);
		$instance['txt']=trim($new_instance['txt']);
		$instance['btn']=trim($new_instance['btn']);
		$instance['grp']=trim($new_instance['grp']);
		$instance['msg']=trim($new_instance['msg']);
		if (!empty($new_instance['nam'])) $instance['nam']=1; else unset($instance['nam']);
		return $instance;
	}
	public function form($instance) {
		$instance=wp_parse_args($instance,array('title'=>'','txt'=>'','btn'=>'Subscribe','log'=>'','pwd'=>'','grp'=>'General Interest','msg'=>'Thank you, you\'ve been added to the list!','nam'=>''));
		echo '<p><label for="'.$this->get_field_id('title').'">Title:</label><input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.esc_attr($instance['title']).'" /></p>'
			.'<p><label for="'.$this->get_field_id('txt').'">Description:</label><input class="widefat" id="'.$this->get_field_id('txt').'" name="'.$this->get_field_name('txt').'" type="text" value="'.esc_attr($instance['txt']).'" placeholder="description" /></p>'
			.'<p><label for="'.$this->get_field_id('btn').'">Button Text:</label><input class="widefat" id="'.$this->get_field_id('btn').'" name="'.$this->get_field_name('btn').'" type="text" value="'.esc_attr($instance['btn']).'" placeholder="button text" /></p>'
			.'<p><label for="'.$this->get_field_id('grp').'">Contact List Name:</label><input class="widefat" id="'.$this->get_field_id('grp').'" name="'.$this->get_field_name('grp').'" type="text" value="'.esc_attr($instance['grp']).'" /></p>'
			.'<p><label for="'.$this->get_field_id('msg').'">Success Message/URL:</label><input class="widefat" id="'.$this->get_field_id('msg').'" name="'.$this->get_field_name('msg').'" type="text" value="'.esc_attr($instance['msg']).'" /></p>'
			.'<p><input type="checkbox" id="'.$this->get_field_id('nam').'" name="'.$this->get_field_name('nam').'" value="1"'.(empty($instance['nam'])?'':' checked').'> <label for="'.$this->get_field_id('nam').'">Ask for first and last name</label></p>';
	}
}}

?>