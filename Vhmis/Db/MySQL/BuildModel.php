<?php

namespace Vhmis\Db\MySQL;

class BuildModel
{
    /**
     *
     * @var \Vhmis\Db\MySQL\Adapter
     */
    protected $adapter;
    protected $namespace;
    protected $path;

    public function __construct($config, $namespace, $path)
    {
        $config['db'] = 'information_schema';

        $this->adapter = new Adapter($config);
        $this->namespace = $namespace;
        $this->path = $path;
    }

    public function build($database)
    {
        $dbInfo = $this->readDatabaseMetadata($database);

        foreach ($dbInfo['tables'] as $table) {
            $tableInfo = $this->buildModelEntity($database, $table['name']);
        }
    }

    public function buildModelEntity($database, $table)
    {
        $info = $this->readTableMetadata($database, $table);

        $content = '<?php' . "\n";

        $content .= 'namespace ' . $this->namespace . '\\' . static::camelCase($table, true) . ';' . "\n";

        $content .= 'class Entity {' . "\n";

        $properties = array();
        $getterAndSetter = array();

        foreach ($info['columns'] as $col) {
            $properties[] = '/**' . "\n"
                . '* ' . $col['comment'] . "\n"
                . '*/' . "\n"
                . 'protected $' . $col['phpName'] . ';' . "\n";

            $getterAndSetter[] = '/**' . "\n"
                . '* Get ' . $col['name'] . "\n"
                . '*' . "\n"
                . '* ' . $col['comment'] . "\n"
                . '*/' . "\n"
                . 'public function get' . ucfirst($col['phpName']) . '() {' . "\n"
                . 'return $this->' . $col['phpName'] . ';' . "\n"
                . '}' . "\n\n"
                . '/**' . "\n"
                . '* Set ' . $col['name'] . "\n"
                . '*' . "\n"
                . '* ' . $col['comment'] . "\n"
                . '*/' . "\n"
                . 'public function set' . ucfirst($col['phpName']) . '($' . $col['phpName'] . ') {' . "\n"
                . '$this->' . $col['phpName'] . ' = $' . $col['phpName'] . ';' . "\n"
                . 'return $this;' . "\n"
                . '}' . "\n";
        }

        $content .= implode("\n", $properties);

        $content .= implode("\n", $getterAndSetter);

        $content .= '}' . "\n";

        file_put_contents($this->path . static::camelCase($table, true) . '.php', $content);

        echo $table . ' : done<br>' . "\n";
    }

    public function readDatabaseMetadata($database)
    {
        $stm = $this->adapter->createStatement('SELECT * FROM `TABLES` WHERE `TABLE_SCHEMA` LIKE :db',
            array(':db' => $database));
        $rows = $stm->execute();
        $tables = array();

        while ($row = $rows->next()) {
            if ($row['TABLE_TYPE'] == 'BASE TABLE') {
                $tables[] = array(
                    'name' => $row['TABLE_NAME'],
                    'comment' => $row['TABLE_COMMENT']
                );
            }
        }

        return array('tables' => $tables);
    }

    public function readTableMetadata($database, $table)
    {
        $stm = $this->adapter->createStatement('SELECT * FROM `COLUMNS` WHERE `TABLE_SCHEMA` LIKE :db AND `TABLE_NAME` LIKE :tbl',
            array(
            ':db' => $database,
            ':tbl' => $table
            )
        );

        $rows = $stm->execute();

        $columns = array();

        while ($row = $rows->next()) {
            $columns[] = array(
                'phpName' => static::camelCase($row['COLUMN_NAME']),
                'name' => $row['COLUMN_NAME'],
                'comment' => $row['COLUMN_COMMENT'],
                'type' => $row['DATA_TYPE']
            );
        }

        return array('columns' => $columns);
    }

    static public function camelCase($str, $ucfirst = false)
    {
        $parts = explode('_', $str);
        $parts = $parts ? array_map('ucfirst', $parts) : array($str);
        $parts[0] = $ucfirst ? ucfirst($parts[0]) : lcfirst($parts[0]);
        return implode('', $parts);
    }
}