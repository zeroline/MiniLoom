<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Database\SQL;

 enum DatabaseType : string 
 {
    case MYSQL = 'mysql';
    case SQLITE = 'sqlite';
    case SQLITE2 = 'sqlite2';
    case SQLITE3 = 'sqlite3';
    case PGSQL = 'pgsql';
    case SQLSRV = 'sqlsrv';
    case DBLIB = 'dblib';
    case MSSQL = 'mssql';
    case SYBASE = 'sybase';
    case FIREBIRD = 'firebird';
 }