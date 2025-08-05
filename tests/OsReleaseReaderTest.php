<?php

declare(strict_types=1);

namespace CompWright\PhpOsRelease;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class OsReleaseReaderTest extends TestCase
{
    #[TestWith([__DIR__ . '/os-release.amazonlinux', 16])]
    #[TestWith([__DIR__ . '/os-release.debian', 9])]
    #[TestWith([__DIR__ . '/os-release.fedora', 19])]
    #[TestWith([__DIR__ . '/os-release.ubuntu', 13])]
    public function testRead(string $file, int $expectedPropertyCount): void
    {
        $reader = new OsReleaseReader();
        $os = $reader->readFile($file);
        $this->assertCount($expectedPropertyCount, $os);
    }

    public function testFileNotFound(): void
    {
        $reader = new OsReleaseReader();
        $this->expectException(OsReleaseException::class);
        $reader->readFile(__DIR__ . '/os-release.notfound');
    }

    public function testReadPropertyAccess(): void
    {
        $reader = new OsReleaseReader();
        $os = $reader->readFile(__DIR__ . '/os-release.fedora');
        $this->assertSame('fedora', $os->id);
        $this->assertSame('Fedora 32 (Workstation Edition)', $os->prettyName);
        $this->assertSame('32', $os->versionId);
        // @phpstan-ignore-next-line property.notFound
        $this->assertSame('Fedora', $os->redhatBugzillaProduct);
        // @phpstan-ignore-next-line property.notFound
        $this->assertEmpty($os->foo);
    }

    public function testReadArrayAccess(): void
    {
        $reader = new OsReleaseReader();
        $os = $reader->readFile(__DIR__ . '/os-release.fedora');
        $this->assertSame('fedora', $os['ID']);
        $this->assertSame('Fedora 32 (Workstation Edition)', $os['PRETTY_NAME']);
        $this->assertSame('32', $os['VERSION_ID']);
        $this->assertSame('Fedora', $os['REDHAT_BUGZILLA_PRODUCT']);
        $this->assertEmpty($os['FOO']);
    }

    public function testJsonSerialize(): void
    {
        $reader = new OsReleaseReader();
        $os = $reader->readFile(__DIR__ . '/os-release.debian');
        $json = json_encode($os);
        $this->assertSame(
            '{"PRETTY_NAME":"Debian GNU\/Linux 12 (bookworm)","NAME":"Debian GNU\/Linux","VERSION_ID":"12","VERSION":"12 (bookworm)","VERSION_CODENAME":"bookworm","ID":"debian","HOME_URL":"https:\/\/www.debian.org\/","SUPPORT_URL":"https:\/\/www.debian.org\/support","BUG_REPORT_URL":"https:\/\/bugs.debian.org\/"}',
            $json
        );
    }
}
