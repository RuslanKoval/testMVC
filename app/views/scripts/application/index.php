<?php

use app\services\user\User;

$user = User::getUser();
?>

<?php if ($user) {?>
<h1>Hello <?= $user->getUsername()?></h1>
<p>Welcome to site, you can change your data by <a href="/profile">link</a></p>


<?php } ?>


<h2>Test</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit id laborum nulla obcaecati quisquam quos, ratione sint. Eum excepturi nesciunt perferendis similique sit? Doloremque dolorum, sit. Harum iusto officia voluptatem?</p>