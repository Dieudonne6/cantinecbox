<?php

namespace App\Http\Controllers;

use const RTF_UNICODE;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Params2;
use App\Models\PeriodeSave;
use Roundcube\Rtf\Html; 
use RtfHtmlPhp\Document;
use RtfHtmlPhp\Html\HtmlFormatter;
use RtfHtmlPhp\RtfDocument;
use PHPRtfLite\Rtf;



class ParametreController extends Controller
{
    public function inscriptionsDiscipline()
    {
        return view('parametre.inscriptions');
    }

    // public function tables()
    // {
    //     // On récupère le premier (ou unique) enregistrement de params2
    //     $settings = Params2::first();

    //     // On récupère la liste des attributs "fillable" (convertible en inputs)
    //     $fields = (new Params2)->getFillable();
          
    //     return view('pages.parametre.tables', compact('settings', 'fields'));
    // }


/**
 * Convertit un fragment HTML en RTF avec header "compatible" à ton existant.
 * Remplace ta précédente convertHtmlToRtf().
 */
private function convertHtmlToRtfKeepingHeader(string $html): string
{
    // Nettoyage minimal
    $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);
    $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

    // Forcer un wrapper pour garantir un body/div
    $dom = new \DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?><div>' . $html . '</div>');
    libxml_clear_errors();

    $container = $dom->getElementsByTagName('div')->item(0);
    $bodyRtf = $this->domNodeToRtf($container);

    // Nettoyage final : éviter \par doublés
    $bodyRtf = preg_replace('/(\\\\par\s*)+/', '\\par ', $bodyRtf);
    $bodyRtf = trim($bodyRtf);

    // Header RTF proche de l'existant
    $header = "{\\rtf1\\ansi\\ansicpg1252\\deff0\\nouicompat\\deflang1036";
    $header .= "{\\fonttbl{\\f0\\fswiss\\fprq2\\fcharset0 Verdana;}{\\f1\\fswiss\\fprq2\\fcharset0 Goudy Stout;}}";
    $header .= "{\\*\\generator Riched20 10.0.17763}\\viewkind4\\uc1\n";

    // Si le corps ne commence pas par \pard, forcer un paragraphe centré en f0 fs17
    if (!preg_match('/^\\\\pard/', $bodyRtf)) {
        $bodyRtf = '\\pard\\qc\\f0\\fs17 ' . $bodyRtf;
    }

    $rtf = $header . $bodyRtf . "\n}";

    return $rtf;
}


// private function writeDomNodeToRtf($node, $section, $parentFont = null, $parentPar = null)
// {
//     foreach ($node->childNodes as $child) {
//         if ($child->nodeType === XML_TEXT_NODE) {
//             $text = trim($child->nodeValue);
//             if ($text !== '') {
//                 $section->writeText($text, $parentFont, $parentPar);
//             }
//         } elseif ($child->nodeType === XML_ELEMENT_NODE) {
//             $tag = strtolower($child->nodeName);
//             $style = $this->parseStyle($child->getAttribute('style'));

//             // Hérite des styles parents
//             $fontOptions = $parentFont ? $parentFont->getAttributes() : [];
//             $fontSize = $parentFont ? $parentFont->getSize() : 12;
//             $fontColor = $parentFont ? $parentFont->getColor() : '#000000';

//             // Styles par balise
//             if (in_array($tag, ['b', 'strong']) || (isset($style['font-weight']) && $style['font-weight'] === 'bold')) {
//                 $fontOptions['bold'] = true;
//             }
//             if (in_array($tag, ['i', 'em']) || (isset($style['font-style']) && $style['font-style'] === 'italic')) {
//                 $fontOptions['italic'] = true;
//             }
//             if ($tag === 'u' || (isset($style['text-decoration']) && $style['text-decoration'] === 'underline')) {
//                 $fontOptions['underline'] = true;
//             }

//             // Font size
//             if (isset($style['font-size'])) {
//                 $fontSize = (int) filter_var($style['font-size'], FILTER_SANITIZE_NUMBER_INT);
//             }

//             // Font color
//             if (isset($style['color'])) {
//                 $fontColor = $style['color'];
//             }

