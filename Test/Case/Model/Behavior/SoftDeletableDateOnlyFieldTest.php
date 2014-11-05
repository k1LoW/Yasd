<?php
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');

class YasdPostDateOnly extends CakeTestModel{

    public $name = 'YasdPostDateOnly';
    public $actsAs = array(
        'Yasd.SoftDeletable' => array(
            'field' => false,
            'field_date' => 'deleted',
        ),
        'Containable'
    );

    public $hasOne = array(
        'YasdMemoDateOnly' => array(
            'className' => 'YasdMemoDateOnly',
            'foreignKey' => 'yasd_post_date_only_id',
            'dependent' => true,
        )
    );

    public $hasMany = array(
        'YasdCommentDateOnly' => array(
            'className' => 'YasdCommentDateOnly',
            'foreignKey' => 'yasd_post_date_only_id',
            'dependent' => true,
        )
    );

    public $hasAndBelongsToMany = array(
        'YasdTagDateOnly'
    );
}

class YasdMemoDateOnly extends CakeTestModel{

    public $name = 'YasdMemoDateOnly';
    public $actsAs = array(
        'Yasd.SoftDeletable' => array(
            'field' => false,
            'field_date' => 'deleted',
        ),
    );

    public $belongsTo = array(
        'YasdPostDateOnly' => array(
            'className' => 'YasdPostDateOnly',
            'foreignKey' => 'yasd_post_date_only_id',
        )
    );
}

class YasdCommentDateOnly extends CakeTestModel{

    public $name = 'YasdCommentDateOnly';
    public $actsAs = array(
        'Yasd.SoftDeletable' => array(
            'field' => false,
            'field_date' => 'deleted',
        ),
    );

    public $belongsTo = array(
        'YasdPostDateOnly' => array(
            'className' => 'YasdPostDateOnly',
            'foreignKey' => 'yasd_post_date_only_id',
        )
    );
}

class YasdTagDateOnly extends CakeTestModel{
    public $name = 'YasdTagDateOnly';
    public $actsAs = array(
        'Yasd.SoftDeletable' => array(
            'field' => false,
            'field_date' => 'deleted',
        ),
    );

}

class YasdPostDateOnliesYasdTagDateOnly extends CakeTestModel{
    public $name = 'YasdPostDateOnliesYasdTagDateOnly';
    public $actsAs = array(
        'Yasd.SoftDeletable' => array(
            'field' => false,
            'field_date' => 'deleted',
        ),
    );
}

/**
 * SoftDeletableDateOnlyFieldTestCase
 * jpn: delete_date1つだけでの運用を可能にする
 *
 */
class SoftDeletableDateOnlyFieldTestCase extends CakeTestCase{

    public $fixtures = array(
        'plugin.Yasd.yasd_post_date_only',
        'plugin.Yasd.yasd_memo_date_only',
        'plugin.Yasd.yasd_comment_date_only',
        'plugin.Yasd.yasd_post_date_onlies_yasd_tag_date_only',
        'plugin.Yasd.yasd_tag_date_only',
    );

    public function setUp() {
        $this->YasdPostDateOnly = new YasdPostDateOnly();
        $this->YasdPostDateOnlyFixture = ClassRegistry::init('YasdPostDateOnlyFixture');
        $this->YasdPostDateOnly->enableSoftDeletable();
        $this->YasdPostDateOnly->YasdMemoDateOnly->enableSoftDeletable();
        $this->YasdPostDateOnly->YasdCommentDateOnly->enableSoftDeletable();
        $this->YasdPostDateOnly->YasdTagDateOnly->enableSoftDeletable();

        $this->YasdPostDateOnliesYasdTagDateOnly = new YasdPostDateOnliesYasdTagDateOnly();
    }

    public function tearDown() {
        unset($this->YasdPostDateOnly);
        unset($this->YasdPostDateOnlyFixture);
        unset($this->YasdPostDateOnliesYasdTagDateOnly);
    }

