<?php

function show($stuff) {
    echo '<pre>';
    print_r($stuff);
    echo '</pre>';
}

function redirect($url) {
    header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
    exit();
}