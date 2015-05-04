<?
return array(
  'default_menu'=>array('page' => 'profil')
  ,'template_class'=>"Admin_Template"
  ,'component_slots'=>array(
    'menu'=>''
    ,'login'=>'authentikacio'
    ,'page'=>''
    ,'messages'=>'uzenetek'
  )
  ,'components'=>array(
    'teszt'=>array(
      'class'=>'Teszt_Komponens'
      ,'params'=>array()
      ,'allowed_slots'=>array('page')
    )
    ,'uzenetek'=>array(
      'class'=>'Uzenetek_Site_Component'
      ,'allowed_slots'=>array()
    ),
     'felhasznalo'=>array(
        'class'=>'Felhasznalok_Site_Component',
        'params'=>array(),
        'allowed_slots'=>array('page')
    ),
      'ugyfel'=>array(
          'class'=>'Ugyfelek_Site_Component',
          'params'=>array(),
          'allowed_slots'=>array('page')
      ),
      'sablon'=>array(
          'class'=>'Urlap_sablonok_Site_Component',
          'params'=>array(),
          'allowed_slots'=>array('page')
      ),
      'igenyles'=>array(
          'class'=>'Igenylesek_Site_Component',
          'params'=>array(),
          'allowed_slots'=>array('page')
      ),
      'authentikacio'=>array(
          'class' =>'Authentication_Site_Component',
          'allowed_slots'=>array()
      ),
        'profil'=>array(
            'class' =>'Profil_Site_Component',
            'allowed_slots'=>array(),
            'allowed_slots'=>array('page')
        ),
        'erp_ugyfel'=>array(
            'class'=>'ERP_Ugyfelek_Site_Component',
            'params'=>array(),
            'allowed_slots'=>array('page')
        )

  ) 
);