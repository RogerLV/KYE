<?php

define('ASSET_DIR', env('ASSET_DIR'));
define('STORAGE_PATH', env('STORAGE_PATH'));

define('STORAGE_PATH_STAFF_LIST', STORAGE_PATH.'StaffList');
define('STORAGE_PATH_OCCUPATIONAL_RISK', STORAGE_PATH.'OccupationalRisk');

define('ROLE_ID_SYS_ADMIN', 4);
define('ROLE_ID_APP_ADMIN', 3);
define('ROLE_ID_CHECKER', 2);
define('ROLE_ID_MAKER', 1);

// Error Messages
define('ERROR_MESSAGE_NOT_AUTHORIZED', 'You are not authorized to view the page.');
define('ERROR_MESSAGE_DATA_ERROR', 'Data Error.');
define('ERROR_MESSAGE_ENTRY_IS_PENDING', 'Operation failed! The entry already has operation pending to check. Please edit after confirmation.');

