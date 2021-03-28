<?php
    $itemjson = file_get_contents('https://raw.githubusercontent.com/NielsTack/runescape-3-grand-exchange-item-id-scraper/main/items.json');
    $itemids = json_decode($itemjson, true);
    $items = [];

    $html =  file_get_contents('https://runescape.wiki/w/Calculator:Grand_Exchange_buying_limits#Z');
    $dom = new DomDocument();
    @ $dom->loadHTML($html);
    $xpath = new DOMXpath($dom);
    $tables = $xpath->query("//table[contains(@class,'wikitable')]");
    $count = $tables->length;
    foreach($tables as $table) {
        foreach($table->getElementsByTagName('tr') as $tablerow) {
            $itemname =  $tablerow->getElementsByTagName('td')[0]->nodeValue;
            $buylimit = $tablerow->getElementsByTagName('td')[1]->nodeValue;
            if($itemname != 'Undefined' && $buylimit != 'Undefined' && $buylimit != '' && $itemname != '' && $buylimit != null && $itemname != null){
                $key = $itemids[$itemname];
                $items[$key] = $buylimit;
            }
        }
    }
 
    $json = json_encode($items);

    //write json to file
    if (file_put_contents("buylimits.json", $json)) {
        echo nl2br("JSON file created successfully...\n");
    } else {
        echo nl2br("Oops! Error creating json file...\n");
    }
    echo $json;

?>