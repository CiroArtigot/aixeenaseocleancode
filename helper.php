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
	
	class AixeenaClean
	{
	
		public static  function get_clean_tag($value) {
			$value2 = preg_replace('/\r\n+|\r+|\n+|\t+/i', '', $value);
			$value2 = trim(preg_replace('/\t+/', '', $value2));
			$value2 = preg_replace('/\s+/', ' ', $value2);
			$value2 = str_replace('> </', '></', $value2);	
			return $value2;
		}
		
		public static  function fix_newlines_for_clean_html($fixthistext) {
			$fixthistext_array = explode("\n", $fixthistext);
			foreach ($fixthistext_array as $unfixedtextkey => $unfixedtextvalue) {
				if (!preg_match("/^(\s)*$/", $unfixedtextvalue)) {
					$fixedtextvalue = preg_replace("/>(\s|\t)*</U", ">\n<", $unfixedtextvalue);
					$fixedtext_array[$unfixedtextkey] = $fixedtextvalue;
				}
			}
			return implode("\n", $fixedtext_array);
		}
		
		
		public static  function strpos_array($haystack, $needles) {
			$resultado = 0;
			if($needles) {
				foreach ($needles as $str) {
					if($str) {
					$str =  trim($str);			
					$pos = strpos($haystack, $str);	
					if ($pos !== FALSE) $resultado = 1;	
					}
				}
			}
			return $resultado;
		}
	
		public static  function disableScript($script){
		
			$app	= JFactory::getApplication();
			if ($app->isAdmin()) return;
			$script = trim($script);
			if (!empty($script)) {
				$doc = JFactory::getDocument();
				$uri = JUri::getInstance();
				$relativePath   = trim(str_replace($uri->getPath(), '', JUri::root()), '/');
				$relativeScript = trim(str_replace($uri->getPath(), '', $script), '/');
				$relativeUrl    = str_replace($relativePath, '', $script);
				// Try to disable relative and full URLs
				unset($doc->_scripts[$script]);
				unset($doc->_scripts[$relativeUrl]);
				unset($doc->_scripts[JUri::root(true) . $script]);
				unset($doc->_scripts[$relativeScript]);
			}
		}
		
		
		public static function compress_code($code) 
		{
		 $search = array(
		  '/\>[^\S ]+/s',  // remove whitespaces after tags
		  '/[^\S ]+\</s',  // remove whitespaces before tags
		  '/(\s)+/s'       // remove multiple whitespace sequences
		 );
		
		 $replace = array('>','<','\\1');

		 $code = preg_replace($search, $replace, $code);
		 $code = str_replace('> <', '><', $code);	
		 return $code;
		}
		
		public static  function disableStylesheet($css) {
			
			$app	= JFactory::getApplication();
			if ($app->isAdmin()) return;
			$script = trim($css);
			if (!empty($css)) {
				$doc = JFactory::getDocument();
				$uri = JUri::getInstance();
				$relativePath   = trim(str_replace($uri->getPath(), '', JUri::root()), '/');
				$relativeScript = trim(str_replace($uri->getPath(), '', $css), '/');
				$relativeUrl    = str_replace($relativePath, '', $css);
				// Try to disable relative and full URLs
				unset($doc->_styleSheets[$css]);
				unset($doc->_styleSheets[$relativeUrl]);
				unset($doc->_styleSheets[JUri::root(true) . $css]);
				unset($doc->_styleSheets[$relativeScript]);
			}
		}
	
		public static function clean_html_code($uncleanhtml) {
	
			$indent = "    ";
			$fixed_uncleanhtml = AixeenaClean::fix_newlines_for_clean_html($uncleanhtml);
			$uncleanhtml_array = explode("\n", $fixed_uncleanhtml);
			$indentlevel = 0;
			foreach ($uncleanhtml_array as $uncleanhtml_key => $currentuncleanhtml) {
				//Removes all indentation
				$currentuncleanhtml = preg_replace("/\t+/", "", $currentuncleanhtml);
				$currentuncleanhtml = preg_replace("/^\s+/", "", $currentuncleanhtml);
				
				$replaceindent = "";
				
				//Sets the indentation from current indentlevel
				for ($o = 0; $o < $indentlevel; $o++)
				{
					$replaceindent .= $indent;
				}
				
				//If self-closing tag, simply apply indent
				if (preg_match("/<(.+)\/>/", $currentuncleanhtml))
				{ 
					$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
				}
				//If doctype declaration, simply apply indent
				else if (preg_match("/<!(.*)>/", $currentuncleanhtml))
				{ 
					$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
				}
				//If opening AND closing tag on same line, simply apply indent
				else if (preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && preg_match("/<\/(.*)>/", $currentuncleanhtml))
				{ 
					$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
				}
				//If closing HTML tag or closing JavaScript clams, decrease indentation and then apply the new level
				else if (preg_match("/<\/(.*)>/", $currentuncleanhtml) || preg_match("/^(\s|\t)*\}{1}(\s|\t)*$/", $currentuncleanhtml))
				{
					$indentlevel--;
					$replaceindent = "";
					for ($o = 0; $o < $indentlevel; $o++)
					{
						$replaceindent .= $indent;
					}
					
					$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
				}
				//If opening HTML tag AND not a stand-alone tag, or opening JavaScript clams, increase indentation and then apply new level
				else if ((preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && !preg_match("/<(link|meta|base|br|img|hr)(.*)>/", $currentuncleanhtml)) || preg_match("/^(\s|\t)*\{{1}(\s|\t)*$/", $currentuncleanhtml))
				{
					$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
					
					$indentlevel++;
					$replaceindent = "";
					for ($o = 0; $o < $indentlevel; $o++)
					{
						$replaceindent .= $indent;
					}
				}
				else
				//Else, only apply indentation
				{$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;}
			}
			//Return single string seperated by newline
			return implode("\n", $cleanhtml_array);	
		}
		
		
		public static function clean_htmlPage($txt, $type, $params) {
		
			$remove =  explode(',', $params->get('remove', null));	
			if (!empty($remove)) {
				foreach ($remove  as $r) {
					$txt = str_replace($r,'',$txt);
				}
			}
	
			$remove2 =  explode(',', $params->get('remove2', null));
			
			if(!empty($remove2)) {
				foreach ($remove2  as $r) {
					$regex = "#{".$r."}(.*?){/".$r."}#is";
					preg_match_all($regex, $txt, $matches);
					foreach ($matches[0] as $key => $match){
						$txt = str_replace($match,'',$txt);
					}
				}
			}
			
			$output = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $txt);
			$output = preg_replace('/(<[^>]*) style=("[^"]+"|\'[^\']+\')([^>]*>)/i', '$1$3', $output);
			$output = str_replace('<p>&nbsp;</p>','',$output);
			
			if($type==1) {
				return strip_tags($output,'<br><br/><p></p>'); 
			} else {
			
				preg_match_all('/<img[^>]+>/i',$output, $images); 
				
				if($images) {
					foreach($images[0] as $image) {			
						$doc = new DOMDocument();
						$doc->loadHTML($image);
						$tags = $doc->getElementsByTagName('img');
						foreach ($tags as $tag) {
							$src = '/'.$tag->getAttribute('src');			
							if(strpos($src,'http')!== false) {
								$output = str_replace($image, '', $output); 
							} else {
								$imgpath = JPATH_SITE.$src ;
								list($width, $height) = getimagesize($imgpath);					   
								$output = str_replace($image, '<amp-img src='.$src.' alt="'.$tag->getAttribute('alt').'" width="'.$width.'" height="'.$height.'"  layout="responsive"></amp-img>', $output); 
							}
						}		
					}
				}
			
				return $output;
			
			}
		
		}
	
		
		public static function firstXChars($string, $chars = 100, $suffix)
		{
				if(strlen($string)<=$chars) return $string;
	
				$text = $string." "; 
				$text = substr($text,0,$chars); 
				$text = substr($text,0,strrpos($text,' ')); 
				$text = $text.$suffix; 
				return $text; 
			
		}
		
	}
?>