<?php
/**
* Telegram Bot example for Italian Museums of DBUnico Mibact Lic. CC-BY
* @author Francesco Piero Paolicelli @piersoft
*/
//include("settings_t.php");
include("Telegram.php");

class mainloop{
const MAX_LENGTH = 4096;
function start($telegram,$update)
{

	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");

	//$data=new getdata();
	// Instances the class

	/* If you need to manually take some parameters
	*  $result = $telegram->getData();
	*  $text = $result["message"] ["text"];
	*  $chat_id = $result["message"] ["chat"]["id"];
	*/


	$text = $update["message"] ["text"];
	$chat_id = $update["message"] ["chat"]["id"];
	$user_id=$update["message"]["from"]["id"];
	$location=$update["message"]["location"];
	$reply_to_msg=$update["message"]["reply_to_message"];

	$this->shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg);
	$db = NULL;

}

//gestisce l'interfaccia utente
 function shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg)
{
	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");
	if (strpos($text,'@sportsop_bot') !== false){
		$text=str_replace("@sportsop_bot ","",$text);
		$text=str_replace("@sportsop_bot","",$text);
	}
if (strpos($text,"⚽️") !== false) $text=str_replace("⚽️ ","",$text);
if (strpos($text,"🚩") !== false) $text=str_replace("🚩 ","",$text);
	if ($text == "/start" || $text == "info" || $text == "©️info") {
		$reply ="SPORTS OPEN DATA (http://sportsopendata.net/)\nSports Open Data è un’associazione culturale senza scopo di lucro che si pone l'obiettivo di fornire dati statistici in ambito sportivo in modalità open data attraverso delle API Rest.";
$reply .="\nQuesto bot è stato realizzato durante il raduno Spaghetti openData 2016 a Trento da Paolo Riva, Piersoft e Alice Giorgio";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
		$log=$today. ",new chat," .$chat_id. "\n";
				$this->create_keyboard($telegram,$chat_id);
				exit;

		}

		//gestione segnalazioni georiferite
		elseif($location!=null)
		{

		//	$this->location_manager($telegram,$user_id,$chat_id,$location);
		//	exit;

		}
		elseif($text == "Gironi" || $text == "gironi" || $text == "/gironi"){


					$json_string = file_get_contents("http://soccer.sportsopendata.net/v1/leagues/uefa-euro-2016/seasons/16/standings");
					$parsed_json = json_decode($json_string, true);
					$count = 0;
					$countl = [];
					$temp_c1="\n";
					//		var_dump($parsed_json['data']['standings']);
					foreach($parsed_json['data']['standings'] as $data=>$csv1){

						$temp_c1 .= $data."\n";
						foreach($csv1 as $keyval=>$team){
							$temp_c1 .=$team['team']." ".$team['position']."\n";
						}
						$temp_c1 .="----\n";

					}
					$chunks = str_split($temp_c1, self::MAX_LENGTH);
					foreach($chunks as $chunk)
					{
				$forcehide=$telegram->buildForceReply(true);
					//chiedo cosa sta accadendo nel luogo
				$content = array('chat_id' => $chat_id, 'text' => $chunk,'disable_web_page_preview'=>true);

						$telegram->sendMessage($content);
						}
						$log=$today. ",gironi," .$chat_id. "\n";
								$this->create_keyboard($telegram,$chat_id);
								exit;
		}

		elseif($text=="Live" || $text=="/live" || $text=="live"){

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

										$temp_c1 .="⚽️ ".$csv1['home']['team']."-".$csv1['away']['team']." : ".$csv1['match_result']."\n";
									//	$option[$countl]=$csv1['home']['team']."-".$csv1['away']['team'];
										$countl++;
									}


								}

		$chunks = str_split($temp_c1, self::MAX_LENGTH);
		foreach($chunks as $chunk) {
//			$forcehide=$telegram->buildForceReply(true);
				//chiedo cosa sta accadendo nel luogo
	//		$content = array('chat_id' => $chat_id, 'text' => $chunk, 'reply_markup' =>$forcehide,'disable_web_page_preview'=>true);
	$forcehide=$telegram->buildForceReply(true);
		//chiedo cosa sta accadendo nel luogo
	$content = array('chat_id' => $chat_id, 'text' => $chunk,'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);

		}
		/*
		$optionf=array([]);
		for ($i=0;$i<$countl;$i++){
			array_push($optionf,[$option[$i]]);

		}
				$keyb = $telegram->buildKeyBoard($optionf, $onetime=false);
				$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Clicca sulla partita per i dettagli");
				$telegram->sendMessage($content);
				*/
							$log=$today. ",live," .$chat_id. "\n";
				$this->create_keyboard($telegram,$chat_id);

	}

	else{
		/*
		$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "sto cercando la partita ".$text);
		$telegram->sendMessage($content);

if (strpos($text,'/') !== false){
	$text=str_replace("/","",$text);
}
if (strpos($text,'_') !== false){
	$text=str_replace("_","-",$text);
}
$text=strtolower($text);

$json_string = file_get_contents("http://soccer.sportsopendata.net/v1/leagues/uefa-euro-2016/seasons/16/rounds/round-1/matches/".$text);
$parsed_json = json_decode($json_string, true);
$count = 0;
$countl = [];
$temp_c1="\n";
	//	var_dump($parsed_json['data']['matches'][0]);

 if (empty($parsed_json['data']['matches'][0]['match_result'])) $temp_c1.="yes";

			$temp_c1 .=$parsed_json['data']['matches'][0]['match_result']."\n";
			$temp_c1 .="----\n";


					$chunks = str_split($temp_c1, self::MAX_LENGTH);
					foreach($chunks as $chunk) {
				$forcehide=$telegram->buildForceReply(true);
					//chiedo cosa sta accadendo nel luogo
				$content = array('chat_id' => $chat_id, 'text' => $chunk,'disable_web_page_preview'=>true);

						$telegram->sendMessage($content);
}
						$log=$today. ",".$text."," .$chat_id. "\n";
								$this->create_keyboard($telegram,$chat_id);
							//	exit;
*/
	}





}



// Crea la tastiera
function create_keyboard($telegram, $chat_id)
 {
	 			$option = array(["🚩 Gironi","⚽️ Live"],["©️info"]);
				$keyb = $telegram->buildKeyBoard($option, $onetime=true);
				$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[fai la tua scelta]");
				$telegram->sendMessage($content);

 }


}

?>
