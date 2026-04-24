<?php
	// literumilo_morpheme_list.php
	// List of morphemes for the Esperanto spell checker.
	// Translated from Python by Claude. Original author: Klivo Lendon.

	require_once __DIR__ . '/literumilo_entry.php';

	class MorphemeList {
		const MAX_MORPHEMES = 9;

		private Ending $ending;
		private int	$last_index;
		/** @var (EspDictEntry|null)[] */
		private array  $morphemes;

		public function __construct(Ending $ending) {
			$this->ending	 = $ending;
			$this->last_index = 0;
			$this->morphemes  = array_fill(0, self::MAX_MORPHEMES, null);
		}

		public function get_last_index(): int {
			return $this->last_index;
		}

		public function type_of_ending(): int {
			return $this->ending->part_of_speech;
		}

		/**
		 * Returns a dot-separated morpheme string, e.g. 'for.ig.it.a'.
		 */
		public function display_form(string $separator='.'): string {
			$s = $this->morphemes[0]->morpheme;
			for ($i = 1; $i <= $this->last_index; $i++) {
				$s .= $separator . $this->morphemes[$i]->morpheme;
			}
			return $s . $separator . $this->ending->ending;
		}

		/**
		 * Counts the number of separator vowels in the list.
		 * Only one separator per word is allowed.
		 */
		public function count_separators(): int {
			$count = 0;
			for ($i = 0; $i <= $this->last_index; $i++) {
				if ($this->morphemes[$i] !== null && $this->morphemes[$i]->flag === 'separator') {
					$count++;
				}
			}
			return $count;
		}

		public function get(int $index): ?EspDictEntry {
			if ($index >= self::MAX_MORPHEMES) {
				trigger_error("MorphemeList::get() bad index $index", E_USER_WARNING);
				return null;
			}
			return $this->morphemes[$index];
		}

		public function put(int $index, EspDictEntry $entry): void {
			if ($index >= self::MAX_MORPHEMES) {
				trigger_error("MorphemeList::put() bad index $index", E_USER_WARNING);
				return;
			}
			$this->last_index	   = $index;
			$this->morphemes[$index] = $entry;
		}
	}
?>