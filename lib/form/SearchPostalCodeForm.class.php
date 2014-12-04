<?php

/**
 * PostalCode form.
 *
 * @package    mindpress
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id$
 */
class SearchForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'q' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'q' => new sfValidatorDoctrineChoice(array('model' => 'PostalCode', 'column' => 'code_postal')),
    ));

    $this->widgetSchema->setNameFormat('search[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
