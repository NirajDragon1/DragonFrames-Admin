* Including library
$this->load->library('AddTxtToImage/AddTxtToImage');

* Example of Calling Function

$stringInfo = [
    ['string' => 'Mukund Topiwala', 'x' => 40, 'y' => 23, 'fontSize' => 4, 'color' => [0, 0, 0], 'tilt' => 0],
    ['string' => '10', 'x' => 120, 'y' => 53, 'fontSize' => 4, 'color' => [0, 0, 0], 'tilt' => 0],
];
$this->addtxttoimage->index($stringInfo);


* Setting Base Image & Font

$param = array(
	'base_image' => 'dir_img_path',
	'font' 	     => 'dir_font_path',
)
$this->load->library('AddTxtToImage/AddTxtToImage', $param);


* Download Sticker instead of display:
* Passing secong argument true to download the file instead of viewing
$this->addtxttoimage->index($stringInfo, true);
