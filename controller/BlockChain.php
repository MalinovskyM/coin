<?php

require ('../coin/controller/TreeBlock.php');

class BlockChain
{
    private const min_name_len = 2;
    private const max_name_len = 10;

    private $root;
    private $height;
    private $result;

    public function __construct()
    {
        $this->root = null;
        $this->height = 0;
        $this->result = 0;
    }

    /**
     * @return TreeBlock method returns tree of blocks
     */
    public function getBlockChain() : TreeBlock
    {
        return $this->root;
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
     * @throws Exception
     */
    public function addBlock(?int $parentBlockId, Block $block)
    {
        $is_valid_block = $this->validateBlock($block);
        if ($this->root !== null) {
            foreach ($block->getTransactions() as $transaction) {
                if ($transaction::TRANSFER === $transaction->getType()) {
                    if ($transaction->getAmount() > $this->getBalance($transaction->getFrom())) {
                        $is_valid_block = false;
                    }
                }
            }
        }
        if ($is_valid_block) {
            if ($parentBlockId === null && $this->root === null) {
                $this->root = new TreeBlock($block);
            } else if ($parentBlockId !== null && $this->root !== null) {
                $this->find_parent($this->root, $parentBlockId, $block);
            }
        }
    }

    /**
     * @param TreeBlock $node
     * @param int $parentBlockId
     * @param Block $block
     * @return void
     * goes up the tree and looks for a block whose id is equal to the parentBlockId
     */
    private function find_parent(TreeBlock $node, int $parentBlockId, Block $block) {
        if ($node->data->getId() === $parentBlockId) {
            $node->child[] = new TreeBlock($block);
        } else {
            if ($node->child !== null) {
                foreach ($node->child as $child) {
                    $this->find_parent($child, $parentBlockId, $block);
                }
            }
        }
    }

    /**
     * @param Block $block
     * @return bool
     * validate passed block
     * checks if there is at least 1 transaction in the block
     * checks if a block with the same ID exists in the "Block Tree" using the checkSameId function
     */
    public function validateBlock(Block $block): bool
    {
        if (!$block->getTransactions()) {
            return false;
        }

        if ($this->root !== null) {
            return $this->checkSameId($this->root, $block->getId());
        } else {
            return true;
        }
    }

    /**
     * @param TreeBlock $node
     * @param int $id id new block
     * @return bool
     * the function goes up the tree trying to find the same id
     */
    private function checkSameId(TreeBlock $node, int $id): bool
    {
        if ($node->data->getId() === $id) {
            return false;
        } else {
            if ($node->child !== null) {
                foreach ($node->child as $child) {
                    $this->checkSameId($child, $id);
                }
            }
        }
        return true;
    }

    /**
     * @param string|null $account
     * @return int
     * @throws Exception
     * we check the account for the number of characters
     * set height and sum constants to zero
     * and then run the findPathSum function
     */
    public function getBalance(?string $account): int
    {
        if (is_null($account) && strlen($account) >= self::min_name_len && strlen($account) <= self::max_name_len) {
            throw new Exception('The "account" property is null, shorter than 2 characters, or longer than 10 characters.');
        }
        $this->result = 0;
        $this->height = 0;
        $this->findPathSum($this->root, 0, 0, $account);

        return $this->result;
    }

    /**
     * @param TreeBlock $node class "block tree"
     * @param int $sum variable that is needed to calculate the sum
     * @param int $level variable that is needed to calculate the height
     * @param string $account name of account
     * @param bool $account_exist to check if the account exists
     * @return void
     * for each block, we look at whether there are transactions on the account and calculate the amount
     * in the last element, we set the height and sum to constants so that we can compare them when we reach a dead end in the next iterations
     */
    public function findPathSum(TreeBlock $node, int $sum, int $level, string $account, bool $account_exist = false)
    {
        $balance = 0;
        foreach ($node->data->getTransactions() as $transaction) {
            if ($transaction::EMISSION === $transaction->getType()) {
                if ($transaction->getTo() === $account) {
                    $balance += $transaction->getAmount();
                    $account_exist = true;
                }
            } else if ($transaction::TRANSFER === $transaction->getType()) {
                if ($transaction->getTo() === $account) {
                    $balance += $transaction->getAmount();
                    $account_exist = true;
                } else if ($transaction->getFrom() === $account) {
                    $balance -= $transaction->getAmount();
                    $account_exist = true;
                }
            }
        }

        if ($node->child !== null) {
            foreach ($node->child as $child) {
                $this->findPathSum($child, $sum + $balance, $level + 1, $account, $account_exist);
            }
        }
        if ($node->child === NULL && $account_exist === true) {
            if ($this->height < $level) {
                $this->height = $level;
                $this->result = $sum + $balance;
            } else if ($this->height === $level && $this->result < $sum + $balance) {
                $this->result = $sum + $balance;
            }
        }
    }
}