<?php
/*
 * PHP version 5
 *
 * @category ImageGD
 * @package  AddTxtToImage
 * @author   Mukund Topiwala
 */

class AddTxtToImage {
    public $base_image;
    public function __construct($settings = array()) {
        $this->base_image = isset($settings['base_image']) ? $settings['base_image'] : __DIR__ . '/./unnamed.png';
        $this->font = __DIR__ . '/./Helvetica.ttf';
    }

    public function index($stringInfo = [], $download = false) {
        //echo '<pre>';print_r($stringInfo);die;
        $pics = $this->base_image;

        $img_type = 'jpg';
        if (preg_match("/\.jpg$/i", "$pics") || preg_match("/.jpeg/i", "$pics")) {
            header('Content-type: image/jpeg');
        }else if (preg_match("/\.gif$/i", "$pics")) {
            $img_type = 'gif';
            header('Content-type: image/gif');
        }else if (preg_match("/\.png$/i", "$pics")) {
            $img_type = 'png';
            header("Content-type: image/png");
        }

        switch ($img_type) {
            case 'jpg' : {
                    $source = imagecreatefromjpeg($pics);
                    break;
                }
            case 'png' : {
                    $source = imagecreatefrompng($pics);
                    imageAlphaBlending($source, true);
                    imageSaveAlpha($source, true);
                    break;
                }
            case 'gif' : {
                    $source = imagecreatefromgif($pics);
                    break;
                }
            default : {
                    $source = imagecreatefromjpeg($pics);
                    break;
                }
        }

        $this->addText($source, $stringInfo);
	ob_start();

        switch ($img_type) {
            case 'jpg' : {
                    imagejpeg($source);
                    break;
                }
            case 'png' : {
                    imagepng($source);
                    break;
                }
            case 'gif' : {
                    imagegif($source);
                    break;
                }
            default : {
                    imagejpeg($source);
                    break;
                }
        }

	if($download){

		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=export.png'); 
		header('Content-Transfer-Encoding: binary');
		header('Connection: Keep-Alive');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
	}

        imagedestroy($source);
	ob_flush();
    }
    
    public function addText($source, $stringInfo){
        if(!empty($stringInfo)){
            foreach ($stringInfo as $k => $v) {
                
                $string     = isset($v['string'])   ? $v['string'] : '';
                $x          = isset($v['x'])        ? $v['x'] : 0;
                $y          = isset($v['y'])        ? $v['y'] : 0;
                $fontSIze   = isset($v['fontSize']) ? $v['fontSize'] : 4;
                $color = array();
                if(isset($v['color']) && !empty($v['color'])){
                    $color[0] = isset($v['color'][0]) ? $v['color'][0] : 0;
                    $color[1] = isset($v['color'][1]) ? $v['color'][1] : 0;
                    $color[2] = isset($v['color'][2]) ? $v['color'][2] : 0;
                }else{
                    $color = [0,0,0];
                }
                
                $color = imagecolorallocate($source, $color[0], $color[1], $color[2]);
                imagettftext($source, $fontSIze, 0, $x, $y, $color, $this->font, $string);
            }
        }    
    }
}

