<?php
putenv('GDFONTPATH=' . realpath('.'));
class gdlevel_GES
{
    protected $watermarkFile;
    protected $value;
    protected $type;
    protected $errorMessage = '';

    function __construct($watermarkImageFile, $valueGES, $filename, $type)
    {
        $this->watermarkFile = $watermarkImageFile;
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
            if ($this->type == 'GES')
            {
                $margin = 0;
                $vertical_pos = 0;
                $horizontal_pos = 0;
                if ($this->new_pos_GES >= 0 && $this->new_pos_GES <= 5)
                {
                    $final_pos = $margin;
                    $horizontal_pos = 230;
                    $vertical_pos = 100;
                }
                elseif ($this->new_pos_GES > 5 && $this->new_pos_GES <= 10)
                {
                    $final_pos = ($margin * 2) + 4;
                    $horizontal_pos = 260;
                    $vertical_pos = 155;
                }
                elseif ($this->new_pos_GES > 10 && $this->new_pos_GES <= 20)
                {
                    $final_pos = ($margin * 3) + 9;
                    $horizontal_pos = 290;
                    $vertical_pos = 220;
                }
                elseif ($this->new_pos_GES > 20 && $this->new_pos_GES <= 35)
                {
                    $final_pos = ($margin * 4) + 13;
                    $horizontal_pos = 320;
                    $vertical_pos = 270;
                }
                elseif ($this->new_pos_GES > 35 && $this->new_pos_GES <= 55)
                {
                    $final_pos = ($margin * 5) + 17;
                    $horizontal_pos = 360;
                    $vertical_pos = 325;
                }
                elseif ($this->new_pos_GES > 55 && $this->new_pos_GES <= 80)
                {
                    $final_pos = ($margin * 6) + 21;
                    $horizontal_pos = 400;
                    $vertical_pos = 380;
                }
                elseif ($this->new_pos_GES > 80)
                {
                    $final_pos = ($margin * 7) + 24;
                    $horizontal_pos = 400;
                    $vertical_pos = 440;
                }
            }			
			imagecopy($resultImage, $finalWaterMarkImage, 0, $final_pos, 0, 0, $finalWaterMarkWidth, $finalWaterMarkHeight);
            $font = 'times.ttf';
			$blanc = imagecolorallocate($resultImage, 150, 0, 0);
			$orange = imagecolorallocate($resultImage, 128, 128, 128); // Le fond est orange (car c'est la première couleur)
			imagettftext($resultImage, 30, 0, $horizontal_pos, $final_pos+$vertical_pos, $blanc, $font, $this->new_pos_GES);
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