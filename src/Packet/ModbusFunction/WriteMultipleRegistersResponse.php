<?php
namespace ModbusTcpClient\Packet\ModbusFunction;

use ModbusTcpClient\Packet\ModbusPacket;
use ModbusTcpClient\Packet\StartAddressResponse;
use ModbusTcpClient\Utils\Types;

/**
 * Response for Write Multiple Registers (FC=16)
 */
class WriteMultipleRegistersResponse extends StartAddressResponse
{
    /**
     * @var int
     */
    private $registersCount;

    public function __construct($rawData, $unitId = 0, $transactionId = null)
    {
        parent::__construct($rawData, $unitId, $transactionId);
        $this->registersCount = Types::parseUInt16(substr($rawData, 2, 2));
    }

    public function getFunctionCode()
    {
        return ModbusPacket::WRITE_MULTIPLE_REGISTERS;
    }

    /**
     * @return int
     */
    public function getRegistersCount()
    {
        return $this->registersCount;
    }

    protected function getLengthInternal()
    {
        return parent::getLengthInternal() + 2; //registersCount is 2 bytes
    }

    public function __toString()
    {
        return parent::__toString()
            . Types::toInt16($this->registersCount);
    }
}