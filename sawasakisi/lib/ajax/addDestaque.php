<?php
	include('../../functions/banco.php');
	include('../../conf/tags.php');
	
    $banco = new banco;
	$banco->Conecta();
	session_start('login');
    
    $referencia = $_POST['ref'];
    
    $SqlVerifica = "SELECT D.* FROM t_destaques D 
                    INNER JOIN t_imoveis I ON D.idimovel = I.idimovel 
                    WHERE I.referencia = '$referencia'";
    $resultVerifica = $banco->Execute($SqlVerifica);
    $linhasVerifica = $banco->Linha($resultVerifica);
    
    if($linhasVerifica){
        echo 444;
    }else{
        $SqlID = "SELECT idimovel FROM t_imoveis WHERE referencia = '$referencia'";
        $resultID = $banco->Execute($SqlID);
        $linhaID = $banco->Linha($resultID);
        if($linhaID){
            $rsID = $banco->ArrayData($resultID);
            $SqlInsert = "INSERT INTO t_destaques (idimovel) VALUES (".$rsID['idimovel'].")";
            $banco->Execute($SqlInsert);
            echo 1;
        }else{
            echo 666;
        }
    }
?>