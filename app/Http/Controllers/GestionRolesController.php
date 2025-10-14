<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Groupe;
use App\Models\GroupePermission;
use App\Models\Users;

class GestionRolesController extends Controller
{
    /**
     * Afficher la page de gestion des rôles et permissions
     */
    public function index()
    {
        // Charger les groupes existants
        $groupes = Groupe::with('permissions', 'users')->get();
        
        // Vérifier si le groupe ADMINISTRATEUR existe, sinon le créer SEULEMENT s'il n'y a aucun groupe
        if ($groupes->isEmpty()) {
            $this->ensureAdminGroupExists();
            $groupes = Groupe::with('permissions', 'users')->get();
        }
        
        // Génère la liste des fonctionnalités exactement comme dans la sidebar
        $fonctionnalites = $this->generatePermissionsFromSidebar();
        $utilisateurs = Users::all();
        
        // Charger les permissions existantes pour chaque groupe
        $existingPermissions = $this->loadExistingPermissions($groupes);
        

        return view('admin.gestion-roles', compact('groupes', 'fonctionnalites', 'utilisateurs', 'existingPermissions'));
    }

    /**
     * Détecter automatiquement les fonctionnalités de l'application
     * (conserve la signature existante mais redirige vers la génération basée sur la sidebar)
     */
    private function detecterFonctionnalites()
    {
        return $this->generatePermissionsFromSidebar();
    }

