<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNews extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('news');
    $this->hasColumn('title', 'varchar', 255, array('type' => 'varchar', 'length' => '255'));
    $this->hasColumn('body', 'text', null, array('type' => 'text'));
  }

  public function setUp()
  {
    $timestampable0 = new Doctrine_Template_Timestampable();
    $sluggable0 = new Doctrine_Template_Sluggable(array('fields' => array(0 => 'title'), 'alias' => 'key', 'indexName' => 'news_slug'));
    $this->actAs($timestampable0);
    $this->actAs($sluggable0);
  }
}