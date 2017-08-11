<?php

namespace Fanaticpost;

error_reporting(E_ALL);
error_reporting(-1);


# http://fanaticpost.flywheelsites.com/wp-content/themes/x-child/lib/importer/import-teams.php



require dirname(__FILE__) . '/../../../../../wp-load.php';
require dirname(__FILE__) . '/importer.php';
require dirname(__FILE__) . '/../phpexcel/PHPExcel.php';

class ImportTeam extends Importer {

    public static function init() {

        $file = dirname(__FILE__) . '/teams-mlb.csv';
        self::setTableKeys();
        $data = static::parseFile( $file );
        self::importData( $data );
    }

    public static function setTableKeys() {
        $columns = [
            'team name' => 'team name',
            'short name' => 'short name',
            'font color' => 'font color',
            'background color' => 'background color',
        ];
        $columnsExtra = [
        ];
        $columns = array_merge( $columns, $columnsExtra );

        static::$keys = static::setImportKeys( array_flip($columns) );
    }
    public static function importData( $rows = [] ) {
        if( $rows ) {
            foreach( $rows as $row ) :

                $team = fsu_get_post_by_name( $row['team name'], 'team' );
                vard($team);
                #vard($row);
                #echo $row['team name'];

                if( $team ) {
                    # team name,short name,background color,font color
                    update_post_meta( $team->ID, 'wpcf-team-short-name', $row['short name'] );
                    update_post_meta( $team->ID, 'wpcf-team-background-color', $row['background color'] );
                    update_post_meta( $team->ID, 'wpcf-team-font-color', $row['font color'] );
                }

            endforeach;
        }
    }
}

ImportTeam::init();