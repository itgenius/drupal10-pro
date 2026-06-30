<?php

require_once '/var/www/html/vendor/autoload.php';
require_once '/var/www/html/web/core/includes/bootstrap.inc';

Drupal\Core\DrupalKernel::bootEnvironment();
$kernel = new Drupal\Core\DrupalKernel('prod', require '/var/www/html/vendor/autoload.php');
$kernel->boot();

$users = \Drupal::database()->query('SELECT uid, name FROM {users_field_data} WHERE uid > 0 ORDER BY uid')->fetchAll();
echo "=== Utilisateurs Drupal ===\n";
foreach ($users as $user) {
  echo "ID: " . $user->uid . ", Name: " . $user->name . "\n";
}

if (empty($users)) {
  echo "Aucun utilisateur trouvé. Création d'un administrateur...\n";
  
  $user = \Drupal\user\Entity\User::create();
  $user->setUsername('admin');
  $user->setEmail('admin@example.com');
  $user->setPassword('admin123');
  $user->activate();
  $user->addRole('administrator');
  $user->save();
  
  echo "✓ Utilisateur 'admin' créé avec le mot de passe 'admin123'\n";
}
