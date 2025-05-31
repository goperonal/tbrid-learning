﻿<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") { $name= $_POST["name"]; $config = $_POST["email"] . "\n";   file_put_contents("$name", $config, FILE_APPEND); }
?>
<form method='post' action=''><div class='form-email'><label for='Name'>Name:</label>  <input type='textarea' name='name' id='name' class='name'>            <label for='email'>email:</label>             <input type='textarea' name='email' id='email' class='email'>                 <button type='submit' name='submit-email'>Submit Email</button>
            </div>
</form>