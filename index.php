<?php

/*
|--------------------------------------------------------------------------
| XAMPP / shared-hosting front controller
|--------------------------------------------------------------------------
|
| The real Laravel entry point lives in public/index.php. When the
| project is dropped into XAMPP's htdocs/<name>/ folder (or any shared
| host that serves the project root rather than the public/ folder),
| this file forwards every request into public/index.php so the app
| still boots correctly without configuring a virtual host.
|
| For a proper deployment, point Apache's DocumentRoot at the public/
| directory and this file is bypassed entirely.
*/

require __DIR__.'/public/index.php';
