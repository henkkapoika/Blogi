<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION["username"])) {
    session_unset();
    session_destroy();
    echo '
<ul id="header-login-status" hx-swap-oob="outerHTML">
    <li>
        <button id="open-login" class="header-btn">Login</button>
        <button id="open-register" class="header-btn">Register</button>
    </li>
</ul>
';
}

//<a class="header-options" href="user.php">User Page</a>
//<a class="header-options" href="http://">About Us</a>

//include "user_state.php";
