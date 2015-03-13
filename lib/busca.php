<?php
	#include das funcoes da tela busca
	include('functions/banco-busca.php');

	#Instancia o objeto
	$banco = new bancobusca();
    
    if($_GET){
        if($_GET['pesquisa']){
            
        }else{
            $idcategoria = $_GET['tipo'];
        }
    }
    
    $busca_rapida = $banco->MontaBuscaRapida($idcategoria);
    
	#Imprimi valores
	$Conteudo = utf8_encode($banco->CarregaHtml('busca'));
    $Conteudo = str_replace('<%BUSCARAPIDA%>', $busca_rapida, $Conteudo);
?>