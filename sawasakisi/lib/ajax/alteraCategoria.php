<?php
	include('../../functions/banco.php');
	include('../../conf/tags.php');
	
    $banco = new banco;
	$banco->Conecta();
	session_start('login');
    
    $idcategoria = $_POST['idcategoria'];
    $categoria = $_POST['categoria'];
    
    $Sql = "UPDATE fixo_categorias_imovel SET nome = '$categoria' WHERE idcategoria = $idcategoria";
    $banco->Execute($Sql);
    
    echo 1;
?>