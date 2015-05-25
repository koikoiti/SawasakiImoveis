<?php
	class bancobusca extends banco{
		
        #Monta itens busca rapida
        function MontaBuscaRapidaItens($idcategoria, $ce, $bairro){
            $Auxilio = utf8_encode(parent::CarregaHtml('itens/busca-itens'));
            $Sql = "SELECT I.*, C.nome AS categoria, M.* FROM t_imoveis I 
                    INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria 
                    INNER JOIN t_imagens_imovel M ON M.idimovel = I.idimovel
                    INNER JOIN 
                    (
                      SELECT *, MIN(caminho) AS minC FROM t_imagens_imovel GROUP BY idimovel
                    ) X ON X.idimovel = I.idimovel AND M.caminho = X.minC
                    WHERE 1";
            if($idcategoria != ''){
                $Sql .= " AND I.idcategoria = '$idcategoria'";
            }
            if($ce){
                $Sql .= " AND (";
                foreach($ce as $value){
                    $aux = explode('_', $value);
                    $Sql .= " (I.cidade = '".utf8_decode($aux[0])."' AND I.estado = '".utf8_decode($aux[1])."') OR";
                }
                $Sql = rtrim($Sql, ' OR');
                $Sql .= ")";
            }
            if($bairro){
                $Sql .= " AND (";
                foreach($bairro as $val){
                    $Sql .= "(I.bairro = '".utf8_decode($val)."') OR";
                }
                $Sql = rtrim($Sql, ' OR');
                $Sql .= ")";
            }
            $result = parent::Execute($Sql);
            while($rs = parent::ArrayData($result)){
                $Linha = $Auxilio;
                $Linha = str_replace('<%CAMINHO%>', $rs['caminho'], $Linha);
                $Linha = str_replace('<%ID%>', $rs['idimovel'], $Linha);
                $Linha = str_replace('<%VALOR%>', number_format($rs['valor'], 2, ',', '.'), $Linha);
                $Linha = str_replace('<%REFERENCIA%>', utf8_encode($rs['referencia']), $Linha);
                $Linha = str_replace('<%DORMITORIOS%>', utf8_encode($rs['dormitorios']), $Linha);
                $Linha = str_replace('<%BAIRRO%>', utf8_encode($rs['bairro']), $Linha);
                $Linha = str_replace('<%GARAGEM%>', utf8_encode($rs['garagem']), $Linha);
                $Linha = str_replace('<%AREATOTAL%>', utf8_encode($rs['area_total']), $Linha);
                $tipo_endereco = $rs['categoria'] . " - " . $rs['cidade'] . "/" . $rs['estado'];
                $Linha = str_replace('<%TIPOENDERECO%>', utf8_encode($tipo_endereco), $Linha);
                $Busca .= $Linha;
            }
            
            return $Busca;
        }
        
        #Monta busca normal
        function MontaBuscaNormal($pesquisa){
            $Auxilio = utf8_encode(parent::CarregaHtml('itens/busca-itens'));
            $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I 
                    INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria 
                    WHERE I.referencia LIKE '%$pesquisa%' 
                    OR I.bairro LIKE '%$pesquisa%' 
                    OR I.endereco LIKE '%$pesquisa%'
                    OR I.cidade LIKE '%$pesquisa%'";
            $result = parent::Execute($Sql);
            while($rs = parent::ArrayData($result)){
                $Linha = $Auxilio;
                $SqlCaminho = "SELECT * FROM t_imagens_imovel WHERE idimovel = " . $rs['idimovel'] . " ORDER BY caminho ASC";
                $resultCaminho = parent::Execute($SqlCaminho);
                $rsCaminho = parent::ArrayData($resultCaminho);
                $Linha = str_replace('<%CAMINHO%>', $rsCaminho['caminho'], $Linha);
                $Linha = str_replace('<%ID%>', $rs['idimovel'], $Linha);
                $Linha = str_replace('<%VALOR%>', number_format($rs['valor'], 2, ',', '.'), $Linha);
                $Linha = str_replace('<%REFERENCIA%>', utf8_encode($rs['referencia']), $Linha);
                $Linha = str_replace('<%DORMITORIOS%>', utf8_encode($rs['dormitorios']), $Linha);
                $Linha = str_replace('<%BAIRRO%>', utf8_encode($rs['bairro']), $Linha);
                $Linha = str_replace('<%GARAGEM%>', utf8_encode($rs['garagem']), $Linha);
                $Linha = str_replace('<%AREATOTAL%>', utf8_encode($rs['area_total']), $Linha);
                $tipo_endereco = $rs['categoria'] . " - " . $rs['cidade'] . "/" . $rs['estado'];
                $Linha = str_replace('<%TIPOENDERECO%>', utf8_encode($tipo_endereco), $Linha);
                $Busca .= $Linha;
            }
            
            return $Busca;
        }
	}
?>