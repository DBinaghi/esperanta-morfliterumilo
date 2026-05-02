<?php
	require_once 'literumilo/literumilo.php';

	$testo_da_analizzare = $_POST['teksto'] ?? "";
	$versio = "2.7";
	
	/**
	 * Trasforma la struttura dati (array/json) in output HTML.
	 */
	function render_html(array $data): string {
		if (empty($data)) return "";

		$output = '';
		foreach ($data as $item) {
			if (isset($item['is_punctuation']) && $item['is_punctuation']) {
				// Gestione spazi e punteggiatura (converte newline in <br>)
				continue;
			}

			if (!$item['valid']) {
				// Parola non riconosciuta
				$output .= '<div class="risultato invalido">';
				$output .= '<p class="descrizione">' . htmlspecialchars($item['word']) . ' <span class="fail-badge">Ne-analizita</span></p>';
				$output .= '</div>';
				continue;
			} else {
				// Parola valida: creiamo il contenitore per le soluzioni
				$output .= '<div class="risultato">';
				$output .= '<p class="descrizione">' . htmlspecialchars($item['word']) . '</p>';
				foreach ($item['solutions'] as $sol) {
					$output .= '<div class="scomposizione">';
					foreach ($sol as $m) {
						$morpheme = htmlspecialchars($m['morpheme']);
						$type = htmlspecialchars($m['type']);
						$pos = mb_strtolower(htmlspecialchars($m['pos']));
						switch ($type) {
							case 'radiko':
							case 'preposicio':
								$output .= "<div class=\"morfemo radiko\">{$morpheme}<div class=\"etikedo\">radiko<br><span class=\"pos\">{$pos}</span></div></div>";
								break;
							case 'finaĵo':
							case 'akuzativo':
							case 'pluralo':
							case 'disigilo':
							case 'participo':
								$output .= "<div class=\"morfemo finaĵo\">{$morpheme}<div class=\"etikedo\">finaĵo<br><span class=\"pos\">{$pos}</span></div></div>";
								break;
							case 'prefikso':
								$output .= "<div class=\"morfemo prefikso\">{$morpheme}<div class=\"etikedo\">prefikso<br><span class=\"pos\">{$pos}</span></div></div>";
								break;
							case 'sufikso':
								$output .= "<div class=\"morfemo sufikso\">{$morpheme}<div class=\"etikedo\">sufikso<br><span class=\"pos\">{$pos}</span></div></div>";
								break;
						}
					}
					$output .= '</div>';
				}
				$output .= '</div>';
			}
		}
		return $output;
	}
?>

