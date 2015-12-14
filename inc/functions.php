<?php
function draw_mini_calendar($month,$year,$result,$admin_user,$date_tod,$headings,$userTimeZone){
	$cal  = '<table class="calendar" style="padding: 0; border-spacing: 0;">';
	$cal .= '<tr class="calendar-row-top"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
	$start_day = $date_tod;
	$mon_days  = 7+$start_day-1;
	$month_day = date('t',mktime(0,0,0,$month,1,$year)+$userTimeZone);
	
	$day_in_wk = 1;
	$day_count = $start_day;
	$dates_arr = array();
	
	$cal .= '<tr class="calendar-row">';
	
	for($list_day = $start_day; $list_day <= $mon_days && $list_day != $month_day+1; $list_day++){
		$cal .= '<td class="calendar-day">';
			if(($list_day == date('d',time()+$userTimeZone)) && ($month == date('m',time()+$userTimeZone)) && ($year == date('Y',time()+$userTimeZone))){
				$cal .= '<div class="day-number-today">';
			} else {
				$cal .= '<div class="day-number">';
			};
			if($admin_user == true){
				$cal .= '<a href="admin.php?day='.$list_day.'&month='.$month.'&year='.$year.'#events_add">';
			}
			$cal .= $list_day;
			if($admin_user == true){
				$cal .= '</a>';
			}
			$cal .= '</div>';
			// QUERY DATABASE HERE //
			for($x = 0; $x < count($result);$x++){
				$this_day_datetime = date('d-m-Y',mktime(0,0,0,$month,$list_day,$year)+$userTimeZone);
				$sql_day   = $result[$x]['date'];
				$sql_time  = $result[$x]['time'];
				$sql_title = $result[$x]['title'];
				$sql_id    = $result[$x]['id'];
				if (strlen($sql_title) > 12) {
					$sql_name = substr($sql_title,0,9).'...';
				} else {
					$sql_name = $sql_title;
				};
				if($sql_day == $this_day_datetime){
					$cal .= '<span class="event" title="'.$sql_title.'"><a href="calendar_signup.php?id='.$sql_id.'">'.$sql_time.' - '.$sql_name.'</a></span>';
				}
			}
			//
		$cal .= '</td>';
		$day_in_wk++;
		$start_day++;
		$day_count++;
	}
	if(($day_in_wk < 8) && ($day_in_wk != 1)){
		$next_days = date('j',mktime(0,0,0,$month+1,1,$year));
		for($i = 1; $i <= (8 - $day_in_wk); $i++){
			$cal .= '<td class="calendar-day">';
				$cal .= '<div class="day-number">';
				if($admin_user == true){
				$cal .= '<a href="admin.php?day='.$next_days.'&month='.$month.'&year='.$year.'#events_add">';
				}
				$cal .= $next_days;
				if($admin_user == true){
				$cal .= '</a>';
				}
				$cal .= '</div>';
				// QUERY DATABASE HERE //
				for($x = 0; $x < count($result);$x++){
					$this_day_datetime = date('d-m-Y',mktime(0,0,0,$month+1,$next_days,$year));
					$sql_day   = $result[$x]['date'];
					$sql_time  = $result[$x]['time'];
					$sql_title = $result[$x]['title'];
					$sql_id    = $result[$x]['id'];
					if (strlen($sql_title) > 11) {
						$sql_name = substr($sql_title,0,8).'...';
					} else {
						$sql_name = $sql_title;
					};
					if($sql_day == $this_day_datetime){
						$cal .= '<span class="event" title="'.$sql_title.'"><a href="calendar_signup.php?id='.$sql_id.'">'.$sql_time.' - '.$sql_name.'</a></span>';
					}
				}
				//
				$next_days++;
			$cal .= '</td>';
		}
	}
	
	$cal .= '</tr>';
	$cal .= '</table>';
	return $cal;
};

