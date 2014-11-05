<?php
class YasdPostFlgOnliesYasdTagFlgOnlyFixture extends CakeTestFixture {
    public $name = 'YasdPostFlgOnliesYasdTagFlgOnly';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'primary'),
        'yasd_post_flg_only_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
        'yasd_tag_flg_only_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'delete_flg' => array('type' => 'integer', 'null' => true, 'default' => NULL),
    );

    public $records = array(
        array(
            'yasd_post_flg_only_id' => 1,
            'yasd_tag_flg_only_id' => 1,
            'created' => '2012-07-05 00:00:00',
            'modified' => '2012-07-05 00:00:00',
            'delete_flg' => null
        ),
        array(
            'yasd_post_flg_only_id' => 1,
            'yasd_tag_flg_only_id' => 2,
            'created' => '2012-07-05 00:00:00',
            'modified' => '2012-07-05 00:00:00',
            'delete_flg' => null
        ),
    );
}
