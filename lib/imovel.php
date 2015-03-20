<?php
	#include das funcoes da tela imovel
	include('functions/banco-imovel.php');

	#Instancia o objeto
	$banco = new bancoimovel();
        
    $idimovel = $this->PaginaAux[0];
    
    $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I 
            INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria
            WHERE idimovel = $idimovel";
    
    $result = $banco->Execute($Sql);
    $rs = mysql_fetch_array($result, MYSQL_ASSOC);
    
    $enderecomaps = $rs['endereco'] . " " . $rs['bairro'] . " " . $rs['cidade'] . " " . $rs['estado'];
    
    $slider = $banco->MontaSliderImovel($idimovel);
    
	#Imprime valores
	$Conteudo = $banco->CarregaHtml('imovel');
    $Conteudo = str_replace('<%ENDERECOMAPS%>', urlencode(utf8_encode($enderecomaps)), $Conteudo);
    $Conteudo = str_replace('<%SLIDER%>', $slider, $Conteudo);
    $Conteudo = str_replace('<%ENDERECO%>', utf8_encode($rs['endereco'] . " - " . $rs['cidade'] . "/" . $rs['estado']), $Conteudo);
    $Conteudo = str_replace('<%QUARTOS%>', $rs['dormitorios'], $Conteudo);
    $Conteudo = str_replace('<%REFERENCIA%>', $rs['referencia'], $Conteudo);
    $Conteudo = str_replace('<%GARAGEM%>', $rs['garagem'], $Conteudo);
    $Conteudo = str_replace('<%CATEGORIA%>', utf8_encode($rs['categoria']), $Conteudo);
    $Conteudo = str_replace('<%AREATOTAL%>', utf8_encode($rs['area_total']), $Conteudo);
    $Conteudo = str_replace('<%VALOR%>', number_format($rs['valor'], 2, ',', '.'), $Conteudo);
    $Conteudo = str_replace('<%DESCRICAO%>', utf8_encode($rs['descricao']), $Conteudo);
    $Conteudo = str_replace('<%SLIDER%>', $slider, $Conteudo);
    
?>