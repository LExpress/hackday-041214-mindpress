<?php

class inseeDataTask extends sfBaseTask
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
    $this->name             = 'insee-data';
    $this->briefDescription = '';
    $this->detailedDescription = '';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $csvFile = file_get_contents(sfConfig::get('sf_data_dir').'/csv/data_insee.csv');

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
    unset($arrayCsv[1]);

    $total = count($arrayCsv);
    $this->showStatus(0, $total);

    foreach ($arrayCsv as $index => $data)
    {
      switch ($data[0])
      {
        case '75056':
          foreach (range(75001, 75020) as $value)
          {
            $codes[] = $this->addCode($value+100, $data);
          }
          break;

        case '13055':
          foreach (range(13001, 13016) as $value)
          {
            $codes[] = $this->addCode($value+200, $data);
          }
          break;

        case '69123':
          foreach (range(69001, 69009) as $value)
          {
            $codes[] = $this->addCode($value+380, $data);
          }
          break;

        default:
          $codes[] = $this->addCode($data[0], $data);
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
        $code = new InseeData();
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
      'access_proxi' => $data[2],
      'access_inter' => $data[3],
      'licence_sport' => $data[4],
      'emploi' => $data[5],
      'salaire' => $data[6],
      'espace_nature' => $data[7],
      'dist_travail' => $data[8],
      'access_doctor' => $data[9],
      'access_care' => $data[10],
    );
  }
}
