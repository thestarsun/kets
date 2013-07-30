

function dealTimeLeft(dealEndDateServer)
{
    var now = new Date();
    var dealEndDate = new Date(dealEndDateServer);
    var totalRemains = (dealEndDate.getTime()-now.getTime());
    if (totalRemains>1)
    {
        var RemainsSec=(parseInt(totalRemains/1000));
        var RemainsFullDays=(parseInt(RemainsSec/(24*60*60)));
        var secInLastDay=RemainsSec-RemainsFullDays*24*3600;
        var RemainsFullHours=(parseInt(secInLastDay/3600));
        if (RemainsFullHours<10){RemainsFullHours="0"+RemainsFullHours};
        var secInLastHour=secInLastDay-RemainsFullHours*3600;
        var RemainsMinutes=(parseInt(secInLastHour/60));
        if (RemainsMinutes<10){RemainsMinutes="0"+RemainsMinutes};
        var lastSec=secInLastHour-RemainsMinutes*60;
        if (lastSec<10){lastSec="0"+lastSec};
        var mcend = Date.parse("Jan 1, 2012, 00:00:00");
        var mcnow = now.getTime();
        var mc = ((mcend-mcnow)/10).toFixed(0).substr(8);
        if(RemainsFullDays>29){
           $('#timeLeft').remove();
        }else {
            document.getElementById('counter').innerHTML = RemainsFullDays+"d "+RemainsFullHours+"h "+RemainsMinutes+"m";
            setTimeout("dealTimeLeft('"+dealEndDateServer+"')",10);
        }
    } 
    else {document.getElementById("counter").innerHTML = "Expired";}
}

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