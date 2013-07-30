function getHistory()
    {
        $.post("/default/ajax/gethistory",
        {
            pageNumb: $("#pageNumb").val(),
            sort: $("#sortHistory").val(),
            filter: $("#filterHistory").val()
            
        },
        function(data){
            
           $('#historyList').html(data);
        });
    }
    
    
    function getDataByPage(pageNumb)
    {
        $("#pageNumb").val(pageNumb);
        getHistory();
    }
    
    $(document).ready(function() {
      getHistory();
      
      $("#sortHistory").change(function()
      {
          getHistory();
      });
      $("#filterHistory").change(function()
      {
          $("#pageNumb").val(0);
          getHistory();
      });
      
    });