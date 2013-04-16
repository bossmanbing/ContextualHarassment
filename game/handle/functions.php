<?php

function sanitize($string){
  $string = htmlentities($string);
  $string = mysql_real_escape_string($string);

  return $string;
}

function mysql_get_var($query,$y=0){
       $res = mysql_query($query);
       $row = mysql_fetch_array($res);
       mysql_free_result($res);
       $rec = $row[$y];
       return $rec;
}

?>