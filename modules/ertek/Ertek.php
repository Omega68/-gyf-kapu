<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.05.10.
 * Time: 18:34
 */

class Ertek extends Persistent{
    public function validate(array $params=null){

    }

    /**
    return void

    Tetsz�leges l�trehoz�si tev�kenys�g.
    Aloszt�ly implement�lja
     */
    protected function onAfterCreate(array $params=null){
    }

    //TODO: getterek, setterek a Persistent-ben l�v� getFields �s setFields seg�ts�g�vel
    public function getErtekFields(){
        return $this->getFields();
    }

    public function setErtekFields(array $values){
        return $this->setFields($values);
    }

    protected function onBeforeDelete(array $params=null) {}

}

