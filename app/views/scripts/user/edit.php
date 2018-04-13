<div class="row">
    <div class="col-md-12">
        <h3 class="text-center">Edit profile</h3>
        <form method="post" action="/profile/edit">
            <div class="form-group <?php echo ($this->error['username']) ? 'has-error' : '' ?>">
                <label>Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo $this->entity['username'] ?>">
                <?php if ($this->error['username']) {?>
                    <span class="text-warning"><?php echo $this->error['username'] ?></span>
                <?php } ?>
            </div>
            <div class="form-group <?php echo ($this->error['email']) ? 'has-error' : '' ?>">
                <label>E-mail</label>
                <input type="text" class="form-control" name="email" placeholder="E-mail" value="<?php echo $this->entity['email'] ?>">
                <?php if ($this->error['email']) {?>
                    <span class="text-warning"><?php echo $this->error['email'] ?></span>
                <?php } ?>
            </div>
            <div class="form-group <?php echo ($this->error['password']) ? 'has-error' : '' ?>">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" value="<?php echo $this->entity['password'] ?>">
                <?php if ($this->error['password']) {?>
                    <span class="text-warning"><?php echo $this->error['password'] ?></span>
                <?php } ?>
            </div>
            <div class="form-group <?php echo ($this->error['confirmPassword']) ? 'has-error' : '' ?>">
                <label>Confirm password</label>
                <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm password"  value="<?php echo $this->entity['confirmPassword']?>">
                <?php if ($this->error['confirmPassword']) {?>
                    <span class="text-warning"><?php echo $this->error['confirmPassword'] ?></span>
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</div>
