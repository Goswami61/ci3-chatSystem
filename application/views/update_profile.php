<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap');

*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body{
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #f6f8fa;
    font-family: 'Poppins', sans-serif;
}

.container{
    max-width: 700px;
    width: 100%;
    background: #ffffff;
    border-radius: 0.5rem;
    box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0.1),
                0px 5px 12px -2px rgba(0, 0, 0, 0.1),
                0px 18px 36px -6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin: 10px;
}

a {
    font-size: 16px;
    font-weight: 500;
    text-decoration: none;
    color: #D64141;
    padding: 8px 12px;
    border: 1px solid #D64141;
    border-radius: 5px;
    transition: all 0.3s ease-in-out;
}

a:hover {
    background: #D64141;
    color: #fff;
}

/* .container .title{
    padding: 25px;
    background: #f6f8fa;
}

.container .title p{
    font-size: 25px;
    font-weight: 500;
    position: relative;
}

.container .title p::before{
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 30px;
    height: 3px;
    background: linear-gradient(to right, #F37A65, #D64141);
} */
.title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding: 10px 20px;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
}

    .title p {
        font-size: 25px;
        font-weight: 500;
        position: relative;
    }

    /* Active user ko center me properly align karne ke liye */
    .user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-info img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #D64141;
}

.user-info .name {
    font-size: 20px;
    font-weight: 600;
    color: #333;
}


.user_details{
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    padding: 25px;
}

.user_details .input_box{
    width: calc(100% / 2 - 20px);
    margin: 0 0 12px 0;
}

.input_box label{
    font-weight: 500;
    margin-bottom: 5px;
    display: block;
}

.input_box label::after{
    content: " *";
    color: red;
}

.input_box input{
    width: 100%;
    height: 45px;
    border: none;
    outline: none;
    border-radius: 5px;
    font-size: 16px;
    padding-left: 15px;
    box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0.1);
    background-color: #f6f8fa;
    font-family: 'Poppins', sans-serif;
    transition: all 120ms ease-out 0s;
}


.input_box input:focus,
.input_box input:valid{
    box-shadow: 0px 0px 0px 2px #AC8ECE;
}

form .gender{
    padding: 0px 25px;
}

.gender .gender_title{
    font-size: 20px;
    font-weight: 500;
}

.gender .category{
    width: 80%;
    display: flex;
    justify-content: space-between;
    margin: 5px 0;
}

.gender .category label{
    display: flex;
    align-items: center;
    cursor: pointer;
}

.gender .category label .dot{
    height: 18px;
    width: 18px;
    background: #d9d9d9;
    border-radius: 50%;
    margin-right: 10px;
    border: 4px solid transparent;
    transition: all 0.3s ease;
}

#radio_1:checked ~ .category label .one,
#radio_2:checked ~ .category label .two,
#radio_3:checked ~ .category label .three{
    border-color: #d9d9d9;
    background: #D64141;
}

.gender input{
    display: none;
}

.reg_btn{
    padding: 25px;
    margin: 15px 0;
}

