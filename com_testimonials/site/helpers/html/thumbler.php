<?php
// no direct access
defined('_JEXEC') or die;

abstract class JHtmlThumbler
{
	/**
	**	available parameters
	**
	**	'noimage' => fall back image
	**	'title' => makes an <img title=""/> 
	**	'alt' => makes an <img alt=""/> 
	**	'set_attrs' => true if need <img width="" height=""/> 
	**	'width' => resulting file width
	**	'height' => resulting file height, ignored and calculated if 'square' is true
	**	'sizing' => image sizing, may be 'no' || 'spaced' || 'filled' || 'full', 'full' is default
	**	'square' => true makes resulting file square
	**	'fill_color' => fill color for whitespaces, transparent if not set
	**	'compression' => quality 0..100 for .jpeg
	**
	**/
	static function renderThumb($image, $settings = array()){

        // avoid strict
        $keys = array('noimage', 'title', 'alt' , 'set_attrs', 'width', 'height', 'sizing', 'square', 'fill_color', 'compression' );
        foreach($keys as $key){
            if(!array_key_exists($key,$settings)) $settings[$key] = '';
        }

		$output = '<img src="';

        $component = self::getWorkingComponent();
        $params = JComponentHelper::getParams($component);
		
		if($settings['title']) $title = $settings['title']; else $title = $params->get('thmbl_title', false);
		if($settings['alt']) $alt = $settings['alt']; else $alt = $params->get('thmbl_alt', false);
		if($settings['set_attrs']) $set_attrs = $settings['set_attrs']; else $set_attrs = $params->get('thmbl_set_attrs', false);
		
		if($settings['width']) $width = $settings['width']; else $width = $params->get('thmbl_width', 100); 
		if($settings['height']) $height = $settings['height']; else $height = $params->get('thmbl_height', false);
		
		if(is_file($image) || is_file(JPATH_SITE.$image)){
			$image = (is_file($image))?$image:JPATH_SITE.$image;
			$output .= $outsrc = self::getThumb($image, $settings);
		}else{
			if($settings['noimage']) $noimage = $settings['noimage']; else $noimage = $params->get('thmbl_noimage', false);
			$output .= $outsrc = self::getThumb($noimage, $settings);
		}
		$output .= '" ';
		if($title) $output .= 'title="'.$title.'" ';
		if($alt) $output .= 'alt="'.$alt.'" ';
		if($set_attrs){
			if($width) $width .= 'width="'.$width.'" ';
			if($height) $height .= 'height="'.$height.'" ';
		}
		$output .= ' />';
		
		if($outsrc){
			return $output;
		}
		else{
			return false;
		}
	}
	
