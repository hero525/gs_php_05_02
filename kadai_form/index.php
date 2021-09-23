<?php

$winfile="win.txt";
$losefile="lose.txt";
$drawfile="draw.txt";
$score0="score0.txt";
$score1="score1.txt";
$score2="score2.txt";
$score3="score3.txt";
$score4="score4.txt";
$score5="score5more.txt";

function h($s){
  return htmlspecialchars($s,ENT_QUOTES,"UTF-8");
}

function rate($a,$b,$c){
  return round($a/($a+$b+$c)*100);
}

if ($_SERVER['REQUEST_METHOD']=='POST'){

  if (isset($_POST["getscore"]) && isset($_POST["lostscore"])){
    $name= trim($_POST['name']);
    $name = ($name==='')? '名無し':$name;
    $name = str_replace("\t", " ",$name);

    $comment= trim($_POST['comment']);
    $comment = str_replace("\t", " ",$comment);

    $getScore= $_POST["getscore"];
    $lostScore= $_POST["lostscore"];
    $postedAt = date('Y/m/d H:i:s');
  }else{
    $getScore= "";
    $lostScore= "";
  }

  if ($getScore!=="" && $lostScore!==""){

    $newData = $name . "\t" . $getScore . "\t".$lostScore ."\t".$comment ."\t".$postedAt . "\n";
    
    if ($getScore > $lostScore){
      $fp = fopen($winfile, 'a');
      fwrite($fp, $newData);
      fclose($fp);
    }elseif($getScore < $lostScore){
      $fp = fopen($losefile, 'a');
      fwrite($fp, $newData);
      fclose($fp);
    }elseif((int)$getScore === (int)$lostScore){
      $fp = fopen($drawfile, 'a');
      fwrite($fp, $newData);
      fclose($fp);
    }

    //得点によって該当ファイルに0を記入
      if ((int)$getScore===0){
        $fp = fopen($score0, 'a');
        fwrite($fp, 0);
        fclose($fp);
      }elseif($getScore==1){
        $fp = fopen($score1, 'a');
        fwrite($fp, 0);
        fclose($fp);
      }elseif($getScore==2){
        $fp = fopen($score2, 'a');
        fwrite($fp, 0);
        fclose($fp);
      }elseif($getScore==3){
        $fp = fopen($score3, 'a');
        fwrite($fp, 0);
        fclose($fp);
      }elseif($getScore==4){
        $fp = fopen($score4, 'a');
        fwrite($fp, 0);
        fclose($fp);
      }elseif($getScore>=5){
        $fp = fopen($score5more, 'a');
        fwrite($fp, 0);
        fclose($fp);
      }

      //失点によって該当ファイルに1を記入
      if ((int)$lostScore===0){
        $fp = fopen($score0, 'a');
        fwrite($fp, 1);
        fclose($fp);
      }elseif($lostScore==1){
        $fp = fopen($score1, 'a');
        fwrite($fp, 1);
        fclose($fp);
      }elseif($lostScore==2){
        $fp = fopen($score2, 'a');
        fwrite($fp, 1);
        fclose($fp);
      }elseif($lostScore==3){
        $fp = fopen($score3, 'a');
        fwrite($fp, 1);
        fclose($fp);
      }elseif($lostScore==4){
        $fp = fopen($score4, 'a');
        fwrite($fp, 1);
        fclose($fp);
      }elseif($lostScore>=5){
        $fp = fopen($score5more, 'a');
        fwrite($fp, 1);
        fclose($fp);
      }
  }
}

  $winPosts =file($winfile,FILE_IGNORE_NEW_LINES);
  $winPosts =array_reverse($winPosts);
  $losePosts =file($losefile,FILE_IGNORE_NEW_LINES);
  $losePosts =array_reverse($losePosts);
  $drawPosts =file($drawfile,FILE_IGNORE_NEW_LINES);
  $drawPosts =array_reverse($drawPosts); 

  $find0 = '/0/';
  $find1 = '/1/';

  $get_score_vote0 = preg_match_all($find0,file("score0.txt")[0]);
  $get_score_vote1 = preg_match_all($find0,file("score1.txt")[0]);
  $get_score_vote2 = preg_match_all($find0,file("score2.txt")[0]);
  $get_score_vote3 = preg_match_all($find0,file("score3.txt")[0]);
  $get_score_vote4 = preg_match_all($find0,file("score4.txt")[0]);
  $get_score_vote5 = preg_match_all($find0,file("score5more.txt")[0]);
  $lost_score_vote0 = preg_match_all($find1,file("score0.txt")[0]);
  $lost_score_vote1 = preg_match_all($find1,file("score1.txt")[0]);
  $lost_score_vote2 = preg_match_all($find1,file("score2.txt")[0]);
  $lost_score_vote3 = preg_match_all($find1,file("score3.txt")[0]);
  $lost_score_vote4 = preg_match_all($find1,file("score4.txt")[0]);
  $lost_score_vote5 = preg_match_all($find1,file("score5more.txt")[0]);

  //ファイルの中身を削除
  function deleateContent($f){
    $fp = fopen($f, "w");
    fclose($fp);
  }
  if(isset($_POST['reset'])) {
      deleateContent($score0);
      deleateContent($score1);
      deleateContent($score2);
      deleateContent($score3);
      deleateContent($score4);
      deleateContent($score5more);
      deleateContent($winfile); 
      deleateContent($losefile);
      deleateContent($drawfile);
  }
