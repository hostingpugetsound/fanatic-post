<?php

namespace Fanaticpost;

use PHPExcel_IOFactory;

class Importer {
    public static $keys;

    public static function init() {

    }


    /**
     * Sets the keys array. Which converts the meta_key names used between the file and the data in the DB
     * @param $keys
     */
    public static function setImportKeys( $keys ) {
        if( is_array($keys) ) {
            self::$keys = $keys;
        } elseif( file_exists($keys) ) {
            $file = file_get_contents( $keys );
            $keys = json_decode( $file, true );
            self::$keys = $keys;
        }
        return $keys;
    }
    # echos out an array to use
    public static function getHeaders( $file, $string = false ) {
        $phpExcel = PHPExcel_IOFactory::load( $file );
        $worksheet = $phpExcel->getActiveSheet();
        //excel with first row header, use header as key
        $highestColumn = $worksheet->getHighestColumn();
        $headingsArray = $worksheet->rangeToArray( 'A1:' . $highestColumn . '1', null, true, true, true );
        if( $string )
            return "['" . implode("', '", $headingsArray[1]) . "']";
        else
            return $headingsArray[1];
    }
    /**
     * Loads a file, and gets it ready for parsing. Checks for CSV
     *
     * @todo: do a better job at checking for csv
     * @param $file
     *
     * @return mixed
     */
    public static function loadFile( $file ) {
        $filetype = wp_check_filetype( basename( $file ), null );
        $csv = $filetype['type'] == 'text/csv' ? true : false;
        if( $csv ) {
            $inputFileType = 'CSV';
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $phpExcel = $objReader->load($file);
        } else {
            $phpExcel = PHPExcel_IOFactory::load( $file );
        }
        $worksheet = $phpExcel->getActiveSheet();
        return $worksheet;
    }
    /**
     * Parses a file into an array
     * https://gist.github.com/calvinchoy/5821235
     * @param string $file      Filename
     * @param bool $useHeaders  If there's a header column at row 1, use that as metakeys
     *
     * @return array
     */
    public static function parseFile( $file, $useHeaders = true ) {
        $worksheet = self::loadFile( $file );
        //excel with first row header, use header as key
        if ( $useHeaders ) {
            $highestRow    = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $headingsArray = $worksheet->rangeToArray( 'A1:' . $highestColumn . '1', null, true, true, true );
            $headingsArray = $headingsArray[1];
            $dataArray     = array();
            $r             = - 1;
            for ( $row = 2; $row <= $highestRow; ++ $row ) {
                $dataRow = $worksheet->rangeToArray( 'A' . $row . ':' . $highestColumn . $row, null, true, true, true );
                if ( ( isset( $dataRow[ $row ]['A'] ) ) && ( $dataRow[ $row ]['A'] > '' ) ) {
                    ++ $r;
                    foreach ( $headingsArray as $columnKey => $columnHeading ) {
                        # if keys are set, use them to replace the existing headers
                        if( self::$keys && isset(self::$keys[$columnHeading]) ) {
                            $columnHeading = self::$keys[$columnHeading];
                        }
                        $dataArray[ $r ][ $columnHeading ] = $dataRow[ $row ][ $columnKey ];
                    }
                }
            }
        } else {
            $dataArray = $worksheet->toArray( null, true, true, true );
        }
        return $dataArray;
    }
}


#\Fanaticpost\Importer::init();