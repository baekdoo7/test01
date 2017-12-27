<?php
global $docHeadStart ;
global $docHeadConts ;
global $docHeadEnd   ;
global $docBodyStart ;
global $docBodyConts ;
global $docBodyEnd   ;


$docHeadStart = "
document.write(\"<!DOCTYPE html>\");
document.write(\"<html>\");
document.write(\"<head>\");
";

$docHeadConts = "    
document.write(\"    <!--[if lt IE 9]>                                                                 \");
document.write(\"    <script src=\\\"https://code.jquery.com/jquery-1.10.2.min.js\\\"><\/script>       \");
document.write(\"    <![endif]-->                                                                      \");    
document.write(\"    <!--[if gte IE 9]>                                                                \");  
document.write(\"    <script src=\\\"https://code.jquery.com/jquery-2.0.3.min.js\\\"><\/script>        \"); 
document.write(\"    <![endif]-->                                                                      \"); 
document.write(\"    <!--[if !IE]> -->                                                                 \"); 
document.write(\"    <script src=\\\"https://code.jquery.com/jquery-2.0.3.min.js\\\"><\/script>        \");
document.write(\"    <!-- <![endif]-->                                                                 \"); 
document.write(\"    <script type=\\\"application/javascript\\\">                                      \"); 
document.write(\"        function label_on(cnt, c_chk){                                                \"); 
document.write(\"            if(cnt>1){                                                                \"); 
document.write(\"                for(var i=1; i<cnt; i++){                                             \"); 
document.write(\"                    \\$('#ad_'+i).removeClass('active');                              \");
document.write(\"                }                                                                     \"); 
document.write(\"            }                                                                         \"); 
document.write(\"            \\$('#ad_'+cnt).addClass('active');                                       \");
document.write(\"            if(c_chk == \\\"Y\\\"){                                                   \");  
document.write(\"                \\$(\\\"#c_chk\\\").removeClass('style-danger');                      \");
document.write(\"                \\$(\\\"#c_chk\\\").addClass('style-success');                        \");
document.write(\"            }                                                                         \"); 
document.write(\"        }                                                                             \"); 
document.write(\"        \\$(document).ready(function(){                                               \"); 

document.write(\"            \\/* min-100 size *\\/                                                    \");  
document.write(\"            var adboxh = \\$('#adBox').height();                                      \"); 
document.write(\"            if( adboxh < 101 ){ \\$('.mdInfo ul li').addClass('d-inblock');           \");
document.write(\"            }else{ \\$('.mdInfo ul li').removeClass('d-inblock'); }                   \");

document.write(\"            \\/* adop label *\\/                                                      \");
document.write(\"            \\$('#adopLabel a').bind('mouseenter focus', function(){                  \");
document.write(\"                \\$(this).children('.train').show();                                  \");  
document.write(\"            }).bind('mouseleave blur', function(){                                    \");
document.write(\"                \\$(this).children('.train').hide();                                  \");
document.write(\"            });                                                                       \");

document.write(\"            \\/* 클립보드 복사하기  *\\/                                                   \");
document.write(\"            \\$('#zonidCopy').on('click', function(e) {                               \");
document.write(\"                \\$('#zonidArea').val();                                              \");  
document.write(\"                \\$('#zonidArea').select();                                           \");
document.write(\"                try {                                                                 \"); 
document.write(\"                    var successful = document.execCommand('copy');                    \");
document.write(\"                    var msg = successful ? '완료' : '실패';                             \"); 
document.write(\"                    alert('존아이디 복사 ' + msg);                                       \");
document.write(\"                }catch(err) {                                                         \");
document.write(\"                    alert('기능 오류, 관리자에게 문의하세요.');                               \");
document.write(\"                }                                                                     \");
document.write(\"            });                                                                       \");  

document.write(\"            \\/* close *\\/                                                           \"); 
document.write(\"            \\$('#closeBtnAdop button').on('click', function(){                       \"); 
document.write(\"                \\$('#adLabel').toggle();                                             \");
document.write(\"                if( \\$(this).text() == '-' ){ \\$(this).text('+');                   \");
document.write(\"                }else{ \\$(this).text('-'); }                                         \");
document.write(\"            });                                                                       \"); 
document.write(\"        });                                                                           \");

document.write(\"        label_on(1, '". $ad_info[0]['cfs_chk'] ."');                                  \");
document.write(\"    <\/script>                                                                        \");
document.write(\"    <style type=\\\"text/css\\\">                                                     \");
document.write(\"        body { padding:0; margin:0; font-family:sans-serif; }                         \");
document.write(\"        ul { margin:0; padding:0; }                                                   \");
document.write(\"        ul li { list-style-type: none; }                                              \");
document.write(\"        a, button, img { outline:0 none; border:0 none; padding:0; cursor:pointer; text-decoration:none; }                              \");
document.write(\"        #adBox { position:relative; overflow:hidden; z-index:9999; }                                                                    \");
document.write(\"        #adLabel { position:absolute; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:2001; }      \");
document.write(\"        #labelBox, #labelBox2 { width:100%; background-color:rgba(0, 0, 0, 0.5); }                                                      \");
document.write(\"        .daysBox { padding-left:16px; padding-right:16px; font-family:Tahoma; margin-bottom:4px; }                                      \");
document.write(\"        .mdInfo ul li { margin-bottom:2px; }                                                                                            \");   
document.write(\"        .mdInfo ul li em { background-color:#fff; padding:0 2px; border-radius:2px; color:#000; font-style:normal; }                    \");
document.write(\"        .mdInfo ul li span { letter-spacing: 0.4px; }                                                                                   \"); 
document.write(\"        .mdInfo ul li.active em { color:#000; background-color:#ffd700; }                                                               \"); 
document.write(\"        .mdInfo ul li.active span { color:#ffd700; text-decoration:underline; font-weight:bold; }                                       \"); 
document.write(\"        .d-inblock { display:inline-block; }                                                                                            \");
document.write(\"        #adopLabel { position:absolute; top:0; right:0; height:16px; background-color:rgba(255, 255, 255, 0.7); }                       \");
document.write(\"        #adopLabel a { display:block; color:#000; height:16px; }                                                                        \");
document.write(\"        #adopLabel img { width:14px; height:14px; padding:1px; }                                                                        \"); 
document.write(\"        .train { padding:1px 0 1px 2px; font-size:8pt; font-style:normal; font-family:sans-serif; vertical-align:text-top; }            \");
document.write(\"        .close { position:absolute; top:0; left:0; width:16px; height:16px; line-height:16px; background-color:#ccc; color:#000; font-size:8pt; text-align:center; font-weight: 500; z-index:2002;}      \");
document.write(\"        .close:hover { background-color:#555; color:#fff; }                                                                             \");  
document.write(\"        #tagBox { position:absolute; bottom:0; right:0; }                                                                               \");
document.write(\"        #tagBox span { float:left; width:16px; height:16px; line-height:16px; font-size:7pt; text-align:center; }                       \");
document.write(\"        #tagBox span a { color:#000; }                                                                                                  \");
document.write(\"        #tagBox span:last-child:active { background-color:#555; }                                                                       \");
document.write(\"        #tagBox span:last-child:active a { color:#fff; }                                                                                \"); 
document.write(\"        .style-gray { background-color:#888; color:#fff; }                                                                              \");
document.write(\"        .style-warning { background-color:#ff9800; color:#fff; }                                                                        \");
document.write(\"        .style-danger { background-color:#e31e26; color:#fff; }                                                                         \");
document.write(\"        .style-info { background-color:#26225e; color:#fff;}                                                                            \"); 
document.write(\"        .style-success { background-color:#35a03a; color:#fff;}                                                                         \");
document.write(\"        .style-white { background-color:#fff; color:#000; }                                                                             \");
document.write(\"    </style>                                                                                                                            \"); 
document.write(\"    <!--[if IE]>                                                                                                                        \");
document.write(\"    <style type=\\\"text/css\\\">                                                                                                       \");
document.write(\"        #adLabel { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#80000000,endColorstr=#80000000); zoom: 1; }               \");
document.write(\"        #labelBox, #labelBox2 { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#80000000,endColorstr=#80000000); zoom: 1;}   \");
document.write(\"        #adopLabel { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#b3ffffff,endColorstr=#b3ffffff); zoom: 1; }             \");
document.write(\"    </style>                                                                                                                                                          \");
document.write(\"    <![endif]-->                                                                                                                                                      \"); 
";



$docHeadEnd = "    
document.write(\"    </head>    \");
";

    
$docBodyStart = "    
document.write(\"  <body>   \");
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
document.write(\"     <section>                                                                                                          \");                         
document.write(\"         <div id=\\\"adBox\\\" style=\\\"width:".$ad_info[0]['size_width']."px; height:".$ad_info[0]['size_height']."px;\\\" >   \");  
document.write(\"             <div id=\\\"adLabel\\\">                                                                                       \");         
";       


            if($ad_info[0]['weight_direction'] == "v"){
                
$docBodyConts .= "  
document.write(\"                <!-- 폭포수 방식 -->                                                    \");             
document.write(\"                <div id=\\\"labelBox\\\">                                            \");             
document.write(\"                    <div style=\\\"padding:3px 5px; color:#fff; font-size:7pt;\\\">  \");         
document.write(\"                        <div class=\\\"daysBox\\\">".$area_info['ecpm']."</div>      \");                 
document.write(\"                        <div class=\\\"mdInfo\\\">                                   \");     
document.write(\"                            <ul>                                                     \");         
";                            
                                 foreach($area_info['ad_order'] as $key => $row){ 
                                    $docBodyConts .= "  
document.write(\"                   <li id=\\\"ad_" . ($key+1) . "\\\">                                \");
document.write(\"                        <em>" . ($key+1) . "</em>                                     \");         
document.write(\"                        <span>" . ($net_list[$row]['net_country']) . " : " . $net_list[$row]['net_nm'] . "</span>    \");
document.write(\"                   </li> \"); ";
                                 }

                
$docBodyConts .= "                                  
document.write(\"                            </ul>            \");                 
document.write(\"                        </div>               \");                 
document.write(\"                    </div>                   \");                     
document.write(\"                </div>                       \");                 
";                                                          
            }else{
$docBodyConts .= "                                                  
document.write(\"                 <!-- 비중 방식 -->                                                      \");                    
document.write(\"                 <div id=\\\"labelBox2\\\">                                            \");                    
document.write(\"                     <div style=\\\"padding:3px 5px; color:#fff; font-size:7pt;\\\">   \");                    
document.write(\"                         <div class=\\\"daysBox\\\">".$area_info['ecpm']."</div>       \");                    
document.write(\"                         <div class=\\\"mdInfo\\\">                                    \");                
document.write(\"                             <ul>                                                      \");                
document.write(\"                                 <li class=\\\"active\\\"><span>".$net_list[$ad_info[0]['network_adv_idx']]['net_country']. " : " .$net_list[$ad_info[0]['network_adv_idx']]['net_nm']."</span></li>                                                                                \");           
document.write(\"                             </ul>                                                     \");                    
document.write(\"                         </div>                                                        \");            
document.write(\"                     </div>                                                            \");                
document.write(\"                 </div>                                                                \");                
document.write(\"                 <!-- end. 비중 방식 -->                                                 \");                      
                ";
            }



$docBodyConts .= "                                                  
document.write(\"                    <div id=\\\"adopLabel\\\">                                                                                 \");
document.write(\"                        <a href=\\\"http://insight.adop.cc\\\" target=\\\"_blank\\\">                                          \");    
document.write(\"                            <em class=\\\"train\\\" style=\\\"display:none;\\\">ADOP Label</em>                                \");    
document.write(\"                            <img src=\\\"https://d3l4xhsa509vkb.cloudfront.net/adop/favicon_adop_16x16.ico\\\" />              \");   
document.write(\"                        </a>                                                                                                   \");    
document.write(\"                    </div>                                                                                                     \");    
document.write(\"                    <div id=\\\"tagBox\\\">                                                                                    \");        
document.write(\"                        <span class=\\\"style-gray\\\">". (($ad_info[0]['trans'] == "Y") ? 'A' : 'C')  . "</span>              \");
document.write(\"                        <span class=\\\"style-warning\\\">                                                                     \");
document.write(\"                            ".$area_info['type']."                                                                             \");    
document.write(\"                        </span>                                                                                                \");    
document.write(\"                        <span class=\\\"". (($area_info['cfs_site'] == "Y") ? 'style-info' : 'style-danger') . "\\\" id=\\\"c_site\\\">C</span><!-- CFS 적용 안한거 class name = style-danger -->                                                                                                                                \");
document.write(\"                        <span class=\\\"". (($ad_info[0]['cfs_chk'] == "Y") ? 'style-success' : 'style-danger') . "\\\" id=\\\"c_chk\\\">C</span><!-- CFS 걸려있는데 작동안한거 class name = style-danger -->                                                                                                                         \");   
document.write(\"                        <span class=\\\"style-white\\\" style=\\\"width:64px;\\\"><a href=\\\"javascript:void(0)\\\" id=\\\"zonidCopy\\\">Zone ID copy</a>           \");
document.write(\"                            <input type=\\\"text\\\" id=\\\"zonidArea\\\" value=\\\"".$ad_info[0]['area_idx']."\\\" style=\\\"position:absolute;top:-9999em;\\\" />  \"); 
document.write(\"                        </span>                                                                                                                      \");      
document.write(\"                    </div>                                                                                                                           \");  
document.write(\"                </div>                                                                                                                               \");      
document.write(\"                <div id=\\\"closeBtnAdop\\\">                                                                                                        \");  
document.write(\"                    <button type=\\\"button\\\" class=\\\"close\\\">-</button>                                                                       \");  
document.write(\"                </div>                                                                                                                               \");  
".make_script2tag($ad_info[0]['html_code'])."
document.write(\"            </div>                                                                                                                                   \");      
document.write(\"        </section>                                                                                                                                   \");  
document.write(\"        <script type=\\\"application/javascript\\\">                                                                                                 \");  
document.write(\"            function label_on(cnt, c_chk){                                                                                                           \");  
document.write(\"                if(cnt>1){                                                                                                                           \");  
document.write(\"                    for(var i=1; i<cnt; i++){                                                                                                        \");  
document.write(\"                        \\$('#ad_'+i).removeClass('active');                                                                                         \");  
document.write(\"                    }                                                                                                                                \");  
document.write(\"                }                                                                                                                                    \");      
document.write(\"                \\$('#ad_'+cnt).addClass('active');                                                                                                  \");  

document.write(\"                if(c_chk == \\\"Y\\\"){                                                                                                              \");  
document.write(\"                    \\$(\\\"#c_chk\\\").removeClass('style-danger');                                                                                 \");          
document.write(\"                    \\$(\\\"#c_chk\\\").addClass('style-success');                                                                                   \");  
document.write(\"                }                                                                                                                                    \");  
document.write(\"            }                                                                                                                                        \");  

document.write(\"            \\$(document).ready(function(){                                                                                                          \");  

document.write(\"                \\/* min-100 size *\\/                                                                                                               \");  

document.write(\"                var adboxh = \\$('#adBox').height();                                                                                                 \");  
document.write(\"                if( adboxh < 101 ){ \\$('.mdInfo ul li').addClass('d-inblock');                                                                      \");      
document.write(\"                }else{ \\$('.mdInfo ul li').removeClass('d-inblock'); }                                                                              \");  

document.write(\"                \\/* adop label *\\/                                                                                                                 \");  
document.write(\"                \\$('#adopLabel a').bind('mouseenter focus', function(){                                                                             \");      
document.write(\"                    \\$(this).children('.train').show();                                                                                             \");      
document.write(\"                }).bind('mouseleave blur', function(){                                                                                               \");      
document.write(\"                    \\$(this).children('.train').hide();                                                                                             \");  
document.write(\"                });                                                                                                                                  \");  

document.write(\"                \\/* 클립보드 복사하기 *\\/                                                                                                              \"); 
document.write(\"                \\$('#zonidCopy').on('click', function(e) {                                                                                          \");  
document.write(\"                    \\$('#zonidArea').val();                                                                                                         \");  
document.write(\"                    \\$('#zonidArea').select();                                                                                                      \");  
document.write(\"                    try {                                                                                                                            \");      
document.write(\"                        var successful = document.execCommand('copy');                                                                               \");      
document.write(\"                        var msg = successful ? '완료' : '실패';                                                                                        \");
document.write(\"                        alert('존아이디 복사 ' + msg);                                                                                                  \");
document.write(\"                    }catch(err) {                                                                                                                    \");  
document.write(\"                        alert('기능 오류, 관리자에게 문의하세요.');                                                                                          \");
document.write(\"                    }                                                                                                                                \");  
document.write(\"                });                                                                                                                                  \");      

document.write(\"                \\/* close *\\/                                                                                                                      \");  
document.write(\"                \\$('#closeBtnAdop button').on('click', function(){                                                                                  \");  
document.write(\"                    \\$('#adLabel').toggle();                                                                                                        \");      
document.write(\"                    if( \\$(this).text() == '-' ){ \\$(this).text('+');                                                                              \");   
document.write(\"                    }else{ \\$(this).text('-'); }                                                                                                    \");  
document.write(\"                });                                                                                                                                  \");  
document.write(\"            });                                                                                                                                      \");  
document.write(\"            label_on(1, '".$ad_info[0]['cfs_chk']."');                                                                                               \");  
document.write(\"        <\/script>                                                                                                                                   \");   
";


//$docBodyConts .= make_script2tag($ad_info[0]['html_code']);
    
    
$docBodyEnd = "
document.write(\"   </body>       \"); 
document.write(\"   </html>       \");  
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
        
        $vtmp = "document.write(\" " .$vtmp. "     \");\n";
        $retVal .= $vtmp;
    }
    return $retVal;
}
*/

?>

