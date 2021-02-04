<?php

// application.php Importa via comando los archivos del FTP de mutriku
// Uso del comando:
// php ftp_import.php ftp:import T01   <-- Donde T01 es el directorio a importar
// php ftp_import.php ftp:import  <-- No poner nada para que lo importe todo

require __DIR__.'/vendor/autoload.php';

use App\Console\Command\FtpImportCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new FtpImportCommand());
$application->run();