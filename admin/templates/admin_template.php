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
      <title>Ügyfélkapu</title>
     </head>
     <body>
      <div class="fixed_header">
        <div class="menu">
          <ul>
              <li>
                  <a href="?page=profil"><span>Profil</span></a>
              </li>
              <?
              $auth = Authentication::getInstance();
              $user = $auth->whoLoggedIn();
              $pm = PersistenceManager::getInstance();
              $u = $pm->getObject($user);
              if($u instanceof Admin){
                ?>
                  <li>
                      <a href="?page=felhasznalo"><span>Felhasználók</span></a>
                  </li>
                  <li>
                      <a href="?page=ugyfel"><span>Ügyfelek</span></a>
                  </li>

                  <li>
                      <a href="?page=erp_ugyfel"><span>ERP Ügyfelek</span></a>
                  </li>

                  <li>
                      <a href="?page=sablon"><span>Sablonok</span></a>
                  </li>
                <?
              }
              else {
                  ?>
                  <li>
                      <a href="?page=igenyles"><span>Igénylések</span></a>
                  </li>
                  <?
              }
              ?>



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

