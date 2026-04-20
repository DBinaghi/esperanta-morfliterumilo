<?php
// literumilo_ending.php
// Grammatical endings of Esperanto words.
// Translated from Python by Claude. Original author: Klivo Lendon.

require_once __DIR__ . '/literumilo_entry.php';

class Ending {
	public string	$ending;
	public int		$length;   // in Unicode characters
	public int		$part_of_speech;

	public function __construct(string $end, int $pos) {
		$this->ending = $end;
		$this->length = mb_strlen($end);
		$this->part_of_speech = $pos;
	}
}

// Pre-built singletons (mirrors Python module-level constants)
function _get_endings(): array {
	static $e = null;
	if ($e === null) {
		$e = [
			'SUB_O'		=> new Ending('o',		POS::Substantive),
			'SUB_ON'	=> new Ending('on',		POS::Substantive),
			'SUB_OJ'	=> new Ending('oj',		POS::Substantive),
			'SUB_OJN'	=> new Ending('ojn',	POS::Substantive),
			'VERB_IS'	=> new Ending('is',		POS::Verb),
			'VERB_AS'	=> new Ending('as',		POS::Verb),
			'VERB_OS'	=> new Ending('os',		POS::Verb),
			'VERB_I'	=> new Ending('i',		POS::Verb),
			'VERB_U'	=> new Ending('u',		POS::Verb),
			'VERB_US'	=> new Ending('us',		POS::Verb),
			'ADJ_A'		=> new Ending('a',		POS::Adjective),
			'ADJ_AN'	=> new Ending('an',		POS::Adjective),
			'ADJ_AJ'	=> new Ending('aj',		POS::Adjective),
			'ADJ_AJN'	=> new Ending('ajn',	POS::Adjective),
			'ADV_E'		=> new Ending('e',		POS::Adverb),
			'ADV_EN'	=> new Ending('en',		POS::Adverb),
		];
	}
	return $e;
}

/**
 * Returns the Ending object for the given word, or null if invalid.
 */
function get_ending(string $word): ?Ending {
	$e		   = _get_endings();
	$word_length = mb_strlen($word);
	if ($word_length < 3) return null;

	$chars	= mb_str_split($word);
	$last_ch  = $chars[$word_length - 1];

	if ($last_ch === 'o') return $e['SUB_O'];
	if ($last_ch === 'a') return $e['ADJ_A'];
	if ($last_ch === 'e') return $e['ADV_E'];
	if ($last_ch === 'i') return $e['VERB_I'];
	if ($last_ch === 'u') return $e['VERB_U'];

	if ($last_ch === 's') {
		if ($word_length < 4) return null;
		$sl = $chars[$word_length - 2];
		if ($sl === 'a') return $e['VERB_AS'];
		if ($sl === 'i') return $e['VERB_IS'];
		if ($sl === 'o') return $e['VERB_OS'];
		if ($sl === 'u') return $e['VERB_US'];
	} elseif ($last_ch === 'n') {
		if ($word_length < 4) return null;
		$sl = $chars[$word_length - 2];
		if ($sl === 'o') return $e['SUB_ON'];
		if ($sl === 'a') return $e['ADJ_AN'];
		if ($sl === 'e') return $e['ADV_EN'];
		if ($sl === 'j') {
			if ($word_length < 5) return null;
			$tl = $chars[$word_length - 3];
			if ($tl === 'o') return $e['SUB_OJN'];
			if ($tl === 'a') return $e['ADJ_AJN'];
		}
	} elseif ($last_ch === 'j') {
		if ($word_length < 4) return null;
		$sl = $chars[$word_length - 2];
		if ($sl === 'o') return $e['SUB_OJ'];
		if ($sl === 'a') return $e['ADJ_AJ'];
	}

	return null;
}