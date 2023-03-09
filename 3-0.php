<?php
interface FileInterface
{
    public function rename(string $name): void;
}

interface FileWithOwnerInterface extends FileInterface
{
    public function changeOwner(
            string $user,
            string $group
    ): void;
}

class DropboxFile implements FileInterface
{
    public function rename($name) : void
    {

    }
}

/*
 * LocalFile is using the extended interface allowing the dropbox file class to not implement the ownership method which
 * is not possible with this file method
 */
class LocalFile implements FileWithOwnerInterface
{
    public function rename(string $name): void { }
    public function changeOwner(string $user, string $group): void { }
}
