<?php
$search_keyword = '2008 land rover range rover sport';
$search_keyword=str_replace(' ','+',$search_keyword);
$newhtml =file_get_html("https://www.google.com/search?q=".$search_keyword."&tbm=isch");
$result_image_source = $newhtml->find('img', 0)->src;
echo '<img src="'.$result_image_source.'">';

?>