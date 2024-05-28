<!-- public function savepaiementcontrat (Request $request) {

$idcontratEleve = Session::get('idcontratEleve');
$moisCoches = $request->input('moiscontrat');
$montanttotal = $request->input('montanttotal');
$datepaiementcontrat = $request->input('date');
$anneeActuelle = date('Y');

// generer une valeur aleatoire comprise entre 10000000 et 99999999 et verifier si elle existe deja dans la table.
// Si la valeur est déjà présente, exists() renverra true, et la boucle continuera à s'exécuter pour générer une nouvelle valeur.
// Si la valeur n'est pas présente (c'est-à-dire qu'elle est unique), la condition exists() renverra false, et la boucle s'arrêtera.

do {
     // Génère un nombre aléatoire entre 10000000 et 99999999
    $valeurDynamiqueNumerique = mt_rand(10000000, 99999999);
} while (DB::table('paiementglobalcontrat')->where('reference_paiementcontrat', $valeurDynamiqueNumerique)->exists());


// ENREGISTREMENT DANS LA TABLE INSCRIPTIONCONTRAT
// Parcourir les mois cochés et insérer chaque id de mois dans la table Inscriptioncontrat
foreach ($moisCoches as $id_moiscontrat) {
    Inscriptioncontrat::create([
        'id_contrat' => $idcontratEleve, 
        'id_moiscontrat' => $id_moiscontrat,
        'anne_inscrption' => $anneeActuelle
    ]);
}

// recuperer les nom des mois cochee

// Array des noms des mois
$nomsMoisCoches = [];

// Parcourir les ID des mois cochés et obtenir leur nom correspondant
foreach ($moisCoches as $id_moiscontrat) {
    // Ici, vous pouvez récupérer le nom du mois à partir de votre modèle Mois
    $mois = Moiscontrat::where('id_moiscontrat', $id_moiscontrat)->first();
    
    // Vérifiez si le mois existe
    if ($mois) {
        // Ajouter le nom du mois à l'array
        $nomsMoisCoches[] = $mois->nom_moiscontrat;
    }
}

// Convertir le tableau en une chaîne de caractères
$moisConcatenes = implode(',', $nomsMoisCoches);
// dd($moisConcatenes);
// Récupérer la somme des montants de paiement précédents
$soldeavant_paiementcontrat = DB::table('paiementglobalcontrat')
->where('id_contrat', $idcontratEleve)
->sum('montant_paiementcontrat');

// dd($soldeavant_paiementcontrat);
// Calculer le solde après le paiement en ajoutant le montant du paiement actuel à la somme des montants précédents
$soldeapres_paiementcontrat = $soldeavant_paiementcontrat + $montanttotal;
// dd($soldeapres_paiementcontrat);


// ENREGISTREMENT DANS LA TABLE PAIEMENTGLOBALCONTRAT
$paiementglobalcontrat =  new Paiementglobalcontrat();
    
$paiementglobalcontrat->soldeavant_paiementcontrat = $soldeavant_paiementcontrat;
$paiementglobalcontrat->montant_paiementcontrat = $montanttotal;
$paiementglobalcontrat->soldeapres_paiementcontrat = $soldeapres_paiementcontrat;
$paiementglobalcontrat->id_contrat = $idcontratEleve;
$paiementglobalcontrat->date_paiementcontrat = $datepaiementcontrat;
//     $paiementglobalcontrat->id_usercontrat = null;
$paiementglobalcontrat->anne_paiementcontrat = $anneeActuelle;
$paiementglobalcontrat->reference_paiementcontrat = $valeurDynamiqueNumerique;
$paiementglobalcontrat->statut_paiementcontrat = 1;
//     $paiementglobalcontrat->datesuppr_paiementcontrat = null;
//    $paiementglobalcontrat->idsuppr_usercontrat = null;
//    $paiementglobalcontrat->motifsuppr_paiementcontrat = null;
$paiementglobalcontrat->mois_paiementcontrat = $moisConcatenes;

$paiementglobalcontrat->save();

// Récupérer l'id_paiementcontrat de la table paiementglobalcontrat qui correspond a l'id du contrat
$idPaiementContrat = Paiementglobalcontrat::where('id_contrat', $idcontratEleve)
->orderBy('id_paiementcontrat', 'desc')
->value('id_paiementcontrat');
// dd($idPaiementContrat);                

// ENREGISTREMENT DANS LA TABLE PAIEMENTCONTRAT

// dd($soldeavant_paiementcontrat);

// Parcourir les mois cochés et insérer chaque id de mois dans la table Inscriptioncontrat
foreach ($moisCoches as $id_moiscontrat) {
    Paiementcontrat::create([
        // 'id_paiementcontrat ' => $valeurDynamiqueidpaiemnetcontrat, 
        'soldeavant_paiementcontrat' => $soldeavant_paiementcontrat,
        'montant_paiementcontrat' => $montanttotal,
        'soldeapres_paiementcontrat' => $soldeapres_paiementcontrat,
        'id_contrat' => $idcontratEleve,
        'date_paiementcontrat' => $datepaiementcontrat,
        // 'id_usercontrat' => $anneeActuelle,
        'mois_paiementcontrat' => $id_moiscontrat,
        'anne_paiementcontrat' => $anneeActuelle,
        'reference_paiementcontrat' => $valeurDynamiqueNumerique,
        'statut_paiementcontrat' => 1,
        // 'datesuppr_paiementcontrat' => $anneeActuelle,
        // 'idsuppr_usercontrat' => $anneeActuelle,
        // 'motifsuppr_paiementcontrat' => $anneeActuelle,
        'id_paiementglobalcontrat' => $idPaiementContrat,
    ]);
}





// echo('paiement effectuer avec succes');



// GESTION DE LA FACTURE NORMALISE

$matriculeeleve = Session::get('matricule');
$nomeleve = Eleve::where('MATRICULE', $matriculeeleve)->value('NOM');
$prenomeleve = Eleve::where('MATRICULE', $matriculeeleve)->value('PRENOM');
$classeeleve = Eleve::where('MATRICULE', $matriculeeleve)->value('CODECLAS');

$nomcompleteleve = $nomeleve .' '. $prenomeleve;


// dd($classeeleve);

$infocontrateleve = Paiementglobalcontrat::where('id_contrat', $idcontratEleve)
->orderBy('id_paiementcontrat', 'desc')->first();

$toutmoiscontrat = $infocontrateleve->mois_paiementcontrat;

// dd($infocontrateleve);



















// Données de la facture
// $invoiceRequestDto = [
//     "seller_id" => "ABCDEFGHIJKLMN",
//     "seller_name" => "ABCDEFGHIJK",
//     "payments" => [
//         [
//             "mode" => "V",
//             "amount" => 129.5
//         ]
//     ],
//     "products" => [
//         [
//             "label" => "ABCD",
//             "tax" => "B",
//             "price" => 807.75,
//             "bar_code" => "ABCDEFG",
//             "items" => 723.75,
//             // "specific_tax" => 18,
//             "specific_tax_desc" => "ABCDEFGHI",
//             // "original_price" => 80.75,
//             "price_change_explanation" => "ABCDEFGHIJKLMN"
//         ]
//     ],
//     "rt" => "FA",
//     "rn" => "facture",
//     "buyer_ifu" => "0202380068074",
//     "buyer_name" => "Jojo",
//     "aib" => "N/A"
// ];

// Conversion en JSON
// $jsonData = json_encode($invoiceRequestDto, JSON_UNESCAPED_UNICODE);

// URL de l'API
$apiUrl = 'http://localhost:38917/info';

// Configuration de la requête cURL
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, HTTP_GET, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_CAINFO, 'D:/certificationCA/cacert.pem');

