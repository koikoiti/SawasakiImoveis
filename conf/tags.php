<?php
	#Defini��es do Sistema
	date_default_timezone_set('America/Sao_Paulo');
    
    #PHPMailer
    define('loginGmail', '');
    define('pwGmail', '');
    
    #Verifica SERVER (web/local)
    if (strpos($_SERVER['DOCUMENT_ROOT'], 'public_html') !== false) {
        #WEB
        define('UrlPadrao' , "http://www.sawasakiimoveis.com/");
    	
    	#Defini��es do Banco de Dados
    	define('DB_Host' , "localhost");
    	define('DB_Database' , "sawas543_sawasaki");
    	define('DB_User' , "sawas543_admin");
    	define('DB_Pass' , '$RT%,.;');
    }else{
        #LOCAL
        define('UrlPadrao' , "http://localhost/sawasakiimoveis/");
    	
    	#Defini��es do Banco de Dados
    	define('DB_Host' , "localhost");
    	define('DB_Database' , "sawasakisi");
    	define('DB_User' , "root");
    	define('DB_Pass' , '');
        
    }
?>