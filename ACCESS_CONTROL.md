# 🔒 Contrôle d'Accès - Cloud Class

## 📋 Vue d'ensemble

Le système de contrôle d'accès empêche les étudiants d'accéder au panel d'administration Filament, tout en permettant aux administrateurs et autres rôles autorisés d'y accéder.

## 🛡️ Mécanisme de protection

### 1. Middleware de restriction
- **Fichier** : `app/Http/Middleware/RestrictStudentsFromAdmin.php`
- **Fonction** : Vérifie le rôle de l'utilisateur connecté
- **Action** : Bloque l'accès si le rôle est `etudiant`

### 2. Configuration Filament
- **Fichier** : `app/Providers/Filament/AdminPanelProvider.php`
- **Middleware ajouté** : `RestrictStudentsFromAdmin::class`
- **Ordre** : Exécuté après l'authentification

### 3. Page d'erreur personnalisée
- **Fichier** : `resources/views/errors/403-student.blade.php`
- **Design** : Interface utilisateur moderne avec Tailwind CSS
- **Actions** : Boutons pour retourner au dashboard ou se déconnecter

## 🎯 Rôles et permissions

| Rôle | Accès au panel Filament | Description |
|------|-------------------------|-------------|
| `etudiant` | ❌ BLOQUÉ | Les étudiants ne peuvent pas accéder à l'administration |
| `admin` | ✅ AUTORISÉ | Les administrateurs ont un accès complet |
| `enseignant` | ✅ AUTORISÉ | Les enseignants peuvent accéder au panel |
| `autre` | ✅ AUTORISÉ | Autres rôles autorisés par défaut |

## 🧪 Tests et validation

### Commande de test
```bash
# Tester tous les étudiants
php artisan test:student-access

# Tester un étudiant spécifique
php artisan test:student-access --student=jean.doe.2023.1@test.com
```

### Résultat attendu
- **36 étudiants de test** : Tous bloqués ❌
- **Utilisateurs autorisés** : Accès normal ✅

## 🔧 Configuration technique

### 1. Enregistrement du middleware
```php
// bootstrap/app.php
$middleware->alias([
    'restrict.students' => App\Http\Middleware\RestrictStudentsFromAdmin::class,
]);
```

### 2. Application dans Filament
```php
// app/Providers/Filament/AdminPanelProvider.php
->authMiddleware([
    Authenticate::class,
    \App\Http\Middleware\RestrictStudentsFromAdmin::class,
]);
```

### 3. Logique de restriction
```php
// app/Http/Middleware/RestrictStudentsFromAdmin.php
if ($user && $user->role === 'etudiant') {
    return response()->view('errors.403-student', [], 403);
}
```

## 🚨 Messages d'erreur

### Pour les étudiants
- **Code HTTP** : 403 Forbidden
- **Message** : "Accès non autorisé. Les étudiants ne peuvent pas accéder au panel d'administration."
- **Interface** : Page d'erreur personnalisée avec options de navigation

### Pour les développeurs
- **Logs** : Les tentatives d'accès sont enregistrées
- **Debug** : Utilisez `php artisan test:student-access` pour vérifier

## 🔄 Maintenance

### Ajouter un nouveau rôle bloqué
```php
// Dans RestrictStudentsFromAdmin.php
if ($user && in_array($user->role, ['etudiant', 'nouveau_role'])) {
    return response()->view('errors.403-student', [], 403);
}
```

### Modifier le message d'erreur
Éditez le fichier `resources/views/errors/403-student.blade.php`

### Désactiver temporairement la restriction
Commentez le middleware dans `AdminPanelProvider.php` :
```php
->authMiddleware([
    Authenticate::class,
    // \App\Http\Middleware\RestrictStudentsFromAdmin::class,
]);
```

## ✅ Vérification du bon fonctionnement

1. **Connectez-vous en tant qu'étudiant** : `jean.doe.2023.1@test.com` / `$helsinki`
2. **Tentez d'accéder à** : `/admin`
3. **Résultat attendu** : Page d'erreur 403 avec message d'interdiction
4. **Connectez-vous en tant qu'admin** : Accès normal au panel

## 📞 Support

En cas de problème :
1. Vérifiez les logs Laravel
2. Utilisez `php artisan test:student-access`
3. Vérifiez la configuration des middlewares
4. Consultez la documentation Filament
