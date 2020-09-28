function show_password(){
        if( $('#input_password').attr('type') == 'password' ){
            $('#input_password').attr('type', 'text');
            $('#label_pass').text('visibility');
            // console.log('x');
        } else {
            $('#input_password').attr('type', 'password');
            $('#label_pass').text('visibility_off');
            // console.log('y');
        }
    }  
    
    function show_pass_2(){
        if( $('#input_password_2').attr('type') == 'password' ){
            $('#input_password_2').attr('type', 'text');
            $('#label_pass_2').text('visibility');
        } else {
            $('#input_password_2').attr('type', 'password');
            $('#label_pass_2').text('visibility_off');
        }
    }  
