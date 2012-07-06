<?php
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');

class YasdPost extends CakeTestModel{

    public $name = 'YasdPost';
    public $actsAs = array('Yasd.SoftDeletable');

    public $hasMany = array(
        'YasdComment' => array(
            'className' => 'YasdComment',
            'foreignKey' => 'yasd_post_id',
            'dependent' => true,
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

class SoftDeletableTestCase extends CakeTestCase{

    public $fixtures = array('plugin.Yasd.yasd_post',
                             'plugin.Yasd.yasd_comment');

    function setUp() {
        $this->YasdPost = new YasdPost();
        $this->YasdPostFixture = ClassRegistry::init('YasdPostFixture');
        $this->YasdPost->enableSoftDeletable();
        $this->YasdPost->YasdComment->enableSoftDeletable();
    }

    function tearDown() {
        unset($this->YasdPost);
        unset($this->YasdPostFixture);
    }

    /**
     * testFind
     *
     * jpn: 通常のfind
     *      delete_flgがnullでもfind可能なのがOriginalと大きく異なる点
     */
    public function testFind(){
        $result = $this->YasdPost->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testFindCount
     *
     */
    public function testFindCount(){
        $result = $this->YasdPost->find('count');
        $this->assertIdentical($result, 2);
    }

    /**
     * testFindWithCondition
     *
     */
    public function testFindWithCondition(){
        $result = $this->YasdPost->find('all', array('conditions' => array('YasdPost.id' => 2)));
        $this->assertIdentical(count($result), 1);

        // jpn: 文字列指定も可
        $result = $this->YasdPost->find('all', array('conditions' => 'YasdPost.id = 2'));
        $this->assertIdentical(count($result), 1);
    }

    /**
     * testDependentFind
     *
     * jpn: hasManyで紐づいているModelについてもSoftDeletable属性をチェックする
     */
    public function testDependentFind(){
        $result = $this->YasdPost->findById(1);
        $this->assertIdentical(count($result['YasdComment']), 2);

        $this->YasdPost->YasdComment->delete(1);
        $result = $this->YasdPost->findById(1);
        $this->assertIdentical(count($result['YasdComment']), 1);
    }

    /**
     * testSoftDeletableFind
     *
     */
    public function testSoftDeletableFind(){
        // jpn: SoftDeletableが有効の時にはModel::delete()がfalseになる
        $result = $this->YasdPost->delete(1);
        $this->assertFalse($result);

        $result = $this->YasdPost->find('all');
        $this->assertIdentical(count($result), 1);

        $this->YasdPost->disableSoftDeletable();
        $result = $this->YasdPost->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testDependentSoftDelete
     *
     * jpn: hasMany先のSoftDeletableが有効に発動する
     */
    public function testDependentSoftDelete(){
        $result = $this->YasdPost->YasdComment->find('all');
        $this->assertIdentical(count($result), 2);

        $this->YasdPost->delete(1);

        $result = $this->YasdPost->YasdComment->find('all');
        $this->assertIdentical(count($result), 0);

        $this->YasdPost->YasdComment->disableSoftDeletable();
        $result = $this->YasdPost->YasdComment->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testHardDelete
     *
     * jpn: SoftDeletableを無効にして削除ができる
     */
    public function testHardDelete(){
        $this->YasdPost->disableSoftDeletable();
        $this->YasdPost->delete(1);
        $result = $this->YasdPost->find('all');
        $this->assertIdentical(count($result), 1);
    }
}
