<?php
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');

class YasdPost extends CakeTestModel{

    public $name = 'YasdPost';
    public $actsAs = array(
        'Yasd.SoftDeletable',
        'Containable'
    );

    public $hasOne = array(
        'YasdMemo' => array(
            'className' => 'YasdMemo',
            'foreignKey' => 'yasd_post_id',
            'dependent' => true,
        )
    );

    public $hasMany = array(
        'YasdComment' => array(
            'className' => 'YasdComment',
            'foreignKey' => 'yasd_post_id',
            'dependent' => true,
        )
    );

    public $hasAndBelongsToMany = array(
        'YasdTag'
    );
}

class YasdMemo extends CakeTestModel{

    public $name = 'YasdMemo';
    public $actsAs = array('Yasd.SoftDeletable');

    public $belongsTo = array(
        'YasdPost' => array(
            'className' => 'YasdPost',
            'foreignKey' => 'yasd_post_id',
        )
    );
}

class YasdComment extends CakeTestModel{

    public $name = 'YasdComment';
    public $actsAs = array('Yasd.SoftDeletable');

    public $belongsTo = array(
        'YasdPost' => array(
            'className' => 'YasdPost',
            'foreignKey' => 'yasd_post_id',
        )
    );
}

class YasdTag extends CakeTestModel{
    public $name = 'YasdTag';
    public $actsAs = array('Yasd.SoftDeletable');

}

class YasdPostsYasdTag extends CakeTestModel{
    public $name = 'YasdPostsYasdTag';
    public $actsAs = array('Yasd.SoftDeletable');
}

class YasdPostNofield extends CakeTestModel{

    public $name = 'YasdPostNofield';
    public $actsAs = array(
        'Yasd.SoftDeletable',
        'Containable'
    );

    public $hasMany = array(
        'YasdCommentNofield' => array(
            'className' => 'YasdCommentNofield',
            'foreignKey' => 'yasd_post_nofield_id',
            'dependent' => true,
        )
    );
}

class YasdCommentNofield extends CakeTestModel{

    public $name = 'YasdCommentNofield';

    public $belongsTo = array(
        'YasdPostNofield' => array(
            'className' => 'YasdPostNofield',
            'foreignKey' => 'yasd_post_nofield_id',
        )
    );
}

class SoftDeletableOneFieldTestCase extends CakeTestCase{

    public $fixtures = array(
        'plugin.Yasd.yasd_post',
        'plugin.Yasd.yasd_memo',
        'plugin.Yasd.yasd_comment',
        'plugin.Yasd.yasd_posts_yasd_tag',
        'plugin.Yasd.yasd_tag',
        'plugin.Yasd.yasd_post_nofield',
        'plugin.Yasd.yasd_comment_nofield',
    );

    public function setUp() {
        $this->YasdPost = new YasdPost();
        $this->YasdPostFixture = ClassRegistry::init('YasdPostFixture');
        $this->YasdPost->enableSoftDeletable();
        $this->YasdPost->YasdMemo->enableSoftDeletable();
        $this->YasdPost->YasdComment->enableSoftDeletable();
        $this->YasdPost->YasdTag->enableSoftDeletable();

        $this->YasdPostsYasdTag = new YasdPostsYasdTag();

        $this->YasdPostNofield= new YasdPostNofield();
        $this->YasdPostNofield->enableSoftDeletable();
    }

    public function tearDown() {
        unset($this->YasdPost);
        unset($this->YasdPostFixture);
        unset($this->YasdPostsYasdTag);
        unset($this->YasdPostNofield);
    }

    /**
     * testUseDeleteFlgOnly
     * jpn: 'field_date' => falseの場合deleted は使われない
     *
     */
    public function testUseDeleteFlgOnly(){
        $result = $this->YasdPost->find('all');
        $this->assertIdentical(count($result), 2);
        
        $settings = array(
            'field' => 'delete_flg',
            'field_date' => false,
        );
        $this->YasdPost->setUp($settings);

        $this->YasdPost->delete(1);

        $result = $this->YasdPost->find('all');
        $this->assertIdentical(count($result), 1);

        $this->YasdPost->disableSoftDeletable();
        $result = $this->YasdPost->findById(1);

        $this->assertIdentical($result['YasdPost']['deleted'], null);
    }

    /**
     * testUseDeletedOnly
     * jpn: 'field' => falseの場合delete_flg は使われない
     *
     */
    public function testUseDeletedOnly(){
        $result = $this->YasdPost->find('all');
        $this->assertIdentical(count($result), 2);
        
        $settings = array(
            'field' => false,
            'field_date' => 'deleted',
        );
        $this->YasdPost->setUp($settings);

        $this->YasdPost->delete(1);

        $result = $this->YasdPost->find('all');
        $this->assertIdentical(count($result), 1);

        $this->YasdPost->disableSoftDeletable();
        $result = $this->YasdPost->findById(1);

        $this->assertIdentical($result['YasdPost']['delete_flg'], null);
    }   
}
