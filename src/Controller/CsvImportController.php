<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Turbines;
use App\Entity\TurbinesDatas;
use App\Entity\TurbinesFiles;
use App\Entity\TurbineToProfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Time;

class CsvImportController extends AbstractController
{


    /**
     * @Route("/csv/import", name="csv_import")
     */
    public function index()
    {

        $finder = new Finder();

        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $finder->depth('== 0');
        $finder->files()->in($this->getParameter('turbines_data_folder'));

        if ($finder->hasResults()) {

//            foreach ($finder as $file) {
            // Limito la importacion a 5 archivos
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
                $turbines = $em->getRepository(Turbines::class)->findOneBy(
                    [
                        'number' => $turbineNumber
                    ]);


                // Compruebo si está activa o no lo esta
                $turbineActive = $turbines->getActive();
                echo "Turbina Activa: ".$turbineActive;
                // Si no stá activa muevo el archivo a su ubicación y termino el proceso.
                if ($turbineActive == 0) {
                    // Mover el archivo a la carpeta HISTORIC
                    $mover = rename($absoluteFilePath, $this->getParameter('turbines_data_folder') . 'HISTORIC/' . $fileNameWithExtension);

                    return $this->render('csv_import/index.html.twig', [
                        'controller_name' => 'CsvImportController',
                    ]);
                    die();

                }


                if ($turbines === null) {
                    $turbines = new Turbines();
                    $turbines->setName('Turbina ' . $turbineNumber);
                    $turbines->setPosition('Posición ' . $turbineNumber);
                    $turbines->setActive(1);
                    $turbines->setCreated(new \DateTime());
                    $turbines->setNumber($turbineNumber);
                    $em->persist($turbines);
                    //dump($turbines);
                    $em->flush();

                    // Obtengo el perfil tecnologo
                    $profile = $em->getRepository(Profile::class)->find(1);
                    $turbinesToProfile = new TurbineToProfile();
                    $turbinesToProfile->setTurbines($turbines);
                    $turbinesToProfile->setProfile($profile);
                }


                // Genero la instancia de turbines_files y la relleno
                $turbines_files = new TurbinesFiles();
                $turbines_files->setTurbines($turbines);
                $turbines_files->setName($fileNameWithExtension);
                $turbines_files->setSize($absoluteFileSize);
                $turbines_files->setDate(new \DateTime());
                $turbines_files->setCreated(new \DateTime());
                $em->persist($turbines_files);
                dump($turbines_files);
                $em->flush();



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

                                $em->persist($turbines_datas);
                            }

                            if ($this->nimutosDiferencia($ultimo_registro_hora, $Time) == 1) {
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

                                $em->persist($turbines_datas);
                            }


                            if ($contador % 2500 == 0) {
                                $em->flush();
                            }
                        }
                        //$em->flush();
                        $contador++;
                    }
                    //$em->flush();
                    fclose($fp);
                    // Mover el archivo a la carpeta HISTORIC
                    $mover = rename($absoluteFilePath, $this->getParameter('turbines_data_folder') . 'HISTORIC/' . $fileNameWithExtension);
//                    echo "mover: ".$mover;

                }


            }

        }

        return $this->render('csv_import/index.html.twig', [
            'controller_name' => 'CsvImportController',
        ]);
    }

    public function nimutosDiferencia($hora1, $hora2)
    {

        $valor1 = new \DateTime($hora1);
        $valor2 = new \DateTime($hora2);
        $intervalo = $valor1->diff($valor2);

        return $intervalo->format('%i');

    }
}
