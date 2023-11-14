<?php


while (true) {

    $input = fopen("php://stdin", "r");
    $user_input = trim(fgets($input));

    if (strtolower($user_input) == 'bye') {
        break;
    }

    $password = $user_input;
    $complexity_count = 0;
    if (strlen($password) < 8) {
        print "Length < 8\n";
    } else if (preg_match("/^([A-Z]+)$|^([a-z]+)$|^([0-9]+)$|^([-!@#%^&*()_+|~=`{}\[\]:\";'<>?,.\\/\$\\\]+)$/", $password)) {
        print "Week Password\n";
    } else {
        print "OK\n";
    }

}
