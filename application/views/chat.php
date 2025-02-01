<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

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
            max-width: 500px;
            width: 100%;
            background: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0.1),
                        0px 5px 12px -2px rgba(0, 0, 0, 0.1),
                        0px 18px 36px -6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 20px;
        }

        /* .title {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f6f8fa;
            border-bottom: 1px solid #e0e0e0;
        } */

        .title {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #f6f8fa;
    border-bottom: 1px solid #e0e0e0;
    position: relative;
}

.title .back-arrow {
    text-decoration: none;
    color: #333333;
    font-size: 24px;
    font-weight: bold;
    margin-right: 10px;
    transition: color 0.3s;
}

.title .back-arrow:hover {
    color: #d64141;
}

        .title img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .title .info {
            flex-grow: 1;
        }

        .title .info p {
            margin: 0;
        }

        .title .info .name {
            font-size: 18px;
            font-weight: 500;
            color: #333333;
        }

        .title .info .status {
            font-size: 14px;
            color: #00b894;
        }

        .messages {
            min-height: 300px;
            max-height: 400px;
            overflow-y: auto;
            padding: 15px;
            background: #f9f9f9;
            border-bottom: 1px solid #e0e0e0;
        }

        .message {
            margin-bottom: 15px;
        }

        .message.sent {
            text-align: right;
        }

        .message p {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 15px;
            font-size: 14px;
            max-width: 70%;
        }

        .message.received p {
            background: #f1f1f1;
            color: #333333;
        }

        .message.sent p {
            background: #d64141;
            color: #ffffff;
        }

        .input-section {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #ffffff;
        }

        .input-section input[type="text"] {
            flex-grow: 1;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
        }

        .input-section .reg_btn {
            margin-left: 10px;
        }

        .input-section .reg_btn input {
            height: 40px;
            width: 100px;
            border: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            background: linear-gradient(to right, #f37a65, #d64141);
            border-radius: 5px;
            color: #ffffff;
            letter-spacing: 1px;
            text-shadow: 0px 2px 2px rgba(0, 0, 0, 0.2);
        }

        .input-section .reg_btn input:hover {
            background: linear-gradient(to right, #d64141, #f37a65);
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Title Section -->
    <div class="title">

    <a href="<?= base_url('dashboard') ?>" class="back-arrow">
        &#8592; <!-- Unicode for left arrow -->
    </a>
        <img src="<?= base_url("/").$user['img'];?>" alt="profile">
        <div class="info">
            <p class="name"><?= $user['name'] ?></p>
            <p class="status">Online</p>
        </div>
    </div>

    <!-- Messages Section -->
    <div class="messages" id="messageContainer">
    <?php foreach($messages as $msg){ ?>
    <div class="message <?= $msg['sender_id'] == $_SESSION['users']->id ? 'sent' : 'received' ?>">
        <p><?= htmlspecialchars($msg['message'], ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <?php } ?>
</div>

    <!-- Input Section -->
    <form id="sendMessage" class="input-section">
        <input type="hidden" name="sender_id" value="<?= $_SESSION['users']->id?>">
        <input type="hidden" name="reciever_id" id="receiver_id" value="<?= $user['id']?>">
        <input type="text" name="message" placeholder="Type a message"><div id="error_message" style="color:red;"></div>
        <div class="reg_btn">
            <input type="submit" name="submit" value="Send">
        </div>
    </form>
</div>
</body>

<script>
$(document).ready(function(){

    // Auto-scroll to the bottom of the message container
    function scrollToBottom() {
        var container = $('#messageContainer');
        container.scrollTop(container.prop('scrollHeight'));
    }

    scrollToBottom(); // Initial scroll to the bottom

    $('#sendMessage').on('submit',function(e){

        e.preventDefault();

        $('error_message').html(' ');
        // $('successMessage').hide();

        $.ajax({
            url:'<?php echo base_url('send-message'); ?>',
            dataType:'json',
            data:$(this).serialize(),
            type:'POST',
            success:function(response){
                console.log(response);
                if(response.status=='error'){
                    if(response.error.message){
                        $('#error_message').html(response.error.message);
                    }

                }else if(response.status=='success'){
                    $('#sendMessage')[0].reset();
                    loadMessage();
                }
            }
        })

    })



    function loadMessage() {
        let receiverId = $('#receiver_id').val();
        $.ajax({
            url:'<?php echo base_url('get-message'); ?>',
            dataType:'json',
            data:{ receiver_id: receiverId },
            type:'GET',
            success:function(response){
                // console.log(response);
                if(response.status=='error'){
                    if(response.error.message){
                        $('#error_message').html(response.error.message);
                    }

                }else if(response.status=='success'){

                    // console.log(response.data);

                    let messageContainer = $('.messages');
                    messageContainer.html(' ');

                    response.data.forEach((msg)=>{
                        let messageClass = msg.sender_id == <?= $_SESSION['users']->id ?>? 'sent':'received';
                        let messageHTML = `<div class="message ${messageClass}"><p>${msg.message}</p></div>`;
                        messageContainer.append(messageHTML)
                    })
                    
                    messageContainer.scrollTop(messagesContainer[0].scrollHeight); // Scroll to bottom
                    
                }
            }
        })
    }

    // Call `loadMessages` every few seconds to refresh messages
setInterval(loadMessage, 1000); // Adjust interval as needed
})


</script>
</html>
