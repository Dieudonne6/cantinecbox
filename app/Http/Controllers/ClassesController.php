<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Eleve;
use App\Models\Classes;
use App\Models\Contrat;
use App\Models\Paramcontrat;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Inscriptioncontrat;
use App\Models\Paiementglobalcontrat;
use App\Models\Paiementcontrat;
use App\Models\Moiscontrat;
use App\Models\Facturenormalise;
use GuzzleHttp\Client;
use Barryvdh\DomPDF\PDF;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
// use Endroid\QrCode\Response\QrCodeResponse;
// use Endroid\QrCode\Writer\PngWriter;
// use Endroid\QrCode\Encoding\Encoding;
// use Endroid\QrCode\writeFile\writeFile;
// use Endroid\QrCode\ErrorCorrectionLevel;
// use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\Writer\FileWriter;


class ClassesController extends Controller
{
    public function classe(Request $request){
        if(Session::has('account')){
            $eleves = Eleve::get();
            $classes = Classes::get();
            $fraiscontrat = Paramcontrat::first(); 
            Session::put('eleves', $eleves);
            Session::put('fraiscontrats', $fraiscontrat);
            return view('pages.classes')->with('eleve', $eleves)->with('classe', $classes)->with('fraiscontrats', $fraiscontrat);
        } 
        return redirect('/');
    }
    
    public function filterEleve($CODECLAS){
        $eleves = Eleve::get();
        $classes = Classes::get();
        $filterEleves = Eleve::where('CODECLAS', $CODECLAS)->get();
        Session::put('fill', $filterEleves);
        
        // dd($fill);
        return view('pages.filterEleve')->with("filterEleve", $filterEleves)->with('classe', $classes)->with('eleve', $eleves);
    }
    
    
    

