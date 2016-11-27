<?php
set_time_limit(0);
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=\"Telepoche-".date('Y-m-d').".xml\""); 
echo '<?xml version="1.0" encoding="UTF-8"?>

<tv source-info-url="http://telepoche.com/" source-data-url="http://telepoche.com/" generator-info-url="http://forum-racacax.ga/">
';
$get1 = html_entity_decode(file_get_contents('http://www.telepoche.fr/programme-tv/grille/'.date('Y-m-d').'/orange').file_get_contents('http://www.telepoche.fr/programme-tv/grille/'.date('Y-m-d').'/free').file_get_contents('http://www.telepoche.fr/programme-tv/grille/'.date('Y-m-d').'/bouygues').file_get_contents('http://www.telepoche.fr/programme-tv/grille/'.date('Y-m-d').'/sfr').file_get_contents('http://www.telepoche.fr/programme-tv/grille/'.date('Y-m-d').'/numericable').file_get_contents('http://www.telepoche.fr/programme-tv/grille/'.date('Y-m-d').'/canal-et-canalsat').file_get_contents('http://www.telepoche.fr/programme-tv/grille/'.date('Y-m-d').'/cable-adsl-satellite').file_get_contents('http://www.telepoche.fr/programme-tv/grille/'.date('Y-m-d').'/belgique').file_get_contents('http://www.telepoche.fr/programme-tv/grille/'.date('Y-m-d').'/ors-bouquet'),ENT_QUOTES);
$get1 = str_replace('<a href="/programme-tv/grille-chaine/','<a chduzes="',"$get1");
preg_match_all('/a chduzes="(.*?)"/', $get1, $listechaine);
$listechaine = array_unique($listechaine[1]);
$ii = 1;
foreach ($listechaine as &$chn) {	
print_r("<channel id=\"C".$ii.".telepoche.com\">
    <display-name>".$chn."</display-name>
</channel>\n");
$ii++;
}
$ii = 1;
foreach ($listechaine as &$chn) {
$get88 = html_entity_decode(file_get_contents('http://www.telepoche.fr/programme-tv/grille-chaine/'.$chn),ENT_QUOTES);
$delimiter = explode('<div class="grid-content">',$get88)[1];
$delimiter = strstr($delimiter, '<!-- adtech emplacement:footer -->', true);
$conversion = array(">"=>'"',"<"=>'"');
$res2 = strtr($delimiter,$conversion);
$res2 = str_replace('"/div"                                                                                                "/div"','"enday"',"$res2");
$conversion = array('   '=>'','class="category"""'=>'class="category""Inconnue"','class="title-episode"""'=>'class="title-episode""Inconnu"','"p class="synopsis""'.CHR(10)=>'"p class="synopsis""',CHR(10).'                "/p"'=>'"/p"','                    '=>'');
$res2 = strtr($res2,$conversion);
$res2 = str_replace('p class="synopsis"""','p class="synopsis""Aucune Description disponible"',"$res2");
$res2 = str_replace('"span"'.CHR(10).CHR(10).'"div class="meta-datas""','"span"'.CHR(10).CHR(10).'"img itemprop="image" class="thumbnail" src="http://css1.telepoche.fr/extension/telepoche/design/telepoche/images/layout/grid-no-picture.jpg" "div class="meta-datas""',"$res2");
$exp2 = 'div id="days"';
$count = 0;
$exp = "fttf";
while(strlen($exp) > 2) {
$date = date('Ymd',strtotime(date("Ymd", strtotime(date("Ymd"))) . " +".$count." day"));
${'deb'.$date} = "";
${'fin'.$date} = "";
${'title'.$date} = "";
${'time'.$date} = "";
${'duree'.$date} = "";
${'cat'.$date} = "";
${'epi'.$date} = "";
${'syno'.$date} = "";
${'thumb'.$date} = "";
${'url'.$date} = "";
$exp = explode($exp2,$res2)[1];
$exp = strstr($exp, '"enday"', true);
$exp2 = $exp2.$exp.'"enday"';
if(strlen($exp) > 2) {
$tabexp[] = $exp;
preg_match_all('/p class="time""(.*?) - "/', $exp, ${'time'.$date});
${'time'.$date} = ${'time'.$date}[1];
preg_match_all('/span"(.*?)"/', $exp, ${'duree'.$date});
${'duree'.$date} = array_filter(${'duree'.$date}[1]);
preg_match_all('/class="category""(.*?)"/', $exp, ${'cat'.$date});
${'cat'.$date} = array_filter(${'cat'.$date}[1]);
preg_match_all('/class="title-episode""(.*?)"/', $exp, ${'epi'.$date});
${'epi'.$date} = array_filter(${'epi'.$date}[1]);
preg_match_all('/p class="synopsis""(.*?)"/', $exp, ${'syno'.$date});
${'syno'.$date} = array_filter(${'syno'.$date}[1]);
preg_match_all('/class="thumbnail" src="(.*?)"/', $exp, ${'thumb'.$date});
${'thumb'.$date} = array_filter(${'thumb'.$date}[1]);
preg_match_all('/lien-fiche" href="(.*?)"/', $exp, ${'url'.$date});
${'url'.$date} = array_filter(${'url'.$date}[1]);
foreach (${'url'.$date} as &$uuu) {
$dab = explode('lien-fiche" href="'.$uuu.'""',$exp)[1];
$dab = strstr($dab, '"', true);
${'title'.$date}[] = $dab;
}
foreach (array_combine(${'time'.$date}, ${'duree'.$date}) as $time => $duree) {
$duree = str_replace('(','',"$duree");
$duree = str_replace(')','',"$duree");
$duree = str_replace(' ','',"$duree");
$duree = str_replace('min','',"$duree");
$duree = $duree*60;
$time = str_replace("h","","$time");
$time = $date.$time.'00';
$dt = strtotime($time);
$duree = $dt+$duree;
$duree = date('YmdHis',$duree);

${'fin'.$date}[] = $duree;
${'deb'.$date}[] = $time;
}
}
$count++;
}
$count3 = 0;
$date = date('Ymd');
while(strlen(${'title'.$date}[0]) > 0)
{
$date = date('Ymd',strtotime(date("Ymd", strtotime(date("Ymd"))) . " +".$count3." day"));
$count3++;
$count2 = 0;
while(strlen(${'title'.$date}[$count2]) >0)
	{
if(strlen(${'deb'.$date}[$count2+1]) >2)
	{
	$hdf = ${'deb'.$date}[$count2+1];
	} else { $hdf = ${'fin'.$date}[$count2]; } 
$string = '  <programme start="'.${'deb'.$date}[$count2].' +0100" stop="'.$hdf.' +0100" channel="C'.$ii.'.telepoche.com">
    <title>'.${'title'.$date}[$count2].'</title>
    <desc lang="fr">'.${'syno'.$date}[$count2].'</desc>
    <category lang="fr">'.${'cat'.$date}[$count2].'</category>
    <icon src="'.${'thumb'.$date}[$count2].'" />
  </programme>';
$string = str_replace('&','&amp;',"$string");
print_r($string."\n");

$count2++;	
	}
}
$ii++;
}
echo '</tv>';