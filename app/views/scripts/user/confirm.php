<?php if(!$this->status) {?>
    <div class="text-center">
        <h1>Confirm you email</h1>
        <h3>check your email i sent you message</h3>
        <p>After confirming the mail, you can login</p>
    </div>
<?php } else {?>
    <div class="text-center">
        <h1>Congratulations</h1>
        <h3>You have successfully confirmed the mail</h3>

        <a href="/login" class="btn btn-success">Login Now</a>
    </div>
<?php } ?>