    public function paiementcontrat(Request $request, $CODECLAS, $MATRICULE){

        // $fill = Session::get('fill');

        // dd($CODECLAS);

            

            // dd($moisCorrespondants);

         // Afficher les mois correspondants à chaque ID du tableau $difference
            // foreach ($moisCorrespondants as $id_moiscontrat => $nom_moiscontrat) {
            //     echo"<br> <br> <br> <br>";
            //     echo "ID Moiscontrat : $id_moiscontrat, Mois : $nom_moiscontrat <br>";
            // }


            // $allmoiscontratarray = $allmoiscontrat->toArray();
            // dd($allmoiscontratarray);


            // $moisContratArray = $inscriptioncontrats->toArray();
            // dd($moisContratArray);
    
                // $moiscontrat = $inscriptioncontrat->id_moiscontrat;
                // dd($inscriptioncontrat->id_moiscontrat) ;
                // dd($moiscontrat);


            // $moiscontrat = $inscriptioncontrat->id_moiscontrat;
            // dd($idcontrateleve);

            Session::put('matricule', $MATRICULE);

            $contrat = Contrat::where('eleve_contrat', $MATRICULE)->first();

            if ($contrat){

                // echo("oui L'eleve existe dans la table contrat !!!!!!!!!! ");
                $idcontrateleve = $contrat->id_contrat;
                Session::put('idcontratEleve', $idcontrateleve);


                // $inscriptioncontrats = Inscriptioncontrat::where('id_contrat', $idcontrateleve)->get(['id_moiscontrat']);
                $inscriptioncontrats = Inscriptioncontrat::where('id_contrat', $idcontrateleve)->pluck('id_moiscontrat')->toArray();
                // dd($inscriptioncontrats);
                // $allmoiscontrat = Moiscontrat::get();
                // $allmoiscontrat = Moiscontrat::get()->toArray();
                $allmoiscontrat = Moiscontrat::pluck('nom_moiscontrat', 'id_moiscontrat')->toArray();
                // dd($allmoiscontrat);

                $difference = array_diff(array_keys($allmoiscontrat), $inscriptioncontrats);

                // dd($difference);
                $moisCorrespondants = [];
                foreach ($difference as $id_moiscontrat) {
                    if (array_key_exists($id_moiscontrat, $allmoiscontrat)) {
                        $moisCorrespondants[$id_moiscontrat] = $allmoiscontrat[$id_moiscontrat];
                    }
                }




                if (($CODECLAS === "MAT1") || ($CODECLAS === "MAT2")  || ($CODECLAS === "MAT2II")  || ($CODECLAS === "MAT3")  || ($CODECLAS === "MAT3II")  || ($CODECLAS === "PREMATER")) {

                    // echo("oui l'eleve est de la maternelle");

                    $pramcontrat = Paramcontrat::first();
                    $fraismensuelle = $pramcontrat->fraisinscription2_paramcontrat;
                    // dd($fraismensuelle);

                    if($inscriptioncontrats){
                        // echo("le contrat existe dans la table inscriptioncontrat");
                        // dd($inscriptioncontrat);
                        // dd($moiscontrat);
                        
                        return view('pages.paiementcontrat')->with('fraismensuelle', $fraismensuelle)->with('moisCorrespondants', $moisCorrespondants);

                    }
                    else {
                        $moisCorrespondants = Moiscontrat::pluck('nom_moiscontrat', 'id_moiscontrat')->toArray();

                        // echo("le contrat n'existe pas dans la table inscriptioncontrat");
                        
                        return view('pages.paiementcontrat')->with('fraismensuelle', $fraismensuelle)->with('moisCorrespondants', $moisCorrespondants);

                    }


                }else{

                    // echo("non l'eleve n'est pas de la maternelle");

                    $pramcontrat = Paramcontrat::first();
                    $fraismensuelle = $pramcontrat->coutmensuel_paramcontrat;
                    // dd($fraismensuelle);

                    if($inscriptioncontrats){
                        // echo("le contrat existe dans la table inscriptioncontrat");
                        // dd($inscriptioncontrat);
                        // dd($moiscontrat);
                        
                        return view('pages.paiementcontrat')->with('fraismensuelle', $fraismensuelle)->with('moisCorrespondants', $moisCorrespondants);

                    }
                    else {
                        $moisCorrespondants = Moiscontrat::pluck('nom_moiscontrat', 'id_moiscontrat')->toArray();

                        return view('pages.paiementcontrat')->with('fraismensuelle', $fraismensuelle)->with('moisCorrespondants', $moisCorrespondants);

                    }

                    


                    // return view('pages.paiementcontrat')->with('fraismensuelle', $fraismensuelle);

                }
            } else {
                // return view('pages.inscriptioncontrat');
                return back()->with('status','Le contrat n\'existe pas.Veuillez créer un contrat pour l\'eleve');

            }


}


    
        




        
        public function create()
        {
            $qrCodeString = Session::get('qrCodeString');

      $qrCode = new QrCode($qrCodeString);
      $qrCode->setSize(300);
      $qrCode->setMargin(20); 
      $qrCode->setEncoding('UTF-8');
      $qrCode->setWriterByName('png');
      $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
      $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
      $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
      $qrCode->setLogoSize(200, 200);
      $qrCode->setValidateResult(false);  
      $qrCode->setRoundBlockSize(true);
      $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
      header('Content-Type: '.$qrCode->getContentType());
      $qrCode->writeFile('D:/PROJETS/CBOX/cantinecbox/public/qrcode');
      return redirect()->route('pdffacture');
        }


