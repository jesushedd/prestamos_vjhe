<?php 

    require_once  __DIR__ . '/../lib/loan/ScheduleGenerator.php';
    use Phelix\LoanAmortization\ScheduleGenerator;

    $interestCalculator = new ScheduleGenerator();
    
    $interestCalculator
                ->setPrincipal(50000)
                ->setInterestRate(12, "yearly", ScheduleGenerator::INTEREST_ON_REDUCING_BALANCE) // note the interest type
                ->setLoanDuration(1, "weeks")
                ->setRepayment(1,2, "days")
                ->setAmortization(ScheduleGenerator::EVEN_PRINCIPAL_REPAYMENT) // note the amortization type
                ->generate();
    
    print "\nTotal Interest = {$interestCalculator->interest} \n";
    print "Effective Interest Rate = {$interestCalculator->effective_interest_rate} \n";
    print "Total Repayable amount = {$interestCalculator->amount} \n";
    print "Number of Installments = {$interestCalculator->no_installments} \n";
    print "Repayment Frequency = {$interestCalculator->repayment_frequency} \n";
    
    print "Amortization Schedule: \n";
    
