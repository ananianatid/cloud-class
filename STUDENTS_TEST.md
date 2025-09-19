# 🎓 Étudiants de Test - Cloud Class

## 📋 Informations générales

Tous les étudiants de test ont été créés avec les caractéristiques suivantes :
- **Nom de famille** : Doe
- **Prénoms** : Commencent tous par la lettre "J"
- **Mot de passe** : `$helsinki` (identique pour tous)
- **Email** : Format `prenom.doe.annee.numero@test.com`
- **Matricule** : Format `ETUYYYYXXX`

## 🔐 Connexion

Pour vous connecter en tant qu'étudiant de test :
1. **Email** : Utilisez n'importe quel email de la liste ci-dessous
2. **Mot de passe** : `$helsinki`

## 📊 Commandes utiles

### Lister tous les étudiants de test
```bash
php artisan students:test-list
```

### Filtrer par promotion
```bash
php artisan students:test-list --promotion=GL
php artisan students:test-list --promotion=2023
php artisan students:test-list --promotion=IABD
```

### Regénérer les étudiants de test
```bash
php artisan db:seed --class=EtudiantSeeder
```

## 📁 Fichier de credentials

Un fichier `storage/app/student_credentials.txt` est généré automatiquement avec toutes les informations de connexion.

## 🎯 Exemples d'étudiants par filière

### Génie Logiciel (GL)
- **Jean Doe** - jean.doe.2023.1@test.com - ETU2023001
- **Justin Doe** - justin.doe.2024.8@test.com - ETU2024008
- **Jérémy Doe** - jérémy.doe.2025.9@test.com - ETU2025009

### Intelligence Artificielle et Big Data (IABD)
- **Jacques Doe** - jacques.doe.2023.2@test.com - ETU2023002
- **Julien Doe** - julien.doe.2024.3@test.com - ETU2024003
- **Jérôme Doe** - jérôme.doe.2025.4@test.com - ETU2025004

### Systèmes et Réseaux Informatiques (SR)
- **Jonathan Doe** - jonathan.doe.2023.5@test.com - ETU2023005
- **Jordan Doe** - jordan.doe.2024.6@test.com - ETU2024006
- **Jules Doe** - jules.doe.2025.7@test.com - ETU2025007

## 📈 Répartition par année

- **2023** : 12 étudiants (promotions de 2 ans)
- **2024** : 12 étudiants (promotions de 1 an)
- **2025** : 12 étudiants (promotions nouvelles)

## 🔧 Maintenance

Pour supprimer tous les étudiants de test :
```bash
php artisan tinker
>>> App\Models\User::where('email', 'like', '%@test.com')->delete();
>>> App\Models\Etudiant::whereHas('user', function($q) { $q->where('email', 'like', '%@test.com'); })->delete();
```

## ⚠️ Note importante

Ces étudiants sont uniquement destinés aux tests. Le mot de passe `$helsinki` est volontairement simple pour faciliter les tests de développement.
