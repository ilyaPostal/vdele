<?php

namespace SITE_DB;
use SITE_CORE AS Core;

class DirectSQL {
	
	public $SelectSQL				= null;
	public $InsertSQL				= null;
	public $UpdateSQL				= null;
	public $DeleteSQL				= null;
	public $PrepareParamsSelectSQL	= null;
	public $PrepareParamsInsertSQL	= null;
	public $PrepareParamsUpdateSQL	= null;
	public $PrepareParamsDeleteSQL	= null;
	
	public $ColumnNames				= null;
	public $SelectResult			= null;
	//public function SelectResultJson()
	
	public $InsertedRows			= null;
	public $UpdatedRows				= null;
	public $DeletedRows				= null;
	public $InsertSQLResult			= null;
	//public function InsertResultJson()
	public $UpdateSQLResult			= null;
	//public function UpdateResultJson()
	public $DeleteSQLResult			= null;
	//public function DeleteResultJson()
	const DMLTypeInsert				= 1;
	const DMLTypeUpdate				= 2;
	const DMLTypeDelete				= 3;
	
	public $PDOConnection			= null;
	
	public $DirectSQLSessionName			= null;
	public $Message							= null;
	//public $StorageColumnName				= null;//'ID';
	//public $StorageResultArrayByColumn	= array();
	public $StorageColumnsName				= array();
	public $StorageResultArray				= null;
	
	public function __construct($PDOObj = null, $DirectSQLSessionName = null) {
		
		//
		//$PDOObj должен быть с указанными атрибутами.
		//
		//$PDOObj->setAttribute(PDO::ATTR_ERRMODE				, PDO::ERRMODE_\Exception);
		//$PDOObj->setAttribute(PDO::ATTR_EMULATE_PREPARES		, false);
		//$PDOObj->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE	, PDO::FETCH_ASSOC);
		//
		
		$this->PDOConnection			= $PDOObj;
		$this->DirectSQLSessionName		= $DirectSQLSessionName;
	}
	
	public function SetSelectSQL($SelectSQL, $PrepareParams = null) {
		$this->SelectSQL				= $SelectSQL;
		$this->PrepareParamsSelectSQL	= $PrepareParams;
	}
	
