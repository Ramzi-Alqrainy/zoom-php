<?php

namespace ZoomPHP\Api;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Zoom Meeting API.
 */
class Meeting extends AbstractItemApi {

  /**
   * {@inheritdoc}
   */
  protected $resourcePath = 'meetings/{id}';

  /**
   * {@inheritdoc}
   */
  protected $propertyDefs = [
    // The user_id is only used with the create method as a parameter since
    // the method cannot handle an additional argument as defined by the
    // abstract class.
    'user_id' => [
      'types' => 'string',
      'required' => TRUE,
    ],
    'meetingUUID' => [
      'types' => 'string',
      'required' => TRUE,
    ],
    'topic' => [
      'types' => 'string',
    ],
    'type' => [
      'types' => 'int',
      'values' => [1, 2, 3, 8],
      'default' => 2,
    ],
    // @todo Format / value conditional on timezone and vice-versa.
    // When using a format like “yyyy-MM-dd’T’HH:mm:ss’Z’”, always use GMT
    // time. When using a format like “yyyy-MM-dd’T’HH:mm:ss”, you should use
    // local time and you will need to specify the time zone. Only used for
    // scheduled meetings and recurring meetings with fixed time.
    'start_time' => [
      'types' => 'string',
      'normalizer' => 'datetime',
    ],
    // @todo only for scheduled meetings.
    'duration' => [
      'types' => 'int',
    ],
    // @todo only for scheduled meetings.
    'timezone' => [
      'types' => 'string',
    ],
    'password' => [
      'types' => 'string',
      'constraints' => [
        'maxlength' => 10,
        'regex' => '[a-zA-Z0-9@\-_*]'
      ],
    ],
    'agenda' => [
      'types' => 'string',
    ],
    'recurrence' => [
      'types' => 'array',
      'propertyDefs' => [
        'type' => [
          'types' => 'int',
          'values' => [1, 2, 3],
        ],
        // @todo max varies by type.
        'repeat_interval' => [
          'types' => 'int',
        ],
        'weekly_days' => [
          'types' => 'int',
          'values' => [1, 2, 3, 4, 5, 6, 7],
        ],
        'monthly_day' => [
          'types' => 'int',
          'constraints' => [
            'range' => [1, 31],
          ],
        ],
        'monthly_week' => [
          'types' => 'int',
          'values' => [-1, 1, 2, 3, 4],
        ],
        'monthly_week_day' => [
          'types' => 'int',
          'values' => [1, 2, 3, 4, 5, 6, 7],
        ],
        // @todo cannot be used with end_date_time.
        'end_times' => [
          'types' => 'int',
          'constraints' => [
            'range' => [1, 50],
          ],
        ],
        // Should be UTC time, such as 2017-11-25T12:00:00Z.
        // @todo Cannot be used with end_times.
        // @todo note different format than start_time.
        'end_date_time' => [
          'type' => 'string',
          'normalizer' => 'datetime',
        ],
      ],
    ],
    'settings' => [
      'types' => 'array',
      'propertyDefs' => [
        'host_video' => [
          'types' => 'bool',
        ],
        'participant_video' => [
          'types' => 'bool',
        ],
        'cn_meeting' => [
          'types' => 'bool',
          'default' => FALSE,
        ],
        'in_meeting' => [
          'types' => 'bool',
          'default' => FALSE,
        ],
        'join_before_host' => [
          'types' => 'bool',
          'default' => FALSE,
        ],
        'mute_upon_entry' => [
          'types' => 'bool',
          'default' => FALSE,
        ],
        'watermark' => [
          'types' => 'bool',
          'default' => FALSE,
        ],
        'use_pmi' => [
          'types' => 'bool',
          'default' => FALSE,
        ],
        'approval_type' => [
          'types' => 'int',
          'values' => [0, 1, 2],
          'default' => 2,
        ],
        'registration_type' => [
          'types' => 'int',
          'values' => [1, 2, 3],
          'default' => 1,
        ],
        'audio' => [
          'types' => 'string',
          'values' => ['both', 'telephony', 'voip'],
          'default' => 'both',
        ],
        'auto_recording' => [
          'types' => 'string',
          'values' => ['local', 'cloud', 'none'],
          'default' => 'none',
        ],
        'enforce_login' => [
          'types' => 'bool',
        ],
        'enforce_login_domains' => [
          'types' => 'string',
        ],
        'alternative_hosts' => [
          'types' => 'string',
        ],
      ],
    ],
    'occurrence_id' => [
      'types' => 'string',
    ],
    // @todo one-offs like this can go into getPropertyDefs().
    'action' => [
      'types' => 'string',
      'values' => ['end'],
    ],
    // @todo this is for a meetings registrants query... a list api.
    'status' => [
      'types' => 'string',
      'values' => ['pending', 'approved', 'denied'],
      'default' => 'approved',
    ],
  ];

