<?php

require_once __DIR__ . "/../../core/Database.php";

class Model
{
    protected PDO $DB;

    public function __construct()
    {
        $this->DB = Database::getConnection();
    }

    public static function prepareWhereArray(array $wheres = [])
    {
        $wheresQuery = "";

        if (!empty($wheres)) {


            $wheresQuery .= "WHERE ";
            $counter = 1;
            foreach ($wheres as $where /* Array */) {
                if ($counter > 1) {
                    if (isset($where[3])) {
                        $wheresQuery .= " {$where[3]} ";
                    } else {
                        $wheresQuery .= " AND ";
                    }
                }

                $wheresQuery .= "{$where[0]} {$where[1]} '{$where[2]}'";

                $counter++;
            }
        }

        return $wheresQuery;
    }
}
