<?php
/*
 * JSON list of tweets using:
 *         http://dev.twitter.com/doc/get/statuses/user_timeline
 * Cached using WP transient API.
 *        http://www.problogdesign.com/wordpress/add-a-backup-to-embedded-tweets-in-wordpress/
 */

add_shortcode('latest_tweets', 'wpc_latest_tweets');
function wpc_latest_tweets(){
    // Configuration.
    $numTweets = 2;
    $name = 'pauldewouters';
    $cacheTime = 5; // Time in minutes between updates.
    $exclude_replies = false; // Leave out @replies?

    $transName = 'list-tweets'; // Name of value in database.
    $backupName = $transName . '-backup'; // Name of backup value in database.

    // Do we already have saved tweet data? If not, lets get it.
    if(false === ($tweets = get_transient($transName) ) ) :    

        // Get the tweets from Twitter.
        $response = wp_remote_get("http://api.twitter.com/1/statuses/user_timeline.json?screen_name=$name&count=$numTweets&exclude_replies=$exclude_replies");

        // If we didn't find tweets, use the previously stored values.
        if( !is_wp_error($response) && $response['response']['code'] == 200) :
            // Get tweets into an array.
            $tweets_json = json_decode($response['body'], true);

            // Now update the array to store just what we need.
            // (Done here instead of PHP doing this for every page load)
            foreach ($tweets_json as $tweet) :
                // Core info.
                $name = $tweet['user']['name'];
                $permalink = 'http://twitter.com/#!/'. $name .'/status/'. $tweet['id_str'];

                /* Alternative image sizes method: http://dev.twitter.com/doc/get/users/profile_image/:screen_name */
                $image = $tweet['user']['profile_image_url'];

                // Message. Convert links to real links.
                $pattern = '/http:(\S)+/';
                $replace = '<a href="${0}" target="_blank" rel="nofollow">${0}</a>';
                $text = preg_replace($pattern, $replace, $tweet['text']);

                // Need to get time in Unix format.
                $time = $tweet['created_at'];
                $time = date_parse($time);
                $uTime = mktime($time['hour'], $time['minute'], $time['second'], $time['month'], $time['day'], $time['year']);

                // Now make the new array.
                $tweets[] = array(
                                'text' => $text,
                                'name' => $name,
                                'permalink' => $permalink,
                                'image' => $image,
                                'time' => $uTime
                                );
            endforeach;

            // Save our new transient, and update the backup.
            set_transient($transName, $tweets, 60 * $cacheTime);
            update_option($backupName, $tweets);

        else : // i.e. Fetching new tweets failed.
            $tweets = get_option($backupName); // False if there has never been data saved.
        endif;
    endif;

    // Now display the tweets, if we can.
    if($tweets) : 
        $output = '<ul id="tweets">';
        foreach($tweets as $t) : 
            $output .= '<li>';
                //$output .= '<img src="' .$t['image'] . '" width="48" height="48" alt="" />';               
                $output .= '<div class="tweet-inner">';
                    $output .= '<p>';
                        $output .=  $t['name'] . ': '. $t['text'];
                        $output .= '<span class="tweet-time"> '. human_time_diff($t['time'], current_time('timestamp')) . ' ago</span>';
                    $output .= '</p>';
                $output .= '</div><!-- /tweet-inner -->';
            $output .= '</li>';
        endforeach;
        $output .= '</ul>';
        $output .= '<p><a href="http://twitter.com/#!/' . $name . '">[ Follow me on Twitter ]</a></p>';

    else : 
        $output = '<p>No tweets found.</p>';
    endif;
    return $output;
}