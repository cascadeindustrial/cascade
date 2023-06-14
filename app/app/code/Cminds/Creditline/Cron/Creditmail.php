<?php
namespace Cminds\Creditline\Cron;

use Psr\Log\LoggerInterface;
use Cminds\Creditline\Helper\Email;

class Creditmail{
  protected $logger;
  protected $email;
  
  public function __construct(
    LoggerInterface $logger,
    Email $email
  ){
    $this->logger = $logger;
    $this->email = $email;
  }

  public function checkCreditTerm() {
    $this->logger->info('Cron Crdit Term Start');
    $this->email->paymentReminder();
    $this->logger->info('Cron Crdit Term End');
  }

  public function invoiceCron() {
    $this->logger->info('Cron Invoice Start');
    $this->email->sendInvoiceReminder();
    $this->logger->info('Cron Invoice End');
  }
}
