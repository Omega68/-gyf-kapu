<?

 
class Teszt_Komponens extends Site_Component{

  function process(){
    //Moo_Session::QuickProcess();
    //Ide megy a controller menetben futó kód
  }
  
  function show(){
         
    //Moo_Session::QuickShow('Ugyfel_Lista');

    /*
    strip_tags(string str, [string allowable_tags])
    htmlentities(string string, [int quote_style], [string charset])
    */
    
    
  
  ?>

<h2>Itt a keretrendszer!</h2>
<br />

<h2>A keretrendszer használata:</h2>
<br />
A /modules könyvtárban minden csapat csináljon egy könyvtárat a modulja nevével (rendeles, termekek, stb). 
Oda megy majd minden ami írni fogtok. 
Ezen belül tetszés szerinti fájlszerkezet alakítható ki. Fájlneveknek csak angol ábécé beűit használd! 
Kis-,és nagybetűk különböznek.
<br /><br />
<strong>Betöltők:</strong>
<br><br>
A keretrendszerbe "beépüléshez" először is kell egy autoloader. Ebből minden modulhoz elég egy. 
Ezek arra kellenek, hogy a keretrendszer be tudja tölteni az osztályok kódját, és ne nektek kelljen include-ot hívogatni. 
<br><br>

A betöltők is osztályok, és az AbstractLoader osztályból kell származniuk, és a getFileNameForClass() metódust kell implementálni. 
Ebben te mondhatod meg, hogy a keresett osztály melyik fájlban található. 
Példaként nézd meg ennek a modulnak a loaderét a /modules/elek/teszt_loader.php -ban!
<br><br>
A loader osztályodat a /loader/loaders_config.php fájlban kell bekonfigurálni, hogy a keretrendszer is tudjon róla.
<br><br><br>
<strong>Felület komponensek:</strong>
<br><br>
Amit itt látsz, az egy felület komponens. Ez lesz a másik fő módja a keretrendszerbe beépülésnek.
Ehhez szintén nézd meg a /modules/elek/teszt_komponens.php fájlt!
<br><br>
A komponenseket a /router/sites könyvtár alatt abba a fájlba kell bekonfigurálni, amelyik "site"-ra el akarod helyezni. 
Ez itt most a "customer_site". 
<br><br>
Ha ez megvan, akkor annak megfelelően ahogy be lett konfigurálnva a komponens, lehet mindig látható, vagy az url-ben lehet a komponensre hivatkozni, a nevével.
<br />
Ez a komponens ?page=teszt url-re jön be, de alapértelmezetten is ez jelenik meg. 
<br><br>
<br />
<b style="font-weight:bold;color:red;">A konfigurációs fájlokkal vigyázzatok mikor szerkesztitek, nehogy felülírjátok  egymás módosításait!</b>
  
  
<h2>Adatbázis</h2>    

A projektnek saját adatbázisa van . Ide kell majd dolgozni, és az adatbázis modul is ide kapcsolódik.    
<?
    echo "Példa adatbázis kérésre (nézd meg ezt a komponenst):<br>";
    echo "Aktuális adatbázis táblák:<br />";
    
    $db=Database::getInstance();
    $res=$db->query("SHOW TABLES");
    //debug
    echo "<pre>";
    var_dump($res);
    echo "</pre>";
  }
   
}
