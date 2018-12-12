<?php
/**
 * Created by PhpStorm.
 * User: irslambouf
 * Date: 2018-12-12
 * Time: 4:00 PM
 */

use \ForceUTF8\Encoding;

require_once("Dependencies\Encoding.php");

if (!isset($argv) || sizeof($argv) < 3) {
    echo "Not enough command line arguments passed\n";
    echo "Usage: php UTF8DecodeEncode.php [in filename] [out filename]\n";
    echo "Exiting...";
    return;
}

$in_filename = $argv[1];
$out_filename = $argv[2];

if (!file_exists($in_filename)) {
    echo "File: ";
    echo $in_filename;
    echo ", Does not exist.\nExiting...";
    return;
}

if ($out_filename === $in_filename) {
    echo "In and out filenames provided are the same, exiting...";
    return;
}

if (file_exists($out_filename)) {
    do {
        echo "File: ";
        echo $out_filename;
        echo ", exists already. Do you want to overrider? (Y/N)";
        $override_prompt_response = readline();
    } while (!(strcasecmp(substr($override_prompt_response, 0, 1), "Y") == 0 || strcasecmp(substr($override_prompt_response, 0, 1), "N") == 0));

    if (strcasecmp(substr($override_prompt_response, 0, 1), 'N') == 0) {
        echo "File will not be overwritten, exiting...";
        return;
    }
}

echo "[+]Opening input file - ";
echo $in_filename;
echo "\n";

$in_handle = fopen($in_filename, "r");

if ($in_handle != false) {
    echo "[+]Opening output file - ";
    echo $out_filename;
    echo "\n";

    $out_handle = fopen($out_filename, "w");

    if ($out_handle == false) {
        echo "Error opening the output file, exiting...";
        return;
    }

    echo "[+]Starting conversion\n";

    $line_count = 0;
    while (($line = fgets($in_handle)) !== false) {
        $line_count++;

        echo "Original: ";
        echo $line;
        echo "\n";

        $proper_utf8 = Encoding::toUTF8($line);
        $test_utf8 = Encoding::fixUTF8($proper_utf8);
        $test_utf8_2 = Encoding::fixUTF8($line);

        echo "Re-encoded: ";
        echo $proper_utf8;
        echo "\n";

        fwrite($out_handle, $proper_utf8);
        fwrite($out_handle, "\n");
        fwrite($out_handle, $test_utf8);
        fwrite($out_handle, "\n");
        fwrite($out_handle, $test_utf8_2);
    }

    if (!fclose($in_handle)){
        echo "Failed to close handle on input file, ";
        echo $in_filename;
        echo "\n";
    }

    if (!fclose($out_handle)){
        echo "Failed to close handle on output file, ";
        echo $out_filename;
        echo "\n";
    }

    echo "Processed ";
    echo $line_count;
    echo " lines, Exiting...";
    return;

} else {
    echo "Error while opening file (";
    echo $in_filename;
    echo "), exiting...";
    return;
}
