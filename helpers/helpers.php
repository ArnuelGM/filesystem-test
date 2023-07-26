<?php

function pj($data, $return = false){
  return print_r(json_encode($data, JSON_PRETTY_PRINT), $return);
}

function pre($data) {
  echo "<pre>";
  echo $data;
  echo "</pre>";
}

function pjpre($data) {
  pre(pj($data, true));
}