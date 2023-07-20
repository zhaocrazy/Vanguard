<?php

namespace Vanguard\Http\Controllers\Web;

use Barryvdh\Debugbar\Twig\Extension\Dump;
use Vanguard\Http\Controllers\Controller;

class LogicalController extends  Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function taskFirst()
    {
        $data = ["D2705B", "D2805C", "F3805C", "F3805F"];
        //$data = ["D2705A","D2705E"];

        $data = array_unique($data);
        foreach ($data as $value) {    //divide into groups
            $head = substr($value, 0, 5);
            foreach ($data as $v) {
                if (preg_match("/$head/", $v)) {
                    $res[$head][] = substr($value, -1);
                    $res[$head] = array_values(array_unique($res[$head]));
                }
            }
        }

        foreach ($res as $key => $value) {
            if (count($value) == 1) {
                $last = substr($value[0], -1);
                for ($i = 65; $i < ord($last); $i++) {
                    $chr[] = strtoupper(chr($i));
                }
                $result[$key] = $key . implode(',', $chr);
                unset($chr);

            } elseif (count($value) == 2) {
                $num = array_map(function ($v) {
                    return ord(substr($v, -1));
                }, $value);
                rsort($num);  // order desc

                // output letter
                for ($i = 65; $i < $num[1]; $i++) {
                    $chr[] = strtoupper(chr($i));
                }
                // output letter
                for ($i = $num[1] + 1; $i < $num[0]; $i++) {
                    $chr[] = strtoupper(chr($i));
                }

                $result[$key] = $key . implode(',', $chr);
                unset($chr);
            }
        }

        dump($result);

    }


    public function taskSecond()
    {

        $input = "()";
        //$input = "()[]{}";
        //$input = "(]";     // invalidate
        //$input = "([)]"; //invalidate
        //$input = "{[]}";

        $string = [
            '{' => '}',
            '}' => '{',
            '[' => ']',
            ']' => '[',
            '(' => ')',
            ')' => '('];

        if (strlen($input) % 2 == 1) {
            dump($input . '-is-' . 'invalidate');
            return false;
        }

        $flag = 0;
        for ($i = 0; $i < strlen($input) / 2; $i++){
            //matches the side string
            if ($i/2 == 0 &&  $input[$i + 1] == $string[$input[$i]]) {
                $flag = 1;
            }
            //matches the corresponding string
            if ($input[strlen($input) - ($i+1)] == $string[$input[$i]]) {
                $flag = 1;
            }
        }
        dump($flag ? $input.'-is-'. 'validate' : $input.'-is-'. 'invalidate');
    }

}