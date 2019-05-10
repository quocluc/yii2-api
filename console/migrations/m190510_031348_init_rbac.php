<?php

use yii\db\Migration;

/**
 * Class m190510_031348_init_rbac
 */
class m190510_031348_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */

    // Use up()/down() to run migration code without a transaction.
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);
        // add "createPost" permission
        $staff = $auth->createRole('staff');
        $staff->description = 'Staff';
        $auth->add($staff);


        $auth->addChild($admin, $staff);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($staff, 2);
        $auth->assign($admin, 1);


    }

    public function safeDown()
    {
        echo "m190510_031348_init_rbac cannot be reverted.\n";

        return false;
    }

}
