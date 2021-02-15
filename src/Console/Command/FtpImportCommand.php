<?php

// Uso del comando:
// php bin/console ftp:import T01   <-- Donde T01 es el archivo de la turbina de ayer a importar
// Tambien puede ser RegenA o RegenB . Recordar que las turbinas 1 y 9 son de prueba y no tienen datos
// Así como el RegenB que también es de pruebas

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use DateInterval;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FtpImportCommand extends Command
{

    private $params;

    // Creo un constructor y le paso la instancia de  ParameterBagInterface para tener acceso a los parametros del services.yml

    public function __construct(ParameterBagInterface $params)
    {
        parent::__construct();
        $this->params = $params;

    }

    protected function configure()
    {
        $this
            ->setName('ftp:import')
            ->setDescription('Importar CSVs de Turbinas y Regeneradores')
            ->addArgument(
                'turbina',
                InputArgument::OPTIONAL,
                '¿Que rurbina quieres importar?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $dsn_host = $this->params->get('mutriku_dsn_host');
        $dsn_username = $this->params->get('mutriku_dsn_username');
        $dsn_password = $this->params->get('mutriku_dsn_password');
        $resultado = '';
        $date = new DateTime();
        $date->add(DateInterval::createFromDateString('yesterday'));


        // Comprobar si es turbina, boya o regenerador
        if (strpos($input->getArgument('turbina'), "T") !== false) {

            $csvType = "turbines";
            // Comprobamos que exista el directorio
            if (!(is_dir("var/DATA/" . $csvType)))
                mkdir("var/DATA/" . $csvType);
            $current_file_name = $input->getArgument('turbina') . "_" . $date->format('Ymd') . "_S1.csv";

        } elseif (strpos($input->getArgument('turbina'), "RegenA") !== false) {

            $csvType = "regenerators";
            // Comprobamos que exista el directorio
            if (!(is_dir("var/DATA/" . $csvType)))
                mkdir("var/DATA/" . $csvType);
            $current_file_name = "RegenA_" . $date->format('Ymd') . "_S1.csv";

        } elseif (strpos($input->getArgument('turbina'), "RegenB") !== false) {

            $csvType = "regenerators";
            // Comprobamos que exista el directorio
            if (!(is_dir("var/DATA/" . $csvType)))
                mkdir("var/DATA/" . $csvType);
            $current_file_name = "RegenB_" . $date->format('Ymd') . "_S1.csv";

        } elseif (strpos($input->getArgument('turbina'), "Buoy") !== false) {

            $csvType = "buoys";
            // Comprobamos que exista el directorio
            if (!(is_dir("var/DATA/" . $csvType)))
                mkdir("var/DATA/" . $csvType);
            $current_file_name = $input->getArgument('turbina') . "_" . $date->format('Ymd') . "_S1.csv";

        } else {
            die("CSV file type not recognized.");
        }

        $folder = $input->getArgument('turbina');
        $destination_folder = "var/DATA/" . $csvType . "/";

        // Comprobamos que exista el directorio
        if (!(is_dir($destination_folder)))
            mkdir($destination_folder);

        // Conecto via FTP normal
        $conn_id = ftp_connect($dsn_host) or die("Couldn't connect to " . $dsn_host);

        // Hago el login via FTP normal
        $login_result = ftp_login($conn_id, $dsn_username, $dsn_password);
        if ((!$conn_id) || (!$login_result))
            die("FTP Connection Failed");

        // Me meto en el directorio solicitado
        if (ftp_chdir($conn_id, $folder) == false) {
            echo("Change Dir Failed: $folder<BR>\r\n");
            return;
        }

        // Me descargo el archivo
        if (ftp_get($conn_id, $destination_folder . "/" . $current_file_name, $current_file_name, FTP_BINARY)) {
            echo "Successfully written to " . $destination_folder . "/" . $current_file_name . "\n";
        } else {
            echo "There was a problem\n";
        }

        // Cerramos la conexion
        ftp_close($conn_id);



        $output->writeln($resultado);
        // Siempre debemos devolver 0 para que la consola no de error
        return 0;
    }
}