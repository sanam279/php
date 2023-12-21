<?php

$url = 'https://gateway.qg1.apps.qualys.eu/rest/2.0/search/am/asset?filter=json&pageSize=300&includeFields=address,dnsName,assetName,operatingSystem';

$ch = curl_init($url);

$curl_header = array(
    'Content-Type: application/json',
    'Authorization: Bearer eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJrcG1ncC1kYTkiLCJpc3MiOiJxYXMiLCJwb3J0YWxVc2VySWQiOjE2NTM5MDMwMSwiY3VzdG9tZXJVdWlkIjoiM2RmNzYzY2UtZjc1NS01YWY2LTgyYzEtOTJjZTkwZDNmMmZjIiwibW9kdWxlc0FsbG93ZWQiOlsiQVNTRVQgSU5WRU5UT1JZIiwiRUMyIiwiS0JYIiwiUkVQT1JUIENFTlRFUiIsIkFETUlOIiwiQUdFTlRfQ0FQUyIsIkNFUlRWSUVXIiwiUVdFQl9WTSIsIkNPTlRJTlVPVVMgTU9OSVRPUklORyIsIkZSRUUgQUdFTlQiLCJQQyBBR0VOVCIsIlBDIFNDQU5ORVIiLCJRQ0FQUyIsIlNFQ1VSRUNPTkZJRyIsIlVEIiwiVk0iLCJBU1NFVF9NQU5BR0VNRU5UIiwiUFMiLCJDTE9VRFZJRVciLCJHTE9CQUxfQUlfQ01EQl9TWU5DIiwiUE0iLCJQT1JUQUwgVEFHR0lORyIsIlNDQU4gQlkgSE9TVE5BTUUiLCJTRU0iLCJDT05UQUlORVJfU0VDVVJJVFkiLCJNRFMiLCJQQyIsIlFHUyIsIlNBTUwiLCJTQ0FfQUdFTlQiLCJVTklGSUVEX0RBU0hCT0FSRCIsIlZMQU4iLCJWTSBBR0VOVCIsIklUQU0iLCJORVRXT1JLIFNVUFBPUlQiLCJQQ0kiLCJRV0VCX1BDIiwiVEhSRUFUIFBST1RFQ1QiLCJUSFJFQVRfUFJPVEVDVCIsIlZJUlRVQUwgU0NBTk5FUiIsIldBUyIsIkFQSSIsIkNBIiwiQ0VSVF9WSUVXIiwiQ00iLCJDUyIsIlBBU1NJVkVfU0NBTk5FUiIsIlZNIFNDQU5ORVIiXSwiY3VzdG9tZXJJZCI6MTQ1MjgzNywic2Vzc2lvbkV4cGlyYXRpb24iOiIyNDAiLCJqd3RFeHBpcnlNaW51dGUiOiIyNDAiLCJleHAiOjE3MDMxMDc4NTAsImlhdCI6MTcwMzA5MzQ1MCwianRpIjoiVEdUODk4NDU2LWRmWEdicmFkVGJ1V3hZbmhDbWZ4VkxlbGxRbWtLTmlNUGVvTHRSRVRMc25kT3hiaVRHWmxoY2dJanpXR1RHckwtcWFzLTY4NjZmNGNjNmMtc2JqcGciLCJsb2dpblJlc3BvbnNlIjoiU1VDQ0VTU0ZVTCIsImF1dGhlbnRpY2F0aW9uRGF0ZSI6WzE3MDMwOTM0NTAzMzBdLCJzdWNjZXNzZnVsQXV0aGVudGljYXRpb25IYW5kbGVycyI6WyJBdXRoSGFuZGxlciJdLCJpcEFkZHJlc3MiOiIyMjMuMjMzLjgwLjExMiIsInVzZXJ0eXBlIjoiZm8iLCJxd2ViVXNlcklkIjo5MTIyNzU4OSwiY3JlZGVudGlhbFR5cGUiOiJRVXNlcm5hbWVQYXNzd29yZENyZWRlbnRpYWwiLCJhdWQiOiJxYXMiLCJhdXRoZW50aWNhdGlvbk1ldGhvZCI6IkF1dGhIYW5kbGVyIiwidXNlclV1aWQiOiJjOWViMGMyZi0zNDU2LTZmMTMtODMzMy0yNDYwOGU1M2MwNzYiLCJzdWJzY3JpcHRpb25VdWlkIjoiM2RmNzYzY2UtZjc1NS01YWY2LTgyYzEtOTJjZTkwZDNmMmZjIiwiaXNUZ3RFeHBpcmVkIjoidHJ1ZSIsInN1YnNjcmlwdGlvbklkIjo5MDkwMDYwOX0.Z1ItofclAXdaW6SNTU9d1Ov_TtVpSQgULj71B5_kTf6KfQa0dd6XzsuaHYSGgfAK1WTgo0oldRuZatJfAbMfYg'
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