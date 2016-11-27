<?php
set_time_limit(0); 
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=\"Telerama-".date('Y-m-d').".xml\""); 
echo '<?xml version="1.0" encoding="UTF-8"?>

<tv source-info-url="http://guidetv-iphone.telerama.fr/" source-data-url="http://guidetv-iphone.telerama.fr/" generator-info-url="http://forum-racacax.ga/">
';
$url = "http://guidetv-iphone.telerama.fr/verytv/procedures/ListeChaines.php";
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $url);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_USERAGENT, "Telerama/1.2 CFNetwork/459 Darwin/10.0.0d3");
$res1 = curl_exec($ch1);
curl_close($ch1);
$res1 = iconv('Windows-1252', 'UTF-8//TRANSLIT', $res1); 
$res1 = ":$$$:".$res1;
$conversion = array(":$$$:"=>'"><channel id="');
$res1 = strtr($res1,$conversion);
$conversion = array("$$$"=>'"><channel2 id="');
$res1 = strtr($res1,$conversion);
preg_match_all('/channel id="(.*?)"/', $res1, $chnid);
$chnid = $chnid[1];
preg_match_all('/channel2 id="(.*?)"/', $res1, $chnname);
$chnname = $chnname[1];
foreach (array_combine($chnid, $chnname) as $id => $name) {	
$conversion = array("&"=>"&amp;");
$name = strtr($name,$conversion);
if(strlen($name) > 0) {
print_r("<channel id=\"C".$id.".telerama.fr\">
    <display-name>".$name."</display-name>
</channel>\n");
$chnid2[] = $id;
$chnname2[] = $name;
}
}
$chnid = $chnid2;
$chnname = $chnname2;
foreach (array_combine($chnid, $chnname) as $id => $name) {	
for ($i = 0; $i <= 10; $i++) {
$date = date("Y-m-d");// ici ta date
$date = date('Y-m-d',strtotime(date("Y-m-d", strtotime($date)) . " +".$i." day"));
$url = "http://guidetv-iphone.telerama.fr/verytv/procedures/LitProgrammes1Chaine.php?date=".$date."&chaine=".$id;
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $url);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_USERAGENT, "Telerama/1.2 CFNetwork/459 Darwin/10.0.0d3");
$res2 = curl_exec($ch1);
curl_close($ch1);
$res2 = iconv('Windows-1252', 'UTF-8//TRANSLIT', $res2); 
$conversion = array("$$$$$$"=>'$$$ $$$');
$res2 = strtr($res2,$conversion);
$res2 = CHR(10)."$$$".$res2;
$res2 = str_replace('$$$Durée',"Durée",$res2);
$res2 = str_replace(CHR(10)."$$$",'"><debutprgm id="',$res2);
$res2 = str_replace(CHR(10),"||",$res2);
preg_match_all('/debutprgm id="(.*?)"/', $res2, $bwah);
$delimit = "ACTIVE";
$count = 0;
$delimitable = array();
$delimitable = $bwah[1];
foreach ($delimitable as &$tout) {
$tout = $tout."||";
$tout = str_replace("||",CHR(10),$tout);
$title = explode('$$$'.$name.'$$$',$tout)[1];
$title = strstr($title, "$$$", true);	
$deb = explode('$$$'.$name.'$$$'.$title.'$$$',$tout)[1];
$deb1 = strstr($deb, ":00$$$", true);	
$deb = strstr($deb, "$$$", true);	
$genre2 = explode('$$$'.$name.'$$$'.$title.'$$$'.$deb.'$$$',$tout)[1];
$genre2 = explode('$$$',$genre2)[1];
$dt = str_replace('-','',"$date");
$fin = explode('$$$'.$name.'$$$'.$title.'$$$'.$deb.'$$$',$tout)[1];
$fin = strstr($fin, "$$$", true);
$fin = $dt.str_replace(':','',"$fin");	
$deb = $dt.str_replace(':','',"$deb");
if($fin < $deb) { $fin = date('YmdHis',strtotime(date("YmdHis", strtotime($fin)) . " +1 day")); }
$dt = strtotime($deb);
$duree = explode('Durée : ',$tout)[1];
$duree = strstr($duree, " min", true);	
$duree = $duree *60;
$genre = explode('Genre : ',$tout)[1];
$genre = strstr($genre, CHR(10), true);	
$aspect = explode(CHR(10)."En ",$tout)[1];
$aspect = strstr($aspect, CHR(10), true);	
$csa = explode("Interdit aux moins de ",$tout)[1];
$csa = strstr($csa, " an", true);	
if(empty($csa)) { $csa = "Tous public"; } else { $csa = '-'.$csa;}
$actor = explode("Acteurs : ",$tout)[1];
$actor = strstr($actor, CHR(10), true);	
if(strlen($actor)< 1)
{
$actor = explode("Présentateur : ",$tout)[1];
$actor = strstr($actor, CHR(10), true);	
}
$real = explode("Réalisateur : ",$tout)[1];
$real = strstr($real, CHR(10), true);	
$an = explode("Année : ",$tout)[1];
$an = strstr($an, CHR(10), true);	
$comp = explode("Musique : ",$tout)[1];
$comp = strstr($comp, CHR(10), true);	
$shw = explode("Showview : ",$tout)[1];
$shw = strstr($shw, CHR(10), true);	
$sub = explode("Sous-titre : ",$tout)[1];
$sub = strstr($sub, CHR(10), true);	
$saison = explode("Saison : ",$tout)[1];
$saison = strstr($saison, CHR(10), true);	
$ep = explode("Episode : ",$tout)[1];
$ep = strstr($ep, CHR(10), true);	
$desc1 = explode('$$$'.$genre2."$$$",$tout)[1];
$desc1 = strstr($desc1, "\"", true);	
if(preg_match("(Durée :)",$desc1))
{ $desc1 = "";}
$sai = "";
if(strlen($saison) > 0) { $sai = 'Saison : '.$saison.' Episode : '.$ep.' - '; }
if(strlen($real) > 0) { $real = '
<director>'.$real.'</director>'; } else { $real = ""; }
if(strlen($actor) > 0) { $actor = '
<actor>'.$actor.'</actor>'; } else { $actor = ""; }
if(strlen($comp) > 0) { $comp = '
<composer>'.$comp.'</composer>'; } else { $comp = ""; }
if(strlen($desc1) > 0) { $desc2 = '
<desc lang="fr">'.$sai.$desc1.'</desc>'; } else { $desc2 = ""; }
if(strlen($sub) > 0) { $sub = '
<sub-title>'.$sub.'</sub-title>'; } else { $sub = ""; }
if(strlen($genre) > 0) { $genre = '
<category lang="fr">'.$genre.'</category>'; } else { $genre = ""; }
if(strlen($genre2) > 0) { $genre2 = '
<category lang="fr">'.$genre2.'</category>'; } else { $genre2 = ""; }
if(strlen($an) > 0) { $an = '
<date>'.$an.'</date>'; } else { $an = ""; }
if(strlen($csa) > 0) { $csa = '
<rating system="CSA">
      <value>'.$csa.'</value>
    </rating>'; } else { $csa = ""; }
if(strlen($aspect) > 0) { $aspect = '<aspect>'.$aspect.'</aspect>'; } else { $aspect = ""; }
if(strlen($title) > 0) {
$string = '<programme start="'.$deb.' +0100" stop="'.$fin.' +0100" showview="'.$shw.'" channel="C'.$id.'.telerama.fr">
    <title>'.$title.'</title>'.$sub.$desc2.'
    <credits>'.$real.$actor.$comp.'
    </credits>'.$an.$genre.$genre2.'
    <icon src="http://guidetv-iphone.telerama.fr/verytv/procedures/images/'.$date.'_'.$id.'_'.$deb1.'.jpg" />
    <video>'.$aspect.'
      <quality>HDTV</quality>
    </video>
    <subtitles type="teletext">
      <language>fr</language>
    </subtitles>'.$csa.'
</programme>';
$conversion = array("&"=>"&amp;");	
$string = strtr($string,$conversion);
if(strlen($title) > 0)
{
echo $string.'
';

}
}
}
}
}
echo '</tv>';
?>
