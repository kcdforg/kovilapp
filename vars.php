<?php

global $kulam,$blood_group,$occupation,$qualification, $stars, $raasi, $month, $color, $workplace, $marital_status,$kattalai;

global $kulam;
$kulam = array(
    'na' => "Not Known",
    'aadai' => "Aadai / ஆடை",
    'aandhai' => "Aandhai / ஆந்தை",
    'aathi' => "Aathi / ஆதி ",
    'adara' => "Adara / அடற",
    'anthuvan' => "Anthuvan / அந்துவன்",
    'azhagan' => "Azhagan / அழகன்",
    'eenjan' => "Eenjan / ஈஞ்சன்",
    'ennai' => "Ennai / எண்ணை",
    'kaadai' => "Kaadai / காடை(சாகாடை)",
    'kaari' => "Kaari / காரி",
    'kanakkan' => "Kanakkan / கணக்கன்",
    'kannan' => "Kannan / கண்ணன்",
    'kannandhai' => "Kannandhai / கன்னந்தை ",
    'keerai' => "Keerai / கீரை",
    'koorai' => "Koorai / கூரை",
    'kuzhaayan' => "Kuzhaayan / குழாயன் ",
    'maadai' => "Maadai / மாடை",
    'maniyan' => "Maniyan / மணியன் ",
    'muthan' => "Muthan / முத்தன்",
    'othaalan' => "Othaalan / ஒதாளன்",
    'paandiyan' => "Paandiyan / பாண்டியன் ",
    'panaiyan' => "Panaiyan / பனையன்",
    'panangaadai' => "Panangaadai / பனங்காடை ",
    'pannai' => "Pannai / பண்ணை",
    'pavalan' => "Pavalan / பவளன் ",
    'payiran' => "Payiran / பயிரன்",
    'periya' => "Periya / பெரிய",
    'perungudi' => "Perungudi / பெருங்குடி ",
    'pillan' => "Pillan / பில்லன்",
    'ponna' => "Ponna / பொன்ன",
    'poochandhai' => "Poochandhai / பூச்சந்தை ",
    'poondhai' => "Poondhai / பூந்தை",
    'poosan' => "Poosan / பூசன்",
    'porulthandhaan' => "Piralandhai, muzhukkaadhan) / பொருள்தந்தான், முழுக்காதன்) ",
    'pullan' => "Pullan / புள்ளன்",
    'saathandhai' => "Saathandhai / சாத்தந்தை",
    'sellan' => "Sellan / செல்லன்",
    'sempan' => "Sempan / செம்பன் ",
    'sempoothan' => "Sempoothan / செம்பூத்தன்",
    'senkannan' => "Senkannan / செங்கண்ணன்",
    'senkunni' => "Senkunni / செங்குண்ண ",
    'seralan' => "Seralan / சேரலன்",
    'seran' => "Seran / சேரன்",
    'sevvaayan' => "Sevvaayan / செவ்வாயன்",
    'sevvanthi' => "Sevvanthi / செவ்வந்தி ",
    'thananjeyan' => "Thananjeyan / தனஞ்செயன்",
    'thevendhiran' => "Thevendhiran / தேவேந்திரன்",
    'thoadai' => "Thoadai / தோடை",
    'thooran' => "Thooran / தூரன்",
    'unKnown' => "UnKnown / அறியாதது",
    'vaanan' => "Vaanan / வாணன் ",
    'vannakkan' => "Vannakkan / வண்ணக்கன்",
    'vilayan' => "Velaiyan (vilayan) / வெளையன் (விளையன்)",
    'vellamban' => "Vellamban / வெள்ளம்பன்",
    'vendhan' => "Vendhan / வேந்தன் ",
    'venduvan' => "Venduvan / வெண்டுவன்",
    'villi' => "Villi / வில்லி",
    'vizhiyan' => "Vizhiyan / விழியன்",
);


global $blood_group;
$blood_group = array(
    'A+' => "A +ve",
    'A1+' => "A1 +ve",
    'A2+' => "A2 +ve",
    'O+' => "O +ve",
    'B+' => "B +ve",
    'AB+' => "AB +ve",
    'A1-' => "A1 --ve",
    'A-' => "A --ve",
    'A2-' => "A2 --ve",
    'O-' => "O --ve",
    'B-' => "B --ve",
    'AB-' => "AB --ve",
);



global $occupation;
$occupation = array('agri' => "Agriculture",
    'transport' => "Transports",
    'engg' => "Engineer",
    'business' => "Business ",
    'finance' => "Finance",
    'poultry' => "Poultry",
    'medical' => "Medical field",
    'dental' => "Dental",
    'military' => "Military",
    'bank' => "Bank job",
    'Law' => "Lawyer",
    'auditor' => "Auditor",
    'catering' => "Catering",
    'bank' => "Bank job",
    'govt' => "Govt job",
    'teachr' => "Govt teacher",
    'self ' => "self employed",
    'others' => "Others",
);


global $qualification;
$qualification = array('engg' => "Engineering",
    'medical' => "Medical",
    'dental' => "Dental",
    'law' => "Law",
    'arts' => "Arts& Science",
    'diploma' => "Diploma",
    'CA' => "Chartered Account",
    'marine' => "Marine",
    'others' => "Others"
);


global $height_horo;

