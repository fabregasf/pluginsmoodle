<?php
require_once('MoodleRest.php');

$MoodleRest = new MoodleRest('https://ead.uftm.edu.br/webservice/rest/server.php', 'meutokenmoodle');
$MoodleRest->setReturnFormat(MoodleRest::RETURN_ARRAY);

$idcurso = array('courseid' => 5385); // sala origem
$idcursodestino = 6316; // sala destino

// The default request's METHOD is to make a GET request, but you can change it to POST. This is recommended when inserting and updating data.
$return = $MoodleRest->request('core_enrol_get_enrolled_users', $idcurso, MoodleRest::METHOD_GET);

// URL da requisição anterior
echo "<h2>URL da sala de origem: </h2>". $MoodleRest->getUrl(). "<br><br>";

$mapaIdeGroup = array();
$gruposdistintos = array();
foreach ($return as $value) {

    $baseUrl = "https://ead.uftm.edu.br/webservice/rest/server.php?";
    $enrolments = array(array(
         'userid' => $value['id'],   
         'courseid' =>  $idcursodestino, 
         'roleid' =>  5,
         ));
    
    $params = array(
        'wstoken' => 'meutokenmoodle-----------',
        'wsfunction' => 'enrol_manual_enrol_users',
        'moodlewsrestformat' => 'json',
        'enrolments' => $enrolments,
    );
    $url = $baseUrl . http_build_query($params);
    
    // make enrolment
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

    $response = curl_exec($ch);
    
    curl_close($ch); 
    
    // array assoc. contendo iduser e grupo pertencente
    foreach ($value['groups'] as $pai) {
        $mapaIdeGroup[] = array("iduser" => $value['id'],
            "grupo" => $pai['name']);
        
        if (!in_array($pai['name'], $gruposdistintos)) {
            $gruposdistintos[] = $pai['name'];
        }
    }

    for ($i=0; $i < count($gruposdistintos);$i++){
        $groups = array(array(
            'name' => $gruposdistintos[$i],   
            'courseid' =>  $idcursodestino, 
            'description' =>  'criado pela api',
            ));
    
        $paramsgrp = array(
            'wstoken' => 'meutokenmoodle-----------',
            'wsfunction' => 'core_group_create_groups',
            'moodlewsrestformat' => 'json',
            'enrolments' => $groups,
        );
        $urlgrp = $baseUrl . http_build_query($paramsgrp);
    
        // make enrolment
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlgrp);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        $response = curl_exec($ch);
        
        curl_close($ch);        

    }
    
}

$basecourse = "https://ead.uftm.edu.br/course/view.php?id=".$idcurso['courseid'];
$destcourse = "https://ead.uftm.edu.br/course/view.php?id=".$idcursodestino;

echo "<br>";
echo "<div class=\"mensagemfinal\" style=\"background-color: #40a3e5; min-height: 100px;font-family:Arial;text-align: center;font-size: 16px;\">";
echo "<a href=\"$basecourse\" style=\"text-decoration: none;color: white;font-family: system-ui;font-size: 20px;\">Link da Sala copiada </a></div>";

echo "<br>";
echo "<div class=\"mensagemfinal\" style=\"background-color: #66a759; min-height: 100px;font-family:Arial;text-align: center;font-size: 16px;\">";
echo "<a href=\"$destcourse\" style=\"text-decoration: none;color: white;font-family: system-ui;font-size: 20px;\">Link da Sala destino </a></div>";






?>