?>

<!-- html&css -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>サッカースコア予想App</title>
</head>

<body>
  <h1 class="title">勝敗＆スコアをあてよう！</h1>
  <h2 class="subtitle">Let's 投票</h2>
  <div class="form">
    <form action="" method ="post">
      名前：<input type="text" name="name" autocomplete="on">
      <br>
      得点：<input type="number" name="getscore" min="0" max="20">
      <br>
      失点：<input type="number" name="lostscore" min="0" max="20">
      <br>
      応援：<input name="comment" size=30 placeholder="コメントしよう！">
      <br>
      <input type="submit" value="投票">
    </form>
  </div>

<h2 class="voteall">投票結果</h2>
<div class="allcharts">
  <div class="win_lose_rate chart_wrapper">
    <p class="vote_subtitle">勝敗予想</p>
    <div class="vote_none1">無投票</div>
    <div class="result_figure figures" style="width:330px">
      <canvas id="mychart1" class="mychart1"></canvas>
    </div>
  </div>

  <div class="getScore_rate chart_wrapper">
    <p class="vote_subtitle">得点予想</p>
    <div class="vote_none2">無投票</div>
    <div class="getScore_figure figures" style="width:330px">
      <canvas id="mychart2" class="mychart2"></canvas>
    </div>
  </div>

  <div class="lostScore_rate chart_wrapper">
    <p class="vote_subtitle">失点予想</p>
    <div class="vote_none3">無投票</div>
    <div class="lostScore_figure figures" style="width:330px">
      <canvas id="mychart3" class="mychart3"></canvas>
    </div>
  </div>

</div>

