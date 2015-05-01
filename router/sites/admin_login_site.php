<?
return array(
  'default_menu'=>array()
  ,'template_class'=>"Admin_Login_Site_Template"
  ,'component_slots'=>array(
    'login'=>"ugyfel"
    ,'messages'=>'uzenetek'
  )
  ,'components'=>array(
    'uzenetek'=>array(
      'class'=>'Uzenetek_Site_Component'
      ,'allowed_slots'=>array()
    ),
        'ugyfel'=>array(
            'class'=>'Ugyfelek_Site_Component'
        ,'allowed_slots'=>array()
        )
  )
 
);