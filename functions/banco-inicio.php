<?php
	class bancoinicio extends banco{
		
        #Destaques1
        function MontaDestaques1(){
            $Auxilio = parent::CarregaHtml('itens/inicio-destaque-itens');
            $Sql = "SELECT I.*, M.* FROM t_imoveis I 
                    INNER JOIN t_imagens_imovel M ON I.idimovel = M.idimovel 
                    ORDER BY I.data_cadastro DESC LIMIT 0, 3";
            $result = parent::Execute($Sql);
            while($rs = mysql_fetch_array($result, MYSQL_ASSOC)){
                $Linha = $Auxilio;
                $Linha = str_replace('<%ID%>', $rs['idimovel'], $Linha);
                $Linha = str_replace('<%CAMINHO%>', $rs['caminho'], $Linha);
                $Linha = str_replace('<%REFERENCIA%>', $rs['referencia'], $Linha);
                $Linha = str_replace('<%DORMITORIOS%>', $rs['dormitorios'], $Linha);
                $Linha = str_replace('<%AREAUTIL%>', $rs['area_util'], $Linha);
                $Linha = str_replace('<%VALOR%>', number_format($rs['valor'], 2, ',', '.'), $Linha);
                $Linha = str_replace('<%DESCRICAO%>', $rs['descricao'], $Linha);
                $Linha = str_replace('<%NOVO%>', '<span class="sticker sticker-hot">Novo</span>', $Linha);
                $retorno .= $Linha;
            }
            
            return utf8_encode($retorno);
        }
        
        #Destaques2
        function MontaDestaques2(){
            $Auxilio = parent::CarregaHtml('itens/inicio-destaque-itens');
            $Sql = "SELECT I.*, M.* FROM t_imoveis I 
                    INNER JOIN t_imagens_imovel M ON I.idimovel = M.idimovel 
                    ORDER BY I.data_cadastro DESC LIMIT 3, 3";
            $result = parent::Execute($Sql);
            while($rs = mysql_fetch_array($result, MYSQL_ASSOC)){
                $Linha = $Auxilio;
                $Linha = str_replace('<%ID%>', $rs['idimovel'], $Linha);
                $Linha = str_replace('<%CAMINHO%>', $rs['caminho'], $Linha);
                $Linha = str_replace('<%REFERENCIA%>', $rs['referencia'], $Linha);
                $Linha = str_replace('<%DORMITORIOS%>', $rs['dormitorios'], $Linha);
                $Linha = str_replace('<%AREAUTIL%>', $rs['area_util'], $Linha);
                $Linha = str_replace('<%VALOR%>', number_format($rs['valor'], 2, ',', '.'), $Linha);
                $Linha = str_replace('<%DESCRICAO%>', $rs['descricao'], $Linha);
                $Linha = str_replace('<%NOVO%>', '<span class="sticker sticker-hot">Novo</span>', $Linha);
                $retorno .= $Linha;
            }
            
            return utf8_encode($retorno);
        }
        
        #Slider
        function MontaSlider(){
            $Auxilio = parent::CarregaHtml('itens/inicio-slider-itens');
            $Sql = "SELECT * FROM t_slider";
            $result = parent::Execute($Sql);
            while($rs = mysql_fetch_array($result, MYSQL_ASSOC)){
                $Linha = $Auxilio;
                $Linha = str_replace('<%CAMINHO%>', $rs['caminho'], $Linha);
                $Linha = str_replace('<%TEXTO1%>', $rs['texto1'], $Linha);
                $Linha = str_replace('<%TEXTO2%>', $rs['texto2'], $Linha);
                $Linha = str_replace('<%LINK%>', $rs['link'], $Linha);
                $Slider .= $Linha;
            }
            return $Slider;
        }
	}
?>