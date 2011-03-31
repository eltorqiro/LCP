<?php

class CombatParser {
	
	private $interpreter = null;
	private $result = null;
	
	/*
	 * loads the interpreter patterns from URI
	 */
	public function loadInterpreter($data, $data_is_url) {
		$this->interpreter = new SimpleXMLIterator($data, LIBXML_NOCDATA, $data_is_url);
	}

	/*
	 * returns a copy of the current $translationXML object
	 */
	public function getInterpreter() {
		return $this->interpreter;
	}
	
	/*
	 * loads & parses the log into memory
	 */
	public function load($filename) {

		if($resource = fopen($filename, 'rb')) {
			$phrases = $this->interpreter->xpath('/interpreter/phrase');
			
			while(!feof($resource)) {
				$line = stream_get_line($resource, 1024, "\n");
				$matchFound = false;

				// parse lines based on translation xml
				for($i = 0; !$matchFound && $i < count($phrases); $i++ ) {
					// match the pattern
					if(preg_match($phrases[$i]->pattern, $line, $matches)) {
						$matchFound = true;
						
						echo 'tags: ' . $phrases[$i]->attributes()->tags . "<br />";
						echo 'source: ' . (isset($matches['source']) ? $matches['source'] : $phrases[$i]->attributes()->source) . "<br />";
						echo 'target: ' . (isset($matches['target']) ? $matches['target'] : $phrases[$i]->attributes()->target) . "<br />";
						echo 'action: ' . $matches['action'] . "<br />";
						echo 'result: ' . (isset($matches['result']) ? $matches['result'] : $phrases[$i]->attributes()->result) . "<br />";
						echo 'damage: ' . $matches['damage'] . "<br />";
						echo 'points: ' . preg_replace('/[\.,]/', '', $matches['points']) . "<br />";
						echo 'stat: ' . $matches['stat'] . "<br />";
					}
				}
				
				if(!$matchFound) {
					echo "<br />WARNING: $line<br /><br />";
				}

				echo '-----------------------------------------------' . "<br />";
			}
		
			fclose($resource);
		}
	}
	
	/*
	 * saves the xml to URI
	 */
	public function save($URI) {
		
	}
	
}
?>