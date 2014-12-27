<?php
	#include das funcoes da tela contato
	include('functions/banco-contato.php');

	#Instancia o objeto
	$banco = new bancocontato();

	#Imprimi valores
	$Conteudo = $banco->CarregaHtml('contato');
?>