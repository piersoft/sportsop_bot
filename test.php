<?php
include('settings_t.php');
date_default_timezone_set('Europe/Rome');
date_default_timezone_set("UTC");
$today=time();

$today=1465853400;


					$json_string = file_get_contents("http://soccer.sportsopendata.net/v1/leagues/uefa-euro-2016/seasons/16/rounds/round-1/matches");
					$parsed_json = json_decode($json_string, true);
					$count = 0;
					$countl = 0;
					$temp_c1="\n";
					$option=[];
					foreach($parsed_json['data']['matches'] as $data=>$csv1){


						$from = strtotime($csv1['date_match']);
						$to = strtotime($csv1['date_match']+90*60);
	//echo "sto inviando ".$from." a ".$to;
						if ($today >= $from && $today <= $to) {

							$temp_c1 .=$csv1['home']['team']."-".$csv1['away']['team']." : ".$csv1['match_result']."\n";
							$option[$countl]=strtolower($csv1['home']['team']."_".$csv1['away']['team']);
							$countl++;
						}


					}

$optionf=array([]);
for ($i=0;$i<$countl;$i++){
array_push($optionf,["/".$option[$i]]);

}
var_dump($option);
echo $temp_c1;

 //return $temp_c1;



?>
