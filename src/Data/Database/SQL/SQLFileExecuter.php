<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Database\SQL;

use zeroline\MiniLoom\Data\Database\SQL\BaseRepository as BaseRepository;
use Exception;

final class SQLFileExecuter
{
    /**
     * Read and execute an sql file. All found queries are executed
     * within a transaction environment. If one query fails no query will be
     * commited.
     * Transactions may not be suitable for all queries. Altering tables may not
     * benefit from transactions.
     *
     * @param string $sqlFilename
     * @param string $connection
     *
     * @throws Exception on script exec error
     *
     * @return bool
     */
    public static function loadAndExecute(string $sqlFilename, bool $useTransaction = false, ?string $connection = null) : bool
    {
        if (!is_readable($sqlFilename)) {
            throw new Exception('SQL file "' . $sqlFilename . '" is not readable.');
        }
        $repository = new BaseRepository();
        if(!is_null($connection)) {
            $repository->setConnection($connection);
        }

        if($useTransaction) {
            $repository->beginTransaction();
        }

        $result = null;
        $query = '';
        $fileLines = file($sqlFilename);
        foreach ($fileLines as $line) {
            $startWith = substr(trim($line), 0, 2);
            $endWith = substr(trim($line), -1, 1);
            if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                continue;
            }

            $query = $query . $line;
            if ($endWith == ';') {
                $result = $repository->executeRaw($query);
                if ($result === false) {
                    if($useTransaction) {
                        $repository->rollbackTransaction();
                    }
                    
                    throw new Exception('Execution of SQL script file "' . $sqlFilename . '" aborted. Failure at script line "' . $query . '"');
                    break;
                }
                $query = '';
            }
        }

        if($useTransaction) {
            if ($result === true) {
                $repository->commitTransaction();
            } else {
                $repository->rollbackTransaction();
            }
        }

        return $result;
    }
}