// Exécution de la requête cURL
$response = curl_exec($ch);
dd($response);

// Vérification des erreurs de cURL

if (curl_errno($ch)) {
echo 'Erreur cURL : ' . curl_error($ch);
// Gestion des erreurs cURL
} else {
// Fermeture de la session cURL
curl_close($ch);

// Traitement de la réponse de l'API
$decodedResponse = json_decode($response, true);
dd($decodedResponse );
// Vérification du statut de la réponse
// if ($decodedResponse && isset($decodedResponse['status'])) {
//     // Traiter la réponse en fonction du statut retourné
//     if ($decodedResponse['status'] === 'DeviceNotConnected') {
//         // Quand l'équipement n'est pas connecté
//         http_response_code(503);
//         echo json_encode(["status" => "DeviceNotConnected"]);
//     } else {
//         // Quand tous s'est bien passé
//         http_response_code(200);
//         echo json_encode(["qr_code" => "F;ED04000623;NW34NID6ZHANFNMZ2IU7LL3H;3201910768821;20200503135002"]);
//     }
// } else {
//     // Erreur : réponse invalide
//     http_response_code(500);
//     echo json_encode(["error" => "InvalidResponse"]);
// }
}








// dd($invoiceRequestDto);

// $jsonData = json_encode($invoiceRequestDto, JSON_UNESCAPED_UNICODE);
// // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);


// // dd($jsonData);

// // Définissez l'URL de l'API de facturation
// $apiUrl = 'http://localhost:38917';

// // Définissez le jeton d'authentification
// $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjAyMDIzODAwNjgwNzR8VFMwMTAwNzgyMiIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTcxMzk2NDA3MSwiZXhwIjoxNzQ1NDQ5MjAwLCJpYXQiOjE3MTM5NjQwNzEsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.CuR4P9gaXP1T-I5vWuR0i_iXlRHSZhyu8Hry73GO5o8';

// Effectuez la requête POST à l'API
// // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// $ch = curl_init($apiUrl);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//     'Content-Type: application/json',
//     // 'Authorization: Bearer '
//     // 'Authorization:  host headers' 
// ));
// curl_setopt($ch, CURLOPT_CAINFO, 'D:/certificationCA/cacert.pem');

