<?php

namespace ZoomPHP\Api;

use ZoomPHP\ZoomAPIClient;

/**
 * Zoom API Interface.
 */
interface ApiInterface {

  /**
   * ZoomPHP API Constructor.
   */
  public function __construct(ZoomAPIClient $client);

}
