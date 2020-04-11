<?php

namespace ZoomPHP\Model;

/**
 * Class User.
 *
 * @see https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetings
 */
class Meeting extends AbstractModel {

  const MEETING_TYPE_DAILY = 1;
  const MEETING_TYPE_WEEKLY = 2;
  const MEETING_TYPE_MONTHLY = 3;

  protected static $properties = [


    'topic',
    'type',
    'start_time',
    'duration',
    'timezone',
    'password',
    'agenda',
    'type',
    'repeat_interval',
    'weekly_days',
    'monthly_day',
    'monthly_week_daily',
    'end_times',
    'end_date_time',
    'host_video',
    'in_meeting',
    'join_before_host',
    'mute_upon_entry',
    'participant_video',
    'registrants_confirmation_email',
  ];

  /**
   * Retrieve the user.
   */
  public function fetch() {
    return $this->fromArray($this->client->api('user')->fetch($this->getId()));
  }

  /**
   * Create the user.
   */
  public function create($type, $email, array $data = []) {
    return $this->fromArray($this->client->api('user')->create($type, $email, $data));
  }

  /**
   * Custom Create the user.
   */
  public function custCreate($type, $email, array $data = []) {
    return $this->fromArray($this->client->api('user')->custCreate($type, $email, $data));
  }

  /**
   * Update user.
   */
  public function update($data) {
    $this->client->api('user')->update($this->getId(), $data);
    return $this;
  }

  /**
   * Delete user.
   */
  public function delete() {
    return $this->client->api('user')->delete($this->getId(), 'delete');
  }

  /**
   * Disassociate user.
   */
  public function disassociate(array $data = []) {
    return $this->client->api('user')->delete($this->getId(), 'disassociate');
  }

  /**
   * Get / update user settings.
   */
  public function settings(array $settings = []) {
    if (empty($settings)) {
      $content['user_settings'] = $this->client->api('user')->settings($this->getId());
      return $this->fromArray($content);
    }
    else {
      $this->client->api('user')->settings($this->getId(), $settings);
      return $this;
    }
  }

  /**
   * Update user status.
   */
  public function status($status) {
    $this->client->api('user')->status($this->getId(), $status);
    return $this;
  }

  /**
   * Activate user.
   */
  public function activate() {
    return $this->status('activate');
  }

  /**
   * Deactivate user.
   */
  public function deactivate() {
    return $this->status('deactivate');
  }

  /**
   * Update user password.
   */
  public function password($password) {
    $this->client->api('user')->password($this->getId(), $password);
    return $this;
  }

  /**
   * Update user email.
   */
  public function email($email) {
    $this->client->api('user')->email($this->getId(), $email);
    return $this;
  }

  public function meetings($type = 'live') {
    $content['meetings'] = $this->client->api('meetings')->listAll($this->getId(), $type);
    return $this->toArray($content);
  }

  public function allMeetings() {
    $content['meetings'] = [];

    foreach (['scheduled', 'live', 'upcoming'] as $type) {
      $content['meetings'] += $this->client->api('meetings')->listAll($this->getId(), $type);
    }

    return $this->toArray($content);
  }

}
