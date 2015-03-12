<?php
	class bancogerenciar extends banco{
		
        #Busca imagens slider
        function BuscaImagensSlider(){
            $Auxilio = parent::CarregaHtml('Slider/slider-itens');
            $Sql = "SELECT * FROM t_slider";
            $result = parent::Execute($Sql);
            while($rs = parent::ArrayData($result)){
                $Linha = $Auxilio;
                $Linha = str_replace('<%ID%>', $rs['idslider'], $Linha);
                $Linha = str_replace('<%CAMINHO%>', UrlFoto.$rs['caminho'], $Linha);
                $Linha = str_replace('<%TEXTO1%>', $rs['texto1'], $Linha);
                $Linha = str_replace('<%TEXTO2%>', $rs['texto2'], $Linha);
                $Slider .= $Linha;
            }
            return $Slider;
        }
	}
?>