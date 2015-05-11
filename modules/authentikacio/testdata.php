<?


class TestData extends Site_Component{

    private $pm;

    protected function afterConstruction(){
        $this->pm = PersistenceManager::getInstance();
    }

    public function newAdmin(){

        ?>
        <form method="post">
            <p>Új Admin létrehozása:</p>
            <table>
                <tr>
                    <td>Azonosító: </td>
                    <td><input type="text" name="adminAzon"></td>
                </tr>
                <tr>
                    <td>Jelszó: </td>
                    <td><input type="password" name="adminJelszo"></td>
                </tr>
                <tr>
                    <td>Email: </td>
                    <td><input type="text" name="adminEmail"></td>
                </tr>

           <tr>
               <td colspan="2">
                   <input type="submit" name="adminSubmit" value="Új admin!">
               </td>
           </tr>
                <tr>
                    <td colspan="2">
                        <?
                        if($this->aError){
                            echo "Hiányos adatok!";
                        } else if($this->aSuccess){
                            echo "Sikeres regisztráció!" . $_SESSION['azon'];
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </form>
    <?

    }

    public function newUgyfel(){

        ?>
        <form method="post">
            <p>Új Ügyfél létrehozása:</p>
            <table>
                <tr>
                    <td>Azonosító: </td>
                    <td><input type="text" name="uAzon"></td>
                </tr>
                <tr>
                    <td>Jelszó: </td>
                    <td><input type="password" name="uJelszo"></td>
                </tr>
                <tr>
                    <td>Email: </td>
                    <td><input type="text" name="uEmail"></td>
                </tr>
                <tr>
                    <td>Cím: </td>
                    <td><input type="text" name="uCim"></td>
                </tr>
                <tr>
                    <td>Telefon: </td>
                    <td><input type="text" name="uTelefon"></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="uSubmit" value="Új ügyfél!">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?
                            if($this->uError){
                                echo "Hiányos adatok!";
                            }  else if($this->uSuccess){
                                echo "Sikeres regisztráció! " . $_SESSION['azon'];
                            }
                        ?>
                    </td>
                </tr>
            </table>
        </form>
    <?

    }

    function process()
    {
        if(!empty($_POST['adminSubmit'])){
            if(!empty($_POST['adminAzon']) && !empty($_POST['adminEmail']) && !empty($_POST['adminJelszo'])){
                $adatok = array(
                    'azon'=>$_POST['adminAzon'],
                    'jelszo' => $_POST['adminJelszo'],
                    'email' => $_POST['adminEmail']
                );
                $this->pm->createObject("Admin", $adatok);
                $this->aError = false;
                $this->aSuccess = true;
                $_SESSION['azon'] = $_POST['adminAzon'];

            } else {
                $this->aError = true;
            }
        }
        if(!empty($_POST['uSubmit'])){
            if(!empty($_POST['uAzon']) && !empty($_POST['uEmail']) && !empty($_POST['uJelszo'])  && !empty($_POST['uCim'])  && !empty($_POST['uTelefon'])){
                $adatok = array(
                    'azon'=>$_POST['uAzon'],
                    'jelszo' => $_POST['uJelszo'],
                    'email' => $_POST['uEmail'],
                    'cim' => $_POST['uCim'],
                    'telefon' => $_POST['uTelefon'],
                );
                $this->pm->createObject("Ugyfel", $adatok);
                $this->uError = false;
                $this->uSuccess = true;
                $_SESSION['azon'] = $_POST['uAzon'];
            } else {
                $this->uError = true;
            }
        }
    }


    function show()
    {
        ?>
        <html>
        <body>
            <?
            $this->newAdmin();

            //$this->newUgyfel();
            ?>
            </body>
        <html/>
        <?
    }
}

