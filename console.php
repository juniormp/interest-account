<?php

use Chip\InterestAccount\Application\ClosePayouts;
use Chip\InterestAccount\Application\Command\DepositFundsCommand;
use Chip\InterestAccount\Application\Command\ListTransactionsCommand;
use Chip\InterestAccount\Application\Command\OpenAccountCommand;
use Chip\InterestAccount\Application\DepositFunds;
use Chip\InterestAccount\Application\ListTransactions;
use Chip\InterestAccount\Application\OpenAccount;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\PayoutSupportFactory;
use Chip\InterestAccount\Tests\Support\Repository\PayoutSupportRepository;
use Chip\InterestAccount\Tests\Support\Repository\UserSupportRepository;

$container = require __DIR__ . '/app/bootstrap.php';
$openAccount = $container->get(OpenAccount::class);
$depositFunds = $container->get(DepositFunds::class);
$listTransactions = $container->get(ListTransactions::class);
$closePayouts = $container->get(ClosePayouts::class);


# Create user account --------------------------------------------------------------------------------------------------
$id = UUID::v4();
$user = $openAccount->execute(new OpenAccountCommand($id));
echo "USER" . PHP_EOL;
#print_r($user);
echo PHP_EOL . PHP_EOL . PHP_EOL;



# Deposit Funds --------------------------------------------------------------------------------------------------------
$depositFunds->execute(new DepositFundsCommand($id, 200.0));
$depositFunds->execute(new DepositFundsCommand($id, 500.0));



# List all transactions (Deposits made in the previously script) -------------------------------------------------------
$transactions = $listTransactions->execute(new ListTransactionsCommand($id));
echo "TRANSACTIONS" . PHP_EOL;
#print_r($transactions);
echo PHP_EOL . PHP_EOL . PHP_EOL;



# Interest Calculation and Payouts deposit -----------------------------------------------------------------------------
## Create user 1
$id1 = UUID::v4();
$user = $openAccount->execute(new OpenAccountCommand($id1));

## Set pending Payouts
### Pending payout 1
$pendingAmount = MoneySupportFactory::getInstance()::withAmount(0.50)::build();
$payout = PayoutSupportFactory::getInstance()::withReferenceId($id1)::withAmount($pendingAmount)::build();
PayoutSupportRepository::persistPayout($payout);
### Pending payout 2
$pendingAmount = MoneySupportFactory::getInstance()::withAmount(0.50)::build();
$payout = PayoutSupportFactory::getInstance()::withReferenceId($id1)::withAmount($pendingAmount)::build();
PayoutSupportRepository::persistPayout($payout);

## Create user 2
$id2 = UUID::v4();
$user = $openAccount->execute(new OpenAccountCommand($id2));

## Set pending Payouts
### Pending payout 1
$pendingAmount = MoneySupportFactory::getInstance()::withAmount(0.50)::build();
$payout = PayoutSupportFactory::getInstance()::withReferenceId($id2)::withAmount($pendingAmount)::build();
PayoutSupportRepository::persistPayout($payout);
### Pending payout 2
$pendingAmount = MoneySupportFactory::getInstance()::withAmount(0.50)::build();
$payout = PayoutSupportFactory::getInstance()::withReferenceId($id2)::withAmount($pendingAmount)::build();
PayoutSupportRepository::persistPayout($payout);


# List all pending payouts
$payouts = PayoutSupportRepository::getAll();
echo "PENDING PAYOUTS" . PHP_EOL;
#print_r($payouts);
echo PHP_EOL . PHP_EOL . PHP_EOL;



# Deposit pending payouts to user accounts -----------------------------------------------------------------------------
$closePayouts->execute();

$transactionsFromUser1 = $listTransactions->execute(new ListTransactionsCommand($id1));
echo "TRANSACTIONS USER 1" . PHP_EOL;
print_r($transactionsFromUser1);
echo PHP_EOL . PHP_EOL . PHP_EOL;

$transactionsFromUser2 = $listTransactions->execute(new ListTransactionsCommand($id2));
echo "TRANSACTIONS USER 1" . PHP_EOL;
print_r($transactionsFromUser2);
echo PHP_EOL . PHP_EOL . PHP_EOL;



# Clean data
UserSupportRepository::cleanUserData();
PayoutSupportRepository::cleanPayoutData();
