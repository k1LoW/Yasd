<?php
class YasdPostNofieldFixture extends CakeTestFixture {
    var $name = 'YasdPostNofield';

    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'primary'),
        'title' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'body' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
    );

    var $records = array(
        array(
            'title' => 'Title',
            'body' => 'Yasd Test',
            'created' => '2012-07-05 00:00:00',
            'modified' => '2012-07-05 00:00:00',
        ),
        array(
            'title' => 'Title2',
            'body' => 'Yasd Test2',
            'created' => '2012-07-05 00:00:00',
            'modified' => '2012-07-05 00:00:00',
        ),
    );
}
