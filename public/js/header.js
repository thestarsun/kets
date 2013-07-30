
$(document).ready(function() {
        login = <?if(!empty($_SESSION['Zend_Auth']['storage'])):?>1;<?else:?>0;<?endif;?>
        if (login == 0){
            $('#login').show();
            $('#registration').show();
            $('#logoutBox').hide();
        }else{
            $('#login').hide();
            $('#registration').hide();
            $('#logoutBox').show();
        }
        $('#registration').click(function(){
            window.location ="<?= $this->url(array(), 'registration')?>";      
        });
        $('#restorepass').click(function(){
            window.location ="<?= $this->url(array(), 'restorepass')?>";      
        });
        $('#login').click(function(){
            $('#loginFormBox').slideDown();
        });
//        $('#LoginForm').ajaxForm(function(){
//             $('#login').show();
//            $('#registration').show();
//            $('#logoutBox').hide();
//        });
        
        $('#logout').click(function(){
            $.post('/default/auth/logout', '', function(result){
                if(result.success == 1) login = 0;
            }, 'json');
            window.location ="/default/auth/logout";
        });
        $("#closeLogin").click(function(){
            $('#loginFormBox').slideUp();
        })
        $('#indexPage').click(function(){
            window.location ="<?= $this->url(array(), 'base')?>";
        }) 
    }); 


