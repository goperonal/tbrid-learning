<?php
// fetch_live_chat.php
foreach ($live_chat->result() as $r) {
    echo "<span class='badge badge-pill bg-white text-wrap text-left px-3 py-2 mb-2 w-100'>";
    if ($r->user_id == $this->session->user_id) {
        echo "<span class='text-primary'>$r->nama_depan $r->nama_belakang</span>";
    } else {
        echo "$r->nama_depan $r->nama_belakang";
    }
    echo ": <span class='font-weight-normal'>$r->message</span></span>";
}