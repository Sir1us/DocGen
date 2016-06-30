<?php
error_reporting(E_ALL);

/**
 * Подключаеться фаил xml
 * Сериализация xml в array
 */
if (file_exists('bands.xml')) {
 $new_xml = simplexml_load_file('bands.xml');
 $json = json_encode($new_xml);
 $array = json_decode($json,TRUE);
} else {
exit('-------');
}


/**
 *  task 2
 */

print_r(MembersNumber($array));

/**
 *  task 3
 */

print_r(GenreInBands($array));

/**
 *  task 4
 * array_filter
 */

print_r(Members4andLess($array));

/**
 *  task 5
 */

print_r(CountGenreInBands($array));

/**
 *  task 6
 */

print_r(CountGenreInBandsAndGroup($array));

/**
 *  task 7
 */

print_r(lastTask($array));


/**
 * функция, которая сортирует этот массив по кол-ву участников (band->members).
 * @param $array
 * @return mixed
 */
function MembersNumber($array) {
 $member = [];
 foreach($array['Band'] as $key => $arr){
  $member[$key] = $arr['Members'];
 }
 array_multisort($member, SORT_NUMERIC, $array['Band']);

 return $array['Band'];
}

/**
 * функция, которая сортирует этот массив по количеству жанров в группе (band->genres).
 * @param $array
 * @return mixed
 */
function GenreInBands ($array) {
 $sort_genre = [];
 foreach($array['Band'] as $key => $arr){
  $sort_genre[$key] = $arr['Genres']['Genre'];
 }
 array_multisort($sort_genre, $array['Band']);
 return $array['Band'];
}

/**
 * функция, которая фильтрует этот массив, и оставляет только группы с 4 и менее участников.
 * @param $array
 * @return array
 */
function Members4andLess($array) {
 $resl =[];
 foreach ($array['Band'] as $valueLow) {
  if ($valueLow['Members'] <= 4) {
   $resl[] = $valueLow;
  }
 }
 return $resl;
}

/**
 * функция, которая проходится по массиву, подсчитывает количество жанров каждой группы, и записывает в группу как свойство genresTotal.
 * @param $array
 * @return array
 */
function CountGenreInBands ($array) {
 $res = [];
 foreach ($array['Band'] as $countitem) {
  $sumGenres['@attributes']['genresTotal'] = count($countitem['Genres']['Genre']);
  $countitem['Genres']['@attributes']['genresTotal'] = $sumGenres['@attributes']['genresTotal'];
  $res[] = $countitem;
 }
 return $res;
}


/**
 * функция, которая выбирает весь список жанров, и для каждого жанра подсчитывает количество групп, которые его играют.
 * @param $array
 * @return array
 */
function CountGenreInBandsAndGroup ($array) {
 $retro = [];

 foreach ($array['Band'] as $items) {
  $Genrescount = $items['Genres']['Genre'];
  if(is_array($Genrescount)) {
   foreach ($Genrescount as $var) {
    $retro[] = $var;
    }
   } else {
   $retro[] = $Genrescount;
  }
 }
 $mathThem = array_count_values(array_map('strtolower', $retro));
 return $mathThem;
}


/**
 * функция, которая выбирает весь список жанров, и для каждого жанра находит самую старую группу, что его играет (год брать с band->formed), и кол-во групп, что его играет.
 * @param $array
 * @return array
 */
function lastTask ($array)
{
 $genres = [];
 foreach ($array['Band'] as $item) {
  $bandGenres = $item['Genres']['Genre'];
  if (!is_array($bandGenres)) {
   $bandGenres = [];
   $bandGenres[] = $item['Genres']['Genre'];
  }
  foreach ($bandGenres as $var) {
   if (array_key_exists($var, $genres)) {
    if ($genres[$var]['year'] > $item['@attributes']['formed']) {
     $genres[$var]['year'] = $item['@attributes']['formed'];
     $genres[$var]['oldest_band'] = $item['Name'];
    }
    $genres[$var]['bands_count'] = $genres[$var]['bands_count'] + 1;
   } else {
    $genres[$var]['year'] = $item['@attributes']['formed'];
    $genres[$var]['oldest_band'] = $item['Name'];
    $genres[$var]['bands_count'] = 1;
   }
  }
 }
 return $genres;
}
?>
