<?php

namespace SITE_PAGE_MODULES;


//
// $SQLQuery["Name"] : {
//			"RequireName"		: string
//			, "Caption"			: string
//			, "Table"			: string
//			, "Alias" 			: string
//			, "Join" 			: string
//			, "JoinCondition" 	: string
//			, "Select" 			: string
//			, "Finally" 		: string
//			, "Finally Offset"	: string
// }
//

//
// Части запроса SQL.
// Часть $Other присоединяется к общему запросу только если
// в фильтре указан соответствующий RequireName.
//

class ClientFilterQueries {
	
	public static $Basic = array(
			
		'Clients' => array(
			'Table'			=> 'Clients'
			, 'Alias'			=> 'cl'
			, 'Join' 			=> 'FROM'
			, 'Select'			=> 'cl.id cl_id, cl.Name cl_Name'
		)
		, 'Contracts' => array(
			'RequireName'		=> 'Clients'
			, 'Table'			=> 'Contracts'
			, 'Alias'			=> 'con'
			, 'Join' 			=> 'LEFT JOIN'
			, 'JoinCondition'	=> 'ON con.idClient = cl.id'
			, 'Select'			=> 'con.Number con_Number'
			, 'Finally'			=> 'ORDER BY con.Number'
			, 'FinallyOffset'	=> 'ORDER BY con_Number'
		)
			
	);
	
	public static $Other = array(
			
		'TO' => array(
			'RequireName'		=> 'Contracts'
			, 'Table'			=> '[TO]'
			, 'Alias'			=> 'tos'
			, 'Join' 			=> 'LEFT JOIN'
			, 'JoinCondition'	=> 'ON tos.idContract = con.id'
		)
			
	);
	
}

class ClientsFilterSelectOptions {
	
	public $IsDistinct;
	public $Limit;
	public $Offset;
	public $Nums;
	public $IsNumsDisplay;
	public $IsCount;
	
	function __construct($IsDistinct = true, $Limit = null, $Offset = null, $Nums = null, $IsNumsDisplay = false, $IsCount = false) {
		$this->IsDistinct		= $IsDistinct;
		$this->Limit			= $Limit;
		$this->Offset			= $Offset;
		$this->Nums				= $Nums;
		$this->IsNumsDisplay	= $IsNumsDisplay;
		$this->IsCount			= $IsCount;
	}
	
}

//
// SQLFilter["Name"]: { 
//			"RequireName"		: string
//			, "Caption"			: string
//			, "Where" 			: string
//			, "WhereValue"		: array
//			, "IsEditAllow"		: bool
// }
//

class ClientsFilter {
	
	public static function GetQuery(array $Filters, &$SQLParams = null, ClientsFilterSelectOptions $Options = null) {
		
		//
		// Выбор частей запроса.
		//
		
		$QueryParts			= ClientFilterQueries::$Basic;
		$QueryPartsIndexed	= array();
		$QueryPartsICounter	= 0;
		$QueryPartsNames	= array();
		foreach ($QueryParts as $Key => $Value) {
			if (!is_numeric($Key)) {
				$QueryPartsNames[] = $Key;
				$QueryPartsIndexed[$QueryPartsICounter++] = $Value;
			}
		} 
		foreach ($Filters as $Value) {
			$QueryName = $Value['RequireName'];
			if (!in_array($QueryName, $QueryPartsNames)) {
				do {
					$QueryPart									= ClientFilterQueries::$Other[$QueryName];
					$QueryParts[$QueryName]						= $QueryPart;
					$QueryPartsIndexed[$QueryPartsICounter++]	= $QueryPart;
					$QueryPartsNames[]							= $QueryName;
					$QueryName									= $QueryPart['RequireName'];
				} while (!in_array($QueryName, $QueryPartsNames));
			}
		}
		
		//
		//
		//
		
		if ($Options == null) {
			$Options = new ClientsFilterSelectOptions();
		}
		
		
		//
		// SELECT
		//
		
		$SQL 	= "SELECT ";
		$SQL	.= $Options->IsDistinct ? ' DISTINCT ' : '';
		
		if ($Options->IsCount) {
			
			$SQL	.= 'COUNT(*) CNT ';
			
		} else {
			
			$Parts	= array_column($QueryParts, 'Select');
			$SQL	.= implode(', ', $Parts) . ' ';
			
		} 
		
		if ($Options->Offset || $Options->Nums || $Options->IsNumsDisplay) {
			
			$Parts	= array_column($QueryParts, 'Finally');
			$SQL	.= ', ROW_NUMBER() OVER (' . implode(' ', $Parts) . ') AS __Num__ ';
			
		}
		
		
		//
		// FROM
		//
		
		for ($i = 0; $i < $QueryPartsICounter; $i++) {
			$Part	= $QueryPartsIndexed[$i];
			$SQL	.= (isset($Part['Join'])			? ($Part['Join'] . ' ')				: '');
			$SQL	.= (isset($Part['Table'])			? ($Part['Table'] . ' ')			: '');
			$SQL	.= (isset($Part['Alias']) 			? ($Part['Alias'] . ' ')			: '');
			$SQL	.= (isset($Part['JoinCondition'])	? ($Part['JoinCondition'] . ' ')	: '');
		}
		
		
		//
		// WHERE
		//
		
		$Parts		= array_column($Filters, 'Where');
		$SQL		.= count($Parts) > 0 ? 'WHERE ' : '';
		$SQL		.= implode(' AND ', $Parts) . ' ';
		$ParamsTemp	= array_column($Filters, 'WhereValue');
		$SQLParams	= array();
		foreach ($ParamsTemp as $Value) {
			foreach ($Value as $SubKey => $SubValue) {
				$SQLParams[$SubKey] = $SubValue;
			}
		}
		
		//
		// Finally
		//
		
		if ($Options->Limit || $Options->Offset || $Options->Nums) {
			
			if ($Options->Limit) {
				$SQL = 'SELECT TOP (' . $Options->Limit . ') * FROM (' . $SQL . ') __t__ ';
			} else if ($Options->Nums) {
				$SQL = 'SELECT * FROM (' . $SQL . ') __t__ ';
			}
			if ($Options->Offset) {
				$SQL .= 'WHERE __Num__ > ' . $Options->Offset. ' ';
			} else if ($Options->Nums) {
				$SQL					.= 'WHERE __Num__ IN (:__Num__) ';
				$SQLParams['__Num__']	= $Options->Nums;
			}
			$Parts	= array_column($QueryParts, 'FinallyOffset');
			$SQL	.= implode(' ', $Parts) . ' ';
			
		} else if (!$Options->IsCount) {
			$Parts	= array_column($QueryParts, 'Finally');
			$SQL	.= implode(' ', $Parts) . ' ';
		}
		
		
		//
		//
		//
		
		return $SQL;
		
	}
	
}

?>