$height_horo = array(
    '148' => " < 5 ft ",
    '150' => " 5ft - 00in  (150cm)",
    '152' => " 5ft - 01in ( 152 cm )",
    '155' => " 5ft - 02in ( 155 cm )",
    '157' => " 5ft - 03in ( 157 cm )",
    '159' => " 5ft - 04in ( 159 cm )",
    '162' => " 5ft - 05in ( 162 cm )",
    '165' => " 5ft - 06in ( 165 cm )",
    '167' => " 5ft - 07in ( 167 cm )",
    '169' => " 5ft - 08in ( 169 cm )",
    '173' => " 5ft - 09in ( 173 cm )",
    '175' => " 5ft - 10in ( 175cm )",
    '178' => " 5ft - 11in ( 178cm )",
    '180' => " 6ft - 00in ( 180cm )",
    '182' => " > 6ft  ",
);

$marital_status = array('unmarried' => "Unmarried",
                                      'divorced' => "Divorced",
                                      'widower' => "Widow/ Widower",
                                      'awaiting' => "Awaiting divorce",
);

$stars = array('1' => "Ashwini / அஸ்வினி",
    '2' => "Bharani / பரணி",
    '3' => "Krittika / கார்த்திகை",
    '4' => "Rohini / ரோகிணி",
    '5' => "Mrigasira / மிருகசீரிடம்",
    '6' => "Thiruvadirai / திருவாதிரை",
    '7' => "Punarpusam / புனர்பூசம்",
    '8' => "Poosam / பூசம்",
    '9' => "Ayilyam / ஆயில்யம்",
    '10' => "Magam / மகம்",
    '11' => "Pooram / பூரம்",
    '12' => "Uthiram / உத்திரம்",
    '13' => "Hastham / ஹஸ்தம்",
    '14' => "Chitrai / சித்திரை",
    '15' => "Swati / சுவாதி",
    '16' => "Vishakham / விசாகம்",
    '17' => "Anusham / அனுசம்",
    '18' => "Kettai / கேட்டை",
    '19' => "Moolam / மூலம்",
    '20' => "Pooradam / பூராடம்",
    '21' => "Thiruvonam / திருவோணம்",
    '22' => "Pooram / பூரம்",
    '23' => "Avittam / அவிட்டம்",
    '24' => "Sadhayam / சதயம்",
    '25' => "Poorattadhi / பூரட்டாதி",
    '26' => "Uthirattadhi / உத்திரட்டாதி",
    '27' => "Revathi / ரேவதி");


$raasi = array('1' => "Mesham / மேஷம்",
    '2' => "Rishabam / ரிஷபம்",
    '3' => "Mithunam / மிதுனம் ",
    '4' => "Katagam / கடகம்",
    '5' => "Simham / சிம்மம்",
    '6' => "Kanni / கன்னி",
    '7' => "Tula / துலாம்",
    '8' => "Vrichigam / விருச்சிகம்",
    '9' => "Dhanus / தனுசு",
    '10' => "Makara / மகரம்",
    '11' => "Kumbha / கும்பம்",
    '12' => "Meena / மீனம்",);


$month = array('01' => "Jan",
    '02' => "Feb",
    '03' => "Mar",
    '04' => "Apr",
    '05' => "May",
    '06' => "Jun",
    '07' => "Jul",
    '08' => "Aug",
    '09' => "Sep",
    '10' => "Oct",
    '11' => "Nov",
    '12' => "Dec",);

$colour = array('white' => "white",
    'wheatish' => "wheatish",
    'dark' => "Dark"
);

$workplace = array('abroad' => "Abroad",
    'onsite' => "onsite",
    'other state' => "Other state",
    'TN' => "Tamilnadu"
);

global $graham;

$graham = array('1' => "சூரியன்",
    '2' => "சந்திரன்",
    '3' => "செவ்வாய்",
    '4' => "புதன்",
    '5' => "குரு",
    '6' => "சுக்கிரன்",
    '7' => "சனி",
    '8'=> 'ராகு',
    '9'=> 'கேது',
  );


global $graham_short;
$graham_short = array('sooriyan' => "சூ",
    'chandran' => " சந் ",
    'sevvai' => " செவ் ",
    'buthan' => " பு ",
    'guru' => " குரு ",
    'raaghu' => " ரா ",
    'kaedhu' => " கே " ,
    'sani'=> ' சனி ',
    'sukkiran'=> ' சுக் ',
    'laknam'=> ' ல ',
    'a_sooriyan' => "சூ",
    'a_chandran' => " சந் ",
    'a_sevvai' => " செவ் ",
    'a_buthan' => " பு ",
    'a_guru' => " குரு ",
    'a_raaghu' => " ரா ",
    'a_kaedhu' => " கே " ,
    'a_sani'=> ' சனி ',
    'a_sukkiran'=> ' சுக் ',
    'a_laknam'=> ' ல ',
  );
  
global $kattalai;
$kattalai = array('chithirai' => "சித்திரை",
    'vaikasi' => "வைகாசி",
    'aani' => "ஆணி",
    'aadi' => "ஆடி ",
    'aavani' => "ஆவணி",
    'puratasi' => "புரட்டாசி",
    'aipasi' => "ஐப்பசி",
    'karthigai' => "கார்த்திகை",
    'margali' => "மார்கழி",
    'thai' => "தை",
    'masi' => "மாசி",
    'panguni' => "பங்குனி"
  );
  
global $blood_group;
$blood_group = array(
    'A+' => "A +ve",
    'A1+' => "A1 +ve",
    'A2+' => "A2 +ve",
    'O+' => "O +ve",
    'B+' => "B +ve",
    'AB+' => "AB +ve",
    'A1-' => "A1 --ve",
    'A-' => "A --ve",
    'A2-' => "A2 --ve",
    'O-' => "O --ve",
    'B-' => "B --ve",
    'AB-' => "AB --ve",
);

?>
