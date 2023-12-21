<?php

$url = 'https://gateway.qg1.apps.qualys.eu/rest/2.0/search/am/asset?filter=json&pageSize=300&includeFields=address,dnsName,assetName,operatingSystem';

$ch = curl_init($url);

$curl_header = array(
    'Content-Type: application/json',
    'Authorization: Bearer <token>'
);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_header);

$response_string = curl_exec($ch);

if(curl_errno($ch))
{
    echo 'Error: '.curl_error($ch);
}
else
{
    $json_array = json_decode($response_string, TRUE);
    $json_result_string = json_encode($json_array['assetListData']['asset']);
    $json_result_string = trim($json_result_string, '[]');
    
    $file = fopen("response.json", 'w+');
    fwrite($file, $json_result_string);
    fclose($file);
    
    while($json_array['hasMore'] == 1)
    {
        $lsaid = $json_array['lastSeenAssetId'];
        
        $url = 'https://gateway.qg1.apps.qualys.eu/rest/2.0/search/am/asset?filter=json&pageSize=300&includeFields=address,dnsName,assetName,operatingSystem&lastSeenAssetId='.$lsaid;
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_header);
        
        $response_string = curl_exec($ch);
        
        $json_array = json_decode($response_string, TRUE);
        $json_result_string = json_encode($json_array['assetListData']['asset']);
        $json_result_string = trim($json_result_string, '[]');
        
        $file = fopen("response.json", 'a');
        fwrite($file, $json_result_string);
        fclose($file);
    }
}
curl_close($ch);
?>
