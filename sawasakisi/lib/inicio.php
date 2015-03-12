<?php
	#include das funcoes da tela inico
	include('functions/banco-inicio.php');

	#Instancia o objeto
	$banco = new bancoinicio();
    
    if($this->PaginaAux[0] == 'sair'){
        #Fazer logout
        $banco->FechaSessao();
    }elseif($this->PaginaAux[0] == 'acesso-negado'){
        $msg = "acesso negado";
    }
    
	#Imprime valores
	$Conteudo = utf8_encode($banco->CarregaHtml('inicio'));
    $Conteudo = str_replace('<%MSG%>', $msg, $Conteudo);
?>