.reg_btn input{
    height: 45px;
    width: 100%;
    border: none;
    font-size: 18px;
    font-weight: 500;
    cursor: pointer;
    background: linear-gradient(to right, #F37A65, #D64141);
    border-radius: 5px;
    color: #ffffff;
    letter-spacing: 1px;
    text-shadow: 0px 2px 2px rgba(0, 0, 0, 0.2);
}

.reg_btn input:hover{
    background: linear-gradient(to right, #D64141, #F37A65);
}

@media screen and (max-width: 584px){

    .user_details{
        max-height: 340px;
        overflow-y: scroll;
    }

    .user_details::-webkit-scrollbar{
        width: 0;
    }

    .user_details .input_box{
        width: 100%;
    }

    .gender .category{
        width: 100%;
    }

}


@media screen and (max-width: 419px){
    .gender .category{
        flex-direction: column;
    }   
}

.custom-file-upload {
    position: relative;
    display: inline-block;
    width: 100%;
    height: 45px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background: #f6f8fa;
    cursor: pointer;
    text-align: center;
    line-height: 45px;
    font-size: 16px;
    color: #333;
}

.custom-file-upload input[type="file"] {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.image-preview {
    margin-top: 10px;
    text-align: center;
}

.image-preview img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #D64141;
    display: block;
    margin: 0 auto;
}

    </style>
</head>
<body>
<div class="container">
<div class="title">
    <div class="user-info">
        <a href="<?= base_url("dashboard");?>">
            <img src="<?= base_url("/").$active_user['img'] ?>" alt="Profile pic">
        </a>
        <div>
            <span class="name"><?= $active_user['name']; ?></span>
        </div>
    </div>
    <a href="<?= base_url('logout'); ?>">Logout</a>
</div>
        <div id="successMessage" style="display:none; color:green"></div>
        <form id="updateUser" enctype="multipart/form-data">
        <input type="hidden" id="id" name="id" value="<?= $active_user['id'];?>">
            <div class="user_details">
                <div class="input_box">
                    <label for="name">Username</label>
                    <input type="text" id="name" name="name" value="<?= $active_user['name'];?>" placeholder="Enter your username"><div id="error_name" style="color:red"></div>
                </div>
                <!-- <div class="input_box">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email"><div id="error_email" style="color:red"></div>
                </div> -->
                <!-- <div class="input_box">
                    <label for="mobile">Phone Number</label>
                    <input type="text" id="mobile" name="mobile" placeholder="Enter your number"><div id="error_mobile" style="color:red"></div>
                </div> -->
                <div class="input_box">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?= $active_user['address'];?>" placeholder="Enter your address"><div id="error_address" style="color:red"></div>
                </div>
                <!-- <div class="input_box">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password"><div id="error_password" style="color:red"></div>
                </div>
                <div class="input_box">
                    <label for="cpassword">Confirm Password</label>
                    <input type="password" id="cpassword" name="cpassword" placeholder="Confirm your password"><div id="error_cpassword" style="color:red"></div>
                </div> -->
                <div class="input_box">
                    <label for="img">Profile</label>
                    <input type="hidden" id="old_img" name="old_img" value="<?= $active_user['img'];?>">
                    <input type="file" id="img" name="img"><div id="error_img" style="color:red">
                    <img src="<?= base_url("/").$active_user['img'];?>" width="100px" height="100px" alt="">
                    </div>
                </div>

                <!-- <div class="input_box">
                <label for="img">Profile</label>
                <div class="custom-file-upload">
                    <input type="file" id="img" name="img" accept="image/*"  onchange="previewImage(event)">
                    <span id="fileLabel">Choose File</span>
                </div>
                <div id="error_img" style="color:red"></div>
                <div class="image-preview">
                    <img id="imgPreview" src="" alt="Profile Preview" style="display:none;">
                </div>
                <img src="<?= base_url("/").$active_user['img'];?>" width="100px" height="100px" alt="">
            </div> -->
            
            </div>
            <div class="gender">
                <span class="gender_title">Gender</span>
                <input type="radio" name="gender" id="radio_1" value="male" <?php if($active_user['gender']== "male"){ echo "checked";}?>>
                <input type="radio" name="gender" id="radio_2" value="female" <?php if($active_user['gender']== "female"){ echo "checked";}?>>
                <input type="radio" name="gender" id="radio_3" value="other" <?php if($active_user['gender']== "other"){ echo "checked";}?>>

                <div class="category">
                    <label for="radio_1">
                        <span class="dot one"></span>
                        <span>Male</span>
                    </label>
                    <label for="radio_2">
                        <span class="dot two"></span>
                        <span>Female</span>
                    </label>
                    <label for="radio_3">
                        <span class="dot three"></span>
                        <span>Prefer not to say</span>
                    </label>
                </div>
            </div><div id="error_gender" style="color:red"></div>
            <div class="reg_btn">
                <input type="submit" name="submit" value="Update Profile">
            </div>
        </form>

        <!-- Change Password -->
         <p>Change Password</p>
         <div id="psuccessMessage" style="display:none; color:green"></div>
        <form id="changePassword" enctype="multipart/form-data">
        <input type="hidden" id="id" name="id" value="<?= $active_user['id'];?>">
            <div class="user_details">
                <div class="input_box">
                    <label for="address">Old Password</label>
                    <input type="text" id="old_password" name="old_password" placeholder="Enter your old password "><div id="error_old_password" style="color:red"></div>
                </div>
                <div class="input_box">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password"><div id="error_password" style="color:red"></div>
                </div>
                <div class="input_box">
                    <label for="cpassword">Confirm Password</label>
                    <input type="password" id="cpassword" name="cpassword" placeholder="Confirm your password"><div id="error_cpassword" style="color:red"></div>
                </div>
            </div>
            <div class="reg_btn">
                <input type="submit" name="submit" value="Change Password">
            </div>
        </form>
    </div>
</body>

<!-- <script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var imgPreview = document.getElementById('imgPreview');
        imgPreview.src = reader.result;
        imgPreview.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);

    // Show file name in label
    var fileLabel = document.getElementById('fileLabel');
    fileLabel.textContent = event.target.files[0].name;
}
</script> -->