    public function telechargerfacture() {
        $decodedResponseConfirmation = Session::get('factureconfirm');
        $facturedetaille = Session::get('facturedetaille');
        $reffacture = Session::get('reffacture');
        $classeeleve = Session::get('classeeleve');
        $nomcompleteleve = Session::get('nomcompleteleve');
        $toutmoiscontrat = Session::get('toutmoiscontrat');
        $qrCodeString = Session::get('qrCodeString');
        // $reffacture = Session::get('reffacture');

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pages.facture', [
                'factureconfirm' => $decodedResponseConfirmation,
                'facturedetaille' => $facturedetaille, 
                'reffacture' => $reffacture,
                'classeeleve' => $classeeleve,
                'nomcompleteleve' => $nomcompleteleve,
                'toutmoiscontrat' => $toutmoiscontrat,
                'qrCodeString' => $qrCodeString,
            ]);        
            return $pdf->download('facture.pdf');
        // return view('Essai.mercccii');
    }

    public function facturenormalise() {
        $decodedResponseConfirmation = Session::get('factureconfirm');
        $facturedetaille = Session::get('facturedetaille');
        $reffacture = Session::get('reffacture');
        $classeeleve = Session::get('classeeleve');
        $nomcompleteleve = Session::get('nomcompleteleve');
        $toutmoiscontrat = Session::get('toutmoiscontrat');
        $qrCodeString = Session::get('qrCodeString');
        return view('pages.facturenormalise',  [
            'factureconfirm' => $decodedResponseConfirmation,
            'facturedetaille' => $facturedetaille, 
            'reffacture' => $reffacture,
            'classeeleve' => $classeeleve,
            'nomcompleteleve' => $nomcompleteleve,
            'toutmoiscontrat' => $toutmoiscontrat,
            'qrCodeString' => $qrCodeString,
        ]);        
    }


























































































































   

    // public function traiter(Request $request)
    // {
    //     // Récupérer l'ID de l'élève à partir de la requête
    //     $eleveNom = $request->input('eleveNom');
    //     dd($eleveNom);

    //     // Utiliser l'ID de l'élève pour effectuer des opérations dans votre contrôleur
    //     // Par exemple, charger les détails de l'élève à partir de la base de données
        
    //     // Retourner une réponse (si nécessaire)
    //     return response()->json(['message' => 'Informations de l\'élève traitées avec succès.']);
    // }
    // public function getElevesByClasse($CODECLAS) {
    //     $elevess = Eleve::where('CODECLAS', $CODECLAS)->get();
        
    //     // dd($elevess);// Récupérer les élèves de la classe sélectionnée en fonction de $codeClasse
    //     return view('pages.partial')->with('elevess', $elevess);
    //   }
 
    // public function creercontrat(Request $request){
    //     $matricules = $request->input('matricules');
    //     $existingContrat = Contrat::where('eleve_contrat', $matricules)->exists();
    //     if($existingContrat) {
    //         return back()->with('status', 'Un contrat existe déjà pour cet élève.');
    //     }
    //     $contra = new Contrat();
    //     $contra->eleve_contrat = $matricules;
    //     $contra->cout_contrat = $request->input('montant');

    //     $contra->datecreation_contrat = $request->input('date');
    //     $contra->save();
    
    //     return back()->with('status','Contrat enregistré avec succès');
    // }
    public function creercontrat(Request $request){
        $matricules = $request->input('matricules');
        foreach($matricules as $matricule) {
            $existingContrat = Contrat::where('eleve_contrat', $matricule)->exists();
            if($existingContrat) {
                return back()->with('status', 'Un contrat existe déjà pour l\'un des élèves sélectionnés.');
            }
        }
        
        foreach($matricules as $matricule) {
            $contra = new Contrat();
            $contra->eleve_contrat = $matricule;
            $contra->cout_contrat = $request->input('montant');
            $dateContrat = $request->input('date');
            if(empty($dateContrat)) {
                $dateContrat = date('Y-m-d'); 
            }
            $contra->datecreation_contrat = $dateContrat;
            $contra->save();
        }
        
        return back()->with('status','Contrats enregistrés avec succès');
    }
    
   


                
       
                
                public function pdffacture()
                {
                    return view('pages.pdffacture');
                }
                
    
                public function supprimercontrat($MATRICULE){

    // $existingContrat = Contrat::where('eleve_contrat', $matricules)->exists();

                    $contratss = Contrat::where('eleve_contrat', $MATRICULE)->first();
                    if($contratss){

                        $idcontrat = $contratss['id_contrat'];
                        // dd($idcontrat);
                        $contratss->delete();


                        // suppression du contrat de la table paiementcontrat
                        
                        $paiementcontrat = Paiementcontrat::where('id_contrat', $idcontrat)->get();
                        // dd($paiementcontrat);
                        // Vérifier si des enregistrements existent
                        if ($paiementcontrat->count() > 0) {
                            // Parcourir chaque modèle et appeler delete() sur chaque modèle
                            foreach ($paiementcontrat as $paiement) {
                                $paiement->delete();
                            }
                        }else{

                            return back()->with("status", "Le contrat n'existe pas,  veuiller d'abord le creer pour l'eleve");
                        }


                        // suppression du contrat de la table inscriptioncontrat

                        $inscriptioncontratspe = Inscriptioncontrat::where('id_contrat', $idcontrat)->get();
                        // dd($paiementcontrat);
                        // Vérifier si des enregistrements existent
                        if ($inscriptioncontratspe->count() > 0) {
                            // Parcourir chaque modèle et appeler delete() sur chaque modèle
                            foreach ($inscriptioncontratspe as $paiementinscri) {
                                $paiementinscri->delete();
                            }
                        }else{

                            return back()->with("status", "Le contrat n'existe pas,  veuiller d'abord le creer pour l'eleve");
                        }


                        // suppression du contrat de la table paiementglobalcontrat

                        $paiementglobal = Paiementglobalcontrat::where('id_contrat', $idcontrat)->get();
                        // dd($paiementcontrat);
                        // Vérifier si des enregistrements existent
                        if ($paiementglobal->count() > 0) {
                            // Parcourir chaque modèle et appeler delete() sur chaque modèle
                            foreach ($paiementglobal as $paiementglob) {
                                $paiementglob->delete();
                            }
                        }else{

                            return back()->with("status", "Le contrat n'existe pas,  veuiller d'abord le creer pour l'eleve");
                        }

                        // $paiementcontrat->delete();
                        return back()->with("status", "Le contrat a ete supprimer avec succes");

                    }else{
                        return back()->with("status", "Le contrat n'existe pas,  veuiller d'abord le creer pour l'eleve");

                    }
                
            }
                
  
                
                // public function traiter(Request $request)
                // {
                    //     // Récupérer l'ID de l'élève à partir de la requête
                    //     $eleveNom = $request->input('eleveNom');
                    //     dd($eleveNom);
                    
                    //     // Utiliser l'ID de l'élève pour effectuer des opérations dans votre contrôleur
                    //     // Par exemple, charger les détails de l'élève à partir de la base de données
                    
                    //     // Retourner une réponse (si nécessaire)
                    //     return response()->json(['message' => 'Informations de l\'élève traitées avec succès.']);
                    // }
                    // public function getElevesByClasse($CODECLAS) {
                        //     $elevess = Eleve::where('CODECLAS', $CODECLAS)->get();
                        
                        //     // dd($elevess);// Récupérer les élèves de la classe sélectionnée en fonction de $codeClasse
                        //     return view('pages.partial')->with('elevess', $elevess);
                        //   }
                        
                        // public function creercontrat(Request $request){
                            //     $matricules = $request->input('matricules');
                            //     $existingContrat = Contrat::where('eleve_contrat', $matricules)->exists();
                            //     if($existingContrat) {
                                //         return back()->with('status', 'Un contrat existe déjà pour cet élève.');
                                //     }
                                //     $contra = new Contrat();
                                //     $contra->eleve_contrat = $matricules;
                                //     $contra->cout_contrat = $request->input('montant');
                                
                                //     $contra->datecreation_contrat = $request->input('date');
                                //     $contra->save();
                                
                                //     return back()->with('status','Contrat enregistré avec succès');
                                // }
                     
                                
                                
                                
                            
}