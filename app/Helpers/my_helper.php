<?php

function getYouTubeId($url)
{
    preg_match('/(youtu\.be\/|v=)([^&]+)/', $url, $matches);
    return $matches[2] ?? null;
}
