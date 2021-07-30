<?php

function get_domain($str) {
    if (gettype($str) !== 'string') return '';
    $parsed = parse_url($str);
    if (gettype($parsed) !== 'array' || !isset($parsed['host'])) return '';
    $domain = $parsed['host'];
    if (substr($domain, 0, 4) === 'www.') $domain = substr($domain, 4);
    return $domain;
}