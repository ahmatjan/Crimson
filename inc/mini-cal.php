<?php
$month = date("n",time()+$userTimeZone);
$year = date("Y",time()+$userTimeZone);
$day = 0;
for($i = 0; $i < 7; $i++){
	$days[] = date('D',(time()+$userTimeZone+$day));
	$mini_cal_days[] = date('Y-m-d',(time()+$userTimeZone+$day));
	$day = $day+86400;
};
$mini_cal_days = implode('\',\'', $mini_cal_days);
$sql = "SELECT * FROM events_final WHERE date IN ('$mini_cal_days')";
$que = mysqli_query($con,$sql);
$res = array();
while($row = mysqli_fetch_assoc($que)){
	$datetime = abs( (strtotime($row['datetime'])) + $userTimeZone );
	$row['date'] = date('d-m-Y',$datetime);
	$row['time'] = date('g:i a',$datetime);
	$res[] = $row;
};

if( ($perms_raidAdmin == 1) && ($perms_raidAdd == 1) ){
	$admin_user = true;
};

$headings = array($days[0],$days[1],$days[2],$days[3],$days[4],$days[5],$days[6]);
$date_tod   = date('j', time() + $userTimeZone);

echo draw_mini_calendar($month,$year,$res,$admin_user,$date_tod,$headings,$userTimeZone);
?>