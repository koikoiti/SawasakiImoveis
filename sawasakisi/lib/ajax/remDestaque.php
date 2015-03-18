<?php
	include('../../functions/banco.php');
	include('../../conf/tags.php');
	
    $banco = new banco;
	$banco->Conecta();
	session_start('login');
    
    $idimovel = $_POST['idimovel'];
    
    $Sql = "DELETE FROM t_destaques WHERE idimovel = $idimovel";
    $banco->Execute($Sql);
    echo 1;
?>