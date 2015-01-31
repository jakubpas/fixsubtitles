<?php

namespace JP;

class Subtitles
{
    private static $replacementArray = array(
        'ê' => 'ę',
        '_1_' => 'ó',
        '¹' => 'ą',
        'œ' => 'ś',
        '³' => 'ł',
        '¿' => 'ż',
        'Ÿ' => 'ź',
        'æ' => 'ć',
        '_2_' => 'ń',
        'ñ' => 'ń',
        '£' => 'Ł',
        '_3_' => 'Ę',
        '_4_' => 'Ó',
        '_5_' => 'Ą',
        'Œ' => 'Ś',
        '_6_' => 'Ł',
        '<8f>' => 'Ż',
        '¯' => 'Ź',
        'Æ' => 'Ć',
        '_8_' => 'Ń',
    );

    public static function fix()
    {
        $directories = $GLOBALS['argv'];
        if (count($directories) > 1) {
            array_shift($directories);
            foreach ($directories as $directory) {
                self::fixSubtitles($directory);
            }
        } else {
            echo('Usage: ' . $directories[0] . ' dir1 dir2...' . PHP_EOL);
        }
    }

    private static function fixSubtitles($directory)
    {
        if ($handle = opendir($directory)) {
            while ($fileName = readdir($handle)) {
                if ($fileName == '.' || $fileName == '..') {
                    continue;
                }
                $fileName = $directory . '/' . $fileName;
                if (is_dir($fileName)) {
                    self::fixSubtitles($fileName);
                } elseif (preg_match('/^.*\.srt$/', $fileName)) {
                    self::replaceCharacters($fileName);
                }
            }
            closedir($handle);
        }
    }

    private static function replaceCharacters($filename)
    {
        $file = file_get_contents($filename);
        foreach (self::$replacementArray as $from => $to) {
            $file = preg_replace('/' . $from . '/m', $to, $file);
        }
        file_put_contents($filename, $file);
    }
}
