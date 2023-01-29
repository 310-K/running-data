<?php
//XSS対応（ echoする場所で使用！）
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

//DB接続関数：db_conn() 
//※関数を作成し、内容をreturnさせる。
//※ DBname等、今回の授業に合わせる。
function db_conn(){
    try {
      $db_name = "runner_tool";    //データベース名
      $db_id   = "root";      //アカウント名
      $db_pw   = "root";      //パスワード：XAMPPはパスワードなしMAMPのパスワードはroot
      $db_host = "localhost"; //DBホスト
      $db_port = "8889"; //XAMPPの管理画面からport番号確認
      $pdo = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host.';port='.$db_port.'', $db_id, $db_pw);
      return $pdo;//ここを追加！！
    } catch (PDOException $e) {
        exit('DB Connection Error:' . $e->getMessage());
    }
}

//リダイレクト関数: redirect($file_name)
function redirect($file_name){
    header('Location: '.$file_name);
}

// userIdからユーザーの名前を呼び出す関数：userName
function userName($userId){
    $pdo = db_conn();
    $stmt = $pdo->prepare("SELECT name FROM `user_list` WHERE userId=$userId");
    // 実行
    $status = $stmt->execute();
    // データ取得
    if($status==false){
    // SQL実行時にエラーがある場合
        $error = $stmt->errorInfo();
        exit("ErrorMassage:".$error[2]);
    }else{
        while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
            return $result['name'];
        }
    }
}

// 各ユーザーの走行距離の合計値を算出する関数：sumDistance
function sumDistance($userId){
    // SQL文を用意（SELECT）
    $pdo = db_conn();
    $stmt = $pdo->prepare("SELECT * FROM running_distance WHERE userId=$userId");
    // 実行
    $status = $stmt->execute();
    // データ表示
    $distance = [];
    $sum = "";
    if($status==false){
        // SQL実行時にエラーがある場合
        $error = $stmt->errorInfo();
        exit("ErrorMassage:".$error[2]);
    }else{
        while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
            $distance[] = $result['distance'];
        }
    }
    $sum = array_sum($distance);
    return $sum;
}
    