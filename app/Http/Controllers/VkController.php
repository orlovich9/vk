<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class VkController extends Controller
{
    const ACCESS_TOKEN = '51c72540e86c568f054bb1770d53edcd6db43bf920f71c4e011fa9cddd12ef3cdcbc5c0f755b322ac2602';
    const API_VERSION = '5.80';
    const ADMIN_ID = '11424141';

    public $client;
    protected $allDiffTime = false;
    protected $maxTime = false;
    protected $minTime = false;
    protected $averageTime = false;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * Display table for messages info
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('vk');
    }

    /**
     * Display messages info for vk group
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function showVkGroupData(Request $request)
    {
        if (empty($request->date))
        {
            return redirect()->route('main');
        }

        $date = $request->date;

        $request_params = [
            'filter' => 'admin',
            'v' => self::API_VERSION,
            'access_token' => self::ACCESS_TOKEN
        ];

        $get_params = http_build_query($request_params);
        $res = $this->client->request('GET', 'https://api.vk.com/method/groups.get?' . $get_params);
        $data = json_decode($res->getBody());

        if (isset($data->response))
        {
            $arDiffTime = [];
            $count = 0;

            $groupId = $data->response->items[0];
            $arMessages = $this->getMessagesOfGroup(-$groupId, $date);

            foreach ($arMessages as $message)
            {
                $arDiffTime[] = $this->getDiffTimeOfAdminAnswer(-$groupId, $message->id, $message->date);
            }

            foreach ($arDiffTime as $time)
            {
                if ($count <= 0)
                {
                    $this->minTime = $time;
                }
                elseif ($this->minTime > $time)
                {
                    $this->minTime = $time;
                }

                if ($time > $this->maxTime)
                {
                    $this->maxTime = $time;
                }

                $this->allDiffTime += $time;
                $count++;
            }

            if ($this->allDiffTime)
            {
                $this->averageTime = $this->allDiffTime / $count;
            }
        }
        else
        {
            return redirect()->route('main')->withErrors($data->error->error_msg);
        }

        return view('vk', ['arMessages' => $arMessages, 'average' => $this->averageTime . ' ч.', 'max' => $this->maxTime . ' ч.', 'min' => $this->minTime . ' ч.']);
    }

    /**
     * Get messages of vk group
     * @param $id
     * @param $date
     * @return array
     */
    public function getMessagesOfGroup($id, $date)
    {
        $arMessages = [];

        $request_params = [
            'owner_id' => $id,
            'count' => '100',
            'v' => self::API_VERSION,
            'access_token' => self::ACCESS_TOKEN
        ];

        $get_params = http_build_query($request_params);
        $res = $this->client->request('GET', 'https://api.vk.com/method/wall.get?' . $get_params);

        foreach (json_decode($res->getBody())->response->items as $message)
        {
            if (date('d.m.Y', $message->date) == $date)
            {
                $arMessages[] = $message;
            }
        }

        return $arMessages;
    }

    /**
     * Get the difference in time between a message and a comment
     * @param $groupId
     * @param $messageId
     * @param $messageDate
     * @return integer
     */
    public function getDiffTimeOfAdminAnswer($groupId, $messageId, $messageDate)
    {
        $request_params = [
            'owner_id' => $groupId,
            'post_id' => $messageId,
            'extended' => '1',
            'sort' => 'asc',
            'v' => self::API_VERSION,
            'access_token' => self::ACCESS_TOKEN
        ];

        $get_params = http_build_query($request_params);
        $res = $this->client->request('GET', 'https://api.vk.com/method/wall.getComments?' . $get_params);

        $messageDate = Carbon::createFromTimestamp($messageDate);
        $commentDate = Carbon::createFromTimestamp(1474628310);

        return $messageDate->diffInHours($commentDate);

        foreach (json_decode($res->getBody())->response->items as $comment)
        {
            if ($comment->from_id == self::ADMIN_ID)
            {
                return $messageDate->diffInHours(Carbon::createFromTimestamp($comment->date));
            }
        }
    }
}
