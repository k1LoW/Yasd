<?php
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');

class YasdPostFlgOnly extends CakeTestModel{

    public $name = 'YasdPostFlgOnly';
    public $actsAs = array(
        'Yasd.SoftDeletable' => array(
            'field' => 'delete_flg',
            'field_date' => false,
        ),
        'Containable'
    );

    public $hasOne = array(
        'YasdMemoFlgOnly' => array(
            'className' => 'YasdMemoFlgOnly',
            'foreignKey' => 'yasd_post_flg_only_id',
            'dependent' => true,
        )
    );

    public $hasMany = array(
        'YasdCommentFlgOnly' => array(
            'className' => 'YasdCommentFlgOnly',
            'foreignKey' => 'yasd_post_flg_only_id',
            'dependent' => true,
        )
    );

    public $hasAndBelongsToMany = array(
        'YasdTagFlgOnly'
    );
}

class YasdMemoFlgOnly extends CakeTestModel{

    public $name = 'YasdMemoFlgOnly';
    public $actsAs = array(
        'Yasd.SoftDeletable' => array(
            'field' => 'delete_flg',
            'field_date' => false,
        ),
    );

    public $belongsTo = array(
        'YasdPostFlgOnly' => array(
            'className' => 'YasdPostFlgOnly',
            'foreignKey' => 'yasd_post_flg_only_id',
        )
    );
}

class YasdCommentFlgOnly extends CakeTestModel{

    public $name = 'YasdCommentFlgOnly';
    public $actsAs = array(
        'Yasd.SoftDeletable' => array(
            'field' => 'delete_flg',
            'field_date' => false,
        ),
    );

    public $belongsTo = array(
        'YasdPostFlgOnly' => array(
            'className' => 'YasdPostFlgOnly',
            'foreignKey' => 'yasd_post_flg_only_id',
        )
    );
}

class YasdTagFlgOnly extends CakeTestModel{
    public $name = 'YasdTagFlgOnly';
    public $actsAs = array(
        'Yasd.SoftDeletable' => array(
            'field' => 'delete_flg',
            'field_date' => false,
        ),
    );

}

class YasdPostFlgOnliesYasdTagFlgOnly extends CakeTestModel{
    public $name = 'YasdPostFlgOnliesYasdTagFlgOnly';
    public $actsAs = array(
        'Yasd.SoftDeletable' => array(
            'field' => 'delete_flg',
            'field_date' => false,
        ),
    );
}

/**
 * SoftDeletableFlgOnlyFieldTestCase
 * jpn: delete_flg1つだけでの運用を可能にする
 *
 */
class SoftDeletableFlgOnlyFieldTestCase extends CakeTestCase{

    public $fixtures = array(
        'plugin.Yasd.yasd_post_flg_only',
        'plugin.Yasd.yasd_memo_flg_only',
        'plugin.Yasd.yasd_comment_flg_only',
        'plugin.Yasd.yasd_post_flg_onlies_yasd_tag_flg_only',
        'plugin.Yasd.yasd_tag_flg_only',
    );

    public function setUp() {
        $this->YasdPostFlgOnly = new YasdPostFlgOnly();
        $this->YasdPostFlgOnlyFixture = ClassRegistry::init('YasdPostFlgOnlyFixture');
        $this->YasdPostFlgOnly->enableSoftDeletable();
        $this->YasdPostFlgOnly->YasdMemoFlgOnly->enableSoftDeletable();
        $this->YasdPostFlgOnly->YasdCommentFlgOnly->enableSoftDeletable();
        $this->YasdPostFlgOnly->YasdTagFlgOnly->enableSoftDeletable();

        $this->YasdPostFlgOnliesYasdTagFlgOnly = new YasdPostFlgOnliesYasdTagFlgOnly();
    }

    public function tearDown() {
        unset($this->YasdPostFlgOnly);
        unset($this->YasdPostFlgOnlyFixture);
        unset($this->YasdPostFlgOnliesYasdTagFlgOnly);
    }

