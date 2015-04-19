<?php
    $titulo = 'Gerenciar Categorias Imóvel';
    
	#include das funcoes da tela gerenciar-categorias
	include('functions/banco-gerenciar-categorias.php');

	#Instancia o objeto
	$banco = new bancogerenciarcategorias();
    
    $categorias = $banco->MontaCategorias();
    
    #Imprime valores
	$Conteudo = utf8_encode($banco->CarregaHtml('Imovel/gerenciar-categorias'));
    $Conteudo = str_replace('<%CATEGORIAS%>', utf8_encode($categorias), $Conteudo);
    $Conteudo = str_replace('<%TITULO%>', utf8_encode($titulo), $Conteudo);
?>