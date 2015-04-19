<?php
    class bancogerenciarcategorias extends banco{
        
        function MontaCategorias(){
            $Auxilio = parent::CarregaHtml('Imovel/itens/gerenciar-categorias-itens');
            $Sql = "SELECT * FROM fixo_categorias_imovel";
            $result = parent::Execute($Sql);
            while($rs = parent::ArrayData($result)){
                $Linha = $Auxilio;
                $Linha = str_replace('<%IDCATEGORIA%>', $rs['idcategoria'], $Linha);
                $Linha = str_replace('<%NOMECATEGORIA%>', $rs['nome'], $Linha);
                $retorno .= $Linha;
            }
            return $retorno;
        }
    }
?>