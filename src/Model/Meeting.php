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
    'status',
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
    return $this->fromArray($this->client->api('meeting')->fetch($this->getId()));
  }

  /**
   * Create the meeting.
   */
  public function create( array $data = []) {
    return $this->fromArray($this->client->api('meeting')->create($data));
  }

  /**
   * Custom Create the user.
   */
  public function createForUser($userId, array $data = []) {
    return $this->fromArray($this->client->api('meeting')->createForUser($userId, $data));
  }

  /**
   * Update meeting.
   */
  public function update(tring $meetingId, array $data = []) {
    $this->client->api('meeting')->update($meetingId, $data);
    return $this;
  }

  /**
   * Delete meeting.
   */
  public function remove(string $meetingId) {
    return $this->client->api('meeting')->remove($meetingId);
  }

  /**
   * status meeting.
   */
  public function status(string $meetingId, array $data = []) {
    return $this->client->api('meeting')->status($meetingId,$data);
  }

  /**
   * Delete user.
   */
  public function listRegistrants(string $meetingId, array $query = []) {
    return $this->client->api('meeting')->listRegistrants($meetingId,$query);
  }

  /**
   * Add Registrant
   *
   * @param $meetingId
   * @param array $data
   * @return array|mixed
   */
  public function addRegistrant(string $meetingId, $data = []) {
    return $this->client->api('meeting')->addRegistrant($meetingId,$query);
  }

  /**
   * Update Registrant Status
   *
   * @param $meetingId
   * @param array $data
   * @return array|mixed
   */
  public function updateRegistrantStatus(string $meetingId, array $data = []) {
      return $this->client->api('meeting')->updateRegistrantStatus($meetingId, $data);
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
