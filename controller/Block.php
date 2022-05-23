<?php

class Block
{
    private const max_list_len = 10;

    private $id;
    private $transactions;

    public function __construct()
    {
        $this->transactions = [];
    }

    /**
     * @return int
     * method returns block id
     */
    public function getId() :int
    {
        return $this->id;
    }

    /**
     * @return array
     * method returns list of transactions
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param int $id
     * @return void
     * method sets block id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param Transaction $transaction
     * @return void
     * method adds the transaction to list
     * validates the block using a validateBlock method
     * property 'is_valid_transaction' for checking
     * if the number of existing transactions is less than 10 - property $is_valid_transaction = true
     * if there is a transaction with transaction.id in the list - property $is_valid_transaction = false
     * if property $is_valid_transaction = true - adds the transaction to list
     */
    public function addTransaction(Transaction $transaction)
    {
        $is_valid_transaction = $this->validateTransaction($transaction);
        foreach ($this->transactions as $transaction_obj) {
            if ($transaction_obj->getId() === $transaction->getId()) {
                $is_valid_transaction = false;
                break;
            }
        }

        if (count($this->transactions) >= self::max_list_len) {
            $is_valid_transaction = false;
        }

        if ($is_valid_transaction) {
            $this->transactions[] = $transaction;
        }

    }

    /**
     * @param Transaction $transaction
     * @return bool
     * validate passed transaction
     * checks if transaction signature is valid
     * encrypts transaction data (id:type:from:to:amount) to md5 and compares
     */
    private function validateTransaction(Transaction $transaction) : bool
    {
        $properties_string = $transaction->getId().
            ':' .$transaction->getType().
            ':' .$transaction->getFrom().
            ':' .$transaction->getTo().
            ':' .$transaction->getAmount()
        ;
        $is_valid_signature = md5($properties_string);
        return $is_valid_signature === $transaction->getSignature();
    }
}