//             // Création de la police courante
//             $font = new \PHPRtfLite_Font($fontSize, 'Arial', $fontColor, $fontOptions);

//             // Paragraphe courant
//             $paragraph = $parentPar ?? new \PHPRtfLite_ParFormat();
//             if (isset($style['text-align'])) {
//                 switch (trim($style['text-align'])) {
//                     case 'center': $paragraph->setTextAlignment('center'); break;
//                     case 'right':  $paragraph->setTextAlignment('right'); break;
//                     case 'justify':$paragraph->setTextAlignment('justify'); break;
//                     default:       $paragraph->setTextAlignment('left'); break;
//                 }
//             }

//             // Traitement des balises spécifiques
//             if ($tag === 'br') {
//                 $section->writeText("\n");
//             } elseif (in_array($tag, ['p', 'div'])) {
//                 $text = trim($child->textContent);
//                 if ($text !== '') {
//                     $section->writeText($text, $font, $paragraph);
//                     $section->writeText("\n");
//                 }
//             } elseif ($tag === 'span' || $tag === 'font') {
//                 $text = trim($child->textContent);
//                 if ($text !== '') {
//                     $section->writeText($text, $font, $paragraph);
//                 }
//             } else {
//                 // Récursion avec styles hérités
//                 $this->writeDomNodeToRtf($child, $section, $font, $paragraph);
//             }
//         }
//     }
// }


/**
 * Parcours récursif du DOM et production RTF.
 * REMARQUE: on n'insère \par que depuis les balises <p>/<div>/<br>.
 */
private function domNodeToRtf(\DOMNode $node, array $inheritedStyle = []): string
{
    $rtf = '';

    foreach ($node->childNodes as $child) {
        if ($child->nodeType === XML_TEXT_NODE) {
            $text = $child->nodeValue;
            // On échappe *uniquement* le texte utilisateur (pas les control-words)
            $rtf .= $this->rtfEscapeCp1252($text);
            continue;
        }

        if ($child->nodeType !== XML_ELEMENT_NODE) {
            continue;
        }

        $tag = strtolower($child->nodeName);
        // récupérer style inline et fusionner avec style hérités
        $style = $inheritedStyle;
        if ($child->hasAttribute('style')) {
            $style = array_merge($style, $this->parseInlineStyle($child->getAttribute('style')));
        }

        switch ($tag) {
            case 'br':
                $rtf .= "\\par ";
                break;

            case 'p':
            case 'div':
                $align = '\\pard';
                if (isset($style['text-align']) && trim($style['text-align']) === 'center') {
                    $align .= '\\qc';
                } else {
                    // tu peux changer si tu veux left par défaut
                    $align .= '\\qc';
                }

                $fCode = '\\f0';
                $fs = '\\fs17';
                if (isset($style['font-size'])) {
                    $fsN = $this->fontSizeToFs($style['font-size']);
                    if ($fsN) $fs = "\\fs{$fsN}";
                }
                if (isset($style['font-family'])) {
                    $ff = strtolower($style['font-family']);
                    if (stripos($ff, 'goudy') !== false) {
                        $fCode = '\\f1';
                    } else {
                        $fCode = '\\f0';
                    }
                }

                $inner = trim($this->domNodeToRtf($child, $style));
                if ($inner !== '') {
                    $rtf .= $align . $fCode . $fs . ' ' . $inner . "\\par ";
                }
                break;

            case 'b':
            case 'strong':
                $content = $this->domNodeToRtf($child, $style);
                $rtf .= "{\\b " . $content . "}";
                break;

            case 'i':
            case 'em':
                $content = $this->domNodeToRtf($child, $style);
                $rtf .= "{\\i " . $content . "}";
                break;

            case 'u':
                $content = $this->domNodeToRtf($child, $style);
                $rtf .= "{\\ul " . $content . "\\ulnone}";
                break;

            case 'span':
            case 'font':
                $prefix = '';
                $suffix = '';
                if (isset($style['font-family'])) {
                    if (stripos($style['font-family'], 'goudy') !== false) {
                        $prefix .= '\\f1';
                        $suffix = '\\f0' . $suffix;
                    } else {
                        $prefix .= '\\f0';
                    }
                }
                if (isset($style['font-size'])) {
                    $fsN = $this->fontSizeToFs($style['font-size']);
                    if ($fsN) {
                        $prefix .= "\\fs{$fsN}";
                        $suffix = "\\fs17" . $suffix;
                    }
                }
                $inner = $this->domNodeToRtf($child, $style);
                if ($prefix !== '') {
                    $rtf .= '{' . $prefix . ' ' . $inner . $suffix . '}';
                } else {
                    $rtf .= $inner;
                }
                break;

            default:
                $rtf .= $this->domNodeToRtf($child, $style);
                break;
        }
    }

    return $rtf;
}



