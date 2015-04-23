<?

abstract class Site_Authenticator {
  
  final function __construct(){}
  /**
  return:
    true: ha felhasználó be van lépve és jogosult az adott belépési pontra
    false: ha felhasználó be nincs lépve vagy nem jogosult az adott belépési pontra
  */
  abstract function isUserAuthorized();
}
