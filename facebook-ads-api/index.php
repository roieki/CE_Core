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
$page_time_start = time();
print_header($facebook, $me, $user);

if(!$account_id) {
  echo "Please create an ads account first by going to:
  <a href='http://www.facebook.com/ads'>facebook.com/ads>
  </a>";
  exit();
}

echo '
  <form method=post action="index.php" id="form">
    <input type=hidden name="action" id="action">
    <input type=hidden name="adgroup_id" id="adgroup_id">
    <input type=hidden name="campaign_id" id="campaign_id">
    <input type=hidden name="max_bid" id="max_bid">
    <input type=hidden name="ad_name" id="ad_name">
    <input type=hidden name="status" id="status">
  </form>';

$today = strtotime('today');
$time_start = $today;
$time_stop = strtotime('+1 day', $today);

if (!empty($_POST["action"])) {
  $result = null;
  $success = false;
  switch ($_POST["action"]) {
    case 'pauseCampaign':
      $result = $facebook->api(
                  $_POST["campaign_id"], 
                  'post', 
                  array('campaign_status' => CampaignStatus::PAUSED));
      if ($result) {
        $success = true;
      }
      break;
    case 'resumeCampaign':
      $result = $facebook->api(
                  $_POST["campaign_id"], 
                  'post', 
                   array('campaign_status' => CampaignStatus::ACTIVE));
      if ($result) {
        $success = true;
      }
      break;
    case 'deleteCampaign':
      $result = $facebook->api(
                  $_POST["campaign_id"], 
                  'post', 
                   array('campaign_status' => CampaignStatus::DELETED));
      if ($result) {
        $success = true;
      }
      break;
    case 'pauseAdGroup':
      $result = $facebook->api(
                  $_POST["adgroup_id"], 
                  'post', 
                   array('adgroup_status' => AdGroupStatus::ADGROUP_PAUSED));
      if ($result) {
        $success = true;
      }
      break;
    case 'resumeAdGroup':
      $result = $facebook->api(
                  $_POST["adgroup_id"], 
                  'post', 
                   array('adgroup_status' => AdGroupStatus::ACTIVE));
      if ($result) {
        $success = true;
      }
      break;
    case 'deleteAdGroup':
      $result = $facebook->api(
                  $_POST["adgroup_id"], 
                  'post', 
                   array('adgroup_status' => AdGroupStatus::DELETED));
      if ($result) {
        $success = true;
      }
      break;
    case 'updateAdGroup':
      $result = $facebook->api(
                  $_POST["adgroup_id"], 
                  'post', 
                   array('name'    => $_POST['ad_name'],
                         'max_bid' => $_POST['max_bid'] * 100));
      if ($result) {
        $success = true;
      }
      break;
  }

  if (!$success) {
    echo 'Operation failed.<br><pre>' . "\n";
    echo var_export($result, true);
    echo '</pre>';
  }
}

try {
  $act_id = 'act_' . $account_id;

  $campaigns = $facebook->api(
                 $act_id . '/adCampaigns',
                 'get', 
                 array('campaign_ids'    => array(), 
                       'include_deleted' => false));

  $all_adgroups = $facebook->api(
                    $act_id . '/adgroups',
                    'get',
                    array('campaign_ids'    => array(),
                          'adgroup_ids'     => array(),
                          'include_deleted' => false));

  $adgroup_stats = $facebook->api(
                     $act_id . '/adgroupstats',
                     'get',
                     array('campaign_ids'    => array(),
                           'adgroup_ids'     => array(),
                           'include_deleted' => false,
                           'time_ranges' =>
                             array(array('time_start' => $time_start,
                                         'time_stop' => $time_stop))));

  $all_targeting = $facebook->api(array(
                     'method' => 'ads.getAdGroupTargeting',
                     'account_id' => $account_id,
                     'campaign_ids' => array(),
                     'adgroup_ids' => array(),
                     'include_deleted' => false));

  $campaign_stats = $facebook->api(
                      $act_id . '/adCampaignstats',
                      'get',
                      array('campaign_ids'    => array(),
                            'adgroup_ids'     => array(),
                            'include_deleted' => false,
                            'time_ranges' =>
                              array(array('time_start' => $time_start,
                                          'time_stop' => $time_stop))));

} catch (Exception $e) {
  echo '<pre>Exception: ' . $e->getMessage() . var_export($e, true).'</pre>';
  echo '</body></html>';
  return;
}

