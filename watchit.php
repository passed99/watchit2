<?php
    $headers = array (
        'authority: api.watchit.com',
        'accept: application/json',
        'accept-language: en-US,en;q=0.9,ar;q=0.8',
        'applicationversion: 5.12.0',
        'authorization: Bearer 255ab1b80fd97ab4c220d6f16e1e31dd',
        'cache-control: no-cache',
        'content-type: application/json',
        'deviceid: dimno4d8ver614xbeqwsf51pjmt02n1452952800000',
        'deviceos: Web',
        'dgst: qsJGdAAn6/Vu5uTCpgbUz1OnSF9ZvpqRyHwW/m3iLLc=',
        'lang: en',
        'origin: https://www.watchit.com',
        'pragma: no-cache',
        'referer: https://www.watchit.com/',
        'sec-ch-ua: "Not A(Brand";v="99", "Google Chrome";v="121", "Chromium";v="121"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-site',
        'service-code: 1708884483246',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
        'x_c_id: 79',
    );

$headersstream = array (
    'Accept-Encoding: gzip',
    'BCOV-POLICY: BCpkADawqM0jq6G249iAzAlufgb4uP4YYQ8IB8Dq0p418RNx066MMJer-Mkeh5eC9jcEt2qKQf-XO8fUiodPztZQZ1To30mXaBD6OFqOHLLBBuDSHpDLGXpMIBHesyzOOLVfri9aVnHXLCth',
    'Connection: Keep-Alive',
    'Host: edge.api.brightcove.com',
    'User-Agent: Dalvik/2.1.0 (Linux; U; Android 13; sdk_gphone64_x86_64 Build/TE1A.220922.010)'
);
    $reqGet_Data = curl_init();
    curl_setopt($reqGet_Data, CURLOPT_URL, "https://api.watchit.com/api/home/categories");
    curl_setopt($reqGet_Data, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($reqGet_Data, CURLOPT_HTTPHEADER, $headers);
    $ret_Data = curl_exec($reqGet_Data);
    $GetDate = json_decode($ret_Data, true);
    if (isset($GetDate) && is_array($GetDate)) {
        foreach ($GetDate as $item){
            $cat_name = $item['cat_name'];
            // echo $cat_name . '<br>';
            if (isset($item['items']) && is_array($item['items'])) {
                foreach ($item['items'] as $subItem) {
                    if (isset($subItem['name'])) {
                        $id = $subItem['id'];
                        $name = $subItem['name'];
                        /* Find series */
                        $reqGet_series = curl_init();
                        curl_setopt($reqGet_series, CURLOPT_URL, "https://api.watchit.com/api/series?series_id=$id");
                        curl_setopt($reqGet_series, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($reqGet_series, CURLOPT_HTTPHEADER, $headers);
                        $ret_Data_series = curl_exec($reqGet_series);
                        $GetDate_series = json_decode($ret_Data_series, true);
                        if (isset($GetDate_series['season_id'])) {
                            $name = $GetDate_series['name'];
                            $season_id = $GetDate_series['season_id'];
                            /* Find series */
                            /* Find episodes */
                            $reqGet_episodes = curl_init();
                            curl_setopt($reqGet_episodes, CURLOPT_URL, "https://api.watchit.com/api/series/seasons/episodes?series_id=$id&season_id=$season_id");
                            curl_setopt($reqGet_episodes, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($reqGet_episodes, CURLOPT_HTTPHEADER, $headers);
                            $ret_episodes = curl_exec($reqGet_episodes);
                            $GetDate_episodes = json_decode($ret_episodes, true);
                            foreach ($GetDate_episodes as $episodesItem) {
                                $episode_number = $episodesItem['episode_number'];
                                $asset_id = $episodesItem['asset_id'];
                                // echo ($cat_name), '=====>', ($name), '<br>', ($episode_number), '<br>', ($asset_id), '<br>';}
                            /* Find episodes */
                            /* Get madiklink */
                            $reqGet_madiklink = curl_init();
                            curl_setopt($reqGet_madiklink, CURLOPT_URL, "https://edge.api.brightcove.com/playback/v1/accounts/6057955906001/videos/ref%3A$asset_id");
                            curl_setopt($reqGet_madiklink, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($reqGet_madiklink, CURLOPT_HTTPHEADER, $headersstream);
                            $ret_madiklink = curl_exec($reqGet_madiklink);
                            $GetDate_madiklink = json_decode($ret_madiklink, true);
                            $srcm3u8 = $GetDate_madiklink ['sources'][0]['src'];
                            $srcmpd = $GetDate_madiklink ['sources'][1]['src'];
                            echo ($cat_name), '=====>', ($name), '<br>', ($episode_number), '<br>', ($asset_id), '<br>', ($srcm3u8), '<br>', ($srcmpd), '<br>';
                            };
                            echo '======================================================================================================================== <br>';
                            }
                            /* Get madiklink */
                        }
                    }
                }
            }
        }
    else {
        echo "حدث خطأ أثناء جلب البيانات. الرجاء المحاولة مرة أخرى لاحق.";
    }
?>