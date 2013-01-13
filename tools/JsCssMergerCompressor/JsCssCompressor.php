<?php
require_once '../../trunk/library/My/Server/Argv.php';
require_once '_JsCssCompressor.php';

$obj = new My_Server_Argv();
$obj->set_text('You must provide at least 1 js file');
$obj->set_arg('files');
$obj->set_required(true);
$obj->set_value('file1.js[,file2.js,file3.js,...]');

$obj->set_text('You must provide the outfile');
$obj->set_arg('output');
$obj->set_required(true);
$obj->set_value('output.js');

$obj->set_text('You can provide base path with out the trading slash');
$obj->set_arg('basepath');
$obj->set_value('basepath');

///js/layout.js"

$result = $obj->run($argv);
$basepath = $result['basepath'];
$files = explode(',', $result['files']);

foreach($files as &$f) {
    $f = $basepath . $f;
    if (!file_exists($f)) {
        throw new Exception("\nThe file $f was not found\n");
    }
}

$output = $result['output'];

$a = new jsCssCompressor();
unlink($output);
$a->makeJs($files, $output);

// or if CSS $a->makeCss($arr,"js/a.js");

/* or
jsCssCompressor::makeJs($arr,"js/a.js");

or if CSS

jsCssCompressor::makeCss($arr,"js/a.js");
   
*/

/*
for compressing from data and output as a file for save.

$data="some string";
header("Content-Disposition: attachment; filename=compressed.js");
echo jsCssCompressor::compressJs($data);

or if CSS

$data="some string";
header("Content-Disposition: attachment; filename=compressed.css");
echo jsCssCompressor::compressCss($data);
*/