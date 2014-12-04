<?php

/**
 * search actions.
 *
 * @package    mindpress
 * @subpackage search
 * @author     Your name here
 * @version    SVN: $Id$
 */
class searchActions extends sfActions
{
  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new SearchForm();
    $this->data = array();

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()));

      if ($this->form->isValid())
      {
        $this->data = Doctrine_Core::getTable('PostalCode')->retrieveData($this->form->getValue('q'));

        if (isset($this->data['InseeData'][0]))
        {
          // securite
           $insee = $this->data['InseeData'][0];
           // Pratique access_proxi
           if ($insee['access_proxi'] < 60)
           {
             $this->data['stars']['access_proxi'] = 0;
           }
           if ($insee['access_proxi'] >= 60 && $insee['access_proxi'] <= 70)
           {
             $this->data['stars']['access_proxi'] = 1;
           }
            if ($insee['access_proxi'] >= 70 && $insee['access_proxi'] <= 80)
           {
             $this->data['stars']['access_proxi'] = 2;
           }
            if ($insee['access_proxi'] >= 80 && $insee['access_proxi'] <= 90)
           {
             $this->data['stars']['access_proxi'] = 3;
           }
            if ($insee['access_proxi'] >= 90 && $insee['access_proxi'] <= 95)
           {
             $this->data['stars']['access_proxi'] = 4;
           }
           if ($insee['access_proxi'] >= 95 && $insee['access_proxi'] <= 100)
           {
             $this->data['stars']['access_proxi'] = 5;
           }

      // Pratique access_inter

        if ($insee['access_inter'] < 40)
       {
         $this->data['stars']['access_inter'] = 0;
       }
       if ($insee['access_inter'] >= 40 && $insee['access_inter'] <= 50)
       {
         $this->data['stars']['access_inter'] = 1;
       }
        if ($insee['access_inter'] >= 50 && $insee['access_inter'] <= 60)
       {
         $this->data['stars']['access_inter'] = 2;
       }
        if ($insee['access_inter'] >= 60 && $insee['access_inter'] <= 80)
       {
         $this->data['stars']['access_inter'] = 3;
       }
        if ($insee['access_inter'] >= 80 && $insee['access_inter'] <= 90)
       {
         $this->data['stars']['access_inter'] = 4;
       }
       if ($insee['access_inter'] >= 90 && $insee['access_inter'] <= 100)
       {
         $this->data['stars']['access_inter'] = 5;
       }

       // Vie de quartier

       if ($insee['licence_sport'] < 5)
       {
         $this->data['stars']['licence_sport'] = 0;
       }
       if ($insee['licence_sport'] >= 5 && $insee['licence_sport'] <= 10)
       {
         $this->data['stars']['licence_sport'] = 1;
       }
        if ($insee['licence_sport'] >= 10 && $insee['licence_sport'] <= 15)
       {
         $this->data['stars']['licence_sport'] = 2;
       }
        if ($insee['licence_sport'] >= 15 && $insee['licence_sport'] <= 20)
       {
         $this->data['stars']['licence_sport'] = 3;
       }
        if ($insee['licence_sport'] >= 30 && $insee['licence_sport'] <= 40)
       {
         $this->data['stars']['licence_sport'] = 4;
       }
       if ($insee['licence_sport'] >= 40 )
       {
         $this->data['stars']['licence_sport'] = 5;
       }

        // Emploi

       if ($insee['emploi'] < 60)
       {
         $this->data['stars']['emploi'] = 0;
       }
       if ($insee['emploi'] >= 60 && $insee['emploi'] <= 70)
       {
         $this->data['stars']['emploi'] = 1;
       }
        if ($insee['emploi'] >= 70 && $insee['emploi'] <= 80)
       {
         $this->data['stars']['emploi'] = 2;
       }
        if ($insee['emploi'] >= 80 && $insee['emploi'] <= 85)
       {
         $this->data['stars']['emploi'] = 3;
       }
        if ($insee['emploi'] >= 85 && $insee['emploi'] <= 90)
       {
         $this->data['stars']['emploi'] = 4;
       }
       if ($insee['emploi'] >= 90 )
       {
         $this->data['stars']['emploi'] = 5;
       }

      // Salaire

       if ($insee['salaire'] < 80)
       {
         $this->data['stars']['salaire'] = 0;
       }
       if ($insee['salaire'] >= 80 && $insee['salaire'] <= 90)
       {
         $this->data['stars']['salaire'] = 1;
       }
        if ($insee['salaire'] >= 90 && $insee['salaire'] <= 95)
       {
         $this->data['stars']['salaire'] = 2;
       }
        if ($insee['salaire'] >= 95 && $insee['salaire'] <= 100)
       {
         $this->data['stars']['salaire'] = 3;
       }
        if ($insee['salaire'] >= 100 && $insee['salaire'] <= 110)
       {
         $this->data['stars']['salaire'] = 4;
       }
       if ($insee['salaire'] >= 110 )
       {
         $this->data['stars']['salaire'] = 5;
       }

        // Nature

       if ($insee['espace_nature'] > 90)
       {
         $this->data['stars']['espace_nature'] = 0;
       }
       if ($insee['espace_nature'] >= 70 && $insee['espace_nature'] <= 90)
       {
         $this->data['stars']['espace_nature'] = 1;
       }
        if ($insee['espace_nature'] >= 50 && $insee['espace_nature'] <= 70)
       {
         $this->data['stars']['espace_nature'] = 2;
       }
        if ($insee['espace_nature'] >= 30 && $insee['espace_nature'] <= 50)
       {
         $this->data['stars']['espace_nature'] = 3;
       }
        if ($insee['espace_nature'] >= 10 && $insee['espace_nature'] <= 30)
       {
         $this->data['stars']['espace_nature'] = 4;
       }
       if ($insee['espace_nature'] <= 10 )
       {
         $this->data['stars']['espace_nature'] = 5;
       }

      // Transport

       if ($insee['dist_travail'] < 35)
       {
         $this->data['stars']['dist_travail'] = 0;
       }
       if ($insee['dist_travail'] >= 35 && $insee['dist_travail'] <= 50)
       {
         $this->data['stars']['dist_travail'] = 1;
       }
        if ($insee['dist_travail'] >= 50 && $insee['dist_travail'] <= 65)
       {
         $this->data['stars']['dist_travail'] = 2;
       }
        if ($insee['dist_travail'] >= 65 && $insee['dist_travail'] <= 80)
       {
         $this->data['stars']['dist_travail'] = 3;
       }
        if ($insee['dist_travail'] >= 80 && $insee['dist_travail'] <= 90)
       {
         $this->data['stars']['dist_travail'] = 4;
       }
       if ($insee['dist_travail'] >= 90 )
       {
         $this->data['stars']['dist_travail'] = 5;
       }

      // Medecin

       if ($insee['access_doctor'] < 15)
       {
         $this->data['stars']['access_doctor'] = 0;
       }
       if ($insee['access_doctor'] >= 15 && $insee['access_doctor'] <= 30)
       {
         $this->data['stars']['access_doctor'] = 1;
       }
        if ($insee['access_doctor'] >= 30 && $insee['access_doctor'] <= 50)
       {
         $this->data['stars']['access_doctor'] = 2;
       }
        if ($insee['access_doctor'] >= 50 && $insee['access_doctor'] <= 80)
       {
         $this->data['stars']['access_doctor'] = 3;
       }
        if ($insee['access_doctor'] >= 80 && $insee['access_doctor'] <= 110)
       {
         $this->data['stars']['access_doctor'] = 4;
       }
       if ($insee['access_doctor'] >= 110 )
       {
         $this->data['stars']['access_doctor'] = 5;
       }

       // Sante

       if ($insee['access_care'] < 63)
       {
         $this->data['stars']['access_care'] = 0;
       }
       if ($insee['access_care'] >= 63 && $insee['access_care'] <= 78)
       {
         $this->data['stars']['access_care'] = 1;
       }
        if ($insee['access_care'] >= 78 && $insee['access_care'] <= 93)
       {
         $this->data['stars']['access_care'] = 2;
       }
        if ($insee['access_care'] >= 93 && $insee['access_care'] <= 96)
       {
         $this->data['stars']['access_care'] = 3;
       }
        if ($insee['access_care'] >= 96 && $insee['access_care'] <= 98)
       {
         $this->data['stars']['access_care'] = 4;
       }
       if ($insee['access_care'] >= 98 )
       {
         $this->data['stars']['access_care'] = 5;
       }

       //

        if ($insee['access_care'] > 30000)
       {
         $this->data['stars']['access_care'] = 0;
       }
       if ($insee['access_care'] >= 24000 && $insee['access_care'] <= 30000)
       {
         $this->data['stars']['access_care'] = 1;
       }
        if ($insee['access_care'] >= 18000 && $insee['access_care'] <= 24000)
       {
         $this->data['stars']['access_care'] = 2;
       }
        if ($insee['access_care'] >= 12000 && $insee['access_care'] <= 18000)
       {
         $this->data['stars']['access_care'] = 3;
       }
        if ($insee['access_care'] >= 6000 && $insee['access_care'] <= 12000)
       {
         $this->data['stars']['access_care'] = 4;
       }
       if ($insee['access_care'] <= 6000 )
       {
         $this->data['stars']['access_care'] = 5;
       }
        }

        var_dump($this->data);
      }
    }
  }

  public function executeSearch(sfWebRequest $request)
  {
    $search = $request->getParameter('q');

    $res = Doctrine_Core::getTable('PostalCode')
      ->createQuery('c')
      ->select('c.code_insee, c.code_postal, c.nom_commune, c.departement')
      ->where('c.code_postal like ?', array($search.'%'))
      ->orWhere('c.nom_commune like ?', array($search.'%'))
      ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

    return $this->renderJson($res);
  }
}
