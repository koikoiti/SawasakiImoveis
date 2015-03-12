<?php
	#include das funcoes da tela gerenciar-slider
	include('functions/banco-gerenciar.php');

	#Instancia o objeto
	$banco = new bancogerenciar();
    
    $slider = $banco->BuscaImagensSlider();
    
	#Imprime valores
	$Conteudo = utf8_encode($banco->CarregaHtml('gerenciar-slider'));
    $Conteudo = str_replace('<%SLIDER%>', $slider, $Conteudo);
?>