/**
 * Convertit une valeur CSS font-size en valeur RTF \fsN (N = half-points).
 * Gère px et pt (ex: "24px", "12pt").
 * Retourne l'entier N ou null si non convertible.
 */
private function fontSizeToFs(string $cssSize): ?int
{
    $cssSize = trim($cssSize);
    if (strpos($cssSize, 'px') !== false) {
        $px = (int) filter_var($cssSize, FILTER_SANITIZE_NUMBER_INT);
        // approx: 1 px = 0.75 pt -> fsN = round(pt * 2) = round(px * 1.5)
        return (int) round($px * 1.5);
    }
    if (strpos($cssSize, 'pt') !== false) {
        $pt = (float) filter_var($cssSize, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        return (int) round($pt * 2);
    }
    // si nombre sans unité, supposer px
    if (is_numeric($cssSize)) {
        $px = (int) $cssSize;
        return (int) round($px * 1.5);
    }
    return null;
}

/**
 * Parse la chaîne style inline en tableau key => value
 * ex: "text-align:center; font-size:24px" => ['text-align'=>'center', 'font-size'=>'24px']
 */
private function parseInlineStyle(string $styleString): array
{
    $styles = [];
    if (!$styleString) return $styles;
    $parts = explode(';', $styleString);
    foreach ($parts as $p) {
        if (strpos($p, ':') !== false) {
            [$k, $v] = explode(':', $p, 2);
            $styles[trim(strtolower($k))] = trim($v);
        }
    }
    return $styles;
}

/**
 * Échappe le texte utilisateur en vue d'être inséré dans le RTF.
 * - SUPPRIME tous les retours chariot / LF et autres contrôles indésirables
 * - N'échappe PAS les backslashes (les control-words RTF restent intacts)
 * - Échappe accolades { } et convertit les octets non-ascii en \'hh
 */
private function rtfEscapeCp1252(string $text): string
{
    if ($text === '') return '';

    // 1) Supprimer TOUS les retours à la ligne et caractères de contrôle susceptibles de produire des séquences \'0d/\'0a.
    //    On supprime \r et \n explicitement puis tous les autres caractères de contrôle (sauf tab si tu veux le garder).
    $text = str_replace(["\r\n", "\r", "\n"], '', $text);
    // supprimer autres contrôle (0x00-0x1F sauf TAB(0x09) si tu veux le garder)
    $text = preg_replace('/[\x00-\x08\x0B-\x1F\x7F]/', '', $text);

    // 2) Échapper uniquement les accolades (elles sont significatives en RTF)
    $text = str_replace(['{', '}'], ['\\{', '\\}'], $text);

    // 3) Convertir en CP1252 (translittération si possible)
    $cp1252 = @iconv('UTF-8', 'CP1252//TRANSLIT', $text);
    if ($cp1252 === false) {
        $cp1252 = @iconv('UTF-8', 'CP1252', $text);
    }
    if ($cp1252 === false) {
        // fallback très conservateur si iconv échoue
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE);
    }

    // 4) Construire la sortie : garder ASCII imprimables, transformer tout le reste en \'hh
    $out = '';
    $len = strlen($cp1252);
    for ($i = 0; $i < $len; $i++) {
        $c = $cp1252[$i];
        $ord = ord($c);

        if ($ord >= 32 && $ord <= 126) {
            $out .= $c;
        } else {
            $out .= sprintf("\\'%02x", $ord);
        }
    }

    return $out;
}



// private function parseStyle($styleString)
// {
//     $styles = [];

//     if (!$styleString) return $styles;

