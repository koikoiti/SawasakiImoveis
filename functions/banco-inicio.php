<?php
	class bancoinicio extends banco{
		
        #Destaques1
        function MontaDestaques1(){
            $Auxilio = parent::CarregaHtml('itens/inicio-itens');
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
                $retorno .= $Linha;
            }
            
            return utf8_encode($retorno);
        }
        
        #Destaques2
        function MontaDestaques2(){
            $Auxilio = parent::CarregaHtml('itens/inicio-itens');
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
                $retorno .= $Linha;
            }
            
            return utf8_encode($retorno);
        }
	}
?>