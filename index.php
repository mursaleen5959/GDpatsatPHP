<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

// Generate GES for 2022
function generate_GES($GES,$obj_id)
{
	$filename='img2022/'.$obj_id.'-GES-2022.jpg'; //destination

	if (file_exists($filename)) {
		if(!unlink($filename)){
			echo ("$filename cannot be deleted due to an error<br>");
		}
		else {
			echo ("<br> $filename has been deleted<br>");
		}
	}

	$valueGES=$GES;

    if($valueGES < 1) $layer = 'src_img2022/blank-2022.jpg'; //blank layer if value = 0
	elseif($valueGES >=0 	AND $valueGES <= 5 ) $layer = 'src_img2022/GES-A.jpg'; //layer for range = A
	elseif($valueGES >5 	AND $valueGES <= 10 ) $layer = 'src_img2022/GES-B.jpg';
	elseif($valueGES >10  	AND $valueGES <= 20  ) $layer = 'src_img2022/GES-C.jpg';
	elseif($valueGES >20 	AND $valueGES <= 35 ) $layer = 'src_img2022/GES-D.jpg';
	elseif($valueGES >35 	AND $valueGES <= 55 ) $layer = 'src_img2022/GES-E.jpg';
	elseif($valueGES >55 	AND $valueGES <= 80 ) $layer = 'src_img2022/GES-F.jpg'; //layer for range = F
	elseif($valueGES >80 ) $layer = 'src_img2022/GES-G.jpg';
    //else $layer='/data/fralimo/www/images-GES/ges1_layer-2022.png'; //else = layer with arrow

    $filename='img2022/'.$obj_id.'-GES-2022.jpg'; //destination
    $type='GES';
	echo "Layer=".$layer;
	echo "<br>";
	echo "Type=".$type;
	echo "<br>";
	echo "Value=".$valueGES.'<br>';
	$watermarker = new gdlevel_GES($layer,$valueGES,$filename,$type);
    try
	{
		$watermarker->applygdlevel($layer);
		?>
		<img src="img2022/<?php echo $obj_id ; ?>-GES-2022.jpg?r=<?echo(rand());?>" />
		<?php
	}catch (Exception $e)
	{
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}	
}


// Generate DPE for 2022
function generate_DPE($DPE,$GES,$obj_id)
{
	$filename='img2022/'.$obj_id.'-DPE-2022.jpg'; //destination

	if (file_exists($filename)) {
		if(!unlink($filename)){
			echo ("$filename cannot be deleted due to an error<br>");
		}
		else {
			echo ("<br>$filename has been deleted<br>");
		}
	}

	$valueDPE=$DPE;
	$valueGES=$GES;

    if($valueDPE < 1) $layer = 'src_img2022/blank-2022.jpg'; //blank layer if value = 0
	elseif($valueDPE >=0 	AND $valueDPE <= 70 ) $layer = 'src_img2022/DPE-A.jpg'; //layer for range = A
	elseif($valueDPE >70 	AND $valueDPE <= 110 ) $layer = 'src_img2022/DPE-B.jpg';
	elseif($valueDPE >110  	AND $valueDPE <= 180  ) $layer = 'src_img2022/DPE-C.jpg';
	elseif($valueDPE >180 	AND $valueDPE <= 250 ) $layer = 'src_img2022/DPE-D.jpg';
	elseif($valueDPE >250 	AND $valueDPE <= 330 ) $layer = 'src_img2022/DPE-E.jpg';
	elseif($valueDPE >330 	AND $valueDPE <= 421 ) $layer = 'src_img2022/DPE-F.jpg'; //layer for range = F
	elseif($valueDPE >420 ) $layer = 'src_img2022/DPE-G.jpg';
    //else $layer='/data/fralimo/www/images-DPE/dpe1_layer-2022.png'; //else = layer with arrow

    $filename='img2022/'.$obj_id.'-DPE-2022.jpg'; //destination
    $type='DPE';
	echo "Layer=".$layer;
	echo "<br>";
	echo "Type=".$type;
	echo "<br>";
	echo "Value=".$valueDPE.'<br>';
	$watermarker = new gdlevel($layer,$valueDPE,$valueGES,$filename,$type);
    try
	{
		$watermarker->applygdlevel($layer);
		?>
		<img src="img2022/<?php echo $obj_id ; ?>-DPE-2022.jpg?r=<?echo(rand());?>" />
		<?php
	}catch (Exception $e)
	{
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}	
}

function generate_old($DPE,$GES,$obj_id)
{	
	//
	$image='src_img2022/images-DPE/dpe1.jpg'; //fichier source
	$value=$DPE;
	if($value < 1) $layer = 'src_images/images-DPE/blank.png'; //calque VIERGE si pas de DPE
	else $layer='src_img2022/images-DPE/dpe1_layer.png'; //sinon calque avec FLECHE
	$filename='img2022/'.$obj_id.'-DPE.jpg'; //destination
	$type='DPE';
	$watermarker = new gdlevel($layer,$value,$filename,$type);
	if($watermarker->applygdlevel($image)){
			//print 'ok with '.$value;
			?>
			<img src="img2022/<?php echo $obj_id ; ?>-DPE.jpg?r=<?echo(rand());?>" />
			<?php
	}else{
			//print 'erreur classe DPE';
	}
	$image='src_img2022/images-DPE/dpe2.jpg'; //fichier source
	
	$value=$GES;
	if($value < 1)
	{
		$layer = 'src_img2022/images-DPE/blank.png'; //calque VIERGE si pas de DPE
	}
	else
	{
		$layer='src_img2022/images-DPE/dpe2_layer.png'; //sinon calque avec FLECHE
	}
	$filename='img2022/'.$obj_id.'-GES.jpg'; //destination
	$type='GES';
	$watermarker = new gdlevel($layer,$value,$filename,$type);
	if($watermarker->applygdlevel($image)){
			//print 'ok with '.$value;
			?>
			<br><br>
			<img src="img2022/<?php echo $obj_id ; ?>-GES.jpg?r=<?echo(rand());?>" />
			<br>
			<?php
	}else{
			//print 'erreur classe GES';
	}
}
//  	Form submittions here
if(isset($_GET['issubmit']) && $_GET['issubmit']==1)
{
	$DPE=$_GET['DPE'];
	$GES=$_GET['GES'];
	$obj_id=$_GET['object_id'];
	if($DPE[0]=='+' && $GES[0]=='+')
	{
		$DPE = substr($DPE, 1);
		$GES = substr($GES, 1);
		require 'class_dpe_2022.php';
		require 'class_ges_2022.php';
		generate_DPE($DPE,$GES,$obj_id);
		generate_GES($GES,$obj_id);
	}
	else
	{
		require 'classe_dpe.php';
		generate_old($DPE,$GES,$obj_id);
	}
}
?>
<form action="?" method="GET">
  <input type="hidden" name="issubmit" value="1"/>
  <label for="object_id">house ID:</label><br>
  <input type="text" id="object_id" name="object_id" value="<?php echo isset($_GET["object_id"])?$_GET['object_id']:''; ?>"><br>
  <label for="DPE">DPE:</label><br>
  <input type="text" id="DPE" name="DPE" value="<?php echo isset($_GET["DPE"])?$_GET['DPE']:''; ?>"><br>
  <label for="GES">GES:</label><br>
  <input type="text" id="GES" name="GES" value="<?php echo isset($_GET["GES"])?$_GET["GES"]:''; ?>"><br>
  <br>
  <input type="submit" value="Submit">
</form>