<?php
	#include das funcoes da tela busca
	include('functions/banco-busca.php');

	#Instancia o objeto
	$banco = new bancobusca();
    
    if($_GET){
        $order = $_GET['order'];
        if($_GET['pesquisa']){
            #Busca normal
            $pesquisa = utf8_decode($_GET['pesquisa']);
            $busca_titulo = '<h1 style="float: left; margin-top: 10px;">Resultados da busca para "<span class="search-terms">'.$pesquisa.'</span>"</h1>';
            
            $busca = $banco->MontaBuscaNormal($pesquisa, $order);
        }else{
            #Busca rápida
            $idcategoria = $_GET['tipo'];
            $ce = $_GET['ce'];
            $bairro = $_GET['bairro'];
            
            $busca = $banco->MontaBuscaRapidaItens($idcategoria, $ce, $bairro, $order);
            
            $busca_titulo = '<h1 style="float: left; margin-top: 10px;">Resultados da busca rápida:</h1>';
        }
    }
    
    $select_order = $banco->SelectOrder($order);
    
    $busca_rapida = $banco->MontaBuscaRapida($idcategoria);
    
	#Imprimi valores
	$Conteudo = utf8_encode($banco->CarregaHtml('busca'));
    $Conteudo = str_replace('<%BUSCARAPIDA%>', $busca_rapida, $Conteudo);
    $Conteudo = str_replace('<%BUSCAITENS%>', $busca, $Conteudo);
    $Conteudo = str_replace('<%TITULOBUSCA%>', utf8_encode($busca_titulo), $Conteudo);
    $Conteudo = str_replace('<%SELECTORDER%>', $select_order, $Conteudo);
?>