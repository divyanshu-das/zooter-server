<?php

class MatchcornerShell extends AppShell{
	public $uses = array('Series','Match');

	public function main() {		
		$ch = curl_init();

		$matchData = $this->Match->getFullMatchDataForSearch(1);
		//$matchData['match'] = $data;
		$match_json = json_encode($matchData);//pr($match_json);die;
		
		//for ($i=0; $i < 1; $i++) { 
			//foreach ($matchData as $data) {
				//$match_json = json_encode($data);
				$url = "http://localhost:9200/test1/category/1";
				// curl_setopt($ch, CURLOPT_URL, $url);

				
				// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
				// 																						'Content-Length: ' . strlen($match_json)));
				// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				// curl_setopt($ch, CURLOPT_POSTFIELDS,$match_json);
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


			  curl_setopt($ch,CURLOPT_URL,$url);
			  curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
			  curl_setopt($ch,CURLOPT_POSTFIELDS,$match_json);
			  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
			  curl_setopt($ch,CURLOPT_TIMEOUT, 20);
				$response  = curl_exec($ch);
				pr($response);
			//}
		//}		
		curl_close($ch);
	}	

	private function getMatchData () {
		$this->Match->find();
	}

	/*
	{
    "category" : {
        "properties": {
            "MatchTeam" : {
                "type": "nested",
                "properties": {
                    "id" :{"type": "integer"},
                    "team_id" : {"type": "integer"},
                    "status" : {"type": "integer"},
                    "match_id" : {"type":"integer"},
                    "Team" : {
                        "type": "nested",
                        "properties": {
                            "id" :{"type": "integer"},
                            "name" : {"type": "string"}
                        }
                    }
                }
            }
        }
    }
}

	*/

	
} 
