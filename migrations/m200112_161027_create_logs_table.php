<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%logs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%countries}}`
 */
class m200112_161027_create_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%logs}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'particulars' => $this->text(),
            'time' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'ip' => $this->string(64)->notNull(),
            'useragent' => $this->string(800)->notNull(),
            'type' => $this->string(100),
            'data' => $this->text(),
            'ip_country_id' => $this->integer(),
        ],\app\common\Constants::DB_TABLE_OPTIONS);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-logs-user_id}}',
            '{{%logs}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-logs-user_id}}',
            '{{%logs}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `ip_country_id`
        $this->createIndex(
            '{{%idx-logs-ip_country_id}}',
            '{{%logs}}',
            'ip_country_id'
        );

        // add foreign key for table `{{%countries}}`
        $this->addForeignKey(
            '{{%fk-logs-ip_country_id}}',
            '{{%logs}}',
            'ip_country_id',
            '{{%countries}}',
            'id',
            'CASCADE'
        );



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-logs-user_id}}',
            '{{%logs}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-logs-user_id}}',
            '{{%logs}}'
        );

        // drops foreign key for table `{{%countries}}`
        $this->dropForeignKey(
            '{{%fk-logs-ip_country_id}}',
            '{{%logs}}'
        );

        // drops index for column `ip_country_id`
        $this->dropIndex(
            '{{%idx-logs-ip_country_id}}',
            '{{%logs}}'
        );

        $this->dropTable('{{%logs}}');
    }
}
