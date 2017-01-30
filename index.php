<?php
	include_once('../../../inc/start.inc');
	Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	Header("Pragma: no-cache"); // HTTP/1.1
	error_reporting(E_ALL);
CheckAccess(62);
	if (isset($_REQUEST['op']) && $_REQUEST['op']=='save') {
 	// form submit
	
		echo "<PRE>";
		var_dump($_REQUEST);
		echo "</PRE>";
	}
$userId=0;
$myY=0;
$myM=0;


?>
<!DOCTYPE html>
<html>
	<head>
	
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/global/highslide/highslide/highslide.css" />
		<script type="text/javascript" src="/global/highslide/highslide/highslide-full.js"></script>		
		<link href="/global/bootstrap-3.3.6/css/bootstrap.min.css" rel="stylesheet">
		<link href="css.css" rel="stylesheet">
		<title>График работы</title>
		<style type="text/css">
		    .highslide{
				float: center;
			}
			.highslide img {
				border: none;
			}
			td, th {
				text-align:center;
			}
			th {
				background-color:#87CEFA;
			}
			td.tdNumber{
				cursor:pointer;
			}
			td.tdSelected{
				background-color:##87CEFA;
			}
			td.dayOff{
				background-color:##87CEFA;
			}
            #lstForm {
                list-style-type: none;
            }
            #lstForm input[type=text]{
                border:1px solid black;
            }
			.note-hidden{
				display:none;
				position relative;
			}
			a:hover div.note-hidden	{
				display:block;
			}
			.now {
			    font-weight: bolder;
			}

			#viewBodyHS{
				width: 150px; 
				padding: 10px;
				word-wrap: break-word;
			}
		</style>
		<script type="text/javascript" src="/global/js/jquery-1.12.0.min.js"></script> 
		<script type="text/javascript" src="/global/bootstrap-3.3.6/js/bootstrap.js"></script> 
		<script type="text/javascript" src="js.js"></script>


	</head>
	<body>
<?php
    $month_names=array("Январь","Февраль","Март","Апрель","Май","Июнь",
    "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь");
	$getFromDataBase = array();
	function getDataFromWorkTime(){
		return $getFromDataBase;
	}
	function my_calendar($fill=array()) {
		global $month_names, $sql3, $myY, $myM, $ExpGlobal;
;
		
		if (isset($_GET['y']))
    			$y=$_GET['y'];
		if (isset($_GET['m']))
    			$m=$_GET['m'];
		if (isset($_GET['date']) AND strstr($_GET['date'],"-"))
    			list($y,$m)=explode("-",$_GET['date']);
		if (!isset($y) OR $y < 1970 OR $y > 2037)
    			$y=date("Y");
		if (!isset($m) OR $m < 1 OR $m > 12)
    			$m=date("m");

		$month_stamp = mktime(0,0,0,$m,1,$y);
		$day_count = date("t",$month_stamp);
		$weekday = date("w",$month_stamp);
		if ($weekday == 0)
    			$weekday =7;
    			$start =-($weekday-2);
    			$last = ($day_count+$weekday-1) % 7;
		if ($last == 0)
    			$end = $day_count;
		else
    			$end = $day_count+7-$last;
			$today = date("Y-m-d");
			$prev = date('?\m=m&\y=Y',mktime (0,0,0,$m-1,1,$y));
			$next = date('?\m=m&\y=Y',mktime (0,0,0,$m+1,1,$y));
			$i=0;
			$myY = $y;
			$myM = $m;
			
			$query="SELECT DAY(wt.date) AS dayNum, wt.*, cu.name 
			FROM cms_modules_worktime wt LEFT JOIN cms_user cu ON wt.idUser=cu.id
			WHERE YEAR(date) = {$myY} AND MONTH(date)={$myM};";
			
			$sql2 = $ExpGlobal['DBCONN']->ArrArr($query);
			$sql3 = array();
			foreach($sql2 as $v){
				
				
				$sql3[array_shift($v)][] = $v; 
		    }
?>
        <script type="text/javascript">
            var curr_Page = '<?=$_SERVER['SCRIPT_NAME'];?>';
            var selectedMonth = '<?=$m?>';
            var selectedYear ='<?=$y?>';
        </script>

		<table class="table table-bordered border=1 cellspacing=0 cellpadding=0 width="70%" height="40%">
		<tr>
		<td colspan=6>
		<table width="100%" border=0 cellspacing=0 cellpadding=0>

        <select id="idSlctYear">
            <option value="2016" <?=(2016 == $y?'selected':'');?>>2016</option>
            <option value="2017" <?=(2017 == $y?'selected':'');?>>2017</option>
        </select>

		<select id="idSlctMonth">
<?php
        foreach ($month_names as $key=>$value) {
			echo "<option value='".($key+1)."' ".($key==$m-1?'selected':'').">".$value."</option>";
		}
?>
        </select>


		</table>
		</td>
		</tr>
		<tr><th>Понедельник</th><th>Вторник</th><th>Среда</th><th>Четверг</th><th>Пятница</th><th>И того</th></tr>
<?php

		$week_time = array();
		$month_time = array();
		
		for($d=$start;$d<=$end;$d++) {			
			if (!($i++ % 7))
				echo "<tr>";
			if (!($i%7)) 
				continue;
			$cell = ($d < 1) || ($d > $day_count); 
			echo $cell || ($i%7==6)?' <td>':' <td class="tdNumber">';
			if ($cell) {
				echo "&nbsp";
			} else {
				$now="$y-$m-".sprintf("%02d",$d);
				$week = date('N', strtotime($now)) >= 6;
				if (is_array($fill) AND in_array($now,$fill)) {
					if($week != $d){
					echo '<span class="dayNow">'.$d.'</span></div>';}
				} else {
					if($week != $d){
						echo '<span>'.$d.'</span>';
					}
				}
				
				if(isset($sql3[$d])){
					foreach($sql3[$d] as $arr){
						echo "<div><a id='".$arr['id']."' href='#' class='user-link'>";
						echo $arr['name'].'</a>';
						if (!empty($arr['note'])){
						?>
							<!--<a href="#" class="highslide" id="viewBody" onclick="return hs.htmlExpand(this,{ contentId: 'content<?=$arr['id'];?>' } )">-->
							<a href="#" class="highslide" id="viewBody" onclick="return hs.htmlExpand(this,{ maincontentText: $('#content<?=$arr['id'];?>').text() } )">
							<img src="../../images/icons/comment_ico.png" width="20"/></a>
							<div class="highslide-html-content" id="content<?=$arr['id'];?>">
								<a href="#" onclick="hs.close(this); return false;"></a>
								<div class="highslide-body"  id="viewBodyHS"><?=$arr['note'];?></div>
							</div>
                        <?php	
						}
						echo '<br>';
						$temp = (intval($arr['finish']) - intval($arr['start']));
						
						if($temp === 0){
							echo "Время уточняется";
						}
						
						else{
							echo '<code>'.$arr['start'].'</code>'.' - '.'<code>'.$arr['finish'].'</code>'
							.'<br>'.'('.(intval($arr['finish']) - intval($arr['start'])).' ч.)';		
						}
								
						echo '</div>';						
						if (!isset($week_time[$arr['name']])) 
							$week_time[$arr['name']]=0;
						$week_time[$arr['name']]+=intval($arr['finish']) - intval($arr['start']);	
					}
				}
				if ($i%7==6){
					foreach($week_time as $name=>$time){
						echo '<div>'.$name.' - '.$time.' ч.</div>';
						if (!isset($month_time[$name])) 
							$month_time[$name]=0;
						$month_time[$name]+=$time;
					}
					$week_time=array();
				}
			}
			echo "</td>\n";
			if (!($i % 7)) echo " </tr>\n";
		}		
?>
	<tr><td colspan=5>Итого:</td><td><?php
	foreach($month_time as $name=>$time){
		echo '<div>'.$name.' - '.$time.' ч.</div>';
	}	
	?></td></tr>
	</table>
<?php 
}
?>
<?php

