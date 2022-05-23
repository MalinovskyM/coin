<?php

require ('../testcoin/controller/Transaction.php');
require ('../testcoin/controller/Block.php');
require ('../testcoin/controller/BlockChain.php');


$trx = new Transaction();
$trx->setId(1);
$trx->setType($trx::EMISSION);
$trx->setTo('bob');
$trx->setAmount(100);
$trx->setSignature('4b25376e6237e98b06f2cbf07eaa1759');


$block = new Block();
$block->setId(1);
$block->addTransaction($trx);

$trx = new Transaction();
$trx->setId(2);
$trx->setType($trx::TRANSFER);
$trx->setFrom('bob');
$trx->setTo('alisa');
$trx->setAmount(50);
$trx->setSignature('09280bb9e58b56ce2764fdd5b87eecc1');

$block->addTransaction($trx);

$blockChain = new BlockChain();
$blockChain->addBlock(null, $block);


$block = new Block();
$block->setId(2);

$trx = new Transaction();
$trx->setId(3);
$trx->setType($trx::EMISSION);
$trx->setTo('bob');
$trx->setAmount(50);
$trx->setSignature('5b2eb691aa0083d470d866693c18ea6a');

$block->addTransaction($trx);

$blockChain->addBlock(1, $block);

$block = new Block();
$block->setId(3);

$trx = new Transaction();
$trx->setId(3);
$trx->setType($trx::EMISSION);
$trx->setTo('bob');
$trx->setAmount(10);
$trx->setSignature('68993bb24e893f2a4498236b5dd0acb0');

$block->addTransaction($trx);

$blockChain->addBlock(1, $block);


$block = new Block();
$block->setId(4);
$trx = new Transaction();
$trx->setId(4);
$trx->setType($trx::EMISSION);
$trx->setTo('bob');
$trx->setAmount(10);
$trx->setSignature('96d2ab09ffe31192f7e8f6b7bba54ed4');
$block->addTransaction($trx);
$blockChain->addBlock(2, $block);

//print_r($blockChain->getBlockChain());

print_r($blockChain->getBalance('bob'));
