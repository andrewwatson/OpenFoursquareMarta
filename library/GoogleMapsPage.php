<?php

class GoogleMapsPage 
{
    private $_html;

    public function __construct($html)
    {
        $this->_html = $html;

    }

	 public function findAllTimes() {
        error_reporting(E_ERROR);
        $dom = DOMDocument::loadHTML($this->_html);

        $tables = $dom->getElementsByTagName('table');
			$times = array();

        foreach ($tables as $table) {
          if ($table->getAttribute('class') == "tppjt") {
              $rows = $table->getElementsByTagName('tr');

        foreach ($rows as $row) {
          $class = $row->getAttribute('class');
             $tds = $row->getElementsByTagName('td');
             $nextTime = "";

             foreach ($tds as $td) {
               $class = $td->getAttribute('class');

               if (preg_match("/tppjdh/", $class)) {
                 $lineName = $td->nodeValue;
               }

               if (preg_match("/time/", $class) && $nextTime == "") {
                 $nextTime = trim($td->nodeValue);
               }
               
             }

             $times[$lineName] = $nextTime;
        }

          }
        }
        


        return ($times);
    }

    public function findNextTime($searchClass)
    {
        error_reporting(E_ERROR);
        $dom = DOMDocument::loadHTML($this->_html);
        $tds = $dom->getElementsByTagName('td');

        $nextTime = 0;

        foreach ($tds as $td) {
            $class = $td->getAttribute('class');
            if ($nextTime == 0 && $class == "time ${searchClass}") {
                $nextTime = trim($td->nodeValue);
            }

        }

        return($nextTime);

    }
}
