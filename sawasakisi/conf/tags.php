<?php
	#Definições do Sistema
	date_default_timezone_set('America/Sao_Paulo');
	#Verifica SERVER (web/local)
    if (strpos($_SERVER['DOCUMENT_ROOT'], 'public_html') !== false) {
        #WEB
        define('UrlPadrao' , "http://www.sawasakiimoveis.com/sawasakisi/");
        define('UrlFoto', 'http://www.sawasakiimoveis.com/');
        define('UrlPdf', 'http://www.sawasakiimoveis.com/');
    	
    	#Definições do Banco de Dados
    	define('DB_Host' , "localhost");
    	define('DB_Database' , "sawas543_sawasaki");
    	define('DB_User' , "sawas543_admin");
    	define('DB_Pass' , '$RT%,.;');
    }else{
        #LOCAL
        define('UrlPadrao' , "http://localhost/sawasakiimoveis/sawasakisi/");
        define('UrlFoto', 'http://localhost/sawasakiimoveis/');
    	define('UrlPdf', 'http://127.0.0.1/sawasakiimoveis/');
        
    	#Definições do Banco de Dados
    	define('DB_Host' , "localhost");
    	define('DB_Database' , "sawasakisi");
    	define('DB_User' , "root");
    	define('DB_Pass' , '');
    }
?>