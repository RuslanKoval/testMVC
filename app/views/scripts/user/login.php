<?php if ($this->status == false) {?>
    <div class="alert alert-warning">
        Incorrect login or password
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <h3 class="text-center">Authorization</h3>
        <form method="post" action="/login">
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="login" placeholder="Username" value="">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" value="">
            </div>

            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</div>
