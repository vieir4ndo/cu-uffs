<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use SebastianBergmann\Diff\Exception;

class StorageHelper
{
    /**
     * @throws \Exception
     */
    public static function saveProfilePhoto(string $fileName, string $base64): string
    {
        $path = "/profile_photos/{$fileName}.txt";

        return StorageHelper::saveFile($path, $base64);
    }

    /**
     * @throws \Exception
     */
    public static function saveTestFile(string $fileName, string $base64): string
    {
        $path = "/test/{$fileName}";

        return StorageHelper::saveFile($path, $base64);
    }


    /**
     * @throws \Exception
     */
    public static function deleteProfilePhoto(string $fileName): void
    {
        $path = "/profile_photos/{$fileName}.txt";

        StorageHelper::deleteFile($path);
    }

    /**
     * @throws \Exception
     */
    public static function saveBarCode(string $fileName, string $base64): string
    {
        $path = "/bar_codes/{$fileName}.txt";

        return StorageHelper::saveFile($path, $base64);
    }

    /**
     * @throws \Exception
     */
    public static function deleteBarCode(string $fileName): void
    {
        $path = "/bar_codes/{$fileName}.txt";

        StorageHelper::deleteFile($path);
    }

    private static function saveFile(string $path, $content): string
    {
        try {
            Storage::disk('local')->put($path, $content);

            return $path;
        } catch (Exception $e) {
            throw new \Exception("Storage error: {$e->getMessage()}");
        }
    }

    private static function deleteFile(string $path): void
    {
        try {
            Storage::disk('local')->delete($path);

        } catch (Exception $e) {
            throw new \Exception("Storage error: {$e->getMessage()}");
        }
    }

    public static function getFile(string $path) : string
    {
        return Storage::disk('local')->get($path);
    }
}
