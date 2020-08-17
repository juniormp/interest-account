<?php

namespace Chip\InterestAccount\Infrastructure\Repository\Payout;

use Chip\InterestAccount\Domain\Payout\Payout;

/**
 * @property array $payouts
 */
class PayoutProvider implements PayoutRepository
{
    use Repo;

    private $payouts = [];

    public function save(Payout $payout): Payout
    {
        $this->getAll();
        array_push($this->payouts, $payout);
        $this->saveOnfile($this->payouts);

        return $payout;
    }

    public function getAll(): array
    {
        $r = $this->readFromFile();

        if ($r !== false) {
            $this->payouts = $r;
        }

        return $this->payouts;
    }

    public function getAllPayoutsByUserId(string $id): array
    {
        $payouts = $this->getAll();

        return array_filter($payouts, function ($payout) use ($id) {
            return $payout->getReferenceId() === $id;
        });
    }

    public function removePayoutByUserId(string $id)
    {
        $payouts = $this->getAllPayoutsByUserId($id);
        $this->payouts = array_values(array_diff_key($this->payouts, $payouts));
        $this->saveOnfile($this->payouts);
    }
}