	public function Select($SelectSQL = null, $PrepareParams = null) {
		try {
			
			$this->SelectSQL				= $SelectSQL 		!== null															? $SelectSQL					: $this->SelectSQL;
			$this->PrepareParamsSelectSQL	= $PrepareParams	!== null															? $PrepareParams				: $this->PrepareParamsSelectSQL;
			$this->PrepareParamsSelectSQL	= $this->PrepareParamsSelectSQL !== null && count($this->PrepareParamsSelectSQL) > 0	? $this->PrepareParamsSelectSQL : null;
			
			//////////////////////////////////////////
			//	Распаковка составных параметров для //
			//			SQL выражений IN ()			//
			//////////////////////////////////////////
			
			self::UnpackParams($this->SelectSQL, $this->PrepareParamsSelectSQL);
			
			//////////////////////////////////////////
			//		 Заполнение	$SelectResult		//
			//////////////////////////////////////////
			
			$this->SelectResult	= null;
			$RowResult			= null;
			$PDOQuery			= null;
			if ($this->PrepareParamsSelectSQL != null) {
				$PDOQuery		= $this->PDOConnection->prepare($this->SelectSQL);
				if ($PDOQuery == false)
					throw new \Exception('Ошибка в вызове prepare()');
				$IsComplete		= $PDOQuery->execute($this->PrepareParamsSelectSQL);
				if (!$IsComplete)
					throw new \Exception('Ошибка в вызове execute()');
			} else {
				$PDOQuery		= $this->PDOConnection->query($this->SelectSQL);
				if ($PDOQuery == false)
					throw new \Exception('Ошибка в вызове query()');
			}
			
			//
			// MSSQL присылает булевы значения как строки "1" и "0". /facepalm
			//
			
// 			$this->SelectResult	= array();
// 			while ($Row = $PDOQuery->fetch()) {
// 				foreach ($Row AS $key => $value) {
// 					$value		= $value === '1' ? 1 : $value;
// 					$value		= $value === '0' ? 0 : $value;
// 					$Row[$key]	= $value;
// 				}
// 				$this->SelectResult[] = $Row;
// 			}

			//$this->SelectResult = DirectSQL::FetchAll($PDOQuery);
			
			$this->SelectResult = $PDOQuery->fetchAll();
			
			//////////////////////////////////////////
			//		 Заполнение	$ColumnNames		//
			//////////////////////////////////////////
			
			$this->ColumnNames = null;
			$ColumnCount = $PDOQuery->columnCount();
			for ($i = 0; $i < $ColumnCount; $i++) {
				$ColumnMeta	= $PDOQuery->getColumnMeta($i);
				$this->ColumnNames[] = $ColumnMeta['name'];
			}
			
			//////////////////////////////////////////
			//			   Заполнение				//
			//			$StorageResultArray			//
			//////////////////////////////////////////
			
			$this->StorageColumnsName = $this->StorageColumnsName == null ? array() : $this->StorageColumnsName;
			$StorageColumnsNameCount = count($this->StorageColumnsName);
			for ($j = 0; $j < $StorageColumnsNameCount; $j++) {
				if (!in_array($this->StorageColumnsName[$j], $this->ColumnNames))
					unset($this->StorageColumnsName[$j]);
			}
			$this->StorageColumnsName = array_values($this->StorageColumnsName);
			if (count($this->StorageColumnsName) !== 0) {
				$this->StorageResultArray = array();
				$SelRez				= $this->SelectResult;
				$SelRezCount		= count($SelRez);
				$StoreColumns		= $this->StorageColumnsName;
				$StoreColumnsCount	= count($StoreColumns);
				$Columns			= $this->ColumnNames;
				for ($i = 0; $i < $SelRezCount; $i++) {
					$StorageRow	= array();
					for ($j = 0; $j < $StoreColumnsCount; $j++)
						$StorageRow[$StoreColumns[$j]] = $SelRez[$i][$StoreColumns[$j]];
						$this->StorageResultArray[] = $StorageRow;
				}
			}
			
		} catch (\Exception $e) {
			throw new \Exception('Ошибка в объекте класса DirectSQL. Метод Select().'
					. "\r\n" . 'Error message: ' . $e->getMessage());
		}
	}
	
	//////////////////////////////////////////
	//			Get results as json			//
	//////////////////////////////////////////
	
	public function SelectResultJson() {
		return Core\Json_1251::Encode($this->SelectResult, true);
	}
	public function InsertResultJson() {
		return Core\Json_1251::Encode($this->InsertSQLResult, true);
	}
	public function UpdateResultJson() {
		return Core\Json_1251::Encode($this->UpdateSQLResult, true);
	}
	public function DeleteResultJson() {
		return Core\Json_1251::Encode($this->DeleteSQLResult, true);
	}
	
	//////////////////////////////////////////
	//		 	  Save in Session			//
	//////////////////////////////////////////
	
	public function SaveInSession($ObjectSessionName = null) {
		
		try {
			
			$this->DirectSQLSessionName = $ObjectSessionName != null ? $ObjectSessionName : $this->DirectSQLSessionName;
			
			if ($this->DirectSQLSessionName == null)
				throw new \Exception('Не заданно имя переменной для сохранения в сессии.');
				
			if (session_status() !== PHP_SESSION_ACTIVE)
				session_start();
			$_SESSION['DirectSQLObjects'][$this->DirectSQLSessionName]['StorageColumnsName']	= $this->StorageColumnsName;
			$_SESSION['DirectSQLObjects'][$this->DirectSQLSessionName]['StorageResultArray']	= $this->StorageResultArray;
			session_write_close();
					
		} catch (\Exception $e) {
			throw new \Exception('Ошибка в объекте класса DirectSQL. Метод StorageSave(' . $this->DirectSQLSessionName . ').'
					. "\r\n" . 'Error message: ' . $e->getMessage());
		}
	}
	
