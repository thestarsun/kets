
function changeCategory(obj)
    {
        $.post("/default/ajax/getsubcategory",
        {
            categoryId: $(obj).val()
        },
        function(data){
            $('#subCategory').remove();
            $('#subCategoryBlock').append(data);
        });
    }
    
    function getDeals()
    {
        $.post("/default/ajax/getdeals",
        {
            categoryId: $("#category").val(),
            subcategoryId: $("#subCategory").val(),
            discountPrice: $("#amount").val(),
            cityId: $("#city").val(),
            pageNumb: $("#pageNumb").val()
            
        },
        function(data){
            
            $('#dealsList').html(data);
        });
    }
    
    function getDataByPage(pageNumb)
    {
        $("#pageNumb").val(pageNumb);
        getDeals();
    }
    
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
    
    
    