//     $declarations = explode(';', $styleString);

//     foreach ($declarations as $decl) {
//         if (strpos($decl, ':') !== false) {
//             [$property, $value] = explode(':', $decl, 2);
//             $styles[trim($property)] = trim($value);
//         }
//     }

//     return $styles;
// }

private function parseDomToRtf($node)
{
    $rtf = '';

    foreach ($node->childNodes as $child) {
        switch ($child->nodeType) {
            case XML_TEXT_NODE:
                $rtf .= $child->nodeValue;
                break;

            case XML_ELEMENT_NODE:
                $content = $this->parseDomToRtf($child);

                switch (strtolower($child->nodeName)) {
                    case 'b':
                    case 'strong':
                        $rtf .= '{\\b ' . $content . '}';
                        break;

                    case 'i':
                    case 'em':
                        $rtf .= '{\\i ' . $content . '}';
                        break;

                    case 'u':
                        $rtf .= '{\\ul ' . $content . '}';
                        break;

                    case 'br':
                        $rtf .= "\\line ";
                        break;

                    case 'p':
                        $rtf .= $content . "\\line ";
                        break;

                    default:
                        $rtf .= $content;
                        break;
                }
                break;
        }
    }

    return $rtf;
}

/**
 * Supprime les 'd' parasites qui se trouvent juste après \par (ou au début)
 * et juste avant un contrôle RTF (ex: \qc, \f0, \fs17, ...).
 */
private function removeDanglingD(string $rtf): string
{
    // 1) Normaliser d'abord les backslashes doublés s'il y en a eu (sécurité)
    $rtf = str_replace('\\\\par', '\\par', $rtf);
    $rtf = str_replace('\\\\pard', '\\pard', $rtf);

    // 2) Supprime "d" entre "\par" et le contrôle suivant : "\par d\qc" -> "\par \qc"
    $rtf = preg_replace('/(\\\\par)\s*d(?=\\\\)/u', '$1 ', $rtf);

    // 3) Si un 'd' apparaît juste au début du corps (avant un contrôle), le supprimer :
    //    ex: "...\uc1 d\qc" ou le document commence par "d\qc..."
    $rtf = preg_replace('/(^|\\})\s*d(?=\\\\)/u', '$1', $rtf);

    // 4) Nettoyage final (enlever éventuellement des doubles espaces créés)
    $rtf = preg_replace('/[ ]{2,}/', ' ', $rtf);

    return $rtf;
}



