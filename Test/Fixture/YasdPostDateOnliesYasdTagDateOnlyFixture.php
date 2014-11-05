<?php
class YasdPostDateOnliesYasdTagDateOnlyFixture extends CakeTestFixture {
    public $name = 'YasdPostDateOnliesYasdTagDateOnly';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'primary'),
        'yasd_post_date_only_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
        'yasd_tag_date_only_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'deleted' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
    );

    public $records = array(
        array(
            'yasd_post_date_only_id' => 1,
            'yasd_tag_date_only_id' => 1,
            'created' => '2012-07-05 00:00:00',
            'modified' => '2012-07-05 00:00:00',
            'deleted' => null
        ),
        array(
            'yasd_post_date_only_id' => 1,
            'yasd_tag_date_only_id' => 2,
            'created' => '2012-07-05 00:00:00',
            'modified' => '2012-07-05 00:00:00',
            'deleted' => null
        ),
    );
}
