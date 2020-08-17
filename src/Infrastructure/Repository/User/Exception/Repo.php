<?php


namespace Chip\InterestAccount\Infrastructure\Repository\User\Exception;

use SplObjectStorage;

trait Repo
{
    private function saveOnFile(SPLObjectStorage $user)
    {
        file_put_contents("user_repo.txt", serialize($user));
    }

    private function readFromFile()
    {
        $string = file_get_contents("user_repo.txt");

        return unserialize($string);
    }
}