?>
<!--YEAR(date) AND MONTH(date)-->

<!--	Пользователь:
	<select id="slctUser" name="slctUser">
		<option value="0"></option>
		<option value="1">Валентин</option>
		<option value="2">Юля</option>
	</select>-->
<?php

    $today=date("Y-m-d");

	/* if (isset($_GET['date'])) echo "выбрана дата ".$_GET['date']; */
	$myCalendar = my_calendar(array(date("Y-m-d")));
	//getFromDataBase
	$userName=$ExpGlobal['SESSION']->_User->GetUserName();
	$userId=$ExpGlobal['SESSION']->_User->_Id;
	//$today=date("d.m.Y");
	/*
	echo "<pre>";
	var_dump($_SERVER);
	echo "</pre>";
	*/

?>
	<p>Выбрана дата: <span id="slctData"></span></p>

		<form action="save.php" method="post" id="frmWorkTime" onsubmit="return chkFrom(this);"> <!--save.php-->
			<input type="hidden" name="op" value="save"/>
			<input type="hidden" name="recId" value="0" id="recId"/>
			<input type="hidden" name="idUser" value="<?=$userId;?>"/>
			<input type="hidden" name="inpDay" value="<?=$today;?>"/>
            <ul id="lstForm">
                <!--<li><span>Начало: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><input name="startTime" type="time" value="" min="10" max="19"><li/>
			    <li><span>Окончание: </span>-->
				<li><span>Время работы с </span>
					<select id="startTime" name="startTime">
						<option value="0">...</option>
						<option value="100000">10:00</option>
						<option value="110000">11:00</option>
						<option value="120000">12:00</option>
						<option value="130000">13:00</option>
						<option value="140000">14:00</option>
						<option value="150000">15:00</option>
					</select>
					<span>до: </span>
					<select id="endTime" name="endTime">
						<option value="0">...</option>
						<option value="140000">14:00</option>
						<option value="150000">15:00</option>
						<option value="160000">16:00</option>
						<option value="170000">17:00</option>
						<option value="180000">18:00</option>
						<option value="190000">19:00</option>
					</select>
					<span>часов</span>
				</li>
				<!--<input name="endTime" type="time" value="" /><li/>-->
                <li><span>Комментарий:</span><textarea id="post" name="post" class="post"></textarea></li>
				<li><label for="chkDelete">Удалить: </label><input type="checkbox" id="chkDelete" name="chkDelete" value="delete"/></li>
                <li><input type="submit" name="frmSubmit" value="Сохранить"/></li>                
            </ul>
		</form>

		

	<?php

	/*
	echo "<PRE>";
	var_dump($ExpGlobal['SESSION']->_User);
	echo "</PRE>";
	/*
    <form method="POST" action="delete.php">
        <input name="id" type="text" placeholder="ID"/>
        <input type="submit" value="Удалить"/>
    </form>*/
	?>

	</body>
</html>