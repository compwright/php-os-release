<?php

declare(strict_types=1);

namespace CompWright\PhpOsRelease;

use ArrayAccess;
use Countable;
use JsonSerializable;

/**
 * @implements ArrayAccess<string, string>
 * @property string $ansiColor A suggested presentation color when showing the OS name on the console
 * @property string $architecture A string that specifies which CPU architecture the userspace binaries require
 * @property string $buildId A string uniquely identifying the system image originally used as the installation base
 * @property string $bugReportUrl The main bug reporting page for the operating system
 * @property string $confextLevel Semantically the same as SYSEXT_LEVEL= but for confext images
 * @property string $confextScope Semantically the same as SYSEXT_SCOPE= but for confext images
 * @property string $cpeName A CPE name for the operating system, in URI binding syntax, following the Common Platform Enumeration Specification as proposed by the NIST
 * @property string $defaultHostname A string specifying the hostname if hostname(5) is not present and no other configuration source specifies the hostname
 * @property string $documentationUrl
 * @property string $experiment A human-presentable description of what makes this build of the OS experimental
 * @property string $experimentUrl The main informational page about what makes the current OS build experimental, where users can learn more about the experiment's status and potentially leave feedback
 * @property string $homeUrl The homepage of the operating system
 * @property string $id A lower-case string (no spaces or other characters outside of 0–9, a–z, ".", "_" and "-") identifying the operating system, excluding any version information and suitable for processing by scripts or usage in generated filenames
 * @property string $idLike A space-separated list of operating system identifiers in the same syntax as the ID= setting
 * @property string $imageId A lower-case string (no spaces or other characters outside of 0–9, a–z, ".", "_" and "-"), identifying a specific image of the operating system
 * @property string $imageVersion A lower-case string (mostly numeric, no spaces or other characters outside of 0–9, a–z, ".", "_" and "-") identifying the OS image version
 * @property string $logo A string, specifying the name of an icon as defined by freedesktop.org Icon Theme Specification
 * @property string $name A string identifying the operating system, without a version component, and suitable for presentation to the user
 * @property string $platformId
 * @property string $portablePrefixes Takes a space-separated list of one or more valid prefix match strings for the Portable Services logic
 * @property string $prettyName A pretty operating system name in a format suitable for presentation to the user. May or may not contain a release code name or OS version of some kind, as suitable
 * @property string $privacyPolicyUrl The main privacy policy page for the operating system
 * @property string $releaseType A lower-case string (no spaces or other characters outside of 0-9, a-z, ".", "_", and "-"), describing what kind of release this version of the OS is
 * @property string $supportEnd The date at which support for this version of the OS ends
 * @property string $supportUrl The main support page for the operating system
 * @property string $sysextLevel A lower-case string (mostly numeric, no spaces or other characters outside of 0–9, a–z, ".", "_" and "-") identifying the operating system extensions support level, to indicate which extension images are supported
 * @property string $sysextScope Takes a space-separated list of one or more of the strings "system", "initrd" and "portable"
 * @property string $variant A string identifying a specific variant or edition of the operating system suitable for presentation to the user. This field may be used to inform the user that the configuration of this system is subject to a specific divergent set of rules or default configuration settings
 * @property string $variantId A lower-case string (no spaces or other characters outside of 0–9, a–z, ".", "_" and "-"), identifying a specific variant or edition of the operating system. This may be interpreted by other packages in order to determine a divergent default configuration
 * @property string $vendorName The name of the OS vendor
 * @property string $vendorUrl The homepage of the OS vendor
 * @property string $version A string identifying the operating system version, excluding any OS name information, possibly including a release code name, and suitable for presentation to the user
 * @property string $versionCodename A lower-case string (no spaces or other characters outside of 0–9, a–z, ".", "_" and "-") identifying the operating system release code name, excluding any OS name information or release version, and suitable for processing by scripts or usage in generated filenames
 * @property string $versionId A lower-case string (mostly numeric, no spaces or other characters outside of 0–9, a–z, ".", "_" and "-") identifying the operating system version, excluding any OS name information or release code name, and suitable for processing by scripts or usage in generated filenames
 */
class OsRelease implements JsonSerializable, ArrayAccess, Countable
{
    /**
     * @param array<string, string> $data
     */
    public function __construct(private array $data)
    {
    }

    public function count(): int
    {
        return count($this->data);
    }

    private function camelToUnderscore(string $camelCase): string
    { 
        return strtoupper(
            preg_replace(
                '/(?<=\\w)(?=[A-Z])|(?<=[a-z])(?=[0-9])/',
                '_',
                $camelCase
            ) ?? ''
        ); 
    } 

    public function __isset(string $name): bool
    {
        $key = $this->camelToUnderscore($name);
        return array_key_exists($key, $this->data);
    }

    public function __get(string $name): string
    {
        $key = $this->camelToUnderscore($name);
        return $this->data[$key] ?? '';
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet(mixed $offset): string
    {
        return $this->data[$offset] ?? '';
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new OsReleaseException('Read-only violation');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new OsReleaseException('Read-only violation');
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
