function RSP(uuid){
    this.rp = 10;   //주먹 횟수
    this.sp = 10;   //가위 횟수
    this.pp = 10;   //보  횟수
    this.rp_ = 1;   //주먹 횟수
    this.sp_ = 1;   //가위 횟수
    this.pp_ = 1;   //보  횟수    
    this.gameCnt = 0; //게임 카운트
    this.pattern = false; //패턴 공격
    this.loseCount = 0;
    this.drawCount = 0;
    this.winCount = 0;  
    this.continuousLose = 0;
    this.level = 10;
    this.kindOfplay = 1;
    this.socket = null;
    this.object = null;
    
    this.imgr1 = new Image();
    this.imgs1 = new Image();
    this.imgp1 = new Image();
    this.imgr2 = new Image();
    this.imgs2 = new Image();
    this.imgp2 = new Image();
    
    this.imgr1.src = "r1.png";
    this.imgs1.src = "s1.png";
    this.imgp1.src = "p1.png";
    this.imgr2.src = "r2.png";
    this.imgs2.src = "s2.png";
    this.imgp2.src = "p2.png";
    
    
    

    
    //화면초기화 세팅
    this.screenSet = function(){
        $("body").empty();
        $("body").append("<div id='bheader'></div><div id='bbody'><div id='inbbody'></div><div id='cimg'><div id='imggap'></div><img id='img01'><img id='img02'></div></div><button id='btn01' class='btn'>게임준비</btn><button id='btn02' class='btn'>플레이입장</btn><button id='btn03' class='btn'>준비</btn><button id='btn04' class='btn'>모의플레이</btn>");
        $("#bheader").css({"width":"100%","height":"60px","background-color":"#002560","position":"fixed","left":"0","top":"0","color":"white","font-size":"52px"});
        $("#bbody").css({"width":"100%","height":"80%","background-color":"#dbdbd9","position":"fixed","left":"0","top":"62px","overflow-y":"scroll"});
        $(".btn").css({"width":"100px","height":"50px","bottom":"10px","position":"absolute"});
        $("#btn01").css({"left":"10px"});
        $("#btn02").css({"left":"110px"});
        $("#btn03").css({"left":"210px"});
        $("#btn04").css({"left":"310px"});
        $("#cimg").css({"position":"absolute","top":"20%","left":"50%","width":"530px","height":"200px","background":"#f00"});
        $("#img01").css({"width":"250px","height":"200px","float":"left","background":"#fff"});
        $("#img02").css({"width":"250px","height":"200px","float":"right","background":"#fff"});
        //$("#img01").attr("src","p1.png");
        //$("#img02").attr("src","p2.png");
        $("#bheader").append("<span id='T1'>"+uuid+" : </span><span id='S1'>0</span>  <span id='T2'>상대 : </span><span id='S2'>0</span> <span id='T3'>DRAW : <span><span id='S3'>0</span>");
        $("#S1").css({"display":"inline-block","width":"110px"});
        $("#S2").css({"display":"inline-block","width":"110px"});
        $("#S3").css({"display":"inline-block","width":"110px"});
        $("#btn01").on("click",function(){tmp.gameinit01()}); 
        $("#btn02").on("click",function(){tmp.enterGround()}); 
        $("#btn03").on("click",function(){tmp.readyforgame()}); 
        $("#btn04").on("click",function(){tmp.testR(200);});   
        
        
        
    }
    //이미지 처리
    this.setImage = function(a,b){
        var obj  = document.getElementById("img01");
        var obj2 = document.getElementById("img02");
        obj.src = eval("this.img"+a+"1.src");
        obj2.src = eval("this.img"+b+"2.src");
    }
    //객체명 가져오기
    this.myName = function(){
        for (var name in this.global) 
            if (this.global[name] == this) 
                return name 
    }
    //초기화
    this.init = function(){
        this.rp = 10;
        this.sp = 10;
        this.pp = 10;
        this.rp_ = 1;
        this.sp_ = 1;
        this.pp_ = 1;
        this.gameCnt = 0; //게임 카운트
        this.pattern = true;
        this.loseCount = 0;
        this.drawCount = 0;
        this.winCount = 0; 
        this.continuousLose = 0;
        this.level = 10;
        this.kindOfplay = 1;    
        this.socket = null;
        this.object1 = null;
    }
    //게임초기화
    this.gameinit01 = function(){
        this.init();
        this.screenSet();
        
    }
    //플레이 입장
    this.enterGround = function(){
        socket = io.connect("http://13.125.62.224:8091/game");
       
        //setTimeout(function(){tmp.socket.emit('request',{type:'ready',uid:uuid});},3000);
        socket.emit('request',{type:'setid',uid:uuid});
        socket.on('response', function (msg) {
            var wTmp;
                if (msg.type == 'game_result') {
                    //console.log(msg);
                    console.log(msg.data);
                
                    if (msg.data.tmp[0].user_name == uuid){
                        wTmp = tmp.whichWin(tmp.convertRSP2(msg.data.tmp[0].user_mjb),tmp.convertRSP2(msg.data.tmp[1].user_mjb));
                        tmp.resultPlay(tmp.convertRSP2(msg.data.tmp[1].user_mjb),wTmp);      
                        $('<div>' + uuid + ' : ' + tmp.convertRSP2(msg.data.tmp[0].user_mjb)  + ' / ' + '상대 :' + tmp.convertRSP2(msg.data.tmp[1].user_mjb) + ' 결과 : '+ wTmp + ' ('+tmp.kindOfplay +')'+'</div>').insertAfter("#inbbody");
                        tmp.setImage(tmp.convertRSP2(msg.data.tmp[0].user_mjb),tmp.convertRSP2(msg.data.tmp[1].user_mjb));
                        $("#S1").text(tmp.winCount); 
                        $("#S2").text(tmp.loseCount); 
                        $("#S3").text(tmp.drawCount); 
                        tmp.sendRSB();
                    }
                    else{
                        wTmp = tmp.whichWin(tmp.convertRSP2(msg.data.tmp[1].user_mjb),tmp.convertRSP2(msg.data.tmp[0].user_mjb));
                        tmp.resultPlay(tmp.convertRSP2(msg.data.tmp[0].user_mjb),wTmp);      
                        $('<div>' + uuid + ' : ' + tmp.convertRSP2(msg.data.tmp[1].user_mjb)  + ' / ' + '상대 :' + tmp.convertRSP2(msg.data.tmp[0].user_mjb) + ' 결과 : '+ wTmp + ' ('+tmp.kindOfplay +')'+'</div>').insertAfter("#inbbody");
                        tmp.setImage(tmp.convertRSP2(msg.data.tmp[1].user_mjb),tmp.convertRSP2(msg.data.tmp[0].user_mjb));
                        $("#S1").text(tmp.winCount); 
                        $("#S2").text(tmp.loseCount); 
                        $("#S3").text(tmp.drawCount);                     
                        tmp.sendRSB();
                    }
                }
        });

        
    }

    
    //제출후 대기
    this.readyforgame = function(){
        this.sendRSB();
    }
    //제출
    this.sendRSB = function(){
        var tmpNext = this.nextChoice();
        console.log('제출 : '+tmpNext+'('+this.convertRSP(tmpNext)+')');
        socket.emit('request',{type:'mjb',uid:uuid,mjb:this.convertRSP(tmpNext)});
    }
    //가위,바위,보 기호변환
    this.convertRSP = function(w){
        return w=='r'?'mu':w=='s'?'jj':'bb';
    }
    //가위,바위,보 기호변환 거꾸로
    this.convertRSP2 = function(w){
        return w=='mu'?'r':w=='jj'?'s':'p';
    }
    //동적 로딩
    this.loadjs = function(c){
        var d = document.getElementsByTagName('head')[0];    
        var e = document.createElement('script');
            e.type = 'text/javascript';
            e.src = c;
            d.appendChild(e);
    }
    
    //무작위 범위 지정 숫자
    this.makeNum = function(min,max){
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    this.whichWin = function(p1,p2){
        if(p1 == p2){
            return 'd';
        }
        if(p1 == 'r'){
            if(p2 == 's'){
                return 'w';
            }
            else{
                return 'l';
            }    
        }
        else if(p1 == 's'){
            if(p2 == 'p'){
                return 'w';
            }
            else{
                return 'l';
            }    
            
        }
        else if(p1 == 'p'){
            if(p2 == 'r'){
                return 'w';
            }
            else{
                return 'l';
            }    
            
        }
        
    }
    this.testR = function(num){
        var rTmp;
        var nTmp;
        var wTmp;
        var cnt99 = 0;
        //this.screenSet();
        this.gameinit01();
        for(var i=0; i < num;i++){
            rTmp = this.makeNum(1,3);
            nTmp = this.nextChoice();
            
            rTmp==1?rTmp='r':rTmp==3?rTmp='s':rTmp='p';
            
            cnt99++;
            if(cnt99%500==0){
                rTmp = 'p';
            }
            wTmp = this.whichWin(nTmp,rTmp);
            this.resultPlay(rTmp,wTmp);
            
           //$("#bbody").append('<div>'+'상대 :' + rTmp + ' / ' + uuid + ' : ' + nTmp + ' 결과 : '+ wTmp + ' ('+this.kindOfplay +')'+'</div>');
           //$("#bbody").append('<div>' + uuid + ' : ' + nTmp + ' / ' + '상대 :' + rTmp + ' 결과 : '+ wTmp + ' ('+this.kindOfplay +')'+'</div>');
            $('<div>' + uuid + ' : ' + nTmp + ' / ' + '상대 :' + rTmp + ' 결과 : '+ wTmp + ' ('+this.kindOfplay +')'+'</div>').insertAfter("#inbbody");
           this.setImage(nTmp,rTmp);
           $("#S1").text(this.winCount); 
           $("#S2").text(this.loseCount); 
           $("#S3").text(this.drawCount); 
        } 
        return '작업 종료!';
    }
    //게임 결과 저장
    this.resultPlay = function(rsp,wld){
        switch(rsp){
            case 'r' : 
                this.pp = this.pp + this.level;
                this.sp = this.sp + this.level;
                this.pp_ = this.pp_++;
                this.sp_ = this.sp_++;
                break;
            case 'p' : 
                this.rp = this.rp + this.level;
                this.sp = this.sp + this.level;
                this.rp_ = this.rp_++;
                this.sp_ = this.sp_++;
                break;
            case 's' :
                this.pp = this.pp + this.level;
                this.rp = this.rp + this.level;
                this.pp_ = this.pp_ ++ ;
                this.rp_ = this.rp_ ++ ;

                break;
            default :
                    return false;
                            
        }
        if(wld=='l'){
            this.loseCount++; 
            this.continuousLose++;
        }
        else if(wld =='w'){
            this.winCount++;
            this.continuousLose = 0;
        }
        else{
            this.drawCount++;
            this.continuousLose = 0;
        }
                
        this.level = Math.round(this.level * 1.3);
        //this.level = Math.round(this.level + 100);
    }
    this.nextChoice = function(){
        var nextResult;
        this.gameCnt++;
        if(this.pattern){
            return this.nextChoice99();   
        }
        if(this.continuousLose > 4){
            this.continuousLose = 0;
            this.kindOfplay++;
        }
        if(this.kindOfplay > 4){
           this.kindOfplay = 1;
        }
        switch(this.kindOfplay){
            case 1:
                nextResult = this.nextChoice01();
                break;
            case 2:
                nextResult = this.nextChoice02();
                break;
            case 3:
                nextResult = this.nextChoice03();
                break;
            default:
                nextResult = this.nextChoice04();
                break;
        }  
         
        return nextResult;
    }
    this.nextChoice01 = function(){ //가중치 적용 확율 + 의도
        var nTmp01 = 0;
        var nextC = 'r';
        var whichMin = 'r';
        nTmp01 = this.makeNum(1,this.rp + this.sp + this.pp);
//document.write(nTmp01 + ' / '+ this.rp + ' / '+ this.sp + '<br />\n');        
        switch(true){
            case nTmp01 <= this.rp :
                nextC = 'r';
                break;
            case nTmp01 <=(this.rp + this.sp) :
                nextC = 's';
                break;
            default :
                nextC = 'p';
                break;
        }
//    return nextC;
        //최소영역 찾기
        this.rp >= this.sp?this.sp>=this.pp?whichMin='p':whichMin='s':this.rp>=this.pp?whichMin='p':whichMin='r';
//    document.write("whichMin : " + whichMin + ' nextC: '+ nextC +'<br />\n');
        if(nextC == whichMin){ 
            switch(nextC){
                case 'r':
                    nextC = 'p';
                    break;
                case 's':
                    nextC = 'r';
                    break;
                default :
                    nextC = 's';
                    break;
            }
        }else{
            switch(whichMin){
                case 'r':
                    nextC = 'p';
                    break;
                case 's':
                    nextC = 'r';
                    break;
                default :
                    nextC = 's';
                    break;
                   
                   }
        }
        
        return nextC;
    }
        this.nextChoice02 = function(){ //가중치 적용 확율
        var nTmp01 = 0;
        var nextC = 'r';     
        nTmp01 = this.makeNum(1,this.rp + this.sp + this.pp);
//document.write(nTmp01 + ' / '+ this.rp + ' / '+ this.sp + '<br />\n');        
        switch(true){
            case nTmp01 <= this.rp :
                nextC = 'p'; 
                break;
            case nTmp01 <=(this.rp + this.sp) :
                nextC = 'r'; 
                break;
            default :
                nextC = 's';
                break;
        }

        
        return nextC;
    }
        this.nextChoice03 = function(){ // 완전 랜덤
        var nTmp01 = 0;
        var nextC = 'r';     
        nTmp01 = this.makeNum(1,99);
//document.write(nTmp01 + ' / '+ this.rp + ' / '+ this.sp + '<br />\n');        
        switch(true){
            case nTmp01 <= 33 :
                nextC = 'p'; 
                break;
            case nTmp01 <= 66 :
                nextC = 'r'; 
                break;
            default :
                nextC = 's';
                break;
        }

        
        return nextC;
    }
        this.nextChoice04 = function(){ // 가중치 제외 확율 + 의도
        var nTmp01 = 0;
        var nextC = 'r';     
        nTmp01 = this.makeNum(1,this.rp_ + this.sp_ + this.pp_);
//document.write(nTmp01 + ' / '+ this.rp + ' / '+ this.sp + '<br />\n');        
        switch(true){
            case nTmp01 <= this.rp_  :
                nextC = 'p'; 
                break;
            case nTmp01 <= (this.rp_ + this.sp_) :
                nextC = 'r'; 
                break;
            default :
                nextC = 's';
                break;
        }
        
        return nextC;
    }

        this.nextChoice99 = function(){ //패턴으로 내게 설정
            console.log('게임횟수 : '+this.gameCnt+'('+this.gameCnt%3+')');
            switch(this.gameCnt%3){
                case 0:
                    return 'r';
                case 1:
                    return 's';
                default :
                    return 'p';
            }
        }
    //소켓 라이브러리 로딩
    this.loadjs("//cdnjs.cloudflare.com/ajax/libs/socket.io/1.3.6/socket.io.min.js");
        
}
