<?php
/*
 * Copyright 2013 Sean Proctor
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

if (!defined('IN_PHPC')) {
    die("Hacking attempt");
}

// https://gist.github.com/29eafb3b3688fa49546bc14ec434a77c.git
include '../../importerics/iCal.php';

function importics()
{
    global $vars, $phpcdb, $phpcid, $phpc_script;

    if (!is_admin()) {
        permission_error(__('Need to be admin'));
        exit;
    }

    $form_page = "$phpc_script?action=admin#phpc-admin-import-ics";
    return message_redirect(
        "name of uploaded file: {$_FILES['icsfile']['name']}"
            . "<br>"
            . "temp file: {$_FILES['icsfile']['tmp_name']}",
        $form_page
    );
}
