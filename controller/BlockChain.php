<?php

class BlockChain
{

    private const min_name_len = 2;
    private const max_name_len = 10;

    private $block_tree;

    public function __construct()
    {
        $this->block_tree = [];
    }

    /**
     * @return array
     * method returns tree of blocks
     */
    public function getBlockChain(): array
    {
        return $this->block_tree;
    }

    /**
     * @param int|null $parentBlockId
     * @param Block $block
     * @return void
     * adds a block to the "Block Tree"
     * validates the block using a validateBlock method
     * property $is_valid_block for checking
     * property $check_parent for checking exist parentBlockId
     * if parentBlockId not exist - property $is_valid_block = false
     * if $parentBlockId = null and "block tree" not empty - property $is_valid_block = false
     * if property $is_valid_block = true - adds a block to the "Block Tree"
     */
    public function addBlock(?int $parentBlockId, Block $block)
    {
        $is_valid_block = $this->validateBlock($block);

        if (is_null($parentBlockId)) {
            if($this->block_tree) {
                $is_valid_block = false;
            }
        } else {
            $check_parent = false;
            foreach ($this->block_tree as $block_obj) {
                if ($parentBlockId === $block_obj->getId()) {
                    $check_parent = true;
                }
            }
            if (!$check_parent) {
                $is_valid_block = false;
            }
        }

        if ($is_valid_block) {
            $this->block_tree[] = $block;
        }
    }

    /**
     * @param Block $block
     * @return bool
     * validate passed block
     * checks if there is at least 1 transaction in the block
     * checks if a block with the same ID exists in the "Block Tree"
     */
    public function validateBlock(Block $block): bool
    {
        if (!$block->getTransactions()) {
            return false;
        }

        foreach ($this->block_tree as $block_obj) {
            if ($block->getId() === $block_obj->getId()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $account
     * @return int
     * @throws Exception
     * if property "account" = property "from" - takes money off the balance
     * if property "account" = property "to" - pays money to the balance
     */
    public function getBalance($account): int
    {
        if (is_null($account) && strlen($account) >= self::min_name_len && strlen($account) <= self::max_name_len) {
            throw new Exception('The "account" property is null, shorter than 2 characters, or longer than 10 characters.');
        }
        $balance = 0;
        foreach ($this->block_tree as $block_chain) {
            foreach ($block_chain->getTransactions() as $transaction) {
                if ($transaction::EMISSION === $transaction->getType()) {
                    if ($transaction->getTo() === $account) {
                        $balance += $transaction->getAmount();
                    }
                } else if ($transaction::TRANSFER === $transaction->getType()) {
                    if ($transaction->getTo() === $account) {
                        $balance += $transaction->getAmount();
                    } else if ($transaction->getFrom() === $account) {
                        $balance -= $transaction->getAmount();
                    }
                }
            }
        }

        return $balance;
    }
}