	//////////////////////////////////////////
	//	   		  Load from Session			//
	//////////////////////////////////////////
	
	public function LoadFromSession($ObjectSessionName = null) {
		
		try {
			
			$this->DirectSQLSessionName = $ObjectSessionName != null ? $ObjectSessionName : $this->DirectSQLSessionName;
			
			if (session_status() !== PHP_SESSION_ACTIVE)
				session_start();
			$this->StorageColumnsName			= $this->GetSessionVarInStruct(array('DirectSQLObjects', $this->DirectSQLSessionName, 'StorageColumnsName'));
			$this->StorageResultArray			= $this->GetSessionVarInStruct(array('DirectSQLObjects', $this->DirectSQLSessionName, 'StorageResultArray'));
			session_write_close();
				
		} catch (\Exception $e) {
			throw new \Exception('Ошибка в объекте класса DirectSQL. Метод StorageRestore(' . $this->DirectSQLSessionName . ').'
					. "\r\n" . 'Error message: ' . $e->getMessage());
		}
	}
	
	//////////////////////////////////////////
	//	  Apply Changes getting from ajax	//
	//////////////////////////////////////////
	
	public function ApplyChanges($IsGetDMLResult = false) {
		self::ExcecuteDML(self::DMLTypeInsert, $IsGetDMLResult);
		self::ExcecuteDML(self::DMLTypeUpdate, $IsGetDMLResult);
		self::ExcecuteDML(self::DMLTypeDelete, $IsGetDMLResult);
	}
	
	private function ExcecuteDML($DMLType, $IsGetDMLResult = false) {
		$SQL			= null;
		$PrepareParams	= null;
		$DMLRows		= null;
		$DMLResult		= null;
		
		if ($DMLType == self::DMLTypeInsert) {
			self::UnpackParams($this->InsertSQL, $this->PrepareParamsInsertSQL);
			$SQL			= $this->InsertSQL;
			$PrepareParams	= $this->PrepareParamsInsertSQL;
			$DMLRows		= $this->InsertedRows;
			$DMLResult		= &$this->InsertSQLResult;
		} else if ($DMLType == self::DMLTypeUpdate) {
			self::UnpackParams($this->UpdateSQL, $this->PrepareParamsUpdateSQL);
			$SQL			= $this->UpdateSQL;
			$PrepareParams	= $this->PrepareParamsUpdateSQL;
			$DMLRows		= $this->UpdatedRows;
			$DMLResult		= &$this->UpdateSQLResult;
		} else if ($DMLType == self::DMLTypeDelete) {
			self::UnpackParams($this->DeleteSQL, $this->PrepareParamsDeleteSQL);
			$SQL			= $this->DeleteSQL;
			$PrepareParams	= $this->PrepareParamsDeleteSQL;
			$DMLRows		= $this->DeletedRows;
			$DMLResult		= &$this->DeleteSQLResult;
		}
		
		try {
			$DMLRowsCount	= count($DMLRows);
			if ($SQL != null && $DMLRowsCount != 0) {
				$ParamNames	= self::GetParamNamesArrayBySQL($SQL);
			
				if ($ParamNames == null) {
					if ($IsGetDMLResult) {
						$PDOQuery	= $this->PDOConnection->query($SQL);
						$DMLResult	= DirectSQL::FetchAll($PDOQuery);// $PDOQuery->fetchAll();
					} else {
						$this->PDOConnection->exec($SQL);
					}
				} else {
					if ($DMLType == self::DMLTypeUpdate || $DMLType == self::DMLTypeDelete) {
						if (!self::CheckSecureColumns($this->StorageResultArray, $DMLRows))
							throw new \Exception('Класс DirectSQL. Метод ExcecuteDML(). Ошибка при проверке зачищённой колонки.');
					}
					
					$DMLResult			= null;
					$ParamNamesCount	= count($ParamNames);
					for ($i = 0; $i < $DMLRowsCount; $i++) {
						$PDOQuery		= $this->PDOConnection->prepare($SQL);	//Внутри цикла из-за глюков PDO/MySQL
						$ArrayParams	= null;
						if ($PrepareParams !== null)
							foreach ($PrepareParams as $key => $value)
								$ArrayParams[$key] = $value;
						for ($j = 0; $j < $ParamNamesCount; $j++) {
							$key = mb_substr($ParamNames[$j], 1);
							if (key_exists($key, $DMLRows[$i])) {
								$PrepareValue	= $DMLRows[$i][$key];
								$PrepareValue	= $PrepareValue === true 	? '1' : $PrepareValue;
								$PrepareValue	= $PrepareValue === false 	? '0' : $PrepareValue;
								//$PrepareValue	= $PrepareValue !== null	? htmlspecialchars($PrepareValue, ENT_QUOTES) : null;
								$PrepareValue	= $PrepareValue !== null	? Core\StringShielding::ConvertHtmlToBdc($PrepareValue) : null;
								$ArrayParams[$ParamNames[$j]] = $PrepareValue;
							}
						}
						$IsComplete		= $PDOQuery->execute($ArrayParams);
						unset($DMLRows[$i]);
						if (!$IsComplete)
							throw new \Exception('Класс DirectSQL. Метод ExcecuteDML(). Ошибка при выполнении DML. SQL: ' . $SQL);
						
						if ($IsGetDMLResult) {
							$DMLResult[]	= DirectSQL::FetchAll($PDOQuery);// $PDOQuery->fetchAll();
						} else {
							
							//
							// Чтобы не случился глюк PDO/MySQL делаем вид что считываем весь результат.
							// closeCursor() не работает.
							//
							
							try {
								do {
									while ($PDOQuery->fetch())
										;
									if (!$PDOQuery->nextRowset())
										break;
								} while (true);
							} catch(\Exception $ex) { }
							
						}
						$PDOQuery = null;
					}
				}
			}
		} catch (\Exception $ex) {
			throw new \Exception($ex->getMessage());
		} finally {
			unset($DMLResult);	
		}
	}
	
