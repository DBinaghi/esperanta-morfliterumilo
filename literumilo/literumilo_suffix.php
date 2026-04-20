<?php
// literumilo_suffix.php
// Checks synthesis of suffixes.
// Translated from Python by Claude. Original author: Klivo Lendon.

require_once __DIR__ . '/literumilo_entry.php';
require_once __DIR__ . '/literumilo_morpheme_list.php';

function check_acx(int $index, MorphemeList $ml): bool {
	if ($index === 0) {
		$e = $ml->get(0);
		if ($e) { $e->part_of_speech = POS::Adjective; return true; }
		return false;
	}
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos  = $prev->part_of_speech;
	$curr = $ml->get($index);
	if ($curr && ($pos <= POS::Adjective || $pos === POS::Participle)) {
		$curr->part_of_speech = $pos;
		$curr->meaning		= $prev->meaning;
		$curr->transitivity   = $prev->transitivity;
		return true;
	}
	return false;
}

function check_ad(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos  = $prev->part_of_speech;
	$curr = $ml->get($index);
	if ($curr && $pos <= POS::Verb) {
		$curr->part_of_speech = POS::Verb;
		$curr->transitivity   = $prev->transitivity;
		return true;
	}
	return false;
}

function check_ajx(int $index, MorphemeList $ml): bool {
	if ($index === 0) return true;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos = $prev->part_of_speech;
	return $pos <= POS::Adjective || $pos === POS::Preposition || $pos === POS::Participle;
}

function check_an(int $index, MorphemeList $ml): bool {
	if ($index === 0) return true;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos = $prev->part_of_speech;
	if ($pos <= POS::SubstantiveVerb) {
		if (is_person($prev->meaning)) return false;
		return true;
	}
	return false;
}

function check_ar(int $index, MorphemeList $ml): bool {
	if ($index === 0) return true;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos = $prev->part_of_speech;
	return $pos <= POS::SubstantiveVerb || $pos === POS::Participle;
}

function check_ebl(int $index, MorphemeList $ml): bool {
	if ($index === 0) return true;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos = $prev->part_of_speech;
	if ($pos === POS::Verb || $pos === POS::SubstantiveVerb) {
		return $prev->transitivity === Transitivity::Transitive;
	}
	return false;
}

function check_ec(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos  = $prev->part_of_speech;
	$curr = $ml->get($index);
	if ($curr && (
		$pos <= POS::SubstantiveVerb ||
		$pos === POS::Adjective ||
		$pos === POS::Number ||
		$pos === POS::Participle
	)) {
		$curr->part_of_speech = POS::Substantive;
		return true;
	}
	return false;
}

function check_eg_et(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos  = $prev->part_of_speech;
	$curr = $ml->get($index);
	if ($curr && $pos <= POS::Adjective) {
		$curr->part_of_speech = $pos;
		$curr->meaning		= $prev->meaning;
		$curr->transitivity   = $prev->transitivity;
		return true;
	}
	return false;
}

function check_ej(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos = $prev->part_of_speech;
	if ($pos <= POS::Adjective) {
		return $prev->meaning !== Meaning::LOKO;
	}
	return false;
}

function check_em(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	return $prev->part_of_speech <= POS::Adjective;
}

function check_end_ind(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	return $prev && $prev->transitivity === Transitivity::Transitive;
}

function check_er(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	if ($prev->morpheme === 'sup') return false;
	return $prev->part_of_speech <= POS::SubstantiveVerb;
}

function check_ik_ing_ism(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	return $prev && $prev->part_of_speech <= POS::SubstantiveVerb;
}

function check_estr(int $index, MorphemeList $ml): bool {
	if ($index === 0) {
		$curr = $ml->get(0);
		if ($curr) {
			$curr->part_of_speech = POS::Substantive;
			$curr->meaning		= Meaning::PERSONO;
			return true;
		}
		return false;
	}
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$curr = $ml->get($index);
	if ($curr && $prev->part_of_speech <= POS::SubstantiveVerb) {
		$curr->part_of_speech = POS::Substantive;
		$curr->meaning		= Meaning::PERSONO;
		return true;
	}
	return false;
}

function check_id(int $index, MorphemeList $ml): bool {
	if ($index === 0) return true;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	return $prev->meaning === Meaning::ETNO || is_animal($prev->meaning);
}

function check_ig_igx(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos = $prev->part_of_speech;
	return $pos <= POS::Adverb || $pos === POS::Preposition || $pos === POS::Prefix;
}

function check_il(int $index, MorphemeList $ml): bool {
	if ($index === 0) return true;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos = $prev->part_of_speech;
	return ($pos === POS::Verb || $pos === POS::SubstantiveVerb) && $prev->meaning !== Meaning::ILO;
}

function check_in(int $index, MorphemeList $ml): bool {
	if ($index === 0) return true;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	return is_person($prev->meaning) || is_animal($prev->meaning);
}

function check_ist(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos = $prev->part_of_speech;
	return $pos <= POS::Verb && !is_person($prev->meaning);
}

function check_obl_on_op(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	return $prev && $prev->part_of_speech === POS::Number;
}

function check_uj(int $index, MorphemeList $ml): bool {
	if ($index === 0) return false;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos = $prev->part_of_speech;
	return $pos <= POS::SubstantiveVerb && $prev->meaning !== Meaning::ARBO;
}

function check_ul(int $index, MorphemeList $ml): bool {
	if ($index === 0) return true;
	$prev = $ml->get($index - 1);
	if (!$prev) return false;
	$pos = $prev->part_of_speech;
	if ($pos === POS::Participle)  return true;
	if ($pos <= POS::Adjective && !is_person($prev->meaning)) return true;
	if ($pos === POS::Preposition) return true;
	return false;
}

/**
 * Dispatcher: checks synthesis of the given suffix string.
 */
function check_suffix(string $suffix, int $index, MorphemeList $ml): bool {
	switch ($suffix) {
		case 'aĉ':  return check_acx($index, $ml);
		case 'ad':  return check_ad($index, $ml);
		case 'aĵ':  return check_ajx($index, $ml);
		case 'an':  return check_an($index, $ml);
		case 'ar':  return check_ar($index, $ml);
		case 'ebl': return check_ebl($index, $ml);
		case 'ec':  return check_ec($index, $ml);
		case 'eg':
		case 'et':  return check_eg_et($index, $ml);
		case 'ej':  return check_ej($index, $ml);
		case 'em':  return check_em($index, $ml);
		case 'end':
		case 'ind': return check_end_ind($index, $ml);
		case 'er':  return check_er($index, $ml);
		case 'ik':
		case 'ing':
		case 'ism': return check_ik_ing_ism($index, $ml);
		case 'estr': return check_estr($index, $ml);
		case 'id':  return check_id($index, $ml);
		case 'ig':
		case 'iĝ':  return check_ig_igx($index, $ml);
		case 'il':  return check_il($index, $ml);
		case 'in':  return check_in($index, $ml);
		case 'ist': return check_ist($index, $ml);
		case 'obl':
		case 'on':
		case 'op':  return check_obl_on_op($index, $ml);
		case 'uj':  return check_uj($index, $ml);
		case 'ul':  return check_ul($index, $ml);
	}
	return false;
}
