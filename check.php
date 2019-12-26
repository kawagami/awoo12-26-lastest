<?php

    function logOut(){
        session_unset();
        header("Location: member_index.php");

    }
    
    function GetSQLValueString($theValue, $theType) {
        switch ($theType) {
          case "string":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_MAGIC_QUOTES) : "";
            break;
          case "int":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_NUMBER_INT) : "";
            break;
          case "email":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_EMAIL) : "";
            break;
          case "url":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_URL) : "";
            break;      
        }
        return $theValue;
      }
?>