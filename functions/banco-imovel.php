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
	}
?>