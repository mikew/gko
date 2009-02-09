<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BasePosts extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('posts');
    $this->hasColumn('title', 'string', null, array('type' => 'string', 'minlength' => '5'));
    $this->hasColumn('body', 'text', null, array('type' => 'text', 'notblank' => true));
    $this->hasColumn('body_markdown', 'text', null, array('type' => 'text'));
    $this->hasColumn('authors_id', 'integer', 8, array('type' => 'integer', 'length' => 8));
  }

  public function setUp()
  {
    $this->hasOne('Authors', array('local' => 'authors_id',
                                   'foreign' => 'id'));

    $this->hasMany('Comments', array('local' => 'id',
                                     'foreign' => 'posts_id'));

    $timestampable0 = new Doctrine_Template_Timestampable();
    $sluggable0 = new Doctrine_Template_Sluggable(array('fields' => array(0 => 'title'), 'alias' => 'key', 'indexName' => 'posts_slug'));
    $this->actAs($timestampable0);
    $this->actAs($sluggable0);
  }
}