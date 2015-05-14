<?


class TestData extends Site_Component{

    private $pm;

    protected function afterConstruction(){
        $this->pm = PersistenceManager::getInstance();
    }

    public function newAdmin(){

        ?>
            <h1><br/>Új Admin létrehozása:</h1>
            <table>
                <form method="post">
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
                        <?
                        if(isset($this->aError)){
                            echo "Hiányos adatok!";
                            $this->validationError($this->aError);
                        } else if($this->aSuccess){
                            echo "Sikeres regisztráció! Azonosító: " . $_SESSION['azon'];
                        }
                        ?>
                    </td>
                </tr>

           <tr>
               <td colspan="2">
                   <input type="submit" name="adminSubmit" value="Mentés" class="save_button">
               </td>
           </tr>
                </form>

                <tr>
                    <td>
                    <form method="post" action="?page=felhasznalo">
            <input type="submit" name="back" value="Vissza" class="back_button">
            </form>

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

                $adatok = array(
                    'azon'=>$_POST['adminAzon'],
                    'jelszo' => $_POST['adminJelszo'],
                    'email' => $_POST['adminEmail']
                );
                $result = $this->pm->createObject("Admin", $adatok);
                if(is_array($result)){
                    $this->aError = $result;
                    $_SESSION['edit'] = true;
                }
                else $this->aSuccess = true;
                $_SESSION['azon'] = $_POST['adminAzon'];


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
            $this->newAdmin();
    }
}

