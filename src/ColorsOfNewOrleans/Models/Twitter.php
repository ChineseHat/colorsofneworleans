<?php

namespace ColorsOfNewOrleans\Models;

class Twitter
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function saveTweet($data)
    {
        // Add new users. Duplicates will be ignored.
        $userSql = 'INSERT INTO users VALUES (NULL, ?, ?, ?, ?)';
        $userStmt = $this->db->prepare($userSql);
        $userStmt->execute(array(
            $data->user->id_str,
            $data->user->name,
            $data->user->screen_name,
            $data->user->profile_image_url,
        ));
        $user_id = $this->db->lastInsertId();

        // Add the tweet
        $timestamp = strtotime($data->created_at);
        $tweetSql = 'INSERT INTO tweets VALUES (NULL, ?, ?, FROM_UNIXTIME(?), ?)';
        $tweetStmt = $this->db->prepare($tweetSql);
        $tweetStmt->execute(array(
            $data->id_str,
            $user_id,
            $timestamp,
            html_entity_decode($data->text),
        ));
        $tweet_id = $this->db->lastInsertId();

        // Add new hashtags. Duplicates will be ignored.
        $hashtagSql = 'INSERT INTO hashtags VALUES (NULL, ?, UNHEX(MD5(?)))';
        $hashtagStmt = $this->db->prepare($hashtagSql);
        $tweetTagSql = 'INSERT INTO tweet_hashtags VALUES (?, (SELECT id FROM hashtags AS h WHERE h.hashtag = ?))';
        $tweetTagStmt = $this->db->prepare($tweetTagSql);
        foreach ($data->entities->hashtags as $tag)
        {
            $hashtag = strtolower($tag->text);
            $hashtagStmt->execute(array($hashtag, $hashtag));
            $tweetTagStmt->execute(array($tweet_id, $hashtag));
        }
    }

    public function getCollection($name)
    {
        $sql = "
            SELECT t.id AS tweet_id
              FROM tweets AS t
            INNER
              JOIN tweet_hashtags AS th
                ON th.tweet_id = t.id
            INNER
              JOIN hashtag_collections AS hc
                ON hc.hashtag_id = th.hashtag_id
            INNER
              JOIN collections AS c
                ON c.id = hc.collection_id
             WHERE LOWER(c.name) = LOWER(?)
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($name));
        $tweet_ids = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        $tweet_ids_string = implode(',', array_map(array($this->db, 'quote'), $tweet_ids));
        $where_clause = ' WHERE t.id IN (' . $tweet_ids_string . ')';
        return $this->getTweets($where_clause);
    }

    public function getHashtags()
    {
        $sql = "SELECT CONCAT('#', hashtag) FROM hashtags";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getTweets($where_clause = '')
    {
        $sql = "
            SELECT t.twitter_id
                 , t.created_at
                 , t.text
                 , GROUP_CONCAT(h.hashtag SEPARATOR ' ') AS hashtags
                 , u.twitter_user_id
                 , u.name
                 , u.screen_name
                 , u.profile_image_url
                 , GROUP_CONCAT(LOWER(c.name) SEPARATOR ' ') AS collections
              FROM tweets AS t
            INNER
              JOIN users AS u
                ON u.id = t.user_id
            INNER
              JOIN tweet_hashtags AS th
                ON th.tweet_id = t.id
            INNER
              JOIN hashtags AS h
                ON h.id = th.hashtag_id
            INNER
              JOIN hashtag_collections AS hc
                ON hc.hashtag_id = th.hashtag_id
            INNER
              JOIN collections AS c
                ON c.id = hc.collection_id
            $where_clause
            GROUP
                BY t.twitter_id
                 , t.created_at
                 , t.text
                 , u.twitter_user_id
                 , u.name
                 , u.screen_name
                 , u.profile_image_url
            ORDER
                BY t.created_at DESC
             LIMIT 30
            ";
        $tweets = array();
        foreach ($this->db->query($sql) as $tweet) {
            $tweets[] = $tweet;
        }
        return $tweets;
    }
}
