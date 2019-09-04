<?php

// $sInjectPageStyle = '<link rel="stylesheet" href="css/index.css">';
require_once __DIR__.'/top.php';
?>

<div class="page flex__center hidden" id="login">
    <div class="img">
        <img src="./media/ig_logo.png" alt="">
    </div>
    <div class="card item">
        <form id="frmLogin" class="form__sec">
            <input type="text" id="txtLoginEmail" name="txtLoginEmail" placeholder="E-mail">
            <input type="password" id="txtLoginPassword" name="txtLoginPassword" placeholder="Password">
            <button class="btn btn_main">Log in</button>
        </form>
    </div>
</div>
<div class="page flex__center" id="signup">
    <div class="img">
        <img src="./media/ig_logo.png" alt="">
    </div>
    <div class="card item">
        <form id="frmSignup" class="form__sec">
            <input type="text" id="txtSignupUsername" name="txtSignupUsername" placeholder="Username">
            <input type="text" id="txtSignupEmail" name="txtSignupEmail" placeholder="E-mail">
            <input type="text" id="txtSignupFullName" name="txtSignupFullName" placeholder="Full name">
            <input type="password" id="txtSignupPassword" name="txtSignupPassword" placeholder="Password">
            <input type="password" id="txtSignupPasswordRetyped" name="txtSignupPasswordRetyped" placeholder="Confirm Password">
            <div class="flex__grid">
                <div class="btn btn_sec mg_r__8 pager" data-to-open="login">Log in</div>
                <button class="btn btn_main mg_l__8">Sign up</button>
            </div>
        </form>
    </div>
</div>

<?php
$sScript = '<script src="js/index.js"></script>';
require_once __DIR__.'/bottom.php';

?>
