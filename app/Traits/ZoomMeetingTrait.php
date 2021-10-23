<?php

namespace App\Traits;

use App\ZoomMeeting;
use Debugbar;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Log;

/**
 * trait ZoomMeetingTrait
 */
trait ZoomMeetingTrait
{
    public $client;
    public $jwt;
    public $headers;

    public function __construct()
    {
        $this->client = new Client();
        $this->jwt = $this->generateZoomToken();
        Debugbar::info($this->jwt);
        $this->headers = [
            'Authorization' => 'Bearer '.$this->jwt,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }
    public function generateZoomToken()
    {
        $key = env('ZOOM_API_KEY', '');
        $secret = env('ZOOM_API_SECRET', '');
        $payload = [
            'iss' => $key,
            'exp' => strtotime('+90 minute'),
        ];
        return JWT::encode($payload, $secret, 'HS256');
    }

    private function retrieveZoomUrl()
    {
        return env('ZOOM_API_URL', '');
    }

    public function toZoomTimeFormat(string $dateTime)
    {
        try {
            $date = new \DateTime($dateTime);

            return $date->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            Log::error('ZoomJWT->toZoomTimeFormat : '.$e->getMessage());

            return '';
        }
    }

    public function create($data)
    {
        $path = 'users/'.$data['id'].'/meetings';
        $url = $this->retrieveZoomUrl();
        $meetingType = 2;
        $recurrence = null;
        if (array_key_exists('recurrence', $data)) {
            $meetingType = 8;
            $recurrence = [];
            $recurrence['type'] = $data['recurrence_type'];
            $recurrence['repeat_interval'] = $data['recurrence_repeat_interval'];
            if ($data['recurrence_end_type'] == 'datetime') {
                $recurrence['end_date_time'] = $data['recurrence_end_date_time'] . 'T00:00:00Z';
            } else if ($data['recurrence_end_type'] == 'times') {
                $recurrence['end_times'] = $data['recurrence_end_times'];
            }
            switch ($recurrence['type']) {
                case 2:
                    $recurrence['weekly_days'] = implode(",",$data['recurrence_weekly_days']);
                    Debugbar::info($recurrence['weekly_days']);
                    break;
                case 3:
                    $recurrence['monthly_day'] = $data['recurrence_monthly_day'];
                    break;
                default:
                    break;
            }
        }
        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([
                'topic'      => $data['topic'],
                'type'       => $meetingType,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'password'   => (!empty($data['password'])) ? $data['password'] : null,
                'duration'   => $data['duration'],
                'agenda'     => (!empty($data['agenda'])) ? $data['agenda'] : null,
                'timezone'   => 'Asia/Saigon',
                // 'schedule_for'   => 'quanghungleo@gmail.com',
                'recurrence' => $recurrence,
                'settings'   => [
                    // 'host_video'        => ($data['host_video'] == "1") ? true : false,
                    'host_video'        => true,
                    'participant_video'        => true,
                    // 'participant_video' => ($data['participant_video'] == "1") ? true : false,
                    // 'waiting_room'      => true,
                    // 'auto_recording'      => 'local',
                    'join_before_host'      => true,
                    'jbh'               => 5,
                    'approval_type'      => 2,
                    'alternative_hosts'      => '',
                    // 'meeting_authentication'      => true,
                    'audio' => 'voip'
                ],
            ]),
        ];

        $response =  $this->client->post($url.$path, $body);
        $responseBody = json_decode($response->getBody(), true);
        Debugbar::info($responseBody);
        if ($response->getStatusCode() === 201) {
            $zoom = new ZoomMeeting();
            $zoom->meeting_id = $responseBody['id'];
            $zoom->topic = $responseBody['topic'];
            $zoom->type = $responseBody['type'];
            $zoom->password = $responseBody['password'];
            $zoom->settings = json_encode($responseBody['settings']);
            $zoom->join_url = $responseBody['join_url'];
            $zoom->start_url = $responseBody['start_url'];
            $zoom->owner_id = $data['id'];
            $zoom->duration = array_key_exists('duration', $responseBody) ? $responseBody['duration'] : null;
            $zoom->start_time = array_key_exists('start_time', $responseBody) ? $responseBody['start_time'] : null;
            $zoom->tracking_fields = array_key_exists('tracking_fields', $responseBody) ? json_encode($responseBody['tracking_fields']) : null;
            $zoom->recurrence = array_key_exists('recurrence', $responseBody) ? json_encode($responseBody['recurrence']) : null;
            $zoom->occurrences = array_key_exists('occurrences', $responseBody) ? json_encode($responseBody['occurrences']) : null;
            $zoom->agenda = array_key_exists('agenda', $responseBody) ? $responseBody['agenda'] : '';

            $zoom->save();
        }


        return [
            'success' => $response->getStatusCode() === 201,
            'data'    => $responseBody,
        ];
    }

