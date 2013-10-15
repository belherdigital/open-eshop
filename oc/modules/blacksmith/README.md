# Blacksmith (BETA)
A MySQL database manipulation module for Kohana 3


## Create a table
	
	$blacksmith = Blacksmith::create();
	$table = $blacksmith->table(Blacksmith_Table::IF_NOT_EXISTS, 'people');
	$table->increments('id');
	$table->string('email', 100)->default_value('example@example.com');
	$table->string('password', 50);
	$blacksmith->forge();


## Alter/Modify a column
	
	$blacksmith = Blacksmith::alter();
	$table = $blacksmith->table('people');
	$table->modify_column()->string('password', 255);
	$blacksmith->forge();

## Drop a column
	
	$blacksmith = Blacksmith::alter();
	$table = $blacksmith->table('people');
	$table->drop_column('password');
	$blacksmith->forge();

## Alter/Add a column
	
	$blacksmith = Blacksmith::alter();
	$table = $blacksmith->table('people');
	$table->add_column()->string('password');
	$blacksmith->forge();

## Drop a table
	$blacksmith = Blacksmith::drop();
	$blacksmith->table(Blacksmith_Table::IF_EXISTS, 'people');
	$blacksmith->forge();