    /**
     * Vérifier si une route doit être ignorée
     */
    private function shouldIgnoreRoute($uri)
    {
        $ignoredPatterns = [
            'api/', '_debugbar', 'telescope', 'horizon', 
            'get-', 'filter', 'ajax', '{', 'sanctum'
        ];

        foreach ($ignoredPatterns as $pattern) {
            if (str_contains($uri, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Générer un nom de permission basé sur l'URI
     */
    private function generatePermissionName($uri)
    {
        $uri = trim($uri, '/');
        $parts = explode('/', $uri);
        
        if (count($parts) > 1) {
            return strtolower($parts[0]) . '.' . strtolower($parts[1]);
        }
        
        return strtolower($uri) . '.access';
    }

    /**
     * Génère la structure des menus, sous-menus et pages sur la base du sidebar
     * Les clés sont les grands modules (en-têtes) et chaque valeur est une
     * structure hiérarchique de libellés => URI ou sous-tableaux.
     */
    private function getSidebarStructure(): array
    {
        return [
            'Inscriptions & disciplines' => [
                'Accueil' => 'Acceuil',
                'Gestion des classes' => [
                    'Types classes' => 'typesclasses',
                    'Séries' => 'series',
                    'Promotions' => 'promotions',
                    'Gestion des classes' => 'tabledesclasses',
                    'Grouper' => 'groupes',
                ],
                'Recalculer effectifs' => 'recalculereffectifs',
                'Discipline' => 'discipline',
                'Éditions' => 'editions',
                'Archives' => 'archive',
                'Extraction' => [
                    'Exporter' => 'exporternote',
                    'Importer' => 'importernote',
                ],
            ],
            'Scolarité' => [
                'Créer profils' => 'creerprofil',
                'Paramétrage composantes' => 'paramcomposantes',
                'Factures classes' => 'facturesclasses',
                'Duplicata' => 'duplicatarecu',
                'Mise à jour des Paiements' => 'listefacturescolarite',
                'Éditions' => 'editionscolarite',
            ],
            'Cantine' => [
                'Accueil' => 'listecontrat',
                'Etats' => 'etat',
                'Mise à jour des Paiements/Inscriptions' => 'listefacture',
                'Liste des Factures d\'avoir' => 'listeFacturesAvoir',
                'Duplicata facture' => 'duplicatafacture',
            ],
            'Notes' => [
                'Paramètres' => [
                    'Répartition des classes' => 'repartitionclassesparoperateur',
                    'Table des matières' => 'tabledesmatieres',
                    'Table des coefficients' => 'gestioncoefficient',
                ],
                'Manipulation des notes' => [
                    'Saisir notes' => 'saisirnote',
                    'Enregistrer notes' => 'enregistrer_notes',
                ],
                'Sécurité' => [
                    'Verrouillage' => 'verrouillage',
                ],
                'Résultats' => [
                    'Bulletins de notes' => 'bulletindenotes',
                    'Tableau de notes' => 'tableaudenotes',
                    'Attestations de mérite' => 'attestationdemerite',
                ],
            ],
            'Ressources Humaines' => [
                'Régistre Enseignants' => [
                    'Type d\'agent' => 'addAgent',
                    'Mise à jour du Personnel' => 'updatePersonnel',
                ],
                'Configurer Taux Horaire' => 'confTauxH',
            ],
            'Ressources Matérielles' => [
                // Placeholder: aucun lien concret dans la sidebar actuelle
            ],
            'Paramètres' => [
                'Paramètres Cantine' => 'parametrecantine',
                'Identification' => 'params2.updateIdentification', // endpoints nommés
                'Logo Application' => 'settings.logo',
                'Appreciations' => 'appreciations.edit',
                'Bornes Exercice' => 'parametre.bornes',
                'Changement de Trimestre' => 'parametre.changementtrimestre',
                'Gestion des Rôles' => 'admin.roles.index',
            ],
            'États et Rapports' => [
                'État de la caisse' => 'etatdelacaisse',
                'Enquêtes statistiques' => 'enquetesstatistiques',
                'Situation financière globale' => 'situationfinanciereglobale',
                'État des recouvrements' => 'etatdesrecouvrements',
                'Tableau analytique' => 'tableauanalytique',
                'Tableau synoptique' => 'tableausynoptique',
                'Rapport annuel' => 'rapportannuel',
            ],
            'Budget et Finances' => [
                'Paramètres' => [
                    // Liens placeholders dans la sidebar -> traités comme dossiers
                    'Paramétrage' => null,
                ],
                'Exécution du Budget' => [
                    'Exécution' => null,
                    'Op Bancaire' => null,
                    'Valider le Brouillard' => null,
                ],
                'Prévision Budgétaire' => [
                    'Plan Comptable' => null,
                    'Mise en Place' => null,
                    'Décision Modificatives' => null,
                ],
                'Edition' => [
                    'Suivi des Opérations par Compte' => null,
                    'Éditions' => null,
                ],
                'Extraction' => [
                    'Exporter' => null,
                ],
                'Intégrité' => [
                    'Verouillage/Deverouillage' => null,
                    'Cloture de mois' => null,
                ],
            ],
        ];
    }

    /**
     * Transforme la structure du sidebar en une liste à plat d'éléments
     * (dossiers + pages) avec niveau d'indentation et métadonnées.
     * Retourne un tableau indexé par grand module comme attendu par la vue.
     */
    public function generatePermissionsFromSidebar(): array
    {
        $sidebar = $this->getSidebarStructure();
        $permissions = [];

        foreach ($sidebar as $module => $items) {
            $permissions[$module] = $this->processMenuItems($items, 0, '');
        }

        return $permissions;
    }

    /**
     * Parcours récursif des éléments de menu.
     * - Les dossiers (groupes) ont is_folder=true et pas d'URI.
     * - Les pages ont une URI, une permission et une description.
     */
    private function processMenuItems(array $items, int $level = 0, string $parentPath = ''): array
    {
        $result = [];

        foreach ($items as $label => $value) {
            $currentPath = trim(($parentPath ? $parentPath . ' > ' : '') . $label);

            // Null => pas de lien concret (considérer comme dossier)
            if (is_null($value)) {
                $permBase = strtolower(str_replace([' ', '>', '/'], ['.', '.', '.'], $currentPath));
                $result[] = [
                    'label' => $label,
                    'is_folder' => true,
                    'level' => $level,
                    'description' => $currentPath,
                    'permission_view' => 'access.' . $permBase . '.view',
                    'permission_manage' => 'access.' . $permBase . '.manage',
                ];
                continue;
            }

            if (is_array($value)) {
                // Dossier (sous-menu)
                $permBase = strtolower(str_replace([' ', '>', '/'], ['.', '.', '.'], $currentPath));
                $result[] = [
                    'label' => $label,
                    'is_folder' => true,
                    'level' => $level,
                    'description' => $currentPath,
                    'permission_view' => 'access.' . $permBase . '.view',
                    'permission_manage' => 'access.' . $permBase . '.manage',
                ];
                // Descendre d'un niveau
                $result = array_merge($result, $this->processMenuItems($value, $level + 1, $currentPath));
            } else {
                // Page
                $uri = $this->resolveUri($value);
                // Utiliser directement l'URI de la page comme base de permission
                $result[] = [
                    'label' => $label,
                    'is_folder' => false,
                    'level' => $level,
                    'uri' => $uri,
                    'permission_view' => $uri . '.view',
                    'permission_manage' => $uri . '.manage',
                    'description' => $currentPath,
                ];
            }
        }

        return $result;
    }

    /**
     * Résout une valeur de menu en URI lisible
     * - Si c'est un nom de route existant, renvoie l'URI de la route
     * - Sinon, considère que c'est déjà un chemin et le normalise
     */
    private function resolveUri(string $value): string
    {
        $value = trim($value);
        // Tenter de résoudre comme nom de route
        if (Route::has($value)) {
            $route = Route::getRoutes()->getByName($value);
            if ($route) {
                return ltrim($route->uri(), '/');
            }
        }
        // Sinon, considérer comme chemin brut
        return ltrim($value, '/');
    }

    /**
     * Mettre à jour les permissions d'un groupe
     */
    public function updatePermissions(Request $request)
    {
        try {
            $request->validate([
                'nomgroupe' => 'required|string',
                'permissions' => 'array'
            ]);

            $groupe = Groupe::where('nomgroupe', $request->nomgroupe)->first();
            if (!$groupe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Groupe non trouvé: ' . $request->nomgroupe
                ], 404);
            }

            // Supprimer toutes les permissions existantes pour ce groupe
            GroupePermission::where('nomgroupe', $groupe->nomgroupe)->delete();

            // Insérer les nouvelles permissions (déjà sous forme finale)
            $permissions = array_unique(array_filter(array_map('trim', $request->permissions ?? [])));

            foreach ($permissions as $permission) {
                GroupePermission::create([
                    'nomgroupe' => $groupe->nomgroupe,
                    'permissions' => $permission,
                    'module' => 'auto',
                    'description' => $permission
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Permissions mises à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouveau groupe/rôle
     */
    public function createGroupe(Request $request)
    {
        try {
            $request->validate([
                'nomgroupe' => 'required|string|max:100|unique:mysql2.groupe,nomgroupe'
            ]);

            $groupe = Groupe::create([
                'nomgroupe' => $request->nomgroupe
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Groupe créé avec succès',
                'groupe' => $groupe
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides : ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du groupe : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Modifier un groupe/rôle
     */
    public function updateGroupe(Request $request, $nomgroupe)
    {
        try {
            $request->validate([
                'nomgroupe' => 'required|string|max:100|unique:mysql2.groupe,nomgroupe,' . $nomgroupe . ',nomgroupe'
            ]);

            $groupe = Groupe::where('nomgroupe', $nomgroupe)->firstOrFail();
            
            // Empêcher la modification du groupe ADMINISTRATEUR
            if ($groupe->nomgroupe === 'ADMINISTRATEUR') {
                return response()->json([
                    'success' => false,
                    'message' => 'Le groupe ADMINISTRATEUR ne peut pas être modifié'
                ], 403);
            }

            $ancienNom = $groupe->nomgroupe;
            $groupe->nomgroupe = $request->nomgroupe;
            $groupe->save();

            // Mettre à jour les références dans les permissions
            GroupePermission::where('nomgroupe', $ancienNom)->update(['nomgroupe' => $request->nomgroupe]);
            
            // Mettre à jour les références dans les utilisateurs
            Users::where('nomgroupe', $ancienNom)->update(['nomgroupe' => $request->nomgroupe]);

            return response()->json([
                'success' => true,
                'message' => 'Groupe modifié avec succès',
                'groupe' => $groupe
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides : ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Groupe non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification du groupe : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un groupe/rôle
     */
    public function deleteGroupe($nomgroupe)
    {
        try {
            $groupe = Groupe::where('nomgroupe', $nomgroupe)->first();
            
            if (!$groupe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Groupe non trouvé'
                ], 404);
            }
            
            // Empêcher la suppression du groupe ADMINISTRATEUR
            if ($groupe->nomgroupe === 'ADMINISTRATEUR') {
                return response()->json([
                    'success' => false,
                    'message' => 'Le groupe ADMINISTRATEUR ne peut pas être supprimé'
                ], 403);
            }

            // Vérifier s'il y a des utilisateurs assignés à ce groupe
            $usersCount = Users::where('nomgroupe', $groupe->nomgroupe)->count();
            if ($usersCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Impossible de supprimer le groupe. {$usersCount} utilisateur(s) y sont encore assignés."
                ], 400);
            }

            // Supprimer les permissions associées
            GroupePermission::where('nomgroupe', $groupe->nomgroupe)->delete();
            
            // Supprimer le groupe
            $groupe->delete();
            

            return response()->json([
                'success' => true,
                'message' => 'Groupe supprimé avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Afficher les détails d'un groupe
     */
    public function showGroupe($nomgroupe)
    {
        $groupe = Groupe::where('nomgroupe', $nomgroupe)->firstOrFail();
        $users = Users::where('nomgroupe', $nomgroupe)->get();
        $permissions = GroupePermission::where('nomgroupe', $nomgroupe)->get();
        
        return response()->json([
            'success' => true,
            'groupe' => [
                'nomgroupe' => $groupe->nomgroupe,
                'users_count' => $users->count(),
                'permissions_count' => $permissions->count(),
                'users' => $users->map(function($user) {
                    return [
                        'nom' => $user->prenomuser . ' ' . $user->nomuser,
                        'login' => $user->login
                    ];
                })->toArray()
            ]
        ]);
    }

    /**
     * Obtenir les permissions d'un groupe (AJAX)
     */
    public function getGroupePermissions($groupeId)
    {
        // Si l'ID est 0, on retourne toutes les permissions de tous les groupes
        if ($groupeId == 0) {
            $result = [];
            $rows = GroupePermission::select('nomgroupe', 'permissions')->orderBy('nomgroupe')->get();
            foreach ($rows as $row) {
                $result[$row->nomgroupe] = $result[$row->nomgroupe] ?? [];
                if (!empty($row->permissions)) {
                    $result[$row->nomgroupe][] = $row->permissions;
                }
            }
            // Uniques et tri
            foreach ($result as $g => $perms) {
                $result[$g] = array_values(array_unique($perms));
            }
            return response()->json($result);
        }

        // Sinon, on retourne les permissions du groupe spécifié (par nom)
        $permissions = GroupePermission::where('nomgroupe', $groupeId)
            ->pluck('permissions')
            ->filter()
            ->values()
            ->all();

        return response()->json($permissions);
    }

    /**
     * Actualiser automatiquement les fonctionnalités détectées
     */
    public function refreshFonctionnalites()
    {
        $adminGroup = Groupe::where('nomgroupe', 'ADMINISTRATEUR')->first();
        if ($adminGroup) {
            $this->grantAllPermissionsToAdmin($adminGroup);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permissions ADMINISTRATEUR mises à jour avec toutes les fonctionnalités.'
        ]);
    }

     /**
     * Changer dynamiquement le groupe d'un utilisateur (AJAX)
     */
    public function changeUserGroupe(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:mysql2.users,id',
                'nomgroupe' => 'nullable|string|exists:mysql2.groupe,nomgroupe',
            ]);

            $user = Users::findOrFail($request->user_id);
            $user->nomgroupe = $request->nomgroupe; // peut être null
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Le groupe de l\'utilisateur a été mis à jour.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides : ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de groupe : ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Tester la sauvegarde des permissions (pour débogage)
     */
    public function testPermissionSave(Request $request)
    {
        $nomgroupe = $request->get('nomgroupe', 'TestGroupe');
        
        // Vérifier si le groupe existe
        $groupe = Groupe::where('nomgroupe', $nomgroupe)->first();
        $groupeExists = !is_null($groupe);
        
        // Créer le groupe de test s'il n'existe pas
        if (!$groupe) {
            $groupe = Groupe::create(['nomgroupe' => $nomgroupe]);
        }
        
        // Tester la sauvegarde
        $testPermissions = ['test.view', 'test.manage'];
        $permissionString = json_encode($testPermissions);
        
        try {
            // Supprimer les anciennes permissions
            GroupePermission::where('nomgroupe', $nomgroupe)->delete();
            
            // Créer une nouvelle permission
            $permission = GroupePermission::create([
                'nomgroupe' => $nomgroupe,
                'permissions' => $permissionString,
                'module' => 'Test',
                'description' => 'Test de sauvegarde'
            ]);
            
            $saveResult = !is_null($permission);
            
            // Tester la lecture
            $readPermission = GroupePermission::where('nomgroupe', $nomgroupe)->first();
            $readResult = !is_null($readPermission);
            
            // Décoder les permissions
            $permissionsDecoded = json_decode($readPermission->permissions ?? '[]', true);
            
            // Nettoyer (supprimer le groupe de test)
            if (!$groupeExists) {
                GroupePermission::where('nomgroupe', $nomgroupe)->delete();
                $groupe->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Test terminé avec succès',
                'data' => [
                    'groupe_exists' => $groupeExists,
                    'save_result' => $saveResult,
                    'read_result' => $readResult,
                    'permissions_decoded' => $permissionsDecoded
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * S'assurer que le groupe ADMINISTRATEUR existe avec tous les accès
     */
    private function ensureAdminGroupExists()
    {
        $adminGroup = Groupe::where('nomgroupe', 'ADMINISTRATEUR')->first();
        
        if (!$adminGroup) {
            // Créer le groupe ADMINISTRATEUR
            $adminGroup = Groupe::create([
                'nomgroupe' => 'ADMINISTRATEUR'
            ]);
            
            // Donner tous les accès à l'ADMINISTRATEUR
            $this->grantAllPermissionsToAdmin($adminGroup);
        }
        // NE PAS mettre à jour automatiquement les permissions existantes
        // Cela sera fait seulement lors d'une action explicite
    }
    
    /**
     * Donner toutes les permissions au groupe ADMINISTRATEUR
     */
    private function grantAllPermissionsToAdmin($adminGroup)
    {
        $fonctionnalites = $this->generatePermissionsFromSidebar();
        $allPermissions = [];

        foreach ($fonctionnalites as $module => $items) {
            foreach ($items as $item) {
                if (!empty($item['permission_view'])) {
                    $allPermissions[] = $item['permission_view'];
                }
                if (!empty($item['permission_manage'])) {
                    $allPermissions[] = $item['permission_manage'];
                }
            }
        }

        // Supprimer les anciennes
        GroupePermission::where('nomgroupe', $adminGroup->nomgroupe)->delete();

        // Insérer une ligne par permission
        foreach (array_unique($allPermissions) as $perm) {
            GroupePermission::create([
                'nomgroupe' => $adminGroup->nomgroupe,
                'permissions' => $perm,
                'module' => 'admin',
                'description' => $perm
            ]);
        }
    }
    
    /**
     * Mettre à jour les permissions de l'ADMINISTRATEUR
     */
    private function updateAdminPermissions($adminGroup)
    {
        // Supprimer les anciennes permissions
        GroupePermission::where('nomgroupe', $adminGroup->nomgroupe)->delete();
        
        // Créer les permissions ADMINISTRATEUR par défaut
        $adminPermissions = [
            ['permissions' => 'manage', 'module' => 'Utilisateurs', 'description' => 'Gérer les utilisateurs'],
            ['permissions' => 'view', 'module' => 'Utilisateurs', 'description' => 'Voir les utilisateurs'],
            ['permissions' => 'manage', 'module' => 'Rôles et Permissions', 'description' => 'Gérer les rôles'],
            ['permissions' => 'view', 'module' => 'Rôles et Permissions', 'description' => 'Voir les rôles'],
        ];
        
        foreach ($adminPermissions as $permission) {
            GroupePermission::create([
                'nomgroupe' => $adminGroup->nomgroupe,
                'permissions' => $permission['permissions'],
                'module' => $permission['module'],
                'description' => $permission['description']
            ]);
        }
    }
    
    /**
     * Charger les permissions existantes pour chaque groupe
     */
    private function loadExistingPermissions($groupes)
    {
        $existingPermissions = [];

        foreach ($groupes as $groupe) {
            // Récupérer toutes les permissions brutes pour ce groupe
            $permissions = GroupePermission::where('nomgroupe', $groupe->nomgroupe)
                ->pluck('permissions') // <-- on prend juste la colonne `permissions`
                ->filter() // enlève les null/vide
                ->values()
                ->all();

            $existingPermissions[$groupe->nomgroupe] = $permissions;
        }

        return $existingPermissions;
    }
    
    /**
     * Obtenir le nom d'affichage d'une page à partir de son nom technique
     */
    private function getPageDisplayName($pageName)
    {
        // Mapping des noms techniques vers les noms d'affichage
        $pageMapping = [
            // Inscriptions & disciplines
            'Acceuil' => 'Accueil',
            'typesclasses' => 'Types classes',
            'series' => 'Séries',
            'promotions' => 'Promotions',
            'tabledesclasses' => 'Gestion des classes',
            'groupes' => 'Grouper',
            'recalculereffectifs' => 'Recalculer effectifs',
            'discipline' => 'Discipline',
            'editions' => 'Éditions',
            'archive' => 'Archives',
            'exporternote' => 'Exporter',
            'importernote' => 'Importer',
            
            // Scolarité
            'creerprofil' => 'Créer profils',
            'paramcomposantes' => 'Paramétrage composantes',
            'facturesclasses' => 'Factures classes',
            'duplicatarecu' => 'Duplicata',
            'listefacturescolarite' => 'Mise à jour des Paiements',
            'editionscolarite' => 'Éditions',
            
            // Cantine
            'listecontrat' => 'Accueil Cantine',
            'etat' => 'Etats',
            'listefacture' => 'Mise à jour des Paiements/Inscriptions',
            'listeFacturesAvoir' => 'Liste des Factures d\'avoir',
            'duplicatafacture' => 'Duplicata facture',
            
            // Notes
            'repartitionclassesparoperateur' => 'Répartition des classes',
            'tabledesmatieres' => 'Table des matières',
            'gestioncoefficient' => 'Table des coefficients',
            'saisirnote' => 'Saisir notes',
            'enregistrer_notes' => 'Enregistrer notes',
            'verrouillage' => 'Verrouillage',
            'bulletindenotes' => 'Bulletins de notes',
            'tableaudenotes' => 'Tableau de notes',
            'attestationdemerite' => 'Attestations de mérite',
            
            // Ressources Humaines
            'addAgent' => 'Type d\'agent',
            'updatePersonnel' => 'Mise à jour du Personnel',
            'confTauxH' => 'Configurer Taux Horaire',
            
            // Paramètres
            'parametrecantine' => 'Paramètres Cantine',
            'params2.updateIdentification' => 'Identification',
            'settings.logo' => 'Logo Application',
            'appreciations.edit' => 'Appreciations',
            'parametre.bornes' => 'Bornes Exercice',
            'parametre.changementtrimestre' => 'Changement de Trimestre',
            'admin.roles.index' => 'Gestion des Rôles',
            
            // États et Rapports
            'etatdelacaisse' => 'État de la caisse',
            'enquetesstatistiques' => 'Enquêtes statistiques',
            'situationfinanciereglobale' => 'Situation financière globale',
            'etatdesrecouvrements' => 'État des recouvrements',
            'tableauanalytique' => 'Tableau analytique',
            'tableausynoptique' => 'Tableau synoptique',
            'rapportannuel' => 'Rapport annuel'
        ];
        
        return $pageMapping[$pageName] ?? ucfirst($pageName);
    }
    
    /**
     * Obtenir la description d'une page
     */
    private function getPageDescription($pageName, $type)
    {
        $displayName = $this->getPageDisplayName($pageName);
        
        if ($type === 'manage') {
            return "Accès complet à {$displayName}";
        } else {
            return "Accès en lecture à {$displayName}";
        }
    }
    
    /**
     * Obtenir la description d'un dossier
     */
    private function getFolderDescription($folderPath, $type)
    {
        if ($type === 'manage') {
            return "Accès complet au dossier {$folderPath}";
        } else {
            return "Accès en lecture au dossier {$folderPath}";
        }
    }
    
    /**
     * Obtenir le nom technique d'une page à partir de son nom d'affichage
     */
    private function getPageNameFromDisplayName($displayName)
    {
        // Mapping inverse : nom d'affichage vers nom technique
        $reverseMapping = [
            // Inscriptions & disciplines
            'Accueil' => 'Acceuil',
            'Types classes' => 'typesclasses',
            'Séries' => 'series',
            'Promotions' => 'promotions',
            'Gestion des classes' => 'tabledesclasses',
            'Grouper' => 'groupes',
            'Recalculer effectifs' => 'recalculereffectifs',
            'Discipline' => 'discipline',
            'Éditions' => 'editions',
            'Archives' => 'archive',
            'Exporter' => 'exporternote',
            'Importer' => 'importernote',
            
            // Scolarité
            'Créer profils' => 'creerprofil',
            'Paramétrage composantes' => 'paramcomposantes',
            'Factures classes' => 'facturesclasses',
            'Duplicata' => 'duplicatarecu',
            'Mise à jour des Paiements' => 'listefacturescolarite',
            'Éditions' => 'editionscolarite',
            
            // Cantine
            'Accueil Cantine' => 'listecontrat',
            'Etats' => 'etat',
            'Mise à jour des Paiements/Inscriptions' => 'listefacture',
            'Liste des Factures d\'avoir' => 'listeFacturesAvoir',
            'Duplicata facture' => 'duplicatafacture',
            
            // Notes
            'Répartition des classes' => 'repartitionclassesparoperateur',
            'Table des matières' => 'tabledesmatieres',
            'Table des coefficients' => 'gestioncoefficient',
            'Saisir notes' => 'saisirnote',
            'Enregistrer notes' => 'enregistrer_notes',
            'Verrouillage' => 'verrouillage',
            'Bulletins de notes' => 'bulletindenotes',
            'Tableau de notes' => 'tableaudenotes',
            'Attestations de mérite' => 'attestationdemerite',
            
            // Ressources Humaines
            'Type d\'agent' => 'addAgent',
            'Mise à jour du Personnel' => 'updatePersonnel',
            'Configurer Taux Horaire' => 'confTauxH',
            
            // Paramètres
            'Paramètres Cantine' => 'parametrecantine',
            'Identification' => 'params2.updateIdentification',
            'Logo Application' => 'settings.logo',
            'Appreciations' => 'appreciations.edit',
            'Bornes Exercice' => 'parametre.bornes',
            'Changement de Trimestre' => 'parametre.changementtrimestre',
            'Gestion des Rôles' => 'admin.roles.index',
            
            // États et Rapports
            'État de la caisse' => 'etatdelacaisse',
            'Enquêtes statistiques' => 'enquetesstatistiques',
            'Situation financière globale' => 'situationfinanciereglobale',
            'État des recouvrements' => 'etatdesrecouvrements',
            'Tableau analytique' => 'tableauanalytique',
            'Tableau synoptique' => 'tableausynoptique',
            'Rapport annuel' => 'rapportannuel'
        ];
        
        return $reverseMapping[$displayName] ?? null;
    }
}