// Exécutez la requête cURL et récupérez la réponse
// $response = curl_exec($ch);
// dd($response);

// Vérifiez les erreurs de cURL
// if (curl_errno($ch)) {
//     echo 'Erreur cURL : ' . curl_error($ch);
//     return back()->with('erreur','Erreur curl , mauvaise connexion a l\'API');
// }

// // Fermez la session cURL
// curl_close($ch);

// // Affichez la réponse de l'API
// $decodedResponse = json_decode($response, true);
// // dd($decodedResponse);

// // Vérifiez si l'UID est présent dans la réponse
// if (isset($decodedResponse['uid'])) {
//     // L'UID de la demande
//     $uid = $decodedResponse['uid'];
//     dd($uid);
//     $taxb = 0.18;

//     // Affichez l'UID
//     // echo "L'UID de la demande est : $uid";

//     // ACTION pour la confirmation
//     $actionConfirmation = 'confirm';

//     // Définissez l'URL de l'API de confirmation de facture
//     $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;

//     // Configuration de la requête cURL pour la confirmation
//     $chConfirmation = curl_init($confirmationUrl);
//     curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
//     curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
//         'Authorization: Bearer ' . $token,
//         'Content-Length: 0'
//     ]);
//     curl_setopt($chConfirmation, CURLOPT_CAINFO, 'D:/certificationCA/cacert.pem');

//     // Exécutez la requête cURL pour la confirmation
//     $responseConfirmation = curl_exec($chConfirmation);

//     // Vérifiez les erreurs de cURL pour la confirmation
//     if (curl_errno($chConfirmation)) {
//         // echo 'Erreur cURL pour la confirmation : ' . curl_error($chConfirmation);/
//         return redirect('classes')->with('erreur','Erreur curl pour la confirmation');

//     }

//     // Fermez la session cURL pour la confirmation
//     curl_close($chConfirmation);

// // Convertissez la réponse JSON en tableau associatif PHP
// $decodedResponseConfirmation = json_decode($responseConfirmation, true);

// $facturedetaille = json_decode($jsonData, true);

// $reffacture = uniqid('f_');
// dd($decodedResponseConfirmation);
// dd($facturedetaille);

// Vérifiez si la conversion a réussi
// if ($decodedResponseConfirmation === null) {
//     // La conversion a échoué
//     // echo 'Erreur lors de la conversion JSON : ' . json_last_error_msg();
//     return back()->with('erreur','Erreur lors de la convertion json');

// } else {

//     // 'codemecef',
//     // 'commande_id',
//     // 'user_id',
//     // 'nom_utilisateur',
//     // 'montant_total',
//     // 'details',

//     $codemecef = $decodedResponseConfirmation['codeMECeFDGI'];

//     // dd($decodedResponseConfirmation);

//     // Générer le code QR
//     $qrCodeString = $decodedResponseConfirmation['qrCode'];





// $commandeId =  \App\Models\Commandes::find(id);
// $commande = \App\Models\Commandes::find($commandid);
// dd($codemecef);

// $facturenormalise = new Facturenormalise();
//     $facturenormalise->id = $reffacture;
//     $facturenormalise->codemecef = $codemecef;
//     $facturenormalise->MATRICULE = $matriculeeleve;
//     $facturenormalise->idcontrat = $idcontratEleve;
//     $facturenormalise->id_paiementglobalcontrat = $idPaiementContrat;
//     $facturenormalise->classe = $classeeleve;
//     $facturenormalise->nom = $nomcompleteleve;
//     $facturenormalise->montant_total = $infocontrateleve->montant_paiementcontrat;

// $facturenormalise->save();

// Session::put('factureconfirm', $decodedResponseConfirmation);
// Session::put('facturedetaille', $facturedetaille);
// Session::put('reffacture', $reffacture);
// Session::put('classeeleve', $classeeleve);
// Session::put('nomcompleteleve', $nomcompleteleve);
// Session::put('toutmoiscontrat', $toutmoiscontrat);
// Session::put('qrCodeString', $qrCodeString);


// return view('pages.pdffacture', [
//     'factureconfirm' => $decodedResponseConfirmation,
//     'facturedetaille' => $facturedetaille,
//     'reffacture' => $reffacture,
//     'classeeleve' => $classeeleve,
//     'nomcompleteleve' => $nomcompleteleve,
//     'toutmoiscontrat' => $toutmoiscontrat,
//     'qrCodeString' => $qrCodeString,
//     // 'qrCodeImage' => $qrCodeImage,

//          ]);



}
// } else {
//     // La réponse ne contient pas d'UID
//     // echo 'Erreur : Aucun UID trouvé dans la réponse de l\'API.';
//     return back()->with('erreur','Aucun iud trouver dans la reponse de l\'API');

// } -->