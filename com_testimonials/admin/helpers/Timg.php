<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
class TimgHelper
{
	function __construct()
	{
		if (! extension_loaded('gd') || ! function_exists('gd_info')) { die ("Hey, where is GD library?"); }
		return;
	}
	

//	MAIN FUNCTIONS	\\
		
	/*** -=:: FUNCTION getImage ::=-
	 * $main_img_obj 		: main image
	 ***/
	function getImage( $main_img_obj )
	{global $type;
		$type = 0;
		if (!is_resource($main_img_obj))
		{
			if (!file_exists($main_img_obj) || !is_file($main_img_obj)) die ("File not found.");
			
			$infoImage 	= getimagesize($main_img_obj);               
			$type		= $infoImage[2];
		}
		
		switch($type)
                  {
                    case 1: //gif
                     {
                          $img = imagecreatefromgif($main_img_obj);
                        break;
                     }
                    case 2: //jpg,jpeg
                     {					 
                          $img = imagecreatefromjpeg($main_img_obj);
                        break;
                     }
                    case 3: //png
                     {
                          $img = imagecreatefrompng($main_img_obj);
                        break;
                     }
                     default:
                     		$img = $main_img_obj;
                     break;
                  } // switch
        return $img;
	}

	/*** -=:: FUNCTION rotate ::=-
		* $main_img_obj		: main image
		* $degrees		 	: angle of rotation
	***/     
	function rotate($main_img_obj, $degrees) 
		{	
				$main_img_obj= $this->getImage($main_img_obj);
				$rotate = imagerotate($main_img_obj, $degrees, -1);
				return $rotate;
		}

	/*** -=:: FUNCTION resize ::=-
		* $main_img_obj			: main image
		* $max_width=100		: maximum width
		* $max_height=100		: maximum height
		* $min_width=50			: minimum width
		* $min_height=50		: minimum height
		* $newname=''			: save as ...
		* $bg_color = 'ffffff'	: background color	
	***/    	
	function resize($main_img_obj, $max_width=300, $max_height=300, $min_width=10, $min_height=10, $bg_color = 'ffffff')
	{global $type;
		$main_img_obj 	= $this->getImage($main_img_obj);
		$width				= imagesx ($main_img_obj);
		$height 			= imagesy ($main_img_obj);

		$w_orig = $width;
		$h_orig = $height;
		
		$canv_width = $width;
		$canv_height = $height;
		
		$canv_color = $this->getCollorArray($bg_color);
		

			if ($canv_width < $min_width) { 
				$canv_height = intval($canv_height * $min_width / $canv_width); 
				$canv_width = $min_width; 
			}
			if ($canv_width > $max_width) 
			{ 
				$canv_height = intval($canv_height * $max_width / $canv_width); 
				$canv_width = $max_width; 
			}
			
			if ($canv_height < $min_height) { 
				$canv_width = intval($canv_width * $min_height / $canv_height); 
				$canv_height = $min_height; 
			}			
			if ($canv_height > $max_height) 
			{ 
				$canv_width = intval($canv_width * $max_height / $canv_height); 
				$canv_height = $max_height; 
			}
 
		$factor_x = $w_orig / $canv_width;
		$factor_y = $h_orig / $canv_height;
		$factor = max($factor_x, $factor_y);

		$im_width = intval($w_orig / $factor);
		$im_height = intval($h_orig / $factor);
		
		$padding_width = intval(($canv_width - $im_width) / 2);
		$padding_height = intval(($canv_height - $im_height) / 2);
		
			switch ($type)
			{
				case 1: 
					$im_blank=imagecreate($canv_width,$canv_height);
				break;	
			
				case 2:
					$im_blank=imagecreatetruecolor($canv_width,$canv_height);
				break;
				
				case 3:
					$im_blank=imagecreate($canv_width,$canv_height);
				break;	
				
				default:
					$im_blank=imagecreate($canv_width,$canv_height);
			}

			$color = imagecolorallocate($im_blank, $canv_color['red'], $canv_color['green'], $canv_color['blue']);
			imagefill($im_blank, 0, 0, $color);

			imagecopyresampled($im_blank,$main_img_obj,$padding_width,$padding_height,0,0,$im_width,$im_height,$w_orig,$h_orig);
	      return $im_blank;
	}
	
