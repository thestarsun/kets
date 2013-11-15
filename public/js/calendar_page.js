$(document).ready(function(){
    $('#region').change(function(){
        $.post('/default/ajax/getcitiesforregion', {
            regionID:$(this).val()
            }, function(resp){
            select_option = $("#cityTemplate").tmpl(JSON.parse(resp.data));
            $("#city").html(select_option);
            updateDoctorType($("#city").val(), $('#clinicType').val());
            updateClinic();
        }, 'json');
    });
    $('#city, #clinicType').change(function(){
        updateDoctorType($('#city').val(), $('#clinicType').val());
        clinClinCalendar();
        clinClinic();
    });
    $('#searchinput').keyup(function(event) {
        updateDoctorType($('#city').val(), $('#clinicType').val(),$(this).val());
    //            updateClinic();
    });
    $('#doctortype').change(function(){
        updateClinic();
    });
//    $(document).on('click','.hospital_select',function(){
//        $('.hospital_select').each(function(i){
//           if(i%2 != 0)
//                $(this).attr('style', 'background: rgb(248, 234, 226);');
//            else
//                $(this).attr('style', 'background: white;');
//        });
//        $(this).attr('style', 'background: rgb(216, 215, 215);');
//        updateCalendar($(this).attr('paramID'),$('#doctortype').val()); 
//    });
})
function clinClinic(){
    $("#hoshital_type").html('');
}
    
function clinClinCalendar(){
    $('#calendar').hide();
    $("#calendarBody").html('');
    $('#clinicinfo').html('');
}

   
function updateClinic(){
    $.post('/default/ajax/gethospital', {
        cityID:$('#city').val(), 
        clinicType:$('#clinicType').val(), 
        doctortype: $('#doctortype').val(), 
        regionID:$('#region').val()
        }, function(resp){
        searchText = $('#searchinput').val();
        history.pushState('','','?city='+$('#city').val()+'&clinic_managnent_form='+$('#clinicType').val()+'&search_doc_spec='+searchText+'&doctor_speciality='+$('#doctortype').val());
        select_option = $("#hoshital_typeTemplate").tmpl(JSON.parse(resp.data));
        console.log(select_option);
        $("#hoshital_type").html(select_option);
        clinClinCalendar();
    }, 'json');
}
    
function getPreviousWeek(){
    fday = $('#fday').text();
    month = $('#month').attr('attr');
    year = $('#year').text();
    l = $('#l').text();
          
    if(fday-7<1){
        if(month -1<1){
            year = year -1;
            month = 12;
            l = IsLeapYear(year);
        }else{
            month = month-1;
        }
        if(month == 1||month ==4||month ==6||month ==9||month ==11){
            day_in_month = 30;
        }else if(month == 3||month == 5||month ==7||month ==8||month ==10 ||month ==12){
            day_in_month = 31;
        }else{
            day_in_month = (l==0)?28:29;
        }
        new_fday = day_in_month +parseInt(fday)-7;
    }else{
        new_fday = fday-7
    }
    $.post('/default/ajax/calendar', {
        day:new_fday, 
        month:month, 
        year:year, 
        l:l
    }, function(resp){
        $('#calendar').html(resp);
        updateCalendar(); 
    }, 'html');
}
function getNextWeek(){
    fday = $('#fday').text();
    month = $('#month').attr('attr');
    year = $('#year').text();
    l = $('#l').text();
    if(month == 1||month ==4||month ==6||month ==9||month ==11){
        day_in_month = 30;
    }else if(month == 3||month == 5||month ==7||month ==8||month ==10 ||month ==12){
        day_in_month = 31;
    }else{
        day_in_month = (l==0)?28:29;
    }
    if(parseInt(fday)+7>day_in_month){
        if(parseInt(month) +1>12){
            year = parseInt(year) +1;
            month = 1;
            l = IsLeapYear(year);
        }else{
            month = parseInt(month)+1;
        }
        new_fday = parseInt(fday)+parseInt(7)-day_in_month;
    }else{
        new_fday = parseInt(fday)+7
    }
    $.post('/default/ajax/calendar', {
        day:new_fday, 
        month:month, 
        year:year, 
        l:l
    }, function(resp){
        $('#calendar').html(resp);
        updateCalendar(); 
    }, 'html');
}
      
