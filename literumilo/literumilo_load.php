<?php
// literumilo_load.php
// Loads the Esperanto dictionary (vortaro.tsv) into a hash map.
// Translated from Python by Claude. Original author: Klivo Lendon.

require_once __DIR__ . '/literumilo_entry.php';

/**
 * Parses tab-separated dictionary lines and returns an associative array
 * keyed by lowercase morpheme (Unicode characters, dots removed).
 *
 * @return EspDictEntry[]   keyed by morpheme string
 */
function make_dictionary(array $lines): array {
	$dict = [];
	foreach ($lines as $line) {
		$line = trim($line);
		if (mb_strlen($line) < 10) continue;   // too short — junk
		if ($line[0] === '#')	   continue;   // comment

		$parts = explode("\t", $line);
		if (count($parts) < 9) {
			// error in dictionary line — skip silently
			continue;
		}

		$entry = new EspDictEntry($parts);
		if ($entry->flag === 'X') continue;	 // excluded entries

		// Key: lower-case morpheme, dots stripped (x_to_accent already applied in constructor)
		$key = mb_strtolower(str_replace('.', '', $entry->morpheme));
		$dict[$key] = $entry;
	}
	return $dict;
}

/**
 * Reads vortaro.tsv and returns the dictionary hash map.
 *
 * @return EspDictEntry[]
 */
function load_dictionary(): array {
	$dict_path = __DIR__ . '/data/vortaro.tsv';
	$lines = file($dict_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	if ($lines === false) {
		trigger_error("Cannot open dictionary: $dict_path", E_USER_ERROR);
		return [];
	}
	return make_dictionary($lines);
}