	private static function GetParamNamesArrayBySQL($SQL) {
		$Columns			= null;
		$CurrentIndex		= 0;
		$LeftBoundIndex		= 0;
		$RightBoundIndex	= 0;
		while (true) {
			$CurrentIndex		= DirectSQL::StringIndexOfAnySymbols($SQL, ":", $CurrentIndex);
			if ($CurrentIndex === false)
				break;
			$LeftBoundIndex		= $CurrentIndex;
			$CurrentIndex		= DirectSQL::StringIndexOfAnySymbols($SQL, " \r\n\t,);", $CurrentIndex);
			$CurrentIndex		= $CurrentIndex === false ? mb_strlen($SQL) : $CurrentIndex;
			$RightBoundIndex	= $CurrentIndex;
			$Columns[]			= trim(mb_substr($SQL, $LeftBoundIndex, $RightBoundIndex - $LeftBoundIndex));
		}
		return $Columns;
	}
	
	private static function StringIndexOfAnySymbols($Str, $StringOfChars, $Offset = 0) {
		$StrLength				= mb_strlen($Str);
		$MinIndex				= $StrLength;
		$StringOfCharsLength	= mb_strlen($StringOfChars);
		for ($i = 0; $i < $StringOfCharsLength; $i++) {
			$PosResult			= mb_strpos($Str, $StringOfChars[$i], $Offset);
			if ($PosResult === false)
				continue;
			else
				$MinIndex		= $PosResult < $MinIndex ? $PosResult : $MinIndex;
		}
		return $MinIndex != $StrLength ? $MinIndex : false;
	}
	
