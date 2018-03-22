<?php

use ModbusTcpClient\Network\BinaryStreamConnection;
use ModbusTcpClient\Packet\ModbusFunction\WriteSingleRegisterRequest;
use ModbusTcpClient\Packet\ModbusFunction\WriteSingleRegisterResponse;
use ModbusTcpClient\Packet\ResponseFactory;

require __DIR__ . '/../vendor/autoload.php';

//已台達為範例 設定人機介面 Port Host
$connection = BinaryStreamConnection::getBuilder()
    ->setPort(502)
    ->setHost('172.16.190.51')
    ->build();

//起始記憶體位置
$startAddress = 14;
$value = true;
$packet = new WriteSingleRegisterRequest($startAddress, $value);
echo '發送數據: ' . $packet->toHex() . PHP_EOL;

try {
    $binaryData = $connection->connect()
        ->sendAndReceive($packet);
    echo '二進制:   ' . unpack('H*', $binaryData)[1] . PHP_EOL;

    /* @var $response WriteSingleRegisterResponse */
    $response = ResponseFactory::parseResponseOrThrow($binaryData);
    echo '分析包:     ' . $response->toHex() . PHP_EOL;
    echo '從數據包解析的寄存器值:' . PHP_EOL;
    print_r($response->getValue());

} catch (Exception $exception) {
    echo '發生異常' . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    echo $exception->getTraceAsString() . PHP_EOL;
} finally {
    $connection->close();
}