function draw_calendar($month,$year,$result,$admin_user,$date_tod){
	$cal  = '<table cellpadding="0" cellspacing="0" class="calendar">';
	$headings = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
	$cal .= '<tr class="calendar-row-top"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
	
	$start_day = date('N',mktime(0,0,0,$month,1,$year));
	$start_day = ($start_day > 0) ? $start_day-1 : $start_day = 0;
	$mon_days  = date('t',mktime(0,0,0,$month,1,$year));
	
	$day_in_wk = 1;
	$day_count = 0;
	$dates_arr = array();
	$cal .= '<tr class="calendar-row">';
	$start_day_name = date('D',mktime(0,0,0,$month,1,$year));
	switch ($start_day_name){
		case 'Mon':
			$minus = -1;
		break;
		case 'Tue':
			$minus = 0;
		break;
		case 'Wed':
			$minus = 1;
		break;
		case 'Thu':
			$minus = 2;
		break;
		case 'Fri':
			$minus = 3;
		break;
		case 'Sat':
			$minus = 4;
		break;
		case 'Sun':
			$minus = 5;
		break;
	}
	$prev_days = date('t',mktime(0,0,0,$month-1,1,$year)) - $minus;
	for($i = 0; $i < $start_day; $i++){
		$cal .= '<td class="calendar-day-np">';
			$cal .= '<div class="day-number-other">'.$prev_days.'</div>';
			// QUERY DATABASE HERE //
			for($x = 0; $x < count($result);$x++){
				$this_day_datetime = date('d-m-Y',mktime(0,0,0,$month-1,$prev_days,$year));
				$sql_day   = $result[$x]['date'];
				$sql_time  = $result[$x]['time'];
				$sql_title = $result[$x]['title'];
				$sql_id    = $result[$x]['id'];
				if (strlen($sql_title) > 11) {
					$sql_name = substr($sql_title,0,8).'...';
				} else {
					$sql_name = $sql_title;
				};
				if($sql_day == $this_day_datetime){
					$cal .= '<span class="event" title="'.$sql_title.'"><a href="calendar_signup.php?id='.$sql_id.'">'.$sql_time.' - '.$sql_name.'</a></span>';
				}
			}
			//
		$cal .= '</td>';
		$day_in_wk++;
		$prev_days++;
	}
	
	for($list_day = 1; $list_day <= $mon_days; $list_day++){
		$cal .= '<td class="calendar-day">';
			if(($list_day == $date_tod) && ($month == date('m')) && ($year == date('Y'))){
				$cal .= '<div class="day-number-today">';
				if($admin_user == true){
				$cal .= '<a href="admin.php?day='.$list_day.'&month='.$month.'&year='.$year.'#events_add">';
				}
				$cal .= $list_day;
				if($admin_user == true){
				$cal .= '</a>';
				}
				$cal .= '</div>';
			} else {
				$cal .= '<div class="day-number">';
				if($admin_user == true){
				$cal .= '<a href="admin.php?day='.$list_day.'&month='.$month.'&year='.$year.'#events_add">';
				}
				$cal .= $list_day;
				if($admin_user == true){
				$cal .= '</a>';
				}
				$cal .= '</div>';
			};
			// QUERY DATABASE HERE //
			for($x = 0; $x < count($result);$x++){
				$this_day_datetime = date('d-m-Y',mktime(0,0,0,$month,$list_day,$year));
				$sql_day   = $result[$x]['date'];
				$sql_time  = $result[$x]['time'];
				$sql_title = $result[$x]['title'];
				$sql_id    = $result[$x]['id'];
				if (strlen($sql_title) > 11) {
					$sql_name = substr($sql_title,0,8).'...';
				} else {
					$sql_name = $sql_title;
				};
				if($sql_day == $this_day_datetime){
					$cal .= '<span class="event" title="'.$sql_title.'"><a href="calendar_signup.php?id='.$sql_id.'">'.$sql_time.' - '.$sql_name.'</a></span>';
				}
			}
			//
		$cal .= '</td>';
		if($start_day == 6){
			$cal .= '</tr>';
			if(($day_count+1) != $mon_days){
				$cal .= '<tr class="calendar-row">';
			}
			$start_day = -1;
			$day_in_wk = 0;
		}
		$day_in_wk++;
		$start_day++;
		$day_count++;
	}
	
	if(($day_in_wk < 8) && ($day_in_wk != 1)){
		$next_days = date('j',mktime(0,0,0,$month+1,1,$year));
		for($i = 1; $i <= (8 - $day_in_wk); $i++){
			$cal .= '<td class="calendar-day-np">';
				$cal .= '<div class="day-number-other">';
				if($admin_user == true){
				$cal .= '<a href="admin.php?day='.$next_days.'&month='.$month.'&year='.$year.'#events_add">';
				}
				$cal .= $next_days;
				if($admin_user == true){
				$cal .= '</a>';
				}
				$cal .= '</div>';
				// QUERY DATABASE HERE //
				for($x = 0; $x < count($result);$x++){
					$this_day_datetime = date('d-m-Y',mktime(0,0,0,$month+1,$next_days,$year));
					$sql_day   = $result[$x]['date'];
					$sql_time  = $result[$x]['time'];
					$sql_title = $result[$x]['title'];
					$sql_id    = $result[$x]['id'];
					if (strlen($sql_title) > 11) {
						$sql_name = substr($sql_title,0,8).'...';
					} else {
						$sql_name = $sql_title;
					};
					if($sql_day == $this_day_datetime){
						$cal .= '<span class="event" title="'.$sql_title.'"><a href="calendar_signup.php?id='.$sql_id.'">'.$sql_time.' - '.$sql_name.'</a></span>';
					}
				}
				//
				$next_days++;
			$cal .= '</td>';
		}
	}
	
	$cal .= '</tr>';
	$cal .= '</table>';
	return $cal;
};

// Array

/**
 * Move array element by index.  Only works with zero-based,
 * contiguously-indexed arrays
 *
 * @param array $array
 * @param integer $from Use NULL when you want to move the last element
 * @param integer $to   New index for moved element. Use NULL to push
 * 
 * @throws Exception
 * 
 * @return array Newly re-ordered array
 */
function moveValueByIndex( array $array, $from=null, $to=null )
{
  if ( null === $from )
  {
    $from = count( $array ) - 1;
  }

  if ( !isset( $array[$from] ) )
  {
    throw new Exception( "Offset $from does not exist" );
  }

  if ( array_keys( $array ) != range( 0, count( $array ) - 1 ) )
  {
    throw new Exception( "Invalid array keys" );
  }

  $value = $array[$from];
  unset( $array[$from] );

  if ( null === $to )
  {
    array_push( $array, $value );
  } else {
    $tail = array_splice( $array, $to );
    array_push( $array, $value );
    $array = array_merge( $array, $tail );
  }

  return $array;
};
?>