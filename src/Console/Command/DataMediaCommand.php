<?php

// Uso del comando:
// php bin/console data:media T01   <-- Donde T01 es el archivo de la turbina de ayer a importar
// Tambien puede ser RegenA o RegenB . Recordar que las turbinas 1 y 9 son de prueba y no tienen datos
// Así como el RegenB que también es de pruebas

namespace App\Console\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Turbines;
use App\Entity\TurbinesDatas;
use App\Entity\TurbinesMedias;
use DateTime;
use DateInterval;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DataMediaCommand extends Command
{

    private $params;

    // Creo un constructor y le paso la instancia de  ParameterBagInterface para tener acceso a los parametros del services.yml

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->params = $params;


    }

    protected function configure()
    {
        $this
            ->setName('data:media')
            ->setDescription('Generar valores medios, maximos de ciertos valores')
            ->addArgument(
                'turbina',
                InputArgument::OPTIONAL,
                '¿Que rurbina deseas calcular?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $Turbine = $input->getArgument('turbina');
        $turbineNumber = str_replace("T", "", $Turbine);
        $turbineNumber = intval($turbineNumber);

        echo "Turbine Numer: ".$turbineNumber;

        $resultado = "";

        // Obtener el doctrine manager
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);


        $turbines = $this->em->getRepository(Turbines::class)->findBy(array('active' => 1, 'number' => $turbineNumber), array('number' => 'ASC'));
        $i = 0;
        foreach ($turbines as $data){
            $resultado .= "Turbine ID: ".$data->getId();
            $turbine_id = $data->getId();
            $RAW_QUERY_POWER_KW = 'select DISTINCT TIME(hour) AS hora, max(power_k_w * -1000) AS maximo_power, AVG(power_k_w * -1000 ) AS media_power, max(rmspressure_pa) AS maximo_rms, AVG(rmspressure_pa) AS media_rms, date(date) AS fecha, turbines_id from turbines_datas  WHERE date(date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND turbines_id = ' . $turbine_id . ' AND automatic = 10 group by hour(timestamp) ORDER BY ID ASC  ';

            echo "<br />";
            echo $RAW_QUERY_POWER_KW;

            $statement = $this->em->getConnection()->prepare($RAW_QUERY_POWER_KW);
            $statement->execute();
            $medias = $statement->fetchAllAssociative();
            print_r($medias);

            for($i = 0; $i < count($medias); $i++){

                $hora = $medias[$i]["hora"];
                $maximo_power = $medias[$i]["maximo_power"];
                $media_power = $medias[$i]["media_power"];
                $maximo_rms = $medias[$i]["maximo_rms"];
                $media_rms = $medias[$i]["media_rms"];
                $fecha = $medias[$i]["fecha"];
                $turbines_id = $medias[$i]["turbines_id"];
                $timestamp = new DateTime();

                echo "Hora: ".$hora;

                $turbinesMedias = new TurbinesMedias();
                $turbinesMedias->setTurbines($turbines_id);
                $turbinesMedias->setTimestamp($timestamp);
                $turbinesMedias->setDate($fecha);
                $turbinesMedias->setHour($hora);
                $turbinesMedias->setPowerKWMedia($media_power);
                $turbinesMedias->setRMSPressurePaMedia($media_rms);
                $turbinesMedias->setPowerKWMax($maximo_power);
                $turbinesMedias->setRMSPressurePaMax($maximo_rms);
                $turbinesMedias->setCreated(new DateTime());

                $this->em->persist($turbinesMedias);

            }



        }

//        $turbine_id = 17;
//
//        $RAW_QUERY_POWER_KW = 'select DISTINCT TIME(hour) AS hora, max(power_k_w * -1000) AS maximo, AVG(power_k_w * -1000 ), date(date) AS fecha, turbines_id from turbines_datas  WHERE date(date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND turbines_id = ' . $turbine_id . ' AND automatic = 10 group by hour(timestamp) ORDER BY ID ASC  ';
//        $RAW_QUERY_POWER_KW = 'select DISTINCT TIME(hour) AS hora, max(power_k_w * -1000) AS maximo_power, AVG(power_k_w * -1000 ) AS media_power, max(rmspressure_pa) AS maximo_rms, AVG(rmspressure_pa) AS media_rms, date(date) AS fecha, turbines_id from turbines_datas  WHERE date(date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND turbines_id = ' . $turbine_id . ' AND automatic = 10 group by hour(timestamp) ORDER BY ID ASC  ';
//        $RAW_QUERY_RMS_PRESSURE_PA = 'select DISTINCT TIME(hour) AS hora, max(rmspressure_pa) AS maximo, AVG(rmspressure_pa), date(date) AS fecha, turbines_id from turbines_datas  WHERE date(date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND turbines_id = ' . $turbine_id . ' AND automatic = 10 group by hour(timestamp) ORDER BY ID ASC  ';
//
//        $dateFormat = 'H:i';
//        $period = new \DateTime();
//        $period = $period->format('d/m/Y');
//
//        $statement = $this->em->getConnection()->prepare($RAW_QUERY_POWER_KW);
//        $statement->execute();
//        $presiones = $statement->fetchAll();
//        $numPresiones = count($presiones);



        $output->writeln($resultado);
        // Siempre debemos devolver 0 para que la consola no de error
        return 0;
    }
}