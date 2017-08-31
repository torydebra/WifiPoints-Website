<?php
//https://stackoverflow.com/questions/6484307/how-to-check-if-an-uploaded-file-is-an-image-without-mime-type
//converte immagine in png sempre, più gestibile e almeno formati immagini sempre gli stessi.
function imageUploaded($source, $target){

	$size = getimagesize($source);

	if ($size === false) {
		echo ('<p>Punto inserito ma immagine non valida<p>
		<p><a href="../checkPositionAddedPoint.php">Continua
		<i class="fa fa-undo" aria-hidden="true"></i></a>
		</p>');
		die;

	} else if ($size[0] > 2000 || $size[1] > 2000) { //larghezza e altezza max: 2000
		echo ('<p>Punto inserito ma immagine troppo grande<p>
		<p><a href="../checkPositionAddedPoint.php">Continua
		<i class="fa fa-undo" aria-hidden="true"></i></a>
		</p>');
		die;
	} else {

		// loads it and convert it to png
		$sourceImg = @imagecreatefromstring(@file_get_contents($source));
		if ($sourceImg === false) {
			echo ('<p>Punto inserito ma immagine non valida<p>
			<p><a href="../checkPositionAddedPoint.php">Continua
			<i class="fa fa-undo" aria-hidden="true"></i></a>
			</p>');
		die;

		} else{

			$width = imagesx($sourceImg);
			$height = imagesy($sourceImg);
			$widthTarget = 1000;
			$heightTarget = 500;
			$targetImg = imagecreatetruecolor($widthTarget, $heightTarget);
			imagecopyresampled($targetImg, $sourceImg, 0, 0, 0, 0, $widthTarget, $heightTarget, $width, $height);
			imagedestroy($sourceImg);
			imagepng($targetImg, $target); //output targetimg in percorso target
			imagedestroy($targetImg);
		}
	}
}

//non reindirizza al check poisition come quella sopra visto che è chiamata dal bottone di modifica foto
//da pagina punto già inserito
function imageUploadedfromModify($source, $target){

	$size = getimagesize($source);

	if ($size === false) {
		echo ('<p> Immagine non valida<p>');
		die;

	} else if ($size[0] > 2000 || $size[1] > 2000) { //larghezza e altezza max: 2000
		echo ('<p> Immagine troppo grande<p>');
		die;
	} else {

		// loads it and convert it to png
		$sourceImg = @imagecreatefromstring(@file_get_contents($source));
		if ($sourceImg === false) {
			echo ('<p> Immagine non valida<p>');
		die;

		} else{

			$width = imagesx($sourceImg);
			$height = imagesy($sourceImg);
			$widthTarget = 1000;
			$heightTarget = 500;
			$targetImg = imagecreatetruecolor($widthTarget, $heightTarget);
			imagecopyresampled($targetImg, $sourceImg, 0, 0, 0, 0, $widthTarget, $heightTarget, $width, $height);
			imagedestroy($sourceImg);
			imagepng($targetImg, $target); //output targetimg in percorso target
			imagedestroy($targetImg);
		}
	}
}

?>
