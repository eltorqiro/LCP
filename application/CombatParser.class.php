<?php

class CombatParser {

	private $interpreter;
	private $result;
	private $ruleset;
	private $rulesetCache;
	private $statCache;

	/*
	 * sets the interpreter from URI
	 */
	public function setInterpreter($data, $data_is_url) {
		$this->interpreter = new SimpleXMLElement($data, LIBXML_NOCDATA, $data_is_url);
	}

	/*
	 * returns a copy of the current interpreter
	 */
	public function getInterpreter() {
		return $this->interpreter;
	}

	/*
	 * sets the rulset from URI
	 */
	public function setRuleset($data, $data_is_url) {
		$this->ruleset = new SimpleXMLElement($data, LIBXML_NOCDATA, $data_is_url);

		// set where-test normalisation
		$whereTest['is'] = 'equals';
		$whereTest['equals'] = 'equals';
		$whereTest['='] = 'equals';
		$whereTest['has'] = 'contains';
		$whereTest['contains'] = 'contains';
		$whereTest['#'] = 'contains';
		$whereTest['exists'] = 'exists';

		// cache rules
		$this->rulesetCache = array();
		foreach($this->ruleset->rule as $rule) {
			$ruleCache = &$this->rulesetCache[];

			// where elements
			foreach($rule->where as $where) {
				$whereCache = &$ruleCache['where'][];
				// tokenise rule
				if(preg_match('/^(?P<subject>[^ ]+)(?: (?P<test>[^ ]+))?(?: (?P<object>.+))?/i', (string)$where, $matches)) {
					$whereCache['subject'] = $matches['subject'];
					$whereCache['test'] = $whereTest[$matches['test']];
					$whereCache['object'] = $matches['object'];
				}
			}

			// do elements
			foreach($rule->do as $do) {
				$doCache = &$ruleCache['do'][];
				// what element
				$doCache['what'] = (string)$do->what;

				// with elements
				foreach($do->with as $with) {
					$withCache = &$doCache['with'][];
					$withExplode = explode(':', (string)$with);
					$withCache['key'] = $withExplode[0];
					$withCache['field'] = $withExplode[1];
				}
			}
		}
	}

	/*
	 * returns a copy of the current ruleset
	 */
	public function getRuleset() {
		return $this->ruleset;
	}

	/*
	 * loads the log into memory and puts into basic template
	 */
	public function load($source, $destination) {
		if($resource = fopen($source, 'rb')) {
			$phrases = $this->interpreter->xpath('/interpreter/phrase');
			$default = $this->interpreter->xpath('/interpreter/default');

			try {
				$pdo = new PDO('sqlite:lcp.sqlite3');
				$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			}

			catch(PDOException $e) {
				echo 'connection failed';
				exit(1);
			}

//			$insertEvents = $pdo->prepare('insert into events (line_num, tags, actor, target, action, result, type, points, stat) values (:line_num, :tags, :actor, :target, :action, :result, :type, :points, :stat)');
//			$pdo->beginTransaction();

//			$pdo->query('delete from events');

			for($lineNumber = 1; !feof($resource); $lineNumber++) {

				// trimmed of cr/lf pairs
				$line = trim(stream_get_line($resource, 512, "\n"));

				$matchFound = false;
				foreach($phrases as &$phrase) {
					if($matchFound = preg_match((string)$phrase->pattern, $line, $matches)) {
						//$event['tags'] = explode(',', $phrase->attributes()->tags);
						$event['line_num'] = $lineNumber;
						$event['tags'] = $phrase->attributes()->tags;
						$event['actor'] = isset($matches['actor']) ? $matches['actor'] : $phrase->attributes()->actor;
						$event['target'] = isset($matches['target']) ? $matches['target'] : $phrase->attributes()->target;
						$event['action'] = $matches['action'];
						$event['result'] = isset($matches['result']) ? $matches['result'] : $phrase->attributes()->result;
						$event['type'] = $matches['type'];
						$event['points'] = (int)preg_replace('/[\.,]/', '', $matches['points']);
						$event['stat'] = $matches['stat'];

//						$insertEvents->execute($event);

/*
						// walk ruleset
						foreach($this->rulesetCache as &$rule) {

							// match where clauses
							foreach($rule['where'] as &$where) {
								$funcName = 'ruleTest' . $where['test'];
								if(!self::$funcName($event[$where['subject']], $where['object'])) {
									continue 2;
								}
							}
							unset($where);

							// process do directives
							foreach($rule['do'] as &$do) {
								$funcName = 'doWhat' . $do['what'];

								foreach($do['with'] as &$with) {
									$this->$funcName($with, $event);
								}
								unset($with);
							}
							unset($do);
						}
						unset($rule);
*/
						break;
					}

				}
				unset($phrase);

				if(!$matchFound) {
				}

			}

			// rebuild collated events table
//			$pdo->exec('delete from collated_events;');
//			$pdo->exec('insert into collated_events select * from view_collate_events;');

//			$pdo->commit();
			fclose($resource);
		}


		//


	}

