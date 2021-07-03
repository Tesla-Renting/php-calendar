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
include "../importerics/iCal.php";

function importics()
{
    global $vars, $phpcdb, $phpcid, $phpc_script, $phpc_user;;

    if (!is_admin()) {
        permission_error(__('Need to be admin'));
        exit;
    }

    $eventcountsuccess = 0;
    $eventcountfail = 0;
    $iCal = new iCal($_FILES['icsfile']['tmp_name']);
    $events = $iCal->events();
    foreach ($events as $event) {

        $startDateTime = $event->dateStart;
        $endDateTime = $event->dateEnd;
        
        $start_dt = DateTime::createFromFormat('Y-m-d H:i:s', $startDateTime);
        $end_dt = DateTime::createFromFormat('Y-m-d H:i:s', $endDateTime);
        if ($start_dt == false) {
            $eventcountfail++;
            continue;
        }
        if ($end_dt == false) {
            $eventcountfail++;
            continue;
        }
        
        $start_ts = $start_dt->getTimestamp();
        $end_ts = $end_dt->getTimestamp();
        $time_type = 0;
        
        if ($start_ts > $end_ts) {
            $eventcountfail++;
            continue;
        }
        
        $eid = $phpcdb->create_event(
            $phpcid,
            $phpc_user->get_uid(),
            $event->title(),
            $event->description(),
            false
        );

        $phpcdb->create_occurrence($eid, $time_type, $start_ts, $end_ts);
        $eventcountsuccess++;
    }

    $form_page = "$phpc_script?action=admin#phpc-admin-import-ics";
    return message_redirect(
        "{$eventcountsuccess} events imported successfully. {$eventcountfail} events not imported.",
        $form_page
    );
}
