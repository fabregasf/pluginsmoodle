<?php
	include ('pdftotext/PdfToText.phpclass');	
    	$file = 'teste.pdf';
	
	$pdf = new PdfToText ($file);
	
	echo ( "Extracted file contents :\n" ) ;
	echo ( $pdf -> Text ) ;

    	$regex = "/[0-9]{4}\.[0-9]{4}\.[0-9]{1}/";
    

    	preg_match_all($regex, $pdf->Text, $matches);
    	echo ("matches:\n");
    	echo "<pre>";
    	print_r($matches);
	echo "</pre>";
   ?>