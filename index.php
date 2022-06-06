<?php

require ('../coin/controller/Transaction.php');
require ('../coin/controller/Block.php');
require ('../coin/controller/BlockChain.php');


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
$trx->setId(4);
$trx->setType($trx::EMISSION);
$trx->setTo('bob');
$trx->setAmount(10);
$trx->setSignature('96d2ab09ffe31192f7e8f6b7bba54ed4');

$block->addTransaction($trx);

$blockChain->addBlock(1, $block);


$block = new Block();
$block->setId(4);
$trx = new Transaction();
$trx->setId(5);
$trx->setType($trx::EMISSION);
$trx->setTo('bob');
$trx->setAmount(10);
$trx->setSignature('4ece284a88455b0ee71df50de5f4b1d0');
$block->addTransaction($trx);
$blockChain->addBlock(2, $block);

$block = new Block();
$block->setId(5);
$trx = new Transaction();
$trx->setId(6);
$trx->setType($trx::TRANSFER);
$trx->setFrom('karl');
$trx->setTo('bob');
$trx->setAmount(120);
$trx->setSignature('68429cc84d905496a27940d026365482');
$block->addTransaction($trx);
$blockChain->addBlock(1, $block);

//print_r($blockChain->getBlockChain());

echo 'bob: '. $blockChain->getBalance('bob'). "\n";
echo 'alisa: '. $blockChain->getBalance('alisa'). "\n";
echo 'karl: '. $blockChain->getBalance('karl'). "\n";