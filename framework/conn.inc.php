<?php
/***************************************************************************
 *   copyright            :(C) 2005 by DREAMS	Öì×ÚöÎ                     *
 ***************************************************************************/
require_once('adodb/adodb.inc.php');
class dreams_dbo {
	var $totalcount;
	var $lastpage;
	function getConn(){
		$host = "localhost";//Êý¾Ý¿â¿â·þÎñÆ÷µØÖ·202.101.62.196
		$sa = "root";//ÕÊºÅmingju
		$pwd = "";//ÃÜÂë
		$dbname = "onlook";//Êý¾Ý¿âÃû³Æ

		$conn = &ADONewConnection("mysql");
		$conn -> PConnect($host, $sa, $pwd, $dbname);
		$conn -> SetFetchMode(ADODB_FETCH_ASSOC); 
		$conn -> debug = false;
		$conn -> Execute("set names utf8");
		$conn -> Execute("SET CHARACTER_SET_CLIENT=utf8");
		$conn -> Execute("set names utf8");
		return $conn;
	}

	function ifexists($sql){
		$revalue = true;
		$conn = $this->getConn();
		$recordSet = &$conn -> Execute($sql);
		if ($recordSet === false ){			
			$revalue = false;
		}else{
			if($recordSet -> recordCount() <= 0 ){
				$revalue = false;
			}else{
				$revalue = $recordSet -> FetchRow();
			}
			$recordSet -> close();
		}
		$conn -> close();
		return $revalue;
	}

	function sel_arr($sql){
		$revalue = array();
		$conn = $this->getConn();
		$recordSet = &$conn -> Execute($sql);
		if ($recordSet === false){
			//$revalue = false;
		}
		else {
			$revalue = $recordSet -> GetArray();
			$recordSet -> close();
			if ($revalue === false ) $revalue = array();
		}
		$conn -> close();
		return $revalue;
	}

	function sel_arr_page($sql,$pageno = 1,$ppage = 50,$gettotalcount = false){
		$revalue = array();
		$conn = $this->getConn();
		if ($conn === false) {
			//$revalue = false;
		}
		else {
			$recordSet = $conn -> PageExecute($sql,$ppage,$pageno);
			$revalue = $recordSet -> GetArray();
			$this -> totalcount = $recordSet -> _maxRecordCount;
			$this -> lastpage = $recordSet -> _lastPageNo;			
			$recordSet -> close();
		}
		$conn -> close();
		return $revalue;
	}

	function execsql($sql,$intrans = false){
		$revalue = array();
		$conn = $this->getConn();
		if (is_array($sql)){
			if ($intrans){
				$conn->StartTrans(); 
			}
			$revalue = array();
			foreach($sql as $vv){
				if (substr(trim($vv),0,6) == "select"){
					$result = $conn -> Execute($vv);
					if ($result !== false){
						$revalue[] = $result -> GetArray();
						$result -> close();
					}
				}
				else {
					$conn -> Execute($vv);
				}
			}
			if ($intrans){
				$conn->CompleteTrans(); 
			}
		}
		else {
			if (substr(trim($sql),0,6) == "select"){
				$result = $conn -> Execute($sql);
				if ($result !== false){
					$revalue = $result -> GetArray();
					$result -> close();
				}
			}
			else {
				$conn -> Execute($sql);
			}
		}
		$conn -> close();
		return $revalue;
	}

	function batchinsert($sql,$arr,$intrans = false){
		$revalue = array();
		$conn = $this->getConn();
		if ($intrans) $conn -> StartTrans(); 
		$stmt = $conn -> Prepare($sql);
		foreach($arr as $vv){
			$conn -> Execute($stmt, $vv);
		}
		if ($intrans) $conn -> CompleteTrans(); 
		$conn -> close();
		return $revalue;
	}
}
?>