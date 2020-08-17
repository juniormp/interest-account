<?php


namespace Chip\InterestAccount\Infrastructure\Repository\Payout;

trait Repo
{
    private function saveOnfile(array $payout)
    {
        file_put_contents("payout_repo.txt", serialize($payout));
    }

    private function readFromFile()
    {
        $string = file_get_contents("payout_repo.txt");

        return unserialize($string);
    }
}
