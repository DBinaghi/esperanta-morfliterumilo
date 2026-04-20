<?php
// literumilo_entry.php
// Dictionary entry class and enumerations for the Esperanto spell checker.
// Translated from Python by Claude. Original author: Klivo Lendon.

require_once __DIR__ . '/literumilo_utils.php';

class POS {
	const Substantive		= 1;
	const SubstantiveVerb	= 2;
	const Verb				= 3;
	const Adjective			= 4;
	const Number			= 5;
	const Adverb			= 6;
	const Pronoun			= 7;
	const PronounAdjective	= 8;
	const Preposition		= 9;
	const Conjunction		= 10;
	const Subjunction		= 11;
	const Interjection		= 12;
	const Prefix			= 13;
	const TechPrefix		= 14;
	const Suffix			= 15;
	const Article			= 16;
	const Participle		= 17;
	const Abbreviation		= 18;
	const Letter			= 19;
}

class Cap {
	const Miniscule = 1;
	const Majuscule = 2;
	const AllCaps	= 3;
}

class Transitivity {
	const Transitive	= 1;
	const Intransitive	= 2;
	const Both			= 3;
}

class WithoutEnding {
	const No	= 1;
	const Yes	= 2;
}

class WithEnding {
	const No	= 1;
	const Yes	= 2;
}

class Synthesis {
	const Suffix		= 1;
	const Prefix		= 2;
	const Participle	= 3;
	const Limited		= 4;
	const UnLimited 	= 5;
	const No			= 6;
}

class Meaning {
	const N				= 1;
	const LEGOMO		= 2;
	const BOATO			= 3;
	const KRUSTULO		= 4;
	const INSULO		= 5;
	const RELIGIO		= 6;
	const HERBO			= 7;
	const KOLORO		= 8;
	const PLANTO		= 9;
	const FESTO			= 10;
	const LIBRO			= 11;
	const LOKO			= 12;
	const DROGO			= 13;
	const LAGO			= 14;
	const PSEUXDOSCI	= 15;
	const RELPOSTENO	= 16;
	const PROFESIO		= 17;
	const GRAMATIKO		= 18;
	const EHXINODERMO	= 19;
	const MEDIKAMENTO	= 20;
	const REGIONO		= 21;
	const BIOLOGIO		= 22;
	const BIRDO			= 23;
	const URBO			= 24;
	const VETURILO		= 25;
	const LANDO			= 26;
	const ETNO			= 27;
	const KANTO			= 28;
	const VESTAJXO		= 29;
	const TITOLO		= 30;
	const REGANTO		= 31;
	const RIVERO		= 32;
	const ARTO			= 33;
	const ERAO			= 34;
	const PROVINCO		= 35;
	const MUZIKO		= 36;
	const PERSONO		= 37;
	const SXTATO		= 38;
	const MAMULO		= 39;
	const FISXO			= 49;
	const MEZURUNUO		= 41;
	const FUNGO			= 42;
	const KURACARTO		= 43;
	const ARMILO		= 44;
	const ALGO			= 45;
	const KOELENTERO	= 46;
	const NUKSO			= 47;
	const MONTO			= 48;
	const GEOGRAFIO		= 49;
	const TEHXNOLOGIO	= 50;
	const MONATO		= 51;
	const ARKITEKTURO	= 52;
	const INSULARO		= 53;
	const METIO			= 54;
	const ASTRONOMIO	= 55;
	const KREDO			= 56;
	const MOLUSKO		= 57;
	const REPTILIO		= 58;
	const TRINKAJXO		= 59;
	const ANIMALO		= 60;
	const INSEKTO		= 61;
	const FRUKTO		= 62;
	const ARBUSTO		= 63;
	const ARAKNIDO		= 64;
	const AVIADILO		= 65;
	const SPORTO		= 66;
	const ELEMENTO		= 67;
	const ALOJO			= 68;
	const RELPERSONO	= 69;
	const RELPROFESIO	= 70;
	const KEMIAJXO		= 71;
	const FILOZOFIO		= 72;
	const SXTOFO		= 73;
	const POSTENO		= 74;
	const PARENCO		= 75;
	const KONSTRUAJXO	= 76;
	const CEREALO		= 77;
	const DANCO			= 78;
	const TAGO			= 79;
	const POEMO			= 80;
	const SXIPO			= 81;
	const LUDILO		= 82;
	const POEZIO		= 83;
	const CXAMBRO		= 84;
	const MANGXAJXO		= 85;
	const ASTRO			= 86;
	const ILO			= 87;
	const MIKROBO		= 88;
	const LUDO			= 89;
	const DEZERTO		= 90;
	const MITBESTO		= 91;
	const DRAMO			= 92;
	const VETERO		= 93;
	const ARBO			= 94;
	const SCIENCO		= 95;
	const ORNAMAJXO		= 96;
	const VERMO			= 97;
	const MINERALO		= 98;
	const SPICO			= 99;
	const MASXINO		= 100;
	const KONTINENTO	= 101;
	const PERIODO		= 102;
	const LINGVO		= 103;
	const MEZURILO		= 104;
	const MARO			= 105;
	const MONTARO		= 106;
	const MITPERSONO	= 107;
	const FONETIKO		= 108;
	const MONERO		= 109;
	const MATEMATIKO	= 110;
	const RANGO			= 111;
	const ANATOMIO		= 112;
	const STUDO			= 113;
	const OPTIKO		= 114;
	const AMFIBIO		= 115;
	const MALSANO		= 116;
	const MUZIKILO		= 117;
	const GEOMETRIO		= 118;

