<?php


Class Transaction
{


    public const EMISSION = 0;
    public const TRANSFER = 1;

    private const min_name_len = 2;
    private const max_name_len = 10;
    private const signature_len = 32;
    private const min_amount = 0;

    private $id;
    private $type;
    private $from;
    private $to;
    private $amount;
    private $signature;

    public function __construct()
    {

    }

    /**
     * @return int
     * method returns transaction id
     */
    public function getId() :int
    {
        return $this->id;
    }

    /**
     * @return int
     * method returns transaction type
     */
    public function getType() :int
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getFrom() :?string
    {
        return $this->from;
    }

    /**
     * @return string
     * the method returns the name of the recipient of the transaction
     */
    public function getTo() :string
    {
        return $this->to;
    }

    /**
     * @return int
     * method returns transaction amount
     */
    public function getAmount() :int
    {
        return $this->amount;
    }

    /**
     * @return string
     * method returns transaction signature
     */
    public function getSignature() :string
    {
        return $this->signature;
    }

    /**
     * @param int $id
     * @return void
     * method sets transaction id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param int $type
     * @return void
     * @throws Exception
     * method sets transaction type
     * checks if the given type is within acceptable range
     * If the type is "emission", then the "from" property is set to null
     */
    public function setType(int $type)
    {
        if($type === self::EMISSION || $type === self::TRANSFER) {
            $this->type = $type;
            if($type === self::EMISSION) {
                $this->setFrom(null);
            }
        } else {
            throw new Exception('Property "type" is not in range.');
        }
    }

    /**
     * @param string|null $from
     * @return void
     * @throws Exception
     * method sets the name of the sender
     * checks that the entry is not null, shorter than 2 characters, or longer more than 10 characters
     * If the type is "emission", then the "from" property is set to null
     */
    public function setFrom(?string $from)
    {
        if($this->type === self::EMISSION) {
            $this->from = null;
        } else {
            if (!is_null($from) && strlen($from) >= self::min_name_len && strlen($from) <= self::max_name_len) {
                $this->from = $from;
            } else {
                throw new Exception('The "from" property is null, shorter than 2 characters, or longer than 10 characters.');
            }
        }
    }

    /**
     * @param string|null $to
     * @return void
     * @throws Exception
     * method sets the name of the recipient
     * checks that the entry is not null, shorter than 2 characters, or longer more than 10 characters
     * checks that the to != from
     */
    public function setTo(?string $to)
    {
        if (!is_null($to) && strlen($to) >= self::min_name_len && strlen($to) <= self::max_name_len && $to !== $this->from) {
            $this->to = $to;
        } else {
            throw new Exception('The "to" property is empty, or shorter than 2 characters, or longer than 10 characters, or equal to the "from" property.');
        }
    }

    /**
     * @param int $amount
     * @return void
     * @throws Exception
     * method sets amount of transaction
     * checks that the entry is not less than zero
     */
    public function setAmount(int $amount)
    {
        if ($amount >= self::min_amount) {
            $this->amount = $amount;
        } else {
            throw new Exception('Property "sum" is less than zero.');
        }
    }

    /**
     * @param $signature
     * @return void
     * @throws Exception
     * method sets signature of transaction
     * check that the signature length = 32
     */
    public function setSignature($signature)
    {
        if (strlen($signature) === self::signature_len) {
            $this->signature = $signature;
        } else {
            throw new Exception('The length of the "signature" property is not 32.');
        }
    }




}