echo '<div class="ads_manager">
<table>';
foreach ($campaigns['data'] as $campaign) {
  $campaign_id = trim(sprintf("%20.0f", $campaign['campaign_id']));
  echo '<tr style="background-color:#aaa;">' .
        '<td>';
  if ($campaign['campaign_status'] == CampaignStatus::ACTIVE) {
    echo '<input type=button onclick="pauseCampaign(' . $campaign_id . ')" value="Pause">';
  } else if ($campaign['campaign_status'] == CampaignStatus::PAUSED) {
    echo '<input type=button onclick="resumeCampaign(' . $campaign_id . ')" value="Resume">';
  }
  echo '<input type=button onclick="deleteCampaign(' . $campaign_id . ')" value="Delete"></td>';
  echo '<td>Campaign ' . $campaign_id . '</td>' .
        '<td>Campaign Name: ' . $campaign['name'] . '</td>' .
        '<td>from ' . getDateString($campaign['start_time']) . ' ' .
        'to ' . ($campaign['end_time'] ? getDateString($campaign['end_time']) : '') . '</td>' .
        '<td>Daily Budget: ' . (isset($campaign['daily_budget']) ? getMoneyString($campaign['daily_budget']) : 'N/A') . '</td>' .
        '<td>Lifetime Budget: ' . (isset($campaign['lifetime_budget']) ? getMoneyString($campaign['lifetime_budget']) : 'N/A') . '</td>' .
        '<td>Status: ' . getStatusString($campaign['campaign_status']) . '</td>';
  $stat = getCampaignStat($campaign_stats, $campaign_id);
  if ($stat) {
    $impressions = 0;
    $clicks = 0;
    $spend = 0;
    if ($stat) {
      $impressions = $stat['impressions'];
      $clicks = $stat['clicks'];
      $spend = $stat['spent'];
    }
    echo '<td>Impressions: ' . $impressions . ', ' .
         'Clicks: ' . $clicks . ', ' .
         'Cost: ' . getMoneyString($spend) . '</td>';
  }
  echo '</tr>';

  $adgroups = array();
  foreach ($all_adgroups['data'] as $adgroup) {
    $adgroup_campaign_id = trim(sprintf("%20.0f", $adgroup['campaign_id']));
    $adgroup_id = trim(sprintf("%20.0f", $adgroup['adgroup_id']));

    if ($adgroup_campaign_id == $campaign_id) {
      $adgroups []= $adgroup;
    }
  }

  if (!$adgroups) {
    echo '<tr><td colspan=10>No ads</td></tr>';
  } else {
    echo '<tr><td colspan=10><table class="datakit_table"><tr>' .
          '<th></th>' .
          '<th>Ad ID</th>' .
          '<th>Name</th>' .
          '<th>Status</th>' .
          '<th>Max Bid ($)</th>' .
          '<th>Type</th>' .
          '<th>Targeting</th>' .
          '<th>Creative</th>' .
          '<th>Impressions</th>' .
          '<th>Clicks</th>' .
          '<th>Cost</th>' .
          '</tr>';

    foreach ($adgroups as $adgroup) {
      $adgroup_id = trim(sprintf("%20.0f", $adgroup['adgroup_id']));

      $adgroup_i++;
      
      $bid_type = getBidTypeString($adgroup['bid_type']);
      $stat = getAdgroupStat($adgroup_stats, $adgroup_id);

      $impressions = 0;
      $clicks = 0;
      $spend = 0;
      if ($stat) {
        $impressions = $stat['impressions'];
        $clicks = $stat['clicks'];
        $spend = $stat['spent'];
      }

      echo '<tr><td>';
      if ($adgroup['adgroup_status'] == AdGroupStatus::ACTIVE) {
        echo '<input type=button onclick="pauseAdGroup(' . $adgroup_id . ')" value="Pause">';
      } else if ($adgroup['adgroup_status'] == AdGroupStatus::ADGROUP_PAUSED) {
        echo '<input type=button onclick="resumeAdGroup(' . $adgroup_id . ')" value="Resume">';
      } else if ($adgroup['adgroup_status'] == AdGroupStatus::CAMPAIGN_PAUSED) {
        echo '<input type=button onclick="pauseAdGroup(' . $adgroup_id . ')" value="Pause*">';
      }

      echo '<input type=button onclick="deleteAdGroup(' . $adgroup_id . ')" value="Delete"></td>';
      echo '<td>' . $adgroup_id . '</td>' .
            '<td>' . $adgroup['name'] . '</td>' .
            '<td style="max-width: 100px;">' . getStatusString($adgroup['adgroup_status']) . '</td>' .
            '<td>' . getMoneyString($adgroup['max_bid']) . '</td>' .
            '<td>' . $bid_type . '</td>' .
            '<td><pre>';

      $targeting = $adgroup['targeting'];
      if ($targeting) {
        foreach ($targeting as $k => $v) {
          echo $k . ' ' . var_export($v, true) . "\n";
        }
      }

      echo '</pre></td>' .
            '<td width=100px>';

      $creative_ids = implode(',', array_map(
                        function($id) { return trim(sprintf("%20.0f", $id)); },
                        $adgroup['creative_ids']));
      try {
        $creatives = $facebook->api('', 'get', array('ids' => $creative_ids));
      } catch (Exception $e) {
      }
      foreach ($creatives as $creative) {
        $creative_title = "";
        if (!empty($creative['title'])) {
          $creative_title = $creative['title'];
        } else if (!empty($creative['name'])) {
          $creative_title = $creative['name'];
        }
        echo '<a class="creative" href="' . $creative['link_url'] . '">' .
                '<b>' . $creative_title . '</b>' .
             '</a> ';
        if (!empty($creative['image_url'])) {
          echo '<img src="' . $creative['image_url'] . '">';
        }
        echo $creative['body'];
      }

      echo '</td>' .
           '<td>' . $impressions . '</td>' .
           '<td>' . $clicks . '</td>' .
           '<td>' . getMoneyString($spend) . '</td>' .
           '<td>' .
            'Ad Name: <input type=text id="ad_' . $adgroup_id . '_name" value="' . $adgroup['name'] . '"><br>' .
            'Max Bid: <input type=text id="ad_' . $adgroup_id . '_bid" value="' . getMoneyString($adgroup['max_bid']) . '">' .
            '<input type=button value="Update" onclick="updateAdGroup(' . $adgroup_id . ')">' .
            '</td>' .
           '</tr>';
    }
    echo '</table></td></tr>';
  }
}
echo '</table></div>';

echo '<br><br>Number of campaigns: ' . $campaigns['count'];
echo '<br><br>Number of ad groups: ' . $all_adgroups['count'];
$render_time = time() - $page_time_start;
echo '<br><br>Page Render Time: '
     . ($render_time == 1 ? '1 second.' : $render_time . ' seconds.');
echo '</body></html>';
