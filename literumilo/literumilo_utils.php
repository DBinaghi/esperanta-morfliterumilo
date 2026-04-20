<?php
// literumilo_utils.php
// Utility functions for the Esperanto spell checker 'literumilo'.
// Translated from Python by Claude. Original author: Klivo Lendon.

/**
 * Tests whether the given letter can accept an Esperanto accent (hat).
 * For example, 'c' can take an accent (ĉ).
 */
function accepts_hat(string $letter): bool {
	$l = mb_strtolower($letter);
	return in_array($l, ['c', 'g', 'h', 'j', 's', 'u'], true);
}

/**
 * Tests whether the given letter is x or X.
 */
function is_x(string $letter): bool {
	return $letter === 'x' || $letter === 'X';
}

/**
 * Puts an Esperanto accent on the given letter.
 */
function accent_letter(string $letter): string {
	$map = [
		'c' => 'ĉ', 'g' => 'ĝ', 'h' => 'ĥ', 'j' => 'ĵ', 's' => 'ŝ', 'u' => 'ŭ',
		'C' => 'Ĉ', 'G' => 'Ĝ', 'H' => 'Ĥ', 'J' => 'Ĵ', 'S' => 'Ŝ', 'U' => 'Ŭ',
	];
	return $map[$letter] ?? '?';
}

/**
 * Returns true for word characters (letters, hyphens), false for punctuation/whitespace.
 */
function is_word_char(string $ch): bool {
	if ($ch >= 'a' && $ch <= 'z') return true;
	if ($ch >= 'A' && $ch <= 'Z') return true;
	$cp = mb_ord($ch);
	if ($cp >= mb_ord('À') && $cp <= mb_ord('ʯ')) return true;
	if ($ch === '-' || $ch === "\xC2\xAD") return true; // 0x00AD = soft hyphen
	return false;
}

/**
 * Returns true for hyphens (U+002D and U+00AD soft hyphen).
 */
function is_hyphen(string $ch): bool {
	return $ch === '-' || $ch === "\xC2\xAD";
}

/**
 * Removes hyphens from a string.
 */
function remove_hyphens(string $word): string {
	return str_replace(['-', "\xC2\xAD"], '', $word);
}

/**
 * Converts x-method Esperanto to Unicode accented letters.
 * E.g. 'cxirkaux' → 'ĉirkaŭ'
 */
function x_to_accent(string $word): string {
	$chars = mb_str_split($word);
	$length = count($chars);
	$new_word = '';
	$skip_x = false;

	for ($i = 0; $i < $length; $i++) {
		if ($skip_x) {
			$skip_x = false;
			continue;
		}
		$ch1 = $chars[$i];
		if (accepts_hat($ch1)) {
			if ($i < $length - 1) {
				$ch2 = $chars[$i + 1];
				if (is_x($ch2)) {
					$new_word .= accent_letter($ch1);
					$skip_x = true;
				} else {
					$new_word .= $ch1;
				}
			} else {
				$new_word .= $ch1;
			}
		} else {
			$new_word .= $ch1;
		}
	}
	return $new_word;
}

/**
 * Restores the original capitalisation to an analysed word.
 * E.g. original='RIĈULO', analysed='riĉ.ul.o' → 'RIĈ.UL.O'
 */
function restore_capitals(string $original, string $analyzed): string {
	$result = '';
	$index = 0;
	$original_chars = mb_str_split($original);
	$original_length = count($original_chars);
	$analyzed_chars = mb_str_split($analyzed);

	foreach ($analyzed_chars as $ch) {
		if ($ch === '.') {
			$result .= '.';
		} else {
			if ($index < $original_length) {
				$result .= $original_chars[$index];
			} else {
				$result .= $ch;
			}
			$index++;
		}
	}
	return $result;
}
