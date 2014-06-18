<?php
class YasdCommentNofieldFixture extends CakeTestFixture {
    var $name = 'YasdCommentNofield';

    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'primary'),
        'yasd_post_nofield_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
        'comment' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
    );

    var $records = array(
        array(
            'yasd_post_nofield_id' => '1',
            'comment' => 'Comment',
            'created' => '2012-07-05 00:00:00',
            'modified' => '2012-07-05 00:00:00',
        ),
        array(
            'yasd_post_nofield_id' => '1',
            'comment' => 'Comment2',
            'created' => '2012-07-05 00:00:00',
            'modified' => '2012-07-05 00:00:00',
        ),
    );
}
