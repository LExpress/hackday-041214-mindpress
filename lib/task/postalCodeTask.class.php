<?php

class postalCodeTask extends sfBaseTask
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
    $this->name             = 'postal-code';
    $this->briefDescription = '';
    $this->detailedDescription = '';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $csvFile = file_get_contents(sfConfig::get('app_data_postal_code'));

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

    foreach ($arrayCsv as $index => $data)
    {
      $codes[] = array(
        'code_insee' => trim($data[0]),
        'nom_commune' => ucwords(strtolower(trim($data[1]))),
        'code_postal' => trim($data[2]),
        'libelle_acheminement' => ucwords(strtolower(trim($data[3])))
      );

      $this->showStatus($index++, $total);

      if (count($codes) < 1000)
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
        $code = new PostalCode();
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
}
