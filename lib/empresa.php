<?php
	#include das funcoes da tela empresa
	include('functions/banco-empresa.php');

	#Instancia o objeto
	$banco = new bancoempresa();

	#Imprimi valores
	$Conteudo = $banco->CarregaHtml('empresa');
?>