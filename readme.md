# 入れたい機能

1. 画像複数アップロード機能
2. ログインしないユーザーも閲覧できる全件表示機能(タイムライン)
3. ログインしたユーザーが投稿した記事のみを表示する機能

## カテゴリー検索機能について

posts テーブルにナルトがあったとして、
アニメをセレクトした時に、ナルトを出したい、映画をセレクトした時も、ナルトを出したい

2 つのテーブルで紐付けしようとした時の問題点
gategories.id=1 の映画が posts.category_id=1 のナルトと紐付いているとする。
もしこの状況でナルトを categories.id=2 のアニメと紐付けたい場合、詰む。
無理やりやろうとすれば、2 つめの posts.cattegory_id=2 のナルトを作らないといけない。
対処法として post_category という中間テーブルを作成する。これによって変更する場合はカテゴリーを書き換えるだけで良い
