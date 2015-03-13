<?php
	#include das funcoes da tela busca
	include('functions/banco-busca.php');

	#Instancia o objeto
	$banco = new bancobusca();
    
    if($_GET){
        if($_GET['pesquisa']){
            #Busca normal
            
        }else{
            #Busca rápida
            $idcategoria = $_GET['tipo'];
            $ce = $_GET['ce'];
            
            $busca = $banco->MontaBuscaRapidaItens($idcategoria, $ce);
        }
    }
    
    
    
    $busca_rapida = $banco->MontaBuscaRapida($idcategoria);
    
	#Imprimi valores
	$Conteudo = utf8_encode($banco->CarregaHtml('busca'));
    $Conteudo = str_replace('<%BUSCARAPIDA%>', $busca_rapida, $Conteudo);
    $Conteudo = str_replace('<%BUSCAITENS%>', $busca, $Conteudo);
?>