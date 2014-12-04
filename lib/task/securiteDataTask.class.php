<?php

class securiteDataTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'main'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'init';
    $this->name             = 'securite-data';
    $this->briefDescription = '';
    $this->detailedDescription = '';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $csvFile = file_get_contents(sfConfig::get('sf_data_dir').'/csv/securite.csv');

    $lines = explode(PHP_EOL, $csvFile);
    $arrayCsv = array();
    foreach ($lines as $line)
    {
      if ($line != '')
      {
        $arrayCsv[] = str_getcsv($line, ';');
      }
    }

    unset($arrayCsv[0]);

    $total = count($arrayCsv);
    $this->showStatus(0, $total);

    $q = Doctrine_Core::getTable('PostalCode')
      ->createQuery('c')
      ->select('c.code_insee')
      ->where('c.nom_commune = ?')
      ->setAutoFree(false)
      ->limit(1);

    foreach ($arrayCsv as $index => $data)
    {
      // $map = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?sensor=false&latlng='.str_replace(' ', '', $data[11])), true);
      // var_export($map['results'][0]['address_components'][6]['long_name']);
      // $code = Doctrine_Core::getTable('PostalCode')->findOneByCodePostal($map['results'][0]['address_components'][6]['long_name'], Doctrine_Core::HYDRATE_ARRAY);

      // $ville = str_replace(' ', '-', strtoupper(Doctrine_Inflector::unaccent($data[1])));
      // $code = json_decode(file_get_contents('http://public.opendatasoft.com/api/records/1.0/search?dataset=correspondance-code-insee-code-postal&facet=nom_comm&refine.nom_comm='.$ville), true);

      $ville = trim(str_replace("' ", "'", $data[1]));

      switch ($ville)
      {
        case 'Paris':
          foreach (range(75001, 75020) as $value)
          {
            $codes[] = $this->addCode($value+100, $data);
          }
          break;

        case 'Marseille':
          foreach (range(13001, 13016) as $value)
          {
            $codes[] = $this->addCode($value+200, $data);
          }
          break;

        case 'Lyon':
          foreach (range(69001, 69009) as $value)
          {
            $codes[] = $this->addCode($value+380, $data);
          }
          break;

        default:
          $code = $q->execute($ville, Doctrine_Core::HYDRATE_NONE);

          if (!isset($code[0][0]))
          {
            $this->logSection('notfound', 'No city found: '.$ville);
            continue;
          }

          $codes[] = $this->addCode($code[0][0], $data);
      }

      $this->showStatus($index++, $total);

      if (count($codes) < 500)
      {
        continue;
      }

      $pid = pcntl_fork();
      if ($pid == -1)
      {
        throw new Exception('could not fork, you need pcntl extension');
      }
      elseif ($pid)
      {
        pcntl_wait($status);
        pcntl_waitpid($pid, $stat);

        // we finished to process last wagon, let's prepare a new one
        $codes = array();
      }
      else
      {
        try
        {
          $this->pcntlProcess($codes);
          posix_kill(getmypid(), 9);
        }
        catch (Exception $e)
        {
          posix_kill(getmypid(), 9);
        }
      }
    }

    if (count($codes))
    {
      $this->pcntlProcess($codes);
    }
  }

  private function pcntlProcess($codes)
  {
    foreach ($codes as $data)
    {
      try
      {
        $code = new SecuriteData();
        $code->fromArray($data);
        $code->save();
        $code->free();
        unset($code);
      }
      catch (Exception $e)
      {
        $this->logSection('error', 'Problem with data: '.var_export($data, true).' / '.$e->getMessage());
      }
    }
  }

  private function addCode($codeInsee, $data)
  {
    return array(
      'code_insee' => $codeInsee,
      'commune' => $data[1],
      'population' => $data[2],
      'departement' => $data[3],
      'zone' => $data[4],
      'compagnies_de_gendarmerie_departementale' => $data[5],
      'classement_violences_personnes' => $data[6],
      'classement_atteintes_biens' => $data[7],
      'classement_violences_physiques' => $data[8],
      'classement_cambriolages' => $data[9],
      'classement_vols_voitures' => $data[10],
      'coordonnees_geographiques' => $data[11],
    );
  }
}
