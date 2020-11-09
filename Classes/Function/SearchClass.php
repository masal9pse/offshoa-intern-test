<?php
require_once dirname(__FILE__) . '/../UtilClass.php';

class SearchClass extends UtilClass
{
  public function AllSearch()
  {
    if (!empty($_GET['tags'] && $_GET['search'] && $_GET['category'])) {
      list($tag_binds, $whereSql) = $this->tagWhere();
      // explodeが使えるかチェック
      //var_dump($whereSql);
      $sql = "SELECT distinct posts.*
              FROM posts
              LEFT JOIN post_category
              ON posts.id = post_category.post_id
              LEFT JOIN categories
              ON categories.id = post_category.category_id
              JOIN post_tag
              ON posts.id = post_tag.post_id
              JOIN tags
              ON post_tag.tag_id = tags.id
              WHERE  categories.category = :category
              AND tags.tag IN ($whereSql)  
              AND (posts.title like :title OR posts.detail like :detail )";

      var_dump($sql);
      $db = $this->dbConnect();
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':category', $_GET['category'], PDO::PARAM_STR);

      // タグ検索 $tag_bindsのキーと$whereSqlは同じ
      $this->tagBinds($tag_binds, $stmt);

      $stmt->bindValue(':title', "%{$_GET['search']}%", PDO::PARAM_STR);
      $stmt->bindValue(':detail', "%{$_GET['search']}%", PDO::PARAM_STR);
      $this->queryPost($stmt);
    }
  }

  // tagとカテゴリーの絞り込み検索
  public function tagCategorySearch()
  {
    if (!empty($_GET['tags'] && $_GET['category']) && empty($_GET['search'])) {

      list($tag_binds, $whereSql) = $this->tagWhere();
      var_dump($whereSql);

      $sql = "SELECT distinct posts.*
              FROM posts
                INNER JOIN post_category
                ON posts.id = post_category.post_id
                LEFT JOIN categories
                ON categories.id = post_category.category_id
                JOIN post_tag
                ON posts.id = post_tag.post_id
                JOIN tags
                ON post_tag.tag_id = tags.id
              WHERE  categories.category = :category
                AND tags.tag IN ($whereSql)";

      var_dump($sql);
      $db = $this->dbConnect();
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':category', $_GET['category'], PDO::PARAM_STR);
      // タグ検索 $tag_bindsのキーと$whereSqlは同じ
      $this->tagBinds($tag_binds, $stmt);
      $this->queryPost($stmt);
    }
  }

  public function tagTextSearch()
  {
    if (!empty($_GET['tags'] && $_GET['search']) && empty($_GET['category'])) {
      list($tag_binds, $whereSql) = $this->tagWhere();
      var_dump($whereSql);

      $sql = "SELECT distinct posts.*
              FROM posts
                INNER JOIN post_category
                ON posts.id = post_category.post_id
                LEFT JOIN categories
                ON categories.id = post_category.category_id
                JOIN post_tag
                ON posts.id = post_tag.post_id
                JOIN tags
                ON post_tag.tag_id = tags.id
                AND tags.tag IN ($whereSql)
                AND posts.title LIKE :title
                OR posts.detail LIKE :detail";

      var_dump($sql);
      $db = $this->dbConnect();
      $stmt = $db->prepare($sql);
      // タグ検索 $tag_bindsのキーと$whereSqlは同じ
      $this->tagBinds($tag_binds, $stmt);
      $stmt->bindValue(':title', '%' . $_GET["search"] . '%', PDO::PARAM_STR);
      $stmt->bindValue(':detail', '%' . $_GET["search"] . '%', PDO::PARAM_STR);
      $this->queryPost($stmt);
    }
  }

  public function tagSearch()
  {
    if (!empty($_GET['tags']) && empty($_GET['search']) && empty($_GET['category'])) {
      $first_sql = "SELECT distinct p.*
                    FROM post_tag pt, posts p, tags t
                    WHERE pt.tag_id = t.id
                      AND (t.tag IN (";

      $second_sql = "AND p.id = pt.post_id";

      list($tag_binds, $whereSql) = $this->tagWhere();

      $sql = $first_sql . $whereSql . '))' . ' ' .  $second_sql;
      //$sql .= $whereSql;
      var_dump($sql);
      $db = $this->dbConnect();
      $stmt = $db->prepare($sql);
      $this->tagBinds($tag_binds, $stmt);
      $this->queryPost($stmt);
    }
  }

  //exit;
  // カテゴリーのみが入力されている条件
  public function categorySearch()
  {
    if (!empty($_GET['category'] && empty($_GET['search']) && empty($_GET['tags']))) {
      $sql = 'SELECT * FROM posts 
              LEFT JOIN post_category 
              ON posts.id = post_category.post_id
              LEFT JOIN categories
              ON categories.id = post_category.category_id
              WHERE categories.category = :category';

      var_dump($sql);
      $db = $this->dbConnect();
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':category', $_GET["category"], PDO::PARAM_STR);
      $this->queryPost($stmt);
    }
  }


  public function textSearch()
  {
    if (!empty($_GET['search']) && empty($_GET['category']) && empty($_GET['tags'])) {
      $sql = 'SELECT * FROM posts 
              WHERE posts.title LIKE :title OR posts.detail LIKE :detail';

      $db = $this->dbConnect();
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':title', '%' . $_GET["search"] . '%', PDO::PARAM_STR);
      $stmt->bindValue(':detail', '%' . $_GET["search"] . '%', PDO::PARAM_STR);
      $this->queryPost($stmt);
    }
  }

  public function categoryTextSearch()
  {
    if (!empty($_GET['category'] && $_GET['search']) && empty($_GET['tags'])) {
      $sql = 'SELECT distinct posts.* FROM posts 
              LEFT JOIN post_category 
              ON posts.id = post_category.post_id
              LEFT JOIN categories
              ON categories.id = post_category.category_id
              WHERE categories.category = :category
              AND (posts.title like :title 
              OR posts.detail like :detail)';

      var_dump($sql);
      $db = $this->dbConnect();
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':category', $_GET['category'], PDO::PARAM_STR);
      $stmt->bindValue(':title', '%' . $_GET['search'] . '%', PDO::PARAM_STR);
      $stmt->bindValue(':detail', '%' . $_GET['search'] . '%', PDO::PARAM_STR);
      //var_dump($stmt); 
      $this->queryPost($stmt);
    }
  }

  public function AllNotSearch()
  {
    if (empty($_GET['search']) && empty($_GET['category']) && empty($_GET['tags'])) {
      echo '結果は０件です';
    }
  }

  private function tagWhere()
  {
    $tag_where = [];
    $tag_binds = [];
    foreach ($_GET['tags'] as $key =>  $tag) {
      $tag_where[] = ":tag" . $key;
      $tag_binds[":tag" . $key] = $tag;
    }
    $whereSql = implode(' , ', $tag_where);
    var_dump($whereSql);
    return [$tag_binds, $whereSql];
  }

  private function tagBinds($tag_binds, $stmt)
  {
    foreach ($tag_binds as $key => $val) {
      var_dump($key);
      $stmt->bindValue($key, $val, PDO::PARAM_STR);
    }
  }
}
