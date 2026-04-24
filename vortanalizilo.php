<?php
	require_once 'literumilo\literumilo.php';

	$testo_da_analizzare = $_POST['parola'] ?? "";

	/**
	 * Trasforma la struttura dati (array/json) in output HTML.
	 * PHP puro, senza JavaScript.
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
				$output .= '<div class="risultato invalido">' . htmlspecialchars($item['word']) . '</div>';
				continue;
			}

			// Parola valida: creiamo il contenitore per le soluzioni
			$output .= '<div class="risultato">';
			$output .= '<p class="descrizione">' . $item['word'] . '</p>'; 
			foreach ($item['solutions'] as $sol) {
				$output .= '<div class="scomposizione">';
				foreach ($sol as $m) {
					$morpheme = htmlspecialchars($m['morpheme']);
					$type = htmlspecialchars($m['type']);
					$pos = mb_strtolower(htmlspecialchars($m['pos']));
					if ($type == 'radiko') {
						$output .= "<div class=\"morfemo {$type}\" title=\"{$type}\">{$morpheme}<div class=\"etikedo\">{$pos}</div></div>";
					} else {
						$output .= "<div class=\"morfemo {$type}\" title=\"{$pos}\">{$morpheme}<div class=\"etikedo\">{$type}</div></div>";
					}
				}
				$output .= '</div>';
			}
			$output .= '</div>';
		}
		return $output;
	}
?>

<!DOCTYPE html>
<html lang="eo">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Esperanta vortanalizilo 2.2</title>
		<style>
			body { font-family: sans-serif; line-height: 1.6; max-width: 800px; margin: 20px auto; padding: 0 15px; color: #333; }
			.container { background: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
			h1 { color: #2e7d32; }

			/* Stile del Form */
			.search-box { display: flex; gap: 10px; margin-bottom: 15px; }
			textarea { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; }
			button { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
			button:hover { background-color: #45a049; }

			/* Stile Risultati */
			.risultato { background: white; padding: 15px; border-left: 5px solid #4CAF50; margin-bottom: 10px; border-radius: 4px; }
			.invalido { border-left: 5px solid #dc2626; font-style: italic }
			.scomposizione:not(:last-child) { margin-bottom: 1em; }
			.esempio-titolo { margin-top: 30px; font-weight: bold; color: #666; border-bottom: 1px solid #ddd; }
			.footer-info { font-size: 0.8em; color: #777; margin-top: 10px; }

			.descrizione { font-weight:bold; margin-top: 0; margin-bottom: .2em; font-family:sans-serif; }
			.analizero { font-size:1.2em; font-weight:bold; display:block; margin-bottom: 2px; }

			/* Stile base delle tessere */
			.morfemo {
				display: inline-block;
				vertical-align: top;
				margin-right: .3em;
				margin-bottom: .3em;
				text-align: center;
				font-weight:bold;
				font-family: 'Segoe UI', Tahoma, sans-serif;
				border-width: 1px;
				border-style: solid;
				border-radius: 4px;
				padding: 4px 6px;
				box-shadow: 0 1px 2px rgba(0,0,0,0.05); /* Leggera profondità */
			}

			.morfemo.prefikso	{ background-color: #fef3c7; border-color: #f59e0b; color: #92400e; } /* Giallo miele */
			.morfemo.radiko		{ background-color: #dcfce7; border-color: #22c55e; color: #166534; } /* Verde salvia */
			.morfemo.sufikso	{ background-color: #dbeafe; border-color: #3b82f6; color: #1e40af; } /* Blu pastello */
			.morfemo.participo	{ background-color: #f3e8ff; border-color: #a855f7; color: #6b21a8; } /* Viola lavanda */
			.morfemo.pluralo	{ background-color: #fee2e2; border-color: #ef4444; color: #991b1b; } /* Rosso rosa */
			.morfemo.finaĵo		{ background-color: #fecaca; border-color: #dc2626; color: #7f1d1d; } /* Rosso profondo */
			.morfemo.disigilo	{ background-color: #cffafe; border-color: #06b6d4; color: #155e75; } /* Ciano-turchese */
			.morfemo.akuzativo	{ background-color: #ffedd5; border-color: #f97316; color: #9a3412; } /* Arancio tenue */
			.etikedo			{ display: block; font-size: 0.70em; opacity: 0.7; }
		</style>
	</head>

	<body>
		<div class="container">
			<h1>Esperanta vortanalizilo 2.2</h1>

			<form method="POST" action="" class="search-box">
				<textarea name="parola" placeholder="Enigu tekston (ekz.: Malsanulejon)..."><?php echo htmlspecialchars($testo_da_analizzare) ?></textarea>
				<button type="submit">Analizi</button>
			</form>

			<div class="content">
				<?php 
					if (!empty($testo_da_analizzare)) {
						// 1. Generiamo i dati (backend logic)
						$analisi_array = literumilo_get_analysis_array($testo_da_analizzare);

						// 2. Renderizziamo l'HTML (frontend logic in PHP)
						echo render_html($analisi_array);

						echo '<p><a href="">← Montri ekzemplojn</a></p>';
					} else {
						$testo_da_analizzare = 'ek fidi Bonvolu malplej belegaj ĉiulandanojn ŝian malreskribita ĉirkaŭdiri ĉimomente kongresaliĝilo krokodilo paperaro';

						echo '<div class="footer-info">
								Bonvolu atenti: ĉi vortanalizilo estas (serioza) provo krei ilon por rekoni la diversajn partojn de vortoj en Esperanto; 
								ĝi uzas la "literumilo" kodon kreita de Klivo Lendon, sed daŭre ne estas 100% preciza, do ĉiam kontrolu ĝiajn respondojn se ili ne konvinkas vin kaj, eble, sendu viajn 
								komentojn al la programisto, sed NE plendu: vivo estas tro mallonga por ĝin pasigi plendante...
							</div>';

						echo '<div class="esempio-titolo">Ekzemploj:</div>';

						// 1. Generiamo i dati (backend logic)
						$analisi_array = literumilo_get_analysis_array($testo_da_analizzare);

						// 2. Renderizziamo l'HTML (frontend logic in PHP)
						echo render_html($analisi_array);
					}
				?>
			</div>
		</div>
	</body>
</html>