<h2 class="commentall">投票履歴</h2>
  <div class="vote_all">
      <div class="win_vote comment">
        <p class="vote_title">勝ち予想【いま<?php echo count($winPosts);?>件】</p>
        <ul class="win_vote_content">
          <?php if (count($winPosts)!==0): ?>
          <?php foreach($winPosts as $post):?>
          <?php list($name, $getScore, $lostScore, $comment)= explode("\t",$post); ?>
          <li><?php echo h($getScore);?> vs <?php echo h($lostScore);?> - <?php echo h($comment);?> (<?php echo h($name); ?>) </li>
          <?php endforeach; ?>
          <?php else: ?>
          <div class="vote_none11">無投票</div>
          <?php endif ?>
        </ul>
      </div>
      <div class="lose_vote comment">
        <p class="vote_title">負け予想【いま<?php echo count($losePosts);?>件】</p>
        <ul class="lose_vote_content">
          <?php if (count($losePosts)!==0): ?>
          <?php foreach($losePosts as $post):?>
          <?php list($name, $getScore, $lostScore, $comment)= explode("\t",$post); ?>
          <li><?php echo h($getScore);?> vs <?php echo h($lostScore);?> - <?php echo h($comment);?> (<?php echo h($name); ?>) </li>
          <?php endforeach; ?>
          <?php else: ?>
          <div class="vote_none11">無投票</div>
          <?php endif ?>
        </ul>
      </div>
      <div class="draw_vote comment comment">
        <p class="vote_title">ドロー予想【いま<?php echo count($drawPosts);?>件】</p>
        <ul class="draw_vote_content">
        <?php if (count($drawPosts)!==0 && isset($drawPosts)):?>
          <?php foreach($drawPosts as $post):?>
          <?php list($name, $getScore, $lostScore, $comment)= explode("\t",$post); ?>
          <li><?php echo h($getScore);?> vs <?php echo h($lostScore);?> - <?php echo h($comment);?> (<?php echo h($name); ?>) </li>
          <?php endforeach; ?>
          <?php else: ?>
          <div class="vote_none11">無投票</div>
          <?php endif ?>
        </ul>
      </div>
  </div>

  <form  method="post">
    <button type="submit" name="reset">リセット</button>
  </form>


  <!-- 別途スタイルシート読み込めなかったのでココに記載 泣-->
  <style>
  body{
    color: black;
    background-color: lemonchiffon;
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
  }
  
  .title{
    margin-bottom: 20px;
    text-align: center;
    text-shadow: 1px 2px 3px #808080;
  }
  .titlemessage{
    margin-top: 0px;
    margin-bottom: 10px;
  }
  .subtitle{
    text-align: center;
    color: crimson;
  }

  .form{
    padding: 0.5em 1em;
    margin: 2em 0;
    color: #5989cf;
    background: #c6e4ff;
    border-bottom: solid 6px #aac5de;
    border-radius: 9px;
    font-weight: bolder;
  }

  .voteall{
    text-align: center;
    color: crimson;
  }
  .vote_all{
    display: flex;
    margin-top: 0px;
  }
  .vote_title{
    font-weight: bold;
    text-align: center;
  }
  .vote_subtitle{
    font-weight: bolder;
  }
  .commentall{
    margin-bottom: 10px;
    text-align: center;
    color: crimson;
  }
  .comment{
    width: 33%;
  }
  .vote_none1{
    text-align: center;
    color: gray;
  }
  .vote_none2{
    text-align: center;
    color: gray;
  }
  .vote_none3{
    text-align: center;
    color: gray;
  }
  .vote_none11{
    text-align: center;
    color: gray;
  }

  .allcharts{
    display: flex;
    margin-bottom: 50px;
  }
  .chart_wrapper{
    width:33%;
    text-align: center;
    padding: 0 auto;
  }
  .figures{
    margin: auto;
  }
  </style>

  <!--  -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js" integrity="sha512-Wt1bJGtlnMtGP0dqNFH1xlkLBNpEodaiQ8ZN5JLA5wpc1sUlk/O5uuOMNgvzddzkpvZ9GLyYNa8w2s7rqiTk5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    // 図を表示する場合
    <?php if(count($winPosts) !==0 || count($losePosts) !==0 || count($drawPosts) !==0): ?>
      document.getElementById("vote_none1").style.display ="none";
      document.getElementById("vote_none2").style.display ="none";
      document.getElementById("vote_none3").style.display ="none";

      var ctx = document.getElementById('mychart1');
      var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['勝ち', 'ドロー', '負け'],
          datasets:[{
            data: [<?php echo rate(count($winPosts),count($losePosts),count($drawPosts));?>, <?php echo rate(count($drawPosts),count($winPosts),count($losePosts));?>, <?php echo rate(count($losePosts),count($drawPosts),count($winPosts));?>],
            backgroundColor: ['ff4500', '#484', '#48f'],
            weight: 100,
          }],
        },
      });
      var ctx = document.getElementById('mychart2');
      var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['0点', '1点', '2点', '3点', '4点','5点以上'],
          datasets: [{
            data: [<?php echo $get_score_vote0 ?>,<?php echo $get_score_vote1 ?>,<?php echo $get_score_vote2 ?>,<?php echo $get_score_vote3 ?>,<?php echo $get_score_vote4 ?>,<?php echo $get_score_vote5 ?>,<?php echo $get_score_vote6 ?>,<?php echo $get_score_vote7more ?>],
            backgroundColor: ['#e6b8c2','#f88','#e68a9e','#e65c7a','#e62e56','#ff0037','#cc002c','#990021'],
            weight: 100,
          }],
        },
      });
      var ctx = document.getElementById('mychart3');
      var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['0点', '1点', '2点', '3点', '4点','5点以上'],
          datasets: [{
            data: [<?php echo $lost_score_vote0 ?>,<?php echo $lost_score_vote1 ?>,<?php echo $lost_score_vote2 ?>,<?php echo $lost_score_vote3 ?>,<?php echo $lost_score_vote4 ?>,<?php echo $lost_score_vote5 ?>,<?php echo $lost_score_vote6 ?>,<?php echo $lost_score_vote7more ?>],
            backgroundColor: ['#abcbd9', '#82bdd9', '#57b0d9','#2ba2d9','#0095d9','#00aeff','#008bcc','#006999'],
            weight: 100,
          }],
        },
      });
    //図を非表示の場合
    <?php else: ?>  
      document.getElementById("vote_none").style.display ="block";
    <?php endif ?>
  </script>
</body>
</html>
