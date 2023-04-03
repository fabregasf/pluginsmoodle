<?php 

require_once('lib.php');

$pdffile = new MeuPdf();
$novoarquivo = $pdffile->novoPdf('teste.pdf');

$getdata = $pdffile->getDataFromPdf("/[0-9]{4}\.[0-9]{4}\.[0-9]{1}/", $novoarquivo);

echo "Dados extraidos:\n";
print_r($getdata);  


$getmatriculas = $pdffile->getMatricula($getdata);
echo "\nConsulta no banco:\n";
print_r($getmatriculas);  

//$setmatr = $pdffile->setMatriculas($getdata);




