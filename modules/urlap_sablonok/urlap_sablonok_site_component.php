<html>
<head>
    <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
    <link rel="stylesheet" type="text/css"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
    <script type="text/javascript">
        $(document).ready(function(){
            $("#date").datepicker();
        });
    </script>
</head>
<body>
<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Urlap_sablonok_Site_Component extends Site_Component{

    private $perm;
    private $showAddForm=true;

    protected function afterConstruction(){
        $this->perm=PersistenceManager::getInstance();
    }

    function process(){
        $this->perm=PersistenceManager::getInstance();
        if(!empty($_POST['new'])){
            $this->showAddForm=true;
        }
        if(!empty($_POST['back']) || !empty($_POST['save'])){
            $this->showAddForm=false;
        }
    }

    function show(){
        if ($this->showAddForm) {
            include_once 'views/new_sablon.php';
        } else {
            include_once 'views/sablon_lista.php';
        }
    }
}?>
</body>
</html>