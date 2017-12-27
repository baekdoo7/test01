<?php
global $docHeadStart ;
global $docHeadConts ;
global $docHeadEnd   ;
global $docBodyStart ;
global $docBodyConts ;
global $docBodyEnd   ;


$docHeadStart = "
document.writeln(\"<!DOCTYPE html>\");
document.writeln(\"<html>\");
document.writeln(\"<head>\");
";

$docHeadConts = "    
document.writeln(\"    <!--[if lt IE 9]>                                                                 \");
document.writeln(\"    <script src=\\\"https://code.jquery.com/jquery-1.10.2.min.js\\\"><\/script>       \");
document.writeln(\"    <![endif]-->                                                                      \");    
document.writeln(\"    <!--[if gte IE 9]>                                                                \");  
document.writeln(\"    <script src=\\\"https://code.jquery.com/jquery-2.0.3.min.js\\\"><\/script>        \"); 
document.writeln(\"    <![endif]-->                                                                      \"); 
document.writeln(\"    <!--[if !IE]> -->                                                                 \"); 
document.writeln(\"    <script src=\\\"https://code.jquery.com/jquery-2.0.3.min.js\\\"><\/script>        \");
document.writeln(\"    <!-- <![endif]-->                                                                 \"); 
document.writeln(\"    <script type=\\\"application/javascript\\\">                                      \");


document.writeln(\"              if(window.addEventListener){                                            \");    
document.writeln(\"                  window.addEventListener(\\\"message\\\",listener102,false);         \");
document.writeln(\"              }                                                                       \");
document.writeln(\"              else if(window.attatchEvent){                                           \");
document.writeln(\"                  window.attachEvent(\\\"onmessage\\\",listener102);                  \");
document.writeln(\"              }                                                                       \");

document.writeln(\"          function listener102(e){                                                    \");
document.writeln(\"              var strMsg = e.data.split(\\\"-\\\");                                   \"); 
document.writeln(\"              if(strMsg.length == 2 && strMsg[0] == \\\"102\\\" ){                    \"); 
document.writeln(\"                  console.log(strMsg[1]);                                             \"); 
document.writeln(\"                  label_on(strMsg[1],\\\"N\\\");                                      \"); 
document.writeln(\"              }                                                                       \"); 
document.writeln(\"          }                                                                           \");



document.writeln(\"        function label_on(cnt, c_chk){                                                \"); 
document.writeln(\"        console.log(cnt+\\\"a\\\");                                  \");
document.writeln(\"            if(cnt>1){                                                                \"); 
document.writeln(\"                for(var i=1; i<cnt; i++){                                             \"); 
document.writeln(\"                    \\$('#ad_'+i).removeClass('active');                              \");
document.writeln(\"                }                                                                     \"); 
document.writeln(\"            }                                                                         \"); 
document.writeln(\"            \\$('#ad_'+cnt).addClass('active');                                       \");
document.writeln(\"            if(c_chk == \\\"Y\\\"){                                                   \");  
document.writeln(\"                \\$(\\\"#c_chk\\\").removeClass('style-danger');                      \");
document.writeln(\"                \\$(\\\"#c_chk\\\").addClass('style-success');                        \");
document.writeln(\"            }                                                                         \"); 
document.writeln(\"        }                                                                             \"); 
//document.writeln(\"        label_on(1, '". $ad_info[0]['cfs_chk'] ."');                                \");
document.writeln(\"        setTimeout(function(){label_on(1,'". $ad_info[0]['cfs_chk'] ."');},300);      \");
document.writeln(\"        \\$(document).ready(function(){                                               \"); 

document.writeln(\"            \\/* min-100 size *\\/                                                    \");  
document.writeln(\"            var adboxh = \\$('#adBox').height();                                      \"); 
document.writeln(\"            if( adboxh < 101 ){ \\$('.mdInfo ul li').addClass('d-inblock');           \");
document.writeln(\"            }else{ \\$('.mdInfo ul li').removeClass('d-inblock'); }                   \");

document.writeln(\"            \\/* adop label *\\/                                                      \");
document.writeln(\"            \\$('#adopLabel a').bind('mouseenter focus', function(){                  \");
document.writeln(\"                \\$(this).children('.train').show();                                  \");  
document.writeln(\"            }).bind('mouseleave blur', function(){                                    \");
document.writeln(\"                \\$(this).children('.train').hide();                                  \");
document.writeln(\"            });                                                                       \");

document.writeln(\"            \\/* 클립보드 복사하기  *\\/                                                   \");
document.writeln(\"            \\$('#zonidCopy').on('click', function(e) {                               \");
document.writeln(\"                \\$('#zonidArea').val();                                              \");  
document.writeln(\"                \\$('#zonidArea').select();                                           \");
document.writeln(\"                try {                                                                 \"); 
document.writeln(\"                    var successful = document.execCommand('copy');                    \");
document.writeln(\"                    var msg = successful ? '완료' : '실패';                             \"); 
document.writeln(\"                    alert('존아이디 복사 ' + msg);                                       \");
document.writeln(\"                }catch(err) {                                                         \");
document.writeln(\"                    alert('기능 오류, 관리자에게 문의하세요.');                               \");
document.writeln(\"                }                                                                     \");
document.writeln(\"            });                                                                       \");  

document.writeln(\"            \\/* close *\\/                                                           \"); 
document.writeln(\"            \\$('#closeBtnAdop button').on('click', function(){                       \"); 
document.writeln(\"                \\$('#adLabel').toggle();                                             \");
document.writeln(\"                if( \\$(this).text() == '-' ){ \\$(this).text('+');                   \");
document.writeln(\"                }else{ \\$(this).text('-'); }                                         \");
document.writeln(\"            });                                                                       \"); 
document.writeln(\"        });                                                                           \");
document.writeln(\"    <\/script>                                                                        \");
document.writeln(\"    <style type=\\\"text/css\\\">                                                     \");
document.writeln(\"        body { padding:0; margin:0; font-family:sans-serif; }                         \");
document.writeln(\"        ul { margin:0; padding:0; }                                                   \");
document.writeln(\"        ul li { list-style-type: none; }                                              \");
document.writeln(\"        a, button, img { outline:0 none; border:0 none; padding:0; cursor:pointer; text-decoration:none; }                            \");
document.writeln(\"        #adBox { position:relative; overflow:hidden; z-index:9999; }                                                                  \");
document.writeln(\"        #adLabel { position:absolute; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:2001; }    \");
document.writeln(\"        #labelBox, #labelBox2 { width:100%; background-color:rgba(0, 0, 0, 0.5); }                                                    \");
document.writeln(\"        .daysBox { padding-left:16px; padding-right:16px; font-family:Tahoma; margin-bottom:4px; }                                      \");
document.writeln(\"        .mdInfo ul li { margin-bottom:2px; }                                                                                            \");   
document.writeln(\"        .mdInfo ul li em { background-color:#fff; padding:0 2px; border-radius:2px; color:#000; font-style:normal; }                    \");
document.writeln(\"        .mdInfo ul li span { letter-spacing: 0.4px; }                                                                                   \"); 
document.writeln(\"        .mdInfo ul li.active em { color:#000; background-color:#ffd700; }                                                               \"); 
document.writeln(\"        .mdInfo ul li.active span { color:#ffd700; text-decoration:underline; font-weight:bold; }                                       \"); 
document.writeln(\"        .d-inblock { display:inline-block; }                                                                                            \");
document.writeln(\"        #adopLabel { position:absolute; top:0; right:0; height:16px; background-color:rgba(255, 255, 255, 0.7); }                       \");
document.writeln(\"        #adopLabel a { display:block; color:#000; height:16px; }                                                                        \");
document.writeln(\"        #adopLabel img { width:14px; height:14px; padding:1px; }                                                                        \"); 
document.writeln(\"        .train { padding:1px 0 1px 2px; font-size:8pt; font-style:normal; font-family:sans-serif; vertical-align:text-top; }            \");
document.writeln(\"        .close { position:absolute; top:0; left:0; width:16px; height:16px; line-height:16px; background-color:#ccc; color:#000; font-size:8pt; text-align:center; font-weight: 500; z-index:2002;}      \");
document.writeln(\"        .close:hover { background-color:#555; color:#fff; }                                                                             \");  
document.writeln(\"        #tagBox { position:absolute; bottom:0; right:0; }                                                                               \");
document.writeln(\"        #tagBox span { float:left; width:16px; height:16px; line-height:16px; font-size:7pt; text-align:center; }                       \");
document.writeln(\"        #tagBox span a { color:#000; }                                                                                                  \");
document.writeln(\"        #tagBox span:last-child:active { background-color:#555; }                                                                       \");
document.writeln(\"        #tagBox span:last-child:active a { color:#fff; }                                                                                \"); 
document.writeln(\"        .style-gray { background-color:#888; color:#fff; }                                                                              \");
document.writeln(\"        .style-warning { background-color:#ff9800; color:#fff; }                                                                        \");
document.writeln(\"        .style-danger { background-color:#e31e26; color:#fff; }                                                                         \");
document.writeln(\"        .style-info { background-color:#26225e; color:#fff;}                                                                            \"); 
document.writeln(\"        .style-success { background-color:#35a03a; color:#fff;}                                                                         \");
document.writeln(\"        .style-white { background-color:#fff; color:#000; }                                                                             \");
document.writeln(\"    </style>                                                                                                                            \"); 
document.writeln(\"    <!--[if IE]>                                                                                                                        \");
document.writeln(\"    <style type=\\\"text/css\\\">                                                                                                       \");
document.writeln(\"        #adLabel { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#80000000,endColorstr=#80000000); zoom: 1; }               \");
document.writeln(\"        #labelBox, #labelBox2 { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#80000000,endColorstr=#80000000); zoom: 1;}   \");
document.writeln(\"        #adopLabel { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#b3ffffff,endColorstr=#b3ffffff); zoom: 1; }             \");
document.writeln(\"    </style>                                                                                                                                                          \");
document.writeln(\"    <![endif]-->                                                                                                                                                      \"); 
";



$docHeadEnd = "    
document.writeln(\"    </head>    \");
";

    
$docBodyStart = "    
document.writeln(\"  <body>   \");
";

if(file_exists(AREAINFO_PATH."/network_list")) {
    $net_list_tmp = file_get_contents(AREAINFO_PATH.'/network_list',true);
}else{
    $net_list_tmp = make_network_list();
}


if(file_exists(AREAINFO_PATH."/".$ad_info[0]['area_idx']."/area_info.txt")) {
    $area_info_tmp = file_get_contents(AREAINFO_PATH."/".$ad_info[0]['area_idx']."/area_info.txt",true);
}else{
    $area_info_tmp = make_area_info($ad_info[0]['area_idx']);
}
$net_list = json_decode($net_list_tmp, true);
$area_info = json_decode($area_info_tmp, true);


$docBodyConts = "
document.writeln(\"     <section>                                                                                                                   \");                         
document.writeln(\"         <div id=\\\"adBox\\\" style=\\\"width:".$ad_info[0]['size_width']."px; height:".$ad_info[0]['size_height']."px;\\\" >   \");  
document.writeln(\"             <div id=\\\"adLabel\\\">                                                                                            \");         
";       


            if($ad_info[0]['weight_direction'] == "v"){
                
$docBodyConts .= "  
document.writeln(\"                <!-- 폭포수 방식 -->                                                    \");             
document.writeln(\"                <div id=\\\"labelBox\\\">                                            \");             
document.writeln(\"                    <div style=\\\"padding:3px 5px; color:#fff; font-size:7pt;\\\">  \");         
document.writeln(\"                        <div class=\\\"daysBox\\\">".$area_info['ecpm']."</div>      \");                 
document.writeln(\"                        <div class=\\\"mdInfo\\\">                                   \");     
document.writeln(\"                            <ul>                                                     \");         
";                            
                                 foreach($area_info['ad_order'] as $key => $row){ 
                                    $docBodyConts .= "  
document.writeln(\"                   <li id=\\\"ad_" . ($key+1) . "\\\">                                \");
document.writeln(\"                        <em>" . ($key+1) . "</em>                                     \");         
document.writeln(\"                        <span>" . ($net_list[$row]['net_country']) . " : " . $net_list[$row]['net_nm'] . "</span>    \");
document.writeln(\"                   </li> \"); ";
                                 }

                
$docBodyConts .= "                                  
document.writeln(\"                            </ul>            \");                 
document.writeln(\"                        </div>               \");                 
document.writeln(\"                    </div>                   \");                     
document.writeln(\"                </div>                       \");                 
";                                                          
            }else{
$docBodyConts .= "                                                  
document.writeln(\"                 <!-- 비중 방식 -->                                                      \");                    
document.writeln(\"                 <div id=\\\"labelBox2\\\">                                            \");                    
document.writeln(\"                     <div style=\\\"padding:3px 5px; color:#fff; font-size:7pt;\\\">   \");                    
document.writeln(\"                         <div class=\\\"daysBox\\\">".$area_info['ecpm']."</div>       \");                    
document.writeln(\"                         <div class=\\\"mdInfo\\\">                                    \");                
document.writeln(\"                             <ul>                                                      \");                
document.writeln(\"                                 <li class=\\\"active\\\"><span>".$net_list[$ad_info[0]['network_adv_idx']]['net_country']. " : " .$net_list[$ad_info[0]['network_adv_idx']]['net_nm']."</span></li>                                                                                \");           
document.writeln(\"                             </ul>                                                     \");                    
document.writeln(\"                         </div>                                                        \");            
document.writeln(\"                     </div>                                                            \");                
document.writeln(\"                 </div>                                                                \");                
document.writeln(\"                 <!-- end. 비중 방식 -->                                                 \");                      
                ";
            }



$docBodyConts .= "                                                  
document.writeln(\"                    <div id=\\\"adopLabel\\\">                                                                                 \");
document.writeln(\"                        <a href=\\\"http://insight.adop.cc\\\" target=\\\"_blank\\\">                                          \");    
document.writeln(\"                            <em class=\\\"train\\\" style=\\\"display:none;\\\">ADOP Label</em>                                \");    
document.writeln(\"                            <img src=\\\"https://d3l4xhsa509vkb.cloudfront.net/adop/favicon_adop_16x16.ico\\\" />              \");   
document.writeln(\"                        </a>                                                                                                   \");    
document.writeln(\"                    </div>                                                                                                     \");    
document.writeln(\"                    <div id=\\\"tagBox\\\">                                                                                    \");        
document.writeln(\"                        <span class=\\\"style-gray\\\">". (($ad_info[0]['trans'] == "Y") ? 'A' : 'C')  . "</span>              \");
document.writeln(\"                        <span class=\\\"style-warning\\\">                                                                     \");
document.writeln(\"                            ".$area_info['type']."                                                                             \");    
document.writeln(\"                        </span>                                                                                                \");    
document.writeln(\"                        <span class=\\\"". (($area_info['cfs_site'] == "Y") ? 'style-info' : 'style-danger') . "\\\" id=\\\"c_site\\\">C</span><!-- CFS 적용 안한거 class name = style-danger -->                                                                                                                                \");
document.writeln(\"                        <span class=\\\"". (($ad_info[0]['cfs_chk'] == "Y") ? 'style-success' : 'style-danger') . "\\\" id=\\\"c_chk\\\">C</span><!-- CFS 걸려있는데 작동안한거 class name = style-danger -->                                                                                                                         \");   
document.writeln(\"                        <span class=\\\"style-white\\\" style=\\\"width:64px;\\\"><a href=\\\"javascript:void(0)\\\" id=\\\"zonidCopy\\\">Zone ID copy</a>           \");
document.writeln(\"                            <input type=\\\"text\\\" id=\\\"zonidArea\\\" value=\\\"".$ad_info[0]['area_idx']."\\\" style=\\\"position:absolute;top:-9999em;\\\" />  \"); 
document.writeln(\"                        </span>                                                                                                                      \");      
document.writeln(\"                    </div>                                                                                                                           \");  
document.writeln(\"                </div>                                                                                                                               \");      
document.writeln(\"                <div id=\\\"closeBtnAdop\\\">                                                                                                        \");  
document.writeln(\"                    <button type=\\\"button\\\" class=\\\"close\\\">-</button>                                                                       \");  
document.writeln(\"                </div>                                                                                                                               \");  
".make_script2tag($ad_info[0]['html_code'])."
document.writeln(\"            </div>                                                                                                                                   \");      
document.writeln(\"        </section>                                                                                                                                   \");  
";


//$docBodyConts .= make_script2tag($ad_info[0]['html_code']);
    
    
$docBodyEnd = "
document.writeln(\"   </body>       \"); 
document.writeln(\"   </html>       \");  
";    

/*
function make_script2tag($str){
    $retVal = "";   
    $str001 = explode("\n",$str);
    foreach($str001 as $k => $v){        
        $vtmp = "";
        $vtmp = str_replace("\"","\\\"",$v);
        $vtmp = str_replace("</script","<\\/script",$vtmp);
        $vtmp = str_replace("/RD/","/RE/",$vtmp);
        
        $vtmp = "document.writeln(\" " .$vtmp. "     \");\n";
        $retVal .= $vtmp;
    }
    return $retVal;
}
*/

?>

