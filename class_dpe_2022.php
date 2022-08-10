<?php
putenv('GDFONTPATH=' . realpath('.'));
class gdlevel
{
    protected $watermarkFile;
    protected $value;
    protected $type;
    protected $errorMessage = '';

    function __construct($watermarkImageFile, $valueDPE,$valueGES, $filename, $type)
    {
        $this->watermarkFile = $watermarkImageFile;
        $this->new_pos_DPE = $valueDPE;
        $this->new_pos_GES = $valueGES;
        $this->filename = $filename;
        $this->type = $type;
    }
    public function setWatermarkFile($imagePath)
    {
        $this->watermarkFile = $imagePath;
    }
    public function getLastErrorMessage()
    {
        return $this->errorMessage;
    }
    public function applygdlevel($unmarkedImagePath, $quality = 100)
    {
        $finalWatermarkedImageFile = $this->filename;
        $targetImageSize = getimagesize($unmarkedImagePath);        
		if ($targetImageSize[2] == 2 || $targetImageSize[2] == 3)
        {
            $originalImageName = $unmarkedImagePath;
            $watermark = $this->watermarkFile;
            $wmTarget = $this->watermarkFile;
            $origInfo = getimagesize($originalImageName);
            $origWidth = $origInfo[0];
            $origHeight = $origInfo[1];
            $waterMarkInfo = getimagesize($watermark);
            $waterMarkDestWidth = $waterMarkInfo[0];
            $waterMarkDestHeight = $waterMarkInfo[1];
            $differenceX = $origWidth - $waterMarkDestWidth;
            $differenceY = $origHeight - $waterMarkDestHeight;
            if ($targetImageSize[2] == 3)
            {
                $resultImage = imagecreatefrompng($originalImageName);
            }
            else
            {
                $resultImage = imagecreatefromjpeg($originalImageName);
            }
            imagealphablending($resultImage, true);
            $finalWaterMarkImage = imagecreatefromjpeg($wmTarget);
            $finalWaterMarkWidth = imagesx($finalWaterMarkImage);
            $finalWaterMarkHeight = imagesy($finalWaterMarkImage);
            if ($this->type == 'DPE')
            {
                $margin = 1;
                $vertical_pos = 0;
                if ($this->new_pos_DPE >= 0 && $this->new_pos_DPE <= 70)
                {
                    $final_pos = $margin;
                    $vertical_pos = 100;
                }
                elseif ($this->new_pos_DPE > 70 && $this->new_pos_DPE <= 110)
                {
                    $final_pos = ($margin * 2) - 5;
                    $vertical_pos = 156;
                }
                elseif ($this->new_pos_DPE > 110 && $this->new_pos_DPE <= 180)
                {
                    $final_pos = ($margin * 3) - 6;
                    $vertical_pos = 220;
                }
                elseif ($this->new_pos_DPE > 180 && $this->new_pos_DPE <= 250)
                {
                    $final_pos = ($margin * 4) - 11;
                    $vertical_pos = 280;
                }
                elseif ($this->new_pos_DPE > 250 && $this->new_pos_DPE <= 330)
                {
                    $final_pos = ($margin * 5) - 16;
                    $vertical_pos = 330;
                }
                elseif ($this->new_pos_DPE > 330 && $this->new_pos_DPE <= 420)
                {
                    $final_pos = ($margin * 6) - 18;
                    $vertical_pos = 385;
                }
                elseif ($this->new_pos_DPE > 420)
                {
                    $final_pos = ($margin * 7) - 21;
                    $vertical_pos = 440;
                }
            }
            if ($this->type == 'GES')
            {
                $margin = 57;
                if ($this->new_pos >= 0 && $this->new_pos <= 5)
                {
                    $final_pos = $margin;
                }
                elseif ($this->new_pos > 5 && $this->new_pos <= 10)
                {
                    $final_pos = ($margin * 2) + 4;
                }
                elseif ($this->new_pos > 10 && $this->new_pos <= 20)
                {
                    $final_pos = ($margin * 3) + 9;
                }
                elseif ($this->new_pos > 20 && $this->new_pos <= 35)
                {
                    $final_pos = ($margin * 4) + 13;
                }
                elseif ($this->new_pos > 35 && $this->new_pos <= 55)
                {
                    $final_pos = ($margin * 5) + 17;
                }
                elseif ($this->new_pos > 55 && $this->new_pos <= 80)
                {
                    $final_pos = ($margin * 6) + 21;
                }
                elseif ($this->new_pos > 80)
                {
                    $final_pos = ($margin * 7) + 24;
                }
            }			
			imagecopy($resultImage, $finalWaterMarkImage, 0, $final_pos, 0, 0, $finalWaterMarkWidth, $finalWaterMarkHeight);
            $font = 'times.ttf';
			$blanc = imagecolorallocate($resultImage, 150, 0, 0);
			$orange = imagecolorallocate($resultImage, 128, 128, 128); // Le fond est orange (car c'est la première couleur)
			imagettftext($resultImage, 30, 0, 15, $final_pos+$vertical_pos, $blanc, $font, $this->new_pos_DPE);
			imagettftext($resultImage, 30, 0, 100, $final_pos+$vertical_pos, $blanc, $font, $this->new_pos_GES);
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
            return true;
        }
        else
        {
            $this->errorMessage = 'destination pas jpeg ou png';
            return false;
        }
    }
    private function _checkGdLibrary()
    {
        if (extension_loaded('gd') && function_exists('gd_info'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}