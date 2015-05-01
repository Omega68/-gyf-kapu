<?
return array(
  'default_menu'=>array()
  ,'template_class'=>"Admin_Login_Site_Template"
  ,'component_slots'=>array(
    'login'=>'authentikacio'
    ,'messages'=>'uzenetek'
  )
  ,'components'=>array(
    'uzenetek'=>array(
      'class'=>'Uzenetek_Site_Component'
      ,'allowed_slots'=>array()
    ),
        'login'=>array(
            'class'=>'Admin_Login_Site_Component',
            'params'=>array(),
            'allowed_slots'=>array('login')
        ),
        'authentikacio'=>array(
            'class'=>'Authentication_Site_Component',
            'allowed_slots'=>array()
        )
    )

 
);