<?php
	#include das funcoes da tela inico
	include('functions/banco-inicio.php');

	#Instancia o objeto
	$banco = new bancoinicio();
    
    $destaques = $banco->MontaDestaques();
        
    $slider = $banco->MontaSlider();
    
    $busca_rapida = $banco->MontaBuscaRapida();
    
	#Imprimi valores
	$Conteudo = utf8_encode($banco->CarregaHtml('inicio'));
    $Conteudo = str_replace('<%DESTAQUES%>', $destaques, $Conteudo);
    $Conteudo = str_replace('<%SLIDER%>', $slider, $Conteudo);
    $Conteudo = str_replace('<%BUSCARAPIDA%>', $busca_rapida, $Conteudo);
?>