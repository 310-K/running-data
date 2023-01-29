<?php
// 登録ユーザーのリストを持ってきたい→users-selectのコピペ

// DB接続
require_once('funcs.php');
$pdo = db_conn();

// SQL文を用意（SELECT）
$stmt = $pdo->prepare("SELECT * FROM user_list");

// 実行
$status = $stmt->execute();

// データ表示
$view = "";
if($status==false){
    // SQL実行時にエラーがある場合
    $error = $stmt->errorInfo();
    exit("ErrorMassage:".$error[2]);
}else{
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
      $kanri ="";
      if($result['kanri_flg']==0){
        // ユーザーのみを表示
        // <option value="1">One</option>
          $view .= "<option value='".$result['userId']."'>";
          $view .= $result['name'];
          $view .= "</option>";
      }
    }
}
?>


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

<form class="mx-auto" style="max-width: 70vw;" method="POST" action="insert.php">
  <div class="mb-3">
    <label class="form-label">名前</label>
    <select class="form-select" aria-label="Default select example" id="name" name="userId" style="max-width: 20rem;" required>
      <option selected>ユーザー選択</option>
      <?= $view?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">練習日</label>
    <input type="date" class="form-control" id="runDate" name="runDate" style="max-width: 15rem;" required>
  </div>
  <div class="mb-3">
    <label class="form-label">走行距離</label>
    <div class="input-group" style="max-width: 15rem;">
        <input type="number" step="0.1" class="form-control" id="distance" name="distance" placeholder="0.0" required>
        <span class="input-group-text">km</span>
    </div>
  </div>
  <div class="mb-3">
    <label class="form-label">コメント</label>
    <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="調子、怪我の様子など" style="resize: none;"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">送信</button>
</form>


</main>    
<footer>



</footer>


<!-- jquery -->
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


</body>
</html>