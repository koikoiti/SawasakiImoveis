<?php
	#include das funcoes da tela busca
	include('functions/banco-busca.php');

	#Instancia o objeto
	$banco = new bancobusca();
    
    if($_GET){
        if($_GET['pesquisa']){
            #Busca normal
            $pesquisa = utf8_decode($_GET['pesquisa']);
            $busca_titulo = '<h1>Resultados da busca para "<span class="search-terms">'.$pesquisa.'</span>"</h1>';
            
            $busca = $banco->MontaBuscaNormal($pesquisa);
        }else{
            #Busca r�pida
            $idcategoria = $_GET['tipo'];
            $ce = $_GET['ce'];
            
            $busca = $banco->MontaBuscaRapidaItens($idcategoria, $ce);
            
            $busca_titulo = '<h1>Resultados da busca r�pida:</h1>';
        }
    }
    
    
    
    $busca_rapida = $banco->MontaBuscaRapida($idcategoria);
    
	#Imprimi valores
	$Conteudo = utf8_encode($banco->CarregaHtml('busca'));
    $Conteudo = str_replace('<%BUSCARAPIDA%>', $busca_rapida, $Conteudo);
    $Conteudo = str_replace('<%BUSCAITENS%>', $busca, $Conteudo);
    $Conteudo = str_replace('<%TITULOBUSCA%>', utf8_encode($busca_titulo), $Conteudo);
?>