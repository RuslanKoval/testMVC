<h3 class="text-center">View profile</h3>
<div class="list-group">
    <div class="list-group-item">
        <div class="row">
            <div class="col-md-4">Username</div>
            <div class="col-md-8"><?= $this->user->getUsername() ?></div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row">
            <div class="col-md-4">E-mail</div>
            <div class="col-md-8"><?= $this->user->getEmail() ?></div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row">
            <div class="col-md-4">Created</div>
            <div class="col-md-8"><?= date('d-m-Y', $this->user->getCreated() ) ?></div>
        </div>
    </div>
</div>

<a href="/profile/edit" class="btn btn-default">Edit profile</a>
