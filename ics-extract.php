<?php 
$base = mysql_connect ('DATABASE', 'USER', 'PASSWORDS');
	mysql_select_db ('db590264841', $base);
  $sql = "SELECT * FROM table WHERE username = '".$_SESSION['login']."'";
	$req = mysql_query($sql41) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
	while ($data = mysql_fetch_array($req41)) 
	{
    $surname = $data['surname'];
    $name = $data['name'];
    $id = $data['id'];
  }
  
echo '<font style="font-size: 30px;"><center><strong>'.$surname.' '.$name.'</strong> - Student Agenda</center></font><br><br>';
echo '<b><u><i>Attention</b></u></i> : Ceci reflète l\'emploi du temps en ligne de votre numéro étudiant, nous ne garantissons pas l\'exactitude des informations fournies<br><br>';

if(empty($id))
{
    echo 'Veuillez indiquer votre numéro étudiant pour accéder à votre emploi du temps : <a href="MODIFY PROFIL">Cliquez ici pour modifier votre profil et ajouter votre numéro étudiant</a>.';
}

else 
{
  require './ics-parser-master/class.iCalReader.php';
  $ical   = new ICal('WEBSITE ICS REFERENCE'.$id.''  );
  $events = $ical->events();
  $i=1;
  ini_set('display_errors','off');
  foreach ($events as $event) 
  {
    ${$i."summary"} = $event['SUMMARY'];
  	${$i."location"} = $event['LOCATION'];
    ${$i.'time'} = $ical->iCalDateToUnixTimestamp($event['DTSTART']);
  	${$i.'timeend'} = $ical->iCalDateToUnixTimestamp($event['DTEND']);
  	$i = $i + 1;
  }
  $nbevent = $ical->event_count;
  $i = 1;
  $timeinf = 0;
  while ($i < $nbevent) 
  {
  	$f = 1;
  	$timesup = 999999999999;
  	while ($f < $nbevent)
  	{
  		if(${$f.'time'} > $timeinf && ${$f.'time'} < $timesup)
  		{
  			$timesup = ${$f.'time'};
  			$timesupvar = $f;
  		}
  		$f = $f +1;
  	}
  	$timeinf = $timesup;
  	$timefinal = $timesupvar;
  	$timestamp = ${$timefinal.'time'};
  	date_default_timezone_set('Europe/Paris');
  	setlocale(LC_TIME, 'fr_FR.utf8','fra');
  	echo strftime('%A %d %B', $timestamp);
  	echo date(', H:i', $timestamp);
  	echo ' - ';
  	echo date('H:i', ${$timefinal.'timeend'});
  	echo ' / ';
  	echo ${$timefinal.'location'};;
  	echo ' / ';
  	echo ${$timefinal.'summary'};
  	echo '<br><br>';
  	$i = $i + 1;
  }
}
?>