	/*** -=:: FUNCTION watermark ::=-
	 * $main_img_obj 		: main image
	 * $watermark_img_obj 	: watermark image
	 * $alpha_level 		: transparency
	 * $type				: 1-> bottom_right; 2->center; 3->top_right; 4-> top_left; 5-> bottom_left
	***/
	function watermark( $main_img_obj, $watermark_img_obj, $alpha_level = 100, $type=1 )
      {
      	$main_img_obj 		= $this->getImage($main_img_obj);
      	$watermark_img_obj 	= $this->getImage($watermark_img_obj);
      	
            $alpha_level/= 100;
            $main_img_obj_w = imagesx( $main_img_obj );
            $main_img_obj_h = imagesy( $main_img_obj );
            $watermark_img_obj_w = imagesx( $watermark_img_obj );
            $watermark_img_obj_h = imagesy( $watermark_img_obj );
           
			    switch ( $type ) 
			    {
					case 1:
							$main_img_obj_min_x = floor( $main_img_obj_w - ($watermark_img_obj_w));
							$main_img_obj_max_x = ceil( $main_img_obj_w );
							$main_img_obj_min_y = floor( $main_img_obj_h - $watermark_img_obj_h) ;
							$main_img_obj_max_y = ceil( $main_img_obj_h );
						break;
						
					case 2:
							$main_img_obj_min_x = floor( ( $main_img_obj_w / 2 ) - ( $watermark_img_obj_w / 2 ) );
				            $main_img_obj_max_x = ceil( ( $main_img_obj_w / 2 ) + ( $watermark_img_obj_w / 2 ) );
				            $main_img_obj_min_y = floor( ( $main_img_obj_h / 2 ) - ( $watermark_img_obj_h / 2 ) );
				            $main_img_obj_max_y = ceil( ( $main_img_obj_h / 2 ) + ( $watermark_img_obj_h / 2 ) );
						break;	
							
					case 3:
							$main_img_obj_min_x = floor($main_img_obj_w - ($watermark_img_obj_w));
							$main_img_obj_max_x = ceil( ( $main_img_obj_w / 2 ) + ( $watermark_img_obj_w / 2 ) );
							$main_img_obj_min_y = floor($watermark_img_obj_h - ( $watermark_img_obj_h )) ;
							$main_img_obj_max_y = ceil( $main_img_obj_h );
						break;	
						
					case 4:
							$main_img_obj_min_x = 0;
							$main_img_obj_max_x = ceil($watermark_img_obj_w);
							$main_img_obj_min_y = 0;
							$main_img_obj_max_y = ceil($main_img_obj_h);
						break;
						
					case 5:
							$main_img_obj_min_x = 0;
							$main_img_obj_max_x = ceil( $main_img_obj_w );
							$main_img_obj_min_y = floor( $main_img_obj_h - $watermark_img_obj_h) ;
							$main_img_obj_max_y = ceil( $main_img_obj_h );
						break;
											
					default:
							$main_img_obj_min_x = floor( $main_img_obj_w - ($watermark_img_obj_w));
							$main_img_obj_max_x = ceil( $main_img_obj_w );
							$main_img_obj_min_y = floor( $main_img_obj_h - $watermark_img_obj_h) ;
							$main_img_obj_max_y = ceil( $main_img_obj_h );
						break;
				}
			
            $return_img = imagecreatetruecolor( $main_img_obj_w, $main_img_obj_h );
            
      for( $y = 0; $y < $main_img_obj_h; $y++ )
      {
            for ($x = 0; $x < $main_img_obj_w; $x++ )
            {
                  $return_color = NULL;
                  $watermark_x = $x - $main_img_obj_min_x;
                  $watermark_y = $y - $main_img_obj_min_y;
                  $main_rgb = imagecolorsforindex( $main_img_obj, imagecolorat( $main_img_obj, $x, $y ) );
                  if ($watermark_x >= 0 && $watermark_x < $watermark_img_obj_w && $watermark_y >= 0 && $watermark_y < $watermark_img_obj_h )
                  {
                        $watermark_rbg = imagecolorsforindex( $watermark_img_obj, imagecolorat( $watermark_img_obj, $watermark_x, $watermark_y ) );
                        $watermark_alpha = round( ( ( 127 - $watermark_rbg['alpha'] ) / 127 ), 2 );
                        $watermark_alpha = $watermark_alpha * $alpha_level;
                        $avg_red = $this->getAveColor( $main_rgb['red'], $watermark_rbg['red'], $watermark_alpha );
                        $avg_green = $this->getAveColor( $main_rgb['green'], $watermark_rbg['green'], $watermark_alpha );
                        $avg_blue = $this->getAveColor( $main_rgb['blue'], $watermark_rbg['blue'], $watermark_alpha );
                        $return_color = $this->getImageColor( $return_img, $avg_red, $avg_green, $avg_blue );
                        } else { $return_color = imagecolorat( $main_img_obj, $x, $y ); }
                  imagesetpixel($return_img, $x, $y, $return_color );
            }

		} 
            return $return_img;
      } 

//	TEXT	\\
	