  /**
   * Create Meeting.
   */
  public function create(array $params = []) {
    $params = $this->resolveOptionsBySet($params, 'create');
    $userId = $params['user_id'];
    unset($params['user_id']);
    return $this->createForUser($userId, $params);
  }

  /**
   * Create Meeting for User.
   */
  public function createForUser($userId, array $params = []) {
    $params = $this->resolveOptionsBySet($params, 'createForUser');
    return $this->post("users/{$userId}/meetings", $params);
  }

  /**
       * Meeting
       *
       * @param $meetingId
       * @return array|mixed
       */
      public function meeting(string $meetingId) {
          return $this->get("meetings/{$meetingId}");
      }

      /**
       * Remove
       *
       * @param $meetingId
       * @return array|mixed
       */
      public function remove(string $meetingId) {
          return $this->delete("meetings/{$meetingId}");
      }

      /**
       * Update
       *
       * @param $meetingId
       * @param array $data
       * @return array|mixed
       */
      public function update(string $meetingId, array $data = []) {
          return $this->patch("meetings/{$meetingId}", $data);
      }

      /**
       * Status
       *
       * @param $meetingId
       * @param array $data
       * @return mixed
       */
      public function status(string $meetingId, array $data = []) {
          return $this->put("meetings/{$meetingId}/status", $data);
      }

      /**
       * List Registrants
       *
       * @param $meetingId
       * @param array $query
       * @return array|mixed
       */
      public function listRegistrants(string $meetingId, array $query = []) {
          return $this->get("meetings/{$meetingId}/registrants", $query);
      }

      /**
       * Add Registrant
       *
       * @param $meetingId
       * @param array $data
       * @return array|mixed
       */
      public function addRegistrant(string $meetingId, $data = []) {
          return $this->post("meetings/{$meetingId}/registrants", $data);
      }

      /**
       * Update Registrant Status
       *
       * @param $meetingId
       * @param array $data
       * @return array|mixed
       */
      public function updateRegistrantStatus(string $meetingId, array $data = []) {
          return $this->put("meetings/{$meetingId}/registrants/status", $data);
      }


  /**
   * {@inheritdoc}
   */
  protected function getPropertyDefs($setName = '') {
    $defs = parent::getPropertyDefs($setName);
    $properties = [];

    switch ($setName) {
      case 'create':
        $properties = ['user_id'];

      case 'createForUser':
        $properties = array_merge($properties, [
          'topic',
          'type',
          'start_time',
          'duration',
          'timezone',
          'password',
          'agenda',
          'recurrence',
          'settings',
        ]);
        break;

      case 'update':
        $properties = [
          'topic',
          'type',
          'start_time',
          'duration',
          'timezone',
          'password',
          'agenda',
          'recurrence',
          'settings',
        ];
        break;

      case 'remove':
        $properties = ['occurrence_id'];
        break;

      case 'status':
        $properties = ['action'];
        break;
    }

    $defs = array_intersect_key($defs, array_combine($properties, $properties));
    return $defs;
  }

}
