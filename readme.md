# 機能一覧

- CUR 機能 + 投稿の際にタグを紐付け
- 認証
- マイページ機能
- アーカイブ機能 => アーカイブした記事はマイページから復元できます。
- XSS,CSRF(まだいいね機能とサインアップのみ),SQL インジェクション対策
- オブジェクト指向の導入(抽象クラスとインターフェース、static キーワード、トレイトは無理矢理ですが勉強用に導入してみました)
- タグ検索＋カテゴリー検索＋テキストフォームでの絞り込み検索
- フォロー機能
- いいね機能(トップページは PHP のみ、マイページは ajax を導入して非同期処理)
- 管理ログイン＋管理画面からユーザーがいいねした記事を取得できる

# ディレクトリ構成

- Api
  - 非同期処理の利用した機能のバックエンド
- auth
  - 認証系に関しては画面ファイルも実行処理もここに入っている
- Models
  - クラス一覧
- public
  - CSS,JSFile
- Cntroller
  - 主に Models で実装した処理を実際に実行しているファイル
- db
  - データベース、テーブル、テストデータがまとまっている
- images
  - 画像ファイル
- views
  - 主に画面を表示している

# 目標

- リレーション、関数、オブジェクト指向に慣れる

# 得た知見

- 全く関数化していなかったコードを関数化してその後、オブジェクト指向を導入

  - => 初めのディレクトリ構成や クラス、DB の設計が後の生産性に大きく関与してくることを身をもって経験した
  - => 抽象クラスやインターフェース、トレイトを少し理解したことによって vendor 内のコードを深ぼれるようになった。

- いいね機能やフォロー機能、検索機能

  - n:n のテーブルの関係を理解

- アーカイブ機能
  - 論理削除を初めて使用

# 改善

1. 名前空間とオートロードを導入して、app から呼び出す
2. PHPUnit 導入
