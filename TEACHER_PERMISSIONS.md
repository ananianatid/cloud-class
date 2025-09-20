# 👨‍🏫 Permissions Enseignants - Cloud Class

## 📋 Vue d'ensemble

Le système de permissions pour les enseignants a été implémenté pour restreindre leur accès au panel Filament. Les enseignants ne peuvent voir que les fichiers et ne peuvent uploader que dans les matières qui leur sont assignées.

## 🔒 Restrictions appliquées

### 1. **Ressources masquées pour les enseignants**
- ❌ **Promotions** : Non visible dans la navigation
- ❌ **Semestres** : Non visible dans la navigation  
- ❌ **Matières** : Non visible dans la navigation
- ✅ **Fichiers** : Visible et accessible (filtré par matières assignées)

### 2. **Filtrage des fichiers**
- Les enseignants ne voient que les fichiers de leurs matières assignées
- Ils ne peuvent uploader que dans leurs matières
- Les filtres de promotion et semestre sont masqués

## 🛠️ Implémentation technique

### 1. **Middleware de restriction**
- **Fichier** : `app/Http/Middleware/RestrictTeacherAccess.php`
- **Fonction** : Stocke l'ID de l'enseignant dans la session
- **Application** : Exécuté sur toutes les requêtes Filament

### 2. **Trait de permissions**
- **Fichier** : `app/TeacherPermissions.php`
- **Fonctions** : Méthodes utilitaires pour gérer les permissions
- **Usage** : Utilisé dans les ressources Filament

### 3. **Modifications des ressources**

#### FichierResource
- **Filtrage** : `modifyQueryUsing()` pour limiter les fichiers
- **Formulaire** : Masquage des filtres promotion/semestre
- **Sélection** : Matières limitées aux assignations de l'enseignant

#### Autres ressources
- **PromotionResource** : `shouldRegisterNavigation()` = false pour enseignants
- **SemestreResource** : `shouldRegisterNavigation()` = false pour enseignants
- **MatiereResource** : `shouldRegisterNavigation()` = false pour enseignants

## 🧪 Tests et validation

### Commande de test
```bash
# Tester tous les enseignants
php artisan test:teacher-permissions

# Tester un enseignant spécifique
php artisan test:teacher-permissions --teacher=marie.dubois@cloudclass.edu
```

### Résultats attendus
- **Enseignants** : Voient seulement leurs matières assignées
- **Fichiers** : Filtrés par matières de l'enseignant
- **Navigation** : Seule la section "Fichiers" visible

## 📊 Assignations actuelles

### Dr. Marie Dubois
- **Email** : `marie.dubois@cloudclass.edu`
- **Matières** : 2 assignées
- **Spécialité** : Intelligence Artificielle

### Prof. Jean Martin
- **Email** : `jean.martin@cloudclass.edu`
- **Matières** : 1 assignée
- **Spécialité** : Génie Logiciel

### Dr. Fatou Ndiaye
- **Email** : `fatou.ndiaye@cloudclass.edu`
- **Matières** : 1 assignée
- **Spécialité** : Systèmes et Réseaux

### Prof. Ahmed Hassan
- **Email** : `ahmed.hassan@cloudclass.edu`
- **Matières** : 1 assignée
- **Spécialité** : Génie Civil

### Dr. Sophie Laurent
- **Email** : `sophie.laurent@cloudclass.edu`
- **Matières** : 0 assignée
- **Spécialité** : Communication Digitale

## 🔧 Gestion des assignations

### Assigner une matière à un enseignant
```php
// Dans un seeder ou via tinker
$matiere = \App\Models\Matiere::create([
    'unite_id' => $uniteId,
    'semestre_id' => $semestreId,
    'enseignant_id' => $enseignantId,
]);
```

### Vérifier les assignations
```bash
php artisan test:teacher-permissions --teacher=email@cloudclass.edu
```

## 🎯 Flux de travail pour les enseignants

1. **Connexion** : Utiliser email + mot de passe (`$helsinki`)
2. **Navigation** : Seule la section "Fichiers" visible
3. **Upload** : Sélectionner parmi leurs matières assignées
4. **Gestion** : Voir et modifier seulement leurs fichiers

## ⚠️ Notes importantes

- **Sécurité** : Les enseignants ne peuvent pas contourner les restrictions
- **Performance** : Filtrage au niveau de la base de données
- **Évolutivité** : Facile d'ajouter de nouvelles restrictions
- **Maintenance** : Utiliser les commandes de test pour vérifier

## 🔄 Maintenance

### Ajouter de nouvelles restrictions
1. Modifier le trait `TeacherPermissions`
2. Appliquer aux ressources concernées
3. Tester avec `php artisan test:teacher-permissions`

### Dépanner les problèmes
1. Vérifier les assignations de matières
2. Contrôler la session de l'enseignant
3. Examiner les logs Laravel
4. Utiliser les commandes de test

## 📞 Support

En cas de problème :
1. Vérifiez les assignations avec `php artisan test:teacher-permissions`
2. Contrôlez la configuration des middlewares
3. Examinez les logs d'erreur
4. Testez avec différents comptes enseignants