	/*** -=:: FUNCTION setText ::=-
		* $main_img_obj			: main image
		* $text=''				: text to print
		* $x=0					: coordinate x
		* $y=0					: coordinate y
		* $textcolor = '1E231F'	: color of text
		* $ft=1					: font type. If font is 1, 2, 3, 4 or 5, a built-in font is used.
	***/    	
	function setText($main_img_obj, $text='', $x=0, $y=0,  $textcolor = '1E231F', $ft=1)
	{
		$textcolor = $this->getCollorArray($textcolor);
		$textcolor = $this->getImageColor($main_img_obj, $textcolor['red'], $textcolor['green'], $textcolor['blue']);
		
		$main_img_obj= $this->getImage($main_img_obj);
		imagestring($main_img_obj, $ft, $x, $y, $text, $textcolor);
		return $main_img_obj;
	}
	

//	AUXILIARY FUNCTIONS	\\ 
    
	function getAveColor( $color_a, $color_b, $alpha_level )
	      {
	            return round( ( ( $color_a * ( 1 - $alpha_level ) ) + ( $color_b * $alpha_level ) ) ); 
	      }

	function getImageColor($im, $r, $g, $b)
	      {
	            $c=imagecolorexact($im, $r, $g, $b);
	            if ($c!=-1) return $c;
	            $c=imagecolorallocate($im, $r, $g, $b);
	            if ($c!=-1) return $c;
	            return imagecolorclosest($im, $r, $g, $b);
	      } 
   
	function getWidth($main_img_obj)
    {
    	$main_img_obj= $this->getImage($main_img_obj);
    	return imagesx( $main_img_obj );
    }
    
	function getHeight($main_img_obj)
    {
    	$main_img_obj= $this->getImage($main_img_obj);
    	return imagesy( $main_img_obj );
    }

	function getCollorArray($color='ffffff')
	{
		$color = str_replace('#','',$color);
		$arr_color = array();
			$arr_color['red'] = hexdec(substr($color, 0, 2));
			$arr_color['green'] = hexdec(substr($color, 2, 2));
			$arr_color['blue'] = hexdec(substr($color, 4, 2));
		return $arr_color;
	}

	
//	FILTER GALLERY	\\

	/*** -=:: FUNCTION setGrayscale ::=-
	 * $main_img_obj 		: main image
	 * Converts the image into grayscale. 
	 */
	function setGrayscale( $main_img_obj )
      {
            $main_img_obj= $this->getImage($main_img_obj);
            imagefilter($main_img_obj, IMG_FILTER_GRAYSCALE);
    		return $main_img_obj;
      }
      
    /*** -=:: FUNCTION setBrightness ::=-
	 * $main_img_obj 		: main image
	 * $percent				: parameter 
     * Changes the brightness of the image. Use arg1 to set the level of brightness. 
     */
	function setBrightness( $main_img_obj, $percent=25 )
    {
    	    $main_img_obj= $this->getImage($main_img_obj);
            imagefilter($main_img_obj, IMG_FILTER_BRIGHTNESS, $percent);
    		return $main_img_obj;
    }
    
    /*** -=:: FUNCTION setReverseColors ::=-
	 * $main_img_obj 		: main image
     * Reverses all colors of the image. 
     */
	function setReverseColors($main_img_obj)
    {
    	 $main_img_obj= $this->getImage($main_img_obj);
            imagefilter($main_img_obj, IMG_FILTER_NEGATE);
    		return $main_img_obj;
    }
    
    /*** -=:: FUNCTION setContrast ::=-
	 * $main_img_obj 		: main image
	 * $percent				: parameter 
     * Changes the contrast of the image. Use arg1 to set the level of contrast. 
     */
    function setContrast( $main_img_obj, $percent=25 ) 
    {
    	$main_img_obj= $this->getImage($main_img_obj);
            imagefilter($main_img_obj, IMG_FILTER_CONTRAST, $percent);
		return $main_img_obj ;
	}
	
