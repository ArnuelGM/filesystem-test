<?php

use Src\Storage;

require_once 'config/filesystem.php';

/* pjpre( realpath($_FILES['image']['name']) );
exit; */

Storage::upload("./../forbident/".$_FILES['image']['name'], $_FILES['image']);

