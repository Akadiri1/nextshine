<?php
// ADMC extension screens are for signed-in admins only. Anyone else is sent
// to this site's ADMC dashboard.
if (!isset($_SESSION['admin_id'])) {
    header("Location: https://" . (getenv('ADMC_USERNAME') ?: 'nextshine') . ".admc.dev");
    exit();
}