    public function update($id, $data)
    {
        $path = 'meetings/'.$id;
        $url = $this->retrieveZoomUrl();
        $meetingType = 2;
        $recurrence = null;
        if (array_key_exists('recurrence', $data)) {
            $meetingType = 8;
            $recurrence = [];
            $recurrence['type'] = $data['recurrence_type'];
            $recurrence['repeat_interval'] = $data['recurrence_repeat_interval'];
            if ($data['recurrence_end_type'] == 'datetime') {
                $recurrence['end_date_time'] = $data['recurrence_end_date_time'] . 'T00:00:00Z';
            } else if ($data['recurrence_end_type'] == 'times') {
                $recurrence['end_times'] = $data['recurrence_end_times'];
            }
            switch ($recurrence['type']) {
                case 2:
                    $recurrence['weekly_days'] = implode(",",$data['recurrence_weekly_days']);
                    Debugbar::info($recurrence['weekly_days']);
                    break;
                case 3:
                    $recurrence['monthly_day'] = $data['recurrence_monthly_day'];
                    break;
                default:
                    break;
            }
        }
        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([
                'topic'      => $data['topic'],
                'type'       => $meetingType,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'password'   => (!empty($data['password'])) ? $data['password'] : null,
                'duration'   => $data['duration'],
                'agenda'     => (!empty($data['agenda'])) ? $data['agenda'] : null,
                'timezone'   => 'Asia/Saigon',
                // 'schedule_for'   => 'quanghungleo@gmail.com',
                'recurrence' => $recurrence,
                'settings'   => [
                    // 'host_video'        => ($data['host_video'] == "1") ? true : false,
                    'host_video'        => true,
                    'participant_video'        => true,
                    // 'participant_video' => ($data['participant_video'] == "1") ? true : false,
                    // 'waiting_room'      => true,
                    // 'auto_recording'      => 'local',
                    'join_before_host'      => true,
                    'jbh'               => 5,
                    'approval_type'      => 2,
                    'alternative_hosts'      => '',
                    // 'meeting_authentication'      => true,
                    'audio' => 'voip'
                ],
            ]),
        ];
        $response =  $this->client->patch($url.$path, $body);
        if ($response->getStatusCode() === 204) {
            $path = 'meetings/'.$id;
            $url = $this->retrieveZoomUrl();
            $this->jwt = $this->generateZoomToken();
            $body = [
                'headers' => $this->headers,
                'body'    => json_encode([]),
                'show_previous_occurrences'    => false,
            ];

            $response =  $this->client->get($url.$path, $body);
            $responseBody = json_decode($response->getBody(), true);

            $zoom = ZoomMeeting::where('meeting_id', $id)->first();
            Debugbar::info($zoom);
            $zoom->topic = $responseBody['topic'];
            $zoom->type = $responseBody['type'];
            $zoom->password = $responseBody['password'];
            $zoom->settings = json_encode($responseBody['settings']);
            $zoom->join_url = $responseBody['join_url'];
            $zoom->start_url = $responseBody['start_url'];
            $zoom->duration = array_key_exists('duration', $responseBody) ? $responseBody['duration'] : null;
            $zoom->start_time = array_key_exists('start_time', $responseBody) ? $responseBody['start_time'] : null;
            $zoom->tracking_fields = array_key_exists('tracking_fields', $responseBody) ? json_encode($responseBody['tracking_fields']) : null;
            $zoom->recurrence = array_key_exists('recurrence', $responseBody) ? json_encode($responseBody['recurrence']) : null;
            $zoom->occurrences = array_key_exists('occurrences', $responseBody) ? json_encode($responseBody['occurrences']) : null;
            $zoom->agenda = array_key_exists('agenda', $responseBody) ? $responseBody['agenda'] : '';

            $zoom->save();
        }

        return [
            'success' => $response->getStatusCode() === 200,
            'data'    => json_decode($response->getBody(), true),
        ];
    }

    public function get($id)
    {
        $path = 'meetings/'.$id;
        $url = $this->retrieveZoomUrl();
        $this->jwt = $this->generateZoomToken();
        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([]),
            'show_previous_occurrences'    => false,
        ];

        $response =  $this->client->get($url.$path, $body);
        Debugbar::info($response);
        return [
            'success' => $response->getStatusCode() === 200,
            'data'    => json_decode($response->getBody(), true),
        ];
    }

    public function getList($id)
    {
        $meetings = ZoomMeeting::where('owner_id', $id)->get();
        return [
            'data'    => $meetings,
        ];

        // $path = 'users/'.$id.'/meetings';
        // $url = $this->retrieveZoomUrl();
        // $this->jwt = $this->generateZoomToken();
        // $body = [
        //     'headers' => $this->headers,
        //     'body'    => json_encode([]),
        // ];

        // $response =  $this->client->get($url.$path, $body);
        // return [
        //     'success' => $response->getStatusCode() === 200,
        //     'data'    => json_decode($response->getBody(), true),
        // ];
    }

    public function getListUsers()
    {
        $path = 'users';
        $url = $this->retrieveZoomUrl();
        $this->jwt = $this->generateZoomToken();
        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([]),
        ];

        $response =  $this->client->get($url.$path, $body);
        return [
            'success' => $response->getStatusCode() === 200,
            'data'    => json_decode($response->getBody(), true),
        ];
    }

    /**
     * @param string $id
     *
     * @return bool[]
     */
    public function delete($id)
    {
        $path = 'meetings/'.$id;
        $url = $this->retrieveZoomUrl();
        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([]),
        ];
        $idOwner = '1';

        $response =  $this->client->delete($url.$path, $body);
        if ($response->getStatusCode() === 204) {
            $zoom = ZoomMeeting::where('meeting_id', $id)->first();
            if ($zoom) {
                $idOwner = $zoom->owner_id;
                $zoom->delete();
            }
        }

        return [
            'success' => $response->getStatusCode() === 204,
            'owner_id' => $idOwner
        ];
    }
}
