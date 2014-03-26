<?php

class TestController extends AppController {


  var $uses = array('User','Library','Download','Song','Wishlist','Album','Url','Language','Credentials','Files', 'Artist', 'Genre', 'Zipusstate', 'AuthenticationToken');
  var $components = array('Downloads','AuthRequest');

  public $arr_result = array();

  function index(){

  }
  
  function arrayPlay($arg1, $startFrom = null, $recordCount = null) {
    
        
    set_time_limit(0);
    
    echo '<pre>';
    
    $arrLibrarData = array();
  
    $libraryDetails = $this->Library->find('all',array(
      'fields' => array('id', 'library_name','library_authentication_method'),
      'conditions' => array('Library.library_status' => 'active', 'id < 610'),
      'recursive' => -1
      
      )
    );
    
     
    foreach($libraryDetails AS $key => $val) {
    
      $arrLibrarData[$val['Library']['id']]['library_id'] = $val['Library']['id'];
      $arrLibrarData[$val['Library']['id']]['library_name'] = $val['Library']['library_name'];
      $arrLibrarData[$val['Library']['id']]['method'] = $val['Library']['library_authentication_method'];
    }
     
    echo '<br />******************************************************************************************************<br />'; 
    echo '<br />*************************************** library table data start  ************************************<br />'; 
    print_r($arrLibrarData);   
    echo '<br />*************************************** library table data end  ************************************<br />'; 
    echo '<br />******************************************************************************************************<br />';
    
    
    
    $arr_library = array(
      'user_account' => array(
        'agent' => 'mobile',
        'authtype' => 1,
        'card' => 'no',
        'pin' => 'no',  
        'email' => 'yes',
        'password' => 'yes',
        'last_name' => 'no',      
      ),
      'innovative' => array(
        'agent' => 'mobile',
        'authtype' => 2,
        'card' => 'yes',
        'pin' => 'yes',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'innovative_wo_pin' => array(
        'agent' => 'mobile',
        'authtype' => 3,
        'card' => 'yes',
        'pin' => 'no',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'innovative_https' => array(
        'agent' => 'mobile',
        'authtype' => 4,
        'card' => 'yes',
        'pin' => 'yes',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),      
      'innovative_var_https' => array(
        'agent' => 'mobile',
        'authtype' => 5,
        'card' => 'yes',
        'pin' => 'yes',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'innovative_var_name' => array(
        'agent' => 'mobile',
        'authtype' => 6,
        'card' => 'yes',
        'pin' => 'no',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'yes', 
      ),
      'innovative_var_https_name' => array(
        'agent' => 'mobile',
        'authtype' => 7,
        'card' => 'yes',
        'pin' => 'no',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'yes', 
      ),
      'sip2' => array(
        'agent' => 'mobile',
        'authtype' => 8,
        'card' => 'yes',
        'pin' => 'yes',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'sip2_var' => array(
        'agent' => 'mobile',
        'authtype' => 9,
        'card' => 'yes',
        'pin' => 'yes',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'soap' => array(
        'agent' => 'mobile',
        'authtype' => 10,
        'card' => 'yes',
        'pin' => 'yes',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'innovative_var_wo_pin' => array(
        'agent' => 'mobile',
        'authtype' => 11,
        'card' => 'yes',
        'pin' => 'no',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'innovative_var_https_wo_pin' => array(
        'agent' => 'mobile',
        'authtype' => 12,
        'card' => 'yes',
        'pin' => 'no',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'sip2_wo_pin' => array(
        'agent' => 'mobile',
        'authtype' => 13,
        'card' => 'yes',
        'pin' => 'no',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'sip2_var_wo_pin' => array(
        'agent' => 'mobile',
        'authtype' => 14,
        'card' => 'yes',
        'pin' => 'no',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'curl_method' => array(
        'agent' => 'mobile',
        'authtype' => 15,
        'card' => 'yes',
        'pin' => 'yes',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'referral_url' => array(
        'agent' => 'mobile',
        'authtype' => 16,
        'card' => 'yes',
        'pin' => 'yes',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      'innovative_var' => array(
        'agent' => 'mobile',
        'authtype' => 17,
        'card' => 'yes',
        'pin' => 'yes',  
        'email' => 'no',
        'password' => 'no',
        'last_name' => 'no', 
      ),
      

    );
    
    foreach($arr_library AS $key1 => $val1) {
    
      foreach($arrLibrarData AS $key2 => $val2) {
        if($key1 == $val2['method']) {
          $arrLibrarData[$key2]['agent'] =  $val1['agent'];
          $arrLibrarData[$key2]['authtype'] =  $val1['authtype'];
          $arrLibrarData[$key2]['card'] =  $val1['card'];
          $arrLibrarData[$key2]['pin'] =  $val1['pin'];
          $arrLibrarData[$key2]['email'] =  $val1['email'];
          $arrLibrarData[$key2]['password'] =  $val1['password'];
          $arrLibrarData[$key2]['last_name'] =  $val1['last_name'];
        }
      
      }
    
    }
    
    //m68 Interactive , 1
    $arrLibrarData[1]['email'] = 'gupta09sandeep@gmail.com';
    $arrLibrarData[1]['password'] = '6fy2dqsp';
    $arrLibrarData[1]['login'] = 1;
    
    //Library Ideas , 2
    $arrLibrarData[2]['email'] = '';
    $arrLibrarData[2]['password'] = '';
    $arrLibrarData[2]['login'] = 0;
    
    //Orange County Library System , 3
    $arrLibrarData[3]['card'] = 'P013321245';
    $arrLibrarData[3]['pin'] = 'brimfire1';
    $arrLibrarData[3]['login'] = 1;
    
    //Princeton Library , 4
    $arrLibrarData[4]['card'] = '21945000779646';
    $arrLibrarData[4]['login'] = 1;
    
    //Wilkinson Public Library , 5
    $arrLibrarData[5]['card'] = '4230000214105';
    $arrLibrarData[5]['login'] = 1;
    
    //Nashville Public Library , 6
    $arrLibrarData[6]['card'] = '25192012546608';
    $arrLibrarData[6]['pin'] = '1234';
    $arrLibrarData[6]['login'] = 1;
    
    //Maricopa County Library District , 7
    $arrLibrarData[7]['card'] = '1110001491465';
    $arrLibrarData[7]['pin'] = '1234';
    $arrLibrarData[7]['login'] = 1;
    
    //Santa Clara County Library , 8
    $arrLibrarData[8]['card'] = '23305008074706';
    $arrLibrarData[8]['pin'] = '9051';
    $arrLibrarData[8]['login'] = 1;
    
    //Los Gatos Public Library , 9
    $arrLibrarData[9]['card'] = '23518000594964';
    $arrLibrarData[9]['pin'] = '8447';
    $arrLibrarData[9]['login'] = 1;
    
    //Paris Public Library , 10
    $arrLibrarData[10]['card'] = '22520428369855';
    $arrLibrarData[10]['login'] = 1;
    
    //Douglas County Libraries , 11
    $arrLibrarData[11]['card'] = '23025003575917';
    $arrLibrarData[11]['pin'] = '7604';
    $arrLibrarData[11]['login'] = 1;
    
    //New City Public Library , 12
    $arrLibrarData[12]['card'] = '22825000002356';
    $arrLibrarData[12]['pin'] = '4997';
    $arrLibrarData[12]['login'] = 1;
       
    //Grandview Heights Public Library , 13
    $arrLibrarData[13]['card'] = '21870000972639';
    $arrLibrarData[13]['pin'] = '1234';
    $arrLibrarData[13]['login'] = 1;
    
    // Princeton Public Library , 14
    $arrLibrarData[14]['email'] = '';
    $arrLibrarData[14]['password'] = '';
    $arrLibrarData[14]['login'] = 0;
      
    // San Jose Public Library , 15
    $arrLibrarData[15]['card'] = '21197204255367';
    $arrLibrarData[15]['pin'] = '1234';
    $arrLibrarData[15]['login'] = 1; 
    
    // Westport Public Library , 16
    $arrLibrarData[16]['card'] = '24015006394126';
    $arrLibrarData[16]['login'] = 1;
   
    // Bedford Free Public Library , 17
    $arrLibrarData[17]['card'] = '24861000357001';
    $arrLibrarData[17]['login'] = 1;
    
    
    //Sandwich Public Library , 18
    $arrLibrarData[18]['card'] = '20617000000171';
    $arrLibrarData[18]['login'] = 1;
    
    //Calcasieu Parish Library , 19
    $arrLibrarData[19]['card'] = '920110195702';
    $arrLibrarData[19]['pin'] = '1111';
    $arrLibrarData[19]['login'] = 1;
    
    //Glencoe Public Library , 20
    $arrLibrarData[20]['card'] = '21121000529874';
    $arrLibrarData[20]['login'] = 1;
    
    //East Baton Rouge Library , 21
    $arrLibrarData[21]['card'] = '';
    $arrLibrarData[21]['pin'] = '';
    $arrLibrarData[21]['login'] = 0;
    
    //Kenton County Public Library , 22
    $arrLibrarData[22]['card'] = '23126004678435';
    $arrLibrarData[22]['login'] = 1;
    
    //Kent District Public Library , 23
    $arrLibrarData[23]['card'] = '21297010035316';
    $arrLibrarData[23]['login'] = 1;
    
    //Yorba Linda Public Library , 24
    $arrLibrarData[24]['card'] = '';
    $arrLibrarData[24]['pin'] = '';
    $arrLibrarData[24]['login'] = 0;
    
    //Wellfleet Public Library , 25
    $arrLibrarData[25]['card'] = '1270000000222';
    $arrLibrarData[25]['login'] = 1;
    
    //Brownsburg Public Library , 26
    $arrLibrarData[26]['card'] = '120102551123';
    $arrLibrarData[26]['pin'] = '1234';
    $arrLibrarData[26]['login'] = 1;
    
    //Palestine, TX Public Library , 27
    $arrLibrarData[27]['card'] = '33619002251112';
    $arrLibrarData[27]['pin'] = '1112';
    $arrLibrarData[27]['login'] = 1;
    
    //Lane Public Library , 28
    $arrLibrarData[28]['card'] = '21842000775791';
    $arrLibrarData[28]['pin'] = '9889';
    $arrLibrarData[28]['login'] = 1;
    
    //Mesa County Public Library , 29
    $arrLibrarData[29]['card'] = '';
    $arrLibrarData[29]['login'] = 0;
    
    //New Canaan Library , 30
    $arrLibrarData[30]['card'] = '21457000813813';
    $arrLibrarData[30]['login'] = 1;
    
    //Chattahoochee Valley Libraries , 31
    $arrLibrarData[31]['card'] = '21008003374475';
    $arrLibrarData[31]['pin'] = '8642';
    $arrLibrarData[31]['login'] = 1;
    
    //Chappaqua Public Library , 32
    $arrLibrarData[32]['card'] = '21005100005549';
    $arrLibrarData[32]['login'] = 1;
    
    //Haverhill Public Library , 33
    $arrLibrarData[33]['card'] = '21479001202663';
    $arrLibrarData[33]['pin'] = '1586';
    $arrLibrarData[33]['login'] = 1;
    
    //O'Fallon Public Library , 34
    $arrLibrarData[34]['card'] = '1001600001234';
    $arrLibrarData[34]['pin'] = '1234';
    $arrLibrarData[34]['login'] = 1;
    
    //St. Charles Public Library , 35
    $arrLibrarData[35]['card'] = '20053001525422';
    $arrLibrarData[35]['pin'] = '0256';
    $arrLibrarData[35]['login'] = 1;
    
    //Edwardsville Public Library , 36
    $arrLibrarData[36]['card'] = '1000800705001';
    $arrLibrarData[36]['pin'] = '5001';
    $arrLibrarData[36]['login'] = 1;
    
    //Kewanee Public Library District , 37
    $arrLibrarData[37]['card'] = 'D320000057';
    $arrLibrarData[37]['pin'] = '982001';
    $arrLibrarData[37]['login'] = 1;
    
    //Jefferson Township Public Library , 38
    $arrLibrarData[38]['card'] = '1101400335373';
    $arrLibrarData[38]['login'] = 1;
    
    //Sacramento Public Library , 39
    $arrLibrarData[39]['card'] = '23029071653291';
    $arrLibrarData[39]['pin'] = 'test1';
    $arrLibrarData[39]['login'] = 1;
    
    //Amarillo Public Library , 40
    $arrLibrarData[40]['card'] = '1000204094873';
    $arrLibrarData[40]['pin'] = '4028';
    $arrLibrarData[40]['login'] = 1;
     
    //Amarillo Public Library , 41
    $arrLibrarData[41]['card'] = '29221001222073';
    $arrLibrarData[41]['login'] = 1;
    
    //Cedar Rapids Public Library , 42
    $arrLibrarData[42]['card'] = '1000105225485';
    $arrLibrarData[42]['pin'] = '5485';
    $arrLibrarData[42]['login'] = 1;
    
    //Ardsley Public Library , 43
    $arrLibrarData[43]['card'] = '21000300100737,21000300102030,21000300165466,21000100003040,21000300100000';
    $arrLibrarData[43]['login'] = 0;
    
    //Powhatan County Public Library , 44
    $arrLibrarData[44]['card'] = '20438000223279';
    $arrLibrarData[44]['pin'] = '2345';
    $arrLibrarData[44]['login'] = 1;
    
    //Delphi Public Library , 45
    $arrLibrarData[45]['card'] = '22991000014971';
    $arrLibrarData[45]['login'] = 1;
    
    //Buffalo and Erie County Public Library , 46
    $arrLibrarData[46]['card'] = '1000123017161';
    $arrLibrarData[46]['pin'] = '3499';
    $arrLibrarData[46]['login'] = 1;
    
    //Newport Public Library , 47
    $arrLibrarData[47]['card'] = '21540000506931';
    $arrLibrarData[47]['pin'] = '';
    $arrLibrarData[47]['login'] = 0;
    
    //Lake Forest Public Library , 48
    $arrLibrarData[48]['card'] = '21243000941360';
    $arrLibrarData[48]['login'] = 1;
     
    //Fountaindale Public Library District , 49
    $arrLibrarData[49]['card'] = '20401506967161';
    $arrLibrarData[49]['pin'] = '7161';
    $arrLibrarData[49]['login'] = 1;
    
    //Rockford Public Library , 50
    $arrLibrarData[50]['card'] = '21112000302222';
    $arrLibrarData[50]['pin'] = '2222';
    $arrLibrarData[50]['login'] = 1;
       
    //Brentwood Library , 51
    $arrLibrarData[51]['card'] = '';
    $arrLibrarData[51]['login'] = 0;
    
    //Marion County Public Library , 52
    $arrLibrarData[52]['card'] = '2001014496';
    $arrLibrarData[52]['login'] = 1;
    
    //Tappan Free Library , 53
    $arrLibrarData[53]['card'] = '22838000039641';
    $arrLibrarData[53]['pin'] = '2072';
    $arrLibrarData[53]['login'] = 1;
    
    //Sioux City Public Library , 55
    $arrLibrarData[55]['card'] = '23024002259821';
    $arrLibrarData[55]['login'] = 1;
    
    //Hubbard Public Library , 56
    $arrLibrarData[56]['card'] = '29832000033855';
    $arrLibrarData[56]['login'] = 1;
    
    //Riverhead Free Library , 57
    $arrLibrarData[57]['card'] = '20646200035240';
    $arrLibrarData[57]['login'] = 1;
    
    //Warner Library , 58
    $arrLibrarData[58]['card'] = '21033300333834';
    $arrLibrarData[58]['pin'] = '0113';
    $arrLibrarData[58]['login'] = 1;
    
    //Topeka & Shawnee County Public Library , 59
    $arrLibrarData[59]['card'] = '23247002747236';
    $arrLibrarData[59]['pin'] = '9999';
    $arrLibrarData[59]['login'] = 1;
    
    //Glenside Public Library , 60
    $arrLibrarData[60]['card'] = '21385000927350';
    $arrLibrarData[60]['pin'] = '6895';
    $arrLibrarData[60]['login'] = 1;
    
    //Charles M. Bailey Public Library , 61
    $arrLibrarData[61]['card'] = '26397002007615';
    $arrLibrarData[61]['login'] = 1;
    
    //Upper Saddle River Public Library , 62
    $arrLibrarData[62]['card'] = '29113005253040';
    $arrLibrarData[62]['pin'] = '';
    $arrLibrarData[62]['login'] = 0;
    
    //Lowell Public Library , 63
    $arrLibrarData[63]['email'] = '';
    $arrLibrarData[63]['password'] = '';
    $arrLibrarData[63]['login'] = 0;
    
    //Eastham Public Library , 64
    $arrLibrarData[64]['card'] = '1260000202001';
    $arrLibrarData[64]['login'] = 1;
    
    //Jericho Public Library , 65
    $arrLibrarData[65]['card'] = '21325000390662';
    $arrLibrarData[65]['login'] = 1;
    
    //Welles-Turner Memorial Library , 66
    $arrLibrarData[66]['card'] = '22512026934990';
    $arrLibrarData[66]['login'] = 1;
    
    //Jonathan Bourne Public Library , 67
    $arrLibrarData[67]['card'] = '1011300311111';
    $arrLibrarData[67]['login'] = 1;
    
    //ELA Area Public Library , 68
    $arrLibrarData[68]['card'] = '21241000911433';
    $arrLibrarData[68]['login'] = 1;
    
    //Innisfil Public Library , 69
    $arrLibrarData[69]['card'] = '21681000228348';
    $arrLibrarData[69]['pin'] = '1234';
    $arrLibrarData[69]['login'] = 1;
    
    //Half Hollow Hills Community Library , 70
    $arrLibrarData[70]['card'] = '21974007552970';
    $arrLibrarData[70]['login'] = 1;
    
    //Bridgeport Public Library , 71
    $arrLibrarData[71]['card'] = '24000031754882';
    $arrLibrarData[71]['login'] = 1;
    
    //Library Ideas Canada , 72
    $arrLibrarData[72]['email'] = '';
    $arrLibrarData[72]['password'] = '';
    $arrLibrarData[72]['login'] = 0;
    
    //Denver Public Library , 73
    $arrLibrarData[73]['card'] = 'D055561580';
    $arrLibrarData[73]['pin'] = '2010';
    $arrLibrarData[73]['login'] = 1;
    
    //Edmonton Public Library , 74
    $arrLibrarData[74]['card'] = '21221015118828';
    $arrLibrarData[74]['pin'] = '0432';
    $arrLibrarData[74]['login'] = 1;
    
    //McAlester Public Library , 75
    $arrLibrarData[75]['card'] = '20792001796471';
    $arrLibrarData[75]['login'] = 1;
     
    //Baldwin Public Library , 76
    $arrLibrarData[76]['card'] = '21552001158604';
    $arrLibrarData[76]['login'] = 1;
    
    //San Bruno Public Library , 77
    $arrLibrarData[77]['card'] = '29046146470893';
    $arrLibrarData[77]['pin'] = '8303';
    $arrLibrarData[77]['login'] = 1;
    
    //Carol Stream Public Library , 78
    $arrLibrarData[78]['card'] = '21319001296523';
    $arrLibrarData[78]['pin'] = '6523';
    $arrLibrarData[78]['login'] = 1;
    
    //Rutherford County Library , 79
    $arrLibrarData[79]['card'] = '28801000313766';
    $arrLibrarData[79]['pin'] = '32874';
    $arrLibrarData[79]['login'] = 1;
    
    //William F. Laman Public Library System , 80
    $arrLibrarData[80]['card'] = '27910501102577';
    $arrLibrarData[80]['pin'] = '5939';
    $arrLibrarData[80]['login'] = 1;
    
    //Manchester-by-the-Sea Public Library , 81
    $arrLibrarData[81]['card'] = '22124000006702';
    $arrLibrarData[81]['pin'] = 'hillBilly1948';
    $arrLibrarData[81]['login'] = 1;
     
    //Farmington Libraries , 82
    $arrLibrarData[82]['card'] = '22501016464613';
    $arrLibrarData[82]['pin'] = 'changeme';
    $arrLibrarData[82]['login'] = 1;
    
    //Bartholomew County Public Library , 83
    $arrLibrarData[83]['card'] = '22173000000926';
    $arrLibrarData[83]['login'] = 1;
    
    //Three Rivers Public Library , 84
    $arrLibrarData[84]['card'] = '21561000420170';
    $arrLibrarData[84]['login'] = 1;
      
    //Prince George's County Memorial Library System , 85
    $arrLibrarData[85]['card'] = '21268016667774';
    $arrLibrarData[85]['pin'] = '9139';
    $arrLibrarData[85]['login'] = 1;
    
    //Plainfield-Guilford Township Public Library , 86
    $arrLibrarData[86]['card'] = '21208049053600';
    $arrLibrarData[86]['login'] = 1;
    
    //West Vancouver Memorial Library , 87
    $arrLibrarData[87]['card'] = '21010002132912';
    $arrLibrarData[87]['login'] = 1;
      
    //Starke County Public Library System , 88
    $arrLibrarData[88]['card'] = '20000000017430';
    $arrLibrarData[88]['pin'] = '7323';
    $arrLibrarData[88]['login'] = 0;
    
    //Lorain Public Library System , 89
    $arrLibrarData[89]['card'] = '28082100652532';
    $arrLibrarData[89]['login'] = 1;
    
    //Scottsdale Public Library , 90
    $arrLibrarData[90]['card'] = '1000102328555';
    $arrLibrarData[90]['pin'] = '1234';
    $arrLibrarData[90]['login'] = 1;
    
    //Franklin Lakes Free Public Library , 91
    $arrLibrarData[91]['card'] = '';
    $arrLibrarData[91]['pin'] = '';
    $arrLibrarData[91]['login'] = 0;
    
    //Scarsdale Public Library , 92
    $arrLibrarData[92]['card'] = '21029300352568';
    $arrLibrarData[92]['pin'] = '1232';
    $arrLibrarData[92]['login'] = 1;
    
    //New Fairfield Free Public Library , 93
    $arrLibrarData[93]['email'] = '';
    $arrLibrarData[93]['password'] = '';
    $arrLibrarData[93]['login'] = 0;
    
    //Manitowoc Public Library , 94
    $arrLibrarData[94]['card'] = '23128000855215';
    $arrLibrarData[94]['pin'] = '3000';
    $arrLibrarData[94]['login'] = 1;
    
    //Rocky River Public Library , 95
    $arrLibrarData[95]['card'] = '';
    $arrLibrarData[95]['pin'] = '';
    $arrLibrarData[95]['login'] = 0;
    
    //Fairview Heights Public Library , 96
    $arrLibrarData[96]['card'] = '1001500092041';
    $arrLibrarData[96]['pin'] = '8826';
    $arrLibrarData[96]['login'] = 1;
    
    //Redwood City Public Library , 97
    $arrLibrarData[97]['card'] = '29045141978561';
    $arrLibrarData[97]['pin'] = '7020';
    $arrLibrarData[97]['login'] = 1;
    
    //Memorial Hall Library , 98
    $arrLibrarData[98]['card'] = '21330000097192';
    $arrLibrarData[98]['pin'] = '1234';
    $arrLibrarData[98]['login'] = 0;
    
    //Brown County Library (WI) , 99
    $arrLibrarData[99]['card'] = '29878003450182';
    $arrLibrarData[99]['pin'] = '3299';
    $arrLibrarData[99]['login'] = 1;
    
    //Tampa-Hillsborough County Public Library , 100
    $arrLibrarData[100]['card'] = '21667076248849';
    $arrLibrarData[100]['login'] = 1;
    
    //Greenwich Library, 101
    $arrLibrarData[101]['card'] = '21117003457485';
    $arrLibrarData[101]['login'] = 1;
    
    //Ruth Enlow Library of Garrett County, 103
    $arrLibrarData[103]['card'] = '22214010015734';
    $arrLibrarData[103]['pin'] = '321';
    $arrLibrarData[103]['login'] = 1;
    
    //Langley-Adams Library, 104
    $arrLibrarData[104]['card'] = '22121000503134';
    $arrLibrarData[104]['pin'] = '1732';
    $arrLibrarData[104]['login'] = 1;
    
    //Owatonna Public Library, 105
    $arrLibrarData[105]['card'] = '1101302202424';
    $arrLibrarData[105]['login'] = 1;
    
    //Monticello-Union Township Public Library, 106
    $arrLibrarData[106]['card'] = '';
    $arrLibrarData[106]['login'] = 0;
    
    //Alexander Public Library, 107
    $arrLibrarData[107]['email'] = '';
    $arrLibrarData[107]['password'] = '';
    $arrLibrarData[107]['login'] = 0;
    
    //Clearview Library District, 108
    $arrLibrarData[108]['card'] = '20214000536196';
    $arrLibrarData[108]['pin'] = '1234';
    $arrLibrarData[108]['login'] = 1;
    
    //Trenton Free Public Library, 109
    $arrLibrarData[109]['card'] = '26592000844544';
    $arrLibrarData[109]['login'] = 1;
    
    //Terrace Public Library, 110
    $arrLibrarData[110]['card'] = '25151000142200';
    $arrLibrarData[110]['pin'] = '2200';
    $arrLibrarData[110]['login'] = 1;
    
    //Lake Bluff Public Library, 111
    $arrLibrarData[111]['card'] = '27686000310214';
    $arrLibrarData[111]['login'] = 1;
    
    //Deforest Area Public Library, 113
    $arrLibrarData[113]['card'] = '';
    $arrLibrarData[113]['pin'] = '';
    $arrLibrarData[113]['login'] = 0;
    
    //Zion-Benton Public Library, 114
    $arrLibrarData[114]['card'] = '21126001871332';
    $arrLibrarData[114]['login'] = 1;
    
    //Henrico County Public Library, 115
    $arrLibrarData[115]['card'] = '28674003324415';
    $arrLibrarData[115]['login'] = 1;
    
    //Reuben Hoar Library, 116
    $arrLibrarData[116]['card'] = '29965000022666';
    $arrLibrarData[116]['pin'] = '0557';
    $arrLibrarData[116]['login'] = 0;
     
    //Burlingame Public Library, 117
    $arrLibrarData[117]['card'] = '29042144958906';
    $arrLibrarData[117]['pin'] = '1234';
    $arrLibrarData[117]['login'] = 1;
    
    //Northern Onondaga Public Library, 118
    $arrLibrarData[118]['card'] = '29850006738137';
    $arrLibrarData[118]['pin'] = '8137';
    $arrLibrarData[118]['login'] = 1;
    
    //Liverpool Public Library, 119
    $arrLibrarData[119]['card'] = '29850004356304';
    $arrLibrarData[119]['pin'] = '0310';
    $arrLibrarData[119]['login'] = 1;
    
    //Hillsdale Free Public Library, 120
    $arrLibrarData[120]['card'] = '';
    $arrLibrarData[120]['pin'] = '';
    $arrLibrarData[120]['login'] = 0;
    
    //Albert Wisner Public Library, 121
    $arrLibrarData[121]['card'] = '22844000293220';
    $arrLibrarData[121]['pin'] = '8917';
    $arrLibrarData[121]['login'] = 1;
    
    //Mount Pleasant Public Library, 122
    $arrLibrarData[122]['card'] = '21024300363509';
    $arrLibrarData[122]['pin'] = '0548';
    $arrLibrarData[122]['login'] = 1;
    
    
    //Volusia County Public Library, 123
    $arrLibrarData[123]['card'] = '22417000063115';
    $arrLibrarData[123]['pin'] = '2260';
    $arrLibrarData[123]['login'] = 1;
    
    
    //Guilderland Public Library, 124
    $arrLibrarData[124]['card'] = '28119000189312';
    $arrLibrarData[124]['pin'] = '2405';
    $arrLibrarData[124]['login'] = 1;
    
    //Craighead County Public Library, 125
    $arrLibrarData[125]['card'] = '23563001430404';
    $arrLibrarData[125]['pin'] = '3772';
    $arrLibrarData[125]['login'] = 1;
    
    //Jackson District Library, 126
    $arrLibrarData[126]['card'] = '26177000003265';
    $arrLibrarData[126]['login'] = 1;
    
    //The Urbana Free Library, 127
    $arrLibrarData[127]['card'] = '';
    $arrLibrarData[127]['login'] = 0;

    //Orange County Library System - Español, 128
    $arrLibrarData[128]['card'] = '';
    $arrLibrarData[128]['pin'] = '';
    $arrLibrarData[128]['login'] = 0;
    
    //Boone County Public Library, 129
    $arrLibrarData[129]['card'] = '204024601509';
    $arrLibrarData[129]['login'] = 1;
    
    //Newburyport Public Library, 130
    $arrLibrarData[130]['card'] = '21934000011473';
    $arrLibrarData[130]['login'] = 1;
    
    //Nevins Memorial Library, 132
    $arrLibrarData[132]['card'] = '21548000779606';
    $arrLibrarData[132]['login'] = 1;
    
    
    //Keller Public Library, 133
    $arrLibrarData[133]['card'] = '23595000481327';
    $arrLibrarData[133]['login'] = 1;
    
    
    //Clifton Park - Halfmoon Public Library, 134
    $arrLibrarData[134]['card'] = '';
    $arrLibrarData[134]['pin'] = '';
    $arrLibrarData[134]['login'] = 0;
    
    //DeWitt Community Library, 135
    $arrLibrarData[135]['card'] = '29850005573204';
    $arrLibrarData[135]['pin'] = '13214';
    $arrLibrarData[135]['login'] = 1;
    
    //Hendrick Hudson Free Library, 136
    $arrLibrarData[136]['card'] = '21016300126993';
    $arrLibrarData[136]['login'] = 1;
    
    
    //Brooks Free Library, 137
    $arrLibrarData[137]['card'] = '1010900363720';
    $arrLibrarData[137]['login'] = 1;
    
    
    //New York Public Library, 138
    $arrLibrarData[138]['card'] = '23333081863803';
    $arrLibrarData[138]['pin'] = '1115';
    $arrLibrarData[138]['login'] = 1;
    
    //Saint Paul Public Library, 139
    $arrLibrarData[139]['card'] = '22091009849970';
    $arrLibrarData[139]['pin'] = '123456';
    $arrLibrarData[139]['login'] = 1;
    
      
    //Sparta Public Library, 140
    $arrLibrarData[140]['email'] = '';
    $arrLibrarData[140]['password'] = '';
    $arrLibrarData[140]['login'] = 0;
    
    //Mentor Public Library, 141
    $arrLibrarData[141]['card'] = '23199003637553';
    $arrLibrarData[141]['login'] = 1;
    
    //Easton Public Library, 142
    $arrLibrarData[142]['card'] = '27777000211971';
    $arrLibrarData[142]['login'] = 1;
    
    //Salt Lake City Public Library, 143
    $arrLibrarData[143]['card'] = '21120010139831';
    $arrLibrarData[143]['login'] = 1;
    
    //J.V. Fletcher Library, 144
    $arrLibrarData[144]['card'] = '21990000483328';
    $arrLibrarData[144]['pin'] = '5555';
    $arrLibrarData[144]['login'] = 1;
    
    //Henderson District Libraries, 145
    $arrLibrarData[145]['card'] = '';
    $arrLibrarData[145]['pin'] = '';
    $arrLibrarData[145]['login'] = 0;
    
    //William E. Dermody Free Public Library, 146
    $arrLibrarData[146]['card'] = '';
    $arrLibrarData[146]['pin'] = '';
    $arrLibrarData[146]['login'] = 0;  
  
    //Huntsville-Madison County Public Library, 147
    $arrLibrarData[147]['card'] = '';
    $arrLibrarData[147]['pin'] = '';
    $arrLibrarData[147]['login'] = 0;
    
    //Missoula Public Library, 148
    $arrLibrarData[148]['email'] = '';
    $arrLibrarData[148]['password'] = '';
    $arrLibrarData[148]['login'] = 0;
      
    //Salt Lake County Library, 149
    $arrLibrarData[149]['card'] = '21181075027014';
    $arrLibrarData[149]['pin'] = '1234';
    $arrLibrarData[149]['login'] = 1;
    
    //West Nyack Free Library, 150
    $arrLibrarData[150]['card'] = '22846000072983';
    $arrLibrarData[150]['pin'] = '3186';
    $arrLibrarData[150]['login'] = 1;
    
    //L.E. Phillips Memorial Public Library, 151
    $arrLibrarData[151]['card'] = '2000186127';
    $arrLibrarData[151]['last_name'] = 'Falkenberg';
    $arrLibrarData[151]['login'] = 1;
    
    
    //Glen Ellyn Public Library, 152
    $arrLibrarData[152]['card'] = '21322001210536';
    $arrLibrarData[152]['login'] = 1;
    
    
    //Snow Library, 153
    $arrLibrarData[153]['card'] = '1011000222220';
    $arrLibrarData[153]['login'] = 1;
    
    
    //Kitchener Public Library, 154
    $arrLibrarData[154]['card'] = '29098003156420';
    $arrLibrarData[154]['pin'] = '7075';
    $arrLibrarData[154]['login'] = 1;
    
    
    //Seattle Public Library, 155
    $arrLibrarData[155]['card'] = '1000010671294';
    $arrLibrarData[155]['pin'] = '1111';
    $arrLibrarData[155]['login'] = 1;
    
    //Camarillo Public Library, 156
    $arrLibrarData[156]['card'] = '29301000102276';
    $arrLibrarData[156]['pin'] = '2276';
    $arrLibrarData[156]['login'] = 0;
    
    //Tuckahoe Public Library, 157
    $arrLibrarData[157]['card'] = '21034300114166';
    $arrLibrarData[157]['pin'] = '1349';
    $arrLibrarData[157]['login'] = 1;
    
    
    //Salina Free Library, 158
    $arrLibrarData[158]['card'] = '29850006461383';
    $arrLibrarData[158]['pin'] = '1234';
    $arrLibrarData[158]['login'] = 1;
    
    //Westlake Porter Public Library, 159
    $arrLibrarData[159]['card'] = 'PPPL0002602301';
    $arrLibrarData[159]['login'] = 1;
    
    //Evansville Vanderburgh Public Library, 160
    $arrLibrarData[160]['card'] = '';
    $arrLibrarData[160]['pin'] = '';
    $arrLibrarData[160]['login'] = 0;
    
    
    //Timberland Regional Library, 161
    $arrLibrarData[161]['card'] = 'D450627000';
    $arrLibrarData[161]['pin'] = '1890';
    $arrLibrarData[161]['login'] = 0;
    
    //Albany Public Library, 162
    $arrLibrarData[162]['card'] = '21182005091740';
    $arrLibrarData[162]['pin'] = '4300';
    $arrLibrarData[162]['login'] = 1;    
    
    
    //Beaver Dam Community Library, 163
    $arrLibrarData[163]['card'] = '23019001053324';
    $arrLibrarData[163]['pin'] = '1013';
    $arrLibrarData[163]['login'] = 1; 
    
    
    //Lewisboro Library, 164
    $arrLibrarData[164]['card'] = '21032300086368';
    $arrLibrarData[164]['pin'] = '5246';
    $arrLibrarData[164]['login'] = 1; 
    
    //Henrico Library, 165
    $arrLibrarData[165]['email'] = '';
    $arrLibrarData[165]['password'] = '';
    $arrLibrarData[165]['login'] = 0;     
    
    //Thayer Public Library, 166
    $arrLibrarData[166]['card'] = '21629010293208';
    $arrLibrarData[166]['pin'] = 'OCLN';
    $arrLibrarData[166]['login'] = 1;
    
    //Herrick District Library, 167
    $arrLibrarData[167]['card'] = '21309001825330';
    $arrLibrarData[167]['pin'] = '3720';
    $arrLibrarData[167]['login'] = 1;
    
    
    //Brimfield Public Library District, 168
    $arrLibrarData[168]['card'] = 'D873055909';
    $arrLibrarData[168]['pin'] = '1835';
    $arrLibrarData[168]['login'] = 1;
    
    
    //Upper Arlington Public Library, 169
    $arrLibrarData[169]['card'] = '2000253511';
    $arrLibrarData[169]['pin'] = '1234';
    $arrLibrarData[169]['login'] = 0;
      
    
    //Allegany County Library System, 170
    $arrLibrarData[170]['card'] = '21183014181886';
    $arrLibrarData[170]['pin'] = '1337';
    $arrLibrarData[170]['login'] = 1;
    
    
    //Lakeland Public Library, 171
    $arrLibrarData[171]['email'] = '';
    $arrLibrarData[171]['password'] = '';
    $arrLibrarData[171]['login'] = 0;
    
    
    //McKinley Memorial Library, 172
    $arrLibrarData[172]['card'] = '20480000029124';
    $arrLibrarData[172]['login'] = 1;
    
    
    //Morse Institute Library, 173
    $arrLibrarData[173]['card'] = '23016000634823';
    $arrLibrarData[173]['pin'] = 'gold';
    $arrLibrarData[173]['login'] = 1;
    
    
    //Baldwinsville Public Library, 174
    $arrLibrarData[174]['card'] = '29850004863382';
    $arrLibrarData[174]['pin'] = '1953';
    $arrLibrarData[174]['login'] = 1;
    
    
    
    //La Grange Public Library, 175
    $arrLibrarData[175]['card'] = '21320000615911';
    $arrLibrarData[175]['pin'] = '4101';
    $arrLibrarData[175]['login'] = 1;
    
    
    //Marcellus Free Library, 176
    $arrLibrarData[176]['card'] = '29850003025025';
    $arrLibrarData[176]['pin'] = '1234';
    $arrLibrarData[176]['login'] = 0;
    
    
    
    //Multnomah County Library, 177
    $arrLibrarData[177]['card'] = '21168028125748';
    $arrLibrarData[177]['pin'] = '5050';
    $arrLibrarData[177]['login'] = 1;
    
    
    //Norwalk Public Library, 178
    $arrLibrarData[178]['card'] = '';
    $arrLibrarData[178]['pin'] = '';
    $arrLibrarData[178]['login'] = 0;
    
    
    //Fayetteville Free Library, 179
    $arrLibrarData[179]['card'] = '29850004749318';
    $arrLibrarData[179]['pin'] = '2003';
    $arrLibrarData[179]['login'] = 0;
    
    
    //Millis Public Library, 180
    $arrLibrarData[180]['card'] = '26216000049892';
    $arrLibrarData[180]['login'] = 1;
    
    
    // Manlius Library, 181
    $arrLibrarData[181]['card'] = '29850000071071';
    $arrLibrarData[181]['login'] = 1;
    
    //Las Vegas - Clark County Library District, 182
    $arrLibrarData[182]['card'] = '21431003311398';
    $arrLibrarData[182]['pin'] = '1234';
    $arrLibrarData[182]['login'] = 1;
    
    //Prospect Heights Public Library, 183
    $arrLibrarData[183]['card'] = '21530000390097';
    $arrLibrarData[183]['login'] = 1;
    
    
    //Pierce County Library System, 184
    $arrLibrarData[184]['card'] = '29093006659561';
    $arrLibrarData[184]['pin'] = '1234';
    $arrLibrarData[184]['login'] = 1;
    
    //Billerica Public Library, 185
    $arrLibrarData[185]['card'] = '23934000732548';
    $arrLibrarData[185]['pin'] = '1234';
    $arrLibrarData[185]['login'] = 1;
    
    //Avon-Washington Township Public Library, 186
    $arrLibrarData[186]['card'] = '';
    $arrLibrarData[186]['login'] = 0;
    
    //Clifton Park – Halfmoon Public Library, 187
    $arrLibrarData[187]['card'] = '';
    $arrLibrarData[187]['pin'] = '';
    $arrLibrarData[187]['login'] = 0;
    
    
    //Atlantic County Library System, 188
    $arrLibrarData[188]['card'] = '21975000283851';
    $arrLibrarData[188]['pin'] = '1155';
    $arrLibrarData[188]['login'] = 1;
    
    
    //Public Library of Mount Vernon and Knox County, 189
    $arrLibrarData[189]['card'] = '21430628232831';
    $arrLibrarData[189]['pin'] = '2665';
    $arrLibrarData[189]['login'] = 1;
    
    
        
    //Aurora Public Library (CO), 190
    $arrLibrarData[190]['card'] = '21277006917115';
    $arrLibrarData[190]['login'] = 1;
    
    
    //Lawrenceburg Public Library, 191
    $arrLibrarData[191]['card'] = '25340629407348';
    $arrLibrarData[191]['login'] = 1;
    
    //Washington County Free Library, 192
    $arrLibrarData[192]['card'] = '22395002162509';
    $arrLibrarData[192]['login'] = 1;
    
    //New Castle Public Library, 193
    $arrLibrarData[193]['email'] = '';
    $arrLibrarData[193]['password'] = '';
    $arrLibrarData[193]['login'] = 0;
    
    
    //Onondaga Free Library, 194
    $arrLibrarData[194]['card'] = '';
    $arrLibrarData[194]['pin'] = '';
    $arrLibrarData[194]['login'] = 0;
    
    
    //Greensburg-Decatur County Public Library, 195
    $arrLibrarData[195]['card'] = '22826700223854';
    $arrLibrarData[195]['pin'] = '8724';
    $arrLibrarData[195]['login'] = 1;
    
    
    //Abbott Library, 196
    $arrLibrarData[196]['card'] = '20128000002142';
    $arrLibrarData[196]['pin'] = '7583';
    $arrLibrarData[196]['login'] = 1;
    
    
    //Plain City Public Library, 197
    $arrLibrarData[197]['card'] = '20305000180108';
    $arrLibrarData[197]['pin'] = '1234';
    $arrLibrarData[197]['login'] = 1;
    
    
    //Elbert County Library District, 198
    $arrLibrarData[198]['card'] = 'B12345';
    $arrLibrarData[198]['pin'] = '1111';
    $arrLibrarData[198]['login'] = 1;
    
    //Palisades Free Library, 199
    $arrLibrarData[199]['card'] = '22829000043402';
    $arrLibrarData[199]['pin'] = '0599';
    $arrLibrarData[199]['login'] = 1;
    
    
    //Tomkins Cove Public Library, 200
    $arrLibrarData[200]['card'] = '20305000180108';
    $arrLibrarData[200]['pin'] = '1234';
    $arrLibrarData[200]['login'] = 1;
    
    
    //Suffern Free Library , 201
    $arrLibrarData[201]['card'] = '22837000376805';
    $arrLibrarData[201]['pin'] = '2375';
    $arrLibrarData[201]['login'] = 1;
    
    //Lewiston Public Library , 202
    $arrLibrarData[202]['card'] = '24240000866131';
    $arrLibrarData[202]['login'] = 1;
    
    //Mastics-Moriches-Shirley Community Library , 203
    $arrLibrarData[203]['card'] = '063810368003';
    $arrLibrarData[203]['login'] = 1;
    
    
    //Sloatsburg Public Library , 204
    $arrLibrarData[204]['card'] = '22849000091011';
    $arrLibrarData[204]['pin'] = '2001';
    $arrLibrarData[204]['login'] = 1;
    
    //Bollinger County Library , 205
    $arrLibrarData[205]['card'] = 'emd0916';
    $arrLibrarData[205]['pin'] = '217pa';
    $arrLibrarData[205]['login'] = 0;
    
    //Marysville Public Library, 206
    $arrLibrarData[206]['card'] = '20231000247939';
    $arrLibrarData[206]['pin'] = '7939';
    $arrLibrarData[206]['login'] = 1;
    
    
    //Norwell Public Library, 207
    $arrLibrarData[207]['card'] = '21639000122754';
    $arrLibrarData[207]['pin'] = 'ocln1';
    $arrLibrarData[207]['login'] = 1;
    
    
    //Maxwell Memorial Library, 208
    $arrLibrarData[208]['card'] = '29850005366666';
    $arrLibrarData[208]['pin'] = '1414';
    $arrLibrarData[208]['login'] = 0;
    
    
    //Mamie Doud Eisenhower Public Library, 209
    $arrLibrarData[209]['card'] = 'd041140966';
    $arrLibrarData[209]['login'] = 1;
    
    
    //Muskingum County Library, 210
    $arrLibrarData[210]['card'] = 'AF711';
    $arrLibrarData[210]['pin'] = '1111';
    $arrLibrarData[210]['login'] = 0;
    
     
    //Piermont Public Library, 211
    $arrLibrarData[211]['card'] = '22831000048464';
    $arrLibrarData[211]['pin'] = '4595';
    $arrLibrarData[211]['login'] = 1;
    
    
    //Willard Public Library, 212
    $arrLibrarData[212]['card'] = '23330002960070';
    $arrLibrarData[212]['pin'] = '5166';
    $arrLibrarData[212]['login'] = 1;
    
    
    //Lake Wales Public Library, 213
    $arrLibrarData[213]['email'] = '';
    $arrLibrarData[213]['password'] = '';
    $arrLibrarData[213]['login'] = 0;
    
    
    //Pikes Peak Library District, 215
    $arrLibrarData[215]['card'] = '420378499';
    $arrLibrarData[215]['pin'] = '1234';
    $arrLibrarData[215]['login'] = 1;
    
    
    //Finkelstein Memorial Library, 216
    $arrLibrarData[216]['card'] = '22191001610428';
    $arrLibrarData[216]['pin'] = '1111';
    $arrLibrarData[216]['login'] = 1; 
    
     
    //Nanuet Public Library, 217
    $arrLibrarData[217]['card'] = '22824000335759';
    $arrLibrarData[217]['pin'] = '4281';
    $arrLibrarData[217]['login'] = 1; 


    //The Nyack Library, 218
    $arrLibrarData[218]['card'] = '22827000450031';
    $arrLibrarData[218]['pin'] = '6875';
    $arrLibrarData[218]['login'] = 0; 


    //Orangeburg Public Library, 219
    $arrLibrarData[219]['card'] = '22828000063980';
    $arrLibrarData[219]['pin'] = '0341';
    $arrLibrarData[219]['login'] = 1;

    //Pearl River Public Library, 220
    $arrLibrarData[220]['card'] = '22830000133748';
    $arrLibrarData[220]['pin'] = '1505';
    $arrLibrarData[220]['login'] = 1;


    //Valley Cottage Free Library, 221
    $arrLibrarData[221]['card'] = '22841000253576';
    $arrLibrarData[221]['pin'] = '0001';
    $arrLibrarData[221]['login'] = 1;


    //Whitley County Library, 222
    $arrLibrarData[222]['card'] = '20695100031122';
    $arrLibrarData[222]['pin'] = '1234';
    $arrLibrarData[222]['login'] = 1;


    //Haverstraw King's Daughters Public Library, 223
    $arrLibrarData[223]['card'] = '22835000488935';
    $arrLibrarData[223]['pin'] = '3800';
    $arrLibrarData[223]['login'] = 1;


    //Stevens Memorial Library, 224
    $arrLibrarData[224]['card'] = '21478000610033';
    $arrLibrarData[224]['pin'] = '0127';
    $arrLibrarData[224]['login'] = 0;


    //Lee County Library System, 225
    $arrLibrarData[225]['card'] = '23069011873377';
    $arrLibrarData[225]['pin'] = '5555';
    $arrLibrarData[225]['login'] = 1;

    
    //Green Hills Public Library, 226
    $arrLibrarData[226]['card'] = '21814000691427';
    $arrLibrarData[226]['pin'] = '8611';
    $arrLibrarData[226]['login'] = 1;


    //Ida Rupp Public Library, 227
    $arrLibrarData[227]['card'] = '23567000282815';
    $arrLibrarData[227]['pin'] = '3212';
    $arrLibrarData[227]['login'] = 1;

    
    //Arapahoe Library District, 228
    $arrLibrarData[228]['card'] = '21393006924868';
    $arrLibrarData[228]['last_name'] = 'Freegal';
    $arrLibrarData[228]['login'] = 0;

    
    //Commerce Township Community Library, 230
    $arrLibrarData[230]['card'] = '29098001212308';
    $arrLibrarData[230]['login'] = 1;


    //Paul Sawyier Public Library, 231
    $arrLibrarData[231]['card'] = '491000081300';
    $arrLibrarData[231]['pin'] = '1212';
    $arrLibrarData[231]['login'] = 1;
    
    
    //Richland Public Library, 232
    $arrLibrarData[232]['card'] = '23629000049048';
    $arrLibrarData[232]['pin'] = '1234';
    $arrLibrarData[232]['login'] = 1;    
    
    
    //Pueblo City-County Library District, 233
    $arrLibrarData[233]['card'] = '1222204309313';
    $arrLibrarData[233]['pin'] = '4715';
    $arrLibrarData[233]['login'] = 0;    
    
    
   //Parmly Billings Library, 234
    $arrLibrarData[234]['card'] = '21238003330223';
    $arrLibrarData[234]['pin'] = '6061';
    $arrLibrarData[234]['login'] = 0;
    
    
   //Free Library of Philadelphia, 235
    $arrLibrarData[235]['card'] = '22222044882276';
    $arrLibrarData[235]['pin'] = '1234';
    $arrLibrarData[235]['login'] = 1; 
    
    
    //Amesbury Public Library, 237
    $arrLibrarData[237]['card'] = '22114000904568';
    $arrLibrarData[237]['pin'] = 'freegal1';
    $arrLibrarData[237]['login'] = 1; 
       
    
    //Indian Trails Public Library, 238
    $arrLibrarData[238]['card'] = '21125002584449';
    $arrLibrarData[238]['pin'] = '2212';
    $arrLibrarData[238]['login'] = 1;
    
    
    //Hyannis Library Association, 239
    $arrLibrarData[239]['card'] = '1010100463601';
    $arrLibrarData[239]['login'] = 1;
    
    //Johnson City Public Library, 240
    $arrLibrarData[240]['card'] = '';
    $arrLibrarData[240]['login'] = 0;
    
    //Bertha Voyer Memorial Library, 241
    $arrLibrarData[241]['email'] = '';
    $arrLibrarData[241]['password'] = '';
    $arrLibrarData[241]['login'] = 0;
    
    
    //Essex County Library, 242
    $arrLibrarData[242]['card'] = '26489001873483';
    $arrLibrarData[242]['pin'] = '7130';
    $arrLibrarData[242]['login'] = 0;
    

    //Omaha Public Library, 243
    $arrLibrarData[243]['card'] = '23149005119639';
    $arrLibrarData[243]['pin'] = '1234';
    $arrLibrarData[243]['login'] = 1;


    //Orange Public Library, 244
    $arrLibrarData[244]['card'] = '22357003534722';
    $arrLibrarData[244]['pin'] = '20080101';
    $arrLibrarData[244]['login'] = 1;


    //Field Library, 245
    $arrLibrarData[245]['card'] = '21022300135976';
    $arrLibrarData[245]['pin'] = '1887';
    $arrLibrarData[245]['login'] = 1;


    //Humboldt Public Library, 246
    $arrLibrarData[246]['card'] = '20037000060556';
    $arrLibrarData[246]['pin'] = '0556';
    $arrLibrarData[246]['login'] = 1;
  
  
    //Williamsburg Regional Library, 247
    $arrLibrarData[247]['card'] = '21688002236146';
    $arrLibrarData[247]['login'] = 1;

    //Fort Dodge Public Library, 248
    $arrLibrarData[248]['card'] = '23711000411723';
    $arrLibrarData[248]['pin'] = '1723';
    $arrLibrarData[248]['login'] = 1;


    //Greenville Public Library, 249
    $arrLibrarData[249]['card'] = '22051000261450';
    $arrLibrarData[249]['login'] = 1;


    //Gail Borden Public Library District, 250
    $arrLibrarData[250]['card'] = '21113003515877';
    $arrLibrarData[250]['login'] = 1;


    //Schaumburg Township District Library, 251
    $arrLibrarData[251]['card'] = '21257006348420';
    $arrLibrarData[251]['pin'] = '1234';
    $arrLibrarData[251]['login'] = 0;


    //Wethersfield Public Library, 252
    $arrLibrarData[252]['card'] = '';
    $arrLibrarData[252]['pin'] = '';
    $arrLibrarData[252]['login'] = 0;

  
    //Milton Public Library, 253
    $arrLibrarData[253]['card'] = '22022012866008';
    $arrLibrarData[253]['pin'] = '1234';
    $arrLibrarData[253]['login'] = 0;


    //Hoboken Public Library , 254
    $arrLibrarData[254]['card'] = '';
    $arrLibrarData[254]['pin'] = '';
    $arrLibrarData[254]['login'] = 0;


    //Cumberland Public Library , 255
    $arrLibrarData[255]['card'] = '20082000407753';
    $arrLibrarData[255]['login'] = 1;

    //Warwick Public Library , 256
    $arrLibrarData[256]['card'] = '22080000197083';
    $arrLibrarData[256]['login'] = 1;

    //San Antonio Public Library , 260
    $arrLibrarData[260]['card'] = '21551024082858';
    $arrLibrarData[260]['login'] = 1;
    
    //Hennepin County Library , 261
    $arrLibrarData[261]['card'] = '';
    $arrLibrarData[261]['pin'] = '';
    $arrLibrarData[261]['login'] = 0;

    //Brookfield Public Library , 262
    $arrLibrarData[262]['card'] = '20056000492676';
    $arrLibrarData[262]['pin'] = '8213';
    $arrLibrarData[262]['login'] = 1;

    //Nashville Test Library , 263
    $arrLibrarData[263]['card'] = '';
    $arrLibrarData[263]['pin'] = '';
    $arrLibrarData[263]['login'] = 0;


    //North Castle Public Library , 264
    $arrLibrarData[264]['card'] = '21001310049294';
    $arrLibrarData[264]['pin'] = '4080';
    $arrLibrarData[264]['login'] = 1;
   
   
    //Charles City Public Library , 265
    $arrLibrarData[265]['card'] = '';
    $arrLibrarData[265]['pin'] = '';
    $arrLibrarData[265]['login'] = 0;
   
    
   //Saint Tammany Parish Library , 266
    $arrLibrarData[266]['card'] = '21756004824787';
    $arrLibrarData[266]['login'] = 1;
   
   //Fort McMurray Public Library , 267
    $arrLibrarData[267]['card'] = '25555000907877';
    $arrLibrarData[267]['pin'] = '7877';
    $arrLibrarData[267]['login'] = 1;
   
   //Weston Public Library , 268
    $arrLibrarData[268]['card'] = '24871000302758';
    $arrLibrarData[268]['login'] = 1;
   
   //Weston Public Library , 269
    $arrLibrarData[269]['card'] = '';
    $arrLibrarData[269]['pin'] = '';
    $arrLibrarData[269]['login'] = 0;   
   
   
    //Pinellas Public Library Cooperative , 270
    $arrLibrarData[270]['card'] = '22410002639693';
    $arrLibrarData[270]['pin'] = 'deschaine';
    $arrLibrarData[270]['login'] = 1;   
   
   
    //Pinellas Public Library Cooperative , 271
    $arrLibrarData[271]['card'] = '23941000462620';
    $arrLibrarData[271]['last_name'] = 'ayres';
    $arrLibrarData[271]['login'] = 1;   
   
   
   //Canton Public Library , 273
    $arrLibrarData[273]['card'] = '21631000440500';
    $arrLibrarData[273]['pin'] = 'ri2ma20';
    $arrLibrarData[273]['login'] = 1;
   
   
    //Bloomfield Township Public Library , 274
    $arrLibrarData[274]['card'] = '21160300048854';
    $arrLibrarData[274]['last_name'] = 'Patron';
    $arrLibrarData[274]['login'] = 1;
   
   
    //Mason City Public Library , 275
    $arrLibrarData[275]['card'] = '9000000025';
    $arrLibrarData[275]['login'] = 1;
    
    
    //Novi Public Library , 277
    $arrLibrarData[277]['card'] = '29066000087765';
    $arrLibrarData[277]['pin'] = '1234';
    $arrLibrarData[277]['login'] = 1;
    
    
    
    //Marshall County Public Library , 278
    $arrLibrarData[278]['card'] = '';
    $arrLibrarData[278]['pin'] = '';
    $arrLibrarData[278]['login'] = 0;
    
    
    //Middlebury Community Public Library , 280
    $arrLibrarData[280]['card'] = '25601000000014';
    $arrLibrarData[280]['pin'] = '1407';
    $arrLibrarData[280]['login'] = 1;
    
    
    //Schlow Centre Region Library , 281
    $arrLibrarData[281]['card'] = '22379000359820';
    $arrLibrarData[281]['login'] = 1;
    
    
    //Normal Public Library , 282
    $arrLibrarData[282]['card'] = 'D031352333';
    $arrLibrarData[282]['pin'] = '1234';
    $arrLibrarData[282]['login'] = 0;
    
    
    //North Shelby Library , 283
    $arrLibrarData[283]['card'] = '21726001963128';
    $arrLibrarData[283]['pin'] = '3128';
    $arrLibrarData[283]['login'] = 1;
    
    
    //Daviess County Public Library , 284
    $arrLibrarData[284]['card'] = '23307013599891';
    $arrLibrarData[284]['login'] = 1;
    
    //Chambers County Library System , 285
    $arrLibrarData[285]['card'] = '';
    $arrLibrarData[285]['pin'] = '';
    $arrLibrarData[285]['login'] = 0;
    
    
    //Monroe County Public Library , 287
    $arrLibrarData[287]['card'] = '21477003226888';
    $arrLibrarData[287]['pin'] = '1234';
    $arrLibrarData[287]['login'] = 1;
    
    //Norfolk County Public Library , 288
    $arrLibrarData[288]['card'] = '';
    $arrLibrarData[288]['pin'] = '';
    $arrLibrarData[288]['login'] = 0;
   
   
    //Woonsocket Harris Public Library , 289
    $arrLibrarData[289]['card'] = '23013000440830';
    $arrLibrarData[289]['pin'] = '4206';
    $arrLibrarData[289]['login'] = 1; 
   
   
    //Beaman Memorial Public Library , 290
    $arrLibrarData[290]['email'] = '';
    $arrLibrarData[290]['password'] = '';
    $arrLibrarData[290]['login'] = 0; 
   
   
    //Osceola County Library System , 291
    $arrLibrarData[291]['card'] = '';
    $arrLibrarData[291]['pin'] = '';
    $arrLibrarData[291]['login'] = 0; 
   
    //Palos Verdes Library District , 292
    $arrLibrarData[292]['card'] = '29350002022743';
    $arrLibrarData[292]['login'] = 1;
    
    //Tacoma Public Library , 293
    $arrLibrarData[293]['card'] = 'P07758866';
    $arrLibrarData[293]['pin'] = '3333';
    $arrLibrarData[293]['login'] = 0;
    
    
    //The Millicent Library , 294
    $arrLibrarData[294]['card'] = '22035000469577';
    $arrLibrarData[294]['login'] = 1;
    
    //Scott County Public Library , 295
    $arrLibrarData[295]['card'] = '25820431286246';
    $arrLibrarData[295]['pin'] = '4444';
    $arrLibrarData[295]['login'] = 1;
    
    
    //Howland Public Library , 296
    $arrLibrarData[296]['card'] = '22912000266726';
    $arrLibrarData[296]['login'] = 1;
    
    //Round Lake Library , 297
    $arrLibrarData[297]['card'] = '';
    $arrLibrarData[297]['pin'] = '';
    $arrLibrarData[297]['login'] = 0;    
     
    
    //Robert W. Barlow Memorial Library , 299
    $arrLibrarData[299]['card'] = '26478000104560';
    $arrLibrarData[299]['pin'] = '4560';
    $arrLibrarData[299]['login'] = 1;
    
     
    //Hampton Public Library , 300
    $arrLibrarData[300]['card'] = '20033110011027';
    $arrLibrarData[300]['pin'] = '1027';
    $arrLibrarData[300]['login'] = 1;
    

    //White Plains Public Library , 301
    $arrLibrarData[301]['card'] = '21544001131251';
    $arrLibrarData[301]['pin'] = 'test';
    $arrLibrarData[301]['login'] = 1;
    
    
    //Garner Public Library , 302
    $arrLibrarData[302]['card'] = '20030000000003';
    $arrLibrarData[302]['pin'] = '0003';
    $arrLibrarData[302]['login'] = 1;
    
    //Wadsworth Public Library , 303
    $arrLibrarData[303]['card'] = '10013102024982';
    $arrLibrarData[303]['pin'] = '5761';
    $arrLibrarData[303]['login'] = 1;
    
    //Dobbs Ferry Public Library , 304
    $arrLibrarData[304]['card'] = '21007300136348';
    $arrLibrarData[304]['pin'] = '6614';
    $arrLibrarData[304]['login'] = 1;
    
    
    //Fall River Public Library , 305
    $arrLibrarData[305]['card'] = '22036101295630';
    $arrLibrarData[305]['pin'] = '9999';
    $arrLibrarData[305]['login'] = 1;
    
    
    //Grant County Library , 307
    $arrLibrarData[307]['card'] = '20430100136139';
    $arrLibrarData[307]['pin'] = '2468';
    $arrLibrarData[307]['login'] = 1;
    
    //Alsip-Merrionette Park Public Library District , 308
    $arrLibrarData[308]['card'] = '21145000421725';
    $arrLibrarData[308]['login'] = 1;
    
    
    //Eagle Valley Library District , 311
    $arrLibrarData[311]['card'] = '4060001916233';
    $arrLibrarData[311]['login'] = 1;
    
    
    //Minoa Free Library , 312
    $arrLibrarData[312]['card'] = '29850002259294';
    $arrLibrarData[312]['pin'] = '081996';
    $arrLibrarData[312]['login'] = 1;
    
    
    //Windsor Public Library , 313
    $arrLibrarData[313]['card'] = 'U1536000255550';
    $arrLibrarData[313]['pin'] = '2556770';
    $arrLibrarData[313]['login'] = 1;    
    
    
    //Bayport-Blue Point Public Library , 314
    $arrLibrarData[314]['card'] = '20613001142682';
    $arrLibrarData[314]['login'] = 1;    
    
    
    //Santa Clarita Public Library , 315
    $arrLibrarData[315]['card'] = '29135000000027';
    $arrLibrarData[315]['pin'] = '6041';
    $arrLibrarData[315]['login'] = 1; 
    
    
    //Berthoud Community Library District , 316
    $arrLibrarData[316]['email'] = '';
    $arrLibrarData[316]['password'] = '';
    $arrLibrarData[316]['login'] = 0; 
    
    
    //Barren County Public Library , 318
    $arrLibrarData[318]['card'] = '20105100000903';
    $arrLibrarData[318]['pin'] = '0903';
    $arrLibrarData[318]['login'] = 1; 
    
    
    //Goodnow Library , 319
    $arrLibrarData[319]['card'] = '24866000423695';
    $arrLibrarData[319]['login'] = 1;
    
    
    //North Vancouver District Public Library , 320
    $arrLibrarData[320]['card'] = '23141001859177';
    $arrLibrarData[320]['login'] = 1;
    
    //Muskegon Area District Library , 321
    $arrLibrarData[321]['card'] = '21368004079030';
    $arrLibrarData[321]['pin'] = '1234';
    $arrLibrarData[321]['login'] = 1;
    
    
    //Dartmouth Public Libraries , 323
    $arrLibrarData[323]['card'] = '22034001025165';
    $arrLibrarData[323]['login'] = 1;
    
    //Coal City Public Library District , 324
    $arrLibrarData[324]['card'] = '25920000277509';
    $arrLibrarData[324]['pin'] = '7509';
    $arrLibrarData[324]['login'] = 1;
    
    
    //Los Angeles Public Library , 325
    $arrLibrarData[325]['card'] = '27244054214252';
    $arrLibrarData[325]['pin'] = '1111';
    $arrLibrarData[325]['login'] = 1;
    
    
    //Richland County Public Library , 326
    $arrLibrarData[326]['card'] = '20080102378831';
    $arrLibrarData[326]['pin'] = '1234';
    $arrLibrarData[326]['login'] = 1;
    
    
    //Salem Public Library , 327
    $arrLibrarData[327]['card'] = '23610041414490';
    $arrLibrarData[327]['pin'] = '2222';
    $arrLibrarData[327]['login'] = 1;
    
    
    //Wilmington Memorial Library , 328
    $arrLibrarData[328]['card'] = '22136000482578';
    $arrLibrarData[328]['pin'] = '1234';
    $arrLibrarData[328]['login'] = 1;
    
    
    //Merrimac Public Library , 329
    $arrLibrarData[329]['card'] = '22125000109883';
    $arrLibrarData[329]['pin'] = 'warmrocks7071';
    $arrLibrarData[329]['login'] = 1;
    
    //Salisbury Public Library , 330
    $arrLibrarData[330]['card'] = '22131000078730';
    $arrLibrarData[330]['pin'] = '3333';
    $arrLibrarData[330]['login'] = 1;
    
    
    //Wood Dale Public Library , 331
    $arrLibrarData[331]['card'] = '21687000251115';
    $arrLibrarData[331]['pin'] = '1115';
    $arrLibrarData[331]['login'] = 1;
    
    
    //Vernon Area Library District , 332
    $arrLibrarData[332]['card'] = '21968001037038';
    $arrLibrarData[332]['login'] = 1;
    
    
    //Ionia Community Library , 333
    $arrLibrarData[333]['card'] = '21347000248054';
    $arrLibrarData[333]['pin'] = '1234';
    $arrLibrarData[333]['login'] = 1;
    
    
    //Newburgh Free Library , 334
    $arrLibrarData[334]['card'] = '22826001097601';
    $arrLibrarData[334]['pin'] = '3613';
    $arrLibrarData[334]['login'] = 1;
    
    
    //Peters Township Library , 335
    $arrLibrarData[335]['email'] = '';
    $arrLibrarData[335]['password'] = '';
    $arrLibrarData[335]['login'] = 0;
    
    
    //Hartford Public Library , 336
    $arrLibrarData[336]['card'] = '22520028870617';
    $arrLibrarData[336]['login'] = 1;
    
    
    //Shelby County Public Library , 339
    $arrLibrarData[339]['card'] = '23610703985266';
    $arrLibrarData[339]['pin'] = '5266';
    $arrLibrarData[339]['login'] = 1;
    
    
    //Tuxedo Park Library , 340
    $arrLibrarData[340]['card'] = '22840000086663';
    $arrLibrarData[340]['pin'] = '1234';
    $arrLibrarData[340]['login'] = 1;
    
    
    //Martin County Public Library , 341
    $arrLibrarData[341]['card'] = '20277000031211';
    $arrLibrarData[341]['pin'] = '1211';
    $arrLibrarData[341]['login'] = 1;
    
    
    //Eldredge Public Library , 342
    $arrLibrarData[342]['card'] = '1190000103661';
    $arrLibrarData[342]['login'] = 1;
    
    
    //Saline District Library , 343
    $arrLibrarData[343]['card'] = '24604030135579';
    $arrLibrarData[343]['login'] = 1;
    
    
    //Moffat Library of Washingtonville , 344
    $arrLibrarData[344]['card'] = '22845000262404';
    $arrLibrarData[344]['pin'] = '5829';
    $arrLibrarData[344]['login'] = 1;
    
    
    //Grand County Public Library , 345
    $arrLibrarData[345]['card'] = 'P0005847';
    $arrLibrarData[345]['pin'] = 'Lund';
    $arrLibrarData[345]['login'] = 1;
    
    
    //Hancock County Public Library , 346
    $arrLibrarData[346]['card'] = '21202000356524';
    $arrLibrarData[346]['login'] = 1;
    
    
    //Katonah Village Library , 347
    $arrLibrarData[347]['card'] = '21013100000015';
    $arrLibrarData[347]['pin'] = '3988';
    $arrLibrarData[347]['login'] = 1;
    
    
    //Newport (RI) Public Library , 348
    $arrLibrarData[348]['card'] = '21540000506931';
    $arrLibrarData[348]['login'] = 1;
    
    
    //Holbrook Public Library , 349
    $arrLibrarData[349]['card'] = '21635000053718';
    $arrLibrarData[349]['pin'] = 'aggie';
    $arrLibrarData[349]['login'] = 1;
    
    
    //Monroe Township Public Library , 350
    $arrLibrarData[350]['card'] = '29370000897110';
    $arrLibrarData[350]['pin'] = '1583';
    $arrLibrarData[350]['login'] = 1;
    
    
    //Prince George Public Library , 351
    $arrLibrarData[351]['card'] = '25197002406515';
    $arrLibrarData[351]['pin'] = '1111';
    $arrLibrarData[351]['login'] = 1;
    
    
    //Dorr Township Library , 352
    $arrLibrarData[352]['card'] = '21341000151616';
    $arrLibrarData[352]['login'] = 1;
    
    
    //Australian Capital Territory Library , 353
    $arrLibrarData[353]['card'] = '';
    $arrLibrarData[353]['pin'] = '';
    $arrLibrarData[353]['login'] = 0;


    //Wollongong City Council Library , 354
    $arrLibrarData[354]['card'] = '22500003083535';
    $arrLibrarData[354]['pin'] = '1234';
    $arrLibrarData[354]['login'] = 1;


    //Greater Victoria Public Library , 355
    $arrLibrarData[355]['card'] = '29066007517145';
    $arrLibrarData[355]['pin'] = 'devtest';
    $arrLibrarData[355]['login'] = 1;   

    //Saugatuck-Douglas District Library , 356
    $arrLibrarData[356]['card'] = '21371000161331';
    $arrLibrarData[356]['pin'] = '2211';
    $arrLibrarData[356]['login'] = 1; 

    //Matawan-Aberdeen Public Library , 357
    $arrLibrarData[357]['card'] = '27834000229360';
    $arrLibrarData[357]['pin'] = '9360';
    $arrLibrarData[357]['login'] = 1;   

    
    //Vineyard Haven Public Library , 358
    $arrLibrarData[358]['card'] = '1011400237968';
    $arrLibrarData[358]['pin'] = '2020';
    $arrLibrarData[358]['login'] = 1;   

    
    //Dennis Public Library , 359
    $arrLibrarData[359]['card'] = '1340000201250';
    $arrLibrarData[359]['pin'] = '5588';
    $arrLibrarData[359]['login'] = 1; 

    //Edgartown Public Library , 360
    $arrLibrarData[360]['card'] = '1010800252403';
    $arrLibrarData[360]['pin'] = '1234';
    $arrLibrarData[360]['login'] = 1;     
    
    //Edgartown Public Library , 361
    $arrLibrarData[361]['card'] = '23946000010001';
    $arrLibrarData[361]['pin'] = 'GoFreegal1';
    $arrLibrarData[361]['login'] = 1;
    
    
    //North Brunswick Free Public Library , 362
    $arrLibrarData[362]['card'] = '29302100066817';
    $arrLibrarData[362]['pin'] = '8482';
    $arrLibrarData[362]['login'] = 1;
    
    
    //County of Los Angeles Public Library , 363
    $arrLibrarData[363]['card'] = '1111601507803';
    $arrLibrarData[363]['login'] = 1;
    
    
    //Spokane Public Library , 364
    $arrLibrarData[364]['card'] = '27413203728484';
    $arrLibrarData[364]['pin'] = '1111';
    $arrLibrarData[364]['login'] = 1;
    
    
    //Bellevue Public Library , 365
    $arrLibrarData[365]['card'] = '28072900000008';
    $arrLibrarData[365]['login'] = 1;
    
    
    //Cleveland Heights - University Heights Public Library , 366
    $arrLibrarData[366]['card'] = '28073900000006';
    $arrLibrarData[366]['login'] = 1;
    
    
    //Cleveland Public Library , 367
    $arrLibrarData[367]['card'] = '28074900000004';
    $arrLibrarData[367]['login'] = 1;
    
    //Euclid Public Library , 368
    $arrLibrarData[368]['card'] = '';
    $arrLibrarData[368]['login'] = 0;
    
    
    //Hudson Library & Historical Society , 369
    $arrLibrarData[369]['card'] = '28080900000001';
    $arrLibrarData[369]['login'] = 1;
    
    //Madison Public Library , 370
    $arrLibrarData[370]['card'] = '1002091111117';
    $arrLibrarData[370]['login'] = 1;
    
    //Medina County District Library , 371
    $arrLibrarData[371]['card'] = '28083900000005';
    $arrLibrarData[371]['login'] = 1;
    
    //Sandusky Public Library , 372
    $arrLibrarData[372]['card'] = '1000991111112';
    $arrLibrarData[372]['login'] = 1;
    
    //Twinsburg Public Library , 373
    $arrLibrarData[373]['card'] = '28087900000006';
    $arrLibrarData[373]['login'] = 1;
    
    
    //Wickliffe Public Library , 374
    $arrLibrarData[374]['card'] = '28088900000004';
    $arrLibrarData[374]['login'] = 1;
    
    //Willoughby - Eastlake Public Library , 375
    $arrLibrarData[375]['card'] = '28089900000002';
    $arrLibrarData[375]['login'] = 1;    
    
    //Bartlett Public Library District , 376
    $arrLibrarData[376]['card'] = '29800001127794';
    $arrLibrarData[376]['pin'] = '1234';
    $arrLibrarData[376]['login'] = 1;
    
    
    //Melbourne Library Service , 377
    $arrLibrarData[377]['card'] = '20021000000001';
    $arrLibrarData[377]['pin'] = '1688';
    $arrLibrarData[377]['login'] = 1;
    
    
    //Hamilton Public Library , 378
    $arrLibrarData[378]['card'] = '22022012866008';
    $arrLibrarData[378]['pin'] = '1234';
    $arrLibrarData[378]['login'] = 1;
    
    
    //Mahwah Public Library , 379
    $arrLibrarData[379]['card'] = '';
    $arrLibrarData[379]['pin'] = '';
    $arrLibrarData[379]['login'] = 0;
    
    //Indianapolis Marion County Public Library , 380
    $arrLibrarData[380]['card'] = '21978025292113';
    $arrLibrarData[380]['pin'] = '2113';
    $arrLibrarData[380]['login'] = 1;
    
    
    //Yarra Plenty Regional Library , 381
    $arrLibrarData[381]['card'] = '11380039';
    $arrLibrarData[381]['pin'] = '1234';
    $arrLibrarData[381]['login'] = 1;
    
    
    //Whitehorse-Manningham Regional Library Corporation , 382
    $arrLibrarData[382]['card'] = '20007005116505';
    $arrLibrarData[382]['login'] = 0;
    
    
    //Spotswood Public Library , 383
    $arrLibrarData[383]['card'] = '29384000160379';
    $arrLibrarData[383]['pin'] = '2881';
    $arrLibrarData[383]['login'] = 1;
    
    
    //South River Public Library , 384
    $arrLibrarData[384]['card'] = '29308000259642';
    $arrLibrarData[384]['pin'] = '7890';
    $arrLibrarData[384]['login'] = 1;
    
    //Matheson Memorial Library , 385
    $arrLibrarData[385]['card'] = '22678000195104';
    $arrLibrarData[385]['pin'] = '1961';
    $arrLibrarData[385]['login'] = 1;
    

    //Redford Township District Library , 386
    $arrLibrarData[386]['card'] = '29009000831709';
    $arrLibrarData[386]['pin'] = '1234';
    $arrLibrarData[386]['login'] = 1;
    
    
    //McCracken County Public Library , 387
    $arrLibrarData[387]['card'] = '20001002041238';
    $arrLibrarData[387]['login'] = 1;
    
    
    //Sterling Public Library , 388
    $arrLibrarData[388]['card'] = 'A9/953050522';
    $arrLibrarData[388]['pin'] = '0000';
    $arrLibrarData[388]['login'] = 1;
    
    
    //Casey-Cardinia Library Corporation , 389
    $arrLibrarData[389]['card'] = 'A5738504';
    $arrLibrarData[389]['pin'] = '8504';
    $arrLibrarData[389]['login'] = 1;
    
    //Groton Public Library , 390
    $arrLibrarData[390]['card'] = '';
    $arrLibrarData[390]['pin'] = '';
    $arrLibrarData[390]['login'] = 0;
    
    //Hurstville City Library , 391
    $arrLibrarData[391]['card'] = 'X1602982937';
    $arrLibrarData[391]['pin'] = '1234';
    $arrLibrarData[391]['login'] = 1;
    
    
    //Richmond Tweed Regional Library , 392
    $arrLibrarData[392]['card'] = '23125016';
    $arrLibrarData[392]['pin'] = '1242';
    $arrLibrarData[392]['login'] = 0;
    
    
    //Timothy C. Hauenstein Reynolds Township Library , 393
    $arrLibrarData[393]['card'] = '21369000001150';
    $arrLibrarData[393]['pin'] = '4885';
    $arrLibrarData[393]['login'] = 1;
    
    
    //Lake Villa District Library , 394
    $arrLibrarData[394]['card'] = '21981000933974';
    $arrLibrarData[394]['login'] = 1;
    
    //Riverside County Library System , 395
    $arrLibrarData[395]['card'] = '1000909682808';
    $arrLibrarData[395]['pin'] = '3003';
    $arrLibrarData[395]['login'] = 1;    
    
    //Trail & District Public Library , 396
    $arrLibrarData[396]['email'] = '';
    $arrLibrarData[396]['password'] = '';
    $arrLibrarData[396]['login'] = 0; 
    
    //Talihina Public Library , 397
    $arrLibrarData[397]['card'] = '20792001796471';
    $arrLibrarData[397]['login'] = 1;
    
    //Okanagan Regional Library , 398
    $arrLibrarData[398]['card'] = '23132005650258';
    $arrLibrarData[398]['pin'] = '1234';
    $arrLibrarData[398]['login'] = 1;
    
    
    //Vanderhoof Public Library , 399
    $arrLibrarData[399]['card'] = '25193000013007';
    $arrLibrarData[399]['login'] = 1;
    
    
    //Council Bluffs Public Library ,400
    $arrLibrarData[400]['card'] = '25226000086126';
    $arrLibrarData[400]['login'] = 1;
    
    
    //Red Bank Public Library ,402
    $arrLibrarData[402]['card'] = '27826000084720';
    $arrLibrarData[402]['pin'] = '8403';
    $arrLibrarData[402]['login'] = 1;
    
    //Poudre River Public Library District ,403
    $arrLibrarData[403]['card'] = '23052000622658';
    $arrLibrarData[403]['login'] = 1;
    
    //Public Library of Faulkner & Van Buren County ,404
    $arrLibrarData[404]['card'] = '13131';
    $arrLibrarData[404]['pin'] = 'abc123';
    $arrLibrarData[404]['login'] = 1;
    
    
    //Coyle Free Library ,405
    $arrLibrarData[405]['card'] = '27201000498454';
    $arrLibrarData[405]['pin'] = '0000';
    $arrLibrarData[405]['login'] = 1;
    
    
    //Clear Lake Public Library ,406
    $arrLibrarData[406]['card'] = '96102000263078';
    $arrLibrarData[406]['pin'] = '3078';
    $arrLibrarData[406]['login'] = 1;
    
    
    //Nelson Municipal Library ,408
    $arrLibrarData[408]['card'] = '25148000018364';
    $arrLibrarData[408]['login'] = 0;
    
    
    //Poplar Creek Public Library District ,409
    $arrLibrarData[409]['card'] = '21339001980058';
    $arrLibrarData[409]['pin'] = '0058';
    $arrLibrarData[409]['login'] = 1;
    
    
    //Pineville-Bell County Library ,410
    $arrLibrarData[410]['card'] = '222200019501';
    $arrLibrarData[410]['login'] = 1;
    
    
    //Clarion Public Library ,411
    $arrLibrarData[411]['email'] = '';
    $arrLibrarData[411]['password'] = '';
    $arrLibrarData[411]['login'] = 0;
    
    
    //Deschutes Public Library ,413
    $arrLibrarData[413]['card'] = '25394001505542';
    $arrLibrarData[413]['pin'] = '1234';
    $arrLibrarData[413]['login'] = 1;
    
   
    //Camden Council Library ,414
    $arrLibrarData[414]['card'] = 'c098873d';
    $arrLibrarData[414]['pin'] = 'library';
    $arrLibrarData[414]['login'] = 1;   
    
    
    //Algonquin Area Public Library District ,416
    $arrLibrarData[416]['card'] = '21488000859142';
    $arrLibrarData[416]['login'] = 1; 

    
    //Athol Public Library ,418
    $arrLibrarData[418]['email'] = '';
    $arrLibrarData[418]['password'] = '';
    $arrLibrarData[418]['login'] = 0;  
    
    
    //Otsego District Library ,419
    $arrLibrarData[419]['card'] = 'P0035142';
    $arrLibrarData[419]['pin'] = '123';
    $arrLibrarData[419]['login'] = 0; 
    
    
    //Newcastle Region Library ,420
    $arrLibrarData[420]['card'] = '22300000000057';
    $arrLibrarData[420]['login'] = 1;   
    
    
    //Queens Borough Public Library ,421
    $arrLibrarData[421]['card'] = '1118436146889';
    $arrLibrarData[421]['pin'] = '1234';
    $arrLibrarData[421]['login'] = 1; 
    
    //The Rowayton Library ,422
    $arrLibrarData[422]['card'] = '23625000051901';
    $arrLibrarData[422]['login'] = 1; 
    
    
    //The Rowayton Prize Library ,423
    $arrLibrarData[423]['email'] = '';
    $arrLibrarData[423]['password'] = '';
    $arrLibrarData[423]['login'] = 0; 
    
    
    //Lake Odessa Community Library ,424
    $arrLibrarData[424]['card'] = '21289000066104';
    $arrLibrarData[424]['pin'] = '3020';
    $arrLibrarData[424]['login'] = 1; 
    
    
    //Mooneyham Public Library ,425
    $arrLibrarData[425]['card'] = '20113100052114';
    $arrLibrarData[425]['login'] = 1;
    
    
    //Norris Public Library ,426
    $arrLibrarData[426]['card'] = '20254000057652';
    $arrLibrarData[426]['pin'] = '7423';
    $arrLibrarData[426]['login'] = 0;
    
    
    //Spindale Public Library ,427
    $arrLibrarData[427]['card'] = '20134000035074';
    $arrLibrarData[427]['pin'] = '1234';
    $arrLibrarData[427]['login'] = 1;   
    
    //Isothermal Community College Library ,428
    $arrLibrarData[428]['card'] = '20220000092924';
    $arrLibrarData[428]['pin'] = '1234';
    $arrLibrarData[428]['login'] = 1;   
    
    
    //Polk County Public Library ,429
    $arrLibrarData[429]['card'] = '17790';
    $arrLibrarData[429]['pin'] = '1234';
    $arrLibrarData[429]['login'] = 1; 
    
    
    //Chandler Public Library ,430
    $arrLibrarData[430]['card'] = '21684011732043';
    $arrLibrarData[430]['pin'] = '1111';
    $arrLibrarData[430]['login'] = 1; 
    
    
    //Hartshorne Public Library ,431
    $arrLibrarData[431]['card'] = '20792002098471';
    $arrLibrarData[431]['login'] = 1;   
    
    
    //Elk Grove Village Public Library ,432
    $arrLibrarData[432]['card'] = '21250001505045';
    $arrLibrarData[432]['login'] = 0;     
    
    
    //Marine Corps Base Hawaii ,433
    $arrLibrarData[433]['email'] = '';
    $arrLibrarData[433]['password'] = '';  
    $arrLibrarData[433]['login'] = 0;
    
    //Naval Air Station Fort Worth ,434
    $arrLibrarData[434]['email'] = '';
    $arrLibrarData[434]['password'] = '';  
    $arrLibrarData[434]['login'] = 0;
    
    
    //Camp Lejeune Base Library ,436
    $arrLibrarData[436]['email'] = '';
    $arrLibrarData[436]['password'] = '';  
    $arrLibrarData[436]['login'] = 0;
    
    
    //Nantucket Atheneum ,437
    $arrLibrarData[437]['card'] = '1290001128467';
    $arrLibrarData[437]['login'] = 1;
    
    //Little Rock Air Force Base Library ,438
    $arrLibrarData[438]['email'] = '';
    $arrLibrarData[438]['password'] = '';  
    $arrLibrarData[438]['login'] = 0;
    
    
    //Surrey Libraries ,440
    $arrLibrarData[440]['card'] = '29090005505613';
    $arrLibrarData[440]['login'] = 1;
    
    
    //Andrews Base Library ,441
    $arrLibrarData[441]['email'] = '';
    $arrLibrarData[441]['password'] = '';  
    $arrLibrarData[441]['login'] = 0;
    
    
    //Broward County Library ,442
    $arrLibrarData[442]['card'] = 'D032222222';
    $arrLibrarData[442]['pin'] = '2222';  
    $arrLibrarData[442]['login'] = 1;
    
    
    //Sayreville Public Library ,443
    $arrLibrarData[443]['card'] = '29359000527991';
    $arrLibrarData[443]['pin'] = '6006';  
    $arrLibrarData[443]['login'] = 1;
    
    
    //Emmet O'Neal Library ,444
    $arrLibrarData[444]['card'] = '';
    $arrLibrarData[444]['pin'] = '';  
    $arrLibrarData[444]['login'] = 0;
    
    
    //Ascension Parish Library ,445
    $arrLibrarData[445]['card'] = '26716000604042';
    $arrLibrarData[445]['pin'] = 'mye04042';  
    $arrLibrarData[445]['login'] = 1;
    
    
    //Peoria Public Library ,446
    $arrLibrarData[446]['card'] = '23305002473029';
    $arrLibrarData[446]['pin'] = '7555';  
    $arrLibrarData[446]['login'] = 1;
    
    
    //San Bernardino Public Library ,447
    $arrLibrarData[447]['card'] = '';
    $arrLibrarData[447]['pin'] = '';  
    $arrLibrarData[447]['login'] = 0;
    
    //Ocean City Free Public Library ,448
    $arrLibrarData[448]['card'] = '3050657';
    $arrLibrarData[448]['pin'] = '1234';  
    $arrLibrarData[448]['login'] = 1;
    
    
    //St. Louis Public Library ,449
    $arrLibrarData[449]['card'] = '1263119305';
    $arrLibrarData[449]['pin'] = '1234';  
    $arrLibrarData[449]['login'] = 0;
    
    
    //Grand Prairie Public Library ,450
    $arrLibrarData[450]['email'] = '';
    $arrLibrarData[450]['password'] = '';  
    $arrLibrarData[450]['login'] = 0;
    
    
    //Richland Hills Public Library  ,451
    $arrLibrarData[451]['card'] = '23678000112751';
    $arrLibrarData[451]['login'] = 1;
    
    
    //Kokomo-Howard County Public Library  ,452
    $arrLibrarData[452]['card'] = '29223001118608';
    $arrLibrarData[452]['pin'] = '0000';
    $arrLibrarData[452]['login'] = 1;   
    
    
    //Phoenix Public Library  ,453
    $arrLibrarData[453]['card'] = '21730017526820';
    $arrLibrarData[453]['login'] = 1;  
    
    //Mesa Public Library  ,454
    $arrLibrarData[454]['card'] = '00001111';
    $arrLibrarData[454]['pin'] = '1111';
    $arrLibrarData[454]['login'] = 1;  
    
    
    //Patrick AFB Library  ,455
    $arrLibrarData[455]['email'] = '';
    $arrLibrarData[455]['password'] = '';
    $arrLibrarData[455]['login'] = 0; 
    
    
    //Desert Foothills Library  ,456
    $arrLibrarData[456]['card'] = 'P 0011875';
    $arrLibrarData[456]['login'] = 0; 
    
    
    //Peterborough Public Library  ,457
    $arrLibrarData[457]['card'] = '23121002025713';
    $arrLibrarData[457]['pin'] = '5382';
    $arrLibrarData[457]['login'] = 1; 
    
    //Tempe Public Library  ,458
    $arrLibrarData[458]['card'] = '22953005149863';
    $arrLibrarData[458]['pin'] = '12345';
    $arrLibrarData[458]['login'] = 1;
    

    //Lyndhurst Free Public Library  ,459
    $arrLibrarData[459]['card'] = '';
    $arrLibrarData[459]['pin'] = '';
    $arrLibrarData[459]['login'] = 0;
    
    //Bristol Public Library  ,460
    $arrLibrarData[460]['card'] = '20650000291854';
    $arrLibrarData[460]['pin'] = '1234';
    $arrLibrarData[460]['login'] = 1;
    
    
    //Sistema Bibliotecario Cremasco Soresinese  ,461
    $arrLibrarData[461]['email'] = '';
    $arrLibrarData[461]['password'] = '';
    $arrLibrarData[461]['login'] = 0;
    
    
    //MLOL Demo Library  ,462
    $arrLibrarData[462]['email'] = '';
    $arrLibrarData[462]['password'] = '';
    $arrLibrarData[462]['login'] = 0;
    
    
    //Danbury Public Library  ,463
    $arrLibrarData[463]['card'] = '24008017450334';
    $arrLibrarData[463]['login'] = 1;
    
    //Toledo-Lucas County Public Library  ,464
    $arrLibrarData[464]['card'] = 'DF6ID';
    $arrLibrarData[464]['pin'] = '1111';
    $arrLibrarData[464]['login'] = 0;
    
    
    //Bankstown City Council Libraryy  ,465
    $arrLibrarData[465]['card'] = '29339020407283';
    $arrLibrarData[465]['pin'] = '01011900';
    $arrLibrarData[465]['login'] = 0;
    
    
    //Randolph Township Free Public Library  ,466
    $arrLibrarData[466]['card'] = '1103200446558';
    $arrLibrarData[466]['login'] = 1;
    
    //Sheffield Public Library  ,467
    $arrLibrarData[467]['card'] = '21446001782630';
    $arrLibrarData[467]['login'] = 1;
    
    //Clearview District Library  ,468
    $arrLibrarData[468]['card'] = '';
    $arrLibrarData[468]['pin'] = '';
    $arrLibrarData[468]['login'] = 0;    
    
    
    //Stonnington Library and Information Service  ,469
    $arrLibrarData[469]['card'] = '20029003547705';
    $arrLibrarData[469]['pin'] = 'fiona1';
    $arrLibrarData[469]['login'] = 1; 
    
    
    //Knox County Public Library  ,470
    $arrLibrarData[470]['card'] = '20088200034396';
    $arrLibrarData[470]['login'] = 1; 
    
    
    //Centro servizi culturali di Macomer  ,471
    $arrLibrarData[471]['email'] = '';
    $arrLibrarData[471]['password'] = '';
    $arrLibrarData[471]['login'] = 0;
    
    
    //Stow-Munroe Falls Public Library  ,472
    $arrLibrarData[472]['card'] = '33529001234567';
    $arrLibrarData[472]['pin'] = '5678';
    $arrLibrarData[472]['login'] = 1;
    
    //Fort Erie Public Library  ,473
    $arrLibrarData[473]['card'] = '23145000333333';
    $arrLibrarData[473]['pin'] = '2546';
    $arrLibrarData[473]['login'] = 0;
    
    
    //Biblioteche del Comune di Piacenza  ,474
    $arrLibrarData[474]['email'] = '';
    $arrLibrarData[474]['password'] = '';
    $arrLibrarData[474]['login'] = 0;
    
    
    //Avondale Public Library  ,475
    $arrLibrarData[475]['card'] = '3100001023555';
    $arrLibrarData[475]['pin'] = '3555';
    $arrLibrarData[475]['login'] = 1;
    
    //Buckeye Public Library  ,476
    $arrLibrarData[476]['card'] = '20623000111067';
    $arrLibrarData[476]['login'] = 1;
    
    //Martin County Library System  ,477
    $arrLibrarData[477]['card'] = '40546000123456';
    $arrLibrarData[477]['login'] = 1;
    
    //Napa City - County Library  ,478
    $arrLibrarData[478]['card'] = '21128003653320';
    $arrLibrarData[478]['login'] = 1;
    
    //Long Branch Free Public Library  ,479
    $arrLibrarData[479]['card'] = '29345000160743';
    $arrLibrarData[479]['pin'] = '8478';
    $arrLibrarData[479]['login'] = 1;
    
    //Sistema Bibliotecario Consortile Panizzi  ,480
    $arrLibrarData[480]['email'] = '';
    $arrLibrarData[480]['password'] = '';
    $arrLibrarData[480]['login'] = 0;
    
    
    //Brunswick Community Library  ,481
    $arrLibrarData[481]['card'] = '28117000077586';
    $arrLibrarData[481]['login'] = 1;
    
    //Poquoson Public Library  ,482
    $arrLibrarData[482]['email'] = '';
    $arrLibrarData[482]['password'] = '';
    $arrLibrarData[482]['login'] = 0;
    
    //Butler Area Public Library  ,483
    $arrLibrarData[483]['card'] = '21669000939915';
    $arrLibrarData[483]['pin'] = '9915';
    $arrLibrarData[483]['login'] = 1;
    
    //Tolleson Public Library  ,484
    $arrLibrarData[484]['card'] = '25353000100200';
    $arrLibrarData[484]['pin'] = 'test1';
    $arrLibrarData[484]['login'] = 1;
    
    //Guelph Public Library  ,485
    $arrLibrarData[485]['card'] = '23281002325057';
    $arrLibrarData[485]['pin'] = '5057';
    $arrLibrarData[485]['login'] = 0;
    
    
    //District of Columbia Public Library  ,486
    $arrLibrarData[486]['card'] = '21172022947925';
    $arrLibrarData[486]['pin'] = '6709';
    $arrLibrarData[486]['login'] = 0;    
    
    
    //US Army Library / MWR Programs  ,487
    $arrLibrarData[487]['email'] = '';
    $arrLibrarData[487]['password'] = '';
    $arrLibrarData[487]['login'] = 0;  
    
    
    //Clifton Public Library  ,488
    $arrLibrarData[488]['card'] = '26040000132361';
    $arrLibrarData[488]['pin'] = '5500';
    $arrLibrarData[488]['login'] = 1;    
   

    //Sno-Isle Libraries  ,489
    $arrLibrarData[489]['card'] = '';
    $arrLibrarData[489]['login'] = 0; 
    
  
    //Huntley Area Public Library District  ,491
    $arrLibrarData[491]['card'] = '26839000447046';
    $arrLibrarData[491]['login'] = 1;
    
    
    //Menomonie Public Library  ,493
    $arrLibrarData[493]['card'] = '20235000205912';
    $arrLibrarData[493]['login'] = 1;
    
    
    //Vancouver Island Regional Library  ,494
    $arrLibrarData[494]['card'] = '23119009382483';
    $arrLibrarData[494]['pin'] = '1778';
    $arrLibrarData[494]['login'] = 1;
    
    //Pima County Public Library  ,495
    $arrLibrarData[495]['card'] = '21152022784270';
    $arrLibrarData[495]['pin'] = '1234';
    $arrLibrarData[495]['login'] = 1;
    
    
    //Auckland Council Libraries  ,496
    $arrLibrarData[496]['card'] = 'C61007996D';
    $arrLibrarData[496]['pin'] = '1111';
    $arrLibrarData[496]['login'] = 1;
    
    
    //Midland County Public Library  ,497
    $arrLibrarData[497]['card'] = '2000067624';
    $arrLibrarData[497]['login'] = 1;
    
    //Allen County Public Library  ,499
    $arrLibrarData[499]['card'] = '2 1833 00784 0662';
    $arrLibrarData[499]['pin'] = '1234';
    $arrLibrarData[499]['login'] = 1;
    
    
    //Cresskill Public Library  ,501
    $arrLibrarData[501]['card'] = '';
    $arrLibrarData[501]['pin'] = '';
    $arrLibrarData[501]['login'] = 0;
    
    
    //Biblioteche della Romagna  ,502
    $arrLibrarData[502]['email'] = '';
    $arrLibrarData[502]['password'] = '';
    $arrLibrarData[502]['login'] = 0;
    
    
    //Provincia di Pesaro e Urbino  ,503
    $arrLibrarData[503]['email'] = '';
    $arrLibrarData[503]['password'] = '';
    $arrLibrarData[503]['login'] = 0;
    
    //Stone County Library  ,504
    $arrLibrarData[504]['card'] = '21358000082070';
    $arrLibrarData[504]['login'] = 1;
    
    //Biblioteche di Bologna  ,505
    $arrLibrarData[505]['email'] = '';
    $arrLibrarData[505]['password'] = '';
    $arrLibrarData[505]['login'] = 0;
    
    //Florham Park Public Library  ,506
    $arrLibrarData[506]['card'] = '1101100005268';
    $arrLibrarData[506]['login'] = 1;


    //Lakeland Public Library  ,507
    $arrLibrarData[507]['card'] = '20398001591472';
    $arrLibrarData[507]['login'] = 1;


    //Wickenburg Public Library  ,509
    $arrLibrarData[509]['card'] = '8000000075210';
    $arrLibrarData[509]['pin'] = '2276';
    $arrLibrarData[509]['login'] = 1;

    
    //Boulder Public Library  ,510
    $arrLibrarData[510]['card'] = 'D007746746';
    $arrLibrarData[510]['login'] = 1;


    //Jennings County Public Library  ,511
    $arrLibrarData[511]['card'] = '20653000304594';
    $arrLibrarData[511]['login'] = 1;

    //Wayne Public Library (NJ)  ,512
    $arrLibrarData[512]['card'] = '22352100130532';
    $arrLibrarData[512]['pin'] = '0000';
    $arrLibrarData[512]['login'] = 1;

    //Aurora Public Library (Ontario)  ,513
    $arrLibrarData[513]['card'] = '';
    $arrLibrarData[513]['pin'] = '';
    $arrLibrarData[513]['login'] = 0;
   
    //Kent Free Library  ,514
    $arrLibrarData[514]['card'] = '24414001171078';
    $arrLibrarData[514]['login'] = 1;

    
    //Rete Bibliotecaria Bresciana  ,515
    $arrLibrarData[515]['email'] = '';
    $arrLibrarData[515]['password'] = '';
    $arrLibrarData[515]['login'] = 0;


    //Gravenhurst Public Library  ,516
    $arrLibrarData[516]['email'] = '';
    $arrLibrarData[516]['password'] = '';
    $arrLibrarData[516]['login'] = 0;

    //Milwaukee Public Library  ,517
    $arrLibrarData[517]['card'] = '25290000000002';
    $arrLibrarData[517]['pin'] = 'testf';
    $arrLibrarData[517]['login'] = 1;
    
    
    //Coalinga-Huron Library District  ,518
    $arrLibrarData[518]['card'] = '0028741023';
    $arrLibrarData[518]['login'] = 1;
    
    //Mid North Coast Library Service  ,519
    $arrLibrarData[519]['card'] = 'M776496';
    $arrLibrarData[519]['login'] = 1;
    
    //Oxford County Library  ,520
    $arrLibrarData[520]['card'] = '26497000271526';
    $arrLibrarData[520]['pin'] = '5378';
    $arrLibrarData[520]['login'] = 1;    
    
  
    //Clarence Dillon Public Library  ,521
    $arrLibrarData[521]['card'] = '912001479302';
    $arrLibrarData[521]['login'] = 1; 
    
    //Frederick County Public Library  ,522
    $arrLibrarData[522]['card'] = '11982010700066';
    $arrLibrarData[522]['login'] = 1;
    
    
    //Johnson Public Library  ,525
    $arrLibrarData[525]['card'] = '';
    $arrLibrarData[525]['pin'] = '';
    $arrLibrarData[525]['login'] = 0;
    
    
    //Ramsey Free Public Library  ,526
    $arrLibrarData[526]['card'] = '';
    $arrLibrarData[526]['pin'] = '';
    $arrLibrarData[526]['login'] = 0;
    
    //Erie County Public Library  ,529
    $arrLibrarData[529]['card'] = '21211003785001';
    $arrLibrarData[529]['login'] = 1;
    
    //Morton Public Library  ,530
    $arrLibrarData[530]['card'] = 'D120401714';
    $arrLibrarData[530]['pin'] = '8888';
    $arrLibrarData[530]['login'] = 0;

    //Kitsap Regional Library , 531
    $arrLibrarData[531]['card'] = '29068007688520';
    $arrLibrarData[531]['pin'] = '9100';
    $arrLibrarData[531]['login'] = 1;
    
    //Barberton Public Library  ,533
    $arrLibrarData[533]['card'] = '24019111111118';
    $arrLibrarData[533]['login'] = 1; 

    //Birchard Public Library  ,534
    $arrLibrarData[534]['card'] = '23520111111111';
    $arrLibrarData[534]['login'] = 1; 
    
    
    //Bristol Public Library  ,535
    $arrLibrarData[535]['card'] = '20348111111115';
    $arrLibrarData[535]['login'] = 1; 
    
    //Burton Public Library  ,536
    $arrLibrarData[536]['card'] = '23235111111112';
    $arrLibrarData[536]['login'] = 1;
    
    //East Cleveland Public Library  ,537
    $arrLibrarData[537]['card'] = '28077111111113';
    $arrLibrarData[537]['login'] = 1;
    
    //Elyria Public Library System  ,538
    $arrLibrarData[538]['card'] = '28078111111110';
    $arrLibrarData[538]['login'] = 1;
    
    
    //Fairport Harbor Public Library  ,539
    $arrLibrarData[539]['card'] = '20445111111110';
    $arrLibrarData[539]['login'] = 1;
    
    //Girard Free Library  ,540
    $arrLibrarData[540]['card'] = '20124111111114';
    $arrLibrarData[540]['login'] = 1;
    
    //Kinsman Free Public Library  ,541
    $arrLibrarData[541]['card'] = '21088111111116';
    $arrLibrarData[541]['login'] = 1;
    
    //Peninsula Library & Historical Society  ,542
    $arrLibrarData[542]['card'] = '20527111111116';
    $arrLibrarData[542]['login'] = 1;
    
    //Perry Public Library  ,543
    $arrLibrarData[543]['card'] = '28084111111118';
    $arrLibrarData[543]['login'] = 1;
    
    //Ritter Public Library  ,544
    $arrLibrarData[544]['card'] = '28085111111116';
    $arrLibrarData[544]['login'] = 1;
    
    
    //Shaker Library  ,545
    $arrLibrarData[545]['card'] = '28086111111114';
    $arrLibrarData[545]['login'] = 1;
    
    //Wayne County Public Library (OH)  ,546
    $arrLibrarData[546]['card'] = '20441111111112';
    $arrLibrarData[546]['login'] = 1;
    
    //Cambridge Public Library  ,547
    $arrLibrarData[547]['card'] = '22168002240108';
    $arrLibrarData[547]['login'] = 1;
    
    
    //Margate City Public Library  ,549
    $arrLibrarData[549]['card'] = '23649000227279';
    $arrLibrarData[549]['login'] = 1;
    
    //Burlington Public Library  ,550
    $arrLibrarData[550]['card'] = '29071003890272';
    $arrLibrarData[550]['pin'] = '6645';
    $arrLibrarData[550]['login'] = 1;
    
       
    //Biblioteca Provinciale di Avellino  ,551
    $arrLibrarData[551]['email'] = '';
    $arrLibrarData[551]['password'] = '';
    $arrLibrarData[551]['login'] = 0;
    
    //Biblioteca Statale di Trieste  ,552
    $arrLibrarData[552]['email'] = '';
    $arrLibrarData[552]['password'] = '';
    $arrLibrarData[552]['login'] = 0;
    
    //Biblioteca Paroniana di Rieti  ,553
    $arrLibrarData[553]['email'] = '';
    $arrLibrarData[553]['password'] = '';
    $arrLibrarData[553]['login'] = 0;
    
    
    //Sistema Bibliotecario di Prato  ,554
    $arrLibrarData[554]['email'] = '';
    $arrLibrarData[554]['password'] = '';
    $arrLibrarData[554]['login'] = 0;
    
    //Rete Bibliotecaria Senese RE.DO.S  ,555
    $arrLibrarData[555]['email'] = '';
    $arrLibrarData[555]['password'] = '';
    $arrLibrarData[555]['login'] = 0;
    
    //Sistema SDIAF Firenze ,556
    $arrLibrarData[556]['email'] = '';
    $arrLibrarData[556]['password'] = '';
    $arrLibrarData[556]['login'] = 0;
    
    
    //Rye Public Library ,557
    $arrLibrarData[557]['card'] = '24602002054612';
    $arrLibrarData[557]['login'] = 1;
    
    //Huntsville Public Library (ON) ,558
    $arrLibrarData[558]['card'] = '25232000418467';
    $arrLibrarData[558]['pin'] = '5232';
    $arrLibrarData[558]['login'] = 1;
    
    //Whiting Public Library ,559
    $arrLibrarData[559]['card'] = '31735100033285';
    $arrLibrarData[559]['login'] = 1;
    
    //St. Catharines Public Library ,560
    $arrLibrarData[560]['card'] = '23043003460782';
    $arrLibrarData[560]['pin'] = '6103';
    $arrLibrarData[560]['login'] = 1;
    
    //Dorchester County Library ,562
    $arrLibrarData[562]['card'] = '20018000789576';
    $arrLibrarData[562]['pin'] = '1234';
    $arrLibrarData[562]['login'] = 1;
    
    //Burlington County Library System ,564
    $arrLibrarData[564]['card'] = '23660004109751';
    $arrLibrarData[564]['pin'] = '9660';
    $arrLibrarData[564]['login'] = 1;
    
    
    //Decatur Public Library ,565
    $arrLibrarData[565]['card'] = '21202002956701';
    $arrLibrarData[565]['pin'] = '6701';
    $arrLibrarData[565]['login'] = 1;
    
    //Greenwood County Library ,566
    $arrLibrarData[566]['card'] = '23162000000010';
    $arrLibrarData[566]['pin'] = '3751';
    $arrLibrarData[566]['login'] = 1;
    
    //Long Beach Public Library ,567
    $arrLibrarData[567]['card'] = '23090007623022';
    $arrLibrarData[567]['pin'] = 'free';
    $arrLibrarData[567]['login'] = 1;
    
    //Licking County Public Library ,568
    $arrLibrarData[568]['card'] = '22487001297772';
    $arrLibrarData[568]['pin'] = '1038';
    $arrLibrarData[568]['login'] = 1;
    
    
    //Bernards Township Library ,570
    $arrLibrarData[570]['card'] = '1105100005582';
    $arrLibrarData[570]['login'] = 1;
    
    //Boxford Town Library ,571
    $arrLibrarData[571]['card'] = '22115000180844';
    $arrLibrarData[571]['login'] = 1;
    
    
    //Muncie Public Library ,572
    $arrLibrarData[572]['card'] = '23174700198662';
    $arrLibrarData[572]['pin'] = '9367';
    $arrLibrarData[572]['login'] = 1;
    
    
    //Norfolk Public Library (NE) ,573
    $arrLibrarData[573]['card'] = '23506000689983';
    $arrLibrarData[573]['login'] = 0;
    
    
    //Samuels Public Library ,574
    $arrLibrarData[574]['card'] = '824004841000';
    $arrLibrarData[574]['login'] = 1;
    
    //Ajax Public Library ,575
    $arrLibrarData[575]['card'] = '';
    $arrLibrarData[575]['pin'] = '';
    $arrLibrarData[575]['login'] = 0; 


    //Jasper Dubois County Public Library ,576
    $arrLibrarData[576]['card'] = '27832000009030';
    $arrLibrarData[576]['login'] = 1; 

    
    //Woodstock Public Library ,577
    $arrLibrarData[577]['card'] = '22105001962522';
    $arrLibrarData[577]['pin'] = '4801';
    $arrLibrarData[577]['login'] = 1;

    
    //Princeton Public Library (IN) ,579
    $arrLibrarData[579]['card'] = '20890000070894';
    $arrLibrarData[579]['login'] = 1;

    
    //Rosenberg Library ,580
    $arrLibrarData[580]['card'] = '';
    $arrLibrarData[580]['pin'] = '';
    $arrLibrarData[580]['login'] = 0;

    //Free Library of Northampton Township ,581
    $arrLibrarData[581]['card'] = 'B7620575';
    $arrLibrarData[581]['pin'] = '8107';
    $arrLibrarData[581]['login'] = 1;

    //Sistema Bibliotecario Vittoriese ,582
    $arrLibrarData[582]['email'] = '';
    $arrLibrarData[582]['password'] = '';
    $arrLibrarData[582]['login'] = 0;


    //Puyallup Public Library ,585
    $arrLibrarData[585]['card'] = '29331005439878';
    $arrLibrarData[585]['pin'] = '3333';
    $arrLibrarData[585]['login'] = 0;


    //Perry County District Library ,586
    $arrLibrarData[586]['card'] = '25947000031446';
    $arrLibrarData[586]['pin'] = '3899';
    $arrLibrarData[586]['login'] = 1;


    //Charleston County Public Library ,587
    $arrLibrarData[587]['card'] = 'D232496807';
    $arrLibrarData[587]['login'] = 1;

    
    //Carnegie Public Library ,588
    $arrLibrarData[588]['card'] = '29196000195985';
    $arrLibrarData[588]['pin'] = '2048';
    $arrLibrarData[588]['login'] = 1;


    //East Brunswick Public Library ,589
    $arrLibrarData[589]['card'] = '29344002042991';
    $arrLibrarData[589]['login'] = 1;
    
    //Ketchikan Public Library ,590
    $arrLibrarData[590]['card'] = '23427000134781';
    $arrLibrarData[590]['login'] = 1;
    
    //Barrington Area Library ,591
    $arrLibrarData[591]['card'] = '21487002769705';
    $arrLibrarData[591]['login'] = 0;
    
    
    //Orangeburg County Library ,592
    $arrLibrarData[592]['card'] = '22757000255363';
    $arrLibrarData[592]['pin'] = 'buffy';
    $arrLibrarData[592]['login'] = 1;
    
    //Provincetown Public Library ,593
    $arrLibrarData[593]['card'] = '1280000113156';
    $arrLibrarData[593]['login'] = 1;
    
    //Mary Riley Styles Public Library ,594
    $arrLibrarData[594]['card'] = '22766006493381';
    $arrLibrarData[594]['pin'] = '1111';
    $arrLibrarData[594]['login'] = 1;
    
    //Central Northern Regional Library ,595
    $arrLibrarData[595]['card'] = '4594222';
    $arrLibrarData[595]['pin'] = 'test';
    $arrLibrarData[595]['login'] = 1;
    
    
    //Sistema Bibliotecario della Lomellina ,596
    $arrLibrarData[596]['email'] = '';
    $arrLibrarData[596]['password'] = '';
    $arrLibrarData[596]['login'] = 0;
    
    
    //Sistemi Bibliotecari della Provincia di Bergamo ,597
    $arrLibrarData[597]['email'] = '';
    $arrLibrarData[597]['password'] = '';
    $arrLibrarData[597]['login'] = 0;
    
    //Barnesville Hutton Memorial Library ,598
    $arrLibrarData[598]['card'] = '22771000049726';
    $arrLibrarData[598]['pin'] = '9226';
    $arrLibrarData[598]['login'] = 1;
    
    //Laurel County Public Library ,599
    $arrLibrarData[599]['card'] = '21330002264188';
    $arrLibrarData[599]['pin'] = '1234';
    $arrLibrarData[599]['login'] = 1;
    
    //Monroe County District Library (OH) ,600
    $arrLibrarData[600]['card'] = '21944000007777';
    $arrLibrarData[600]['pin'] = '3964';
    $arrLibrarData[600]['login'] = 1;
    
    //Farmers Branch Manske Public Library ,601
    $arrLibrarData[601]['card'] = '26120000946463';
    $arrLibrarData[601]['login'] = 1;
    
    
    //Montgomery County Public Library System ,602
    $arrLibrarData[602]['card'] = '26098000008888';
    $arrLibrarData[602]['pin'] = '8888';
    $arrLibrarData[602]['login'] = 1;
    
    //Niagra-on-the-Lake Public Library ,603
    $arrLibrarData[603]['card'] = '01963000305670';
    $arrLibrarData[603]['pin'] = '2023';
    $arrLibrarData[603]['login'] = 1;
    
    
    //Flossmoor Public Library ,604
    $arrLibrarData[604]['card'] = '';
    $arrLibrarData[604]['login'] = 0;
    
    //Mount Sterling Public Library ,605
    $arrLibrarData[605]['card'] = '24228000069822';
    $arrLibrarData[605]['pin'] = '9876';
    $arrLibrarData[605]['login'] = 1;
    
    //Tiffin-Seneca Public Library ,606
    $arrLibrarData[606]['card'] = '21075000650165';
    $arrLibrarData[606]['pin'] = '8362';
    $arrLibrarData[606]['login'] = 1;
    
    //Brown County Public Library ,607
    $arrLibrarData[607]['card'] = '23622000537955';
    $arrLibrarData[607]['pin'] = '1227';
    $arrLibrarData[607]['login'] = 1;
    
    //Welland Public Library ,608
    $arrLibrarData[608]['card'] = '02759000833755';
    $arrLibrarData[608]['pin'] = '5125';
    $arrLibrarData[608]['login'] = 1;
    
    
    //Floyd County Public Library ,609
    $arrLibrarData[609]['card'] = '';
    $arrLibrarData[609]['pin'] = '';
    $arrLibrarData[609]['login'] = 0;
    

    $arrLibrarDataTmp = array();
    foreach($arrLibrarData AS $key => $val) {
    
      if( '' != $arrLibrarData[$key]['library_id']) {
        $arrLibrarDataTmp[$key] = $val;
      }
      
    }
    $arrLibrarData = $arrLibrarDataTmp;
    
    echo '<br />******************************************************************************************************<br />'; 
    echo '<br />*************************************** library updated data start  ************************************<br />'; 
    print_r($arrLibrarData);   
    echo '<br />*************************************** library updated data end  ************************************<br />'; 
    echo '<br />******************************************************************************************************<br />';
    

    //$arg1, $startFrom, $recordCount
    if('all' == $arg1) {
      
      $arrLibrarData = array_slice($arrLibrarData, $startFrom, $recordCount, true);   
      $this->arr_result = $arrLibrarData;
      foreach($arrLibrarData AS $key => $val){
      
        if( 1 == $val['login'] ) {
          $this->loginByWebservice($val['authtype'], $val['email'], $val['password'], $val['card'], $val['pin'], $val['last_name'], $val['library_id'], $val['agent'], $key);
        }        
      }
   
    } else {
      if(is_numeric($arg1)) {
      
        if( 1 == $arrLibrarData[$arg1]['login'] ) {
        
        $this->arr_result[$arg1] = $arrLibrarData[$arg1];
        $this->loginByWebservice($arrLibrarData[$arg1]['authtype'], $arrLibrarData[$arg1]['email'], $arrLibrarData[$arg1]['password'], $arrLibrarData[$arg1]['card'], $arrLibrarData[$arg1]['pin'], $arrLibrarData[$arg1]['last_name'], $arrLibrarData[$arg1]['library_id'], $arrLibrarData[$arg1]['agent'], $arg1);
        
        }
      }
    }
    

    echo '<br />******************************************************************************************************<br />'; 
    echo '<br />*************************************** library result data start  ************************************<br />'; 
    print_r($this->arr_result);   
    echo '<br />*************************************** library result data end  ************************************<br />'; 
    echo '<br />******************************************************************************************************<br />';
    
    
    
    //600 609 Floyd County Public Library
    exit;  
    exit;    
    
  }
    
    
  function loginByWebservice($authtype, $email, $password, $card, $pin, $last_name, $library_id, $agent, $autoId) {

    switch($authtype){

      case '1':  {
        $resp = $this->loginAuthinticate($email, $password, $library_id, $agent, $autoId);
      }
      break;

      case '2':  {
        $resp = $this->iloginAuthinticate($card, $pin, $library_id, $agent, $autoId);
      }
      break;

      case '3':  {
        $resp = $this->inloginAuthinticate($card, $library_id, $agent, $autoId);
      }
      break;

      case '4':  {
        $resp = $this->inhloginAuthinticate($card, $pin, $library_id, $agent, $autoId);
      }
      break;

      case '5':  {
        $resp = $this->ihdloginAuthinticate($card, $pin, $library_id, $agent, $autoId);
      }
      break;

      case '6':  {
        $resp = $this->ildloginAuthinticate($card, $last_name, $library_id, $agent, $autoId);
      }
      break;

      case '7':  {
        $resp = $this->ilhdloginAuthinticate($card, $last_name, $library_id, $agent, $autoId);
      }
      break;

      case '8':  {
        $resp = $this->sloginAuthinticate($card, $pin, $library_id, $agent, $autoId);
      }
      break;

      case '9':  {
        $resp = $this->sdloginAuthinticate($card, $pin, $library_id, $agent, $autoId);
      }
      break;

      case '10':  {
        $resp = $this->ploginAuthinticate($card, $pin, $library_id, $agent, $autoId);
      }
      break;

      case '11':  {
        $resp = $this->indloginAuthinticate($card, $library_id, $agent, $autoId);
      }
      break;

      case '12':  {
        $resp = $this->inhdloginAuthinticate($card, $library_id, $agent, $autoId);
      }
      break;

      case '13':  {
        $resp = $this->snloginAuthinticate($card, $library_id, $agent, $autoId);
      }
      break;

      case '14':  {
        $resp = $this->sndloginAuthinticate($card, $library_id, $agent, $autoId);
      }
      break;

      case '15':  {
        $resp = $this->cloginAuthinticate($card, $pin, $library_id, $agent, $autoId);
      }
      break;

      case '16':  {
        $resp = $this->referralAuthinticate($card, $pin, $library_id, $agent, $autoId);
      }
      break;
      
      case '17':  {
        $resp = $this->idloginAuthinticate($card, $pin, $library_id, $agent, $autoId);
      }
      break;

    }
    
  }

  /**
  * Authenticates user by login method
  * @param $email
  * @param $password
  * @param $library_id
  * @param $agent
  * @return AuthenticationResponseDataType[]
  */

  private function loginAuthinticate($email, $password, $library_id, $agent, $autoId){


    $check_password = Security::hash(Configure::read('Security.salt').$password);
    
    $conditions = array(
      'email'=>$email,
      'user_status' => 'active',
      'password' => $check_password
    );


    $user = $this->User->find('first',array(
        'fields' => array('id', 'email', 'library_id', 'password'),
        'conditions' => $conditions,
      )
    );

    
    if(false == $user) {
      $retVal = 0;
    } else {
      $retVal = 1;
    }

    $this->arr_result[$autoId]['result'] = $retVal;


    

  }


  /**
   * Authenticates user by ilogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function iloginAuthinticate($card, $pin, $library_id, $agent, $autoId) {

    $card = str_replace(" ", "", $card);
    $card = strtolower($card);
    $data['card'] = $card;
    $data['pin'] = $pin;
    $patronId = $card;
    $data['patronId'] = $patronId;
    
    $data['wrongReferral'] = '';
    $data['referral'] = '';


    if($card == ''){


      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);

    }
    elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      
      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');
      
      $data['library_cond'] = $library_id;
      
      $existingLibraries = $this->Library->find('all',array(
                    'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                          'library_authentication_method' => 'innovative'),
                    'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url',
                                      'Library.library_logout_url','Library.library_territory','Library.library_user_download_limit',
                                      'Library.library_block_explicit_content','Library.library_language, library_subdomain'))
      );

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      
      if(count($existingLibraries) == 0){


        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      else{
       
        $authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
        $data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";
        $data['database'] = 'freegal';
        
        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl1 = Configure::read('App.AuthUrl_AU')."ilogin_validation";
        }
        else{
					$authUrl1 = Configure::read('App.AuthUrl')."ilogin_validation";
				}
          
        $result = $this->AuthRequest->getAuthResponse($data,$authUrl1);

        $this->arr_result[$autoId]['result'] = $result;

       
      }
    }
  }


  /**
   * Authenticates user by inlogin method
   * @param $card
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function inloginAuthinticate($card, $library_id, $agent, $autoId){


    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;
    
    $data['wrongReferral'] = '';
    
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      
      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      
      $existingLibraries = $this->Library->find('all',array(
                      'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                            'library_authentication_method' => 'innovative_wo_pin'),
                      'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory',
                                        'Library.library_authentication_url','Library.library_logout_url',
                                        'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                                        'Library.library_language,library_subdomain'))
      );

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

      if(count($existingLibraries) == 0){


        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{
 
        $authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
        $data['url'] = $authUrl."/PATRONAPI/".$card."/dump";
        
        $data['database'] = 'freegal';
        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."inlogin_validation";
					}
        else{
					$authUrl = Configure::read('App.AuthUrl')."inlogin_validation";
				}
        $result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $this->arr_result[$autoId]['result'] = $result;
        
      }
    }

	}

  /**
   * Authenticates user by inhlogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function inhloginAuthinticate($card, $pin, $library_id, $agent, $autoId) {


		$card = str_replace(" ","", $card);
		$card = strtolower($card);
		$data['card'] = $card;

		$data['pin'] = $pin;
    $patronId = $card;
		$data['patronId'] = $patronId;
		
    $data['wrongReferral'] = '';
    $data['referral'] = '';


    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);

		}
    elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      
			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

			$this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
														'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                                  'library_authentication_method' => 'innovative_https'),
														'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory',
                                              'Library.library_authentication_url','Library.library_logout_url',
                                              'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                                              'Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      
		}

    if(count($existingLibraries) == 0){
    
      $response_msg = 'Invalid credentials provided.';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{
			$matches = array();
			$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
			$data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";
                
			$data['database'] = 'freegal';
      if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
        $authUrl = Configure::read('App.AuthUrl_AU')."inhlogin_validation";
      }
			else{
        $authUrl = Configure::read('App.AuthUrl')."inhlogin_validation";
			}
			$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

      $this->arr_result[$autoId]['result'] = $result;

			
		}
	}

  /**
   * Authenticates user by ihdlogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function ihdloginAuthinticate($card, $pin, $library_id, $agent, $autoId) {


    $card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
		$data['pin'] = $pin;
		$patronId = $card;
		$data['patronId'] = $patronId;

    $data['wrongReferral'] = '';
    $data['referral'] = '';

    if($card == ''){


      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      
      
			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');
      $data['library_cond'] = $library_id;
			$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                                  'library_authentication_method' => 'innovative_var_https'),
														'fields' => array('Library.id','Library.library_authentication_method','Library.library_authentication_url',
                                              'Library.library_logout_url','Library.library_territory','Library.library_user_download_limit',                                              'Library.library_block_explicit_content','Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      
      
			if(count($existingLibraries) == 0){
      
        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{
				$matches = array();
				$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
				$data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";
          
				$data['database'] = 'freegal';
        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."ihdlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."ihdlogin_validation";
				}

				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $this->arr_result[$autoId]['result'] = $result;

        
			}
    }
  }

  /**
   * Authenticates user by ildlogin method
   * @param $card
   * @param $last_name
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function ildloginAuthinticate($card, $last_name, $library_id, $agent, $autoId) {


		$card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
    $data['name'] = $last_name;
		$patronId = $card;
		$data['patronId'] = $patronId;

    $data['wrongReferral'] = '';
    $data['referral'] = '';

    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($last_name == ''){


      $response_msg = 'Last name not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      
      
			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');
      
      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                              'library_authentication_method' => 'innovative_var_name'),
        'fields' => array('Library.id','Library.library_authentication_method','Library.library_authentication_url','Library.library_logout_url',
                          'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                          'Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      
			if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
			}
			else{

				$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
				$url = $authUrl."/PATRONAPI/".$card."/dump";

				$data['url'] = $url;
				$data['database'] = 'freegal';
        
        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."ildlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."ildlogin_validation";
				}
          
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $this->arr_result[$autoId]['result'] = $result;
			}
		}

  }


  /**
   * Authenticates user by ilhdlogin method
   * @param $card
   * @param $last_name
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function ilhdloginAuthinticate($card, $last_name, $library_id, $agent, $autoId) {


		$card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
    $data['name'] = $last_name;
		$patronId = $card;
		$data['patronId'] = $patronId;
           
    $data['wrongReferral'] = '';
    $data['referral'] = '';

    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($last_name == ''){


      $response_msg = 'Last name not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      
      
			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                              'library_authentication_method' => 'innovative_var_https_name'),
        'fields' => array('Library.id','Library.library_authentication_method','Library.library_authentication_url','Library.library_logout_url',
                          'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                          'Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

			if(count($existingLibraries) == 0){


        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
			}
			else{

				$matches = array();
				$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
				$data['url'] = $authUrl."/PATRONAPI/".$card."/dump";
				
        $data['database'] = 'freegal';
				
        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."ilhdlogin_validation";
        }
				else{
					$authUrl = Configure::read('App.AuthUrl')."ilhdlogin_validation";
				}
          
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $this->arr_result[$autoId]['result'] = $result;
       
			}
		}

  }


  /**
   * Authenticates user by slogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function sloginAuthinticate($card, $pin, $library_id, $agent, $autoId) {


    $card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
		$data['pin'] = $pin;
		$patronId = $card;
		$data['patronId'] = $patronId;

    $data['wrongReferral'] = '';
    
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      
			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
			$existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active','library_authentication_method' => 'sip2'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language,library_subdomain'))
      );

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      
			if(count($existingLibraries) == 0){


        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        $data['database'] = 'freegal';
        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
          $authUrl = Configure::read('App.AuthUrl_AU')."slogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."slogin_validation";
				}
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $this->arr_result[$autoId]['result'] = $result;
     
			}
    }
  }


  /**
   * Authenticates user by sdlogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function sdloginAuthinticate($card, $pin, $library_id, $agent, $autoId) {


    $data['card_orig'] = $card;
    
    $card = str_replace(" ","",$card);
		$data['card'] = $card;
		$data['pin'] = $pin;
		$patronId = $card;
		$data['patronId'] = $patronId;


    if($card == ''){


      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      
      
			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
			$existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active','library_authentication_method' => 'sip2_var'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_logout_url','Library.library_authentication_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_sip_error','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','library_subdomain'))
      );

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];

      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      $data['referral'] = '';
      $data['wrongReferral'] = '';
      
			if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."sdlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."sdlogin_validation";
				}
				$data['database'] = 'freegal';
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $this->arr_result[$autoId]['result'] = $result;
			}
    }
  }


  /**
   * Authenticates user by plogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function ploginAuthinticate($card, $pin, $library_id, $agent, $autoId) {


    $card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
		$data['pin'] = $pin;
		$patronId = $card;
		$data['patronId'] = $patronId;

    $data['wrongReferral'] = '';
    $data['referral'] = '';
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      
			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
			$existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active','library_authentication_method' => 'soap'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_soap_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      
			if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        $data['soapUrl'] = $existingLibraries['0']['Library']['library_soap_url'];
				$data['database'] = 'freegal';
				if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
          $authUrl = Configure::read('App.AuthUrl_AU')."plogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."plogin_validation";
				}
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $this->arr_result[$autoId]['result'] = $result;
        
			}
    }
  }


  /**
   * Authenticates user by indlogin method
   * @param $card
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function indloginAuthinticate($card, $library_id, $agent, $autoId){


    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;
    
    $data['wrongReferral'] = '';
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
  
      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                              'library_authentication_method' => 'innovative_var_wo_pin'),
        'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url',
                          'Library.library_logout_url',
                          'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                          'Library.library_language,library_subdomain'))
      );

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        $authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
				$data['url'] = $authUrl."/PATRONAPI/".$card."/dump";
          
				$data['database'] = 'freegal';
				if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
          $authUrl = Configure::read('App.AuthUrl_AU')."indlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."indlogin_validation";
				}	
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $this->arr_result[$autoId]['result'] = $result;
       
      }
    }

	}


  /**
   * Authenticates user by inhdlogin method
   * @param $card
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function inhdloginAuthinticate($card, $library_id, $agent, $autoId){


    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;
    
    $data['wrongReferral'] = '';
    $data['referral'] = '';
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
    
      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
        'library_authentication_method' => 'innovative_var_https_wo_pin'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_authentication_url','Library.library_logout_url',
                          'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                          'Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        $matches = array();
				$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
				$data['url'] = $authUrl."/PATRONAPI/".$card."/dump";
          
				$data['database'] = 'freegal';
				if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."inhdlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."inhdlogin_validation";
				}
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $this->arr_result[$autoId]['result'] = $result;
      }
    }

	}


    /**
   * Authenticates user by snlogin method
   * @param $card
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function snloginAuthinticate($card, $library_id, $agent, $autoId){


    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;
    $data['wrongReferral'] = '';
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
 
      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                              'library_authentication_method' => 'sip2_wo_pin'),
        'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

				$data['database'] = 'freegal';
        
        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
          $authUrl = Configure::read('App.AuthUrl_AU')."snlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."snlogin_validation";
				}	
        
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $this->arr_result[$autoId]['result'] = $result;
      }
    }

	}


  /**
   * Authenticates user by sndlogin method
   * @param $card
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function sndloginAuthinticate($card, $library_id, $agent, $autoId){


    $data['card_orig'] = $card;
    
    $card = str_replace(" ","",$card);
    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < 5){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      } 
    
    
      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');
      
      $data['library_cond'] = $library_id; 
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                              'library_authentication_method' => 'sip2_var_wo_pin'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_sip_error','Library.library_user_download_limit', 'library_subdomain','Library.library_block_explicit_content','Library.library_language'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      $data['referral'] = '';

      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."sndlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."sndlogin_validation";
				}
				$data['database'] = 'freegal';
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $this->arr_result[$autoId]['result'] = $result;
      }
    }

	}

  /**
   * Authenticates user by clogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */
	private function cloginAuthinticate($card, $pin, $library_id, $agent, $autoId){


    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;
    $data['wrongReferral'] = '';
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < 5){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
 
      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;
      $data['referral']='';
      $data['pin'] = $pin;
      $this->Library->recursive = -1;
      $library_authentication_method = 'curl_method';
      $data['database'] = 'freegal';

      $library_cond = $library_id;
      $data['library_cond'] = $library_cond;
      $existingLibraries = $this->Library->find('all',array(
              'conditions' => array('library_status' => 'active','library_authentication_method' => 'curl_method','id' => $library_cond),
              'fields' => array('Library.id','Library.library_territory','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language,library_subdomain')
              )
             );

      
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      
      if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
				$authUrl1 = Configure::read('App.AuthUrl_AU')."clogin_validation";
			}
			else{
				$authUrl1 = Configure::read('App.AuthUrl')."clogin_validation";
			}

      $result = $this->AuthRequest->getAuthResponse($data,$authUrl1);
      $this->arr_result[$autoId]['result'] = $result;

    }
  }



    /**
   * Authenticates user by referral_url method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */
	private function referralAuthinticate($card, $pin, $library_id, $agent, $autoId){

    $card = trim($card);
    $data['card'] = $card;
    $data['pin'] = $pin;
    $patronId = $card;
    $data['patronId'] = $patronId;


    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < 5){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{
    
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num', 'mobile_auth'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim($library_data['Library']['mobile_auth'])) ) {

        $response_msg = 'Sorry, your library authentication is not supported at this time.  Please contact your library for further information.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }            
    
      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $existingLibraries = $this->Library->find('all',array(
                    'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                          'library_authentication_method' => 'referral_url'),
                    'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url',
                                      'Library.library_logout_url','Library.library_territory','Library.library_user_download_limit',
                                      'Library.library_block_explicit_content','Library.library_language', 'mobile_auth'))
      );


      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $mobile_auth = $existingLibraries[0]['Library']['mobile_auth'];
      
      $auth_url = str_replace('CARDNUMBER', $data['patronId'], $mobile_auth);
      $auth_url = str_replace('PIN', $data['pin'], $auth_url);
 
      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      else{

        $ch = curl_init($auth_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec ( $ch );
        curl_close($ch);

        $this->arr_result[$autoId]['result'] = $resp;

      }
    }

  }


  /**
   * Authenticates user by idloginAuthinticate method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function idloginAuthinticate($card, $pin, $library_id, $agent, $autoId) {


    $card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
		$data['pin'] = $pin;
		$patronId = $card;
    $data['wrongReferral'] = '';
    $data['referral'] = '';

    if($card == ''){


      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif(strlen($card) < 5){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{
    
      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1
                        
                      ));
                      
      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      
			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');


			$existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active','library_authentication_method' => 'innovative_var'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_soap_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_authentication_url,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      
			if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{
      

		  $data['library_cond'] = $library_id;

          $authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
		  		$data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest"; 
          
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."idlogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."idlogin_validation";
					}
          
              
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);	
          
          $this->arr_result[$autoId]['result'] = $result;
          
			}
    }
  }  
}