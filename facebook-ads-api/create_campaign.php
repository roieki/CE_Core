<?php
/**
 * Copyright 2010 Facebook, Inc.
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require './inc/includes.php';

function splitAndClean($s, $sep = ',') {
  return array_unique(array_map('trim', explode($sep, $s)));
}

function getIdNameArray($s, $sep = ',') {
  $array = splitAndClean($s, $sep);
  if (!$array) {
    return null;
  }

  $result = array();
  foreach ($array as $v) {
    if (is_numeric($v)) {
      $result []= array('id' => $v);
    } else {
      $result []= array('name' => $v);
    }
  }

  return $result;
}

$error_message = '';
$targeting_stats = '';

$allLocales = getLocales();

$name = 'Campaign';
$budget = 10.00;
$error_message = null;

$act_id = 'act_' . $account_id;

if (!empty($_POST['submit'])) {
  $name = $_POST['name'];
  $budget = $_POST['budget'];

  $campaign_spec = array(
    'name'                => $name,
    'daily_budget'        => round($budget * 100),
  );

  try {
    $result = $facebook->api(
                $act_id . '/adCampaigns',
                'post',
                $campaign_spec);
    if (!empty($result['id'])) {
      echo '<html><META HTTP-EQUIV=REFRESH CONTENT="1; URL=index.php"></html>';
      return;
    } else {
      $error_message = var_export($result, true);
    }
  } catch (Exception $e) {
    $error_message = $e->getMessage();
  }
}

if ($error_message) {
  echo 'Operation failed<br><br>';
  echo 'Request: <pre>' . var_export($campaign_spec, true) . '</pre><br>';
  if ($result) {
    echo 'Response: <pre>' . var_export($result, true) . '</pre>';
  }
  echo 'Error Message: <pre>' . $error_message . '</pre><br>';
}

print_header($facebook, $me, $user);

echo '
<form action="create_campaign.php" method="post">
<table>
<tr><td colspan=3 style="background-color: #aaaaaa;"><h2>Campaign</h2></td></tr>
<tr><td>Campaign Name</td><td><input type=text name="name" value="' . $name . '"></td></tr>
<tr><td>Daily Budget</td><td><input type=text name="budget" value="' . $budget . '"></td></tr>
<tr><td colspan=2><input type=submit name="submit" value="Save"></td></tr>
</table>
</form>
</body></html>';
