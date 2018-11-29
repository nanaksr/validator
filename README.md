# PHP FormValidator
Simple PHP Validator

## Installation
Via composer:
```
composer require nanaksr/validator
```
#Requirements
PHP Validator works with PHP  >= 5.6

## Typical Use
```
require __DIR__ . '/vendor/autoload.php';

use nanaksr\validator;

$params = []; //From your HTML Form parameters

$vldtn = new vldtn('id');
$vldtn->params($params);
$vldtn->setParamKey('nama_lengkap')->setName('Nama Lengkap')->setRule('ValidateFullName');//validate existing rules
$vldtn->setParamKey('email')->setName('Alamat Email')->setRule('ValidateEmail');//validate existing rules
$vldtn->setParamKey('bank_option')->setName('Bank Tujuan')->setRule(['BCA','BNI','BRI','MANDIRI']); //option value
$vldtn->setParamKey('customs')->setName('Custom Regex')->setRule('/^(your_regex_pattern)$/'); //custom regex pattern
$vldtn->setParamKey('password')->setName('Buat Password')->setRule('ValidateAlnum'); //Alpha, Numeric, and keyboard character
$vldtn->setParamKey('re_password')->setName('Konfirmasi Password')->equal('password'); //equal param, usually for password
$vldtn->unsetParamKey('email');//if you don't want to be displayed some param

if($vldtn->hasError()){
    print_r($vldtn->getErrors())
}

$fields = $vldtn->getResults();
```
