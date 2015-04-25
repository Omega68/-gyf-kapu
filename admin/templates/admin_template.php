<?

class Admin_Template extends Site_Template{
  function show(){
    ?><!DOCTYPE html>
    <html>
     <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <script src="/modules/moopage/moopage4.js"></script>
      <link rel="stylesheet" href="/admin/templates/perpetuum_template.css" type="text/css">
      <link rel="stylesheet" href="/admin/templates/menu.css" type="text/css">
      <title>Admin</title>
     </head>
     <body>
      <div class="fixed_header">
        <div class="menu">
          <ul>
            <li>
              <a href="?page=felhasznalo"><span>Felhasználók</span></a>
            </li>
            <li>
              <a href="?page=ugyfel"><span>Ügyfelek</span></a>
            </li>
            <li>
              <a href="?page=termek"><span>Termékek</span></a>
            </li>
            <li>
              <a href="?page=ajanlat_keres"><span>Ajánlat kérések</span></a>
            </li>
            <li>
              <a href="?page=ajanlat"><span>Ajánlatok</span></a>
            </li>
            <li>
              <a href="?page=rendeles"><span>Rendelések</span></a>
            </li>
          </ul>
        </div>
      <div class="clear"></div>
      </div>
      <div class="header_right">
        <? $this->show_slot("login"); ?>
      </div>
      <div class="content">
      <?
      
      
      $this->show_slot('messages');
      $this->show_slot("page");
      ?>
       
      </div>     
      
     </body>
    </html>
    <?
  }
}

