<?php

/**
 * @name      phpargv
 * @package   PHP TaMeR
 * @copyright (C) 2009-2010 Dennis T Kaplan
 * @license   GPL v3 {@link http://www.gnu.org/licenses/gpl.html}
 * @author    Dennis T Kaplan
 * @todo
 */

class My_Server_Argv {
    public function run($argv) {
        echo exec('clear'); // You might have to remove this for windows
        $this->argv = $argv;
        $this->remove_self();
        $this->check_help();
        $this->args_array();
        $this->check_args();
        return $this->argv;
    }

    public function set_arg($arg) {
        $this->arg = $arg;
        $this->args[$this->arg]['required'] = FALSE;
    }

    public function set_required($i=FALSE) {
        $this->args[$this->arg]['required'] = $i;
    }

    public function set_value($v) {
        $this->args[$this->arg]['value'][] = $v;
    }

    public function set_text($text) {
        if(!isset($this->text)) {
            $this->text = $text;
        }else{
            $this->text .= PHP_EOL.$text ;
        }
    }

    public function args_view() {
        $r = "\n$this->text\n\nOptions:\n";
        $h = '';
        foreach($this->args as $arg=>$v) {
            $s= '';
            $i='Optional';
            if($v['required'] === TRUE) {
                $i = "Required";
            }
            if(isset($v['value'])) {
                foreach($v['value'] as $value) {
                    $s .= "$value | ";
                }
                $s=trim($s,'| ');
                $r.="\t-$arg=[ $s ] <$i>\n";
                $h .= " $arg=$s";
            }
        }
        $self = __FILE__;
        $r .= "\n\nUsage:\n\t php $self $h\n\n";
        return $this->help = $r;
    }

    private function check_args() {
        if(isset($this->argv) && is_array($this->argv)) {
            foreach($this->args as $key=>$args){
                foreach($args as $k=>$v){
                    if ($k == 'required' && $v == 1) {
                        if(!isset($this->argv[$key][$k])){
                            $this->create_help();
                            exit;
                        }
                    }
                }
            }
        }else {
            $this->create_help();
            exit;
        }
    }

    private function check_help() {
        if (isset($this->argv[1]) && in_array($this->argv[1], array('--help', '-help', '-h', '-?'))) {
            $this->create_help();
            exit;
        }
    }

    private function create_help() {
        $this->args_view();
        $h="\n   This is a command line PHP script.\n";
        $h.= $this->help;
        $h.="\n\n";
        echo $h;
    }

    private function remove_self() {
        if($this->argv[0] == $_SERVER['PHP_SELF']) {
            unset($this->argv[0]);
        }
    }

    function args_array() {
        $t = $this->argv;
        unset($this->argv);
        foreach($t as $v) {
            $this->argv[trim(strstr($v,'=',true),'- ')] = trim(strstr($v,'='),'= ');
        }
        unset($t);
    }
}