<!DOCTYPE html>
<html lang="eo">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Vortanalizilo <?= $versio ?> — hVortaro</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Source+Sans+3:ital,wght@0,300;0,400;0,600;1,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
		<style>
			:root {
				--green-dark:  #1a5c2a;
				--green-mid:   #2d8a45;
				--green-light: #4ab563;
				--green-pale:  #e8f5ec;
				--salmon:      #e87060;
				--salmon-pale: #fdecea;
				--bg:          #f7f9f7;
				--surface:     #ffffff;
				--border:      #d4e8da;
				--text:        #1c2e22;
				--text-mid:    #4a6855;
				--text-light:  #7a9e88;
				--radius:      10px;
				--shadow:      0 2px 12px rgba(30,80,45,0.09);
			}
			* { 
				box-sizing: border-box;
				margin: 0;
				padding: 0;
			}
			body {
				font-family: 'Roboto', 'Open Sans', sans-serif;
				background: var(--bg);
				color: var(--text);
				min-height: 100vh;
				padding: 0 0 60px;
			}
			a { 
				color: var(--green-mid);
				text-decoration: none;
				font-weight: bold;
			}
			a:hover { 
				color: var(--green-dark);
				text-decoration: underline;
			}

			/* ── HEADER ── */
			header {
				background: var(--green-dark);
				color: white;
				padding: 0 24px;
				box-shadow: 0 2px 8px rgba(0,0,0,0.18);
			}
			header .header-inner {
				max-width: 780px;
				margin: 0 auto;
				padding: 18px 0 16px;
				display: flex;
				align-items: center;
				gap: 14px;
			}
			header a.header-home {
				display: flex;
				align-items: center;
				gap: 14px;
				text-decoration: none;
				color: inherit;
			}
			header a.header-home:hover .logo-mark { background: var(--green-mid); }
			header a.header-home:hover .page-title { 
				text-decoration: underline;
				text-underline-offset: 3px;
			}
			header .logo-mark {
				width: 38px;
				height: 38px;
				background: var(--green-light);
				border-radius: 8px;
				display: flex;
				align-items: center;
				justify-content: center;
				font-family: 'JetBrains Mono', monospace;
				font-size: 17px;
				font-weight: 500;
				color: white;
				letter-spacing: -1px;
				flex-shrink: 0;
				transition: background 0.18s;
			}
			header .site-name {
				font-size: 13px;
				font-weight: 300;
				opacity: 0.75;
				letter-spacing: 0.04em;
			}
			header .page-title {
				font-family: 'Roboto', 'Open Sans', serif;
				font-size: 22px;
				color: white;
				font-weight: 600;
				line-height: 1.1;
			}
			header .version-badge {
				margin-left: auto;
				background: rgba(255,255,255,0.15);
				border: 1px solid rgba(255,255,255,0.25);
				border-radius: 20px;
				padding: 3px 10px;
				font-size: 12px;
				font-family: 'JetBrains Mono', monospace;
				color: rgba(255,255,255,0.85);
				flex-shrink: 0;
			}

			/* ── LAYOUT ── */
			.container { 
				max-width: 780px;
				margin: 0 auto;
				padding: 28px 20px 0;
			}

			/* ── INPUT PANEL ── */
			.input-panel {
				background: var(--surface);
				border: 1.5px solid var(--border);
				border-radius: var(--radius);
				box-shadow: var(--shadow);
				padding: 20px;
				margin-bottom: 20px;
			}
			.search-box { 
				display: flex;
				gap: 10px;
				align-items: stretch;
				margin-bottom: 0;
			}
			textarea {
				flex: 1;
				border: 1.5px solid var(--border);
				border-radius: 8px;
				padding: 12px 14px;
				font-family: 'Roboto', 'Open Sans', sans-serif;
				font-size: 15px;
				color: var(--text);
				resize: none;
				height: 80px;
				background: var(--bg);
				transition: border-color 0.2s, box-shadow 0.2s;
				line-height: 1.5;
			}
			textarea:focus {
				outline: none;
				border-color: var(--green-mid);
				box-shadow: 0 0 0 3px rgba(45,138,69,0.12);
				background: white;
			}
			textarea::placeholder { color: var(--text-light); }
			button[type="submit"] {
				background: var(--green-mid);
				color: white;
				border: none;
				border-radius: 8px;
				padding: 0 22px;
				font-family: 'Roboto', 'Open Sans', sans-serif;
				font-size: 15px;
				font-weight: 600;
				cursor: pointer;
				transition: background 0.18s, transform 0.1s;
				letter-spacing: 0.02em;
				white-space: nowrap;
			}
			button[type="submit"]:hover { 
				background: var(--green-dark);
			}
			button[type="submit"]:active { 
				transform: scale(0.97);
			}

			/* ── NOTA INFO ── */
			.nota {
				margin-top: 14px;
				padding: 10px 14px;
				background: #eef4fb;
				border-radius: var(--radius);
				font-size: 12px;
				color: var(--text-mid);
				line-height: 1.45;
				display: flex;
				gap: 10px;
				align-items: flex-start;
			}
			.nota svg { 
				flex-shrink: 0;
				margin-top: 1px;
			}

			/* ── STATS BAR ── */
			.resumo {
				display: flex;
				gap: 10px;
				align-items: center;
				justify-content: center;
				margin-bottom: 22px;
				padding: 0 2px;
				flex-wrap: wrap;
				text-align: left; /* override vecchio stile */
			}
			.stat-chip {
				display: flex;
				align-items: center;
				gap: 6px;
				background: var(--surface);
				border: 1px solid var(--border);
				border-radius: 20px;
				padding: 5px 14px 5px 10px;
				font-size: 13.5px;
				color: var(--text-mid);
				box-shadow: 0 1px 4px rgba(0,0,0,0.05);
			}
			.stat-chip .dot { 
				width: 8px;
				height: 8px;
				border-radius: 50%;
				flex-shrink: 0;
			}
			.stat-chip b {
				font-family: 'JetBrains Mono', monospace;
				font-size: 14px;
				font-weight: 500;
				color: var(--text);
			}
			.dot-total { background: #222; }
			.dot-ok    { background: var(--green-light); }
			.dot-fail  { background: var(--salmon); }

			/* ── SECTION TITLE ── */
			.esempio-titolo {
				font-family: 'Roboto', 'Open Sans', serif;
				font-size: 22px;
				font-weight: 600;
				color: var(--text);
				padding-bottom: 8px;
				margin-bottom: .5em;
				display: flex;
				align-items: center;
				gap: 8px;
			}
			
			/* ── WORD BLOCK ── */
			.risultato {
				background: var(--surface);
				border: 1.5px solid var(--border);
				border-left: 4px solid var(--green-mid);
				border-radius: var(--radius);
				padding: 16px 18px;
				margin-bottom: 12px;
				box-shadow: var(--shadow);
			}
			.risultato.invalido {
				border-left-color: var(--salmon);
				background: var(--salmon-pale);
			}
			.risultato.invalido .descrizione { color: var(--salmon); }

			.descrizione {
				font-family: 'JetBrains Mono', monospace;
				font-size: 16px;
				font-weight: 700;
				color: black;
				margin-bottom: 12px;
				letter-spacing: 0.01em;
				display: flex;
				align-items: center;
				gap: 8px;
			}
			.fail-badge {
				font-family: 'Roboto', 'Open Sans', sans-serif;
				font-size: 11px;
				font-weight: 600;
				text-transform: uppercase;
				letter-spacing: 0.06em;
				background: var(--salmon);
				color: white;
				border-radius: 4px;
				padding: 1px 7px;
			}

			/* ── MORFEMOJ ── */
			.scomposizione { 
				display: flex;
				flex-wrap: wrap;
				gap: 8px;
			}
			.scomposizione:not(:last-child) { margin-bottom: 10px; }

			.morfemo {
				display: flex;
				flex-direction: column;
				align-items: center;
				border-radius: 8px;
				padding: 10px 16px 8px;
				min-width: 64px;
				text-align: center;
				border: 1.5px solid transparent;
				transition: transform 0.15s, box-shadow 0.15s;
				cursor: default;
				font-family: 'JetBrains Mono', monospace;
				font-size: 15px;
				font-weight: 500;
				color: var(--text);
			}
			.morfemo:hover { 
				transform: translateY(-2px);
				box-shadow: 0 4px 12px rgba(0,0,0,0.1);
			}

			.morfemo.radiko    { background: #e6f4ea; border-color: #9dd4ac; }
			.morfemo.prefikso  { background: #e8f2f8; border-color: #93c0d8; }
			.morfemo.sufikso   { background: #fdf3e3; border-color: #e8c07a; }
			.morfemo.finaĵo    { background: #fdecea; border-color: #f4a89d; }
			.morfemo.participo { background: #f3e8ff; border-color: #c084fc; }
			.morfemo.pluralo   { background: #fdecea; border-color: #f4a89d; }
			.morfemo.akuzativo { background: #ffedd5; border-color: #fdba74; }
			.morfemo.disigilo  { background: #cffafe; border-color: #67e8f9; }

			.etikedo {
				display: block;
				margin-top: 6px;
				font-family: 'Roboto', 'Open Sans', sans-serif;
				font-size: 11.5px;
				font-weight: 600;
				text-transform: capitalize;
				letter-spacing: 0.02em;
				color: var(--text-mid);
				line-height: 1;
			}
			.etikedo .pos {
				display: block;
				font-style: italic;
				font-size: 10px;
				color: var(--text-light);
				margin-top: 2px;
				font-weight: 400;
			}

			/* ── LINK BACK ── */
			.back-link { 
				margin-top: 16px;
				font-size: 14px;
			}
			.back-link a { 
				color: var(--green-mid);
				text-decoration: none;
			}
			.back-link a:hover { text-decoration: underline; }
			/* ── RADIO FILTER ── */
			.filter-radio { display: none; }

			/* Chip cliccabile */
			label.stat-chip {
				cursor: pointer;
				transition: background 0.15s, border-color 0.15s, box-shadow 0.15s;
				user-select: none;
			}
			label.stat-chip:hover { 
				border-color: var(--text-light);
				background: var(--green-pale);
			}

			/* Stato attivo: chip selezionato — sfumatura colorata per ciascun tipo */
			#f-tutti:checked        ~ .resumo label[for="f-tutti"] {
				background: #333;
				border-color: #333;
				color: white;
				box-shadow: 0 2px 8px rgba(0,0,0,0.22);
			}
			#f-analizitaj:checked   ~ .resumo label[for="f-analizitaj"] {
				background: var(--green-mid);
				border-color: var(--green-mid);
				color: white;
				box-shadow: 0 2px 8px rgba(45,138,69,0.35);
			}
			#f-neanalizitaj:checked ~ .resumo label[for="f-neanalizitaj"] {
				background: var(--salmon);
				border-color: var(--salmon);
				color: white;
				box-shadow: 0 2px 8px rgba(232,112,96,0.35);
			}
			/* pallino e numero bianchi quando chip attivo */
			#f-tutti:checked        ~ .resumo label[for="f-tutti"] .dot,
			#f-analizitaj:checked   ~ .resumo label[for="f-analizitaj"] .dot,
			#f-neanalizitaj:checked ~ .resumo label[for="f-neanalizitaj"] .dot {
				background: rgba(255,255,255,0.7) !important;
			}
			#f-tutti:checked        ~ .resumo label[for="f-tutti"] b,
			#f-analizitaj:checked   ~ .resumo label[for="f-analizitaj"] b,
			#f-neanalizitaj:checked ~ .resumo label[for="f-neanalizitaj"] b { color: white; }

			/* Filtro contenuto — logica corretta:
			   f-analizitaj  = mostra solo validi  → nasconde .invalido
			   f-neanalizitaj = mostra solo invalidi → nasconde i validi (non .invalido) */
			#f-analizitaj:checked   ~ .content .risultato.invalido     { display: none; }
			#f-neanalizitaj:checked ~ .content .risultato:not(.invalido) { display: none; }

			/* Nascondiamo sempre la checkbox */
			.toggle-checkbox {
				display: none;
			}

			/* Di default (desktop), nascondiamo il label perché il testo è tutto visibile */
			.read-more {
				display: none;
			}

			@media (max-width: 720px) {
				/* Nascondiamo il testo extra inizialmente */
				.text-extra {
					display: none;
				}

				/* Mostriamo il "mostra tutto" come se fosse un link */
				.read-more {
					display: inline;
					color: #4a7fa0;
					text-decoration: underline;
					cursor: pointer;
					font-weight: bold;
					margin-left: 5px;
				}

				/* LOGICA: Quando la checkbox è selezionata... */
				.toggle-checkbox:checked ~ span .text-extra {
					display: inline; /* ...mostra il testo */
				}

				.toggle-checkbox:checked ~ span .read-more {
					display: none; /* ...e nascondi il pulsante */
				}
			}
		</style>
	</head>

	<body>
		<header>
			<div class="header-inner">
				<a class="header-home" href="vortanalizilo.php" title="Reen al la ekzemploj">
					<div class="logo-mark">hV</div>
					<div>
						<div class="site-name">hVortaro</div>
						<div class="page-title">Vortanalizilo</div>
					</div>
				</a>
				<div class="version-badge">v<?= $versio ?></div>
			</div>
		</header>

		<div class="container">

			<!-- Radio filter: fratelli di .resumo e .content -->
			<input type="radio" class="filter-radio" name="filtro" id="f-tutti" checked>
			<input type="radio" class="filter-radio" name="filtro" id="f-analizitaj">
			<input type="radio" class="filter-radio" name="filtro" id="f-neanalizitaj">

			<div class="input-panel">
				<form method="POST" action="" class="search-box">
					<textarea name="teksto" placeholder="Enigu tekston (ekz.: Malsanulejon)..." onkeydown="if(event.keyCode == 13 && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"><?php echo trim(htmlspecialchars($testo_da_analizzare)) ?></textarea>
					<button type="submit">Analizi</button>
				</form>

				<?php if (empty($_POST['teksto'])): ?>
					<div class="nota">
						<svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="10" cy="10" r="10" fill="#4a7fa0"/>
							<text x="10" y="15" text-anchor="middle" font-family="Georgia,serif" font-size="13" font-weight="bold" fill="white">i</text>
						</svg>
						
						<!-- La checkbox "motore" del sistema -->
						<input type="checkbox" id="toggle-text" class="toggle-checkbox">
						
						<span>
							Ĉi tiu vortanalizilo estas provo krei ilon por rekoni la diversajn partojn de vortoj en Esperanto; ĝi uzas parton de la "<a href="https://github.com/Indrikoterio/literumilo-python" target="_blank">literumilo</a>" kreita de Klivo Lendon,
							<!-- Parte da nascondere -->
							<span class="text-extra">sed ĝi ankoraŭ ne estas 100% preciza, do ĉiam kontrolu ĝiajn respondojn se ili ne konvinkas vin. Korajn dankojn al Andrea Vaccari, Carlo Minnaja kaj Norberto Saletti pro iliaj konsiloj kaj sugestoj.</span>
							<!-- Il finto "link" -->
							<label for="toggle-text" class="read-more">mostra tutto</label>
						</span>
					</div>
				<?php endif; ?>
			</div>

			<?php
					if (empty($testo_da_analizzare)) {
						$isEsempio = true;
						$testo_da_analizzare = 'ek fidi Bonvolu malplej belegaj ĉiulandanojn ŝian malreskribita ĉirkaŭdiri ĉimomente kongresaliĝilo krokodilo paperaro';
					} else {
						$isEsempio = false;
					}

					// 1. Generiamo i dati (backend logic)
					$analisi_array = literumilo_get_analysis_array($testo_da_analizzare);
					
					// 1b. Log delle ricerche (solo se input utente, non esempio)
					if (!$isEsempio) {
						$log_file = __DIR__ . '/vortanalizilo.log';
						$timestamp = date('Y-m-d H:i:s');
						foreach ($analisi_array as $item) {
							if (!empty($item['is_punctuation']) && $item['is_punctuation']) continue;
							$analizita = $item['valid'] ? 'jes' : 'ne';
							$vorto = $item['word'];
							$log_linio = implode("\t", [$timestamp, $versio, $vorto, $analizita]) . PHP_EOL;
							file_put_contents($log_file, $log_linio, FILE_APPEND | LOCK_EX);
						}
					}

					// 2. Calcoliamo il riassunto (escludendo la punteggiatura)
					$vortoj = 0;
					$analizitaj = 0;
					$ne_analizitaj = 0;
					foreach ($analisi_array as $item) {
						if (!empty($item['is_punctuation']) && $item['is_punctuation']) continue;
						$vortoj++;
						if ($item['valid']) $analizitaj++;
						else $ne_analizitaj++;
					}

					// Stats bar con chip (label per radio filter) — FUORI da .content
					echo '<div class="resumo">';
					echo '<label for="f-tutti"        class="stat-chip" title="Montri ĉiujn vortojn"><span class="dot dot-total"></span>Vortoj: <b>' . $vortoj . '</b></label>';
					echo '<label for="f-analizitaj"   class="stat-chip" title="Montri nur analizitajn vortojn"><span class="dot dot-ok"></span>Analizitaj: <b>' . $analizitaj . '</b></label>';
					echo '<label for="f-neanalizitaj" class="stat-chip" title="Montri nur ne-analizitajn vortojn"><span class="dot dot-fail"></span>Ne-analizitaj: <b>' . $ne_analizitaj . '</b></label>';
					echo '</div>';
					echo '<div class="content">'; // apre .content

					if ($isEsempio)
						echo '<div class="esempio-titolo">Ekzemploj</div>';
					
					// 3. Renderizziamo l'HTML (frontend logic in PHP)
					echo render_html($analisi_array);

					if (!$isEsempio) echo '<p class="back-link"><a href="vortanalizilo.php">← Montri ekzemplojn</a></p>';

					echo '</div>'; // chiude .content
				?>
			</div> <!-- chiude .container -->
	</body>
</html>
