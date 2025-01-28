<?php

// repost forbidden
// http://127.0.0.1:9504/api/messages.forwardMessages/?data[from_peer]=@obmen_lugansk_reserv&data[to_peer]=@kursivalut_ru_donetsk&data[id][0]=2386260

$url = env('APP_URL') . '/api/posts/add';

// лучше ставить актуальное время date

// original $content = '{"_":"updateNewChannelMessage","message":{"_":"message","out":false,"mentioned":false,"media_unread":false,"silent":false,"post":false,"from_scheduled":false,"legacy":false,"edit_hide":false,"pinned":false,"noforwards":false,"id":405943,"from_id":{"_":"peerUser","user_id":1752154981},"peer_id":{"_":"peerChannel","channel_id":1345575332},"date":1737637014,"message":"Продам безнал 1590 грн по 2.37. Личная встреча","reply_markup":{"_":"replyInlineMarkup","rows":[{"_":"keyboardButtonRow","buttons":[{"_":"keyboardButtonCallback","requires_password":false,"text":"\ud83c\udf4f","data":{"_":"bytes","bytes":"Y2FwdGNoYV\/wn42PXzM5MDA2Nw=="}},{"_":"keyboardButtonCallback","requires_password":false,"text":"\ud83c\udf52","data":{"_":"bytes","bytes":"Y2FwdGNoYV\/wn42SXzM5MDA2Nw=="}},{"_":"keyboardButtonCallback","requires_password":false,"text":"\ud83c\udf47","data":{"_":"bytes","bytes":"Y2FwdGNoYV\/wn42HXzM5MDA2Nw=="}},{"_":"keyboardButtonCallback","requires_password":false,"text":"\ud83e\udd5d","data":{"_":"bytes","bytes":"Y2FwdGNoYV\/wn6WdXzM5MDA2Nw=="}},{"_":"keyboardButtonCallback","requires_password":false,"text":"\ud83c\udf4c","data":{"_":"bytes","bytes":"Y2FwdGNoYV\/wn42MXzM5MDA2Nw=="}}]}]},"entities":[{"_":"messageEntityMentionName","offset":0,"length":8,"user_id":6449556380},{"_":"messageEntityBold","offset":43,"length":27}],"replies":{"_":"messageReplies","comments":false,"replies":0,"replies_pts":639615},"ttl_period":259200,"to_id":{"_":"peerChannel","channel_id":1776390569}},"pts":639615,"pts_count":1}';
$channel_id = "1271652753";
$post_id = "2386260";
$content = '{"_":"updateNewChannelMessage","message":{"_":"message","out":false,"mentioned":false,"media_unread":false,"silent":false,"post":false,"from_scheduled":false,"legacy":false,"edit_hide":false,"pinned":false,"noforwards":false,"id":'. $post_id . ',"from_id":{"_":"peerUser","user_id":1752154981},"peer_id":{"_":"peerChannel","channel_id":' . $channel_id . '},"date":1737637014,"message":"Продам безнал 1590 грн по 2.37. Личная встреча","reply_markup":{"_":"replyInlineMarkup","rows":[{"_":"keyboardButtonRow","buttons":[{"_":"keyboardButtonCallback","requires_password":false,"text":"\ud83c\udf4f","data":{"_":"bytes","bytes":"Y2FwdGNoYV\/wn42PXzM5MDA2Nw=="}},{"_":"keyboardButtonCallback","requires_password":false,"text":"\ud83c\udf52","data":{"_":"bytes","bytes":"Y2FwdGNoYV\/wn42SXzM5MDA2Nw=="}},{"_":"keyboardButtonCallback","requires_password":false,"text":"\ud83c\udf47","data":{"_":"bytes","bytes":"Y2FwdGNoYV\/wn42HXzM5MDA2Nw=="}},{"_":"keyboardButtonCallback","requires_password":false,"text":"\ud83e\udd5d","data":{"_":"bytes","bytes":"Y2FwdGNoYV\/wn6WdXzM5MDA2Nw=="}},{"_":"keyboardButtonCallback","requires_password":false,"text":"\ud83c\udf4c","data":{"_":"bytes","bytes":"Y2FwdGNoYV\/wn42MXzM5MDA2Nw=="}}]}]},"entities":[{"_":"messageEntityMentionName","offset":0,"length":8,"user_id":6449556380},{"_":"messageEntityBold","offset":43,"length":27}],"replies":{"_":"messageReplies","comments":false,"replies":0,"replies_pts":639615},"ttl_period":259200,"to_id":{"_":"peerChannel","channel_id":1776390569}},"pts":639615,"pts_count":1}';
$curl_handle = curl_init();
curl_setopt($curl_handle, CURLOPT_URL, $url);
curl_setopt($curl_handle, CURLOPT_POST, 1);
curl_setopt($curl_handle, CURLOPT_POSTFIELDS, [
    "password" => env('API_PASSWORD'),
    "content" => $content]);
curl_setopt($curl_handle,CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
$query = curl_exec($curl_handle);
curl_close($curl_handle);

var_dump($query);