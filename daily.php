<?php
    // Flickr Family Emailer
    // by Noah Coffey
    // noah.coffey@gmail.com

    $feed = simplexml_load_file("http://api.flickr.com/services/feeds/photos_public.gne?id=XXX-YOUR-FLICKR-ID-XXX&lang=en-us&format=atom");
    
    $i=0;
    foreach ($feed->entry as $entry){
    
        
        $photos[$i]['timestamp'] = (string)($entry->published);
        $photos[$i]['timestamp'] = strtotime($photos[$i]['timestamp']);
    
        $content = (array)$entry->content;
    
        preg_match_all('/<img[^>]*>/Ui', $content[0], $matches);
    
        $photos[$i]['thumb'] = $matches[0][0];
    
    
        $photos[$i]['title'] = (string)$entry->title;
        $vars = (array)$entry->link[0];
        $photos[$i]['url'] = (string)$vars["@attributes"]["href"];
        $i++;
    }

    $count=0;

    foreach ($photos as $photo){
        $past24 = strtotime("24 hours ago");
        if ($photo['timestamp'] > $past24){
            $html .=    "<strong>{$photo['title']}</strong><br />";
            $html .=    "<a href=\"".$photo['url']."\">" . $photo['thumb'] . "</a><br /><br />";
            $count++;
        }
        
    }


    if ($count){
    
        echo "Sending email...";
        
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        // Additional headers
        $headers .= 'To: nameto@example.com' . "\r\n";
        $headers .= 'From: namefrom@example.com' . "\r\n";
        
        $subject =  "New photos uploaded for " . date("F j, Y");
        
        
        $message .= "Hello,<br />";
        $message .= "<br />";
        $message .= "This is an automated message letting you know that I've uploaded $count new photos to flickr since yesterday.<br />";
        $message .= "<br />";
        $message .= "Add whatever text you want to the email here.<br />";
        $message .= "<br />";
        $message .= "Here are some of them...<br />";
        $message .= "<br />";
        $message .= $html;
        $message .= "<br />";
        $message .= "Enjoy!<br />";
        
        
        // Mail it
        $result = mail($to, $subject, $message, $headers, "-f namefrom@example.com");    
        print_r($result);
    }

?>
<?=$html?>