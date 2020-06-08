<?php

use yii\db\Migration;

/**
 * Class m200608_135425_increase_hash_column_size_in_user_sessions_table
 */
class m200608_135425_increase_hash_column_size_in_user_sessions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%user_sessions}}','hash',$this->string(800));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200608_135425_increase_hash_column_size_in_user_sessions_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200608_135425_increase_hash_column_size_in_user_sessions_table cannot be reverted.\n";

        return false;
    }
    */
}
