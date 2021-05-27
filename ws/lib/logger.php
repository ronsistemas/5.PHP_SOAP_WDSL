<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function _log($stmt)
{
   file_put_contents("ws-errors.log", '[INFO] '.$stmt.PHP_EOL,FILE_APPEND);        
}