	public static function fromName(string $name): int {
		static $map = null;
		if ($map === null) {
			$map = [
				'N'=>1, 'LEGOMO'=>2, 'BOATO'=>3, 'KRUSTULO'=>4, 'INSULO'=>5, 
				'RELIGIO'=>6, 'HERBO'=>7, 'KOLORO'=>8, 'PLANTO'=>9, 'FESTO'=>10, 
				'LIBRO'=>11, 'LOKO'=>12, 'DROGO'=>13, 'LAGO'=>14, 'PSEUXDOSCI'=>15, 
				'RELPOSTENO'=>16, 'PROFESIO'=>17, 'GRAMATIKO'=>18, 'EHXINODERMO'=>19, 'MEDIKAMENTO'=>20, 
				'REGIONO'=>21, 'BIOLOGIO'=>22, 'BIRDO'=>23, 'URBO'=>24, 'VETURILO'=>25, 
				'LANDO'=>26, 'ETNO'=>27, 'KANTO'=>28, 'VESTAJXO'=>29, 'TITOLO'=>30, 
				'REGANTO'=>31, 'RIVERO'=>32, 'ARTO'=>33, 'ERAO'=>34, 'PROVINCO'=>35, 
				'MUZIKO'=>36, 'PERSONO'=>37, 'SXTATO'=>38, 'MAMULO'=>39, 'FISXO'=>49, 
				'MEZURUNUO'=>41, 'FUNGO'=>42, 'KURACARTO'=>43, 'ARMILO'=>44, 'ALGO'=>45, 
				'KOELENTERO'=>46, 'NUKSO'=>47, 'MONTO'=>48, 'GEOGRAFIO'=>49, 'TEHXNOLOGIO'=>50, 
				'MONATO'=>51, 'ARKITEKTURO'=>52, 'INSULARO'=>53, 'METIO'=>54, 'ASTRONOMIO'=>55, 
				'KREDO'=>56, 'MOLUSKO'=>57, 'REPTILIO'=>58, 'TRINKAJXO'=>59, 'ANIMALO'=>60, 
				'INSEKTO'=>61, 'FRUKTO'=>62, 'ARBUSTO'=>63, 'ARAKNIDO'=>64, 'AVIADILO'=>65, 
				'SPORTO'=>66, 'ELEMENTO'=>67, 'ALOJO'=>68, 'RELPERSONO'=>69, 'RELPROFESIO'=>70, 
				'KEMIAJXO'=>71, 'FILOZOFIO'=>72, 'SXTOFO'=>73, 'POSTENO'=>74, 'PARENCO'=>75, 
				'KONSTRUAJXO'=>76, 'CEREALO'=>77, 'DANCO'=>78, 'TAGO'=>79, 'POEMO'=>80, 
				'SXIPO'=>81, 'LUDILO'=>82, 'POEZIO'=>83, 'CXAMBRO'=>84, 'MANGXAJXO'=>85, 
				'ASTRO'=>86, 'ILO'=>87, 'MIKROBO'=>88, 'LUDO'=>89, 'DEZERTO'=>90, 
				'MITBESTO'=>91, 'DRAMO'=>92, 'VETERO'=>93, 'ARBO'=>94, 'SCIENCO'=>95, 
				'ORNAMAJXO'=>96, 'VERMO'=>97, 'MINERALO'=>98, 'SPICO'=>99, 'MASXINO'=>100, 
				'KONTINENTO'=>101, 'PERIODO'=>102, 'LINGVO'=>103, 'MEZURILO'=>104, 'MARO'=>105, 
				'MONTARO'=>106, 'MITPERSONO'=>107, 'FONETIKO'=>108, 'MONERO'=>109, 'MATEMATIKO'=>110, 
				'RANGO'=>111, 'ANATOMIO'=>112, 'STUDO'=>113, 'OPTIKO'=>114, 'AMFIBIO'=>115, 
				'MALSANO'=>116, 'MUZIKILO'=>117, 'GEOMETRIO'=>118,
			];
		}
		return $map[$name] ?? self::N;
	}
}