function IsLeapYear(year) {
    if(year%4 == 0) {
        if(year%100 == 0) {
            if(year%400 == 0) {
                return true;
            }
            else
                return false;
        }
        else
            return true;
    }
    return false;
}
//function updateCalendar(clinicID, doctorTypeID){
//    $.post('/default/ajax/getcalendar', {
//        clinicID:clinicID,
//        doctorTypeID:doctorTypeID
//    }, function(resp){
//        console.log(resp.data);
//        if(resp.data != '[]'){
//            select_option = $("#calendarTemplate").tmpl(JSON.parse(resp.data));
//            $("#calendarBody").html(select_option);
//            $("#calendarBody").html(html_entity_decode($("#calendarBody").html()));
//            $('#calendar').show();}
//        else{
//             $('#calendar').hide();
//        }
//        $('#clinicinfo').html(resp.info[<?=Zend_Registry::get('uds_lang')?>'_about']);
//    }, 'json');
//}
function get_html_translation_table (table, quote_style) {
    var entities = {},
    hash_map = {},
    decimal;
    var constMappingTable = {},
    constMappingQuoteStyle = {};
    var useTable = {},
    useQuoteStyle = {};

    // Translate arguments
    constMappingTable[0] = 'HTML_SPECIALCHARS';
    constMappingTable[1] = 'HTML_ENTITIES';
    constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
    constMappingQuoteStyle[2] = 'ENT_COMPAT';
    constMappingQuoteStyle[3] = 'ENT_QUOTES';

    useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
    useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() : 'ENT_COMPAT';

    if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
        throw new Error("Table: " + useTable + ' not supported');
    // return false;
    }

    entities['38'] = '&amp;';
    if (useTable === 'HTML_ENTITIES') {
        entities['160'] = '&nbsp;';
        entities['161'] = '&iexcl;';
        entities['162'] = '&cent;';
        entities['163'] = '&pound;';
        entities['164'] = '&curren;';
        entities['165'] = '&yen;';
        entities['166'] = '&brvbar;';
        entities['167'] = '&sect;';
        entities['168'] = '&uml;';
        entities['169'] = '&copy;';
        entities['170'] = '&ordf;';
        entities['171'] = '&laquo;';
        entities['172'] = '&not;';
        entities['173'] = '&shy;';
        entities['174'] = '&reg;';
        entities['175'] = '&macr;';
        entities['176'] = '&deg;';
        entities['177'] = '&plusmn;';
        entities['178'] = '&sup2;';
        entities['179'] = '&sup3;';
        entities['180'] = '&acute;';
        entities['181'] = '&micro;';
        entities['182'] = '&para;';
        entities['183'] = '&middot;';
        entities['184'] = '&cedil;';
        entities['185'] = '&sup1;';
        entities['186'] = '&ordm;';
        entities['187'] = '&raquo;';
        entities['188'] = '&frac14;';
        entities['189'] = '&frac12;';
        entities['190'] = '&frac34;';
        entities['191'] = '&iquest;';
        entities['192'] = '&Agrave;';
        entities['193'] = '&Aacute;';
        entities['194'] = '&Acirc;';
        entities['195'] = '&Atilde;';
        entities['196'] = '&Auml;';
        entities['197'] = '&Aring;';
        entities['198'] = '&AElig;';
        entities['199'] = '&Ccedil;';
        entities['200'] = '&Egrave;';
        entities['201'] = '&Eacute;';
        entities['202'] = '&Ecirc;';
        entities['203'] = '&Euml;';
        entities['204'] = '&Igrave;';
        entities['205'] = '&Iacute;';
        entities['206'] = '&Icirc;';
        entities['207'] = '&Iuml;';
        entities['208'] = '&ETH;';
        entities['209'] = '&Ntilde;';
        entities['210'] = '&Ograve;';
        entities['211'] = '&Oacute;';
        entities['212'] = '&Ocirc;';
        entities['213'] = '&Otilde;';
        entities['214'] = '&Ouml;';
        entities['215'] = '&times;';
        entities['216'] = '&Oslash;';
        entities['217'] = '&Ugrave;';
        entities['218'] = '&Uacute;';
        entities['219'] = '&Ucirc;';
        entities['220'] = '&Uuml;';
        entities['221'] = '&Yacute;';
        entities['222'] = '&THORN;';
        entities['223'] = '&szlig;';
        entities['224'] = '&agrave;';
        entities['225'] = '&aacute;';
        entities['226'] = '&acirc;';
        entities['227'] = '&atilde;';
        entities['228'] = '&auml;';
        entities['229'] = '&aring;';
        entities['230'] = '&aelig;';
        entities['231'] = '&ccedil;';
        entities['232'] = '&egrave;';
        entities['233'] = '&eacute;';
        entities['234'] = '&ecirc;';
        entities['235'] = '&euml;';
        entities['236'] = '&igrave;';
        entities['237'] = '&iacute;';
        entities['238'] = '&icirc;';
        entities['239'] = '&iuml;';
        entities['240'] = '&eth;';
        entities['241'] = '&ntilde;';
        entities['242'] = '&ograve;';
        entities['243'] = '&oacute;';
        entities['244'] = '&ocirc;';
        entities['245'] = '&otilde;';
        entities['246'] = '&ouml;';
        entities['247'] = '&divide;';
        entities['248'] = '&oslash;';
        entities['249'] = '&ugrave;';
        entities['250'] = '&uacute;';
        entities['251'] = '&ucirc;';
        entities['252'] = '&uuml;';
        entities['253'] = '&yacute;';
        entities['254'] = '&thorn;';
        entities['255'] = '&yuml;';
    }

    if (useQuoteStyle !== 'ENT_NOQUOTES') {
        entities['34'] = '&quot;';
    }
    if (useQuoteStyle === 'ENT_QUOTES') {
        entities['39'] = '&#39;';
    }
    entities['60'] = '&lt;';
    entities['62'] = '&gt;';


    // ascii decimals to real symbols
    for (decimal in entities) {
        if (entities.hasOwnProperty(decimal)) {
            hash_map[String.fromCharCode(decimal)] = entities[decimal];
        }
    }

    return hash_map;
}
function html_entity_decode (string, quote_style) {
    var hash_map = {},
    symbol = '',
    tmp_str = '',
    entity = '';
    tmp_str = string.toString();

    if (false === (hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style))) {
        return false;
    }

    // fix &amp; problem
    // http://phpjs.org/functions/get_html_translation_table:416#comment_97660
    delete(hash_map['&']);
    hash_map['&'] = '&amp;';

    for (symbol in hash_map) {
        entity = hash_map[symbol];
        tmp_str = tmp_str.split(entity).join(symbol);
    }
    tmp_str = tmp_str.split('&#039;').join("'");

    return tmp_str;
}
