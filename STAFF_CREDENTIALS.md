# 👥 Personnel Cloud Class - Identifiants de Connexion

## 🔐 Informations générales

Tous les membres du personnel (enseignants et administrateurs) ont été créés avec les caractéristiques suivantes :
- **Mot de passe** : `$helsinki` (identique pour tous)
- **Email** : Format `nom@cloudclass.edu`
- **Rôles** : `enseignant` ou `administrateur`

## 👨‍🏫 Enseignants (5)

### 1. Dr. Marie Dubois
- **Email** : `marie.dubois@cloudclass.edu`
- **Spécialité** : Intelligence Artificielle
- **Statut** : Permanent
- **Bio** : Docteure en Intelligence Artificielle avec 10 ans d'expérience dans l'enseignement et la recherche. Spécialisée en machine learning et deep learning.

### 2. Prof. Jean Martin
- **Email** : `jean.martin@cloudclass.edu`
- **Spécialité** : Génie Logiciel
- **Statut** : Permanent
- **Bio** : Professeur en Génie Logiciel, expert en développement d'applications web et mobile. 15 ans d'expérience dans l'industrie.

### 3. Dr. Fatou Ndiaye
- **Email** : `fatou.ndiaye@cloudclass.edu`
- **Spécialité** : Systèmes et Réseaux
- **Statut** : Vacataire
- **Bio** : Spécialiste en Systèmes et Réseaux, consultante en cybersécurité. Expertise en administration de serveurs et réseaux.

### 4. Prof. Ahmed Hassan
- **Email** : `ahmed.hassan@cloudclass.edu`
- **Spécialité** : Génie Civil
- **Statut** : Permanent
- **Bio** : Professeur en Génie Civil, expert en construction durable et gestion de projets. 20 ans d'expérience dans le domaine.

### 5. Dr. Sophie Laurent
- **Email** : `sophie.laurent@cloudclass.edu`
- **Spécialité** : Communication Digitale
- **Statut** : Permanent
- **Bio** : Docteure en Communication Digitale, experte en marketing digital et stratégies de communication. 12 ans d'expérience.

## 👨‍💼 Administrateurs (5)

### 1. Admin Principal
- **Email** : `admin@cloudclass.edu`
- **Poste** : Directeur Général
- **Bureau** : Bureau Principal

### 2. Admin Académique
- **Email** : `academic@cloudclass.edu`
- **Poste** : Directrice Académique
- **Bureau** : Bureau Académique

### 3. Admin Technique
- **Email** : `tech@cloudclass.edu`
- **Poste** : Administrateur Système
- **Bureau** : Bureau IT

### 4. Admin Financier
- **Email** : `finance@cloudclass.edu`
- **Poste** : Directrice Financière
- **Bureau** : Bureau Finances

### 5. Admin RH
- **Email** : `rh@cloudclass.edu`
- **Poste** : Directeur des Ressources Humaines
- **Bureau** : Bureau RH

## 📋 Commandes utiles

### Lister tout le personnel
```bash
php artisan staff:list
```

### Lister seulement les enseignants
```bash
php artisan staff:list --role=enseignant
```

### Lister seulement les administrateurs
```bash
php artisan staff:list --role=administrateur
```

### Regénérer le personnel
```bash
php artisan db:seed --class=EnseignantSeeder
```

## 🔒 Accès au panel Filament

- **Enseignants** : ✅ Accès autorisé
- **Administrateurs** : ✅ Accès autorisé
- **Étudiants** : ❌ Accès bloqué

## 🧪 Test de connexion

Pour tester la connexion d'un membre du personnel :
1. **Email** : Utilisez n'importe quel email de la liste ci-dessus
2. **Mot de passe** : `$helsinki`
3. **URL** : `/admin` (panel Filament)

## 📊 Statistiques

- **Total personnel** : 10 personnes
- **Enseignants** : 5 (4 permanents, 1 vacataire)
- **Administrateurs** : 5
- **Répartition par genre** : Équilibrée (M/F)

## ⚠️ Note importante

Ces comptes sont destinés aux tests et au développement. Le mot de passe `$helsinki` est volontairement simple pour faciliter les tests.
