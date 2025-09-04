<?php
$mailbox = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'expersystmskdm@gmail.com';
$password = 'flmj jsoi rcfe zmch'; // App password

$baseDirKDM = '/data/kdm_email';
$baseDirDCP = '/data/dcp_email';
$logKDM = '/data/logs/kdm_email.log';
$logDCP = '/data/logs/dcp_email.log';
$uidFile = __DIR__ . '/processed_uids.txt';
$keywords = ['KDM', 'Keys', 'Key Delivery Message'];

// Logging helper
function logMessage($path, $message) {
    file_put_contents($path, date("[Y-m-d H:i:s] ") . $message . PHP_EOL, FILE_APPEND);
}

// Ensure folders exist
foreach ([$baseDirKDM, $baseDirDCP] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0777, true);
}

// Load processed UIDs
$processedUIDs = file_exists($uidFile) ? explode("\n", trim(file_get_contents($uidFile))) : [];

$inbox = imap_open($mailbox, $username, $password) or die('Cannot connect: ' . imap_last_error());
$emails = imap_search($inbox, 'ALL');

if ($emails) {
    foreach ($emails as $email_number) {
        $uid = imap_uid($inbox, $email_number);
        if (in_array($uid, $processedUIDs)) continue;

        $overview = imap_fetch_overview($inbox, $email_number, 0)[0];
        $subject = $overview->subject ?? 'No Subject';
        $body = imap_body($inbox, $email_number);
        $foundKeyword = false;

        foreach ($keywords as $word) {
            if (stripos($subject, $word) !== false || stripos($body, $word) !== false) {
                $foundKeyword = true;
                break;
            }
        }

        // --- KDM Handling ---
        if ($foundKeyword) {
            $safeSubject = preg_replace('/[^\w\-\. ]+/', '_', $subject);
            $targetDir = rtrim($baseDirKDM, '/') . '/' . $safeSubject;
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            $structure = imap_fetchstructure($inbox, $email_number);
            if (isset($structure->parts)) {
                for ($i = 0; $i < count($structure->parts); $i++) {
                    $part = $structure->parts[$i];
                    $isAttachment = false;
                    $filename = '';

                    if ($part->ifdparameters) {
                        foreach ($part->dparameters as $param) {
                            if (strtolower($param->attribute) == 'filename') {
                                $isAttachment = true;
                                $filename = $param->value;
                            }
                        }
                    }

                    if ($isAttachment && preg_match('/\.(zip|xml)$/i', $filename)) {
                        $attachment = imap_fetchbody($inbox, $email_number, $i + 1);
                        if ($part->encoding == 3) $attachment = base64_decode($attachment);
                        elseif ($part->encoding == 4) $attachment = quoted_printable_decode($attachment);

                        $filePath = $targetDir . '/' . $filename;
                        file_put_contents($filePath, $attachment);
                        logMessage($logKDM, "Downloaded: $filename to $filePath");

                        // Extract ZIP and flatten
                        if (preg_match('/\.zip$/i', $filename)) {
                            $zip = new ZipArchive();
                            if ($zip->open($filePath) === TRUE) {
                                for ($z = 0; $z < $zip->numFiles; $z++) {
                                    $entry = $zip->getNameIndex($z);
                                    if (substr($entry, -1) === '/') continue;
                                    $contents = $zip->getFromIndex($z);
                                    $flatName = basename($entry);
                                    file_put_contents("$targetDir/$flatName", $contents);
                                    logMessage($logKDM, "Extracted: $flatName to $targetDir");
                                }
                                $zip->close();
                                unlink($filePath);
                            }
                        }
                    }
                }
            }
        }

        // --- Supplemental Link Handling ---
        if (preg_match_all('/https?:\/\/[^\s]+?\((.*?)\)\s*download/i', $body, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $url = trim(strtok($match[0], '('));
                $filename = basename(parse_url($url, PHP_URL_PATH));
                $targetDir = "$baseDirDCP/$filename";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                $savePath = "$targetDir/$filename";
                file_put_contents($savePath, file_get_contents($url));
                logMessage($logDCP, "Downloaded: $filename from $url");

                if (preg_match('/\.zip$/i', $filename)) {
                    $zip = new ZipArchive();
                    if ($zip->open($savePath) === TRUE) {
                        for ($z = 0; $z < $zip->numFiles; $z++) {
                            $entry = $zip->getNameIndex($z);
                            if (substr($entry, -1) === '/') continue;
                            $contents = $zip->getFromIndex($z);
                            $flatName = basename($entry);
                            file_put_contents("$targetDir/$flatName", $contents);
                            logMessage($logDCP, "Extracted: $flatName to $targetDir");
                        }
                        $zip->close();
                        unlink($savePath);
                    }
                }
            }
        }

        $processedUIDs[] = $uid;
    }

    file_put_contents($uidFile, implode("\n", $processedUIDs));
}

imap_close($inbox);
?>
