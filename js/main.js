$(document).ready( function () {
    
    $('#pass_toggle').on('click', function(){
        if($('#pass').attr('type') == 'text'){
            $('#pass').attr('type', 'password');
            console.log('to password');
        } else if($('#pass').attr('type') == 'password'){
            $('#pass').attr('type', 'text');   
            console.log('to text');
        } 
    });

});