<?php

namespace ultimo\util\net\wol;

class Exception extends \Exception {
  const SOCKET_CREATE_ERROR = 1;
  const SOCKET_BROADCAST_ERROR = 2;
  const SOCKET_SEND_ERROR = 3;
}