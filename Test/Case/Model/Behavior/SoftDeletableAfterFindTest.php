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
            'conditions' => 'YasdMemo.created IS NOT NULL'
        )
    );

    public $hasMany = array(
        'YasdComment' => array(
            'className' => 'YasdComment',
            'foreignKey' => 'yasd_post_id',
            'dependent' => true,
            'conditions' => array('NOT' => array('YasdComment.created' => null))
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

class SoftDeletableAfterFindTestCase extends CakeTestCase{

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
     * testDependentHasOneConditions
     *
     * jpn: hasOneで紐づいているModelについてもSoftDeletable属性をチェックする
     */
    public function testDependentHasOneConditions(){
        $result = $this->YasdPost->findById(1);
        $this->assertIdentical((string)$result['YasdMemo']['id'], '1');
        $this->YasdPost->YasdMemo->delete(1);
        $this->assertIdentical($this->YasdPost->hasOne['YasdMemo']['conditions'], 'YasdMemo.created IS NOT NULL');
        $result = $this->YasdPost->findById(1);
        $this->assertIdentical($result['YasdMemo']['id'], null);
        $this->assertIdentical($this->YasdPost->hasOne['YasdMemo']['conditions'], 'YasdMemo.created IS NOT NULL');
    }

    /**
     * testDependentHasManyConditions
     *
     * jpn: hasManyで紐づいているModelについてもSoftDeletable属性をチェックする
     */
    public function testDependentHasManyConditions(){
        $result = $this->YasdPost->findById(1);
        $this->assertIdentical(count($result['YasdComment']), 2);
        $this->YasdPost->YasdComment->delete(1);
        $this->assertIdentical($this->YasdPost->hasMany['YasdComment']['conditions'], array('NOT' => array('YasdComment.created' => null)));
        $result = $this->YasdPost->findById(1);
        $this->assertIdentical(count($result['YasdComment']), 1);
        $this->assertIdentical($this->YasdPost->hasMany['YasdComment']['conditions'], array('NOT' => array('YasdComment.created' => null)));
    }

    /**
     * testDependentHABTMConditions
     *
     * jpn: hasAndBelongsToManyで紐づいているModelについてもSoftDeletable属性をチェックする
     */
    public function testDependentHABTMConditions(){
        $result = $this->YasdPost->findById(1);
        $this->assertIdentical(count($result['YasdTag']), 2);

        $this->YasdPost->YasdTag->delete(1);
        $this->assertIdentical($this->YasdPost->hasAndBelongsToMany['YasdTag']['conditions'], '');
        $result = $this->YasdPost->findById(1);
        $this->assertIdentical(count($result['YasdTag']), 1);
        $this->assertIdentical($this->YasdPost->hasAndBelongsToMany['YasdTag']['conditions'], '');
    }
}
