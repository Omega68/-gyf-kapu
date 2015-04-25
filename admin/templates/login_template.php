<?

class Admin_Login_Site_Template extends Site_Template{
  function show(){
    ?><!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
    <html>
     <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <script src="/modules/moopage/moopage4.js"></script>      
      <link rel="stylesheet" href="/admin/templates/login.css" type="text/css">
      <link rel="stylesheet" href="/admin/templates/perpetum_template.css" type="text/css">
      <title>Login</title>
     </head>
     <body>
      <center><h1>
       Admin
      </h1>
      </center>
      <div class= "login_box">
      <div class="header"> 
        <h2>Bejelentkezés</h2>
      </div>
        <?
        $this->show_slot('messages');
        $this->show_slot('login');
        ?>
     
      </div>
     </body>
    </html>
    <?
  }
}
