<?php
	#include das funcoes da tela inico
	include('functions/banco-imovel.php');

	#Instancia o objeto
	$banco = new bancoimovel();
    
    $Imoveis = $banco->ListaImoveis();
    
	#Imprime valores
	$Conteudo = utf8_encode($banco->CarregaHtml('Imovel/lista-imovel'));
    $Conteudo = str_replace("<%IMOVEIS%>", $Imoveis, $Conteudo);
?>