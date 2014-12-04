<?php

require_once dirname(__DIR__).'/lib/vendor/autoload.php';

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');

    $this->dispatcher->connect('doctrine.configure', array($this, 'onDoctrineConfigure'));
  }

  public function onDoctrineConfigure(sfEvent $event)
  {
    $manager = $event->getSubject();
    $manager->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
    $manager->setAttribute(Doctrine_Core::ATTR_AUTO_FREE_QUERY_OBJECTS, true);
    $manager->setAttribute(Doctrine_Core::ATTR_USE_TABLE_REPOSITORY, false);
    $manager->setAttribute(Doctrine_Core::ATTR_USE_TABLE_IDENTITY_MAP, false);
    $manager->setCollate('utf8_unicode_ci');
    $manager->setCharset('utf8');
  }
}