    /**
     * testFind
     *
     * jpn: 通常のfind
     *      delete_dateがnullでもfind可能なのがOriginalと大きく異なる点
     */
    public function testFind(){
        $result = $this->YasdPostDateOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testFindCount
     *
     * jpn: 通常のcount
     */
    public function testFindCount(){
        $result = $this->YasdPostDateOnly->find('count');
        $this->assertIdentical($result, 2);
    }

    /**
     * testFindWithCondition
     *
     */
    public function testFindWithCondition(){
        $result = $this->YasdPostDateOnly->find('all', array(
            'conditions' => array('YasdPostDateOnly.id' => 2)
        ));
        $this->assertIdentical(count($result), 1);

        // jpn: 文字列指定も可
        $result = $this->YasdPostDateOnly->find('all', array(
            'conditions' => 'YasdPostDateOnly.id = 2')
        );
        $this->assertIdentical(count($result), 1);
    }

    /**
     * testDependentHasDateOnlyFind
     *
     * jpn: hasOneで紐づいているModelについてもSoftDeletable属性をチェックする
     */
    public function testDependentHasDateOnlyFind(){
        $result = $this->YasdPostDateOnly->findById(1);
        $this->assertIdentical((string)$result['YasdMemoDateOnly']['id'], '1');

        $this->YasdPostDateOnly->YasdMemoDateOnly->delete(1);
        $result = $this->YasdPostDateOnly->findById(1);
        $this->assertIdentical($result['YasdMemoDateOnly']['id'], null);
    }

    /**
     * testDependentHasManyFind
     *
     * jpn: hasManyで紐づいているModelについてもSoftDeletable属性をチェックする
     */
    public function testDependentHasManyFind(){
        $result = $this->YasdPostDateOnly->findById(1);
        $this->assertIdentical(count($result['YasdCommentDateOnly']), 2);

        $this->YasdPostDateOnly->YasdCommentDateOnly->delete(1);
        $result = $this->YasdPostDateOnly->findById(1);
        $this->assertIdentical(count($result['YasdCommentDateOnly']), 1);
    }

    /**
     * testDependentHABTMFind
     *
     * jpn: hasAndBelongsToManyで紐づいているModelについてもSoftDeletable属性をチェックする
     */
    public function testDependentHABTMFind(){
        $result = $this->YasdPostDateOnly->findById(1);
        $this->assertIdentical(count($result['YasdTagDateOnly']), 2);

        $this->YasdPostDateOnly->YasdTagDateOnly->delete(1);
        $result = $this->YasdPostDateOnly->findById(1);
        $this->assertIdentical(count($result['YasdTagDateOnly']), 1);
    }

    /**
     * testSoftDeletableFind
     *
     * jpn: SoftDeletableが有効になっている
     */
    public function testSoftDeletableFind(){
        // jpn: SoftDeletableが有効の時にはModel::delete()がfalseになる
        $result = $this->YasdPostDateOnly->delete(1);
        $this->assertFalse($result);

        $result = $this->YasdPostDateOnly->find('all');
        $this->assertIdentical(count($result), 1);

        // jpn: disableSoftDeletable()で無効化できる
        $this->YasdPostDateOnly->disableSoftDeletable();
        $result = $this->YasdPostDateOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testSoftDeletableFindWithCondition
     *
     * jpn: deletedの条件を強引に上書きできる
     *
     */
    public function testSoftDeletableFindWithCondition(){
        $now = date('Y-m-d H:i:s');

        $result = $this->YasdPostDateOnly->delete(1);
        $result = $this->YasdPostDateOnly->find('all');
        $this->assertIdentical(count($result), 1);

        $result = $this->YasdPostDateOnly->find('all', array(
            'conditions' => array('YasdPostDateOnly.deleted' => $now)
        ));
        $this->assertIdentical(count($result), 1);
    }

    /**
     * testDependentHasDateOnlySoftDelete
     *
     * jpn: hasDateOnly先のdependentされたModelのSoftDeletableが有効に発動する
     */
    public function testDependentHasDateOnlySoftDelete(){
        $result = $this->YasdPostDateOnly->YasdMemoDateOnly->find('all');
        $this->assertIdentical(count($result), 2);

        $this->YasdPostDateOnly->delete(1);

        $result = $this->YasdPostDateOnly->YasdMemoDateOnly->find('all');
        $this->assertIdentical(count($result), 1);

        $this->YasdPostDateOnly->YasdMemoDateOnly->disableSoftDeletable();
        $result = $this->YasdPostDateOnly->YasdMemoDateOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testDependentHasManySoftDelete
     *
     * jpn: hasMany先のdependentされたModelのSoftDeletableが有効に発動する
     */
    public function testDependentHasManySoftDelete(){
        $result = $this->YasdPostDateOnly->YasdCommentDateOnly->find('all');
        $this->assertIdentical(count($result), 2);

        $this->YasdPostDateOnly->delete(1);

        $result = $this->YasdPostDateOnly->YasdCommentDateOnly->find('all');
        $this->assertIdentical(count($result), 0);

        $this->YasdPostDateOnly->YasdCommentDateOnly->disableSoftDeletable();
        $result = $this->YasdPostDateOnly->YasdCommentDateOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testDependentHABTMSoftDelete
     *
     * jpn: HABTMのModelのSoftDeletableが有効に発動する
     */
    public function testDependentHABTMSoftDelete(){
        $result = $this->YasdPostDateOnly->YasdTagDateOnly->find('all');
        $this->assertIdentical(count($result), 2);

        $this->YasdPostDateOnly->delete(1);

        // jpn: HABTMはリンクを削除するだけ
        $result = $this->YasdPostDateOnly->YasdTagDateOnly->find('all');
        $this->assertIdentical(count($result), 2);

        // jpn: 中間テーブルもSoftDeletableの設定を書けば有効に発動する
        $result = $this->YasdPostDateOnliesYasdTagDateOnly->find('all');
        $this->assertIdentical(count($result), 0);

        $this->YasdPostDateOnliesYasdTagDateOnly->disableSoftDeletable();
        $result = $this->YasdPostDateOnliesYasdTagDateOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testHardDelete
     *
     * jpn: SoftDeletableを無効にして削除ができる
     */
    public function testHardDelete(){
        $this->YasdPostDateOnly->disableSoftDeletable();
        $this->YasdPostDateOnly->delete(1);
        $result = $this->YasdPostDateOnly->find('all');
        $this->assertIdentical(count($result), 1);
    }

    /**
     * testFindWithContain
     *
     * jpn: containを利用しているときもhasMany先のSoftDeletableは有効
     */
    public function testFindWithContain(){
        $result = $this->YasdPostDateOnly->find('first', array(
            'conditions' => array('YasdPostDateOnly.id' => 1),
            'contain' => 'YasdCommentDateOnly')
        );
        $this->assertIdentical(count($result['YasdCommentDateOnly']), 2);

        $this->YasdPostDateOnly->YasdCommentDateOnly->delete(1);
        $result = $this->YasdPostDateOnly->find('first', array(
            'conditions' => array('YasdPostDateOnly.id' => 1),
            'contain' => 'YasdCommentDateOnly')
        );
        $this->assertIdentical(count($result['YasdCommentDateOnly']), 1);
    }

    /**
     * testSoftDeleteAll
     *
     * jpn: Model::softDeleteAll()のテスト
     */
    public function testSoftDeleteAll(){
        $result = $this->YasdPostDateOnly->softDeleteAll();
        $this->assertTrue($result);

        $result = $this->YasdPostDateOnly->find('all');
        $this->assertIdentical(count($result), 0);

        $this->YasdPostDateOnly->disableSoftDeletable();
        $result = $this->YasdPostDateOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testSoftDeleteAllWithConditions
     *
     * jpn: Model::softDeleteAll()のテスト
     */
    public function testSoftDeleteAllWithConditions(){
        $result = $this->YasdPostDateOnly->softDeleteAll(array('YasdPostDateOnly.title' => 'Title2'));
        $this->assertTrue($result);

        $result = $this->YasdPostDateOnly->find('all');
        $this->assertIdentical(count($result), 1);

        $this->YasdPostDateOnly->disableSoftDeletable();
        $result = $this->YasdPostDateOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }
}
