<?php

namespace SITE_PAGE_MODULES;

class GenerateHTML {
	
	//
	// ���������� ������ ������ ������, ���� ���� ID.
	// ������ ������ ������ ��������� ������� @@ ��� ����������� ID.
	// ������ ������ ������ ���������� ������� ** ��� ����������� �����.
	// ���� ID ���, �� ������������ ������ � �������.
	//
	
	public static function GetLinkIfId($TemplateStr, $InnerText, $ID) {
		if ($ID !== null) {
			$TemplateStr	= str_replace('@@', $ID, $TemplateStr);
			$TemplateStr	= str_replace('**', $InnerText, $TemplateStr);
			return $TemplateStr;
		} else {
			return $InnerText;
		}
	}
	
	//
	// ���������� ��������� ���� � ����������
	//
	
	public static function Alert($HTMLMessage) {
		?>
		<div id="modal01" class="w3-modal"  style="display: block" onclick="this.style.display='none'">
		    <div class="w3-modal-content">
		      <div class="w3-container w3-theme-l1"> 
		        <span class="w3-button w3-display-topright">&times;</span>
		        <h3>���������</h3>
		      </div>
		      <div class="w3-container">
		        <p><?php echo $HTMLMessage; ?></p>
		      </div>
		      <div class="w3-container w3-theme-l1">
		        <p></p>
		      </div>
		    </div>
		</div>
		<?php 
	}
}

?>