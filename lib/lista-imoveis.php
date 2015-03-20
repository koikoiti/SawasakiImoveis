<?php
	#include das funcoes da tela inico
	include('functions/banco-imovel.php');

	#Instancia o objeto
	$banco = new bancoimovel();

	#Imprime valores
	$Conteudo = utf8_encode($banco->CarregaHtml('lista-imoveis'));
?>