	/*** -=:: FUNCTION setColorize ::=-
	 * $main_img_obj 		: main image
	 * $r,$g,$b				: parameters 
	 * Use arg1, arg2 and arg3 in the form of red, blue, green. The range for each color is 0 to 255.
	 * !!! PHP ver. 5.2.5 +
	 */
	function setColorize( $main_img_obj, $r=100, $b=0, $g=20 ) 
    {
    	$main_img_obj= $this->getImage($main_img_obj);
            imagefilter($main_img_obj, IMG_FILTER_COLORIZE, $r, $b, $g);
		return $main_img_obj ;
	}
	
	/*** -=:: FUNCTION setGBlur ::=-
	 * $main_img_obj 		: main image
	 * Blurs the image using the Gaussian method
	 */
	function setGBlur( $main_img_obj ) 
    {
    	$main_img_obj= $this->getImage($main_img_obj);
            imagefilter($main_img_obj, IMG_FILTER_GAUSSIAN_BLUR);
		return $main_img_obj ;
	}
	
	/*** -=:: FUNCTION setBlur ::=-
	 * $main_img_obj 		: main image
	 * Blurs the image.
	 */
	function setBlur( $main_img_obj) 
    {
    	$main_img_obj= $this->getImage($main_img_obj);
            imagefilter($main_img_obj, IMG_FILTER_SELECTIVE_BLUR);
		return $main_img_obj ;
	}
	
	/*** -=:: FUNCTION setSketchy ::=-
	 * $main_img_obj 		: main image
	 * Uses mean removal to achieve a "sketchy" effect. 
	 */
	function setSketchy( $main_img_obj) 
    {
    	$main_img_obj= $this->getImage($main_img_obj);
            imagefilter($main_img_obj, IMG_FILTER_MEAN_REMOVAL);
		return $main_img_obj ;
	}
    
    /*** -=:: FUNCTION setSmooth ::=-
	 * $main_img_obj 		: main image
	 * $percent				: parameter 
	 * Uses mean removal to achieve a "sketchy" effect. 
	 */
	function setSmooth( $main_img_obj, $percent=25 ) 
    {
    	$main_img_obj= $this->getImage($main_img_obj);
            imagefilter($main_img_obj, IMG_FILTER_SMOOTH, $percent );
		return $main_img_obj ;
	}
    
	 /*** -=:: FUNCTION setPixelate ::=-
	 * $main_img_obj 		: main image
	 * $percent				: parameter 
	  * Apply pixelation to each instance, with a block
	  * !!! PHP ver 5.3.0+
	  */
	function setPixelate( $main_img_obj, $percent=25 ) 
    {
    	$main_img_obj= $this->getImage($main_img_obj);
            imagefilter($main_img_obj, IMG_FILTER_PIXELATE, $percent, true );
		return $main_img_obj ;
	}


//	SAVE/OUTPUT	\\	

	/*** -=:: FUNCTION show ::=-
	 * $main_img_obj 		: main image
	 * $type=2				: 1 - gif, 2-jpg, 3-png
	 * $file=''				: file name to save !not ext!
	 * $quality='75'		: quality !only $type=2
	*/
	function show($main_img_obj, $type=2, $file='', $quality='75')
	{
		$main_img_obj= $this->getImage($main_img_obj);
        switch($type)
                  {
                    case 1: 
                     {
                     		if (!$file) header("Content-type: image/gif"); else $file.='.gif';
    						imagegif($main_img_obj, $file);
                        break;
                     }
                    case 2: 
                     {
                       		if (!$file){
                                 header("Content-type: image/jpeg");
                            }else{
                                $file.='.jpg';
                            }
    						imagejpeg($main_img_obj, $file, $quality);
                        break;
                     }
                    case 3:
                     {
                          	if (!$file) header("Content-type: image/png"); else $file.='.png';
    						imagepng($main_img_obj, $file);
                        break;
                     }
                     default:
                     		return;
                     break;
                  } 
        return;
	}
	
	public function captureImage($img, $fname){
	    ob_start();
	    $fname = explode(".", $fname);
	    $ext = array_pop($fname);
	    switch(strtolower($ext)){
		case 'gif':
		    imagegif($img);
		    break;
		case 'jpg':
		case 'jpeg':
		    imagejpeg($img);
		    break;
		case 'png':
		    imagepng($img);
		    break;
		case 'bmp':
		    imagewbmp($img);
		    break;
	    }
	    $i = ob_get_clean();
	    return $i;
	}

	
  }	//end class \\
?>