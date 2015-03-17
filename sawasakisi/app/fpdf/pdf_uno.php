<?php
	require('fpdf.php');
	
	class PeDeeFe extends FPDF{
		
		#Header
		function Header(){
			$pathLogo = "arq/logos/Uno.jpg";
			$this->SetXY(10, 5);
			$this->Image($pathLogo);
			$y = $this->GetY() + 3;
			$this->Line(10, $y, 200, $y);
			$this->SetFont('Arial', '', 12);
			$y = $this->GetY() + 6;
			$this->SetXY(129, $y);
		}
		
		#Footer
		function Footer(){
			$this->SetY(-25);
			$y = $this->GetY() + 5;
			$this->Line(10, $y, 200, $y);
			$y = $this->GetY() + 8;
			$this->SetXY(0, $y);
			$this->SetFont('Arial', '', 10);
			$this->Cell(0, 0, "Uno Comercial de Equipamentos Ltda - CNPJ 12.831.481/0001-66 - I.E. 90538776-61", 0, 0, 'C');
			$y = $this->GetY() + 4.5;
			$this->SetXY(0, $y);
			$this->SetFont('Arial', '', 9);
			$this->Cell(0, 0, "Rua C�nego Janu�rio da Cunha Barbosa, 126 - Sobrado 03 - CEP: 81.530-480 - Uberaba - Curitiba/PR", 0, 0, 'C');
			$y = $this->GetY() + 4;
			$this->SetXY(0, $y);
			$this->Cell(0, 0, "Fone: (41) 3068-3673 - Fax: (41) 3068-3668 - e-mail: editais@unoempresarial.com.br", 0, 0, 'C');
			$y = $this->GetY() + 4;
			$this->SetXY(0, $y);
			$this->Cell(0, 0, "P�gina " . $this->PageNo() . "/{nb}", 0, 0, 'C');
		}
	}
?>