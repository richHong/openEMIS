<?php
App::uses('AppHelper', 'View/Helper');

class CsvHelper extends AppHelper {
   protected $_df;

    function newCsv($name, $return)
    { 

        if( ! $name )
        {
            $name = md5(uniqid() . microtime(TRUE) . mt_rand()). '.csv';
        }

        if(!$return){
          header('Expires: 0');
          header('Content-Encoding: UTF-8');
          // force download  
          header("Content-type: text/csv; charset=utf-8");
          header("Content-type: text/x-csv");
          // disposition / encoding on response body
          header('Content-disposition:attachment;filename="'.$name.'"');


          header("Content-Transfer-Encoding: binary");
        }else{
            ob_start();
        }
        $this->_df = fopen("php://output", 'w+');
       
    }

    function setRow($row){
       $this->fputcsv($this->_df, $row);
    }

    function output($name=null)
    {
        fclose($this->_df);
    }

    function get_file_contents(){
       $output = ob_get_contents();
       ob_end_clean();
        fclose($this->_df);
       return $output;
    }

    function fputcsv(&$handle, $fields = array(), $delimiter = ',', $enclosure = '"') {
        $str = '';
        $escape_char = '\\';
        foreach ($fields as $value) {
          if (strpos($value, $delimiter) !== false ||
              strpos($value, $enclosure) !== false ||
              strpos($value, "\n") !== false ||
              strpos($value, "\r") !== false ||
              strpos($value, "\t") !== false ||
              strpos($value, ' ') !== false) {
            $str2 = $enclosure;
            $escaped = 0;
            $len = strlen($value);
            for ($i=0;$i<$len;$i++) {
              if ($value[$i] == $escape_char) {
                $escaped = 1;
              } else if (!$escaped && $value[$i] == $enclosure) {
                $str2 .= $enclosure;
              } else {
                $escaped = 0;
              }
              $str2 .= $value[$i];
            }
            $str2 .= $enclosure;
            $str .= $str2.$delimiter;
          } else {
            $str .= $value.$delimiter;
          }
        }
        $str = substr($str,0,-1);
        $str .= "\n";
        return fwrite($handle, $str);
    }
}