public function updateMessages(Request $request)
{
    $request->validate([
        'message_fiche_notes'       => 'nullable|string',
        'message_des_recus'         => 'nullable|string',
        'entete_des_documents'      => 'nullable|string',
        'texte_fiche_engagement'    => 'nullable|string',
        'entete_bulletins'          => 'nullable|string',
    ]);

    $params = Params2::firstOrNew([]);

    if (!empty($request->message_fiche_notes)) {
        // $params->EnteteFiches = $this->convertHtmlToRtfKeepingHeader($request->message_fiche_notes);

        $generatedRtf = $this->convertHtmlToRtfKeepingHeader($request->message_fiche_notes);
        $params->EnteteFiches = $this->removeDanglingD($generatedRtf);
    }

    if (!empty($request->message_des_recus)) {
        // $params->EnteteRecu = $this->convertHtmlToRtfKeepingHeader($request->message_des_recus);

        $generatedRtf = $this->convertHtmlToRtfKeepingHeader($request->message_des_recus);
        $params->EnteteRecu = $this->removeDanglingD($generatedRtf);
    }

    if (!empty($request->entete_des_documents)) {
        // $params->EnteteDoc = $this->convertHtmlToRtfKeepingHeader($request->entete_des_documents);

        $generatedRtf = $this->convertHtmlToRtfKeepingHeader($request->entete_des_documents);
        $params->EnteteDoc = $this->removeDanglingD($generatedRtf);
    }

    if (!empty($request->texte_fiche_engagement)) {
        // $params->EnteteEngage = $this->convertHtmlToRtfKeepingHeader($request->texte_fiche_engagement);

        $generatedRtf = $this->convertHtmlToRtfKeepingHeader($request->texte_fiche_engagement);
        $params->EnteteEngage = $this->removeDanglingD($generatedRtf);
    }

    if (!empty($request->entete_bulletins)) {
        $generatedRtf = $this->convertHtmlToRtfKeepingHeader($request->entete_bulletins);
        $params->EnteteBull = $this->removeDanglingD($generatedRtf);
    }

    $params->save();

    return redirect()->back()->with('success', 'Les contenus ont été convertis en RTF et enregistrés.');
}
    
    public function tables()
    {
        $settings = Params2::first();

        function convertRTFToHTML($rtf)
        {
            try {
                $document = new Document($rtf);
                $formatter = new HtmlFormatter();
                return $formatter->Format($document);
            } catch (\Throwable $e) {
                return '';
            }
        }

        $entete = convertRTFToHTML($settings->EnteteBull ?? '');
        $enteteEngage = convertRTFToHTML($settings->EnteteEngage ?? '');
        $enteteDoc = convertRTFToHTML($settings->EnteteDoc ?? '');
        $enteteRecu = convertRTFToHTML($settings->EnteteRecu ?? '');
        $enteteFiches = convertRTFToHTML($settings->EnteteFiches ?? '');

        $fields = (new Params2)->getFillable();

        return view('pages.parametre.tables', compact('settings', 'fields', 'entete', 'enteteEngage', 'enteteDoc', 'enteteRecu', 'enteteFiches'));
    }

    public function tablesaessayer()
    {
            $settings = Params2::first();

            // convertit le RTF en HTML (ta librairie)
            $convertRTFToHTML = function($rtf) {
                try {
                    $document = new Document($rtf);
                    $formatter = new HtmlFormatter();
                    return $formatter->Format($document);
                } catch (\Throwable $e) {
                    return '';
                }
            };

            // helper : convertit \fsN -> px approx
            $fsToPx = function(int $fs): int {
                // fs is half-points: pt = fs/2 ; px ~= pt * 1.333
                $pt = $fs / 2;
                return (int) round($pt * 1.333);
            };

            // helper : parse fonttbl -> [index => fontName]
            $parseFontTbl = function(string $rtf): array {
                $map = [];
                if (preg_match_all('/\\{\\\\f(\\d+)[^}]*?([^;\\}]+);\\}/i', $rtf, $m, PREG_SET_ORDER)) {
                    foreach ($m as $row) {
                        $idx = (int)$row[1];
                        $name = trim($row[2]);
                        $map[$idx] = $name;
                    }
                }
                return $map;
            };

            // MAIN: injecte styles par paragraphe en se basant sur \par et \fs dans le RTF
            $applyRtfParagraphStylesToHtml = function(string $rtf, string $html) use ($fsToPx, $parseFontTbl) {
                if (trim($html) === '') return $html;

                // 1) extraire fonttbl
                $fontMap = $parseFontTbl($rtf);

                // 2) découper le RTF par paragraphe (split sur \par) et déterminer pour chaque
                //    paragraphe la dernière \fsN et \fN rencontrée (si présente)
                $parts = preg_split('/\\\\par\s*/u', $rtf);
                $paraStyles = [];
                foreach ($parts as $part) {
                    $style = ['fs' => null, 'f' => null];
                    // récupérer la dernière occurrence (si plusieurs \fs dans le part)
                    if (preg_match_all('/\\\\fs(\d+)/u', $part, $mfs)) {
                        $lastFs = end($mfs[1]);
                        $style['fs'] = (int)$lastFs;
                    }
                    if (preg_match_all('/\\\\f(\d+)/u', $part, $mf)) {
                        $lastF = end($mf[1]);
                        $style['f'] = (int)$lastF;
                    }
                    $paraStyles[] = $style;
                }

                // 3) Parser le HTML et appliquer styles sur <p> et >div de premier niveau
                libxml_use_internal_errors(true);
                $dom = new \DOMDocument();
                // encodage safe
                $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                libxml_clear_errors();

                // récupérer les nœuds block (p, div) dans l'ordre d'apparition
                $xpath = new \DOMXPath($dom);
                $nodes = [];
                // chercher p et div (directement n'importe où)
                foreach ($xpath->query('//p | //div') as $n) {
                    $nodes[] = $n;
                }

                // si aucun <p> trouvé, on peut appliquer au body childNodes séquentiellement
                if (count($nodes) === 0) {
                    // fallback : appliquer aux enfants du body
                    $body = $dom->getElementsByTagName('body')->item(0);
                    if ($body) {
                        foreach ($body->childNodes as $child) {
                            if ($child->nodeType === XML_ELEMENT_NODE) $nodes[] = $child;
                        }
                    }
                }

                // 4) Appliquer style pour chaque nœud, en prenant paraStyles séquentiels.
                $countParas = count($paraStyles);
                $i = 0;
                $lastKnown = ['fs' => null, 'f' => null];
                foreach ($nodes as $node) {
                    $style = $paraStyles[$i] ?? null;
                    if (!$style) {
                        // si pas de style correspondant, réutiliser dernier connu
                        $style = $lastKnown;
                    } else {
                        // conserver dernier connu si null
                        if ($style['fs'] === null) $style['fs'] = $lastKnown['fs'];
                        if ($style['f'] === null) $style['f'] = $lastKnown['f'];
                        $lastKnown = $style;
                    }

                    $inline = [];
                    if (!empty($style['fs'])) {
                        $px = $fsToPx($style['fs']);
                        $inline[] = "font-size: {$px}px";
                    }
                    if (!empty($style['f']) && isset($fontMap[$style['f']])) {
                        // safe font family
                        $fontName = $fontMap[$style['f']];
                        // ajouter fallback
                        $inline[] = "font-family: '" . addslashes($fontName) . "', Arial, sans-serif";
                    }

                    if (count($inline) > 0) {
                        // respecter un style existant
                        $old = $node->getAttribute('style');
                        $newStyle = trim( ($old ? rtrim($old, ';') . '; ' : '') . implode('; ', $inline) . ';' );
                        $node->setAttribute('style', $newStyle);
                    }

                    $i++;
                }

                // 5) retourner HTML (sans <html><body> wrapper ajoutée)
                $out = $dom->saveHTML();
                // enlever éventuels <html><body> wrapper
                // si loadHTML a conservé wrapper, on extrait innerHTML
                if ($dom->getElementsByTagName('body')->length) {
                    $body = $dom->getElementsByTagName('body')->item(0);
                    $inner = '';
                    foreach ($body->childNodes as $c) {
                        $inner .= $dom->saveHTML($c);
                    }
                    return $inner;
                }

                return $out;
            };

            // construire pour chaque champ
            $enteteRaw = $settings->EnteteBull ?? '';
            $enteteHtml = $convertRTFToHTML($enteteRaw);
            $entete = $applyRtfParagraphStylesToHtml($enteteRaw, $enteteHtml);

            $enteteEngageRaw = $settings->EnteteEngage ?? '';
            $enteteEngage = $applyRtfParagraphStylesToHtml($enteteEngageRaw, $convertRTFToHTML($enteteEngageRaw));

            $enteteDocRaw = $settings->EnteteDoc ?? '';
            $enteteDoc = $applyRtfParagraphStylesToHtml($enteteDocRaw, $convertRTFToHTML($enteteDocRaw));

            $enteteRecuRaw = $settings->EnteteRecu ?? '';
            $enteteRecu = $applyRtfParagraphStylesToHtml($enteteRecuRaw, $convertRTFToHTML($enteteRecuRaw));

            $enteteFichesRaw = $settings->EnteteFiches ?? '';
            $enteteFiches = $applyRtfParagraphStylesToHtml($enteteFichesRaw, $convertRTFToHTML($enteteFichesRaw));

            $fields = (new Params2)->getFillable();

            return view('pages.parametre.tables', compact('settings', 'fields', 'entete', 'enteteEngage', 'enteteDoc', 'enteteRecu', 'enteteFiches'));
    }



    public function showLogo($side)
    {
        $settings = DB::table('params2')->first();

        if (!$settings) {
            abort(404);
        }

        if ($side === 'left' && $settings->logoimage1) {
            $imageData = $settings->logoimage1;
        } elseif ($side === 'right' && $settings->LOGO1) {
            $imageData = $settings->LOGO1;
        } else {
            abort(404);
        }

        // Détection automatique du mime-type
        $finfo = finfo_open();
        $mimeType = finfo_buffer($finfo, $imageData, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        return response($imageData)->header('Content-Type', $mimeType);
    }



    public function updateIdentification(Request $request)
    {
        $request->validate([
            'nom_etablissement' => 'nullable|string|max:255',
            'code_etablissement' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            // Ajoute d'autres validations si besoin
        ]);

        // Préparation des données à enregistrer
        $data = [
            'NOMETAB' => $request->nom_etablissement,
            'CodeSITE' => $request->code_etablissement,
            'EMAIL' => $request->email,
            'DEPARTEMEN' => $request->departement,
            'Secteur' => $request->statut,
            'ADRESSE' => $request->adresse,
            'VILLE' => $request->ville,
            'NOMDIRECT' => $request->directeur1,
            'TITRE' => $request->titre_directeur1,
            'NOMDIRECT2' => $request->directeur2,
            'TITRE2' => $request->titre_directeur2,
            'NOMDIRECT3' => $request->directeur3,
            'TITRE3' => $request->titre_directeur3,
            'NOMCENSEUR' => $request->censeur,
            'TITRECENSEUR' => $request->titre_censeur,
            'NOMINTEND' => $request->filled('comptable') ? $request->comptable : '',
            'TITREINTENDANT' => $request->titre_comptable,
            'DeviseEtab' => $request->devise,
            'Typeetab' => is_array($request->Typeetab) ? implode('', $request->Typeetab) : '',
        ];

        // Gestion des fichiers logo
        if ($request->hasFile('logo_gauche')) {
            $data['logoimage1'] = file_get_contents($request->file('logo_gauche')->getRealPath());
        }

        if ($request->hasFile('logo_droit')) {
            // image enregistrée sur le disque
            $path = $request->file('logo_droit')->store('logos', 'public'); // storage/app/public/logos/...
            $filename = basename($path); // on ne garde que le nom pour respecter le VARCHAR(20)
            $data['LOGO1'] = substr($filename, 0, 20); // coupe à 20 caractères si nécessaire
        }

        // Mise à jour ou insertion (s'il n'existe pas encore)
        $exists = DB::table('params2')->exists();

        if ($exists) {
            DB::table('params2')->update($data); // mise à jour du seul enregistrement
        } else {
            DB::table('params2')->insert($data); // insertion si vide
        }

        return redirect()->back()->with('success', 'Les informations ont été enregistrées avec succès.');
    }

     public function editAppreciation()
    {
        $appreciations = Params2::first();
        return view('pages.parametre.appreciations', compact('appreciations'));
    }
    
    public function updateAppreciation(Request $request)
    {
        $appreciations = Params2::first();

        for ($i = 1; $i <= 8; $i++) {
            $appreciations->{'Borne' . $i} = $request->input('Borne' . $i);
            $appreciations->{'Mention' . $i . 'p'} = $request->input('Mention' . $i . 'p');
            $appreciations->{'Mention' . $i . 'd'} = $request->input('Mention' . $i . 'd');
        }

        $appreciations->save();

        return redirect()->back()->with('success', 'Grille des appréciations mise à jour avec succès.');
    }

    public function updateGeneraux(Request $request)
    {
        // 2. Chargement du modèle de settings (supposé unique)
        $settings = Params2::first(); // ou find(1) selon votre logique

        // 3. Mise à jour des composantes de scolarité
        $settings->MTS       = $request->input('montant.0');
        $settings->LIBELF1   = $request->input('libel.1');
        $settings->MT1       = $request->input('montant.1');
        $settings->LIBELF2   = $request->input('libel.2');
        $settings->MT2       = $request->input('montant.2');
        $settings->LIBELF3   = $request->input('libel.3');
        $settings->MT3       = $request->input('montant.3');
        $settings->LIBELF4   = $request->input('libel.4');
        $settings->MT4       = $request->input('montant.4');

        // 4. Échéancier standard
        $settings->Date1erPaie_Standard = $request->input('date1');
        $settings->Periodicite_Standard = $request->input('periodicite');
        $settings->Echeancier_tous_frais = $request->has('echeancier_frais');
        $settings->pcen1_standard = $request->input('t1');
        $settings->pcen2_standard = $request->input('t2');
        $settings->pcent3_standard = $request->input('t3');

        // 5. Type de matricule, périodicité, absences
        $settings->TYPEMATRI  = $request->input('type_matricule');
        $settings->TYPEAN = (int) $request->input('TYPEAN');
        $settings->NMININOTES = $request->input('NMININOTES');

        // 6. Pondérations (on stocke en fraction 0–1)
        $settings->Ponderation_MI   = $request->input('Ponderation_MI')  / 100;
        $settings->Ponderation_Dev  = $request->input('Ponderation_Dev') / 100;
        $settings->Ponderation_Comp = $request->input('Ponderation_Comp')/ 100;

        // 7. Ne pas toucher à ModeSalaire ici
        $settings->ModeSalaire = $request->input('ModeSalaire');
        $mode = $request->input('moyenne'); // 'classique' ou 'avance'
        if ($mode === 'classique') {
            // Seulement Pondération Composition
            $settings->Ponderation_Comp = $request->input('Ponderation_Comp') / 100;

            // Ne pas enregistrer les autres (mettre à null ou 0 si nécessaire)
            // $settings->Ponderation_MI = null;   
            // $settings->Ponderation_Dev = null;
        } elseif ($mode === 'avance') {
            // Prend toutes les pondérations
            $settings->Ponderation_MI  = $request->input('Ponderation_MI') / 100;
            $settings->Ponderation_Dev = $request->input('Ponderation_Dev') / 100;
            $settings->Ponderation_Comp = $request->input('Ponderation_Comp') / 100;
                    // dd($settings->Ponderation_MI, $settings->Ponderation_Dev, $settings->Ponderation_Comp);

        }
        // 8. Sauvegarde et retour
        $settings->save();

        return redirect()
            ->back()
            ->with('success', 'Les paramètres ont bien été mis à jour.');
    }

    public function updateNumerotation(Request $request)
    {
        // Valider
        $validated = $request->validate([
            'NUMDERATT'    => 'required|integer|min:0',
            'NUMDERCARTE'  => 'required|integer|min:0',
            'NUMDERCERTIF' => 'required|integer|min:0',
        ]);

        $params = Params2::firstOrFail();

        $params->NUMDERATT    = $validated['NUMDERATT'];
        $params->NUMDERCARTE  = $validated['NUMDERCARTE'];
        $params->NUMDERCERTIF = $validated['NUMDERCERTIF'];

        $params->save();

        return redirect()->back()
                         ->with('success', 'Numérotation mise à jour avec succès.');
    }



    public function bornes()
    {
        $exercices = DB::connection('mysql2')->table('exercice')->get();

        return view('pages.parametre.bornes-exercice', compact('exercices'));
    }

    public function opOuverture()
    {
        return view('pages.parametre.op-ouverture');
    }

    public function configImprimante()
    {
        return view('pages.parametre.config-imprimante');
    }

    public function changementTrimestre()
    {
        // récupère la période courante (ou null)
        $current = PeriodeSave::where('key', 'active')->value('periode');
        return view('pages.parametre.changement-trimestre', compact('current'));
    }

    public function storePeriode(Request $request)
    {
        $request->validate([
            'periode' => 'required|string|max:255',
            'action'  => 'nullable|string', // facultatif : "delete" si suppression
        ]);

        // si l'utilisateur a cliqué sur "Supprimé"
        if ($request->input('action') === 'delete') {
            PeriodeSave::where('key', 'active')->delete();
            return redirect()->back()->with('success', "La période a été supprimée.");
        }

        // enregistrement ou mise à jour (upsert)
        $periode = $request->input('periode');
        // <!-- dd($periode); -->

        $record = PeriodeSave::updateOrCreate(
            ['key' => 'active'],
            ['periode' => $periode]
        );

        return redirect()->back()->with('success', "Période enregistrée : {$record->periode}");
    }
    public function set(Request $request)
    {
        session(['periode' => $request->input('periode')]);
        return back()->with('success', 'Période définie !');
    } 
}





