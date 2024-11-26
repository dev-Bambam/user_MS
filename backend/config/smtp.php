<?php

return [
    'host' => $_ENV['SMTP_HOST'],
    'port' => $_ENV['SMTP_PORT'],
    'username' => $_ENV['SMTP_USERNAME'],
    'password' => $_ENV['SMTP_PASSWORD'],
    'encryption' => $_ENV['SMTP_ENCRYPTION'],
    'from_email' => $_ENV['SMTP_FROM_EMAIL'],
    'from_name' => $_ENV['SMTP_FROM_NAME'],
];
