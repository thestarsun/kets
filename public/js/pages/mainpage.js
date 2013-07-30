    $(function(){
        $("#see_all").live("click", function(){
            $(this).parent().prev(".hidden_results").animate(500,function(){
                $(this).css({"max-height":"none","overflow":"visible"});
            });
            $("#see_all").css("display","none");
            $("#see_fewer").css("display","block");
        });
        $("#see_fewer").live("click", function(){
            $(this).parent().prev(".hidden_results").animate(500,function(){
                $(this).css({"max-height":"75px","overflow":"hidden"});
            });
            $("#see_all").css("display","block");
            $("#see_fewer").css("display","none");
        });
             
        $(function () {
            var tabContainers = $(".more_info > div.box"); 
            tabContainers.hide().filter(":first").show(); 

            $(".tabs li a").click(function () {
                tabContainers.hide(); 
                tabContainers.filter(this.hash).show(); 
                $(".tabs li a").children(".left_tab").removeClass("left_selected");
                $(".tabs li a").children(".center_tab").removeClass("center_selected");
                $(".tabs li a").children(".right_tab").removeClass("right_selected");
                
                $(this).children(".left_tab").addClass("left_selected");
                $(this).children(".center_tab").addClass("center_selected");
                $(this).children(".right_tab").addClass("right_selected");
                return false;
            }).filter(":first").click();
        });
    });

//function dealTimeLeft(dealEndDateServer)
//{
//    var now = new Date();
//    var dealEndDate = new Date(dealEndDateServer);
//    var totalRemains = (dealEndDate.getTime()-now.getTime());
//    if (totalRemains>1)
//    {
//        var RemainsSec=(parseInt(totalRemains/1000));
//        var RemainsFullDays=(parseInt(RemainsSec/(24*60*60)));
//        var secInLastDay=RemainsSec-RemainsFullDays*24*3600;
//        var RemainsFullHours=(parseInt(secInLastDay/3600));
//        if (RemainsFullHours<10){RemainsFullHours="0"+RemainsFullHours};
//        var secInLastHour=secInLastDay-RemainsFullHours*3600;
//        var RemainsMinutes=(parseInt(secInLastHour/60));
//        if (RemainsMinutes<10){RemainsMinutes="0"+RemainsMinutes};
//        var lastSec=secInLastHour-RemainsMinutes*60;
//        if (lastSec<10){lastSec="0"+lastSec};
//        var mcend = Date.parse("Jan 1, 2012, 00:00:00");
//        var mcnow = now.getTime();
//        var mc = ((mcend-mcnow)/10).toFixed(0).substr(8);
//        document.getElementById('counter').innerHTML = RemainsFullDays+" days "+RemainsFullHours+":"+RemainsMinutes+":"+lastSec;
//        setTimeout("dealTimeLeft('"+dealEndDateServer+"')",10);
//    } 
//    else {document.getElementById("counter").innerHTML = "Expired";}
//}

function buyNow(dealId)
    {
        $.post("/default/ajax/adddealusage",
        {
            dealId: dealId
            
        },
        function(data)
        {
            
        });
    }
    
    function getMainPageDeals(pageNumb)
    {
        $.post("/default/ajax/mainpagedeals",
        {
            pageNumb: pageNumb,
            sortType: $("#sortBy").val()    
        },
        function(data){
            //$('#dealsList').html(data);
            document.getElementById("dealsList").innerHTML = data;
        });
    }
    
    function getMainPageTips(sortType)
    {
        $.post("/default/ajax/mainpagetips", {
            sortType: $("#sorting").val()
        },
    function(data){
//            console.log(data);
            $("#tips").html(data);
            if (sortType == "desc") {
                $("#sorting").val("asc");
            }
            if (sortType == "asc") {
                $("#sorting").val("desc");
            } 
            
        });
    }
    