	private function doWhatCount($with, $event) {
		$this->statCache[$with['key']][$with['field']]++;
	}

	private function doWhatSum($with, $event) {
		$this->statCache[$with['key']][$with['field']] +=  $event['points'];
	}

	private static function ruleTestExists($subject, $object) {
		return $object == '';
	}

	private static function ruleTestEquals($subject, $object) {
		return $subject == $object;
	}

	private static function ruleTestContains($subject, $object) {
		return in_array($object, $subject);
	}

	private static function writeNonEmptyXmlElement(&$xmlWriter, $name, $value, $isCData = false) {
		if($value != '') {
			if(!$isCData) {
				$xmlWriter->writeElement($name, $value);
			}

			else {
				$xmlWriter->startElement($name);
				$xmlWriter->writeCData($value);
				$xmlWriter->endElement();
			}
		}
	}

	/*
	 * parse the loaded log and add extra elements
	 */
	private function parse() {

	}

	/*
	 * load & parse the raw text log into memory
	 */
	public function process($filename) {
			// open output stream
			$xmlw = new XMLWriter();
			$xmlw->openURI($destination);
			$xmlw->setIndent(true);

			// start output xml
			$xmlw->startDocument('1.0');
			$xmlw->startElement('interpreter');
			$xmlw->writeAttribute('name', $this->interpreter->attributes()->name);
			$xmlw->writeAttribute('version', $this->interpreter->attributes()->version);
			$xmlw->endElement();

			$xmlw->startElement('events');

			while(!feof($resource)) {
				// trimmed of newline endings
				$line = str_replace(array("\n", "\r"), '', stream_get_line($resource, 1024, "\n"));

				$matchFound = false;
				$xmlw->startElement('event');

				// parse lines based on translation xml
				for($i = 0; !$matchFound && $i < count($phrases); $i++ ) {
					// match the pattern
					if($matchFound = preg_match($phrases[$i]->pattern, $line, $matches)) {


						self::writeNonEmptyXmlElement($xmlw, 'tags', $phrases[$i]->attributes()->tags);
						self::writeNonEmptyXmlElement($xmlw, 'actor', (isset($matches['actor']) ? $matches['actor'] : $phrases[$i]->attributes()->actor));
						self::writeNonEmptyXmlElement($xmlw, 'target', (isset($matches['target']) ? $matches['target'] : $phrases[$i]->attributes()->target));
						self::writeNonEmptyXmlElement($xmlw, 'action', $matches['action']);
						self::writeNonEmptyXmlElement($xmlw, 'result', (isset($matches['result']) ? $matches['result'] : $phrases[$i]->attributes()->result));
						self::writeNonEmptyXmlElement($xmlw, 'type', $matches['type']);
						self::writeNonEmptyXmlElement($xmlw, 'points', preg_replace('/[\.,]/', '', $matches['points']));
						self::writeNonEmptyXmlElement($xmlw, 'stat', $matches['stat']);

						/*
						echo 'tags: ' . $phrases[$i]->attributes()->tags . "<br />\n";
						echo 'actor: ' . (isset($matches['source']) ? $matches['actor'] : $phrases[$i]->attributes()->actor) . "<br />\n";
						echo 'target: ' . (isset($matches['target']) ? $matches['target'] : $phrases[$i]->attributes()->target) . "<br />\n";
						echo 'action: ' . $matches['action'] . "<br />\n";
						echo 'result: ' . (isset($matches['result']) ? $matches['result'] : $phrases[$i]->attributes()->result) . "<br />\n";
						echo 'type: ' . $matches['type'] . "<br />\n";
						echo 'points: ' . preg_replace('/[\.,]/', '', $matches['points']) . "<br />\n";
						echo 'stat: ' . $matches['stat'] . "<br />\n";
						*/
					}
				}

				if(!$matchFound) {
						self::writeNonEmptyXmlElement($xmlw, 'tags', $default[0]->attributes()->tags);
						self::writeNonEmptyXmlElement($xmlw, 'type', $line, true);
				}

				$xmlw->endElement();
			}

			// close source file
			fclose($resource);

			// end document
			$xmlw->endElement();
			$xmlw->endDocument();
			$xmlw->flush();

	}


	/*
	 * saves the xml to URI
	 */
	public function save($URI) {

	}

}
?>