<?php

function file_CSS($pathFile){
    if(!is_array($pathFile)){
        echo '<link rel="stylesheet" href="' . STATIC_FILE . $pathFile . '">';
        return;
    }
    
    foreach ($pathFile as $key => $value) {
        echo '<link rel="stylesheet" href="' . STATIC_FILE . $value . '">';
    }
}

function file_JS($pathFile){
    if(!is_array($pathFile)){
        echo '<script src="' . STATIC_FILE . $pathFile . '"></script>';
        return;
    }

    foreach ($pathFile as $key => $value) {
        echo '<script src="' . STATIC_FILE . $value . '"></script>';
    }
}

function file_Image(String $pathFile, Array $params = []){
    $cod = '';
    foreach ($params as $key => $value) {
        $cod .= $key.'="'.$value.'" ';
    }
    echo '<img  src="'. STATIC_FILE . $pathFile .'" ' . $cod .'>';
}

function redirect(String $path){
    echo BASE_URL.$path;
}

function base_url_js(){
    echo '<script>const $base_url="'. BASE_URL .'";</script>';
}

function defaultHeader(){
    file_CSS(FILE_DEFAULT_HEADER);
}

function defaultBody(){
    base_url_js();
    file_JS(FILE_DEFAULT_BODY);
}

function debug ($json){
    echo '<pre>';
    var_dump($json);
    echo '</pre>';
    die();
};
