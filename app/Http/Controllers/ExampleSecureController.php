<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HasPermissions;
use App\Helpers\PermissionHelper;

/**
 * Exemple de contrôleur sécurisé utilisant le système d'autorisation
 */
class ExampleSecureController extends Controller
{
    use HasPermissions;

    public function __construct()
    {
        // Appliquer des middlewares de permission sur des méthodes spécifiques
        $this->applyPermissionMiddleware([
            'index' => 'eleves.view',
            'create' => 'eleves.manage',
            'store' => 'eleves.manage',
            'edit' => 'eleves.manage',
            'update' => 'eleves.manage',
            'destroy' => 'eleves.manage'
        ]);
    }

    /**
     * Afficher la liste des élèves
     * Nécessite : eleves.view
     */
    public function index()
    {
        // Vérification supplémentaire (optionnelle, déjà faite par le middleware)
        $this->checkViewPermission('eleves');

        // Obtenir les permissions de l'utilisateur pour la vue
        $permissions = $this->getUserPermissionsForView([
            'eleves' => 'eleves',
            'classes' => 'typesclasses'
        ]);

        // Simuler des données d'élèves
        $eleves = collect([
            ['id' => 1, 'nom' => 'Dupont', 'prenom' => 'Jean'],
            ['id' => 2, 'nom' => 'Martin', 'prenom' => 'Marie'],
            ['id' => 3, 'nom' => 'Durand', 'prenom' => 'Pierre']
        ]);

        // Filtrer les données selon les permissions
        $filteredEleves = $this->filterDataByPermissions($eleves, 'eleves');

        return view('examples.secure-eleves-index', compact('eleves', 'permissions'));
    }

    /**
     * Afficher le formulaire de création
     * Nécessite : eleves.manage
     */
    public function create()
    {
        // Vérification automatique par le middleware
        return view('examples.secure-eleves-create');
    }

    /**
     * Enregistrer un nouvel élève
     * Nécessite : eleves.manage
     */
    public function store(Request $request)
    {
        // Vérification avec réponse JSON personnalisée
        $permissionCheck = $this->checkPermissionJson('eleves.manage', 'Vous ne pouvez pas créer d\'élèves');
        if ($permissionCheck) {
            return $permissionCheck;
        }

        // Logique de création...
        
        return response()->json([
            'success' => true,
            'message' => 'Élève créé avec succès'
        ]);
    }

    /**
     * Afficher un élève spécifique
     * Nécessite : eleves.view
     */
    public function show($id)
    {
        $this->checkAccessPermission('eleves');

        // Obtenir le niveau de permission pour ajuster l'affichage
        $permissionLevel = PermissionHelper::getPermissionLevel('eleves');

        $eleve = ['id' => $id, 'nom' => 'Exemple', 'prenom' => 'Élève'];

        return view('examples.secure-eleves-show', compact('eleve', 'permissionLevel'));
    }

    /**
     * Afficher le formulaire d'édition
     * Nécessite : eleves.manage
     */
    public function edit($id)
    {
        $this->checkManagePermission('eleves');

        $eleve = ['id' => $id, 'nom' => 'Exemple', 'prenom' => 'Élève'];

        return view('examples.secure-eleves-edit', compact('eleve'));
    }

    /**
     * Mettre à jour un élève
     * Nécessite : eleves.manage
     */
    public function update(Request $request, $id)
    {
        // Vérification avec gestion d'erreur personnalisée
        if (!PermissionHelper::canManage('eleves')) {
            if ($request->expectsJson()) {
                return $this->permissionDeniedResponse('Modification non autorisée');
            }
            abort(403, 'Vous ne pouvez pas modifier les élèves');
        }

        // Logique de mise à jour...

        return response()->json([
            'success' => true,
            'message' => 'Élève mis à jour avec succès'
        ]);
    }

    /**
     * Supprimer un élève
     * Nécessite : eleves.manage
     */
    public function destroy($id)
    {
        $this->checkManagePermission('eleves');

        // Vérification supplémentaire pour la suppression (peut nécessiter une permission spéciale)
        if (!PermissionHelper::hasPermission('eleves.delete')) {
            return $this->permissionDeniedResponse('Suppression non autorisée');
        }

        // Logique de suppression...

        return response()->json([
            'success' => true,
            'message' => 'Élève supprimé avec succès'
        ]);
    }

    /**
     * Exporter les données (action spéciale)
     * Nécessite : eleves.export ou eleves.manage
     */
    public function export()
    {
        // Vérification de permissions multiples
        if (!PermissionHelper::hasPermission('eleves.export') && !PermissionHelper::canManage('eleves')) {
            return $this->permissionDeniedResponse('Export non autorisé');
        }

        // Logique d'export...

        return response()->json([
            'success' => true,
            'message' => 'Export généré avec succès'
        ]);
    }

    /**
     * Méthode pour obtenir les statistiques (lecture seule acceptable)
     */
    public function stats()
    {
        // Accepter view ou manage
        $this->checkAccessPermission('eleves');

        $stats = [
            'total_eleves' => 150,
            'nouveaux_cette_annee' => 25,
            'par_classe' => [
                'CP' => 30,
                'CE1' => 28,
                'CE2' => 32
            ]
        ];

        // Masquer certaines données si l'utilisateur n'a que les droits de lecture
        if (PermissionHelper::canOnlyView('eleves')) {
            unset($stats['par_classe']); // Données sensibles
        }

        return response()->json($stats);
    }

    /**
     * Action d'administration (admin uniquement)
     */
    public function adminAction()
    {
        // Vérifier si l'utilisateur est admin
        if (!PermissionHelper::getUserInfo()['groupe'] === 'ADMINISTRATEUR') {
            return $this->permissionDeniedResponse('Action réservée aux ADMINISTRATEURs');
        }

        // Ou vérifier une permission spécifique d'admin
        $this->checkPermission('admin.system.manage');

        // Logique d'administration...

        return response()->json([
            'success' => true,
            'message' => 'Action d\'administration exécutée'
        ]);
    }
}
