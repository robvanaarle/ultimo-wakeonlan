<?php

namespace ultimo\util\net\wol;

class WakeOnLan {
  
  public function getMagicPacket($mac) {
    // build magic packet, starting with 6 times FF
    $packet = str_repeat(chr(255), 6);
    
    // add 16 times mac address
    $macBytes = explode(':', $mac);
    $packetMac = '';
    for ($i=0; $i < 6; $i++) {
      $packetMac .= chr(hexdec($macBytes[$i]));
    }
    $packet .= str_repeat($packetMac, 16);

    return $packet;
  }
  
  public function broadcast($packet, $ip="255.255.255.255", $port=9) {
    // send packet to the broadcast address using UDP
    
    // create socket
    $socket = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    if ($socket === false) {
      throw new Exception("Unable to create socket: " . socket_strerror(socket_last_error()) . ' (' . socket_last_error() . ')', Exception::SOCKET_CREATE_ERROR);
    }
    
    // set broadcast option
    if (!@socket_set_option($socket, 1, SO_BROADCAST, true)) {
      socket_close($socket);
      throw new Exception("Unable to set broadcast option on socket: " . socket_strerror(socket_last_error()) . ' (' . socket_last_error() . ')', Exception::SOCKET_BROADCAST_ERROR);
    }
    
    // send packet
    if (!socket_sendto($socket, $packet, strlen($packet), 0, $ip, $port)) {
      socket_close($socket);
      throw new Exception("Sending packet failed: " . socket_strerror(socket_last_error()) . ' (' . socket_last_error() . ')', Exception::SOCKET_SEND_ERROR);
    }
    
    socket_close($socket);
  }
  
  public function wake($mac, $ip="255.255.255.255", $port=9) {
    $packet = $this->getMagicPacket($mac);
    $this->broadcast($packet, $ip, $port);
  }
}