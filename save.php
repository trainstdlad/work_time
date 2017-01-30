<?php
			include_once('../../../inc/start.inc');
	/*
		echo "<PRE>";
		var_dump($_REQUEST);
		echo "</PRE>";
		die;
	//*/
	
switch($_REQUEST['op']){
    case "save":
			$idUser = isset($_REQUEST['idUser'])?(int)$_REQUEST['idUser']:0;
			$recId = isset($_REQUEST['recId'])?(int)$_REQUEST['recId']:0;
		    $date = date("Ymd", strtotime($_REQUEST['inpDay']));
		    $start =  intval($_REQUEST['startTime']);
		    $finish = intval($_REQUEST['endTime']);			
		    $note = $_REQUEST['post'];
			if ($recId){ //Update
				$sqlTxt="UPDATE cms_modules_worktime ".
				"SET start='{$start}', finish='{$finish}', note='{$note}' ".
				"WHERE id={$recId}";
			} else{
				$sqlTxt="
				INSERT INTO cms_modules_worktime
				(idUser, date, start, finish, note)
				VALUES(".intval($idUser).",".$date.",'$start','$finish','$note')
				";
			}
	/*
		echo "<PRE>";
		var_dump($sqlTxt);
		echo "</PRE>";
		die;
	//*/
			$ExpGlobal['DBCONN']->Query($sqlTxt);
 		    header("Location: index.php");
			exit;
			/* var_dump($sql); */
    break;
    case "delete":
			$recId = isset($_REQUEST['recId'])? intval($_REQUEST['recId']) : 0;
            $sql="DELETE FROM cms_modules_worktime WHERE id={$recId}";
			$ExpGlobal['DBCONN']->Query($sql);
 		    header("Location: index.php");
			exit;    
    break;
}
?>
