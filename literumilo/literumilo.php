<?php
	// literumilo.php
	require_once __DIR__ . '/literumilo_utils.php';
	require_once __DIR__ . '/literumilo_check_word.php';

	$_literumilo_dictionary = load_dictionary();

	/**
	 * Analizza un testo (parola o frase) e restituisce un array strutturato
	 * pronto per essere convertito in JSON.
	 */
	function literumilo_get_analysis_array(string $text): array {
		global $_literumilo_dictionary;
		$results = [];

		// Split su spazi e punteggiatura, mantenendo i delimitatori
		$tokens = preg_split('/(\s+|[^\p{L}\p{M}\-]+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		foreach ($tokens as $token) {
			// Se contiene lettere, lo trattiamo come parola
			if (preg_match('/[\p{L}]/u', $token)) {
				// Trasformiamo in minuscolo per l'analisi linguistica
				$token_lower = mb_strtolower($token, 'UTF-8');

				// Cerchiamo le soluzioni usando il token minuscolo
				$all_solutions = check_word_morphemes_all($token, $_literumilo_dictionary);
				
				$results[] = [
					"word" => $token,
					"valid" => !empty($all_solutions),
					"is_punctuation" => false,
					"solutions" => $all_solutions ?: []
				];
			} else {
				// È uno spazio o punteggiatura
				$results[] = [
					"word" => $token,
					"valid" => true,
					"is_punctuation" => true,
					"solutions" => []
				];
			}
		}

		return $results;
	}

	/**
	 * Endpoint pubblico che restituisce il JSON
	 */
	function literumilo_analyze_to_json(string $text): string {
		$data = literumilo_get_analysis_array($text);
		return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	}
?>