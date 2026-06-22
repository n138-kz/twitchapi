# README

## Refs Projects

- [n138-kz/twitchapi](https://github.com/n138-kz/twitchapi)
- [n138-kz/obswebsocket_screenshot](https://github.com/n138-kz/obswebsocket_screenshot)(example)

## ライブラリインストール

```sh
python -m pip install -r requirements.txt
```

## 参考情報

- [Twitch APIをPythonで利用する（備忘録）@kuonraku0210(久遠 楽)](https://qiita.com/kuonraku0210/items/aaf4e69d3851a0dab471)

## 実行順序

1. `.secret` に認証情報を記載する
2. 依存ライブラリインストール
3. RUN `python getTwitchStreamTitle.py` → 配信タイトル取得
4. RUN `python setTextOBSItem.py`
