<?php defined('SYSPATH') or die('No direct access allowed!'); 
 
class CreateTest extends Kohana_UnitTest_TestCase 
{ 
    public function test_create_table() 
    {
		$blacksmith = Blacksmith::create();
		$table = $blacksmith->table(Blacksmith_Table::IF_NOT_EXISTS, 'people');
		$table->increments('id');
		$table->string('email', 100)->default_value('example@example.com');
		$table->string('password', 50);
		$create_sql = $blacksmith->sql();

        $this->assertEquals($create_sql, "CREATE TABLE IF NOT EXISTS `people` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, `email` VARCHAR(100) DEFAULT 'example@example.com', `password` VARCHAR(50), PRIMARY KEY (`id`))"); 
    } 
}