	public static function CheckSecureColumns($SecureAssocArr, $CheckedAssocArr) {
		$SecureAssocArr			= $SecureAssocArr	== null ? array() : $SecureAssocArr;
		$CheckedAssocArr		= $CheckedAssocArr	== null ? array() : $CheckedAssocArr;
		if (count($SecureAssocArr) == 0 || count($CheckedAssocArr) == 0)
			return true;
		$SecureAssocArrKeys		= array_keys($SecureAssocArr[0]);
		$CheckedAssocArrKeys	= array_keys($CheckedAssocArr[0]);
		
		//
		// Получение только используемых $SecureAssocArrKeys
		//
		
		$SecureAssocArrKeys		= array_uintersect($SecureAssocArrKeys, $CheckedAssocArrKeys
					, function($a, $b) { return ($a === $b) ? 0 : (($a > $b) ? 1 : -1); });
		$SecureAssocArrKeys		= array_values($SecureAssocArrKeys);
		
		
		for ($j = 0; $j < count($SecureAssocArrKeys); $j++) {
			$SecureAssocArrColumn		= array_column($SecureAssocArr, $SecureAssocArrKeys[$j]);
			$CheckedAssocArrColumn		= array_column($CheckedAssocArr, $SecureAssocArrKeys[$j]);
			$CheckedAssocArrColumnCount	= count($CheckedAssocArrColumn);
			for ($i = 0; $i < $CheckedAssocArrColumnCount; $i++) {
				if (!in_array($CheckedAssocArrColumn[$i], $SecureAssocArrColumn))
					return false;
			}
		}
		
		return true;
	}
	
	//
	// Получение переменной из сессии
	//

	private function GetSessionVarInStruct($StructNamesArr) {
		$DefaultValue	= null;
		if (count($StructNamesArr) == 0)
			return $DefaultValue;
		$ResultValue	= isset($_SESSION[$StructNamesArr[0]]) ? $_SESSION[$StructNamesArr[0]] : $DefaultValue;
		for ($i = 1; $i < count($StructNamesArr); $i++) {
			$ResultValue = isset($ResultValue[$StructNamesArr[$i]]) 
							? $ResultValue[$StructNamesArr[$i]] 
							: $DefaultValue;
		}
		return $ResultValue;
	}
	
	//////////////////////////////////////////
	// Преобразование запроса и параметров.	//
	// 	 Если подготавливаемые параметры	//
	// содержат массивы	для выраженией IN()	//
	// 	  , то они преобразуются в общий	//
	// 		массив, с соответствующим		//
	// 		 изменением SQL запроса.		//
	//////////////////////////////////////////
	
	private static function UnpackParams(&$SQL, &$Params) {
		if ($Params) {
			$ParamsCount	= count($Params);
			$ParamsKeys		= array_keys($Params);
			for ($i = 0; $i < $ParamsCount; $i++) {
				if (is_array($Params[$ParamsKeys[$i]])) {
					$ParamName			= $ParamsKeys[$i][0] == ':' ? $ParamsKeys[$i] : (':' . $ParamsKeys[$i]);
					$SubParamArray		= array($ParamName => $Params[$ParamsKeys[$i]]);
					$SubParamCount		= count($SubParamArray[$ParamName]);
					$SubParamNames		= array();
					$SubParamNameValues	= array();
					for ($j = 0; $j < $SubParamCount; $j++) {
						$CurrentSubParamName						= $ParamName. $j;
						$SubParamNames[]							= $CurrentSubParamName;
						$SubParamNameValues[$CurrentSubParamName]	= $SubParamArray[$ParamName][$j];
					}
					$NewParamName	= implode(",", $SubParamNames);
					$SQL			= str_replace(($ParamName), $NewParamName, $SQL);
					unset($Params[$ParamsKeys[$i]]);
					$Params			= array_merge($Params, $SubParamNameValues);
				}
			}
		}
	}
	
	//
	//
	//
	
// 	private static function FetchAll($PDOQuery) {
// 		$Rez	= array();
// 		while ($Row = $PDOQuery->fetch()) {
// 			foreach ($Row AS $key => $value) {
// 				$Row[$key] = is_string($value) ? Core\StringShielding::ConvertBdcToHtml($value) : $value;
// 			}
// 			$Rez[] = $Row;
// 		}
// 		return $Rez;
// 	}
	
}

?>