function is_person(int $meaning): bool {
	return in_array($meaning, [
		Meaning::PERSONO, Meaning::PARENCO, Meaning::ETNO,
		Meaning::PROFESIO, Meaning::RANGO, Meaning::REGANTO,
		Meaning::TITOLO, Meaning::POSTENO, Meaning::RELPOSTENO,
		Meaning::RELPROFESIO, Meaning::MITPERSONO,
	], true);
}

function is_animal(int $meaning): bool {
	return in_array($meaning, [
		Meaning::ANIMALO, Meaning::MAMULO, Meaning::BIRDO,
		Meaning::FISXO, Meaning::REPTILIO, Meaning::MITBESTO,
		Meaning::INSEKTO, Meaning::ARAKNIDO, Meaning::MOLUSKO,
		Meaning::AMFIBIO,
	], true);
}

class EspDictEntry {
	public string $morpheme;
	public int	$length;
	public int	$capitalization;
	public int	$part_of_speech;
	public int	$meaning;
	public int	$transitivity;
	public int	$without_ending;
	public int	$with_ending;
	public int	$synthesis;
	public int	$rarity;
	public string $flag;

	private function _get_transitivity(string $s): int {
		if ($s === 'T') return Transitivity::Transitive;
		if ($s === 'X') return Transitivity::Both;
		return Transitivity::Intransitive;
	}

	private function _get_synthesis(string $s): int {
		if ($s === 'P')	return Synthesis::Prefix;
		if ($s === 'S')	return Synthesis::Suffix;
		if ($s === 'PRT') return Synthesis::Participle;
		if ($s === 'LM') return Synthesis::Limited;
		if ($s === 'NLM') return Synthesis::UnLimited;
		return Synthesis::No;
	}

	private function _get_part_of_speech(string $s): int {
		static $map = [
			'SUBST'=>1, 'SUBSTVERBO'=>2, 'VERBO'=>3, 'ADJ'=>4, 'NUMERO'=>5,
			'ADVERBO'=>6, 'PRONOMO'=>7, 'PRONOMADJ'=>8, 'PREPOZICIO'=>9,
			'KONJUNKCIO'=>10, 'SUBJUNKCIO'=>11, 'INTERJEKCIO'=>12, 'PREFIKSO'=>13,
			'TEHXPREFIKSO'=>14, 'SUFIKSO'=>15, 'ARTIKOLO'=>16, 'PARTICIPO'=>17,
			'MALLONGIGO'=>18, 'LITERO'=>19,
		];
		return $map[$s] ?? POS::Substantive;
	}

	private function _get_capitalization(string $word): int {
		$chars = mb_str_split($word);
		if (count($chars) < 2) return Cap::Miniscule;
		$fu = mb_strtoupper($chars[0]) === $chars[0] && mb_strtolower($chars[0]) !== $chars[0];
		$su = mb_strtoupper($chars[1]) === $chars[1] && mb_strtolower($chars[1]) !== $chars[1];
		if ($fu && $su) return Cap::AllCaps;
		if ($fu)		return Cap::Majuscule;
		return Cap::Miniscule;
	}

	public function __construct(array $data_array) {
		$morpheme				= x_to_accent($data_array[0]);
		$this->morpheme			= $morpheme;
		$this->length			= mb_strlen($morpheme);
		$this->capitalization	= $this->_get_capitalization($morpheme);
		$this->part_of_speech	= $this->_get_part_of_speech($data_array[1]);
		$this->meaning			= Meaning::fromName($data_array[2]);
		$this->transitivity		= $this->_get_transitivity($data_array[3]);
		$this->without_ending	= $this->_get_without_ending($data_array[4]);
		$this->with_ending		= $this->_get_with_ending($data_array[5]);
		$this->synthesis		= $this->_get_synthesis($data_array[6]);
		$this->rarity			= (int) $data_array[7];
		$this->flag				= trim($data_array[8]);
	}

	private function _get_without_ending(string $s): int {
		return $s === 'SF' ? WithoutEnding::Yes : WithoutEnding::No;
	}

	private function _get_with_ending(string $s): int {
		return $s === 'KF' ? WithEnding::Yes : WithEnding::No;
	}

	public static function new_separator(string $separator): ?EspDictEntry {
		if ($separator === 'o')		$pos = 'SUBST';
		elseif ($separator === 'a') $pos = 'ADJ';
		elseif ($separator === 'e') $pos = 'ADVERBO';
		else return null;
		return new EspDictEntry([$separator, $pos, 'N', 'N', 'N', 'N', 'N', '0', 'separator']);
	}
}
