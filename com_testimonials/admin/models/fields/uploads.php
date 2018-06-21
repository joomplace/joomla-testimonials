<?php
/**
 * Testimonials Component for Joomla 3
 *
 * @package   Testimonials
 * @author    JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');


//В путях изображений закомментил JPATH_ADMINISTRATOR что бы работало на локалке
//Можно расширить разрешенные форматы

class JFormFieldUploads extends JFormField
{

    protected $type = 'Uploads';

    public function getInput()
    {
        //Check if ajax
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
        ) {
            ob_clean();
            $max_file_size = 2000000;
            $width         = 150;
            $height        = 150;
            require_once JPATH_COMPONENT_SITE . '/helpers/html/thumbler.php';

            //Check file
            if (isset($_FILES["file"])
                && $_FILES["file"]["size"] < $max_file_size
            ) {
                $type = $_FILES['file']['type'];
                $name = $_FILES['file']['name'];
                $path = JPATH_SITE . '/tmp/' . $name;

                //Access file type
                switch ($type) {
                    case('image/jpg'):
                    case('image/jpeg'):
                        $source
                                 = imagecreatefromjpeg($_FILES['file']['tmp_name']);
                        $newFile = fopen($path, "w");
                        imagejpeg($source, $newFile);
                        break;
                    case('image/bmp'):
                        $source
                                 = imagecreatefromwbmp($_FILES['file']['tmp_name']);
                        $newFile = fopen($path, "w");
                        imagewbmp($source, $newFile);
                        break;
                    case('image/gif'):
                        $source
                                 = imagecreatefromgif($_FILES['file']['tmp_name']);
                        $newFile = fopen($path, "w");
                        imagegif($source, $newFile);
                        break;
                    default:
                        echo /*JPATH_ADMINISTRATOR.*/
                            _pathToRelURLToRoot(getcwd())
                            . '/assets/images/not_found.gif';
                        die();
                }
                $settings = [
                    'width'  => $width,
                    'height' => $height,
                    '',
                    '',
                    '',
                    ''
                ];
                $url      = JHtmlThumbler::getThumb($path, $settings);
                imagedestroy($source);
                // FCLOSE ERROR ???
                echo $url;//*JPATH_SITE.*/'/tmp/' . $name;
                die();
            }
            echo _pathToRelURLToRoot(getcwd()) . '/assets/images/not_found.gif';
            die();
        } else {

            $document = JFactory::getDocument();
            $document->addStyleSheet(_pathToRelURLToRoot(getcwd())
                . '/assets/css/DaD.css');
            $document->addScript(_pathToRelURLToRoot(getcwd())
                . '/assets/js/DaD.js');

            $text = '';

            $text
                .= '
        <div class="col-md-6">
          <!-- D&D Zone-->
          <div class="tst_drop_field uploader">
            <div>Drag &amp; Drop Images Here</div>
            <div class="or">-or-</div>
            <div class="browser">
              <label>
                <span>Click to open the file Browser</span>
                <input type="file" name="files[]" multiple="multiple" title="Click to add Images">
                <input type="hidden" name="thumbsImagesName" multiple="multiple" class="thumbsData">
              </label>
            </div>
          </div>
          <!-- /D&D Zone -->     
          <div class="tst_image_container">           
          </div>
        </div>
        ';

            return $text;
        }
    }

    protected function _pathToRelURLToRoot($path)
    {
        $string = trim(str_replace(array(JPATH_SITE, 'models', '//'),
            array('/' . JUri::root(true), '', '/'), $path), '/');
        $string = substr($string, 0, strrpos($string, "/"));

        return $string;
    }
}
