<?php
try {
 $db = new PDO('pgsql:dbname=offshoa_db;host=127.0.0.1;port=5432;', 'yamamotohiroto', '');
} catch (PDOException $e) {
 echo $e . 'エラーです';
}

//alter table bbs_thread add foreign key (creator_id) references accounts_user(id);

// テーブル作成のSQLを作成
$sql = 'CREATE TABLE likes (
 id SERIAL NOT NULL,
 post_id INT,
 user_id INT,
 foreign key (post_id) references posts(id) ON DELETE CASCADE ON Update CASCADE,
 foreign key (user_id) references users(id) ON DELETE CASCADE ON Update CASCADE,
 created_at TIMESTAMP,
 updated_at TIMESTAMP,
 PRIMARY KEY (id)
)';
// バルクインサートで値を挿入
$insert_sql = 'INSERT INTO likes values (1,2,3),(2,3,1),(3,10,11)';
// SQLを実行
$res = $db->query($sql);
$inser_res = $db->query($insert_sql);
