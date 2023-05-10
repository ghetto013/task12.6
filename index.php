<?php
$example_persons_array = [ 
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getFullnameFromParts($surname, $name, $patronomyc){
    $fullname = [$surname, $name, $patronomyc];
    $fullnameImplode = implode(' ', $fullname);
    return $fullnameImplode;
}

function getPartsFromFullname($fullName){
    $fullnameExplode = explode(" ",$fullName);
    $fullnameArray = [
        'surname' => $fullnameExplode[0],
        'name' => $fullnameExplode[1],
        'patronomyc' => $fullnameExplode[2],
    ] ;
    return $fullnameArray;
}

function getShortName($fullName){
    $split = getPartsFromFullname($fullName);
    $shortName = $split["name"].' '.mb_substr($split["surname"],0,1).".";
    return $shortName;
}

function getGenderFromName($fullName){
    $split = getPartsFromFullname($fullName);
    $gender = 0;

    if (mb_substr($split["patronomyc"],-3,3) == "вна"){
        $gender = -1;
    } elseif (mb_substr($split["patronomyc"],-2,2) == "ич"){
        $gender = 1;
    } else {
        $gender = 0;
    }

    $genderName = mb_substr($split["name"],-1,1);

    if ($genderName == "a"){
        $gender = -1;
    } elseif ($genderName == "й" || $genderName == "н"){
        $gender = 1;
    } else {
        $gender = 0;
    }
    
     if (mb_substr($split["surname"],-2,2) == "ва"){
        $gender = -1;
    } elseif (mb_substr($split["surname"],-1,1) == "в"){
        $gender = 1;
    } else {
        $gender = 0;
    }
    
    if (($gender <=> 0) === 1){
        return "мужчина";
    } elseif (($gender <=> 0) === -1){
        return "женщина";
    } else {
        return "неопределенный пол";
    }
}

function getGenderDescription($array){
    
    $male = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "male");
    });
    $female = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "female");
    });
    $undefined = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "undefined");
    });

    $quantity = count($male) + count($female) + count($undefined);
    $malePercent =  round(count($male) / $quantity * 100,2);
    $femalePercent = round(count($female) / $quantity * 100,2);
    $undefinedPercent = round(count($undefined) / $quantity  * 100,2);

    echo "Гендерный состав аудитории:", "\n";
    echo "---------------------------", "\n";
    echo "Мужчины - $malePercent%","\n";
    echo "Женщины - $femalePercent%", "\n";
    echo "Не удалось определить - $undefinedPercent%";
}

function getPerfectPartner($surname, $name, $patronomyc, $array){

    $surname = mb_convert_case(mb_substr($surname, 0, 1), MB_CASE_UPPER, "UTF-8").mb_convert_case(mb_substr($surname, 1, mb_strlen($surname) -1 ), MB_CASE_LOWER, "UTF-8");
    $name = mb_convert_case(mb_substr($name, 0, 1), MB_CASE_UPPER, "UTF-8").mb_convert_case(mb_substr($name, 1, mb_strlen($name) -1 ), MB_CASE_LOWER, "UTF-8");
    $patronomyc = mb_convert_case(mb_substr($patronomyc, 0, 1), MB_CASE_UPPER, "UTF-8").mb_convert_case(mb_substr($patronomyc, 1, mb_strlen($patronomyc) -1 ), MB_CASE_LOWER, "UTF-8");
    $firstPerson = getFullnameFromParts($surname, $name, $patronomyc);
    $firstGender = getGenderFromName($firstPerson); 
    $shortFirstPerson = getShortName($firstPerson);
    $percent = rand(50,100)+rand(0,99)/100;
    
    $secondPerson = $array[rand(0,count($array)-1)]["fullname"];
    $secondGender = getGenderFromName($secondPerson);
    
    if ($firstGender != $secondGender){
        $shortSecondPerson = getShortName($secondPerson);
        echo "$shortFirstPerson + $shortSecondPerson =", "\n";
        echo "♡ Идеально на $percent% ♡";
    } else {
        echo "Пара не найдена, обновите страницу";
    }
}