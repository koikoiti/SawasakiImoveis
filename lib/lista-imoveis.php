<?php
    $pagina = 1;
    $botao_remover_filtro = '';
    
	#include das funcoes da tela inico
	include('functions/banco-imovel.php');
    
	#Instancia o objeto
	$banco = new bancoimovel();
    
    if($_GET){
        $idcategoria = $_GET['fcategoria'];
        $ce = utf8_decode($_GET['fcidadeestado']);
        $min = strip_tags(trim(addslashes($_GET["fmin"])));
        $max = strip_tags(trim(addslashes($_GET["fmax"])));
        $bairro = utf8_decode($_GET['bairro']);
        $dormitorios = strip_tags(trim(addslashes($_GET["dormitorios"])));
        $garagens = strip_tags(trim(addslashes($_GET["garagens"])));
        $botao_remover_filtro = '<button onclick="removerFiltro()" class="btn btn-peach" type="button" style="margin-bottom: 10px;">Remover Filtro</button>';
        
        if($_GET['page']){
            $pagina = $_GET['page'];
        }else{
            $pagina = 1;
        }
    }
    
    $imoveis = $banco->ListaImoveis($idcategoria, $ce, $min, $max, $bairro, $dormitorios, $garagens, $pagina);
    $paginacao = $banco->MontaPaginacao($idcategoria, $ce, $min, $max, $bairro, $dormitorios, $garagens, $pagina);
    $busca_categoria = $banco->SelectCategorias($idcategoria);
    $busca_cidade_estado = $banco->SelectCidadeEstado($ce);
    
	#Imprime valores
	$Conteudo = utf8_encode($banco->CarregaHtml('lista-imoveis'));
    $Conteudo = str_replace('<%IMOVEIS%>', $imoveis, $Conteudo);
    $Conteudo = str_replace('<%PAGINACAO%>', $paginacao, $Conteudo);
    $Conteudo = str_replace('<%BUSCACATEGORIA%>', $busca_categoria, $Conteudo);
    $Conteudo = str_replace('<%BUSCACIDADEESTADO%>', $busca_cidade_estado, $Conteudo);
    $Conteudo = str_replace('<%MIN%>', $min, $Conteudo);
    $Conteudo = str_replace('<%MAX%>', $max, $Conteudo);
    $Conteudo = str_replace('<%BAIRRO%>', utf8_encode($bairro), $Conteudo);
    $Conteudo = str_replace('<%DORMITORIOS%>', $dormitorios, $Conteudo);
    $Conteudo = str_replace('<%GARAGENS%>', $garagens, $Conteudo);
    $Conteudo = str_replace('<%REMOVERFILTRO%>', $botao_remover_filtro, $Conteudo);
?>