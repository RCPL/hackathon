<?php
/*function randLetter() {
  $int = rand(0,51);
  $a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $rand_letter = $a_z[$int];
  return $rand_letter;
}*/
/*
 * Helper function to get the url for a syndetics image.
 *
 * @param $isbn
 * @param $size
 *   Size of the desired image.  May be one of the following:
 *   'SC' (small), 'MC' (medium), 'LC' (large)
 * @param $format
 *   Format of the desired image (e.g. jpg, gif, etc.)
 */
function pac_syndetics_image_url($isbn = '', $oclc = '', $upc = '', $size = 'MC', $format = 'jpg') {
  $isbn = preg_replace('/ \(.*\)/U', '', $isbn);
  return 'https://secure.syndetics.com/index.aspx?isbn=' . $isbn . '/' . $size . '.' . $format . '&client=richlandlib&upc=' . $upc . '&oclc=' . $oclc;
}

//truncate a string only at a whitespace (by nogdog)
function truncate($text, $length) {
   $length = abs((int)$length);
   if(strlen($text) > $length) {
      $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
   }
   return($text);
}

?>
