<?
/**
Ez egy teszt autentikátor, ami mindig beenged
*/
class Teszt_Authenticator extends Site_Authenticator{
  function isUserAuthorized(){
    //itt kell eldönteni, hogy be lehet-e engedi a user-t az adott felületre, ez esetben az adminra
    //Ha ezt átírod false-ra, akkor a login site-ot fogja mutatni.
    return true;
  }
}   
  
