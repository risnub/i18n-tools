<?php
require_once dirname( __FILE__ ) . '/not-gettexted.php';
require_once dirname( __FILE__ ) . '/pot-ext-meta.php';
require_once dirname( __FILE__ ) . '/extract/extract.php';

if ( !defined( 'STDERR' ) ) {
    define( 'STDERR', fopen( 'php://stderr', 'w' ) );
}

class MakePOT_Messages extends MakePOT {
    
    var $rules = array(
        '_m' => array('string'),
        '_mn' => array('singular', 'plural')
    );

    
}

// run the CLI only if the file
// wasn't included
$included_files = get_included_files();
if ($included_files[0] == __FILE__) {
    $makepot = new MakePOT_Messages;
    if ((3 == count($argv) || 4 == count($argv)) && in_array($method = str_replace('-', '_', $argv[1]), get_class_methods($makepot))) {
        $res = call_user_func(array(&$makepot, $method), realpath($argv[2]), isset($argv[3])? $argv[3] : null);
        if (false === $res) {
            fwrite(STDERR, "Couldn't generate POT file!\n");
        }
    } else {
        $usage  = "Usage: php makepot.php PROJECT DIRECTORY [OUTPUT]\n\n";
        $usage .= "Generate POT file from the files in DIRECTORY [OUTPUT]\n";
        $usage .= "Available projects: ".implode(', ', $makepot->projects)."\n";
        fwrite(STDERR, $usage);
        exit(1);
    }
}
