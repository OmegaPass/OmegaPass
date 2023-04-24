<?php

require_once 'vendor/autoload.php';
use ZxcvbnPhp\Zxcvbn;
use Fernet\Fernet;

function generate_password($length, $digits, $special) {
    $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $digitsChars = '1234567890';
    $specialChars = '#$%&\'()*+,-./:;<=>?@[\]^_`{|}~';

    $factory = new RandomLib\Factory;
    $generator = $factory->getMediumStrengthGenerator();

    switch (true) {
        case ($digits && $special):
            $options = $letters . $digitsChars . $specialChars;
            break;
        case ($digits):
            $options = $letters . $digitsChars;
            break;
        case ($special):
            $options = $letters . $specialChars;
            break;
        default:
            $options = $letters;
            break;
    }

    // TODO: issue that string without digits resolved with this. But don't know if this happens with other parameters

    do {
        $randpass = $generator->generateString($length, $options);
    } while(
        !is_string(
            strpbrk($randpass, $digitsChars)
        )
    );

    return $randpass;
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
    $strength = $zxcvbn->passwordStrength($password)['score'];
    $strengthMessage = [
        'very weak',
        'weak',
        'medium',
        'strong',
        'very strong'
    ];

    return $strengthMessage[$strength] ?? 'calc not avaliable';
}

function encrypt($password, $masterPass) {
    $key = hash('sha256', $masterPass, true);
    $fernet = new Fernet(base64url_encode($key));

    return $fernet->encode($password);
}

function decrypt($encryted, $masterPass) {
    $key = hash('sha256', $masterPass, true);
    $fernet = new Fernet(base64url_encode($key));

    return $fernet->decode($encryted);
}

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}
