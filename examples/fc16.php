<?php

use ModbusTcpClient\Network\BinaryStreamConnection;
use ModbusTcpClient\Packet\ModbusFunction\WriteMultipleRegistersRequest;
use ModbusTcpClient\Packet\ModbusFunction\WriteMultipleRegistersResponse;
use ModbusTcpClient\Packet\ResponseFactory;
use ModbusTcpClient\Utils\Types;

require __DIR__ . '/../vendor/autoload.php';

//已台達為範例 設定人機介面 Port Host
$connection = BinaryStreamConnection::getBuilder()
    ->setPort(502)
    ->setHost('172.16.190.51')
    ->build();

//起始記憶體位置
$startAddress = 10;

//一共設定傳5筆參數 分別對應人機介面 $10 $11 $12 $13 $14
$registers = [
    Types::toInt16(1000),
    Types::toInt16(1000),
    Types::toInt16(true),
    Types::toInt16(true),
    Types::toInt16(true),
];
$packet = new WriteMultipleRegistersRequest($startAddress, $registers);
echo '發送數據: ' . $packet->toHex() . PHP_EOL;

try {
    $binaryData = $connection->connect()->sendAndReceive($packet);
} catch (Exception $exception) {
    echo '發生異常' . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    echo $exception->getTraceAsString() . PHP_EOL;
} finally {
    $connection->close();
}
