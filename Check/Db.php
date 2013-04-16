<?php
require '../Vhmis/Db/MySQL/Apdater.php';
require '../Vhmis/Db/MySQL/Statement.php';
require '../Vhmis/Db/MySQL/Result.php';

use \Vhmis\Db\MySQL as Db;

$config = array(
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '123',
    'db' => 'viethanit_hrm',
    'auto' => true
);

$db = new Db\Apdater($config);

$statement = $db->createStatement('select * from hrm_nhansu where id > :id', array(':id' => 200));

$result = $statement->execute(/* array(array(':id', 5)) */);

/*while ($row = $result->next()) {
    print_r($row);
}*/

echo $result->count();
echo $result->getLastValue();

$statement = $db->createStatement('insert into hrm_chucvu values (null, :ten)');

$result = $statement->execute(array(array(':ten', 'Mot 2 Ba Bon Nam'), array(':ten', 'Mot 7 Ba Bon Nam')));

echo $result->count();
echo $result->getLastValue();