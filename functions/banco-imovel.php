<?php
	class bancoimovel extends banco{
	   
		#Monta slider
        function MontaSliderImovel($idimovel){
            $Auxilio = parent::CarregaHtml('itens/imovel-slider-itens');
            $Sql = "SELECT * FROM t_imagens_imovel WHERE idimovel = $idimovel";
            $result = parent::Execute($Sql);
            while($rs = mysql_fetch_array($result, MYSQL_ASSOC)){
                $Linha = $Auxilio;
                $Linha = str_replace('<%CAMINHO%>', $rs['caminho'], $Linha);
                $Slider .= $Linha;
            }
            return $Slider;
        }
        
        #Lista imóveis
        function ListaImoveis($pagina){
            $inicio = ($pagina * Limite) - Limite;
            $Auxilio = parent::CarregaHtml('itens/lista-imoveis-itens');
            $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I 
                    INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria
                    ORDER BY I.data_cadastro ASC
                    LIMIT $inicio, ".Limite."
                    ";
            echo $Sql;
            $result = parent::Execute($Sql);
            while($rs = parent::ArrayData($result)){
                $Linha = $Auxilio;
                #Foto
                $SqlFoto = "SELECT * FROM t_imagens_imovel WHERE idimovel = " . $rs['idimovel'] . " ORDER BY caminho ASC";
                $resultFoto = parent::Execute($SqlFoto);
                $rsFoto = parent::ArrayData($resultFoto);
                $Linha = str_replace('<%ID%>', $rs['idimovel'], $Linha);
                $Linha = str_replace('<%CAMINHO%>', $rsFoto['caminho'], $Linha);
                $Linha = str_replace('<%REFERENCIA%>', $rs['referencia'], $Linha);
                $Linha = str_replace('<%CATEGORIA%>', utf8_encode($rs['categoria']), $Linha);
                $Linha = str_replace('<%BAIRRO%>', utf8_encode($rs['bairro']), $Linha);
                $Linha = str_replace('<%CIDADEESTADO%>', utf8_encode($rs['cidade'] . "/" . $rs['estado']), $Linha);
                $Linha = str_replace('<%VALOR%>', number_format($rs['valor'], 2, ',', '.'), $Linha);
                
                #Colocar 'novo' para imóveis cadastrados a 15 dias
                if(strtotime($rs['data_cadastro']) < strtotime('-15 day') ){
                    $label_novo = '';
                }else{
                    $label_novo = '<span class="sticker sticker-hot">Novo</span>';
                }
                $Linha = str_replace('<%NOVO%>', $label_novo, $Linha);
                $retorno .= $Linha;
            }
            return $retorno;
        }
        
        #Monta paginacao
        function MontaPaginacao(){
            /*
            <a href="#" class="page">&laquo;</a>
                    <a href="#" class="page">2</a>
                    <a href="#" class="page">3</a>
                    <span class="page active">4</span>
                    <a href="#" class="page">5</a>
                    <a href="#" class="page">6</a>
                    <a href="#"class="page">&raquo;</a>
            */
        }
	}
?>