<?php 
require_once("../../config.php");
require_once('../../lib/setuplib.php');
require_once('../../user/externallib.php');
include ('./pdf/pdftotext/PdfToText.phpclass');

global $CFG,  $USER;


class MeuPdf{    
    

    public static function novoPdf($file){         
        return new PdfToText ($file); 

    }
    // Especificar a expressao a ser buscada. exemplo: xxxx.xxxx.x matricula
    public static function getDataFromPdf($regex, $file){
        preg_match_all($regex, $file->Text, $matches);	
        
        for ($index = 0; $index < sizeof($matches[0]); $index++) { 
            $matches[0][$index] = str_replace('.','', $matches[0][$index]);    
        }
        return $matches[0];
    }

    
    // Pesquisa matriculas depois de ler o pdf
    public static function getMatricula($field){

        global $DB;        
        $usuariosget = array();
        set_error_handler(array(new MeuPdf(),'errocustom'));

        if (is_array($field)){
            foreach ($field as $matricula){                 
                    $usuariosget[] = $DB->get_record_select("user", "username = '". $matricula ."' AND suspended = 0;"
                            , null, "*", MUST_EXIST); 
                    
                    // tentar executar em baixo nivel para desconsiderar
                    // os itens não existentes na transação 
            }            
            
        }      
       
        return $usuariosget; 
    }

    public static function setMatriculas($field){
            global $DB; 
            $fn = "namee";
            $ln = "lastnamee";
            //if (is_array($field)){
                //foreach ($field as $matricula){
                    // executando uma querie bruta
                    $retorno = $DB->execute("insert into mdl_user(username, firstname, lastname) values('2222222', '".$fn."','"
                        .$ln."')" );
                    
                    if ($retorno == 0)  trigger_error("Ocorreu um erro na inserção");

               // }
          //  }

    }

    // Erro customizado para a classe MeuPDF
    public static function errocustom( $errno, $errstr, $errfile, $errline) {
        echo "<b> String com erro:</b> [$errno] $errstr<br>";
        echo " Error on line $errline in $errfile<br>";    
    }
}

/*class core_event_get_users_observer {

    public static function observe_user($event){
        $userid = $event->objectid; // created user id
        print_R($event);
        // write your code here and do the needful operation
    }

}*/