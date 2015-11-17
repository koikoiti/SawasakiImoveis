<?php
	include('../../functions/banco.php');
	include('../../conf/tags.php');
	
    $banco = new banco;
	$banco->Conecta();
	session_start('login');
    
    $categoria = utf8_decode($_POST['categoria']);
    
    $Sql = "INSERT INTO fixo_categorias_imovel (nome) VALUES ('$categoria')";
    $banco->Execute($Sql);
    
    echo 1;
?>