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

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()));

      if ($this->form->isValid())
      {
        $this->data = Doctrine_Core::getTable('PostalCode')->retrieveData($this->form->getValue('q'));
        var_dump($this->data);
      }
    }
  }
}
