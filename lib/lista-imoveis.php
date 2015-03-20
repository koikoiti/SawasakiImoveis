<?php
    $pagina = 1;
    
	#include das funcoes da tela inico
	include('functions/banco-imovel.php');
    
	#Instancia o objeto
	$banco = new bancoimovel();
    
    if($_GET){
        $pagina = $_GET['page'];
    }
    
    $imoveis = $banco->ListaImoveis($pagina);
    $paginacao = $banco->MontaPaginacao($pagina);
    
	#Imprime valores
	$Conteudo = utf8_encode($banco->CarregaHtml('lista-imoveis'));
    $Conteudo = str_replace('<%IMOVEIS%>', $imoveis, $Conteudo);
?>