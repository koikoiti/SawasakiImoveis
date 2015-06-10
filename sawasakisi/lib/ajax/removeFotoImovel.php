<?php
	include('../../functions/banco.php');
	include('../../conf/tags.php');
	
    $banco = new banco;
	$banco->Conecta();
	session_start('login');
    
    $idimagemimovel = $_POST['idimagemimovel'];
    $idimovel = $_POST['idimovel'];
    
    
    $Sql = "SELECT * FROM t_imagens_imovel WHERE idimagemimovel = $idimagemimovel";
	$result = $banco->Execute($Sql);
	$rs = $banco->ArrayData($result);
    
    if (strpos($_SERVER['DOCUMENT_ROOT'], 'public_html') !== false) {
        $caminhoRemover = $_SERVER['DOCUMENT_ROOT'] . "/" . $rs['caminho'];
    }else{
        $caminhoRemover = $_SERVER['DOCUMENT_ROOT'] . "/sawasakiimoveis/" . $rs['caminho'];
    }
    
	unlink($caminhoRemover);
    
	$SqlDeleta = "DELETE FROM t_imagens_imovel WHERE idimagemimovel = $idimagemimovel";
	if($banco->Execute($SqlDeleta)){
		$SqlOrder = "SELECT * FROM t_imagens_imovel WHERE idimovel = '$idimovel' ORDER BY ordem ASC";
		$resultOrder = $banco->Execute($SqlOrder);
		$cont = 1;
		while($rsOrder = $banco->ArrayData($resultOrder)){
			$SqlNewOrder = "UPDATE t_imagens_imovel SET ordem = '$cont' WHERE idimagemimovel = " . $rsOrder['idimagemimovel'];
			$banco->Execute($SqlNewOrder);
			$cont++;
		}
		echo 1;
	}else{
		echo 9;
	}
?>