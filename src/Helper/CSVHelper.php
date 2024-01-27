<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Helper
 *
 * The CSVHelper helps to convert an array or object to a CSV string. And also to download or save it.
 */

 namespace zeroline\MiniLoom\Helper;

final class CSVHelper
{
    /**
     * Uses the given data to build a CSV string.
     * The first row will be the column names except if you provide them within the columns array.
     * Via the functions array you can provide functions to format the data. Use the column name as the key and the function as the value.
     * The ignore array can be used to ignore columns.
     * Change the capsule, separator and lineend to your needs.
     * The extraFunctions array can be used to provide functions that need more than one column. Use the column name as
     * the key and an array with the column name and the function as the value.
     *
     * If no data is given an empty CSV string will be returned.
     *
     * You may notice the chr(255) . chr(254) . mb_convert_encoding($csv_content, "UCS-2LE", "auto") part.
     * This is needed to make sure that the CSV file is encoded in UTF-16LE. This is needed for Excel to open the file correctly.
     * Today I would recommend to use UTF-8 instead. But this is the way I did it back then.
     * In the good old days people tried to open the generated CSV files with Excel and Excel had problems with UTF-8.
     * Today you would use the data import functions of Excel and there you can specify the encoding.
     * But back then people just 'double clicked' the CSV file and expected it to open in Excel.
     *
     * @param array<string> $columns
     * @param array<int, mixed> $data
     * @param array<string, callable> $functions
     * @param array<string> $ignore
     * @param string $capsule
     * @param string $separator
     * @param string $lineend
     * @param array<string, callable> $extraFunctions
     * @return string
     */
    public static function build(
        array $columns,
        array $data,
        array $functions = array(),
        array $ignore = array(),
        string $capsule = '"',
        string $separator = ';',
        string $lineend = PHP_EOL,
        array $extraFunctions = array()
    ) : string {
        $csv_content = "";
        $deepDataIndicator = '.';
        if (count($data) == 0) {
            return chr(255) . chr(254) . mb_convert_encoding($csv_content, "UCS-2LE", "auto");
        }

        if (sizeof($columns) == 0) {
            foreach ($data[0] as $key => $value) {
                if (in_array($key, $columns)) {
                    continue;
                }
                if (in_array($key, $ignore)) {
                    continue;
                }

                if (is_array($value)) {
                    foreach ($value as $subKey => $subValue) {
                        if (in_array($key . $deepDataIndicator . $subKey, $columns)) {
                            continue;
                        }
                        if (in_array($key . $deepDataIndicator . $subKey, $ignore)) {
                            continue;
                        }
                        $columns[$key . $deepDataIndicator . $subKey] = $key . $deepDataIndicator . $subKey;
                    }
                } else {
                    $columns[$key] = $capsule.$key.$capsule;
                }
            }
        } else {
            foreach ($columns as $key => $value) {
                $columns[$key] = $capsule.$value.$capsule;
            }
        }

        $csv_content .= implode($separator, array_values($columns)) . $lineend;

        foreach ($data as $event) {
            $row = array();
            $event = (object)$event;
            foreach ($columns as $column => $title) {
                if (in_array($column, $ignore)) {
                    continue;
                }
                if (array_key_exists($column, $functions)) {
                    $row[] = $capsule . $functions[$column]($event->{$column}) . $capsule;
                } else {
                    if (strpos($column, $deepDataIndicator) !== false) {
                                list($firstKey, $secondKey) = explode($deepDataIndicator, $column);
                        if (isset($event->{$firstKey}->{$secondKey})) {
                            $row[] = $capsule . $event->{$firstKey}->{$secondKey} . $capsule;
                        } else {
                            $row[] = null;
                        }
                    } else {
                        if (isset($event->{$column})) {
                            $row[] = $capsule . $event->{$column} . $capsule;
                        } else {
                            $row[] = null;
                        }
                    }
                }
            }
            $csv_content .= implode($separator, $row) . $lineend;
        }
        return chr(255) . chr(254) . mb_convert_encoding($csv_content, "UCS-2LE", "auto");
    }

    /**
     * Uses the given data to build a CSV string and downloads it.
     *
     * @param array<string> $columns
     * @param array<int, mixed> $data
     * @param array<string, callable> $functions
     * @param array<string> $ignore
     * @param string $filename
     * @param string $capsule
     * @param string $separator
     * @param string $lineend
     * @param array<string, callable> $extraFunctions
     * @return void
     */
    public static function download(
        array $columns,
        array $data,
        array $functions = array(),
        array $ignore = array(),
        string $filename = 'Export.csv',
        string $capsule = '"',
        string $separator = ';',
        string $lineend = PHP_EOL,
        array $extraFunctions = array()
    ) : void {
        header('Content-Type: text/x-csv');
        header("Content-Disposition: attachment; filename=\"" . $filename . "\";");
        echo static::build($columns, $data, $functions, $ignore, $capsule, $separator, $lineend, $extraFunctions);
        exit;
    }

    /**
     * Uses the given data to build a CSV string and saves it.
     *
     * @param array<string> $columns
     * @param array<int, mixed> $data
     * @param array<string, callable> $functions
     * @param array<string> $ignore
     * @param string $filename
     * @param string $capsule
     * @param string $separator
     * @param string $lineend
     * @param array<string, callable> $extraFunctions
     * @return void
     */
    public static function save(
        array $columns,
        array $data,
        array $functions = array(),
        array $ignore = array(),
        string $filename = 'Export.csv',
        string $capsule = '"',
        string $separator = ';',
        string $lineend = PHP_EOL,
        array $extraFunctions = array()
    ) : void {
        file_put_contents($filename, static::build($columns, $data, $functions, $ignore, $capsule, $separator, $lineend, $extraFunctions));
    }
}