    /**
     * testFind
     *
     * jpn: 通常のfind
     *      delete_flgがnullでもfind可能なのがOriginalと大きく異なる点
     */
    public function testFind(){
        $result = $this->YasdPostFlgOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testFindCount
     *
     * jpn: 通常のcount
     */
    public function testFindCount(){
        $result = $this->YasdPostFlgOnly->find('count');
        $this->assertIdentical($result, 2);
    }

    /**
     * testFindWithCondition
     *
     */
    public function testFindWithCondition(){
        $result = $this->YasdPostFlgOnly->find('all', array(
            'conditions' => array('YasdPostFlgOnly.id' => 2)
        ));
        $this->assertIdentical(count($result), 1);

        // jpn: 文字列指定も可
        $result = $this->YasdPostFlgOnly->find('all', array(
            'conditions' => 'YasdPostFlgOnly.id = 2')
        );
        $this->assertIdentical(count($result), 1);
    }

    /**
     * testDependentHasFlgOnlyFind
     *
     * jpn: hasFlgOnlyで紐づいているModelについてもSoftDeletable属性をチェックする
     */
    public function testDependentHasFlgOnlyFind(){
        $result = $this->YasdPostFlgOnly->findById(1);
        $this->assertIdentical((string)$result['YasdMemoFlgOnly']['id'], '1');

        $this->YasdPostFlgOnly->YasdMemoFlgOnly->delete(1);
        $result = $this->YasdPostFlgOnly->findById(1);
        $this->assertIdentical($result['YasdMemoFlgOnly']['id'], null);
    }

    /**
     * testDependentHasManyFind
     *
     * jpn: hasManyで紐づいているModelについてもSoftDeletable属性をチェックする
     */
    public function testDependentHasManyFind(){
        $result = $this->YasdPostFlgOnly->findById(1);
        $this->assertIdentical(count($result['YasdCommentFlgOnly']), 2);

        $this->YasdPostFlgOnly->YasdCommentFlgOnly->delete(1);
        $result = $this->YasdPostFlgOnly->findById(1);
        $this->assertIdentical(count($result['YasdCommentFlgOnly']), 1);
    }

    /**
     * testDependentHABTMFind
     *
     * jpn: hasAndBelongsToManyで紐づいているModelについてもSoftDeletable属性をチェックする
     */
    public function testDependentHABTMFind(){
        $result = $this->YasdPostFlgOnly->findById(1);
        $this->assertIdentical(count($result['YasdTagFlgOnly']), 2);

        $this->YasdPostFlgOnly->YasdTagFlgOnly->delete(1);
        $result = $this->YasdPostFlgOnly->findById(1);
        $this->assertIdentical(count($result['YasdTagFlgOnly']), 1);
    }

    /**
     * testSoftDeletableFind
     *
     * jpn: SoftDeletableが有効になっている
     */
    public function testSoftDeletableFind(){
        // jpn: SoftDeletableが有効の時にはModel::delete()がfalseになる
        $result = $this->YasdPostFlgOnly->delete(1);
        $this->assertFalse($result);

        $result = $this->YasdPostFlgOnly->find('all');
        $this->assertIdentical(count($result), 1);

        // jpn: disableSoftDeletable()で無効化できる
        $this->YasdPostFlgOnly->disableSoftDeletable();
        $result = $this->YasdPostFlgOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testSoftDeletableFindWithCondition
     *
     * jpn: delete_flgの条件を強引に上書きできる
     *
     */
    public function testSoftDeletableFindWithCondition(){
        $result = $this->YasdPostFlgOnly->delete(1);
        $result = $this->YasdPostFlgOnly->find('all');
        $this->assertIdentical(count($result), 1);

        $result = $this->YasdPostFlgOnly->find('all', array(
            'conditions' => array('YasdPostFlgOnly.delete_flg' => array(0,1,null))
        ));
        $this->assertIdentical(count($result), 1);
    }

    /**
     * testDependentHasFlgOnlySoftDelete
     *
     * jpn: hasFlgOnly先のdependentされたModelのSoftDeletableが有効に発動する
     */
    public function testDependentHasFlgOnlySoftDelete(){
        $result = $this->YasdPostFlgOnly->YasdMemoFlgOnly->find('all');
        $this->assertIdentical(count($result), 2);

        $this->YasdPostFlgOnly->delete(1);

        $result = $this->YasdPostFlgOnly->YasdMemoFlgOnly->find('all');
        $this->assertIdentical(count($result), 1);

        $this->YasdPostFlgOnly->YasdMemoFlgOnly->disableSoftDeletable();
        $result = $this->YasdPostFlgOnly->YasdMemoFlgOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testDependentHasManySoftDelete
     *
     * jpn: hasMany先のdependentされたModelのSoftDeletableが有効に発動する
     */
    public function testDependentHasManySoftDelete(){
        $result = $this->YasdPostFlgOnly->YasdCommentFlgOnly->find('all');
        $this->assertIdentical(count($result), 2);

        $this->YasdPostFlgOnly->delete(1);

        $result = $this->YasdPostFlgOnly->YasdCommentFlgOnly->find('all');
        $this->assertIdentical(count($result), 0);

        $this->YasdPostFlgOnly->YasdCommentFlgOnly->disableSoftDeletable();
        $result = $this->YasdPostFlgOnly->YasdCommentFlgOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testDependentHABTMSoftDelete
     *
     * jpn: HABTMのModelのSoftDeletableが有効に発動する
     */
    public function testDependentHABTMSoftDelete(){
        $result = $this->YasdPostFlgOnly->YasdTagFlgOnly->find('all');
        $this->assertIdentical(count($result), 2);

        $this->YasdPostFlgOnly->delete(1);

        // jpn: HABTMはリンクを削除するだけ
        $result = $this->YasdPostFlgOnly->YasdTagFlgOnly->find('all');
        $this->assertIdentical(count($result), 2);

        // jpn: 中間テーブルもSoftDeletableの設定を書けば有効に発動する
        $result = $this->YasdPostFlgOnliesYasdTagFlgOnly->find('all');
        $this->assertIdentical(count($result), 0);

        $this->YasdPostFlgOnliesYasdTagFlgOnly->disableSoftDeletable();
        $result = $this->YasdPostFlgOnliesYasdTagFlgOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testHardDelete
     *
     * jpn: SoftDeletableを無効にして削除ができる
     */
    public function testHardDelete(){
        $this->YasdPostFlgOnly->disableSoftDeletable();
        $this->YasdPostFlgOnly->delete(1);
        $result = $this->YasdPostFlgOnly->find('all');
        $this->assertIdentical(count($result), 1);
    }

    /**
     * testFindWithContain
     *
     * jpn: containを利用しているときもhasMany先のSoftDeletableは有効
     */
    public function testFindWithContain(){
        $result = $this->YasdPostFlgOnly->find('first', array(
            'conditions' => array('YasdPostFlgOnly.id' => 1),
            'contain' => 'YasdCommentFlgOnly')
        );
        $this->assertIdentical(count($result['YasdCommentFlgOnly']), 2);

        $this->YasdPostFlgOnly->YasdCommentFlgOnly->delete(1);
        $result = $this->YasdPostFlgOnly->find('first', array(
            'conditions' => array('YasdPostFlgOnly.id' => 1),
            'contain' => 'YasdCommentFlgOnly')
        );
        $this->assertIdentical(count($result['YasdCommentFlgOnly']), 1);
    }

    /**
     * testSoftDeleteAll
     *
     * jpn: Model::softDeleteAll()のテスト
     */
    public function testSoftDeleteAll(){
        $result = $this->YasdPostFlgOnly->softDeleteAll();
        $this->assertTrue($result);

        $result = $this->YasdPostFlgOnly->find('all');
        $this->assertIdentical(count($result), 0);

        $this->YasdPostFlgOnly->disableSoftDeletable();
        $result = $this->YasdPostFlgOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }

    /**
     * testSoftDeleteAllWithConditions
     *
     * jpn: Model::softDeleteAll()のテスト
     */
    public function testSoftDeleteAllWithConditions(){
        $result = $this->YasdPostFlgOnly->softDeleteAll(array('YasdPostFlgOnly.title' => 'Title2'));
        $this->assertTrue($result);

        $result = $this->YasdPostFlgOnly->find('all');
        $this->assertIdentical(count($result), 1);

        $this->YasdPostFlgOnly->disableSoftDeletable();
        $result = $this->YasdPostFlgOnly->find('all');
        $this->assertIdentical(count($result), 2);
    }
}
