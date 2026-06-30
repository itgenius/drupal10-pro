<?php

/**
 * Local dev settings (Docker/Windows)
 */

$config['system.logging']['error_level'] = 'verbose';

// Temp paths inside container.
$settings['file_temp_path'] = '/tmp';

// IMPORTANT: Twig cache in /tmp (avoid Windows bind-mount permission issues).
$settings['php_storage']['twig'] = [
  'directory' => '/tmp/drupal-twig-cache',
];

// Trusted hosts.
$settings['trusted_host_patterns'] = [
  '^localhost$',
];

// Redis (dev). Keep if you enabled drupal/redis module.
$settings['cache']['default'] = 'cache.backend.redis';
$settings['redis.connection']['interface'] = 'PhpRedis';
$settings['redis.connection']['host'] = 'redis';
$settings['redis.connection']['port'] = 6379;

// Mailpit (SMTP) - if you later use Symfony Mailer / mailer DSN.
$settings['mailer_dsn'] = 'smtp://mailpit:1025';