<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">


<?php

require_once 'vendor/autoload.php';
use ZxcvbnPhp\Zxcvbn;

function generate_password ($length, $digits, $special) {
    $letters = 'abcdefghijklmnopqrstuvwxyz';
    $lettersUpper = strtoupper($letters);
    $digitsChars = '1234567890';
    $specialChars = '#$%&\'()*+,-./:;<=>?@[\]^_`{|}~';

    $factory = new RandomLib\Factory;
    $generator = $factory->getMediumStrengthGenerator();
    $options = '';

    if ($digits) {
        if ($special) {
            $options = $letters . $lettersUpper . $specialChars;
        } else {
            $options = $letters . $lettersUpper . $digitsChars;
        }
    } else {
        if ($special) {
            $options = $letters . $lettersUpper . $special;
        } else {
            $options = $letters . $lettersUpper;
        }
    }   
    
    $randpass = $generator->generateString($length, $options);
    return utf8_encode($randpass);
}

function generate_userid () {

    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $userid = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 12; $i++) {
        $n = rand(0, $alphaLength);
        $userid[] = $alphabet[$n];
    }
    return implode($userid);
}

function hash_pw ($string) {

    $password = utf8_encode($string);
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    return $hashed;
}

function check_pw ($string, $hash) {
    
    return password_verify($string, $hash);
}

function check_password_strength($password) {
    $zxcvbn = new Zxcvbn();
    $strenght = $zxcvbn->passwordStrength($password)['score'];
    $strenghtMessage = '';
    switch ($strenght) {
        case 0:
            $strenghtMessage = 'very weak';
            break;
        
        case 1:
            $strenghtMessage = 'weak';
            break;
        
        case 2:
            $strenghtMessage = 'medium';
            break;
            
        case 3:
            $strenghtMessage = 'strong';
            break;
            
        case 4:
            $strenghtMessage = 'very strong';
            break;
            
        default:
            $strenghtMessage = 'calc not avaliable';
            break;
    }
    return $strenghtMessage;
}