<script>
$(document).ready(function(){
    $('#updateUser').on('submit',function(e){

        e.preventDefault();

        $('error_name').html(' ');
        // $('error_email').html(' ');
        // $('error_mobile').html(' ');
        $('error_address').html(' ');
        // $('error_password').html(' ');
        // $('error_cpassword').html(' ');
        $('error_img').html(' ');
        $('error_gender').html(' ');
        $('successMessage').hide();
        var formData = new FormData(this); // FormData object banaye
        $.ajax({
            url:'<?php echo base_url('update-profile'); ?>',
            dataType:'json',
            data:formData,
            type:'POST',
            contentType: false,  
            processData: false,
            success:function(response){
                console.log(response);
                if(response.status=='error'){
                    if(response.error.name){
                        $('#error_name').html(response.error.name);
                    }

                    // if(response.error.email){
                    //     $('#error_email').html(response.error.email);
                    // }

                    // if(response.error.mobile){
                    //     $('#error_mobile').html(response.error.mobile);
                    // }

                    // if(response.error.password){
                    //     $('#error_password').html(response.error.password);
                    // }

                    // if(response.error.cpassword){
                    //     $('#error_cpassword').html(response.error.cpassword);
                    // }

                    if(response.error.img){
                        $('#error_img').html(response.error.img);
                    }

                    if(response.error.address){
                        $('#error_address').html(response.error.address);
                    }

                    if(response.error.gender){
                        $('#error_gender').html(response.error.gender);
                    }

                }else if(response.status=='success'){
                    $('#successMessage').html(response.message).show();

                    setTimeout(() => {
                        $('#successMessage').fadeOut();
                    }, 3000);

                    $('#updateUser')[0].reset();
                }
            }
        })

    })
})
</script>

<!-- Change Password -->

<script>
$(document).ready(function(){
    $('#changePassword').on('submit',function(e){

        e.preventDefault();

        // $('error_name').html(' ');
        // $('error_email').html(' ');
        // $('error_mobile').html(' ');
        // $('error_address').html(' ');
        $('error_old_password').html(' ');
        $('error_password').html(' ');
        $('error_cpassword').html(' ');
        // $('error_img').html(' ');
        // $('error_gender').html(' ');
        $('psuccessMessage').hide();
        var formData = new FormData(this); // FormData object banaye
        $.ajax({
            url:'<?php echo base_url('change-password'); ?>',
            dataType:'json',
            data:formData,
            type:'POST',
            contentType: false,  
            processData: false,
            success:function(response){
                console.log(response);
                if(response.status=='error'){
                    // if(response.error.name){
                    //     $('#error_name').html(response.error.name);
                    // }

                    // if(response.error.email){
                    //     $('#error_email').html(response.error.email);
                    // }

                    // if(response.error.mobile){
                    //     $('#error_mobile').html(response.error.mobile);
                    // }

                    if(response.error.old_password){
                        $('#error_old_password').html(response.error.old_password);
                    }
                    if(response.error.password){
                        $('#error_password').html(response.error.password);
                    }

                    if(response.error.cpassword){
                        $('#error_cpassword').html(response.error.cpassword);
                    }

                    // if(response.error.img){
                    //     $('#error_img').html(response.error.img);
                    // }

                    // if(response.error.address){
                    //     $('#error_address').html(response.error.address);
                    // }

                    // if(response.error.gender){
                    //     $('#error_gender').html(response.error.gender);
                    // }

                }else if(response.status=='success'){
                    $('#psuccessMessage').html(response.message).show();

                    setTimeout(() => {
                        $('#psuccessMessage').fadeOut();
                    }, 3000);

                    $('#changePassword')[0].reset();
                }
            }
        })

    })
})
</script>
</html>