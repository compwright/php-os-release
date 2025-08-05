<?php

declare(strict_types=1);

namespace CompWright\PhpOsRelease;

use SplFileInfo;
use SplFileObject;
use Throwable;

class OsReleaseReader
{
    public const STANDARD_FILE_PATHS = [
        '/etc/os-release',
        '/usr/lib/os-release',
    ];

    /**
     * @throws OsReleaseException
     */
    public function findFile(): SplFileInfo
    {
        foreach (self::STANDARD_FILE_PATHS as $file) {
            try {
                $fileInfo = new SplFileInfo($file);
                $this->assertFileIsReadable($fileInfo);
                return $fileInfo;
            } catch (OsReleaseException $e) {
                // intentionally ignore
            }
        }

        throw new OsReleaseException('File not found, or not readable, in any of the standard paths');
    }

    /**
     * @throws OsReleaseException
     */
    private function assertFileIsReadable(SplFileInfo $fileInfo): void
    {
        if (!$fileInfo->isFile()) {
            throw new OsReleaseException('File not found: ' . $fileInfo);
        }

        if (!$fileInfo->isReadable()) {
            throw new OsReleaseException('File not readable: ' . $fileInfo);
        }
    }

    /**
     * @throws OsReleaseException
     */
    public function readFile(string|SplFileInfo $fileInfo): OsRelease
    {
        if (is_string($fileInfo)) {
            $fileInfo = new SplFileInfo($fileInfo);
        }

        try {
            $file = $fileInfo->openFile('r');
        } catch (Throwable $e) {
            throw new OsReleaseException('Could not read file ' . $fileInfo, $e->getCode(), $e);
        }

        $file->setFlags(
            SplFileObject::READ_AHEAD
            | SplFileObject::DROP_NEW_LINE
            | SplFileObject::SKIP_EMPTY
        );

        $data = [];

        while (!$file->eof()) {
            $line = $file->fgets();

            // Skip comments
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            $parsed = str_getcsv($line, '=', '"', "\\");
            $key = $parsed[0] ?? '';
            $value = $parsed[1] ?? '';

            if (strlen($key) > 0 && strlen($value) > 0) {
                // The spec requires overwriting duplicate values
                // with the ones that appear last in the file
                $data[$key] = $value;
            }
        }

        return new OsRelease($data);
    }

    /**
     * @throws OsReleaseException
     */
    public function __invoke(): OsRelease
    {
        return $this->readFile($this->findFile());
    }
}
