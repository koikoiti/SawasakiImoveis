<?php
	#include das funcoes da tela imovel
	include('functions/banco-imovel.php');

	#Instancia o objeto
	$banco = new bancoimovel();

	#Imprime valores
	$Conteudo = $banco->CarregaHtml('imovel');
?>