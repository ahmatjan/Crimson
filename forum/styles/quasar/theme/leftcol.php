<div class="span3">
	<div class="content-block">
		<div class="well" style="margin-bottom: 0; padding-top: 0; padding-bottom: 12px;">
		<?php
		$xml=simplexml_load_file("./eu-status.xml");
		foreach($xml->children() as $server){
			if($server['name'] == 'Gelidra'){
				echo '<div style="text-align: center;">';
				echo '<h4 style="margin-bottom: 0;">'.ucfirst($server['name']).'</h4>';
				echo '</div>';
				echo '<br />';
				echo '<div style="text-align: center;">';
				
				if (($server['online'] == 'True') && ($server['locked'] !== 'False')) {
					echo '<span class="label label-success">Online</span>';
				} else if (($server['online'] == 'True') && ($server['locked'] == 'False')) {
					echo '<span class="label label-warning">Locked</span>';
				} else if (($server['online'] == 'False')) {
					echo '<span class="label label-important">Offline</span>';
				};
				if(($server['online'] == 'True')){
					echo '&nbsp';
					if (($server['population'] == 'low')) {
						echo '<span class="label label-success">Low</span>';
					} else if (($server['population'] == 'medium')) {
						echo '<span class="label label-warning">Medium</span>';
					} else if (($server['population'] == 'high')) {
						echo '<span class="label label-warning">High</span>';
					} else if (($server['population'] == 'full')) {
						echo '<span class="label label-important">Full</span>';
					};
				};
				if(($server['online'] == 'True')){
					echo '&nbsp';
					if (($server['queued'] == 0)) {
						echo '<span class="label label-success">No Queue</span>';
					} else if (($server['queued'] <= 10)) {
						echo '<span class="label label-success">Queue of '.$server['queued'].'</span>';
					} else if (($server['queued'] <= 100)) {
						echo '<span class="label label-warning">Queue of '.$server['queued'].'</span>';
					} else if (($server['queued'] > 100)) {
						echo '<span class="label label-important">Queue of '.$server['queued'].'</span>';
					};
				};
				
				echo '</div>';
			};
		};
		?>
		</div>
	</div>
	<div class="content-block">
		<div class="well" style="margin-bottom: 0; padding-top: 0; padding-bottom: 12px;">
		<?php
		try {
			$date_today = date("Y-m-d H:i:s");
			$sql = "SELECT * FROM events WHERE datetime>=:date_today ORDER BY datetime LIMIT 1";
			$sth = $dbh->prepare($sql);
			$sth->bindParam(':date_today', $date_today, PDO::PARAM_STR);
			$sth->execute();
			$res = $sth->fetchAll();
			$cou = $sth->rowCount();
			echo $cou;
			if ($cou > 0) {
				$event_ts = abs(strtotime($res[0]['datetime']) + $userTimeDifference);
				$event_date = date('l \t\h\e jS \o\f M', $event_ts);
				$event_check = date('d-m-Y', $event_ts);
				$event_time = date('g:i a', $event_ts);
				$user_today = date('d-m-Y', $userTimeToday);
				$user_day    = date('d', $userTimeToday);
				$user_tomor  = date('d-m-Y', ($userTimeToday+86400));
				
				//$event_date = $user->format_date(strtotime($res[0]['datetime']), 'l \t\h\e jS');
				//$user_today = $user->format_date(strtotime($date_today), 'd-m-Y');
				//$user_day   = $user->format_date(strtotime($date_today), 'd');
				//$user_tomor = $user->format_date(mktime(0,0,0,date("n"),date("j")+1,date("Y")), 'd');
				//$raid_time  = $user->format_date(strtotime($res[0]['datetime']), 'g:i a');
				echo '<center>';
				echo '<a href="./calendar_signup.php?id='.$res[0]['id'].'">';
				echo '<h4 style="color: #b42121; font-weight: bold;">'.$res[0]['title'].'</h4>';
				echo '</a>';
				echo '<h5>';
				if($event_check == $user_today) {
					echo 'Today';
				} else if ($event_check == $user_tomor) {
					echo 'Tomorrow';
				} else {
					echo $event_date;
				}
				echo '<br />';
				echo $event_time;
				echo '</h5>';
				switch ($res[0]['title']){
					case 'Triumph of the Dragon Queen':
						echo '<span class="label label-info">10 Man</span> ';
						echo '<span class="label label-success">Teir 1</span> ';
						echo '<span class="label">400 Hit</span> ';
					break;
					case 'Grim Awakening':
						echo '<span class="label label-info">10 Man</span> ';
						echo '<span class="label label-warning">Teir 2</span> ';
						echo '<span class="label">500 Hit</span> ';
					break;
					case 'Frozen Tempest':
						echo '<span class="label label-info">20 Man</span> ';
						echo '<span class="label label-success">Teir 1</span> ';
						echo '<span class="label">400 Hit</span> ';
					break;
					case 'Endless Eclipse':
						echo '<span class="label label-info">20 Man</span> ';
						echo '<span class="label label-success">Teir 1</span> ';
						echo '<span class="label">400 Hit</span> ';
					break;
					default:
						echo '<span class="label label-info">Custom Event</span>';
					break;
				}
				echo '</center>';
			}
		} catch (PDOException $e){
				$e->getMessage();
		}
		?>
		</div>
	</div>
	<div class="content-block" style="text-align: center;">
		<div class="well" style="margin-bottom: 0; padding: 5px;">
			<iframe src="http://cache.www.gametracker.com/components/html0/?host=46.105.228.195:9989&bgColor=333333&fontColor=CCCCCC&titleBgColor=222222&titleColor=FF9900&borderColor=555555&linkColor=FFCC00&borderLinkColor=222222&showMap=0&currentPlayersHeight=152&showCurrPlayers=1&showTopPlayers=0&showBlogs=0&width=236" frameborder="0" scrolling="no" width="100%" height="340"></iframe>
		</div>
	</div>
</div>