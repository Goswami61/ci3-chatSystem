<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f6f8fa;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 700px;
            width: 100%;
            background: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0.1),
                        0px 5px 12px -2px rgba(0, 0, 0, 0.1),
                        0px 18px 36px -6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 10px;
            padding: 20px;
        }

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

/* .user-info .status {
    font-size: 14px;
    color: green;
    font-weight: 500;
} */



        /* .title p::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 3px;
            background: linear-gradient(to right, #F37A65, #D64141);
        } */

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

        table {
            width: 95%; /* Table is slightly smaller than the card */
            margin: 0 auto; /* Center the table within the card */
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead tr {
            background: #f6f8fa;
        }

        table thead th {
            text-align: left;
            padding: 10px;
            font-weight: 500;
        }

        table tbody tr {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
        }

        table tbody td {
            padding: 10px;
            vertical-align: middle;
        }

        table tbody td img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

     
        table tbody td .name {
            font-weight: 500;
            font-size: 16px;
            color: #333;
        }

        table tbody td .reg_btn {
            height: 35px;
            width: 80px;
            border: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            background: linear-gradient(to right, #F37A65, #D64141);
            border-radius: 5px;
            color: #ffffff;
            text-shadow: 0px 2px 2px rgba(0, 0, 0, 0.2);
        }

        table tbody td .reg_btn:hover {
            background: linear-gradient(to right, #D64141, #F37A65);
        }

        table tbody td .req_btn {
            height: 35px;
            width: 80px;
            border: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            background: linear-gradient(to right, #F37A65, #D64141);
            border-radius: 5px;
            color: #ffffff;
            text-shadow: 0px 2px 2px rgba(0, 0, 0, 0.2);
        }

        table tbody td .req_btn:hover {
            background: linear-gradient(to right, #D64141, #F37A65);
        }

        table tfoot {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="title">
    <div class="user-info">
        <a href="<?= base_url("profile");?>">
            <img src="<?= base_url("/").$active_user['img'] ?>" alt="Profile pic">
        </a>
        <div>
            <span class="name"><?= $active_user['name']; ?></span>
        </div>
    </div>
    <a href="<?= base_url('logout'); ?>">Logout</a>
</div>
        <div id = "reqMessage" style="display:none; color:green;"></div>
        <table id="example" class="display">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php  foreach($lists as $list){ ?>
                <tr>
                    <?php //if(($list['reciever_id'] != $_SESSION['users']->id)){ ?>
                    <!-- <td><img src="https://picsum.photos/536/354" alt="profile"></td> -->
                    <td><img src="<?= base_url("/").$list['img'];?>" alt="profile"></td>
                    <td><span class="name"><?= $list['name']?></span></td>
                    <td><button type="button" data-id="<?= $list['id']?>" class="reg_btn">Chat</button></td>
                     <!-- <?php //if($list['req_status']==1){ if($list['sender_id']== $_SESSION['users']->id){?>
                        <td><button data-id="<?= $list['id']?>" data-sender_id ="<?= $_SESSION['users']->id?>" data-status="3" class="req_btn">Cancle</button></td>
                        <?php //} else{?>
                            <td><button data-id="<?= $list['id']?>" data-sender_id ="<?= $_SESSION['users']->id?>" data-status="2" class="req_btn">Accept</button> 
                            <button data-id="<?= $list['id']?>" data-sender_id ="<?= $_SESSION['users']->id?>" data-status="3" class="req_btn">Reject</button></td>
                        <?php// } }else if($list['req_status']==2){?>
                    <td><button data-id="<?= $list['id']?>" class="reg_btn">Chat</button></td>
                    <?php //} else{?>
                    <td><button data-id="<?= $list['id']?>" data-sender_id ="<?= $_SESSION['users']->id?>" data-status="1" class="req_btn">Request</button></td>
                    <?php //}
               //  }?> -->
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        new DataTable('#example');


        $(document).ready(function(){
    $('.reg_btn').on('click',function(e){

        e.preventDefault();
        const userId = $(this).data('id'); 
        $.ajax({
            url:'<?= base_url('start'); ?>',
            type:'POST',
            dataType:'json',
            data:{id:userId},
            success:function(response){
                console.log(response);
                if(response.status=='error'){
        alert(response.message);
                }else if(response.status=='success'){
                    window.location.href = response.redirect
                 }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: ", error); // Agar server se koi error ho
            }

        })

    })
})
    $(document).ready(function(){
    $('.req_btn').on('click',function(e){

        
e.preventDefault();
const userId = $(this).data('id'); 
const senderId = $(this).data('sender_id'); 
const status = $(this).data('status'); 
$('reqMessage').hide();
$.ajax({
    url:'<?= base_url('start'); ?>',
    dataType:'json',
    data:{id:userId, sender_id: senderId, status:status},
    type:'POST',
    success:function(response){
        console.log(response);
        if(response.status=='error'){
alert(response.message);
        }else if(response.status=='success'){
            // alert(response.message);
            // window.location.href = response.redirect
            $('#reqMessage').html(response.message).show();

            setTimeout(() => {
                $('#reqMessage').fadeOut();
            }, 3000);

         }
    }
})

})

})
    </script>
</body>
</html>
