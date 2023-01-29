<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
<header>
<nav class="navbar navbar-expand-sm navbar-light mb-3 bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Running Log</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="users-index.php">ユーザー登録</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="graph.php">走行距離グラフ</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                管理者向け
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="select.php">Log-List</a></li>
                <li><a class="dropdown-item" href="users-select.php">Users List</a></li>
              </ul>
            </li>
        </ul>
        </div>
    </div>
    </nav>
</header>
<main>

<canvas id="barGraph"></canvas>

</main>    
<footer>

</footer>

<?php
// ラベルの準備
    // ユーザーリストからユーザーを取得
        // DB接続
        require_once('funcs.php');
        $pdo = db_conn();

        // SQL文を用意（SELECT）
        $stmt = $pdo->prepare("SELECT * FROM user_list");

        // 実行
        $status = $stmt->execute();

        // データ表示
        $users = [];
        if($status==false){
            // SQL実行時にエラーがある場合
            $error = $stmt->errorInfo();
            exit("ErrorMassage:".$error[2]);
        }else{
            while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
                $users[] = $result['name'];
            }
        }
    // ラベルをJSONに変換
    $x  = $users;
    $jx = json_encode($x);

// データの準備
    // ランニングの記録から、userIDごとに距離を合計する
    // ユーザーごとの距離を全員分、配列に突っ込む
    $data = [];

    // 全ユーザーIDを取得
        // SQL文を用意（SELECT）
        $stmt = $pdo->prepare("SELECT * FROM user_list");
        // 実行
        $status = $stmt->execute();
        // データ表示
        if($status==false){
            // SQL実行時にエラーがある場合
            $error = $stmt->errorInfo();
            exit("ErrorMassage:".$error[2]);
        }else{
            while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
                $sum = sumDistance($result['userId']);
                $data[] = $sum;
            }
        }

    // ラベルをJSONに変換
    $y  = $data;
    $jy = json_encode($y);

?>


<!-- jquery -->
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = $("#barGraph");
let x = JSON.parse('<?= $jx?>');
let y = JSON.parse('<?= $jy?>');

const chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: x,
    datasets: [{
      label: 'My First dataset',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: y,
    }]
  },
  options: {
    indexAxis: 'y'
  }
});


</script>

</body>
</html>