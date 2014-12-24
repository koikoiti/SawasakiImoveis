<?php
	#include das funcoes da tela inico
	include('functions/banco-imovel.php');

	#Instancia o objeto
	$banco = new bancoimovel();

	#Imprimi valores
	$Conteudo = $banco->CarregaHtml('imovel');
?>