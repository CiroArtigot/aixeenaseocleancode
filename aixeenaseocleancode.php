<?php

	/*------------------------------------------------------------------------
	# aixeena_clean_code.php - Aixeena CLean Code (plugin)
	# ------------------------------------------------------------------------
	# version		4.0.0
	# author    	@ciroartigot for Aixeena.org
	# copyright 	Copyright (c) 2018 CiroArtigot. All rights reserved.
	# @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	# Websites 		http://aixeena.org/
	-------------------------------------------------------------------------
	*/

	// no direct access
	defined('_JEXEC') or die('Restricted access');

	jimport('joomla.plugin.plugin');
	
	$files = glob(JPATH_PLUGINS . '/system/aixeenaseocleancode/classes/*.php');
	foreach ($files as $file) {
		require($file);   
	}

	class plgSystemAixeenaSEOCleanCode extends JPlugin {
	
	
		function onBeforeCompileHead() {  
		
			$app = JFactory::getApplication();
			if($app->isAdmin()) return;
			// Disable js scripts an css style sheets
	
			if($this->params->get('disabler',0)) {
				$disablescripts_array =  explode(',', str_replace("\n", ",", $this->params->get('scriptsarray', null)));
				if (!empty($disablescripts_array )) {
					foreach ($disablescripts_array  as $script) {
						AixeenaClean::disableScript(trim($script));
					}
				}
				
				$disablecss_array =  explode(',', str_replace("\n", ",", $this->params->get('cssarray', null)));
				if (!empty($disablecss_array )) {
					foreach ($disablecss_array  as $css) {
						AixeenaClean::disableStylesheet(trim($css));
					}
				}
				
			}
	
			return true;
		}
			

		function onAfterRender(){

			$app	= JFactory::getApplication();
			$document = JFactory::getDocument();
			if ($app->isAdmin()) return;
			if ($document->getType() != 'html') return;
			$document = JFactory::getDocument();
			$headerstuff = $document->getHeadData(); 
			$buffer = JResponse::getBody();
			$menu = $app->getMenu();
			$menuactive = $menu->getActive();
			$clean = $this->params->get('clean',0);
			
			if(isset($menuactive)) {
				$custom = $menuactive->params['customseo'];
				if($menuactive->params['clean']==0 && $custom) $clean = 0;
			}
			
			// remove meta generator
			if($this->params->get('meta_generator',1))	$buffer = preg_replace( '/<meta\s*name="Generator"\s*content=".*\/>/isU','', $buffer);
			// remove base href
			if($this->params->get('base_href',1))	$buffer = preg_replace( '/<base.*\/>/isU','', $buffer);
				
			if($this->params->get('disabler',1)) {
				preg_match_all('#<script(.*?)<\/script>#is', $buffer, $matches);
				$scriptkeys2 =  explode(',', str_replace("\n", ",", $this->params->get('scriptsarray2', null)));	
				foreach ($matches[0] as $value) {	
					if($scriptkeys2 && AixeenaClean::strpos_array($value, $scriptkeys2) )  $buffer = str_replace($value, '', $buffer);	
				}	
			}
			
			if($clean) {
			
				$buffer = preg_replace( '/<!--(.|\s)*?-->/' , '' , $buffer );
	
				preg_match_all('#<style(.*?)<\/style>#is', $buffer, $matches);
				foreach ($matches[0] as $value) {	
					$value2 = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $value);
					$value2 = str_replace(': ', ':', $value2);
					$value2 = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $value2);
					$buffer = str_replace($value, $value2, $buffer	);
				}
							
				$buffer = AixeenaClean::clean_html_code($buffer);
				
				// preserve scripts without linebreaks
				preg_match_all('#<script(.*?)<\/script>#is', $buffer, $matches);
				foreach ($matches[0] as $value) {	
					$value2 = str_replace("'text/javascript'","\"text/javascript\"",$value);
					$value2 = preg_replace("/[\n\r]/","",$value2);
					$value2 = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $value2);
					$buffer = str_replace($value, $value2, $buffer	);
				}
		
				// clean empty tags
				preg_match_all('#<span[^>]*(?:/>|>(?:\s|&nbsp;)*</span>)#im', $buffer, $matchesall);
				foreach ($matchesall[0] as $value) {				
					$buffer = str_replace($value, AixeenaClean::get_clean_tag($value),$buffer);			
				}
		
				preg_match_all('#<div[^>]*(?:/>|>(?:\s|&nbsp;)*</div>)#im', $buffer, $matchesall);
				foreach ($matchesall[0] as $value) {				
					$buffer = str_replace($value, AixeenaClean::get_clean_tag($value),$buffer);			
				}
				
				preg_match_all('#<textarea[^>]*(?:/>|>(?:\s|&nbsp;)*</textarea>)#im', $buffer, $matchesall);
				foreach ($matchesall[0] as $value) {				
					$buffer = str_replace($value, AixeenaClean::get_clean_tag($value),$buffer);			
				}
		
				preg_match_all('#<script[^>]*(?:/>|>(?:\s|&nbsp;)*</script>)#im', $buffer, $matchesall);
				foreach ($matchesall[0] as $value) {				
					$buffer = str_replace($value, AixeenaClean::get_clean_tag($value),$buffer);			
				}
			
			}
			
			if($this->params->get('sanitize',1)) $buffer = AixeenaClean::compress_code($buffer);
			
			JResponse::setBody($buffer);
			
		}
	}
?>