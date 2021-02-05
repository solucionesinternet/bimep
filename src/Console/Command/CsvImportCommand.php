<?php

// Uso del comando desde consola:
// php bin/console csv:import Turbine   <-- Donde Turbine puede ser Turbine, Buoy o Regenerator

namespace App\Console\Command;

use App\Entity\Profile;
use App\Entity\Turbines;
use App\Entity\TurbinesDatas;
use App\Entity\TurbinesFiles;
use App\Entity\TurbineToProfile;
use App\Entity\Inverters;
use App\Entity\InvertersDatas;
use App\Entity\InvertersFiles;
use App\Entity\InvertersToProfile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class CsvImportCommand extends Command
{


    private $em;
    private $params;

    // Creo un constructor y le paso la instancia de Entity Manager (ORM) para poder acceder desde el execute a la BBDD
    // También le paso el ParameterBagInterface para tener acceso a los parametros del services.yml

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params)
    {
        parent::__construct();
        $this->em = $em;
        $this->params = $params;

    }

    protected function configure()
    {
        $this
            ->setName('csv:import')
            ->setDescription('Importar CSVs de Turbinas, Boyas y Regeneradores a la BBDD')
            ->addArgument(
                'tipo',
                InputArgument::OPTIONAL,
                '¿Que tipo de CSV quieres importar?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $type = $input->getArgument('tipo');

        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        $finder = new Finder();
        $finder->depth('== 0');

        if ($type == "Turbine") {

            $turbines_data_folder = $this->params->get('turbines_data_folder');
            $finder->files()->in($turbines_data_folder);

            if ($finder->hasResults()) {

//            foreach ($finder as $file) {
                // Limito la importacion a 1 archivos
                foreach (new \LimitIterator($finder->getIterator(), 0, 1) as $file) {
                    $fileNameWithExtension = $file->getFilename();
                    $absoluteFilePath = $file->getRealPath();
                    $absoluteFileSize = $file->getSize();
                    $absoluteFileTime = $file->getMTime();
                    $completeTurbineNumber = substr($fileNameWithExtension, 0, 3);
                    $turbineNumber = str_replace("T", "", $completeTurbineNumber);
                    $turbineNumber = intval($turbineNumber);

                    echo "Archivo: " . $fileNameWithExtension . '<br />';
                    echo "Path: " . $absoluteFilePath . '<br />';
                    echo "Size: " . $absoluteFileSize . '<br />';
                    echo "Time: " . $absoluteFileTime . '<br />';
                    echo "Tres caracteres: " . $completeTurbineNumber . '<br />';
                    echo "Nº Turbina: " . $turbineNumber . '<br />';


                    // Compruebo si existe la turbina y si no existe la creo y le asigno en TurbinestoProfiles
                    // el Perfil de acceso de tecnologo por defecto que tiene el Id 1
                    $turbines = $this->em->getRepository(Turbines::class)->findOneBy(
                        [
                            'number' => $turbineNumber
                        ]);
                    if ($turbines === null) {
                        $turbines = new Turbines();
                        $turbines->setName('Turbina ' . $turbineNumber);
                        $turbines->setPosition('Posición ' . $turbineNumber);
                        $turbines->setActive(1);
                        $turbines->setCreated(new \DateTime());
                        $turbines->setNumber($turbineNumber);
                        $this->em->persist($turbines);
                        $this->em->flush();

                        // Obtengo el perfil tecnologo
                        $profile = $this->em->getRepository(Profile::class)->find(1);
                        $turbinesToProfile = new TurbineToProfile();
                        $turbinesToProfile->setTurbines($turbines);
                        $turbinesToProfile->setProfile($profile);
                        $this->em->persist($turbinesToProfile);
                        $this->em->flush();
                    }


                    // Genero la instancia de turbines_files y la relleno
                    $turbines_files = new TurbinesFiles();
                    $turbines_files->setTurbines($turbines);
                    $turbines_files->setName($fileNameWithExtension);
                    $turbines_files->setSize($absoluteFileSize);
                    $turbines_files->setDate(new \DateTime());
                    $turbines_files->setCreated(new \DateTime());
                    $this->em->persist($turbines_files);
//                dump($turbines_files);
                    $this->em->flush();


                    // Importo el archivo
                    $rowNo = 1;
                    $contador = 0;
                    $linecount = exec('perl -pe \'s/\r\n|\n|\r/\n/g\' ' . escapeshellarg($absoluteFilePath) . ' | wc -l');
                    //die('lineas: '.$linecount);
                    // Cmprobamos si esta vacio y si lo está eliminamos el archivo y la palmamos
                    if($linecount < 3){
                        unlink($absoluteFilePath);
                        die('El archivo esta vacio, por lo cual procedemos a eliminarlo y cortar el proceso de importación.');
                    }
                    if (($fp = fopen($absoluteFilePath, "r")) !== FALSE) {
                        while (($row = fgetcsv($fp, $linecount, "\t")) !== FALSE) {
                            if ($contador != 0) {
                                $rowNo++;
                                $Date = trim($row[0]);
//                            $Time = trim(substr($row[1], 0, -4));
                                $Time = trim($row[1]);
                                $ActiveCurrent_A = trim($row[2]);
                                if ($ActiveCurrent_A == '') $ActiveCurrent_A = 0;
                                $AUTOMATIC = trim($row[3]);
                                if ($AUTOMATIC == '') $AUTOMATIC = 0;
                                $AvPower1min_W = trim($row[4]);
                                if ($AvPower1min_W == '') $AvPower1min_W = 0;
                                $AvPower5min_W = trim($row[5]);
                                if ($AvPower5min_W == '') $AvPower5min_W = 0;
                                $DamperActualPosition_Deg = trim($row[6]);
                                if ($DamperActualPosition_Deg == '') $DamperActualPosition_Deg = 0;
                                $DriveHealthy = trim($row[7]);
                                if ($DriveHealthy == '') $DriveHealthy = 0;
                                $Motor_rpm = trim($row[8]);
                                if ($Motor_rpm == '') $Motor_rpm = 0;
                                $OuputVoltage_V = trim($row[9]);
                                if ($OuputVoltage_V == '') $OuputVoltage_V = 0;
                                $OutputFrequency_Hz = trim($row[10]);
                                if ($OutputFrequency_Hz == '') $OutputFrequency_Hz = 0;
                                $Power_kW = trim($row[11]);
                                if ($Power_kW == '') $Power_kW = 0;
                                $Pressure_Pa = trim($row[12]);
                                if ($Pressure_Pa == '') $Pressure_Pa = 0;
                                $reactiveCurrent_A = trim($row[13]);
                                if ($reactiveCurrent_A == '') $reactiveCurrent_A = 0;
                                $RMSPressure_Pa = trim($row[14]);
                                if ($RMSPressure_Pa == '') $RMSPressure_Pa = 0;
                                $Vibration_mmps = trim($row[15]);
                                if ($Vibration_mmps == '') $Vibration_mmps = 0;

                                // Solo algunas turbinas tienen las filas de la 16 a l 20, así que ponemos el condicional
                                // Si no existe creo la variable con valor cero
                                if (isset($row[16])) {
                                    $Flow1_pa = trim($row[16]);
                                    if ($Flow1_pa == '') $Flow1_pa = 0;
                                } else {
                                    $Flow1_pa = 0;
                                }

                                if (isset($row[17])) {
                                    $Flow2_pa = trim($row[17]);
                                    if ($Flow2_pa == '') $Flow2_pa = 0;
                                } else {
                                    $Flow2_pa = 0;
                                }

                                if (isset($row[18])) {
                                    $Static_pressure_pa = trim($row[18]);
                                    if ($Static_pressure_pa == '') $Static_pressure_pa = 0;
                                } else {
                                    $Static_pressure_pa = 0;
                                }

                                if (isset($row[19])) {
                                    $Temperature1 = trim($row[19]);
                                    if ($Temperature1 == '') $Temperature1 = 0;
                                } else {
                                    $Temperature1 = 0;
                                }

                                if (isset($row[20])) {
                                    $Waterlevel = trim($row[20]);
                                    if ($Waterlevel == '') $Waterlevel = 0;
                                } else {
                                    $Waterlevel = 0;
                                }


                                // Solo vamos a importar 1 registro cada minuto para no llenar la BBDD ya que no se muestran en ningun sitio
                                // Si es el primer elemento del bucle lo guardo
                                if ($contador == 1) {
                                    $ultimo_registro_hora = $Time;
                                    // Guardamos los datos en BBDD
                                    // Creo ls entidad y la voy rellenando
                                    $turbines_datas = new TurbinesDatas();

                                    list($day, $month, $year) = explode('/', $Date);
                                    list($hour, $minute, $second) = explode(':', $Time);
                                    list($single_second, $milisecond) = explode(',', $second);
                                    $timestamp = new \DateTime();
                                    $timestamp->setDate($year, $month, $day);
                                    $timestamp->setTime($hour, $minute, $single_second, $milisecond);
                                    $turbines_datas->setCreated(new \DateTime());
                                    $turbines_datas->setTimestamp($timestamp);
                                    $turbines_datas->setDate($timestamp);
                                    $turbines_datas->setHour($Time);
                                    $turbines_datas->setActiveCurrentA(intval($ActiveCurrent_A));
                                    $turbines_datas->setAUTOMATIC(intval($AUTOMATIC));
                                    $turbines_datas->setAvPower1minW(intval($AvPower1min_W));
                                    $turbines_datas->setAvPower5minW(intval($AvPower5min_W));
                                    $turbines_datas->setDamperActualPositionDeg(intval($DamperActualPosition_Deg));
                                    $turbines_datas->setDriveHealthy(intval($DriveHealthy));
                                    $turbines_datas->setMotorRpm(intval($Motor_rpm));
                                    $turbines_datas->setOuputVoltageV(intval($OuputVoltage_V));
                                    $turbines_datas->setOutputFrequencyHz(intval($OutputFrequency_Hz));
                                    $turbines_datas->setPowerKW(intval($Power_kW));
                                    $turbines_datas->setPressurePa(intval($Pressure_Pa));
                                    $turbines_datas->setReactiveCurrentA(intval($reactiveCurrent_A));
                                    $turbines_datas->setRMSPressurePa(intval($RMSPressure_Pa));
                                    $turbines_datas->setVibrationMmps(intval($Vibration_mmps));
                                    $turbines_datas->setFlow1Pa($Flow1_pa);
                                    $turbines_datas->setFlow2Pa($Flow2_pa);
                                    $turbines_datas->setWStaticPressurePa($Static_pressure_pa);
                                    $turbines_datas->setTemperature1(intval($Temperature1));
                                    $turbines_datas->setWaterlevel(intval($Waterlevel));

                                    $turbines_datas->setTurbines($turbines);

                                    $this->em->persist($turbines_datas);
                                }

//                            if ($this->nimutosDiferencia($ultimo_registro_hora, $Time) == 1) {
                                // Guardo la hora de este elememto del bucle como buena
                                $ultimo_registro_hora = $Time;

                                // Guardamos los datos en BBDD
                                // Creo ls entidad y la voy rellenando
                                list($day, $month, $year) = explode('/', $Date);
                                list($hour, $minute, $second) = explode(':', $Time);
                                list($single_second, $milisecond) = explode(',', $second);
                                $timestamp = new \DateTime();
                                $timestamp->setDate($year, $month, $day);
                                $timestamp->setTime($hour, $minute, $single_second, $milisecond);
                                $turbines_datas = new TurbinesDatas();
                                $turbines_datas->setCreated(new \DateTime());
                                $turbines_datas->setTimestamp($timestamp);
                                $turbines_datas->setDate($timestamp);
                                $turbines_datas->setHour($Time);
                                $turbines_datas->setActiveCurrentA(intval($ActiveCurrent_A));
                                $turbines_datas->setAUTOMATIC(intval($AUTOMATIC));
                                $turbines_datas->setAvPower1minW(intval($AvPower1min_W));
                                $turbines_datas->setAvPower5minW(intval($AvPower5min_W));
                                $turbines_datas->setDamperActualPositionDeg(intval($DamperActualPosition_Deg));
                                $turbines_datas->setDriveHealthy(intval($DriveHealthy));
                                $turbines_datas->setMotorRpm(intval($Motor_rpm));
                                $turbines_datas->setOuputVoltageV(intval($OuputVoltage_V));
                                $turbines_datas->setOutputFrequencyHz(intval($OutputFrequency_Hz));
                                $turbines_datas->setPowerKW(intval($Power_kW));
                                $turbines_datas->setPressurePa(intval($Pressure_Pa));
                                $turbines_datas->setReactiveCurrentA(intval($reactiveCurrent_A));
                                $turbines_datas->setRMSPressurePa(intval($RMSPressure_Pa));
                                $turbines_datas->setVibrationMmps(intval($Vibration_mmps));
                                $turbines_datas->setFlow1Pa($Flow1_pa);
                                $turbines_datas->setFlow2Pa($Flow2_pa);
                                $turbines_datas->setWStaticPressurePa($Static_pressure_pa);
                                $turbines_datas->setTemperature1(intval($Temperature1));
                                $turbines_datas->setWaterlevel(intval($Waterlevel));
                                $turbines_datas->setTurbines($turbines);

                                $this->em->persist($turbines_datas);
//                            }


                                if ($contador % 2500 == 0) {
                                    $this->em->flush();
                                }
                            }
                            $contador++;
                        }
                        fclose($fp);
                        // Mover el archivo a la carpeta HISTORIC
                        // Comprobamos que exista el directorio
                        if (!(is_dir($this->params->get('turbines_data_historic_folder') . $completeTurbineNumber)))
                            mkdir($this->params->get('turbines_data_historic_folder') . $completeTurbineNumber);
                        $mover = rename($absoluteFilePath, $this->params->get('turbines_data_historic_folder') . $completeTurbineNumber . "/" . $fileNameWithExtension);

                    }


                }

            }

        } elseif ($type == "Buoy") {

            $buoys_data_folder = $this->params->get('buoys_data_folder');
            $finder->files()->in($buoys_data_folder);

        } elseif ($type == "Regenerator") {

            $regenerators_data_folder = $this->params->get('regenerators_data_folder');
            $finder->files()->in($regenerators_data_folder);

            if ($finder->hasResults()) {

                // Limito la importacion a 1 archivos
                foreach (new \LimitIterator($finder->getIterator(), 0, 1) as $file) {
                    $fileNameWithExtension = $file->getFilename();
                    $absoluteFilePath = $file->getRealPath();
                    $absoluteFileSize = $file->getSize();
                    $absoluteFileTime = $file->getMTime();
                    $completeRegeneratorNumber = substr($fileNameWithExtension, 0, 6);
                    $RegeneratorNumber = str_replace("Regen", "", $completeRegeneratorNumber);
                    if ($RegeneratorNumber == "A") {
                        $RegeneratorNumber = intval(1);
                    } else {
                        $RegeneratorNumber = intval(2);
                    }
                    $RegeneratorNumber = intval($RegeneratorNumber);

                    echo "Archivo: " . $fileNameWithExtension . '<br />';
                    echo "Path: " . $absoluteFilePath . '<br />';
                    echo "Size: " . $absoluteFileSize . '<br />';
                    echo "Time: " . $absoluteFileTime . '<br />';
                    echo "Primeros caracteres: " . $completeRegeneratorNumber . '<br />';
                    echo "Nº Regenerador: " . $RegeneratorNumber . '<br />';


                    // Compruebo si existe el Regenerador y si no existe lo creo y le asigno en InverterstoProfiles
                    // el Perfil de acceso de tecnologo por defecto que tiene el Id 1
                    $regenerator = $this->em->getRepository(Inverters::class)->findOneBy(
                        [
                            'number' => $RegeneratorNumber
                        ]);

                    if ($regenerator === null) {
                        $regenerator = new Inverters();
                        $regenerator->setName('Inverter ' . $RegeneratorNumber);
                        $regenerator->setPosition('Posición ' . $RegeneratorNumber);
                        $regenerator->setActive(1);
                        $regenerator->setCreated(new \DateTime());
                        $regenerator->setNumber($RegeneratorNumber);
                        $this->em->persist($regenerator);
                        $this->em->flush();

                        // Obtengo el perfil tecnologo
                        $profile = $this->em->getRepository(Profile::class)->find(1);
                        $regeneratorToProfile = new InvertersToProfile();
                        $regeneratorToProfile->setInverters($regenerator);
                        $regeneratorToProfile->setProfile($profile);
                        $this->em->persist($regeneratorToProfile);
                        $this->em->flush();
                    }


                    // Genero la instancia de inverters_files y la relleno
                    $regenerator_files = new InvertersFiles();
                    $regenerator_files->setInverters($regenerator);
                    $regenerator_files->setName($fileNameWithExtension);
                    $regenerator_files->setSize($absoluteFileSize);
                    $regenerator_files->setDate(new \DateTime());
                    $regenerator_files->setCreated(new \DateTime());
                    $this->em->persist($regenerator_files);
                    $this->em->flush();

                    // die();

                    // Importo el archivo
                    $rowNo = 1;
                    $contador = 0;
                    $linecount = exec('perl -pe \'s/\r\n|\n|\r/\n/g\' ' . escapeshellarg($absoluteFilePath) . ' | wc -l');
                    //die('lineas: '.$linecount);
                    if (($fp = fopen($absoluteFilePath, "r")) !== FALSE) {
                        while (($row = fgetcsv($fp, $linecount, "\t")) !== FALSE) {
                            if ($contador != 0) {
                                $rowNo++;
                                $Date = trim($row[0]);
//                            $Time = trim(substr($row[1], 0, -4));
                                $Time = trim($row[1]);
                                $ActiveCurrent_A = trim($row[2]);
                                if ($ActiveCurrent_A == '') $ActiveCurrent_A = 0;
                                $AvPower1min_W = trim($row[3]);
                                if ($AvPower1min_W == '') $AvPower1min_W = 0;
                                $AvPower5min_W = trim($row[4]);
                                if ($AvPower5min_W == '') $AvPower5min_W = 0;
                                $DCLinkVoltage_V = trim($row[5]);
                                if ($DCLinkVoltage_V == '') $DCLinkVoltage_V = 0;
                                $DriveHealthy = trim($row[6]);
                                if ($DriveHealthy == '') $DriveHealthy = 0;
                                $OutputVoltage_V = trim($row[7]);
                                if ($OutputVoltage_V == '') $OutputVoltage_V = 0;
                                $OutputFrequency_Hz = trim($row[8]);
                                if ($OutputFrequency_Hz == '') $OutputFrequency_Hz = 0;
                                $Power_kW = trim($row[9]);
                                if ($Power_kW == '') $Power_kW = 0;
                                $ReactiveCurrent_A = trim($row[10]);
                                if ($ReactiveCurrent_A == '') $ReactiveCurrent_A = 0;
                                $ReactivePower_kVAr = trim($row[11]);
                                if ($ReactivePower_kVAr == '') $ReactivePower_kVAr = 0;
                                $RoomHumidity = trim($row[12]);
                                if ($RoomHumidity == '') $RoomHumidity = 0;
                                $RoomPressure1_Pa = trim($row[13]);
                                if ($RoomPressure1_Pa == '') $RoomPressure1_Pa = 0;
                                $RoomPressure2_Pa = trim($row[14]);
                                if ($RoomPressure2_Pa == '') $RoomPressure2_Pa = 0;
                                $RoomTemperature = trim($row[15]);
                                if ($RoomTemperature == '') $RoomTemperature = 0;


                                // Solo vamos a importar 1 registro cada minuto para no llenar la BBDD ya que no se muestran en ningun sitio
                                // Si es el primer elemento del bucle lo guardo
                                if ($contador == 1) {
                                    $ultimo_registro_hora = $Time;
                                    // Guardamos los datos en BBDD
                                    // Creo ls entidad y la voy rellenando
                                    $regenerators_datas = new InvertersDatas();

                                    list($day, $month, $year) = explode('/', $Date);
                                    list($hour, $minute, $second) = explode(':', $Time);
                                    list($single_second, $milisecond) = explode(',', $second);
                                    $timestamp = new \DateTime();
                                    $timestamp->setDate($year, $month, $day);
                                    $timestamp->setTime($hour, $minute, $single_second, $milisecond);
//                                    $regenerators_datas->setCreated(new \DateTime());
                                    $regenerators_datas->setTimestamp($timestamp);
                                    $regenerators_datas->setDate($timestamp);
                                    $regenerators_datas->setHour($Time);
                                    $regenerators_datas->setActiveCurrentA(intval($ActiveCurrent_A));
                                    $regenerators_datas->setAvPower1minW(intval($AvPower1min_W));
                                    $regenerators_datas->setAvPower5minW(intval($AvPower5min_W));
                                    $regenerators_datas->setDCLinkVoltageV(intval($DCLinkVoltage_V));
                                    $regenerators_datas->setDriveHealthy(intval($DriveHealthy));
                                    $regenerators_datas->setOutputVoltageV(intval($OutputVoltage_V));
                                    $regenerators_datas->setOutputFrequencyHz(intval($OutputFrequency_Hz));
                                    $regenerators_datas->setPowerKW(intval($Power_kW));
                                    $regenerators_datas->setReactiveCurrentA(intval($ReactiveCurrent_A));
                                    $regenerators_datas->setReactivePowerKVAr(intval($ReactivePower_kVAr));
                                    $regenerators_datas->setRoomHumidity(intval($RoomHumidity));
                                    $regenerators_datas->setRoomPressure1Pa(intval($RoomPressure1_Pa));
                                    $regenerators_datas->setRoomPressure2Pa(intval($RoomPressure2_Pa));
                                    $regenerators_datas->setRoomTemperature(intval($RoomTemperature));

                                    $regenerators_datas->setInverters($regenerator);

                                    $this->em->persist($regenerators_datas);
                                }

                                // Guardo la hora de este elememto del bucle como buena
                                $ultimo_registro_hora = $Time;

                                // Guardamos los datos en BBDD
                                // Creo ls entidad y la voy rellenando
                                list($day, $month, $year) = explode('/', $Date);
                                list($hour, $minute, $second) = explode(':', $Time);
                                list($single_second, $milisecond) = explode(',', $second);
                                $timestamp = new \DateTime();
                                $timestamp->setDate($year, $month, $day);
                                $timestamp->setTime($hour, $minute, $single_second, $milisecond);
                                $regenerators_datas = new InvertersDatas();
//                                $regenerators_datas->setCreated(new \DateTime());
                                $regenerators_datas->setTimestamp($timestamp);
                                $regenerators_datas->setDate($timestamp);
                                $regenerators_datas->setHour($Time);
                                $regenerators_datas->setActiveCurrentA(intval($ActiveCurrent_A));
                                $regenerators_datas->setAvPower1minW(intval($AvPower1min_W));
                                $regenerators_datas->setAvPower5minW(intval($AvPower5min_W));
                                $regenerators_datas->setDCLinkVoltageV(intval($DCLinkVoltage_V));
                                $regenerators_datas->setDriveHealthy(intval($DriveHealthy));
                                $regenerators_datas->setOutputVoltageV(intval($OutputVoltage_V));
                                $regenerators_datas->setOutputFrequencyHz(intval($OutputFrequency_Hz));
                                $regenerators_datas->setPowerKW(intval($Power_kW));
                                $regenerators_datas->setReactiveCurrentA(intval($ReactiveCurrent_A));
                                $regenerators_datas->setReactivePowerKVAr(intval($ReactivePower_kVAr));
                                $regenerators_datas->setRoomHumidity(intval($RoomHumidity));
                                $regenerators_datas->setRoomPressure1Pa(intval($RoomPressure1_Pa));
                                $regenerators_datas->setRoomPressure2Pa(intval($RoomPressure2_Pa));
                                $regenerators_datas->setRoomTemperature(intval($RoomTemperature));

                                $regenerators_datas->setInverters($regenerator);

                                $this->em->persist($regenerators_datas);
//                            }


                                if ($contador % 2500 == 0) {
                                    $this->em->flush();
                                }
                            }
                            $contador++;
                        }
                        fclose($fp);
                        // Mover el archivo a la carpeta HISTORIC
                        // Comprobamos que exista el directorio
                        if (!(is_dir($this->params->get('regenerators_data_historic_folder') . "R" . $RegeneratorNumber)))
                            mkdir($this->params->get('regenerators_data_historic_folder') . "R" . $RegeneratorNumber);
                        $mover = rename($absoluteFilePath, $this->params->get('regenerators_data_historic_folder') . "R" . $RegeneratorNumber . "/" . $fileNameWithExtension);

                    }


                }

            }

        } else {
            $output->writeln("Tipo de archivo a importar no soportado.");
            return 0;
            die();
        }


        $output->writeln("Datos importados con éxito.");
        // Siempre debemos devolver 0 para que la consola no de error
        return 0;
    }
}