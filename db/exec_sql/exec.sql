-- カテゴリー検索の未完成例
SELECT posts.* , categories.*
from categories
 inner join posts
 on categories.id = posts.category_id
where posts.category_id=2;

-- 3つのテーブルの結合　カテゴリー検索機能
SELECT
 *
FROM
 posts
 LEFT JOIN
 post_category
 ON
posts.id = post_category.post_id
 LEFT JOIN
 categories
 ON
categories.id = post_category.category_id
WHERE
categories.category='音楽';
--posts.id=3;
--categories.id=1;

SELECT *
from posts
where title='ナルト' or detail='アニメと映画';

-- Narutoを取得、曖昧検索
SELECT *
FROM posts
where  posts.title like '%N%' or posts.detail like '%N%';

-- ナルトが取得できた。
SELECT *
FROM posts
 LEFT JOIN post_category
 ON posts.id = post_category.post_id
 LEFT JOIN categories
 ON categories.id = post_category.category_id
WHERE categories.category = 'アニメ' and posts.title like '%N%';

-- テキストフォームカテゴリー