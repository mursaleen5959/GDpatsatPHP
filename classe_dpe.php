<?php
putenv('GDFONTPATH=' . realpath('.'));
class gdlevel {
    protected $watermarkFile;
    protected $value;
    protected $type;
    protected $errorMessage = ''; 
   
    function __construct($watermarkImageFile, $value,$filename,$type){
        $this->watermarkFile = $watermarkImageFile;
		$this->new_pos = $value;
		$this->filename = $filename;
		$this->type = $type;
    }
    public function setWatermarkFile($imagePath){
        $this->watermarkFile = $imagePath;
    }
    public function getLastErrorMessage(){
        return $this->errorMessage;
    }
    public function applygdlevel($unmarkedImagePath, $quality=100){
        $finalWatermarkedImageFile = $this->filename;
//        echo var_dump(getcwd());
        $targetImageSize=getimagesize($unmarkedImagePath);
        if($targetImageSize[2]==2 || $targetImageSize[2]==3){
            $originalImageName=$unmarkedImagePath;
            $watermark=$this->watermarkFile;
            $wmTarget= $this->watermarkFile;
            $origInfo = getimagesize($originalImageName); 
            $origWidth = $origInfo[0]; 
            $origHeight = $origInfo[1]; 
            $waterMarkInfo = getimagesize($watermark);
            $waterMarkDestWidth = $waterMarkInfo[0];
            $waterMarkDestHeight = $waterMarkInfo[1];
            $differenceX = $origWidth - $waterMarkDestWidth;
            $differenceY = $origHeight - $waterMarkDestHeight;
            if($targetImageSize[2]==3){
                $resultImage = imagecreatefrompng($originalImageName);
            }
            else {
                $resultImage = imagecreatefromjpeg($originalImageName);
            }
            imagealphablending($resultImage, TRUE);
            $finalWaterMarkImage = imagecreatefrompng($wmTarget);
            $finalWaterMarkWidth = imagesx($finalWaterMarkImage);
            $finalWaterMarkHeight = imagesy($finalWaterMarkImage);
			if($this->type=='DPE'){
			$margin=64;
			if($this->new_pos>=0&&$this->new_pos<=50){
			$final_pos=	$margin;
			}elseif($this->new_pos>50&&$this->new_pos<=90){
			$final_pos=	($margin*2)-5;
			}elseif($this->new_pos>90&&$this->new_pos<=150){
			$final_pos=	($margin*3)-6;
			}elseif($this->new_pos>150&&$this->new_pos<=230){
			$final_pos=	($margin*4)-11;
			}elseif($this->new_pos>230&&$this->new_pos<=330){
			$final_pos=	($margin*5)-16;
			}elseif($this->new_pos>330&&$this->new_pos<=450){
			$final_pos=	($margin*6)-18;
			}elseif($this->new_pos>450){
			$final_pos=	($margin*7)-21;
			}
			}
			if($this->type=='GES'){
			$margin=57;
			if($this->new_pos>=0&&$this->new_pos<=5){
			$final_pos=	$margin;
			}elseif($this->new_pos>5&&$this->new_pos<=10){
			$final_pos=	($margin*2)+4;
			}elseif($this->new_pos>10&&$this->new_pos<=20){
			$final_pos=	($margin*3)+9;
			}elseif($this->new_pos>20&&$this->new_pos<=35){
			$final_pos=	($margin*4)+13;
			}elseif($this->new_pos>35&&$this->new_pos<=55){
			$final_pos=	($margin*5)+17;
			}elseif($this->new_pos>55&&$this->new_pos<=80){
			$final_pos=	($margin*6)+21;
			}elseif($this->new_pos>80){
			$final_pos=	($margin*7)+24;
			}
			}
            imagecopy($resultImage, $finalWaterMarkImage, 0, $final_pos, 0, 0, $finalWaterMarkWidth, $finalWaterMarkHeight);
            $font = 'times.ttf';
			$blanc = imagecolorallocate($resultImage, 255, 255, 255);
			$orange = imagecolorallocate($resultImage, 128, 128, 128); // Le fond est orange (car c'est la première couleur)
			imagettftext($resultImage, 35, 0, 468, $final_pos+40, $blanc, $font, $this->new_pos);
			imagecolortransparent($resultImage, $orange); // On rend le fond orange transparent
            if($targetImageSize[2]==3){
                // PNG Image
                imagealphablending($resultImage,FALSE);
                imagesavealpha($resultImage,TRUE);
                imagepng($resultImage,$finalWatermarkedImageFile,$quality);
            } else {
                // JPEG Image
                imagejpeg($resultImage,$finalWatermarkedImageFile,$quality);
            }
            imagedestroy($resultImage);
            imagedestroy($finalWaterMarkImage);
            return TRUE;
        } else {
            $this->errorMessage = 'destination pas jpeg ou png';
            return FALSE;
        }
    }
    private function _checkGdLibrary(){
        if (extension_loaded('gd') && function_exists('gd_info')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}