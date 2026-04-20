<?php
	// literumilo.php
	// Public API: check_word_str(), analyze_string(), analyze_file()
	// Translated from Python by Claude. Original author: Klivo Lendon.

	require_once __DIR__ . '/literumilo_utils.php';
	require_once __DIR__ . '/literumilo_check_word.php';

	// Load dictionary once (module-level, like Python)
	$_literumilo_dictionary = load_dictionary();

	/**
	 * Checks spelling of a single Esperanto word.
	 * Returns an AnalysisResult with ->word (morphemes) and ->valid (bool).
	 *
	 * Usage:
	 *   $result = literumilo_check_word("ĉirkaŭiris");
	 *   if ($result->valid) echo "OK: " . $result->word;
	 */
	function literumilo_check_word(string $word): AnalysisResult {
		global $_literumilo_dictionary;

		return check_word($word, $_literumilo_dictionary);
	}

	/**
	 * Analyses every word in a string.
	 *
	 * If $morpheme_mode is true, returns the string with each word divided into morphemes.
	 * If $morpheme_mode is false, returns a newline-separated list of unknown words.
	 *
	 * Usage:
	 *   $out = literumilo_analyze_string("Birdoj estas", true);
	 *   // → "Bird.oj est.as"
	 */
	function literumilo_analyze_string(string $text, bool $morpheme_mode): string {
		global $_literumilo_dictionary;

		// Tokenise: split on non-word boundaries, preserving delimiters
		$tokens = preg_split('/(\s+|[^\p{L}\p{M}\-\xC2\xAD]+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

		if ($morpheme_mode) {
			$out = '';
			foreach ($tokens as $token) {
				if ($token === '') continue;
				// Check whether token looks like an Esperanto word
				if (preg_match('/[\p{L}]/u', $token)) {
					$result = check_word($token, $_literumilo_dictionary);
					$out   .= $result->word;
				} else {
					$out .= $token;
				}
			}
			return $out;
		} else {
			// Spell-check mode: collect unknown words
			$unknown = [];
			foreach ($tokens as $token) {
				if ($token === '') continue;
				if (!preg_match('/[\p{L}]/u', $token)) continue;
				$result = check_word($token, $_literumilo_dictionary);
				if (!$result->valid) {
					$unknown[] = $token;
				}
			}
			return implode("\n", $unknown);
		}
	}

	/**
	 * Reads a file and calls literumilo_analyze_string() on its contents.
	 */
	function literumilo_analyze_file(string $file_path, bool $morpheme_mode): string {
		$text = file_get_contents($file_path);
		if ($text === false) {
			trigger_error("Cannot read file: $file_path", E_USER_WARNING);
			return '';
		}
		return literumilo_analyze_string($text, $morpheme_mode);
	}
		
	/**
	 * Returns a JSON string describing the morphemes of a single word,
	 * or null if the word is invalid.
	 *
	 * Each element of the array has:
	 *   "morpheme" : the morpheme string (e.g. "mis", "kompren", "it", "a")
	 *   "pos"      : part of speech label (e.g. "Adverb", "Verb", "Participle", "Adjective")
	 *   "type"     : role in the word: "prefix" | "root" | "suffix" | "participle" | "ending" | "separator"
	 *
	 * Example for "miskomprenita":
	 * [
	 *   {"morpheme":"mis",     "pos":"Adverb",     "type":"prefix"},
	 *   {"morpheme":"kompren", "pos":"Verb",       "type":"root"},
	 *   {"morpheme":"it",      "pos":"Participle", "type":"participle"},
	 *   {"morpheme":"a",       "pos":"Adjective",  "type":"ending"}
	 * ]
	 */
	function literumilo_check_word_json(string $word): ?string {
		global $_literumilo_dictionary;

		$morphemes = check_word_morphemes($word, $_literumilo_dictionary);
		if ($morphemes === null) return null;

		return json_encode($morphemes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	}
	
	/**
	 * Returns an HTML string representing the morphological analysis of a word.
	 * Each morpheme is wrapped in a <span> with:
	 *   - class="morfemo {type}"  (e.g. "morfemo prefix")
	 *   - title="{pos}"           (e.g. "Adverb")
	 * followed by a nested <span class="etikedo"> with the type label.
	 *
	 * Returns null if the word is invalid.
	 *
	 * Example for "miskomprenita":
	 * <span class="morfemo prefix" title="Adverb">mis<span class="etikedo">prefix</span></span>
	 * <span class="morfemo root" title="Verb">kompren<span class="etikedo">root</span></span>
	 * ...
	 */
	function literumilo_check_word_html(string $word): ?string {
		global $_literumilo_dictionary;

		$morphemes = check_word_morphemes($word, $_literumilo_dictionary);
		if ($morphemes === null) return null;

		$html = '';
		foreach ($morphemes as $m) {
			$morpheme = htmlspecialchars($m['morpheme'], ENT_QUOTES, 'UTF-8');
			$type     = htmlspecialchars($m['type'],     ENT_QUOTES, 'UTF-8');
			$pos      = htmlspecialchars($m['pos'],      ENT_QUOTES, 'UTF-8');
			$html .= "<div class=\"morfemo {$type}\" title=\"{$pos}\"><span class=\"analizero\">{$morpheme}</span><span class=\"etikedo\">{$type}</span></div>";
		}
		return $html;
	}
?>