	/**
	**	available parameters
	**
	**	'width' => resulting file width
	**	'height' => resulting file height, ignored and calculated if 'square' is true
	**	'sizing' => image sizing, may be 'no' || 'spaced' || 'filled' || 'full', 'full' is default
	**	'square' => true makes resulting file square
	**	'fill_color' => fill color for whitespaces, transparent if not set
	**	'compression' => quality 0..100 for .jpeg
	**
	**/
	static function getThumb($image, $settings = array()){
        

		ini_set('memory_limit', '1024M');
        // avoid strict
        $keys = array('width', 'height', 'sizing' , 'square' , 'fill_color' , 'compression');
		
        foreach($keys as $key){
            if(!array_key_exists($key,$settings)) $settings[$key] = '';
        }

		$component = self::getWorkingComponent();
		$params = JComponentHelper::getParams($component);
	
		if($settings['width']) $width = $settings['width']; else $width = $params->get('thmbl_width', 100); 
		if($settings['height']) $height = $settings['height']; else $height = $params->get('thmbl_height', false);
		if($settings['noimage']) $noimage = $settings['noimage']; else $noimage = $params->get('thmbl_noimage', false);
		if($noimage) $noimage = JUri::root(true).'/'.$noimage;
		
		$thumb_rel_path = "images" . DIRECTORY_SEPARATOR . $component . DIRECTORY_SEPARATOR . "thumbs" . DIRECTORY_SEPARATOR . $width."x". $height . DIRECTORY_SEPARATOR;
		$thumb_path = JPATH_SITE . DIRECTORY_SEPARATOR . $thumb_rel_path;
		$thumb_rel_url = JURI::root(true).'/'.$thumb_rel_path;
		
		if(is_file($image) || is_file(JPATH_SITE.$image)){
			$image = (is_file($image))?$image:JPATH_SITE.$image;
		}else{
			$image = JPATH_SITE.$noimage;
		}

		$image_name = basename($image);
		if(!$image_name){
			return $noimage;
		}
		if(is_file($thumb_path.$image_name)){
			return trim($thumb_rel_url.$image_name,'\\');
		}else{
			if(!file_exists($thumb_path)){
                jimport('joomla.filesystem.file');
                jimport('joomla.filesystem.folder');

                $destination= explode(DIRECTORY_SEPARATOR , $thumb_rel_path);
				$creatingFolder = JPATH_SITE; 
                foreach($destination as $folder){
					if($folder){
						$creatingFolder.= DIRECTORY_SEPARATOR . $folder;
						if (!JFolder::create($creatingFolder, 0755)){
							return $noimage;
						}
					}
                }
			}
			
			if($settings['sizing']) $sizing = $settings['sizing']; else $sizing = $params->get('thmbl_sizing', 'filled');
			if($settings['square']) $square = $settings['square']; else $square = $params->get('thmbl_square', false);
			if($settings['fill_color']) $fill_color = $settings['fill_color']; else $fill_color = $params->get('thmbl_fill_color', "");
			if($settings['compression']) $compression = $settings['compression']; else $compression = $params->get('thmbl_compression', 100);
			
			$fill_color = explode(",",$fill_color);
			
			list($iwidth, $iheight, $itype) = getimagesize($image);
			
			//thumbnail calculating
			$x=0;
			$y=0;
			$xd=0;
			$yd=0;
			//calculating width and height
			if(!$square){
				if(!$width || !$height){
					$ratio = $iwidth/$iheight;
					if($width && !$height) $height = $width/$ratio;
					else if(!$width && $height) $width = $height*$ratio;
				}
			}else{
				if($width) $height = $width;
				else if($height) $width = $height;
			}
			//calculating resize option
			if($sizing=="no"){
				$wsizer = $width/$iwidth;
				$hsizer = $height/$iheight;
			}else{
				if($sizing=="spaced"){
					if($width/$iwidth > $height/$iheight){
						$hsizer = 1;
						$wsizer = ($width*$iheight)/($iwidth*$height);
						$xd = ($wsizer*$width - $iwidth) / 2;
					}else{
						$wsizer = 1;
						$hsizer = ($height*$iwidth)/($iheight*$width);
						$yd = ($height - $height/$hsizer)/2;
					}
				}
				else{
					if($sizing=="filled"){
						if($width/$iwidth > $height/$iheight){
							$hsizer = ($height*$iwidth)/($width*$iheight);
							$wsizer = 1;
							$y = ($iheight - $hsizer*$iheight)/2;
						}else{
							$hsizer = 1;
							$wsizer = ($width*$iheight)/($height*$iwidth);
							$x = ($iwidth - $wsizer*$iwidth) / 2;
						}
						//calculating position
						
						
					}else{
						// full (full sizing)
						$hsizer = 1;
						$wsizer = 1;
					}
				}
			}
			
			$thumb = imagecreatetruecolor($width, $height);
			switch ($itype) {
				case 3:
					$file = imagecreatefrompng($image);
					break;
				case 2:
					$file = imagecreatefromjpeg($image);
					break;
				case 1:
					$file = imagecreatefromgif($image);
					break;
				case 6:
					$file = imagecreatefromwbmp($image);
					break;
			}

			imagedestroy($image);
			
			// sizing and saving
			$thumb = imagecreatetruecolor($width, $height);
			if($fill_color[0]=='' || $fill_color[1]=='' || $fill_color[2]==''){
				$trans = imagecolorallocate($thumb, 255, 255, 255);
				imagecolortransparent($thumb, $trans);
				imagefilledrectangle($thumb, 0, 0, $width, $height, $trans);
			}else{
				imagefill($thumb, (int)$fill_color[0], (int)$fill_color[0], (int)$fill_color[0]);
			}
			imagecopyresampled ($thumb ,$file , $xd , $yd , $x , $y , $width/$wsizer , $height/$hsizer , $iwidth , $iheight );
			
			switch ($itype) {
				case 3:
					@imagepng($thumb, $thumb_path.$image_name);
					break;
				case 2:
					@imagejpeg($thumb, $thumb_path.$image_name, $compression);
					break;
				case 1:
					@imagegif($thumb, $thumb_path.$image_name);
					break;
			}

			imagedestroy($thumb);
			return trim($thumb_rel_url.$image_name,'\\');
		}
	}

    protected static function getWorkingComponent(){
		$component = 'com_testimonials';
		return $component;
    }
}
