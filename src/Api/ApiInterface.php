<?php

namespace ZoomPHP\Api;

use ZoomPHP\ZoomPHPClient;

/**
 * Zoom API Interface.
 */
interface ApiInterface {

  /**
   * ZoomPHP API Constructor.
   */
  public function __construct(ZoomPHPClient $client);

}
